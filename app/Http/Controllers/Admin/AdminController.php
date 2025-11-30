<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Analytic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $stats = [
            'projects_count' => Project::count(),
            'skills_count' => Skill::count(),
            'posts_count' => Post::count(),
            'total_views' => Project::sum('views') + Post::sum('views'),
            'analytics' => Analytic::getStats(30),
        ];

        return response()->json($stats);
    }

    // Projects CRUD
    public function getProjects()
    {
        return response()->json(Project::orderBy('order')->orderBy('created_at', 'desc')->get());
    }

    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'technologies' => 'array',
            'category' => 'string',
            'status' => 'in:active,inactive,archived',
            'featured' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('projects', 'public');
        }

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'url' => 'url',
            'technologies' => 'array',
            'category' => 'string',
            'status' => 'in:active,inactive,archived',
            'featured' => 'boolean',
            'order' => 'integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($project->image_path) {
                Storage::disk('public')->delete($project->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($validated);

        return response()->json($project);
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }
        
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }

    // Skills CRUD
    public function getSkills()
    {
        return response()->json(Skill::ordered()->get());
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'proficiency' => 'required|integer|min:1|max:100',
            'icon' => 'nullable|string',
        ]);

        $skill = Skill::create($validated);

        return response()->json($skill, 201);
    }

    public function updateSkill(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => 'string|max:255',
            'category' => 'string',
            'proficiency' => 'integer|min:1|max:100',
            'icon' => 'nullable|string',
            'order' => 'integer',
        ]);

        $skill->update($validated);

        return response()->json($skill);
    }

    public function deleteSkill($id)
    {
        Skill::findOrFail($id)->delete();

        return response()->json(['message' => 'Skill deleted successfully']);
    }

    // Blog Posts CRUD
    public function getPosts()
    {
        return response()->json(Post::with('user:id,name')->orderBy('created_at', 'desc')->get());
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'tags' => 'array',
            'status' => 'in:draft,published,archived',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $validated['user_id'] = auth()->id();
        
        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        return response()->json($post, 201);
    }

    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'string',
            'category' => 'string',
            'tags' => 'array',
            'status' => 'in:draft,published,archived',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if (isset($validated['status']) && $validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return response()->json($post);
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    // Profile
    public function getProfile()
    {
        return response()->json(Profile::getProfile());
    }

    public function updateProfile(Request $request)
    {
        $profile = Profile::getProfile();

        $validated = $request->validate([
            'name' => 'string|max:255',
            'title' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'email',
            'phone' => 'nullable|string',
            'location' => 'nullable|string',
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'resume_url' => 'nullable|url',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $validated['photo'] = $request->file('photo')->store('profile', 'public');
        }

        $profile->update($validated);

        return response()->json($profile);
    }

    // Analytics
    public function getAnalytics(Request $request)
    {
        $days = $request->get('days', 30);
        $stats = Analytic::getStats($days);

        return response()->json($stats);
    }
}