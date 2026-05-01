<template>
  <div class="min-h-screen bg-gray-50 dark:bg-[#0D1117] py-16 px-4 transition-colors duration-300">
    <div class="max-w-2xl mx-auto">

      <div class="mb-10">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">Get In Touch</h1>
        <p class="text-gray-500 dark:text-gray-400">Have a project in mind or want to collaborate? Drop me a message.</p>
      </div>

      <div v-if="success" class="mb-6 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-700 dark:text-green-400 px-5 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ successMessage }}
      </div>

      <div v-if="error" class="mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 px-5 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ errorMessage }}
      </div>

      <div class="bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-2xl p-8 shadow-sm dark:shadow-none">
        <form @submit.prevent="submitForm" class="space-y-5">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
              <input v-model="form.name" type="text" required
                class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm"
                placeholder="Victor Muojeke"/>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
              <input v-model="form.email" type="email" required
                class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm"
                placeholder="you@example.com"/>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject *</label>
            <input v-model="form.subject" type="text" required
              class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm"
              placeholder="Project collaboration, job opportunity…"/>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Message *</label>
            <textarea v-model="form.message" rows="6" required
              class="w-full px-4 py-3 bg-gray-50 dark:bg-[#0D1117] border border-gray-300 dark:border-[#30363D] text-gray-900 dark:text-white rounded-lg placeholder-gray-400 dark:placeholder-gray-600 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors duration-200 text-sm resize-none"
              placeholder="Tell me about your project…"></textarea>
          </div>

          <button type="submit" :disabled="loading"
            class="w-full bg-primary text-[#0D1117] py-3.5 rounded-lg font-semibold text-sm hover:bg-cyan-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <svg v-if="loading" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            {{ loading ? 'Sending…' : 'Send Message' }}
          </button>
        </form>
      </div>

      <!-- Contact details -->
      <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a v-if="profile?.email" :href="`mailto:${profile.email}`"
           class="flex items-center gap-3 bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl px-5 py-4 hover:border-primary/40 transition-colors duration-200 group shadow-sm dark:shadow-none">
          <div class="w-9 h-9 rounded-lg bg-primary/10 border border-primary/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <div class="min-w-0">
            <p class="text-xs text-gray-500 mb-0.5">Email</p>
            <p class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-primary transition-colors duration-200 truncate">{{ profile.email }}</p>
          </div>
        </a>

        <div v-if="profile?.location" class="flex items-center gap-3 bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl px-5 py-4 shadow-sm dark:shadow-none">
          <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs text-gray-500 mb-0.5">Location</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ profile.location }}</p>
          </div>
        </div>

        <div class="flex items-center gap-3 bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl px-5 py-4 shadow-sm dark:shadow-none">
          <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 flex items-center justify-center flex-shrink-0">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
          </div>
          <div>
            <p class="text-xs text-gray-500 mb-0.5">Status</p>
            <p class="text-sm text-green-600 dark:text-green-400">Available for work</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../composables/useApi';
import { usePortfolioStore } from '../stores/portfolio';

const api = useApi();
const store = usePortfolioStore();
const profile = ref({});
const form = ref({ name: '', email: '', subject: '', message: '' });
const loading = ref(false);
const success = ref(false);
const error = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(async () => {
  await store.fetchProfile();
  profile.value = store.profile;
});

const submitForm = async () => {
  loading.value = true;
  success.value = false;
  error.value = false;
  try {
    const response = await api.submitContact(form.value);
    if (response.data.success) {
      success.value = true;
      successMessage.value = response.data.message;
      form.value = { name: '', email: '', subject: '', message: '' };
    }
  } catch (err) {
    error.value = true;
    errorMessage.value = err.response?.data?.message || 'Something went wrong. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>
