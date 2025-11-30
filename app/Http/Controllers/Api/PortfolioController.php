<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Analytic;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    // Get profile
    public function getProfile()
    {
        return response()->json(Profile::getProfile());
    }

    // Get all active projects
    public function getProjects(Request $request)
    {
        $query = Project::active()->orderBy('order')->orderBy('created_at', 'desc');

        if ($request->has('featured')) {
            $query->featured();
        }

        $projects = $query->get();

        return response()->json($projects);
    }

    // Get single project
    public function getProject($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $project->incrementViews();
        
        // Track analytics
        Analytic::trackVisit(request()->fullUrl());

        return response()->json($project);
    }

    // Get all skills
    public function getSkills(Request $request)
    {
        $query = Skill::ordered();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        // Group by category
        $skills = $query->get()->groupBy('category');

        return response()->json($skills);
    }

    // Get published blog posts
    public function getPosts(Request $request)
    {
        $query = Post::published()
            ->orderBy('published_at', 'desc')
            ->with('user:id,name');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate($request->get('per_page', 12));

        return response()->json($posts);
    }

    // Get single blog post
    public function getPost($slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->with('user:id,name')
            ->firstOrFail();
        
        $post->incrementViews();
        
        // Track analytics
        Analytic::trackVisit(request()->fullUrl());

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'post' => $post,
            'related' => $relatedPosts,
        ]);
    }

    // Get blog categories
    public function getCategories()
    {
        $categories = Post::published()
            ->select('category', \DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        return response()->json($categories);
    }

    // Track page view
    public function trackView(Request $request)
    {
        Analytic::trackVisit($request->input('page_url'));

        return response()->json(['success' => true]);
    }
}