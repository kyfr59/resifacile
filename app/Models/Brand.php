<?php

namespace App\Models;

use App\DataTransferObjects\AddressData;
use App\Enums\PageStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use Searchable;

    protected $fillable = [
        'name',
        'address',
        'status',
        'slug',
        'views',
        'purchased',
        'article',
        'seo_title',
        'seo_description',
        'data',
        'has_childs',
        'template_id',
    ];

    protected $casts = [
        'address' => AddressData::class,
        'status' => PageStatus::class,
        'data' => 'object',
        'has_childs' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function guides(): MorphToMany
    {
        return $this->morphToMany(Guide::class, 'guideable');
    }

    public function categories(): MorphToMany
    {
            return $this->morphToMany(Category::class, 'categorizable');
    }

    public function templates(): MorphToMany
    {
        return $this->morphedByMany(Template::class, 'brandable');
    }

    public function marks(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'brandable');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function searchableAs(): string
    {
        return 'brands_index';
    }

    public function toSearchableArray(): array
    {
        return $this->toArray();
    }
}
