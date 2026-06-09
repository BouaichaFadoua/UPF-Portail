<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Shared routes
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.global');
    Route::get('/admin/demandes/{demande}/telecharger', [\App\Http\Controllers\Admin\DemandeController::class, 'telecharger'])->name('admin.demandes.telecharger');

    // Notifications (polling)
    Route::get('/notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{notification}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markRead');

    // Exports partagés
    Route::get('/admin/notes/export-excel', [\App\Http\Controllers\Admin\NoteController::class, 'exportExcel'])->name('admin.notes.export-excel');
    Route::get('/admin/notes/export-pdf', [\App\Http\Controllers\Admin\NoteController::class, 'exportPdf'])->name('admin.notes.export-pdf');
    Route::get('/admin/absences/export-excel', [\App\Http\Controllers\Admin\AbsenceController::class, 'exportExcel'])->name('admin.absences.export-excel');
    Route::get('/admin/absences/export-pdf', [\App\Http\Controllers\Admin\AbsenceController::class, 'exportPdf'])->name('admin.absences.export-pdf');

    // Profil / Sécurité du compte (partagé pour tous les rôles)
    Route::get('/profile/password', [\App\Http\Controllers\Auth\ProfileController::class, 'showPasswordForm'])->name('profile.password');
    Route::put('/profile/password', [\App\Http\Controllers\Auth\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Users CRUD
        Route::patch('/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::resource('/users', \App\Http\Controllers\Admin\UserController::class);

        // Filières CRUD
        Route::resource('/filieres', \App\Http\Controllers\Admin\FiliereController::class);

        // Modules CRUD
        Route::resource('/modules', \App\Http\Controllers\Admin\ModuleController::class);

        // Groupes CRUD
        Route::resource('/groupes', \App\Http\Controllers\Admin\GroupeController::class);

        // Salles CRUD
        Route::resource('/salles', \App\Http\Controllers\Admin\SalleController::class);

        // Notes CRUD & Export
        Route::get('/notes/export', [\App\Http\Controllers\Admin\NoteController::class, 'export'])->name('notes.export');
        Route::resource('/notes', \App\Http\Controllers\Admin\NoteController::class);

        // Timetable (EDT)
        Route::get('/edt', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'index'])->name('edt.index');
        Route::get('/edt/create', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'create'])->name('edt.create');
        Route::post('/edt', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'store'])->name('edt.store');
        Route::get('/edt/{seance}/edit', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'edit'])->name('edt.edit');
        Route::put('/edt/{seance}', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'update'])->name('edt.update');
        Route::delete('/edt/{seance}', [\App\Http\Controllers\Admin\EmploiDuTempsController::class, 'destroy'])->name('edt.destroy');

        // Demands
        Route::get('/demandes', [\App\Http\Controllers\Admin\DemandeController::class, 'index'])->name('demandes.index');
        Route::get('/demandes/{demande}', [\App\Http\Controllers\Admin\DemandeController::class, 'show'])->name('demandes.show');
        Route::post('/demandes/{demande}/valider', [\App\Http\Controllers\Admin\DemandeController::class, 'valider'])->name('demandes.valider');
        Route::post('/demandes/{demande}/refuser', [\App\Http\Controllers\Admin\DemandeController::class, 'refuser'])->name('demandes.refuser');


        // Reservations
        Route::get('/reservations', [\App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create', [\App\Http\Controllers\Admin\ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [\App\Http\Controllers\Admin\ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{reservation}/edit', [\App\Http\Controllers\Admin\ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{reservation}', [\App\Http\Controllers\Admin\ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/{reservation}', [\App\Http\Controllers\Admin\ReservationController::class, 'destroy'])->name('reservations.destroy');
        Route::post('/reservations/{reservation}/approuver', [\App\Http\Controllers\Admin\ReservationController::class, 'approuver'])->name('reservations.approuver');
        Route::post('/reservations/{reservation}/refuser', [\App\Http\Controllers\Admin\ReservationController::class, 'refuser'])->name('reservations.refuser');

        // Absences & Justifications
        Route::get('/absences', [\App\Http\Controllers\Admin\AbsenceController::class, 'index'])->name('absences.index');
        Route::get('/absences/etudiant/{etudiant}', [\App\Http\Controllers\Admin\AbsenceController::class, 'show'])->name('absences.show');
        Route::get('/absences/justificatifs', [\App\Http\Controllers\Admin\AbsenceController::class, 'justificatifs'])->name('absences.justificatifs');
        Route::post('/absences/justificatifs/{justificatif}/valider', [\App\Http\Controllers\Admin\AbsenceController::class, 'valider'])->name('absences.justificatifs.valider');
        Route::post('/absences/justificatifs/{justificatif}/refuser', [\App\Http\Controllers\Admin\AbsenceController::class, 'refuser'])->name('absences.justificatifs.refuser');
        Route::get('/absences/justificatifs/{justificatif}/telecharger', [\App\Http\Controllers\Admin\AbsenceController::class, 'telecharger'])->name('absences.justificatifs.telecharger');

        // Cahiers de Textes (Lecture)
        Route::get('/cahier-textes', [\App\Http\Controllers\Admin\CahierTexteController::class, 'index'])->name('cahier-textes.index');
    });

    // ==========================================
    // PROFESSEUR ROUTES
    // ==========================================
    Route::middleware('role:professeur')->prefix('prof')->name('prof.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Prof\DashboardController::class, 'index'])->name('dashboard');

        // Notes
        Route::get('/notes', [\App\Http\Controllers\Prof\NoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/{module}/{groupe}', [\App\Http\Controllers\Prof\NoteController::class, 'saisir'])->name('notes.saisir');
        Route::post('/notes/{module}/{groupe}', [\App\Http\Controllers\Prof\NoteController::class, 'enregistrer'])->name('notes.enregistrer');

        // Absences
        Route::get('/absences', [\App\Http\Controllers\Prof\AbsenceController::class, 'index'])->name('absences.index');
        Route::get('/absences/{seance}/appel', [\App\Http\Controllers\Prof\AbsenceController::class, 'appel'])->name('absences.appel');
        Route::post('/absences/{seance}/appel', [\App\Http\Controllers\Prof\AbsenceController::class, 'enregistrer'])->name('absences.enregistrer');

        // Cahier de textes
        Route::get('/cahier-textes', [\App\Http\Controllers\Prof\CahierTexteController::class, 'index'])->name('cahier-textes.index');
        Route::get('/cahier-textes/create', [\App\Http\Controllers\Prof\CahierTexteController::class, 'create'])->name('cahier-textes.create');
        Route::post('/cahier-textes', [\App\Http\Controllers\Prof\CahierTexteController::class, 'store'])->name('cahier-textes.store');

        // Cours & Classroom
        Route::get('/cours', [\App\Http\Controllers\Prof\CoursController::class, 'index'])->name('cours.index');
        Route::get('/cours/{module}', [\App\Http\Controllers\Prof\CoursController::class, 'show'])->name('cours.show');
        Route::post('/cours/{module}/annonce', [\App\Http\Controllers\Prof\CoursController::class, 'posterAnnonce'])->name('cours.annonce');
        Route::post('/cours/{module}/support', [\App\Http\Controllers\Prof\CoursController::class, 'uploaderSupport'])->name('cours.support');
        Route::delete('/cours/annonce/{annonce}', [\App\Http\Controllers\Prof\CoursController::class, 'supprimerAnnonce'])->name('cours.annonce.destroy');
        Route::delete('/cours/support/{support}', [\App\Http\Controllers\Prof\CoursController::class, 'supprimerSupport'])->name('cours.support.destroy');

        // Reservations
        Route::get('/reservations', [\App\Http\Controllers\Prof\ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create', [\App\Http\Controllers\Prof\ReservationController::class, 'create'])->name('reservations.create');
        Route::get('/reservations/salles-disponibles', [\App\Http\Controllers\Prof\ReservationController::class, 'sallesDisponibles'])->name('reservations.salles-disponibles');
        Route::post('/reservations', [\App\Http\Controllers\Prof\ReservationController::class, 'store'])->name('reservations.store');
        Route::post('/reservations/{reservation}/annuler', [\App\Http\Controllers\Prof\ReservationController::class, 'annuler'])->name('reservations.annuler');

        // Demandes administratives
        Route::get('/demandes', [\App\Http\Controllers\Prof\DemandeController::class, 'index'])->name('demandes.index');
        Route::get('/demandes/create', [\App\Http\Controllers\Prof\DemandeController::class, 'create'])->name('demandes.create');
        Route::post('/demandes', [\App\Http\Controllers\Prof\DemandeController::class, 'store'])->name('demandes.store');

        // EDT
        Route::get('/edt', [\App\Http\Controllers\Prof\EmploiDuTempsController::class, 'index'])->name('edt.index');
    });

    // ==========================================
    // ETUDIANT ROUTES
    // ==========================================
    Route::middleware('role:etudiant')->prefix('etudiant')->name('etudiant.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Etudiant\DashboardController::class, 'index'])->name('dashboard');

        // Notes
        Route::get('/notes', [\App\Http\Controllers\Etudiant\NoteController::class, 'index'])->name('notes.index');

        // Absences
        Route::get('/absences', [\App\Http\Controllers\Etudiant\AbsenceController::class, 'index'])->name('absences.index');
        Route::get('/absences/{absence}/justifier', [\App\Http\Controllers\Etudiant\AbsenceController::class, 'justifierForm'])->name('absences.justifier.form');
        Route::post('/absences/{absence}/justifier', [\App\Http\Controllers\Etudiant\AbsenceController::class, 'justifier'])->name('absences.justifier.store');

        // Cours & Classroom
        Route::get('/cours', [\App\Http\Controllers\Etudiant\CoursController::class, 'index'])->name('cours.index');
        Route::get('/cours/{module}', [\App\Http\Controllers\Etudiant\CoursController::class, 'show'])->name('cours.show');
        Route::post('/cours/annonce/{annonce}/commentaire', [\App\Http\Controllers\Etudiant\CoursController::class, 'commenter'])->name('cours.commentaire');
        Route::get('/cours/support/{support}/telecharger', [\App\Http\Controllers\Etudiant\CoursController::class, 'telechargerSupport'])->name('cours.support.telecharger');

        // Demandes administratives
        Route::get('/demandes', [\App\Http\Controllers\Etudiant\DemandeController::class, 'index'])->name('demandes.index');
        Route::get('/demandes/create', [\App\Http\Controllers\Etudiant\DemandeController::class, 'create'])->name('demandes.create');
        Route::post('/demandes', [\App\Http\Controllers\Etudiant\DemandeController::class, 'store'])->name('demandes.store');

        // EDT
        Route::get('/edt', [\App\Http\Controllers\Etudiant\EmploiDuTempsController::class, 'index'])->name('edt.index');

        // Chatbot IA
        Route::get('/chatbot', [\App\Http\Controllers\Etudiant\ChatbotController::class, 'index'])->name('chatbot.index');
        Route::post('/chatbot/message', [\App\Http\Controllers\Etudiant\ChatbotController::class, 'message'])->name('chatbot.message');
    });

    // ==========================================
    // PERSONNEL ROUTES
    // ==========================================
    Route::middleware('role:personnel')->prefix('personnel')->name('personnel.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Personnel\DashboardController::class, 'index'])->name('dashboard');
    });
});
