<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">
    <!-- Admin Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/admin" class="text-2xl font-bold text-primary dark:text-blue-400">
              Admin Panel
            </router-link>
          </div>
          <div class="flex items-center gap-4">
            <router-link 
              to="/" 
              class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition"
              target="_blank"
            >
              View Site
            </router-link>
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

    <!-- Main Container -->
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg min-h-screen transition-colors">
        <nav class="p-4 space-y-1">
          <router-link 
            to="/admin" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
            exact
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
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📈 Analytics
          </router-link>

          <!-- Additional Menu Items -->
          <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <router-link 
              to="/admin/contact-messages" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition relative"
              active-class="bg-primary text-white dark:bg-primary"
            >
              <span class="flex items-center justify-between">
                <span>✉️ Messages</span>
                <span 
                  v-if="notifications.newMessages > 0" 
                  class="px-2 py-0.5 bg-red-500 text-white text-xs rounded-full font-semibold animate-pulse"
                >
                  {{ notifications.newMessages }}
                </span>
              </span>
            </router-link>
            <router-link 
              to="/admin/comments" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition relative"
              active-class="bg-primary text-white dark:bg-primary"
            >
              <span class="flex items-center justify-between">
                <span>💬 Comments</span>
                <span 
                  v-if="notifications.pendingComments > 0" 
                  class="px-2 py-0.5 bg-yellow-500 text-white text-xs rounded-full font-semibold animate-pulse"
                >
                  {{ notifications.pendingComments }}
                </span>
              </span>
            </router-link>
            <router-link 
              to="/admin/newsletter" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              <span class="flex items-center justify-between">
                <span>📧 Newsletter</span>
                <span 
                  v-if="notifications.totalSubscribers > 0" 
                  class="px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full font-semibold"
                >
                  {{ notifications.totalSubscribers }}
                </span>
              </span>
            </router-link>
          </div>
        </nav>
      </aside>

      <!-- Main Content Area -->
      <main class="flex-1 p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useApi } from '../composables/useApi';

const router = useRouter();
const authStore = useAuthStore();
const api = useApi();

const notifications = ref({
  newMessages: 0,
  pendingComments: 0,
  totalSubscribers: 0
});

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

// Fetch notification counts
const fetchNotifications = async () => {
  try {
    // Try to get analytics data which includes these counts
    const response = await api.getAnalytics({ days: 30 });
    
    if (response.data) {
      notifications.value = {
        newMessages: response.data.contact?.new || 0,
        pendingComments: response.data.comments?.pending || 0,
        totalSubscribers: response.data.newsletter?.total || 0
      };
    }
  } catch (error) {
    console.log('Could not fetch notifications:', error);
    // Silently fail - notifications are not critical
  }
};

onMounted(() => {
  fetchNotifications();
  
  // Refresh notifications every 60 seconds
  setInterval(fetchNotifications, 60000);
});
</script>