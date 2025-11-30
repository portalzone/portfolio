<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'name',
        'title',
        'bio',
        'email',
        'phone',
        'photo',
        'resume_url',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'location',
    ];

    // Get or create profile (singleton pattern)
    public static function getProfile()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Your Name',
                'title' => 'Full Stack Developer',
                'bio' => 'Passionate developer building elegant web solutions.',
                'email' => 'contact@basepan.com',
            ]
        );
    }
}