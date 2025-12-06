<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'ip_address' => $request->ip(),
        ]);

        // In production, you would send a verification email here

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing! Check your email to confirm.',
            'data' => $subscriber
        ], 201);
    }

    public function verify($token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->firstOrFail();
        $subscriber->verify();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    public function unsubscribe($email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->firstOrFail();
        $subscriber->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'You have been unsubscribed successfully.'
        ]);
    }

    // Admin methods
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();

        return response()->json([
            'subscribers' => $subscribers,
            'stats' => [
                'total' => NewsletterSubscriber::count(),
                'active' => NewsletterSubscriber::active()->count(),
                'verified' => NewsletterSubscriber::verified()->count(),
            ]
        ]);
    }

    public function delete($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriber deleted'
        ]);
    }

    public function export()
    {
        $subscribers = NewsletterSubscriber::active()->verified()->get(['email', 'name']);

        return response()->json([
            'subscribers' => $subscribers,
            'count' => $subscribers->count()
        ]);
    }
}