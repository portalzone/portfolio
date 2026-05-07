<template>
  <div class="min-h-screen bg-gray-50 dark:bg-[#0D1117] py-12 px-4 transition-colors duration-300">
    <div class="max-w-4xl mx-auto">

      <div v-if="loading" class="flex items-center justify-center py-24">
        <div class="animate-spin rounded-full h-10 w-10 border-2 border-primary border-t-transparent"></div>
      </div>

      <div v-else-if="project">

        <!-- Hero banner -->
        <div class="relative h-72 rounded-2xl overflow-hidden mb-8 shadow-lg">
          <img
            v-if="project.image_path"
            :src="`/storage/${project.image_path}`"
            :alt="project.title"
            class="w-full h-full object-cover"
          />
          <div v-else :class="heroBg" class="w-full h-full flex items-center justify-center">
            <span class="text-9xl font-black opacity-10 select-none text-white">{{ project.title.charAt(0) }}</span>
            <span class="absolute text-7xl font-black text-white opacity-60 drop-shadow-2xl select-none">{{ project.title.charAt(0) }}</span>
          </div>
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
          <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="flex items-center gap-3 mb-2">
              <span :class="categoryBadge" class="text-xs font-semibold px-2.5 py-1 rounded-md uppercase tracking-wider">{{ project.category }}</span>
              <span v-if="project.featured" class="text-xs bg-yellow-50/20 text-yellow-300 border border-yellow-400/30 px-2.5 py-1 rounded-md font-semibold backdrop-blur-sm">★ Featured</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white leading-tight">{{ project.title }}</h1>
          </div>
        </div>

        <!-- Meta row -->
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-500 mb-8 font-mono">
          <span>{{ project.views }} views</span>
          <span class="text-gray-300 dark:text-gray-700">·</span>
          <span>{{ formatDate(project.created_at) }}</span>
          <span v-if="project.url" class="ml-auto">
            <a
              :href="project.url"
              target="_blank"
              class="inline-flex items-center gap-2 bg-primary text-[#0D1117] px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-cyan-300 transition-all duration-200"
            >
              View Live
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>
          </span>
        </div>

        <!-- Description -->
        <div class="bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-2xl p-8 mb-6 shadow-sm dark:shadow-none">
          <div class="description-body text-gray-700 dark:text-gray-300 leading-relaxed" v-html="parsedDescription"></div>
        </div>

        <!-- Technologies -->
        <div class="bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-2xl p-8 mb-6 shadow-sm dark:shadow-none">
          <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full bg-primary inline-block"></span>
            Technologies Used
          </h2>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="tech in project.technologies"
              :key="tech"
              :class="techBadge(tech)"
              class="text-xs px-3 py-1.5 rounded-md font-medium"
            >
              {{ tech }}
            </span>
          </div>
        </div>

        <!-- Back -->
        <div class="flex items-center gap-4">
          <router-link
            to="/projects"
            class="text-sm text-gray-500 hover:text-primary transition-colors duration-200 flex items-center gap-1.5"
          >
            ← Back to Projects
          </router-link>
        </div>

      </div>

      <div v-else class="text-center py-24">
        <p class="text-gray-500 dark:text-gray-400 mb-4">Project not found.</p>
        <router-link to="/projects" class="text-primary hover:underline text-sm">← Back to Projects</router-link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useApi } from '../composables/useApi';

const route = useRoute();
const api = useApi();
const project = ref(null);
const loading = ref(true);

const formatDate = (date) => new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

const heroBg = computed(() => {
  const cat = project.value?.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'bg-gradient-to-br from-purple-900 via-[#161B22] to-[#0D1117]';
  if (cat.includes('react')) return 'bg-gradient-to-br from-blue-900 via-[#161B22] to-[#0D1117]';
  return 'bg-gradient-to-br from-cyan-900 via-[#161B22] to-[#0D1117]';
});

const categoryBadge = computed(() => {
  const cat = project.value?.category?.toLowerCase() ?? '';
  if (cat.includes('ml') || cat.includes('ai')) return 'bg-purple-500/20 text-purple-300 border border-purple-500/30 backdrop-blur-sm';
  if (cat.includes('react')) return 'bg-blue-500/20 text-blue-300 border border-blue-500/30 backdrop-blur-sm';
  return 'bg-primary/20 text-primary border border-primary/30 backdrop-blur-sm';
});

const techBadge = (tech) => {
  const t = tech.toLowerCase();
  if (t.includes('react') || t.includes('next')) return 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20';
  if (t.includes('laravel') || t.includes('php')) return 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20';
  if (t.includes('vue')) return 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-500/20';
  if (t.includes('ml') || t.includes('ai') || t.includes('tf-idf') || t.includes('langchain')) return 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20';
  if (t.includes('tailwind')) return 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-500/20';
  if (t.includes('python')) return 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20';
  if (t.includes('node')) return 'bg-lime-50 dark:bg-lime-500/10 text-lime-600 dark:text-lime-400 border border-lime-200 dark:border-lime-500/20';
  return 'bg-gray-100 dark:bg-[#21262D] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-[#30363D]';
};

const parsedDescription = computed(() => {
  const text = project.value?.description;
  if (!text) return '';

  const lines = text.split('\n');
  let html = '';
  let inUl = false;
  let inOl = false;

  const closeList = () => {
    if (inUl) { html += '</ul>'; inUl = false; }
    if (inOl) { html += '</ol>'; inOl = false; }
  };

  const inlineFormat = (str) =>
    str
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.+?)\*/g, '<em>$1</em>')
      .replace(/`(.+?)`/g, '<code>$1</code>')
      .replace(/^([A-Za-z][^—\n]{1,40}) — /, '<strong>$1</strong> — ');

  for (const raw of lines) {
    const line = raw.trimEnd();
    const trimmed = line.trim();

    if (!trimmed) {
      closeList();
      continue;
    }

    // Ordered list: "1. item"
    if (/^\d+\.\s+/.test(trimmed)) {
      if (inUl) { html += '</ul>'; inUl = false; }
      if (!inOl) { html += '<ol>'; inOl = true; }
      html += `<li>${inlineFormat(trimmed.replace(/^\d+\.\s+/, ''))}</li>`;
      continue;
    }

    // Unordered list: "- item" / "* item" / "• item"
    if (/^[-*•]\s+/.test(trimmed)) {
      if (inOl) { html += '</ol>'; inOl = false; }
      if (!inUl) { html += '<ul>'; inUl = true; }
      html += `<li>${inlineFormat(trimmed.replace(/^[-*•]\s+/, ''))}</li>`;
      continue;
    }

    // Section heading: line ending with ":" and short enough
    if (/^[A-Za-z].{0,60}:$/.test(trimmed) && !trimmed.includes('  ')) {
      closeList();
      html += `<h3>${inlineFormat(trimmed)}</h3>`;
      continue;
    }

    // Normal paragraph
    closeList();
    html += `<p>${inlineFormat(trimmed)}</p>`;
  }

  closeList();
  return html;
});

onMounted(async () => {
  try {
    const response = await api.getProject(route.params.slug);
    project.value = response.data;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.description-body :deep(p) {
  margin-bottom: 1rem;
  font-size: 0.9375rem;
  line-height: 1.75;
}
.description-body :deep(h3) {
  font-size: 1rem;
  font-weight: 700;
  color: inherit;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
  opacity: 0.9;
}
.description-body :deep(ul),
.description-body :deep(ol) {
  padding-left: 1.25rem;
  margin-bottom: 1rem;
}
.description-body :deep(ul) { list-style-type: disc; }
.description-body :deep(ol) { list-style-type: decimal; }
.description-body :deep(li) {
  margin-bottom: 0.375rem;
  font-size: 0.9375rem;
  line-height: 1.7;
}
.description-body :deep(li ul) {
  margin-top: 0.375rem;
  margin-bottom: 0;
  list-style-type: circle;
}
.description-body :deep(strong) {
  font-weight: 600;
  color: inherit;
  opacity: 1;
}
.description-body :deep(em) { font-style: italic; }
.description-body :deep(code) {
  font-family: 'Fira Code', monospace;
  font-size: 0.8125rem;
  background: rgba(0, 212, 255, 0.08);
  border: 1px solid rgba(0, 212, 255, 0.2);
  color: #00D4FF;
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
}
</style>
