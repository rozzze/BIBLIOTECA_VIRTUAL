<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

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
        'status',
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
    | ⚙️ Slug Automático
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function ($book) {
            $book->slug = static::generateUniqueSlug($book->title);
        });

        static::updating(function ($book) {
            if ($book->isDirty('title')) {
                $book->slug = static::generateUniqueSlug($book->title);
            }
        });
    }

    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count ? "{$slug}-" . ($count + 1) : $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | 🖼️ Imagen de portada (local o S3)
    |--------------------------------------------------------------------------
    */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_path) {
            $disk = config('filesystems.default');
            if (method_exists(Storage::disk($disk), 'url')) {
                return Storage::disk($disk)->url($this->cover_path);
            }
            return asset('storage/' . $this->cover_path);
        }

        // Imagen genérica si no hay portada
        return asset('images/default-book.png');
    }

    /*
    |--------------------------------------------------------------------------
    | 🏷️ Accesorios extra (por si luego lo usamos en el catálogo)
    |--------------------------------------------------------------------------
    */
    public function getDisplayTitleAttribute(): string
    {
        return Str::limit($this->title, 40);
    }
}
