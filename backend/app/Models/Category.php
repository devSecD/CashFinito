<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'name', 
        'type', 
        'color', 
        'icon', 
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean', 
    ];

    /**
     * Relación: Una categoría puede pertenecer a un usuario (o ser del sistema).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una categoría puede tener muchas transacciones.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relación: Una categoría puede tener muchos presupuestos.
     */
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Scope: Solo categorías del sistema
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope: Solo acepta categorías personalizadas de un usuario.
     */
    public function scopeUserCategories($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar por tipo (income/expense).
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
