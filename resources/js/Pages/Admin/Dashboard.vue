<template>
  <AdminLayout>
    <h2 class="text-3xl font-bold mb-8 dark:text-white">Dashboard</h2>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Projects</p>
            <p class="text-3xl font-bold dark:text-white">{{ stats.projects_count }}</p>
          </div>
          <div class="text-4xl">📁</div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Skills</p>
            <p class="text-3xl font-bold dark:text-white">{{ stats.skills_count }}</p>
          </div>
          <div class="text-4xl">🛠️</div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Blog Posts</p>
            <p class="text-3xl font-bold dark:text-white">{{ stats.posts_count }}</p>
          </div>
          <div class="text-4xl">✍️</div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Views</p>
            <p class="text-3xl font-bold dark:text-white">{{ stats.total_views }}</p>
          </div>
          <div class="text-4xl">👁️</div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
      <h3 class="text-xl font-bold mb-4 dark:text-white">Quick Actions</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <router-link to="/admin/projects" class="btn-primary text-center">
          + Add New Project
        </router-link>
        <router-link to="/admin/blog" class="bg-secondary text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-center">
          ✍️ Write Blog Post
        </router-link>
        <router-link to="/admin/skills" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition text-center">
          🛠️ Add Skill
        </router-link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const stats = ref({
  projects_count: 0,
  skills_count: 0,
  posts_count: 0,
  total_views: 0
});

onMounted(async () => {
  try {
    const response = await api.getDashboard();
    stats.value = response.data;
  } catch (error) {
    console.error('Error fetching dashboard:', error);
  }
});
</script>