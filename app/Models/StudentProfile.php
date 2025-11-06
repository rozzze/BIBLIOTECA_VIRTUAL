<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Career;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dni',
        'carrera_id',
        'semestre',
        'turno',
        'telefono',
        'direccion',
    ];

    // Relación con carrera
    public function career()
    {
        return $this->belongsTo(Career::class, 'carrera_id');
    }

    // 🔥 ENUM simulados (podríamos moverlos a Enum real si lo deseas)
    public static function turnos(): array
    {
        return ['Diurno', 'Nocturno'];
    }

    public static function semestres(): array
    {
        return ['1', '2', '3', '4', '5', '6'];
    }
}
