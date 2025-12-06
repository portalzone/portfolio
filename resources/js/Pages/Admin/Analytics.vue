<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary dark:text-blue-400">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700 dark:text-gray-300">{{ authStore.user?.name || 'Admin' }}</span>
            <button 
              @click="handleLogout" 
              class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg min-h-screen transition-colors">
        <nav class="p-4 space-y-1">
          <router-link 
            to="/admin" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📊 Dashboard
          </router-link>
          <router-link 
            to="/admin/projects" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📁 Projects
          </router-link>
          <router-link 
            to="/admin/skills" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            🛠️ Skills
          </router-link>
          <router-link 
            to="/admin/blog" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            ✍️ Blog
          </router-link>
          <router-link 
            to="/admin/profile" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            👤 Profile
          </router-link>
          <router-link 
            to="/admin/analytics" 
            class="block px-4 py-3 rounded-lg bg-primary text-white transition"
            active-class="bg-primary text-white"
          >
            📈 Analytics
          </router-link>

          <!-- NEW: Additional Menu Items -->
          <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <router-link 
              to="/admin/contact-messages" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              ✉️ Messages
              <span v-if="stats.overview?.newMessages" class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">
                {{ stats.overview.newMessages }}
              </span>
            </router-link>
            <router-link 
              to="/admin/comments" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              💬 Comments
              <span v-if="stats.overview?.pendingComments" class="ml-2 px-2 py-0.5 bg-yellow-500 text-white text-xs rounded-full">
                {{ stats.overview.pendingComments }}
              </span>
            </router-link>
            <router-link 
              to="/admin/newsletter" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              📧 Newsletter
            </router-link>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold dark:text-white">Analytics Dashboard</h2>
          <select 
            v-model="timeRange" 
            @change="fetchAnalytics" 
            class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
          >
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
            <option value="90">Last 90 Days</option>
          </select>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
          <p class="mt-4 text-gray-600 dark:text-gray-400">Loading analytics...</p>
        </div>

        <div v-else>
          <!-- Overview Stats -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all hover:shadow-xl">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Projects</h3>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
              </div>
              <p class="text-3xl font-bold dark:text-white">{{ stats.overview?.totalProjects || 0 }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ stats.overview?.activeProjects || 0 }} active
              </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all hover:shadow-xl">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Skills</h3>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
              </div>
              <p class="text-3xl font-bold dark:text-white">{{ stats.overview?.totalSkills || 0 }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all hover:shadow-xl">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Blog Posts</h3>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </div>
              <p class="text-3xl font-bold dark:text-white">{{ stats.overview?.totalPosts || 0 }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all hover:shadow-xl">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Total Views</h3>
                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </div>
              <p class="text-3xl font-bold dark:text-white">{{ stats.overview?.totalViews || 0 }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all hover:shadow-xl">
              <div class="flex items-center justify-between mb-2">
                <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium">Subscribers</h3>
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
              </div>
              <p class="text-3xl font-bold dark:text-white">{{ stats.newsletter?.total || 0 }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ stats.newsletter?.verified || 0 }} verified
              </p>
            </div>
          </div>

          <!-- Engagement Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Contact Messages</h3>
                <span class="text-2xl">✉️</span>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total</span>
                  <span class="font-bold dark:text-white">{{ stats.contact?.total || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">New</span>
                  <span class="font-bold text-green-600 dark:text-green-400">{{ stats.contact?.new || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Read</span>
                  <span class="font-bold text-blue-600 dark:text-blue-400">{{ stats.contact?.read || 0 }}</span>
                </div>
              </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Comments</h3>
                <span class="text-2xl">💬</span>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total</span>
                  <span class="font-bold dark:text-white">{{ stats.comments?.total || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Pending</span>
                  <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ stats.comments?.pending || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Approved</span>
                  <span class="font-bold text-green-600 dark:text-green-400">{{ stats.comments?.approved || 0 }}</span>
                </div>
              </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold dark:text-white">Newsletter</h3>
                <span class="text-2xl">📧</span>
              </div>
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Total</span>
                  <span class="font-bold dark:text-white">{{ stats.newsletter?.total || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Active</span>
                  <span class="font-bold text-green-600 dark:text-green-400">{{ stats.newsletter?.active || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600 dark:text-gray-400">Verified</span>
                  <span class="font-bold text-blue-600 dark:text-blue-400">{{ stats.newsletter?.verified || 0 }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Top Projects & Posts -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
              <h2 class="text-xl font-bold mb-4 dark:text-white">Top Projects by Views</h2>
              <div class="space-y-3">
                <div 
                  v-for="project in stats.topProjects" 
                  :key="project.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                >
                  <div class="flex-1">
                    <p class="font-medium dark:text-white">{{ project.title }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ project.category }}</p>
                  </div>
                  <span class="text-lg font-bold text-primary dark:text-blue-400">{{ project.views }}</span>
                </div>
                <div v-if="!stats.topProjects?.length" class="text-center py-6 text-gray-500 dark:text-gray-400">
                  No projects yet
                </div>
              </div>
            </div>

            <!-- Top Posts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
              <h2 class="text-xl font-bold mb-4 dark:text-white">Top Blog Posts by Views</h2>
              <div class="space-y-3">
                <div 
                  v-for="post in stats.topPosts" 
                  :key="post.id"
                  class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                >
                  <div class="flex-1">
                    <p class="font-medium dark:text-white">{{ post.title }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ post.category }}</p>
                  </div>
                  <span class="text-lg font-bold text-primary dark:text-blue-400">{{ post.views }}</span>
                </div>
                <div v-if="!stats.topPosts?.length" class="text-center py-6 text-gray-500 dark:text-gray-400">
                  No blog posts yet
                </div>
              </div>
            </div>
          </div>

          <!-- Views Chart -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-6 dark:text-white">Views Over Time (Last 30 Days)</h2>
            <div class="h-64 flex items-end gap-2">
              <div 
                v-for="(day, index) in stats.viewsOverTime" 
                :key="index"
                class="flex-1 bg-primary dark:bg-blue-600 rounded-t hover:bg-blue-600 dark:hover:bg-blue-500 transition cursor-pointer group relative"
                :style="{ height: `${(day.views / maxViews) * 100}%` }"
                :title="`${day.date}: ${day.views} views`"
              >
                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                  {{ formatDate(day.date) }}<br>{{ day.views }} views
                </div>
              </div>
            </div>
            <div class="mt-4 flex justify-between text-sm text-gray-600 dark:text-gray-400">
              <span>{{ formatDate(stats.viewsOverTime?.[0]?.date) }}</span>
              <span>{{ formatDate(stats.viewsOverTime?.[stats.viewsOverTime.length - 1]?.date) }}</span>
            </div>
          </div>

          <!-- Popular Pages (if analytics data exists) -->
          <div v-if="analytics.page_views?.length" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold mb-4 dark:text-white">Popular Pages</h3>
            <div class="space-y-3">
              <div 
                v-for="page in analytics.page_views" 
                :key="page.page_url" 
                class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0"
              >
                <div class="flex-1">
                  <p class="font-medium dark:text-white">{{ page.page_url }}</p>
                </div>
                <div class="text-right">
                  <p class="font-bold text-primary dark:text-blue-400">{{ page.views }} views</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useApi } from '../../composables/useApi';

const router = useRouter();
const authStore = useAuthStore();
const api = useApi();

const timeRange = ref(30);
const loading = ref(true);
const stats = ref({
  overview: {},
  contact: {},
  comments: {},
  newsletter: {},
  topProjects: [],
  topPosts: [],
  viewsOverTime: [],
});
const analytics = ref({
  total_visits: 0,
  unique_visitors: 0,
  page_views: [],
  devices: [],
  browsers: []
});

const maxViews = computed(() => {
  if (!stats.value.viewsOverTime?.length) return 1;
  return Math.max(...stats.value.viewsOverTime.map(d => d.views));
});

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const fetchAnalytics = async () => {
  loading.value = true;
  try {
    const response = await api.getAnalytics({ days: timeRange.value });
    stats.value = response.data;
    
    // Legacy analytics data (if available)
    try {
      const legacyResponse = await api.getAnalytics({ days: timeRange.value });
      analytics.value = legacyResponse.data;
    } catch (e) {
      console.log('Legacy analytics not available');
    }
  } catch (error) {
    console.error('Error fetching analytics:', error);
  } finally {
    loading.value = false;
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