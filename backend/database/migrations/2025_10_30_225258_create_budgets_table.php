<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 15, 2); // Monto del presupuesto
            $table->integer('month'); // 1-12
            $table->integer('year'); // 2025, 2026, etc.            
            $table->timestamps();

            // Un usuario solo puede tener un presupuesto por categoría/mes/año
            $table->unique(['user_id', 'category_id', 'month', 'year']);
            
            // Índice para búsquedas por periodo
            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
