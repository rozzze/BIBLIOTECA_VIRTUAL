<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    //

    use HasFactory;

    protected $fillable = [
        'user_id', 'dni', 'carrera', 'semestre',
        'codigo_institucional', 'telefono', 'direccion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
