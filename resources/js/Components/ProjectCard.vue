<template>
  <div class="card hover:shadow-xl transition-shadow cursor-pointer" @click="goToProject">
    <!-- Project Image -->
    <div class="h-48 bg-gradient-to-r from-primary to-secondary rounded-t-lg flex items-center justify-center overflow-hidden">
      <img 
        v-if="project.image_path" 
        :src="`/storage/${project.image_path}`" 
        :alt="project.title"
        class="w-full h-full object-cover"
      />
      <div v-else class="text-white text-6xl font-bold">
        {{ project.title.charAt(0) }}
      </div>
    </div>

    <!-- Project Content -->
    <div class="p-6">
      <div class="flex items-center justify-between mb-2">
        <span class="text-xs font-semibold text-primary uppercase">{{ project.category }}</span>
        <span v-if="project.featured" class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
          Featured
        </span>
      </div>

      <h3 class="text-xl font-bold mb-2">{{ project.title }}</h3>
      <p class="text-gray-600 mb-4 line-clamp-2">{{ project.description }}</p>

      <!-- Technologies -->
      <div class="flex flex-wrap gap-2 mb-4">
        <span 
          v-for="tech in project.technologies" 
          :key="tech"
          class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded"
        >
          {{ tech }}
        </span>
      </div>

      <!-- Stats -->
      <div class="flex items-center justify-between text-sm text-gray-500">
        <div class="flex items-center gap-4">
          <span>👁️ {{ project.views }} views</span>
        </div>
        <a 
          :href="project.url" 
          target="_blank"
          class="text-primary hover:text-blue-600 font-semibold"
          @click.stop
        >
          View Live →
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
});

const router = useRouter();

const goToProject = () => {
  router.push(`/projects/${props.project.slug}`);
};
</script>