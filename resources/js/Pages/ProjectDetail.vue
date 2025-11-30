<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
      </div>

      <!-- Project Detail -->
      <div v-else-if="project" class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Project Image -->
        <div class="h-96 bg-gradient-to-r from-primary to-secondary flex items-center justify-center">
          <img 
            v-if="project.image_path" 
            :src="`/storage/${project.image_path}`" 
            :alt="project.title"
            class="w-full h-full object-cover"
          />
          <div v-else class="text-white text-9xl font-bold">
            {{ project.title.charAt(0) }}
          </div>
        </div>

        <!-- Content -->
        <div class="p-8">
          <!-- Header -->
          <div class="mb-6">
            <div class="flex items-center gap-4 mb-4">
              <span class="text-sm font-semibold text-primary uppercase">{{ project.category }}</span>
              <span v-if="project.featured" class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded">
                ⭐ Featured
              </span>
            </div>
            <h1 class="text-4xl font-bold mb-4">{{ project.title }}</h1>
            <div class="flex items-center gap-4 text-gray-600">
              <span>👁️ {{ project.views }} views</span>
              <span>•</span>
              <span>{{ formatDate(project.created_at) }}</span>
            </div>
          </div>

          <!-- Description -->
          <div class="prose max-w-none mb-8">
            <p class="text-lg text-gray-700 leading-relaxed">{{ project.description }}</p>
          </div>

          <!-- Technologies -->
          <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Technologies Used</h2>
            <div class="flex flex-wrap gap-3">
              <span 
                v-for="tech in project.technologies" 
                :key="tech"
                class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium"
              >
                {{ tech }}
              </span>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-4">
            <a 
              :href="project.url" 
              target="_blank"
              class="btn-primary flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
              </svg>
              View Live Project
            </a>
            <router-link to="/projects" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition">
              ← Back to Projects
            </router-link>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Project not found</h2>
        <router-link to="/projects" class="text-primary hover:text-blue-600">
          ← Back to Projects
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useApi } from '../composables/useApi';

const route = useRoute();
const api = useApi();

const project = ref(null);
const loading = ref(true);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(async () => {
  try {
    const response = await api.getProject(route.params.slug);
    project.value = response.data;
  } catch (error) {
    console.error('Error fetching project:', error);
  } finally {
    loading.value = false;
  }
});
</script>