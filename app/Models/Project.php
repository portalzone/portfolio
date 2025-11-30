<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'url',
        'image_path',
        'technologies',
        'category',
        'status',
        'featured',
        'order',
        'views',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'views' => 'integer',
        'order' => 'integer',
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    // Scope for active projects
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for featured projects
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('views');
    }
}