<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cahier_textes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('seance_id')->nullable()->constrained('seances')->onDelete('set null');
            $table->date('date');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->text('objectif');
            $table->enum('nature', ['Cours', 'TD', 'TP'])->default('Cours');
            $table->text('contenu')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cahier_textes'); }
};
