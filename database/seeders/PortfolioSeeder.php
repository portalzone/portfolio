<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@basepan.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        echo "✅ Admin user created: admin@basepan.com / admin123\n";
        echo "⚠️  IMPORTANT: Change this password after first login!\n\n";

        // Create profile
        Profile::create([
            'name' => 'Your Name',
            'title' => 'Full Stack Developer',
            'bio' => 'Passionate developer specializing in Laravel and Vue.js. Building elegant web solutions that solve real-world problems.',
            'email' => 'contact@basepan.com',
            'location' => 'Your Location',
        ]);

        echo "✅ Profile created\n\n";

        // Create sample projects (VMS and MUNext)
        $projects = [
            [
                'title' => 'VMS - Vehicle Management System',
                'slug' => 'vms-vehicle-management-system',
                'description' => 'A comprehensive vehicle management system built with Laravel and Vue.js. Features include vehicle tracking, maintenance scheduling, driver management, and detailed reporting.',
                'url' => 'https://vms.basepan.com',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'TailwindCSS'],
                'category' => 'web',
                'status' => 'active',
                'featured' => true,
                'order' => 1,
            ],
            [
                'title' => 'MUNext - Municipal Management System',
                'slug' => 'munext-municipal-management',
                'description' => 'Municipal management platform for local government operations. Streamlines administrative tasks, citizen services, and resource management.',
                'url' => 'https://munext.basepan.com',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'REST API'],
                'category' => 'web',
                'status' => 'active',
                'featured' => true,
                'order' => 2,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        echo "✅ Sample projects created (VMS, MUNext)\n\n";

        // Create sample skills
        $skills = [
            // Backend
            ['name' => 'Laravel', 'category' => 'backend', 'proficiency' => 95, 'order' => 1],
            ['name' => 'PHP', 'category' => 'backend', 'proficiency' => 90, 'order' => 2],
            ['name' => 'Node.js', 'category' => 'backend', 'proficiency' => 75, 'order' => 3],
            
            // Frontend
            ['name' => 'Vue.js', 'category' => 'frontend', 'proficiency' => 90, 'order' => 1],
            ['name' => 'JavaScript', 'category' => 'frontend', 'proficiency' => 88, 'order' => 2],
            ['name' => 'TailwindCSS', 'category' => 'frontend', 'proficiency' => 85, 'order' => 3],
            ['name' => 'HTML/CSS', 'category' => 'frontend', 'proficiency' => 95, 'order' => 4],
            
            // Database
            ['name' => 'MySQL', 'category' => 'database', 'proficiency' => 85, 'order' => 1],
            ['name' => 'PostgreSQL', 'category' => 'database', 'proficiency' => 70, 'order' => 2],
            
            // Tools
            ['name' => 'Git', 'category' => 'tools', 'proficiency' => 90, 'order' => 1],
            ['name' => 'Docker', 'category' => 'tools', 'proficiency' => 75, 'order' => 2],
            ['name' => 'Linux', 'category' => 'tools', 'proficiency' => 80, 'order' => 3],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        echo "✅ Skills created\n\n";

        // Create sample blog post
        Post::create([
            'title' => 'Welcome to My Portfolio',
            'slug' => 'welcome-to-my-portfolio',
            'excerpt' => 'Introduction to my portfolio and the technologies I use to build modern web applications.',
            'content' => '<h2>Welcome!</h2><p>Thank you for visiting my portfolio. I\'m a full-stack developer passionate about creating elegant solutions to complex problems.</p><h3>What I Do</h3><p>I specialize in building web applications using Laravel and Vue.js. My focus is on creating maintainable, scalable, and user-friendly applications.</p><h3>Recent Projects</h3><p>Check out my recent projects including the Vehicle Management System and Municipal Management Platform.</p>',
            'category' => 'general',
            'tags' => ['introduction', 'portfolio', 'web development'],
            'status' => 'published',
            'published_at' => now(),
            'user_id' => $admin->id,
        ]);

        echo "✅ Sample blog post created\n\n";
        echo "========================================\n";
        echo "Portfolio seed completed successfully!\n";
        echo "========================================\n";
    }
}