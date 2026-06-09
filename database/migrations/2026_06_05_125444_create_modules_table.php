<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiere_id')->constrained('filieres')->onDelete('cascade');
            $table->foreignId('professeur_id')->nullable()->constrained('professeurs')->onDelete('set null');
            $table->string('nom');
            $table->string('code')->unique();
            $table->decimal('coefficient', 4, 2)->default(1.0);
            $table->integer('semestre'); // 1 ou 2
            $table->integer('volume_horaire')->default(30);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('modules'); }
};
