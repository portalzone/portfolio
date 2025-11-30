<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Admin Navbar -->
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700">{{ user?.name }}</span>
            <button @click="handleLogout" class="text-red-600 hover:text-red-800">
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Admin Sidebar & Content -->
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-lg min-h-screen">
        <nav class="p-4">
          <router-link 
            to="/admin" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            📊 Dashboard
          </router-link>
          <router-link 
            to="/admin/projects" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            📁 Projects
          </router-link>
          <router-link 
            to="/admin/skills" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            🛠️ Skills
          </router-link>
          <router-link 
            to="/admin/blog" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            ✍️ Blog
          </router-link>
          <router-link 
            to="/admin/profile" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            👤 Profile
          </router-link>
          <router-link 
            to="/admin/analytics" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
            active-class="bg-primary text-white"
          >
            📈 Analytics
          </router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <h2 class="text-3xl font-bold mb-8">Dashboard</h2>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Total Projects</p>
                <p class="text-3xl font-bold">{{ stats.projects_count }}</p>
              </div>
              <div class="text-4xl">📁</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Total Skills</p>
                <p class="text-3xl font-bold">{{ stats.skills_count }}</p>
              </div>
              <div class="text-4xl">🛠️</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Blog Posts</p>
                <p class="text-3xl font-bold">{{ stats.posts_count }}</p>
              </div>
              <div class="text-4xl">✍️</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Total Views</p>
                <p class="text-3xl font-bold">{{ stats.total_views }}</p>
              </div>
              <div class="text-4xl">👁️</div>
            </div>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <router-link to="/admin/projects" class="btn-primary text-center">
              Add New Project
            </router-link>
            <router-link to="/admin/blog" class="btn-secondary text-center">
              Write Blog Post
            </router-link>
            <router-link to="/admin/skills" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition text-center">
              Add Skill
            </router-link>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useApi } from '../../composables/useApi';
import { storeToRefs } from 'pinia';

const router = useRouter();
const authStore = useAuthStore();
const { user } = storeToRefs(authStore);
const api = useApi();

const stats = ref({
  projects_count: 0,
  skills_count: 0,
  posts_count: 0,
  total_views: 0
});

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

onMounted(async () => {
  try {
    const response = await api.getDashboard();
    stats.value = response.data;
  } catch (error) {
    console.error('Error fetching dashboard:', error);
  }
});
</script>