<template>
  <div
    class="group relative bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.6)] hover:border-gray-300 dark:hover:border-[#30363D] shadow-sm dark:shadow-none"
    @click="goToProject"
  >
    <!-- Category top accent border -->
    <div :class="topBorderColor" class="absolute top-0 left-0 right-0 h-0.5"></div>

    <!-- Image / Placeholder -->
    <div class="h-44 overflow-hidden relative">
      <img
        v-if="project.image_path"
        :src="`/storage/${project.image_path}`"
        :alt="project.title"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <div
        v-else
        :class="placeholderGradient"
        class="w-full h-full flex items-center justify-center relative"
      >
        <span :class="placeholderInitialColor" class="text-7xl font-black tracking-tighter select-none opacity-20">
          {{ project.title.charAt(0) }}
        </span>
        <div class="absolute inset-0 flex items-center justify-center">
          <span :class="placeholderInitialColor" class="text-5xl font-black tracking-tighter drop-shadow-lg select-none opacity-60">
            {{ project.title.charAt(0) }}
          </span>
        </div>
      </div>

      <!-- Category badge -->
      <div class="absolute top-3 left-3">
        <span :class="categoryBadgeColor" class="text-xs font-semibold px-2.5 py-1 rounded-md uppercase tracking-wider">
          {{ project.category }}
        </span>
      </div>

      <!-- Featured badge -->
      <div v-if="project.featured" class="absolute top-3 right-3">
        <span class="text-xs bg-yellow-50 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/30 px-2.5 py-1 rounded-md font-semibold">
          ★ Featured
        </span>
      </div>
    </div>

    <!-- Content -->
    <div class="p-5">
      <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2 leading-snug group-hover:text-primary transition-colors duration-200">
        {{ project.title }}
      </h3>
      <p class="text-gray-500 dark:text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">{{ project.description }}</p>

      <!-- Tech badges -->
      <div class="flex flex-wrap gap-1.5 mb-5">
        <span
          v-for="tech in project.technologies"
          :key="tech"
          :class="techBadgeColor(tech)"
          class="text-xs px-2 py-0.5 rounded font-medium"
        >
          {{ tech }}
        </span>
      </div>

      <!-- Footer row -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-[#21262D]">
        <span class="text-xs text-gray-400 dark:text-gray-600 font-mono">{{ project.views }} views</span>
        <a
          v-if="project.url"
          :href="project.url"
          target="_blank"
          class="inline-flex items-center gap-1.5 text-xs font-semibold bg-primary/10 text-primary border border-primary/20 px-3 py-1.5 rounded-md hover:bg-primary hover:text-[#0D1117] transition-all duration-200"
          @click.stop
        >
          View Live
          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
          </svg>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({ project: { type: Object, required: true } });
const router = useRouter();
const goToProject = () => router.push(`/projects/${props.project.slug}`);

const topBorderColor = computed(() => {
  const cat = props.project.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'bg-gradient-to-r from-purple-500 to-secondary';
  if (cat.includes('react')) return 'bg-gradient-to-r from-blue-500 to-cyan-400';
  return 'bg-gradient-to-r from-primary to-secondary';
});

const placeholderGradient = computed(() => {
  const cat = props.project.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'bg-gradient-to-br from-gray-100 dark:from-[#161B22] via-purple-100 dark:via-purple-900/30 to-gray-50 dark:to-[#0D1117]';
  if (cat.includes('react')) return 'bg-gradient-to-br from-gray-100 dark:from-[#161B22] via-blue-100 dark:via-blue-900/30 to-gray-50 dark:to-[#0D1117]';
  return 'bg-gradient-to-br from-gray-100 dark:from-[#161B22] via-cyan-100 dark:via-[#0d1b2e] to-gray-50 dark:to-[#0D1117]';
});

const placeholderInitialColor = computed(() => {
  const cat = props.project.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'text-purple-500 dark:text-purple-400';
  if (cat.includes('react')) return 'text-blue-500 dark:text-blue-400';
  return 'text-primary';
});

const categoryBadgeColor = computed(() => {
  const cat = props.project.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'bg-purple-50 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/30';
  if (cat.includes('react')) return 'bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30';
  return 'bg-primary/10 text-primary border border-primary/20';
});

const techBadgeColor = (tech) => {
  const t = tech.toLowerCase();
  if (t.includes('react') || t.includes('next')) return 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20';
  if (t.includes('laravel') || t.includes('php')) return 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20';
  if (t.includes('vue')) return 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-500/20';
  if (t.includes('ml') || t.includes('ai') || t.includes('tf-idf') || t.includes('langchain')) return 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20';
  if (t.includes('tailwind')) return 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/20';
  if (t.includes('node')) return 'bg-lime-50 dark:bg-lime-500/10 text-lime-600 dark:text-lime-400 border border-lime-200 dark:border-lime-500/20';
  if (t.includes('python')) return 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20';
  return 'bg-gray-100 dark:bg-[#21262D] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-[#30363D]';
};
</script>
