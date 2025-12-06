<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 dark:text-white">My Projects</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">Explore my portfolio of web applications</p>
      </div>

      <!-- NEW: Search Bar -->
      <div class="mb-8 max-w-2xl mx-auto">
        <div class="relative">
          <input 
            v-model="searchQuery"
            type="text"
            placeholder="Search projects by name, description, or technology..."
            class="w-full px-6 py-4 pl-14 text-lg border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 shadow-sm"
          />
          <svg 
            class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          
          <!-- Clear button -->
          <button 
            v-if="searchQuery"
            @click="searchQuery = ''"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Category Filters -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filter by Category</h3>
        <div class="flex flex-wrap gap-3 justify-center">
          <button 
            @click="selectedCategory = null"
            :class="[
              'px-5 py-2.5 rounded-lg shadow-sm font-medium transition-all',
              selectedCategory === null 
                ? 'bg-primary text-white shadow-md transform scale-105' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            All Projects
            <span class="ml-2 text-sm opacity-75">({{ projects.length }})</span>
          </button>
          <button 
            @click="selectedCategory = 'web'"
            :class="[
              'px-5 py-2.5 rounded-lg shadow-sm font-medium transition-all',
              selectedCategory === 'web' 
                ? 'bg-primary text-white shadow-md transform scale-105' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            Web Apps
            <span class="ml-2 text-sm opacity-75">({{ categoryCount('web') }})</span>
          </button>
          <button 
            @click="selectedCategory = 'mobile'"
            :class="[
              'px-5 py-2.5 rounded-lg shadow-sm font-medium transition-all',
              selectedCategory === 'mobile' 
                ? 'bg-primary text-white shadow-md transform scale-105' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            Mobile Apps
            <span class="ml-2 text-sm opacity-75">({{ categoryCount('mobile') }})</span>
          </button>
          <button 
            @click="selectedCategory = 'api'"
            :class="[
              'px-5 py-2.5 rounded-lg shadow-sm font-medium transition-all',
              selectedCategory === 'api' 
                ? 'bg-primary text-white shadow-md transform scale-105' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            API & Backend
            <span class="ml-2 text-sm opacity-75">({{ categoryCount('api') }})</span>
          </button>
        </div>
      </div>

      <!-- NEW: Technology Filter -->
      <div v-if="allTechnologies.length > 0" class="mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filter by Technology</h3>
        <div class="flex flex-wrap gap-2 justify-center">
          <button 
            @click="selectedTech = null"
            :class="[
              'px-4 py-2 rounded-full text-sm font-medium transition-all',
              selectedTech === null 
                ? 'bg-secondary text-white shadow-md' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            All Technologies
          </button>
          <button 
            v-for="tech in allTechnologies"
            :key="tech"
            @click="selectedTech = tech"
            :class="[
              'px-4 py-2 rounded-full text-sm font-medium transition-all',
              selectedTech === tech 
                ? 'bg-secondary text-white shadow-md transform scale-105' 
                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            {{ tech }}
          </button>
        </div>
      </div>

      <!-- Results Count -->
      <div class="mb-6 text-center">
        <p class="text-gray-600 dark:text-gray-400">
          Showing <span class="font-semibold text-primary dark:text-blue-400">{{ filteredProjects.length }}</span> 
          {{ filteredProjects.length === 1 ? 'project' : 'projects' }}
          <span v-if="searchQuery || selectedCategory || selectedTech">
            matching your filters
          </span>
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Loading projects...</p>
      </div>

      <!-- Projects Grid -->
      <div v-else-if="filteredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
        <ProjectCard 
          v-for="project in filteredProjects" 
          :key="project.id" 
          :project="project" 
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12 animate-fade-in">
        <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No projects found</h3>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
          <span v-if="searchQuery">
            No projects match "{{ searchQuery }}"
          </span>
          <span v-else-if="selectedCategory || selectedTech">
            Try selecting different filters
          </span>
          <span v-else>
            No projects available yet
          </span>
        </p>
        
        <!-- Clear Filters Button -->
        <button 
          v-if="searchQuery || selectedCategory || selectedTech"
          @click="clearFilters"
          class="mt-6 px-6 py-3 bg-primary text-white rounded-lg hover:bg-blue-600 transition"
        >
          Clear All Filters
        </button>
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

// Filter states
const selectedCategory = ref(null);
const selectedTech = ref(null);
const searchQuery = ref('');

// Get all unique technologies from projects
const allTechnologies = computed(() => {
  const techs = new Set();
  projects.value.forEach(project => {
    if (project.technologies && Array.isArray(project.technologies)) {
      project.technologies.forEach(tech => techs.add(tech));
    }
  });
  return Array.from(techs).sort();
});

// Count projects by category
const categoryCount = (category) => {
  return projects.value.filter(p => p.category === category).length;
};

// Filter projects based on all criteria
const filteredProjects = computed(() => {
  let filtered = projects.value;
  
  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(p => 
      p.title?.toLowerCase().includes(query) ||
      p.description?.toLowerCase().includes(query) ||
      p.technologies?.some(tech => tech.toLowerCase().includes(query))
    );
  }
  
  // Filter by category
  if (selectedCategory.value) {
    filtered = filtered.filter(p => p.category === selectedCategory.value);
  }
  
  // Filter by technology
  if (selectedTech.value) {
    filtered = filtered.filter(p => 
      p.technologies?.includes(selectedTech.value)
    );
  }
  
  return filtered;
});

// Clear all filters
const clearFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = null;
  selectedTech.value = null;
};

onMounted(() => {
  portfolioStore.fetchProjects();
  portfolioStore.trackPageView(window.location.href);
});
</script>