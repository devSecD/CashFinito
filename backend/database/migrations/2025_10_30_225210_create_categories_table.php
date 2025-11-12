<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // user_id nullable permite categorías predefinidas del sistema (user_id = null)
            
            $table->string('name', 100);
            $table->string('type', 20); // 'income' o 'expense'
            $table->string('color', 7)->default('#6366f1');
            $table->string('icon', 50)->default('wallet'); // Nombre del icono (lucide-react)
            $table->boolean('is_system')->default(false); // true = categoría predefinida
            $table->timestamps();

            // Índices para búsquedas rápidas
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
