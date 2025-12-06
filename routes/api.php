<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

Route::post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
})->middleware('auth:sanctum');

// Public: Comments
Route::get('/posts/{slug}/comments', [CommentController::class, 'index']);
Route::post('/posts/{slug}/comments', [CommentController::class, 'store']);
// Public API routes (no authentication required)
Route::prefix('portfolio')->group(function () {
    Route::get('/profile', [PortfolioController::class, 'getProfile']);
    Route::get('/projects', [PortfolioController::class, 'getProjects']);
    Route::get('/projects/{slug}', [PortfolioController::class, 'getProject']);
    Route::get('/skills', [PortfolioController::class, 'getSkills']);
    Route::get('/posts', [PortfolioController::class, 'getPosts']);
    Route::get('/posts/{slug}', [PortfolioController::class, 'getPost']);
    Route::get('/categories', [PortfolioController::class, 'getCategories']);
    Route::post('/track-view', [PortfolioController::class, 'trackView']);
    Route::post('/contact', [ContactController::class, 'submit']);

});

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::get('/newsletter/verify/{token}', [NewsletterController::class, 'verify']);
Route::post('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe']);


// Admin API routes (require authentication)
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Admin: Comments
    Route::get('/comments', [CommentController::class, 'adminIndex']);
    Route::put('/comments/{id}/approve', [CommentController::class, 'approve']);
    Route::put('/comments/{id}/spam', [CommentController::class, 'markAsSpam']);
    Route::delete('/comments/{id}', [CommentController::class, 'delete']);
    // Projects
    Route::get('/projects', [AdminController::class, 'getProjects']);
    Route::post('/projects', [AdminController::class, 'storeProject']);
    Route::put('/projects/{id}', [AdminController::class, 'updateProject']);
    Route::delete('/projects/{id}', [AdminController::class, 'deleteProject']);
    
    // Skills
    Route::get('/skills', [AdminController::class, 'getSkills']);
    Route::post('/skills', [AdminController::class, 'storeSkill']);
    Route::put('/skills/{id}', [AdminController::class, 'updateSkill']);
    Route::delete('/skills/{id}', [AdminController::class, 'deleteSkill']);
    
    // Newsletter Subscribers
    Route::get('/newsletter', [NewsletterController::class, 'index']);
    Route::delete('/newsletter/{id}', [NewsletterController::class, 'delete']);
    Route::get('/newsletter/export', [NewsletterController::class, 'export']);
    
    // Posts
    Route::get('/posts', [AdminController::class, 'getPosts']);
    Route::post('/posts', [AdminController::class, 'storePost']);
    Route::put('/posts/{id}', [AdminController::class, 'updatePost']);
    Route::delete('/posts/{id}', [AdminController::class, 'deletePost']);
    
    // Profile
    Route::get('/profile', [AdminController::class, 'getProfile']);
    Route::post('/profile', [AdminController::class, 'updateProfile']);
    
    // Analytics
    Route::get('/analytics', [AdminController::class, 'getAnalytics']);
    

    // Contact Messages
    Route::get('/contact-messages', [ContactController::class, 'index']);
    Route::get('/contact-messages/{id}', [ContactController::class, 'show']);
    Route::put('/contact-messages/{id}/status', [ContactController::class, 'updateStatus']);
    Route::delete('/contact-messages/{id}', [ContactController::class, 'delete']);
});