<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'category_id', 
        'amount', 
        'month', 
        'year', 
    ];

    protected $casts = [
        'amount' => 'decimal:2', 
        'month' => 'integer', 
        'year' => 'integer', 
    ];

    /**
     * Relación: Un presupuesto pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un presupuesto pertenece a una categoría.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope: Presupuesto de un mes/año específico,
     */
    public function scopeForPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Scope: Presupuesto del mes actual.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where('month', now()->month)
                    ->where('year', now()->year);
    }

    /**
     * Método auxiliar: Obtener el gasto total de la categoría  en el periodo del presupuesto.
     */
    public function getSpentAmount()
    {
        return Transaction::wehere('user_id', $this->user_id)
                ->where('category_id', $this->category_id)
                ->where('type', 'expense')
                ->where('transaction_date', $this->month)
                ->where('transaction_date', $this->year)
                ->sum('amount');
    }

    /**
     * Método auxiliar: Porcentaje gastado del presupuesto.
     */
    public function getPercentageUsed()
    {
        $spent = $this->getSpentAmount();
        return $this->amount > 0 ? ($spent / $this->amount) * 100 : 0;
    }

    /**
     * Método auxiliar: Verificar si excedió el presupuesto.
     */
    public function isExceeded()
    {
        return $this->getSpentAmount() > $this->amount;
    }
}
