<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700">Admin</span>
            <button @click="handleLogout" class="text-red-600 hover:text-red-800">Logout</button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-lg min-h-screen">
        <nav class="p-4">
          <router-link to="/admin" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">📊 Dashboard</router-link>
          <router-link to="/admin/projects" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">📁 Projects</router-link>
          <router-link to="/admin/skills" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">🛠️ Skills</router-link>
          <router-link to="/admin/blog" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">✍️ Blog</router-link>
          <router-link to="/admin/profile" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">👤 Profile</router-link>
          <router-link to="/admin/analytics" class="block px-4 py-3 rounded-lg mb-2 bg-primary text-white">📈 Analytics</router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold">Analytics</h2>
          <select v-model="timeRange" @change="fetchAnalytics" class="px-4 py-2 border rounded-lg">
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
            <option value="90">Last 90 Days</option>
          </select>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Total Visits</p>
                <p class="text-3xl font-bold">{{ analytics.total_visits || 0 }}</p>
              </div>
              <div class="text-4xl">👁️</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Unique Visitors</p>
                <p class="text-3xl font-bold">{{ analytics.unique_visitors || 0 }}</p>
              </div>
              <div class="text-4xl">👤</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Avg. Time</p>
                <p class="text-3xl font-bold">2m 34s</p>
              </div>
              <div class="text-4xl">⏱️</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-gray-500 text-sm">Bounce Rate</p>
                <p class="text-3xl font-bold">42%</p>
              </div>
              <div class="text-4xl">↩️</div>
            </div>
          </div>
        </div>

        <!-- Popular Pages -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
          <h3 class="text-xl font-bold mb-4">Popular Pages</h3>
          <div class="space-y-3">
            <div v-for="page in analytics.page_views" :key="page.page_url" class="flex items-center justify-between pb-3 border-b">
              <div class="flex-1">
                <p class="font-medium">{{ page.page_url }}</p>
              </div>
              <div class="text-right">
                <p class="font-bold">{{ page.views }} views</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Device Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Devices</h3>
            <div class="space-y-3">
              <div v-for="device in analytics.devices" :key="device.device_type" class="flex items-center justify-between">
                <span class="capitalize">{{ device.device_type }}</span>
                <span class="font-bold">{{ device.count }}</span>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Browsers</h3>
            <div class="space-y-3">
              <div v-for="browser in analytics.browsers" :key="browser.browser" class="flex items-center justify-between">
                <span>{{ browser.browser }}</span>
                <span class="font-bold">{{ browser.count }}</span>
              </div>
            </div>
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

const router = useRouter();
const authStore = useAuthStore();
const api = useApi();

const timeRange = ref(30);
const analytics = ref({
  total_visits: 0,
  unique_visitors: 0,
  page_views: [],
  devices: [],
  browsers: []
});

const fetchAnalytics = async () => {
  try {
    const response = await api.getAnalytics({ days: timeRange.value });
    analytics.value = response.data;
  } catch (error) {
    console.error('Error fetching analytics:', error);
  }
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

onMounted(() => {
  fetchAnalytics();
});
</script>