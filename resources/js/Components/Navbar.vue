<template>
  <nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="text-2xl font-bold text-primary">
            {{ profile?.name || 'Portfolio' }}
          </router-link>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/" 
            class="text-gray-700 hover:text-primary transition"
            active-class="text-primary font-semibold"
          >
            Home
          </router-link>
          <router-link 
            to="/projects" 
            class="text-gray-700 hover:text-primary transition"
            active-class="text-primary font-semibold"
          >
            Projects
          </router-link>
          <router-link 
            to="/blog" 
            class="text-gray-700 hover:text-primary transition"
            active-class="text-primary font-semibold"
          >
            Blog
          </router-link>
          <a 
            href="#contact" 
            class="btn-primary"
          >
            Contact
          </a>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button 
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="text-gray-700 hover:text-primary focus:outline-none"
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
    <div v-if="mobileMenuOpen" class="md:hidden bg-white border-t">
      <div class="px-2 pt-2 pb-3 space-y-1">
        <router-link 
          to="/" 
          class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Home
        </router-link>
        <router-link 
          to="/projects" 
          class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Projects
        </router-link>
        <router-link 
          to="/blog" 
          class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Blog
        </router-link>
        <a 
          href="#contact" 
          class="block px-3 py-2 text-primary font-semibold hover:bg-gray-100 rounded-md"
          @click="mobileMenuOpen = false"
        >
          Contact
        </a>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePortfolioStore } from '../stores/portfolio';
import { storeToRefs } from 'pinia';

const portfolioStore = usePortfolioStore();
const { profile } = storeToRefs(portfolioStore);
const mobileMenuOpen = ref(false);

onMounted(() => {
  if (!profile.value) {
    portfolioStore.fetchProfile();
  }
});
</script>