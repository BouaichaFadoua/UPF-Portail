<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Seance;
use App\Models\Module;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    // ─── Keywords that trigger a DB lookup ───────────────────────────────────
    private const DB_KEYWORDS = [
        // Français
        'note', 'notes', 'moyenne', 'moyennes', 'résultat', 'résultats',
        'absence', 'absences', 'absent', 'manqué', 'raté',
        'module', 'modules', 'cours', 'matière',
        'emploi', 'emploi du temps', 'planning', 'horaire', 'séance', 'séances',
        'bulletin', 'relevé', 'mention', 'cc1', 'cc2', 'examen',
        'filière', 'groupe', 'semestre',

        // Anglais
        'grade', 'grades', 'score', 'result', 'results',
        'schedule', 'timetable', 'class', 'session',
        'attendance', 'absent',
        'exam', 'midterm', 'gpa', 'academic',

        // Arabe (translitéré)
        'درجة', 'نقطة', 'غياب', 'حضور', 'مادة', 'وحدة', 'مقرر',
        'جدول', 'توقit', 'نتائج', 'نتيجة',
    ];

    // ─── Public entry point ───────────────────────────────────────────────────
    public function chat(string $message, int $userId): string
    {
        $message = trim($message);

        // Check if config has a mock key or is empty
        $apiKey = config('services.openai.key');
        $isMockKey = empty($apiKey) || str_contains($apiKey, 'xxxx') || $apiKey === 'your-openai-api-key-here';

        if ($isMockKey) {
            Log::info('ChatbotService running in Mock Demonstration Mode due to placeholder API key.');
            if ($this->isDbQuestion($message)) {
                $etudiant = Etudiant::with(['user', 'filiere', 'groupe'])
                    ->where('user_id', $userId)
                    ->first();

                if (!$etudiant) {
                    return "Je n'ai pas trouvé votre profil étudiant. Veuillez contacter l'administration.";
                }

                $context = $this->buildStudentContext($etudiant);
                return $this->generateMockResponse($message, $context);
            }
            return $this->generateMockResponse($message, null);
        }

        if ($this->isDbQuestion($message)) {
            return $this->handleDbQuestion($message, $userId);
        }

        return $this->handleGeneralQuestion($message);
    }

    // ─── Router ───────────────────────────────────────────────────────────────
    private function isDbQuestion(string $message): bool
    {
        $lower = mb_strtolower($message);

        foreach (self::DB_KEYWORDS as $keyword) {
            if (str_contains($lower, mb_strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    // ─── DB Question Handler ──────────────────────────────────────────────────
    private function handleDbQuestion(string $message, int $userId): string
    {
        // Load the student profile securely
        $etudiant = Etudiant::with(['user', 'filiere', 'groupe'])
            ->where('user_id', $userId)
            ->first();

        if (!$etudiant) {
            return "Je n'ai pas trouvé votre profil étudiant. Veuillez contacter l'administration.";
        }

        // Build structured JSON context
        $context = $this->buildStudentContext($etudiant);

        // Build system prompt (strict, as required)
        $systemPrompt = "You are a university assistant chatbot. You must answer in a clear, human, and natural language. Use ONLY the provided student data. If something is missing, say you don't have enough information.";

        $userMessage = "Student data context (JSON):\n{$context}\n\nQuestion: {$message}";

        return $this->callOpenAI($systemPrompt, $userMessage);
    }

    // ─── General Question Handler ─────────────────────────────────────────────
    private function handleGeneralQuestion(string $message): string
    {
        $systemPrompt = "You are a university assistant chatbot. You must answer in a clear, human, and natural language.";

        return $this->callOpenAI($systemPrompt, $message);
    }

    // ─── Build Student Context ────────────────────────────────────────────────
    private function buildStudentContext(Etudiant $etudiant): string
    {
        // 1. Identity
        $studentData = [
            'name' => $etudiant->user->name ?? 'N/A',
            'matricule' => $etudiant->matricule ?? 'N/A',
            'filiere' => $etudiant->filiere->nom ?? 'N/A',
        ];

        // 2. Notes
        $notesData = [];
        $notes = Note::with('module')
            ->where('etudiant_id', $etudiant->id)
            ->get();
        
        foreach ($notes as $note) {
            $notesData[] = [
                'module' => $note->module->nom ?? 'Module inconnu',
                'cc1' => $note->cc1 !== null ? (float)$note->cc1 : null,
                'cc2' => $note->cc2 !== null ? (float)$note->cc2 : null,
                'exam' => $note->examen !== null ? (float)$note->examen : null,
                'moyenne' => $note->note_finale !== null ? (float)$note->note_finale : null,
            ];
        }

        // 3. Absences
        $absencesData = [];
        $absences = Absence::with(['seance.module'])
            ->where('etudiant_id', $etudiant->id)
            ->get();
        
        $absByModule = $absences->groupBy(fn($a) => $a->seance->module->nom ?? 'Module inconnu');
        foreach ($absByModule as $moduleName => $abs) {
            $absencesData[] = [
                'module' => $moduleName,
                'count' => $abs->count(),
            ];
        }

        // 4. Schedule (emploi du temps for this week)
        $scheduleData = [];
        $seances = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('date', '>=', now()->startOfWeek())
            ->where('date', '<=', now()->endOfWeek())
            ->orderBy('date')
            ->orderBy('heure_debut')
            ->get();
        
        foreach ($seances as $s) {
            $scheduleData[] = [
                'date' => $s->date->format('Y-m-d'),
                'day' => $this->frenchDay($s->date->dayOfWeek),
                'start_time' => substr($s->heure_debut, 0, 5),
                'end_time' => substr($s->heure_fin, 0, 5),
                'module' => $s->module->nom ?? 'N/A',
                'type' => $s->type,
                'room' => $s->salle->nom ?? 'N/A',
                'teacher' => $s->professeur->user->name ?? 'N/A',
            ];
        }

        // 5. Modules
        $modulesData = Module::whereHas('seances', fn($q) => $q->where('groupe_id', $etudiant->groupe_id))
            ->pluck('nom')
            ->toArray();

        $contextData = [
            'student' => $studentData,
            'notes' => $notesData,
            'absences' => $absencesData,
            'schedule' => $scheduleData,
            'modules' => $modulesData,
        ];

        return json_encode($contextData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // ─── OpenAI Caller ───────────────────────────────────────────────────────
    private function callOpenAI(string $systemPrompt, string $userMessage): string
    {
        $apiKey = config('services.openai.key');

        Log::info('ChatbotService runtime API key check:', [
            'key_present' => !empty($apiKey),
            'key_length' => strlen($apiKey),
            'key_prefix' => substr($apiKey, 0, 8),
        ]);

        if (empty($apiKey)) {
            throw new \RuntimeException("La clé API OpenAI n'est pas configurée.");
        }

        try {
            $factory = \OpenAI::factory()->withApiKey($apiKey);

            // Bypass SSL verification in local development to avoid Windows curl error 60
            if (config('app.env') === 'local') {
                $guzzleClient = new \GuzzleHttp\Client([
                    'verify' => false,
                ]);
                $factory = $factory->withHttpClient($guzzleClient);
            }

            $client = $factory->make();

            $response = $client->chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.4,
                'max_tokens'  => 800,
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userMessage],
                ],
            ]);

            return $response->choices[0]->message->content ?? "Je n'ai pas pu générer une réponse.";

        } catch (\Exception $e) {
            Log::error('ChatbotService OpenAI error: ' . $e->getMessage());
            throw new \RuntimeException("Erreur lors de la communication avec l'assistant : " . $e->getMessage(), 0, $e);
        }
    }

    // ─── Mock Response Generator for testing ──────────────────────────────────
    private function generateMockResponse(string $message, ?string $contextJson): string
    {
        $lower = mb_strtolower($message);
        
        if (!$contextJson) {
            // General question
            if (str_contains($lower, 'bonjour') || str_contains($lower, 'salut') || str_contains($lower, 'hello')) {
                return "Bonjour ! Je suis votre assistant universitaire UPF. Comment puis-je vous aider aujourd'hui ? (Mode Démonstration)";
            }
            return "Je suis en mode démonstration car aucune clé API OpenAI valide n'est configurée. Posez-moi des questions sur vos notes, absences ou emploi du temps pour voir l'interaction avec la base de données !";
        }

        $data = json_decode($contextJson, true);
        $studentName = $data['student']['name'] ?? 'Étudiant';

        // 1. Notes / Grades query
        if (str_contains($lower, 'note') || str_contains($lower, 'moyenne') || str_contains($lower, 'résultat') || str_contains($lower, 'grade')) {
            if (empty($data['notes'])) {
                return "Bonjour {$studentName}, d'après nos dossiers, vous n'avez pas encore de notes enregistrées pour l'année en cours.";
            }

            $response = "Bonjour **{$studentName}** ! Voici le récapitulatif de vos notes et résultats :\n\n";
            $totalMoyenne = 0;
            $count = 0;
            foreach ($data['notes'] as $n) {
                $moyenneStr = $n['moyenne'] !== null ? $n['moyenne'] . "/20" : "Non calculée";
                $response .= "• **{$n['module']}** : CC1 = " . ($n['cc1'] ?? 'N/S') . ", CC2 = " . ($n['cc2'] ?? 'N/S') . ", Examen = " . ($n['exam'] ?? 'N/S') . " → **Moyenne : {$moyenneStr}**\n";
                if ($n['moyenne'] !== null) {
                    $totalMoyenne += $n['moyenne'];
                    $count++;
                }
            }
            if ($count > 0) {
                $gen = round($totalMoyenne / $count, 2);
                $response .= "\n📚 **Moyenne générale** : {$gen}/20";
            }
            return $response;
        }

        // 2. Absences query
        if (str_contains($lower, 'absence') || str_contains($lower, 'absent') || str_contains($lower, 'manqué')) {
            if (empty($data['absences'])) {
                return "Excellente nouvelle **{$studentName}** ! Vous n'avez aucune absence enregistrée dans notre système.";
            }

            $response = "Bonjour **{$studentName}**. Voici le relevé de vos absences par matière :\n\n";
            foreach ($data['absences'] as $a) {
                $response .= "• **{$a['module']}** : {$a['count']} absence(s)\n";
            }
            return $response;
        }

        // 3. Schedule / Emploi du temps query
        if (str_contains($lower, 'emploi') || str_contains($lower, 'planning') || str_contains($lower, 'horaire') || str_contains($lower, 'séance') || str_contains($lower, 'cours') || str_contains($lower, 'schedule') || str_contains($lower, 'temps')) {
            if (empty($data['schedule'])) {
                return "Bonjour **{$studentName}**. Aucun cours n'est planifié pour votre groupe cette semaine.";
            }

            $response = "Bonjour **{$studentName}** ! Voici votre emploi du temps pour cette semaine :\n\n";
            foreach ($data['schedule'] as $s) {
                $response .= "• **{$s['day']} {$s['date']}** | {$s['start_time']}–{$s['end_time']} | **{$s['module']}** ({$s['type']}) | Salle : *{$s['room']}* | Prof : *{$s['teacher']}*\n";
            }
            return $response;
        }

        // 4. Modules query
        if (str_contains($lower, 'module') || str_contains($lower, 'matière')) {
            if (empty($data['modules'])) {
                return "Bonjour **{$studentName}**. Vous n'êtes inscrit à aucun module pour le moment.";
            }
            return "Bonjour **{$studentName}** ! Vous suivez actuellement les modules suivants : " . implode(', ', $data['modules']) . ".";
        }

        // Fallback for other academic questions with context
        return "Bonjour **{$studentName}** ! J'ai bien accès à vos données universitaires sécurisées (Profil, Notes, Absences, Emploi du temps), mais ma clé API OpenAI n'étant pas configurée, je ne peux pas traiter les questions libres complexes. Posez-moi des questions directes sur vos notes, absences ou emploi du temps pour voir le récapitulatif !";
    }

    // ─── Helper ──────────────────────────────────────────────────────────────
    private function frenchDay(int $dow): string
    {
        return ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'][$dow] ?? '';
    }
}
