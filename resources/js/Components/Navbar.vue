<template>
  <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 dark:bg-[#0D1117]/90 backdrop-blur-md border-b border-gray-200 dark:border-[#21262D] transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6">
      <div class="flex justify-between h-14 items-center">

        <!-- Logo -->
        <router-link to="/" class="flex items-center gap-2 group">
          <div class="w-8 h-8 rounded-lg bg-primary/10 border border-primary/30 flex items-center justify-center transition-all duration-200 group-hover:bg-primary/20">
            <span class="text-primary font-bold text-sm font-mono">VM</span>
          </div>
          <span class="text-gray-900 dark:text-white font-semibold text-sm hidden sm:block">
            Victor<span class="text-primary">.</span>
          </span>
        </router-link>

        <!-- Desktop nav links -->
        <div class="hidden md:flex items-center gap-1">
          <router-link
            v-for="link in navLinks"
            :key="link.to"
            :to="link.to"
            class="relative px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 rounded-lg hover:bg-gray-100 dark:hover:bg-[#161B22] font-medium"
            active-class="!text-primary"
          >
            {{ link.label }}
            <span class="absolute bottom-1 left-4 right-4 h-px bg-primary scale-x-0 transition-transform duration-200 origin-left rounded-full route-underline"></span>
          </router-link>
        </div>

        <!-- Right side -->
        <div class="flex items-center gap-2">

          <!-- Dark mode toggle -->
          <button
            @click="themeStore.toggleTheme()"
            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#161B22] transition-all duration-200"
            :title="themeStore.isDark ? 'Switch to light mode' : 'Switch to dark mode'"
          >
            <!-- Sun — shown in dark mode to switch to light -->
            <svg v-if="themeStore.isDark" class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
            </svg>
            <!-- Moon — shown in light mode to switch to dark -->
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
          </button>

          <!-- Auth -->
          <div class="hidden md:flex items-center gap-2">
            <template v-if="authStore.isAuthenticated">
              <router-link
                to="/admin"
                class="text-xs text-gray-500 dark:text-gray-500 hover:text-gray-900 dark:hover:text-white px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-[#161B22] transition-all duration-200 font-mono"
              >
                admin
              </router-link>
              <button
                @click="handleLogout"
                class="text-xs text-red-500 hover:text-red-600 px-3 py-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-200"
              >
                logout
              </button>
            </template>
            <router-link
              v-else
              to="/login"
              class="text-xs text-gray-500 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-300 px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-[#161B22] transition-all duration-200 font-mono"
            >
              login
            </router-link>
          </div>

          <router-link
            to="/contact"
            class="hidden md:inline-flex items-center gap-1.5 text-xs font-semibold bg-primary text-[#0D1117] px-4 py-2 rounded-lg hover:bg-cyan-300 transition-all duration-200"
          >
            Hire Me
          </router-link>

          <!-- Mobile burger -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#161B22] transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <transition
      enter-active-class="transition-all duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="mobileMenuOpen" class="md:hidden bg-white/95 dark:bg-[#0D1117]/98 backdrop-blur-md border-t border-gray-200 dark:border-[#21262D]">
        <div class="px-4 py-4 space-y-1">
          <router-link
            v-for="link in navLinks"
            :key="link.to"
            :to="link.to"
            class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#161B22] rounded-lg transition-all duration-200"
            active-class="!text-primary bg-primary/5"
            @click="mobileMenuOpen = false"
          >
            {{ link.label }}
          </router-link>

          <div class="border-t border-gray-200 dark:border-[#21262D] pt-3 mt-3 space-y-1">
            <router-link
              v-if="!authStore.isAuthenticated"
              to="/login"
              class="block px-4 py-2.5 text-sm text-primary font-semibold hover:bg-primary/10 rounded-lg transition-all duration-200"
              @click="mobileMenuOpen = false"
            >
              Login
            </router-link>
            <template v-else>
              <router-link
                to="/admin"
                class="block px-4 py-2.5 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-[#161B22] rounded-lg transition-all duration-200"
                @click="mobileMenuOpen = false"
              >
                Admin Panel
              </router-link>
              <button
                @click="handleLogout"
                class="block w-full text-left px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-all duration-200"
              >
                Logout
              </button>
            </template>
          </div>
        </div>
      </div>
    </transition>
  </nav>

  <div class="h-14"></div>
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

const navLinks = [
  { to: '/', label: 'Home' },
  { to: '/projects', label: 'Projects' },
  { to: '/blog', label: 'Blog' },
  { to: '/contact', label: 'Contact' },
];

onMounted(() => {
  if (!profile.value) portfolioStore.fetchProfile();
});

const handleLogout = async () => {
  await authStore.logout();
  mobileMenuOpen.value = false;
  router.push('/');
};
</script>

<style scoped>
.router-link-active .route-underline,
.router-link-exact-active .route-underline {
  transform: scaleX(1);
}
</style>
