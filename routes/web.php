<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
Route::get('/test-email', function () {
    try {
        \Mail::raw('This is a test email from BasePan!', function ($message) {
            $message->to('muojekevictor@gmail.com')
                    ->subject('BasePan Email Test');
        });
        
        return 'Email sent! Check your inbox.';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});