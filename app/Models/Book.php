<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'publisher_id',
        'category_id',
        'publication_year',
        'language',
        'pages',
        'stock',
        'cover_path',
        'summary',
        'status', // Tu migración le da un 'default', así que no es 100% necesario al crear
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔗 Relaciones
    |--------------------------------------------------------------------------
    */

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ⚙️ Slug Automático (Versión Robusta)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function (Book $book) {
            $book->slug = static::generateUniqueSlug($book->title);
        });

        static::updating(function (Book $book) {
            if ($book->isDirty('title')) {
                // Pasa el ID actual para ignorarlo en la búsqueda de duplicados
                $book->slug = static::generateUniqueSlug($book->title, $book->id);
            }
        });
    }

    /**
     * Genera un slug único para el libro.
     */
    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        // Bucle para encontrar un slug que no exista
        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId)) // Ignora el ID actual al actualizar
            ->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | 🖼️ Imagen de portada (local o S3) - Versión Robusta
    |--------------------------------------------------------------------------
    */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_path) {
            $disk = config('filesystems.default');
            // Comprueba si el disco (S3, public) tiene un método 'url'
            if (method_exists(Storage::disk($disk), 'url')) {
                return Storage::disk($disk)->url($this->cover_path);
            }
            // Fallback para el disco 'local' (que no tiene 'url')
            return asset('storage/' . $this->cover_path);
        }

        // Imagen genérica si no hay portada
        return asset('images/dafault-book.jpg');
    }

    /*
    |--------------------------------------------------------------------------
    | 🏷️ Accesorios extra
    |--------------------------------------------------------------------------
    */
    public function getDisplayTitleAttribute(): string
    {
        return Str::limit($this->title, 40);
    }
}