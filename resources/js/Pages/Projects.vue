<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">My Projects</h1>
        <p class="text-xl text-gray-600">Explore my portfolio of web applications</p>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap gap-4 justify-center mb-8">
        <button 
          @click="selectedCategory = null"
          :class="selectedCategory === null ? 'bg-primary text-white' : 'bg-white text-gray-700'"
          class="px-4 py-2 rounded-lg shadow hover:shadow-md transition"
        >
          All Projects
        </button>
        <button 
          @click="selectedCategory = 'web'"
          :class="selectedCategory === 'web' ? 'bg-primary text-white' : 'bg-white text-gray-700'"
          class="px-4 py-2 rounded-lg shadow hover:shadow-md transition"
        >
          Web Apps
        </button>
        <button 
          @click="selectedCategory = 'mobile'"
          :class="selectedCategory === 'mobile' ? 'bg-primary text-white' : 'bg-white text-gray-700'"
          class="px-4 py-2 rounded-lg shadow hover:shadow-md transition"
        >
          Mobile Apps
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading projects...</p>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProjectCard 
          v-for="project in filteredProjects" 
          :key="project.id" 
          :project="project" 
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-xl font-medium text-gray-900">No projects found</h3>
        <p class="mt-1 text-gray-500">Try selecting a different category</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePortfolioStore } from '../stores/portfolio';
import { storeToRefs } from 'pinia';
import ProjectCard from '../Components/ProjectCard.vue';

const portfolioStore = usePortfolioStore();
const { projects, loading } = storeToRefs(portfolioStore);

const selectedCategory = ref(null);

const filteredProjects = computed(() => {
  if (!selectedCategory.value) return projects.value;
  return projects.value.filter(p => p.category === selectedCategory.value);
});

onMounted(() => {
  portfolioStore.fetchProjects();
  portfolioStore.trackPageView(window.location.href);
});
</script>