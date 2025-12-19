<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory;
    use HasSlug;
    use Searchable;

    protected $fillable = [
        'name',
        'slug',
        'article',
        'seo_title',
        'seo_description',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @return MorphToMany
     */
    public function templates(): MorphToMany
    {
        return $this->morphedByMany(Template::class, 'categorizable');
    }

    /**
     * @return MorphToMany
     */
    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'categorizable');
    }

    /**
     * @return MorphToMany
     */
    public function guides(): MorphToMany
    {
        return $this->morphedByMany(Guide::class, 'categorizable');
    }
}
