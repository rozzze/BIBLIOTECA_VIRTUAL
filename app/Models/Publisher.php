<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'website',
        'description',
        'logo_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (Publisher $publisher) {
            $publisher->slug = static::generateUniqueSlug($publisher->name);
        });

        static::updating(function (Publisher $publisher) {
            if ($publisher->isDirty('name')) {
                $publisher->slug = static::generateUniqueSlug($publisher->name, $publisher->id);
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

    // 🖼️ Imagen o logo de editorial
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path) {
            return Storage::disk(config('filesystems.default'))->url($this->logo_path);
        }

        return asset('images/default-publisher.png');
    }
}
