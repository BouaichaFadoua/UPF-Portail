<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', [
                'attestation_scolarite','releve_notes','certificat_inscription',
                'attestation_travail','ordre_mission'
            ]);
            $table->enum('statut', ['en_attente','validee','refusee'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('traite_le')->nullable();
            $table->string('destination')->nullable();
            $table->date('date_depart')->nullable();
            $table->date('date_retour')->nullable();
            $table->text('motif_mission')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('demandes'); }
};
