<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Analytic extends Model
{
    protected $fillable = [
        'visitor_ip_hash',
        'user_agent',
        'page_url',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public $timestamps = false;

    // Track a visit
    public static function trackVisit($pageUrl = null)
    {
        $request = request();
        
        $data = [
            'visitor_ip_hash' => hash('sha256', $request->ip() . config('app.key')),
            'user_agent' => $request->userAgent(),
            'page_url' => $pageUrl ?? $request->fullUrl(),
            'referrer' => $request->headers->get('referer'),
            'device_type' => self::detectDeviceType($request->userAgent()),
            'browser' => self::detectBrowser($request->userAgent()),
            'visited_at' => now(),
        ];

        return self::create($data);
    }

    // Detect device type
    private static function detectDeviceType($userAgent)
    {
        if (preg_match('/mobile|android|iphone|ipad|ipod/i', $userAgent)) {
            if (preg_match('/ipad|tablet/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }

    // Detect browser
    private static function detectBrowser($userAgent)
    {
        if (preg_match('/Edge/i', $userAgent)) return 'Edge';
        if (preg_match('/Chrome/i', $userAgent)) return 'Chrome';
        if (preg_match('/Safari/i', $userAgent)) return 'Safari';
        if (preg_match('/Firefox/i', $userAgent)) return 'Firefox';
        if (preg_match('/MSIE|Trident/i', $userAgent)) return 'IE';
        return 'Other';
    }

    // Get stats
    public static function getStats($days = 30)
    {
        $startDate = now()->subDays($days);

        return [
            'total_visits' => self::where('visited_at', '>=', $startDate)->count(),
            'unique_visitors' => self::where('visited_at', '>=', $startDate)->distinct('visitor_ip_hash')->count(),
            'page_views' => self::select('page_url', \DB::raw('count(*) as views'))
                ->where('visited_at', '>=', $startDate)
                ->groupBy('page_url')
                ->orderByDesc('views')
                ->limit(10)
                ->get(),
            'devices' => self::select('device_type', \DB::raw('count(*) as count'))
                ->where('visited_at', '>=', $startDate)
                ->groupBy('device_type')
                ->get(),
            'browsers' => self::select('browser', \DB::raw('count(*) as count'))
                ->where('visited_at', '>=', $startDate)
                ->groupBy('browser')
                ->get(),
        ];
    }
}