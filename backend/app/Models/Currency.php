<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'code', 
        'name', 
        'symbol', 
        'is_active',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relación: Una moneda puede estar en muchas configuraciones de usuario.
     */
    public function userSettings()
    {
        return $this->hasMany(UserSetting::class);
    }

    /**
     * Scope: Solo monedas activas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
