<template>
  <div class="min-h-screen bg-gray-50 dark:bg-[#0D1117] flex items-center justify-center px-4 transition-colors duration-300">
    <div class="w-full max-w-sm">

      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary/10 border border-primary/30 mb-4">
          <span class="text-primary font-bold text-lg font-mono">VM</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Login</h2>
        <p class="text-gray-500 text-sm mt-1">Sign in to manage your portfolio</p>
      </div>

      <div class="bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-2xl p-8 shadow-sm dark:shadow-none">

        <div v-if="error" class="mb-5 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
          <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input id="email" v-model="credentials.email" type="email" required autocomplete="email"
              class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm"
              placeholder="admin@basepan.com"/>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
            <input id="password" v-model="credentials.password" type="password" required autocomplete="current-password"
              class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm"
              placeholder="••••••••"/>
          </div>

          <div class="flex items-center gap-2">
            <input id="remember" v-model="credentials.remember" type="checkbox"
              class="w-4 h-4 rounded border-gray-300 dark:border-[#30363D] bg-white dark:bg-[#0D1117] text-primary focus:ring-primary focus:ring-offset-0"/>
            <label for="remember" class="text-sm text-gray-600 dark:text-gray-400">Remember me</label>
          </div>

          <button type="submit" :disabled="loading"
            class="w-full bg-primary text-[#0D1117] py-3 rounded-lg font-semibold text-sm hover:bg-cyan-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <svg v-if="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ loading ? 'Signing in…' : 'Sign In' }}
          </button>
        </form>
      </div>

      <div class="text-center mt-6">
        <router-link to="/" class="text-sm text-gray-500 hover:text-primary transition-colors duration-200">
          ← Back to Portfolio
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();
const credentials = ref({ email: '', password: '', remember: false });
const loading = ref(false);
const error = ref('');

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  try {
    await authStore.login(credentials.value);
    router.push('/admin');
  } catch (err) {
    error.value = err.response?.data?.message || 'Invalid credentials. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>
