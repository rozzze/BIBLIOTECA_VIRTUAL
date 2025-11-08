<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nationality', 'biography', 'photo_path'];

    // relación futura con libros
    //public function books()
    //{
    //    return $this->hasMany(Book::class);
    //}

    // acceso directo a la URL de la foto (local o S3)
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            $disk = config('filesystems.default'); // 'public' o 's3'
            // Si el disk tiene url() (public/s3) funcionará; si estás en 'local', no.
            if (method_exists(Storage::disk($disk), 'url')) {
                return Storage::disk($disk)->url($this->photo_path);
            }
            // Fallback para 'local' sin url()
            return asset('storage/'.$this->photo_path);
        }

        return asset('images/default-author.png');
    }
}
