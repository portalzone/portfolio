<template>
  <nav class="bg-white dark:bg-gray-900 shadow-lg sticky top-0 z-50 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="text-2xl font-bold text-primary dark:text-blue-400">
            {{ profile?.name || 'Portfolio' }}
          </router-link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/" 
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
            active-class="text-primary dark:text-blue-400 font-semibold"
          >
            Home
          </router-link>
          <router-link 
            to="/projects" 
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
            active-class="text-primary dark:text-blue-400 font-semibold"
          >
            Projects
          </router-link>
          <router-link 
            to="/blog" 
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
            active-class="text-primary dark:text-blue-400 font-semibold"
          >
            Blog
          </router-link>
          <router-link 
            to="/contact" 
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
            active-class="text-primary dark:text-blue-400 font-semibold"
          >
            Contact
          </router-link>

          <!-- Dark Mode Toggle -->
          <button 
            @click="themeStore.toggleTheme()"
            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
            title="Toggle Dark Mode"
          >
            <!-- Sun Icon (Show in dark mode) -->
            <svg 
              v-if="themeStore.isDark" 
              class="w-6 h-6 text-yellow-400" 
              fill="currentColor" 
              viewBox="0 0 20 20"
            >
              <path 
                fill-rule="evenodd" 
                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" 
                clip-rule="evenodd" 
              />
            </svg>
            <!-- Moon Icon (Show in light mode) -->
            <svg 
              v-else 
              class="w-6 h-6 text-gray-700 dark:text-gray-300" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
              />
            </svg>
          </button>

          <!-- Login/Logout -->
          <router-link 
            v-if="!authStore.isAuthenticated"
            to="/login" 
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
          >
            Login
          </router-link>
          
          <div v-else class="flex items-center gap-4">
            <router-link 
              to="/admin" 
              class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 transition"
            >
              Admin
            </router-link>
            <button 
              @click="handleLogout"
              class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition"
            >
              Logout
            </button>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center gap-2">
          <!-- Dark Mode Toggle (Mobile) -->
          <button 
            @click="themeStore.toggleTheme()"
            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
          >
            <svg 
              v-if="themeStore.isDark" 
              class="w-5 h-5 text-yellow-400" 
              fill="currentColor" 
              viewBox="0 0 20 20"
            >
              <path 
                fill-rule="evenodd" 
                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" 
                clip-rule="evenodd" 
              />
            </svg>
            <svg 
              v-else 
              class="w-5 h-5 text-gray-700" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
              />
            </svg>
          </button>

          <!-- Menu Button -->
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-blue-400 focus:outline-none"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path 
                v-if="!mobileMenuOpen"
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path 
                v-else
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div 
      v-if="mobileMenuOpen" 
      class="md:hidden bg-white dark:bg-gray-900 border-t dark:border-gray-800"
    >
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link 
          to="/" 
          class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Home
        </router-link>
        <router-link 
          to="/projects" 
          class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Projects
        </router-link>
        <router-link 
          to="/blog" 
          class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Blog
        </router-link>
        <router-link 
          to="/contact" 
          class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Contact
        </router-link>

        <!-- Mobile Auth Links -->
        <div class="border-t dark:border-gray-800 pt-2 mt-2">
          <router-link 
            v-if="!authStore.isAuthenticated"
            to="/login" 
            class="block px-3 py-2 text-primary dark:text-blue-400 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
            @click="mobileMenuOpen = false"
          >
            Login
          </router-link>
          
          <template v-else>
            <router-link 
              to="/admin" 
              class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
              @click="mobileMenuOpen = false"
            >
              Admin Panel
            </router-link>
            <button 
              @click="handleLogout"
              class="block w-full text-left px-3 py-2 text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-md"
            >
              Logout
            </button>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { usePortfolioStore } from '../stores/portfolio';
import { useAuthStore } from '../stores/auth';
import { useThemeStore } from '../stores/theme';
import { storeToRefs } from 'pinia';

const router = useRouter();
const portfolioStore = usePortfolioStore();
const authStore = useAuthStore();
const themeStore = useThemeStore();

const { profile } = storeToRefs(portfolioStore);
const mobileMenuOpen = ref(false);

onMounted(() => {
  if (!profile.value) {
    portfolioStore.fetchProfile();
  }
});

const handleLogout = async () => {
  await authStore.logout();
  mobileMenuOpen.value = false;
  router.push('/');
};
</script>