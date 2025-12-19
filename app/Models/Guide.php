<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Guide extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'visual',
        'article',
        'seo_title',
        'seo_description',
        'data',
    ];

    protected $casts = [
        'data' => 'object',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @return MorphToMany
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * @return MorphToMany
     */
    public function templates(): MorphToMany
    {
        return $this->morphedByMany(Template::class, 'guideable');
    }

    /**
     * @return MorphToMany
     */
    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'guideable');
    }
}
