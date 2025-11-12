<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'category_id', 
        'type', 
        'amount', 
        'description', 
        'transaction_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2', 
        'transaction_date' => 'date'
    ];

    /**
     * Relación: Una transacción pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una transacción pertenece a una categoria.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope: Solo ingresos.
     */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /**
     * Scope: Solo gastos.
     */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /**
     * Scope: Transacciones de un periodo especifico.
     */
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    /**
     * Scope: Transacciones del mes actual.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }
}
