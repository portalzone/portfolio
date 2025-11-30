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
});

// Admin API routes (require authentication)
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
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
});