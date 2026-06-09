<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->string('titre');
            $table->string('fichier_path');
            $table->string('fichier_nom');
            $table->string('fichier_type')->nullable();
            $table->enum('type', ['Cours', 'TD', 'TP', 'Examen', 'Autre'])->default('Cours');
            $table->bigInteger('taille')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('supports'); }
};
