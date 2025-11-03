<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación: Un usuario tiene una configuración.
     */
    public function setting()
    {
        return $this->hasOne(UserSetting::class);
    }

    /**
     * Relación: Un usuario tiene muchas categorías personalizadas.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relación: Un usuario tiene muchas transacciones.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relación: Un usuario tiene muchos presupuestos.
     */
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Método auxiliar: Obtener todas las categorías disponibles para el usuario
     * (sistema + personalizadas).
     */
    public function availableCategories()
    {
        return Category::where(function($query ){
            $query->where('is_system', true)
                ->orWhere('user_id', $this->id);
        })->get();
    }

    /**
     * Método  auxiliar: Balance actual del usuario.
     */
    public function getCurrentBalance()
    {
        $income = $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->transactions()->where('type', 'expense')->sum('amount');
        return $income - $expense;
    }

    /**
     * Método auxiliar: Balance del mes actual.
     */
    public function getCurrentMonthBalance()
    {
        $income = $this->transactions()
            ->where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $expense = $this->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        return $income - $expense;
    }
}
