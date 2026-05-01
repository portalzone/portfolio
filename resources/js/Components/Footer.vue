<template>
  <footer class="bg-gray-900 dark:bg-[#0D1117] border-t border-gray-800 dark:border-[#21262D] transition-colors duration-300">
    <div class="max-w-6xl mx-auto px-6 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- About -->
        <div>
          <div class="flex items-center gap-2 mb-4">
            <div class="w-7 h-7 rounded-lg bg-primary/10 border border-primary/30 flex items-center justify-center">
              <span class="text-primary font-bold text-xs font-mono">VM</span>
            </div>
            <h3 class="text-white font-bold text-base">
              <span class="text-primary">{{ profile?.name || 'Victor Muojeke' }}</span>
            </h3>
          </div>
          <p class="text-gray-400 text-sm leading-relaxed mb-3">
            {{ profile?.title || 'Full-Stack Engineer & ML Systems Developer' }}
          </p>
          <p class="text-gray-600 text-xs font-mono">
            Built with Laravel &amp; Vue.js · St. John's, NL
          </p>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li v-for="link in quickLinks" :key="link.to">
              <router-link
                :to="link.to"
                class="text-sm text-gray-500 hover:text-primary transition-colors duration-200 flex items-center gap-1.5 group"
              >
                <span class="w-3 h-px bg-gray-700 group-hover:bg-primary group-hover:w-4 transition-all duration-200"></span>
                {{ link.label }}
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Connect -->
        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Connect</h3>
          <div class="flex gap-4 mb-4">
            <a v-if="profile?.github_url" :href="profile.github_url" target="_blank"
               class="text-gray-500 hover:text-primary transition-colors duration-200" title="GitHub">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
              </svg>
            </a>
            <a v-if="profile?.linkedin_url" :href="profile.linkedin_url" target="_blank"
               class="text-gray-500 hover:text-primary transition-colors duration-200" title="LinkedIn">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
            </a>
          </div>
          <a v-if="profile?.email" :href="`mailto:${profile.email}`"
             class="text-sm text-gray-500 hover:text-primary transition-colors duration-200 font-mono">
            {{ profile.email }}
          </a>
        </div>
      </div>

      <!-- Bottom bar -->
      <div class="border-t border-gray-800 dark:border-[#21262D] mt-10 pt-6 flex flex-col sm:flex-row justify-between items-center gap-3">
        <p class="text-gray-600 text-xs font-mono">
          &copy; {{ new Date().getFullYear() }} Victor Muojeke. All rights reserved.
        </p>
        <p class="text-gray-700 text-xs font-mono">
          Laravel · Vue.js · Tailwind CSS
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePortfolioStore } from '../stores/portfolio';
import { storeToRefs } from 'pinia';

const portfolioStore = usePortfolioStore();
const { profile } = storeToRefs(portfolioStore);

const quickLinks = [
  { to: '/projects', label: 'Projects' },
  { to: '/blog', label: 'Blog' },
  { to: '/contact', label: 'Contact' },
  { to: '/api-docs', label: 'API Docs' },
];

onMounted(() => {
  if (!profile.value) portfolioStore.fetchProfile();
});
</script>
