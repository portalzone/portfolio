<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-6xl mx-auto px-4">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="mb-12">
          <h1 class="text-4xl font-bold mb-4">Portfolio API Documentation</h1>
          <p class="text-lg text-gray-600">
            RESTful API for accessing portfolio data. All endpoints return JSON responses.
          </p>
          <div class="mt-4 flex gap-4">
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
              Version 1.0
            </span>
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
              REST API
            </span>
            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
              JSON
            </span>
          </div>
        </div>

        <!-- Base URL -->
        <div class="mb-8 p-4 bg-gray-100 rounded-lg">
          <h3 class="font-semibold mb-2">Base URL</h3>
          <code class="text-primary">https://basepan.com/api</code>
        </div>

        <!-- Authentication -->
        <section class="mb-12">
          <h2 class="text-2xl font-bold mb-4">Authentication</h2>
          <p class="text-gray-600 mb-4">
            Public endpoints don't require authentication. Admin endpoints require a Bearer token.
          </p>
          <div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
            <pre><code>Authorization: Bearer YOUR_TOKEN_HERE</code></pre>
          </div>
        </section>

        <!-- Endpoints -->
        <section class="space-y-8">
          <h2 class="text-2xl font-bold mb-6">Endpoints</h2>

          <!-- Portfolio -->
          <div v-for="group in apiGroups" :key="group.name" class="border-b pb-8">
            <h3 class="text-xl font-bold mb-4">{{ group.name }}</h3>
            
            <div v-for="endpoint in group.endpoints" :key="endpoint.path" class="mb-6">
              <div class="flex items-center gap-3 mb-2">
                <span :class="[
                  'px-3 py-1 rounded font-semibold text-sm',
                  endpoint.method === 'GET' ? 'bg-blue-100 text-blue-700' :
                  endpoint.method === 'POST' ? 'bg-green-100 text-green-700' :
                  endpoint.method === 'PUT' ? 'bg-yellow-100 text-yellow-700' :
                  'bg-red-100 text-red-700'
                ]">
                  {{ endpoint.method }}
                </span>
                <code class="text-lg">{{ endpoint.path }}</code>
              </div>
              
              <p class="text-gray-600 mb-3">{{ endpoint.description }}</p>

              <!-- Parameters -->
              <div v-if="endpoint.params" class="mb-3">
                <h5 class="font-semibold text-sm mb-2">Parameters:</h5>
                <ul class="list-disc list-inside text-sm text-gray-600">
                  <li v-for="param in endpoint.params" :key="param">{{ param }}</li>
                </ul>
              </div>

              <!-- Example Request -->
              <div class="bg-gray-900 text-gray-100 p-4 rounded-lg mb-3">
                <p class="text-xs text-gray-400 mb-2">Example Request:</p>
                <pre><code>{{ endpoint.example }}</code></pre>
              </div>

              <!-- Example Response -->
              <div v-if="endpoint.response" class="bg-gray-100 p-4 rounded-lg">
                <p class="text-xs text-gray-600 mb-2">Example Response:</p>
                <pre><code>{{ endpoint.response }}</code></pre>
              </div>
            </div>
          </div>
        </section>

        <!-- Rate Limiting -->
        <section class="mt-12 p-6 bg-yellow-50 border border-yellow-200 rounded-lg">
          <h3 class="text-xl font-bold mb-2">⚠️ Rate Limiting</h3>
          <p class="text-gray-700">
            API requests are limited to 60 requests per minute for unauthenticated users
            and 120 requests per minute for authenticated users.
          </p>
        </section>

        <!-- Support -->
        <section class="mt-12 text-center p-8 bg-gradient-to-r from-primary to-secondary text-white rounded-lg">
          <h3 class="text-2xl font-bold mb-2">Need Help?</h3>
          <p class="mb-4">Have questions about the API? Get in touch!</p>
          <router-link to="/contact" class="btn-primary bg-white text-primary hover:bg-gray-100">
            Contact Me
          </router-link>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const apiGroups = ref([
  {
    name: 'Portfolio',
    endpoints: [
      {
        method: 'GET',
        path: '/portfolio/profile',
        description: 'Get portfolio profile information',
        example: 'curl https://basepan.com/api/portfolio/profile',
        response: `{
  "name": "M. C. Victor",
  "title": "Full Stack Developer",
  "bio": "Passionate software engineer...",
  "email": "victor@basepan.com",
  "github": "https://github.com/victor",
  "linkedin": "https://linkedin.com/in/victor"
}`
      },
      {
        method: 'GET',
        path: '/portfolio/projects',
        description: 'Get all projects',
        example: 'curl https://basepan.com/api/portfolio/projects',
        response: `[
  {
    "id": 1,
    "title": "VMS - Vehicle Management System",
    "slug": "vms-vehicle-management-system",
    "description": "A comprehensive vehicle management system...",
    "technologies": ["Laravel", "Vue.js", "MySQL"],
    "category": "web",
    "status": "active",
    "featured": true,
    "views": 150
  }
]`
      },
      {
        method: 'GET',
        path: '/portfolio/projects/{slug}',
        description: 'Get a specific project by slug',
        example: 'curl https://basepan.com/api/portfolio/projects/vms-vehicle-management-system',
        response: `{
  "id": 1,
  "title": "VMS - Vehicle Management System",
  "slug": "vms-vehicle-management-system",
  "description": "Full description here...",
  "technologies": ["Laravel", "Vue.js", "MySQL"],
  "url": "https://vms.basepan.com",
  "views": 150
}`
      },
      {
        method: 'GET',
        path: '/portfolio/skills',
        description: 'Get all skills grouped by category',
        example: 'curl https://basepan.com/api/portfolio/skills',
        response: `{
  "Backend": [
    {
      "name": "Laravel",
      "proficiency": 95,
      "category": "Backend"
    }
  ],
  "Frontend": [...]
}`
      }
    ]
  },
  {
    name: 'Blog',
    endpoints: [
      {
        method: 'GET',
        path: '/portfolio/posts',
        description: 'Get all published blog posts',
        params: ['category (optional)', 'search (optional)', 'page (optional)'],
        example: 'curl https://basepan.com/api/portfolio/posts?category=Development',
        response: `{
  "data": [...],
  "current_page": 1,
  "total": 10
}`
      },
      {
        method: 'GET',
        path: '/portfolio/posts/{slug}',
        description: 'Get a specific blog post by slug',
        example: 'curl https://basepan.com/api/portfolio/posts/welcome-to-my-portfolio',
      }
    ]
  },
  {
    name: 'Contact',
    endpoints: [
      {
        method: 'POST',
        path: '/contact',
        description: 'Submit a contact form message',
        params: ['name (required)', 'email (required)', 'subject (required)', 'message (required)'],
        example: `curl -X POST https://basepan.com/api/contact \\
  -H "Content-Type: application/json" \\
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Question about your work",
    "message": "I would like to discuss..."
  }'`,
        response: `{
  "success": true,
  "message": "Thank you for your message! I will get back to you soon."
}`
      }
    ]
  },
  {
    name: 'Comments',
    endpoints: [
      {
        method: 'GET',
        path: '/posts/{slug}/comments',
        description: 'Get approved comments for a blog post',
        example: 'curl https://basepan.com/api/posts/welcome-to-my-portfolio/comments',
      },
      {
        method: 'POST',
        path: '/posts/{slug}/comments',
        description: 'Submit a comment on a blog post',
        params: ['name (required)', 'email (required)', 'content (required)', 'website (optional)'],
      }
    ]
  },
  {
    name: 'Newsletter',
    endpoints: [
      {
        method: 'POST',
        path: '/newsletter/subscribe',
        description: 'Subscribe to newsletter',
        params: ['email (required)', 'name (optional)'],
        example: `curl -X POST https://basepan.com/api/newsletter/subscribe \\
  -H "Content-Type: application/json" \\
  -d '{"email": "user@example.com"}'`,
      }
    ]
  }
]);
</script>