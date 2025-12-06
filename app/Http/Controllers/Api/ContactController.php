<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message! I will get back to you soon.',
            'data' => $contact
        ], 201);
    }

    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'messages' => $messages,
            'stats' => [
                'total' => ContactMessage::count(),
                'new' => ContactMessage::new()->count(),
                'read' => ContactMessage::read()->count(),
            ]
        ]);
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        
        // Mark as read when viewed
        if ($message->status === 'new') {
            $message->markAsRead();
        }

        return response()->json($message);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,read,replied',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $message->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $message
        ]);
    }

    public function delete($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}