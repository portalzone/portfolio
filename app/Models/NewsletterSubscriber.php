<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'verification_token',
        'verified_at',
        'ip_address'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (!$subscriber->verification_token) {
                $subscriber->verification_token = Str::random(60);
            }
        });
    }

    public function verify()
    {
        $this->update([
            'verified_at' => now(),
            'verification_token' => null
        ]);
    }

    public function unsubscribe()
    {
        $this->update(['status' => 'unsubscribed']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
}