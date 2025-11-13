<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'nationality',
        'biography',
        'photo_path',
    ];

    /*
    |--------------------------------------------------------------------------
    | ⚙️ Slug automático único
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (Author $author) {
            $author->slug = static::generateUniqueSlug($author->name);
        });

        static::updating(function (Author $author) {
            if ($author->isDirty('name')) {
                $author->slug = static::generateUniqueSlug($author->name, $author->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    /*
    |--------------------------------------------------------------------------
    | 📚 Relación con libros
    |--------------------------------------------------------------------------
    */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🖼️ Imagen de autor (local o S3)
    |--------------------------------------------------------------------------
    */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path) {
            $disk = config('filesystems.default');
            if (method_exists(Storage::disk($disk), 'url')) {
                return Storage::disk($disk)->url($this->photo_path);
            }
            return asset('storage/'.$this->photo_path);
        }

        return asset('images/default-author.jpg');
    }
}
