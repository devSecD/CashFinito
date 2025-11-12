<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'currency_id',
    ];

    /**
     * Relación: Una configuración pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una configuración tiene una moneda.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
