<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('professeur_id')->constrained('professeurs')->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu');
            $table->boolean('publiee')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('annonces'); }
};
