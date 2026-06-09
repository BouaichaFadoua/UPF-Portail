<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Personnel;
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\Note;
use App\Models\Absence;
use App\Models\Justificatif;
use App\Models\Reservation;
use App\Models\CahierTexte;
use App\Models\Annonce;
use App\Models\Support;
use App\Models\Commentaire;
use App\Models\Demande;
use App\Models\Document;
use App\Models\NotificationUpf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN
        $admin = User::create([
            'name' => 'Administration UPF',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'actif' => true,
        ]);

        // 2. PROFESSEURS
        $prof1User = User::create([
            'name' => 'Prof. Ahmed Alami',
            'email' => 'prof.alami@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROFESSEUR,
            'actif' => true,
        ]);
        $prof1 = Professeur::create([
            'user_id' => $prof1User->id,
            'grade' => 'PES',
            'specialite' => 'Informatique / Web',
            'telephone' => '+213 550 11 22 33',
            'bureau' => 'Bloc A, Bureau 102',
        ]);

        $prof2User = User::create([
            'name' => 'Prof. Fatime Bennani',
            'email' => 'prof.bennani@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROFESSEUR,
            'actif' => true,
        ]);
        $prof2 = Professeur::create([
            'user_id' => $prof2User->id,
            'grade' => 'PH',
            'specialite' => 'Management & Marketing',
            'telephone' => '+213 661 44 55 66',
            'bureau' => 'Bloc C, Bureau 204',
        ]);

        $prof3User = User::create([
            'name' => 'Prof. Khalid Idrissi',
            'email' => 'prof.idrissi@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROFESSEUR,
            'actif' => true,
        ]);
        $prof3 = Professeur::create([
            'user_id' => $prof3User->id,
            'grade' => 'PA',
            'specialite' => 'Droit des Affaires',
            'telephone' => '+213 770 77 88 99',
            'bureau' => 'Bloc B, Bureau 05',
        ]);

        $prof4User = User::create([
            'name' => 'Prof. Leyla Haddad',
            'email' => 'prof.haddad@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROFESSEUR,
            'actif' => true,
        ]);
        $prof4 = Professeur::create([
            'user_id' => $prof4User->id,
            'grade' => 'PES',
            'specialite' => 'Algorithmique & Structures de Données',
            'telephone' => '+213 550 44 33 22',
            'bureau' => 'Bloc A, Bureau 105',
        ]);

        $prof5User = User::create([
            'name' => 'Prof. Youssef Mansouri',
            'email' => 'prof.mansouri@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PROFESSEUR,
            'actif' => true,
        ]);
        $prof5 = Professeur::create([
            'user_id' => $prof5User->id,
            'grade' => 'PA',
            'specialite' => 'Économie & Statistique',
            'telephone' => '+213 661 99 88 77',
            'bureau' => 'Bloc C, Bureau 110',
        ]);

        // 2b. PERSONNEL
        $personnelUser = User::create([
            'name' => 'Sara Benali',
            'email' => 'personnel@gmail.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PERSONNEL,
            'actif' => true,
        ]);
        Personnel::create([
            'user_id' => $personnelUser->id,
            'poste' => 'Agent de scolarité',
            'service' => 'Service Scolarité',
            'telephone' => '+213 555 12 34 56',
        ]);

        // 3. FILIERES
        $filiereGI = Filiere::create([
            'nom' => 'Génie Informatique',
            'code' => 'GI',
            'niveau' => 'Master',
            'departement' => 'Informatique',
        ]);

        $filiereME = Filiere::create([
            'nom' => 'Management des Entreprises',
            'code' => 'ME',
            'niveau' => 'Licence',
            'departement' => 'Management',
        ]);

        $filiereDA = Filiere::create([
            'nom' => 'Droit des Affaires',
            'code' => 'DA',
            'niveau' => 'Master',
            'departement' => 'Droit',
        ]);

        $filiereTI = Filiere::create([
            'nom' => 'Technologies de l\'Information',
            'code' => 'TI',
            'niveau' => 'Licence',
            'departement' => 'Informatique',
        ]);

        $filiereFE = Filiere::create([
            'nom' => 'Finance d\'Entreprise',
            'code' => 'FE',
            'niveau' => 'Master',
            'departement' => 'Management',
        ]);

        // 4. GROUPES
        $groupeGI1 = Groupe::create([
            'filiere_id' => $filiereGI->id,
            'nom' => 'GI - Groupe 1',
            'capacite' => 30,
        ]);
        $groupeGI2 = Groupe::create([
            'filiere_id' => $filiereGI->id,
            'nom' => 'GI - Groupe 2',
            'capacite' => 30,
        ]);
        $groupeME1 = Groupe::create([
            'filiere_id' => $filiereME->id,
            'nom' => 'ME - Groupe 1',
            'capacite' => 40,
        ]);
        $groupeDA1 = Groupe::create([
            'filiere_id' => $filiereDA->id,
            'nom' => 'DA - Groupe 1',
            'capacite' => 25,
        ]);
        $groupeTI1 = Groupe::create([
            'filiere_id' => $filiereTI->id,
            'nom' => 'TI - Groupe 1',
            'capacite' => 30,
        ]);

        // 5. ETUDIANTS
        $etudiantNames = [
            ['Yassine Kamal', 'etudiant1@gmail.com', 'ET001', $filiereGI->id, $groupeGI1->id],
            ['Meriem Toumi', 'etudiant2@gmail.com', 'ET002', $filiereGI->id, $groupeGI1->id],
            ['Anass Rami', 'etudiant3@gmail.com', 'ET003', $filiereME->id, $groupeME1->id],
            ['Salma Alaoui', 'etudiant4@gmail.com', 'ET004', $filiereDA->id, $groupeDA1->id],
            ['Amine Kouris', 'etudiant5@gmail.com', 'ET005', $filiereGI->id, $groupeGI2->id],
        ];

        $etudiants = [];
        foreach ($etudiantNames as $idx => $data) {
            $user = User::create([
                'name' => $data[0],
                'email' => $data[1],
                'password' => Hash::make('password'),
                'role' => User::ROLE_ETUDIANT,
                'actif' => true,
            ]);

            $etudiants[] = Etudiant::create([
                'user_id' => $user->id,
                'filiere_id' => $data[3],
                'groupe_id' => $data[4],
                'matricule' => $data[2],
                'date_naissance' => Carbon::now()->subYears(20 + $idx)->format('Y-m-d'),
                'lieu_naissance' => 'Alger',
                'telephone' => '+213 555 00 99 0' . $idx,
                'adresse' => 'Alger, Algérie',
                'annee_universitaire' => '2024-2025',
            ]);
        }

        // 6. SALLES
        $salle1 = Salle::create([
            'nom' => 'Amphi A',
            'capacite' => 150,
            'type' => 'amphitheatre',
            'batiment' => 'Batiment A',
            'disponible' => true,
        ]);
        $salle2 = Salle::create([
            'nom' => 'Salle 101 (TD)',
            'capacite' => 40,
            'type' => 'td',
            'batiment' => 'Batiment B',
            'disponible' => true,
        ]);
        $salle3 = Salle::create([
            'nom' => 'Labo Informatique 1',
            'capacite' => 30,
            'type' => 'tp',
            'batiment' => 'Batiment C',
            'disponible' => true,
        ]);
        $salle4 = Salle::create([
            'nom' => 'Salle 102 (TD)',
            'capacite' => 35,
            'type' => 'td',
            'batiment' => 'Batiment B',
            'disponible' => true,
        ]);
        $salle5 = Salle::create([
            'nom' => 'Salle de Visioconférence',
            'capacite' => 20,
            'type' => 'td',
            'batiment' => 'Bibliothèque',
            'disponible' => true,
        ]);

        // 7. MODULES
        $modWeb = Module::create([
            'filiere_id' => $filiereGI->id,
            'professeur_id' => $prof1->id,
            'nom' => 'Programmation Web PHP',
            'code' => 'GI-301',
            'coefficient' => 4,
            'semestre' => 1,
            'volume_horaire' => 45,
        ]);

        $modDB = Module::create([
            'filiere_id' => $filiereGI->id,
            'professeur_id' => $prof1->id,
            'nom' => 'Bases de Données Relationnelles',
            'code' => 'GI-302',
            'coefficient' => 3,
            'semestre' => 1,
            'volume_horaire' => 40,
        ]);

        $modMgmt = Module::create([
            'filiere_id' => $filiereME->id,
            'professeur_id' => $prof2->id,
            'nom' => 'Management Stratégique',
            'code' => 'ME-101',
            'coefficient' => 3,
            'semestre' => 1,
            'volume_horaire' => 30,
        ]);

        $modDroit = Module::create([
            'filiere_id' => $filiereDA->id,
            'professeur_id' => $prof3->id,
            'nom' => 'Droit des Affaires Internationales',
            'code' => 'DA-201',
            'coefficient' => 2,
            'semestre' => 1,
            'volume_horaire' => 30,
        ]);

        $modAlgo = Module::create([
            'filiere_id' => $filiereGI->id,
            'professeur_id' => $prof4->id,
            'nom' => 'Algorithmique Avancée',
            'code' => 'GI-303',
            'coefficient' => 4,
            'semestre' => 1,
            'volume_horaire' => 45,
        ]);

        // 8. SEANCES (Emploi du Temps)
        $today = Carbon::today();
        $monday = $today->copy()->startOfWeek();

        // Seance 1: Web PHP GI - Groupe 1, Monday 08:30 - 10:30, Salle 101, Prof 1
        $seance1 = Seance::create([
            'module_id' => $modWeb->id,
            'groupe_id' => $groupeGI1->id,
            'salle_id' => $salle2->id,
            'professeur_id' => $prof1->id,
            'date' => $monday->copy()->format('Y-m-d'),
            'heure_debut' => '08:30:00',
            'heure_fin' => '10:30:00',
            'type' => 'Cours',
            'semaine' => $monday->weekOfYear,
        ]);

        // Seance 2: Web PHP GI - Groupe 1, Monday 10:45 - 12:45, Labo Info 1, Prof 1
        $seance2 = Seance::create([
            'module_id' => $modWeb->id,
            'groupe_id' => $groupeGI1->id,
            'salle_id' => $salle3->id,
            'professeur_id' => $prof1->id,
            'date' => $monday->copy()->format('Y-m-d'),
            'heure_debut' => '10:45:00',
            'heure_fin' => '12:45:00',
            'type' => 'TP',
            'semaine' => $monday->weekOfYear,
        ]);

        // Seance 3: DB GI - Groupe 1, Tuesday 08:30 - 10:30, Salle 102, Prof 1
        $seance3 = Seance::create([
            'module_id' => $modDB->id,
            'groupe_id' => $groupeGI1->id,
            'salle_id' => $salle4->id,
            'professeur_id' => $prof1->id,
            'date' => $monday->copy()->addDay()->format('Y-m-d'),
            'heure_debut' => '08:30:00',
            'heure_fin' => '10:30:00',
            'type' => 'Cours',
            'semaine' => $monday->weekOfYear,
        ]);

        // Seance 4: Management ME - Groupe 1, Tuesday 14:00 - 16:00, Amphi A, Prof 2
        $seance4 = Seance::create([
            'module_id' => $modMgmt->id,
            'groupe_id' => $groupeME1->id,
            'salle_id' => $salle1->id,
            'professeur_id' => $prof2->id,
            'date' => $monday->copy()->addDay()->format('Y-m-d'),
            'heure_debut' => '14:00:00',
            'heure_fin' => '16:00:00',
            'type' => 'Cours',
            'semaine' => $monday->weekOfYear,
        ]);

        // Seance 5: Droit DA - Groupe 1, Wednesday 10:45 - 12:45, Salle 101, Prof 3
        $seance5 = Seance::create([
            'module_id' => $modDroit->id,
            'groupe_id' => $groupeDA1->id,
            'salle_id' => $salle2->id,
            'professeur_id' => $prof3->id,
            'date' => $monday->copy()->addDays(2)->format('Y-m-d'),
            'heure_debut' => '10:45:00',
            'heure_fin' => '12:45:00',
            'type' => 'Cours',
            'semaine' => $monday->weekOfYear,
        ]);

        // 9. NOTES
        // Student 1 (Yassine Kamal) - GI
        Note::create([
            'etudiant_id' => $etudiants[0]->id,
            'module_id' => $modWeb->id,
            'cc1' => 14.5,
            'cc2' => 15.0,
            'examen' => 16.0,
            'annee_universitaire' => '2024-2025',
        ]);
        Note::create([
            'etudiant_id' => $etudiants[0]->id,
            'module_id' => $modDB->id,
            'cc1' => 12.0,
            'cc2' => 11.5,
            'examen' => 13.0,
            'annee_universitaire' => '2024-2025',
        ]);

        // Student 2 (Meriem Toumi) - GI
        Note::create([
            'etudiant_id' => $etudiants[1]->id,
            'module_id' => $modWeb->id,
            'cc1' => 16.0,
            'cc2' => 17.5,
            'examen' => 18.0,
            'annee_universitaire' => '2024-2025',
        ]);

        // Student 3 (Anass Rami) - ME
        Note::create([
            'etudiant_id' => $etudiants[2]->id,
            'module_id' => $modMgmt->id,
            'cc1' => 10.0,
            'cc2' => 12.0,
            'examen' => 11.0,
            'annee_universitaire' => '2024-2025',
        ]);

        // Student 4 (Salma Alaoui) - DA
        Note::create([
            'etudiant_id' => $etudiants[3]->id,
            'module_id' => $modDroit->id,
            'cc1' => 13.5,
            'cc2' => 14.0,
            'examen' => 12.5,
            'annee_universitaire' => '2024-2025',
        ]);

        // 10. ABSENCES & JUSTIFICATIFS
        // Student 1 (Yassine) was absent on Seance 1
        $absence1 = Absence::create([
            'etudiant_id' => $etudiants[0]->id,
            'seance_id' => $seance1->id,
            'justifiee' => false,
            'motif' => 'Non justifiée initialement',
        ]);

        // Student 2 (Meriem) was absent on Seance 1, but justified
        $absence2 = Absence::create([
            'etudiant_id' => $etudiants[1]->id,
            'seance_id' => $seance1->id,
            'justifiee' => true,
            'motif' => 'Rendez-vous médical',
        ]);
        Justificatif::create([
            'absence_id' => $absence2->id,
            'etudiant_id' => $etudiants[1]->id,
            'fichier_path' => 'justificatifs/certificat_medical.pdf',
            'fichier_nom' => 'Certificat Médical Meriem.pdf',
            'statut' => 'valide',
            'traite_par' => $admin->id,
            'traite_le' => Carbon::now(),
        ]);

        // Student 1 (Yassine) submitted a justificatif which is en_attente
        $absence3 = Absence::create([
            'etudiant_id' => $etudiants[0]->id,
            'seance_id' => $seance3->id,
            'justifiee' => false,
            'motif' => 'En attente de validation',
        ]);
        Justificatif::create([
            'absence_id' => $absence3->id,
            'etudiant_id' => $etudiants[0]->id,
            'fichier_path' => 'justificatifs/justif_kamal.pdf',
            'fichier_nom' => 'Justificatif_Yassine_Kamal.pdf',
            'statut' => 'en_attente',
        ]);

        // 11. RESERVATIONS
        Reservation::create([
            'professeur_id' => $prof1->id,
            'salle_id' => $salle1->id, // Amphi A
            'date' => $monday->copy()->addDays(3)->format('Y-m-d'), // Thursday
            'heure_debut' => '14:00:00',
            'heure_fin' => '16:00:00',
            'motif' => 'Conférence programmation web avancée',
            'statut' => 'approuvee',
            'traite_par' => $admin->id,
        ]);

        Reservation::create([
            'professeur_id' => $prof2->id,
            'salle_id' => $salle3->id, // Labo 1
            'date' => $monday->copy()->addDays(4)->format('Y-m-d'), // Friday
            'heure_debut' => '09:00:00',
            'heure_fin' => '11:00:00',
            'motif' => 'Atelier entrepreneuriat et simulation',
            'statut' => 'en_attente',
        ]);

        // 12. CAHIER TEXTES
        CahierTexte::create([
            'professeur_id' => $prof1->id,
            'module_id' => $modWeb->id,
            'seance_id' => $seance1->id,
            'date' => $seance1->date,
            'heure_debut' => $seance1->heure_debut,
            'heure_fin' => $seance1->heure_fin,
            'objectif' => 'Introduction à l\'architecture MVC et Laravel',
            'nature' => 'Cours',
            'contenu' => 'Présentation générale de l\'écosystème Laravel, installation via Composer, structure des dossiers et routing de base.',
        ]);

        // 13. ANNONCES & SUPPORTS & COMMENTAIRES
        $annonce1 = Annonce::create([
            'module_id' => $modWeb->id,
            'professeur_id' => $prof1->id,
            'titre' => 'Bienvenue dans le cours de Programmation Web PHP',
            'contenu' => 'Bonjour à tous, ce canal servira de support pour le dépôt de ressources, TP et annonces pour ce semestre. N\'hésitez pas à poser vos questions en commentaire.',
            'publiee' => true,
        ]);

        Commentaire::create([
            'annonce_id' => $annonce1->id,
            'user_id' => $etudiants[0]->user_id, // Yassine
            'contenu' => 'Merci professeur, est-ce que le support de cours de la séance 1 est disponible ?',
        ]);

        Commentaire::create([
            'annonce_id' => $annonce1->id,
            'user_id' => $prof1User->id, // Prof Alami
            'contenu' => 'Oui Yassine, je viens de l\'uploader dans la section supports.',
        ]);

        Support::create([
            'module_id' => $modWeb->id,
            'professeur_id' => $prof1->id,
            'titre' => 'Support de cours 1: Introduction à Laravel',
            'fichier_path' => 'supports/chapitre1_laravel.pdf',
            'fichier_nom' => 'Chapitre 1 - Laravel.pdf',
            'fichier_type' => 'application/pdf',
            'type' => 'Cours',
            'taille' => 1024 * 512, // 512 KB
        ]);

        Support::create([
            'module_id' => $modWeb->id,
            'professeur_id' => $prof1->id,
            'titre' => 'Sujet de TP 1: Prise en main du Routing et Controllers',
            'fichier_path' => 'supports/tp1_routing.pdf',
            'fichier_nom' => 'TP1_Routing_Laravel.pdf',
            'fichier_type' => 'application/pdf',
            'type' => 'TP',
            'taille' => 1024 * 256, // 256 KB
        ]);

        // 14. DEMANDES ADMINISTRATIVE & DOCUMENTS
        $demande1 = Demande::create([
            'user_id' => $etudiants[0]->user_id, // Student 1 (Yassine)
            'type' => 'attestation_scolarite',
            'statut' => 'validee',
            'traite_par' => $admin->id,
            'traite_le' => Carbon::now(),
        ]);
        Document::create([
            'demande_id' => $demande1->id,
            'fichier_path' => 'documents/attestation_scolarite_ET001.pdf',
            'fichier_nom' => 'Attestation_Scolarite_Kamal.pdf',
            'generated_at' => Carbon::now(),
        ]);

        $demande2 = Demande::create([
            'user_id' => $etudiants[1]->user_id, // Student 2 (Meriem)
            'type' => 'releve_notes',
            'statut' => 'en_attente',
        ]);

        $demande3 = Demande::create([
            'user_id' => $prof1User->id, // Prof Alami
            'type' => 'ordre_mission',
            'statut' => 'validee',
            'traite_par' => $admin->id,
            'traite_le' => Carbon::now(),
            'destination' => 'Oran, Algérie',
            'date_depart' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'date_retour' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'motif_mission' => 'Participation à la conférence nationale du web et de l\'innovation.',
        ]);

        // 15. NOTIFICATIONS
        NotificationUpf::create([
            'user_id' => $etudiants[0]->user_id,
            'titre' => 'Demande administrative traitée',
            'message' => 'Votre demande d\'attestation de scolarité a été validée. Vous pouvez télécharger le PDF.',
            'lien' => '/etudiant/demandes',
            'lu' => false,
            'type' => 'success',
        ]);

        NotificationUpf::create([
            'user_id' => $prof1User->id,
            'titre' => 'Réservation de salle approuvée',
            'message' => 'Votre réservation pour l\'Amphi A le ' . $monday->copy()->addDays(3)->format('d/m/Y') . ' a été approuvée.',
            'lien' => '/prof/reservations',
            'lu' => true,
            'type' => 'success',
        ]);
    }
}
