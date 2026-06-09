<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('capacite')->default(30);
            $table->enum('type', ['amphitheatre', 'td', 'tp', 'labo', 'salle_info'])->default('td');
            $table->string('batiment')->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('salles'); }
};
