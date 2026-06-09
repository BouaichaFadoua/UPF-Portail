<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function __construct(private ChatbotService $chatbot) {}

    /**
     * Serve the chatbot page inside the student space.
     */
    public function index()
    {
        return view('etudiant.chatbot.index');
    }

    /**
     * POST /etudiant/chatbot/message
     * Handles chat message from authenticated student.
     */
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        // Security: only authenticated students may use this endpoint
        if (!Auth::check() || Auth::user()->role !== 'etudiant') {
            return response()->json([
                'reply' => "Accès non autorisé. Seuls les étudiants peuvent interagir avec l'assistant."
            ], 403);
        }

        $userId = Auth::id();

        try {
            $reply = $this->chatbot->chat($request->input('message'), $userId);
            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ChatbotController Error: ' . $e->getMessage());

            // Provide a user-friendly natural language response for configuration issues
            if (str_contains($e->getMessage(), "clé API") || str_contains($e->getMessage(), "API key")) {
                return response()->json([
                    'reply' => "Désolé, le chatbot universitaire n'est pas actif pour le moment car sa clé API de configuration est manquante ou incorrecte."
                ]);
            }

            return response()->json([
                'reply' => "Une erreur est survenue lors de la communication avec l'assistant. Veuillez réessayer ultérieurement."
            ]);
        }
    }
}
