<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Notifications\NewsletterSubscribed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Subscribe a new user to the newsletter.
     */
    public function subscribe(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Create Subscriber
        $subscriber = NewsletterSubscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'status' => 'active', // Default status
            'verification_token' => Str::random(64),
            'ip_address' => $request->ip(),
            // 'verified_at' remains null until they click the email link
        ]);

        // 3. Send Verification Email
        try {
            // Ensure you have the NewsletterSubscribed notification class created
            Notification::route('mail', $subscriber->email)
                ->notify(new NewsletterSubscribed($subscriber));

            $message = 'Thank you for subscribing! Please check your email to confirm.';
        } catch (\Exception $e) {
            // Log the error but don't fail the request completely
            \Log::error('Newsletter email failed: ' . $e->getMessage());
            $message = 'Subscribed successfully! (Email sending failed temporarily)';
        }

        // 4. Response
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $subscriber
        ], 201);
    }

    /**
     * Verify the subscription (Clicked from Email).
     */
    public function verify($token)
    {
        $subscriber = NewsletterSubscriber::where('verification_token', $token)->first();

        if (!$subscriber) {
            // If token is invalid, redirect to frontend with error
            return redirect('https://basepan.com/?verified=false&error=invalid_token');
        }

        // Update subscriber status
        $subscriber->update([
            'verified_at' => now(),
            'verification_token' => null, // Invalidate token so it can't be used again
            'status' => 'active'
        ]);

        // Redirect to your frontend success page
        return redirect('https://basepan.com/?verified=true');
    }

    /**
     * Unsubscribe a user.
     */
    public function unsubscribe(Request $request, $email = null)
    {
        // Handle both Route param {email} or Request body 'email'
        $targetEmail = $email ?? $request->input('email');

        if (!$targetEmail) {
            return response()->json([
                'success' => false, 
                'message' => 'Email is required.'
            ], 400);
        }

        $subscriber = NewsletterSubscriber::where('email', $targetEmail)->first();

        if ($subscriber) {
            $subscriber->update(['status' => 'unsubscribed']);
            
            return response()->json([
                'success' => true, 
                'message' => 'You have been unsubscribed successfully.'
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Email not found.'
        ], 404);
    }

    // ==========================================
    // ADMIN METHODS
    // ==========================================

    /**
     * List all subscribers with statistics.
     */
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('status', 'active')->count(),
            'verified' => NewsletterSubscriber::whereNotNull('verified_at')->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * Delete a subscriber.
     */
    public function delete($id)
    {
        $subscriber = NewsletterSubscriber::find($id);

        if (!$subscriber) {
            return response()->json([
                'success' => false, 
                'message' => 'Subscriber not found'
            ], 404);
        }

        $subscriber->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Subscriber deleted successfully.'
        ]);
    }

    /**
     * Export active subscribers (e.g. for CSV).
     */
    public function export()
    {
        // Only export active and verified users for mailing lists
        $subscribers = NewsletterSubscriber::where('status', 'active')
            ->whereNotNull('verified_at')
            ->select('email', 'name', 'created_at') // Select specific fields for privacy
            ->get();

        return response()->json([
            'success' => true,
            'count' => $subscribers->count(),
            'subscribers' => $subscribers
        ]);
    }
}