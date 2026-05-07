<template>
  <article
    class="group bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl overflow-hidden cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-lg dark:hover:shadow-[0_20px_25px_-5px_rgba(0,0,0,0.6)] hover:border-gray-300 dark:hover:border-[#30363D] shadow-sm dark:shadow-none"
    @click="goToPost"
  >
    <div v-if="post.featured_image" class="h-44 overflow-hidden">
      <img
        :src="`/storage/${post.featured_image}`"
        :alt="post.title"
        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
    </div>
    <div v-else class="h-44 bg-gradient-to-br from-gray-100 dark:from-[#161B22] via-cyan-50 dark:via-[#0d1b2e] to-gray-50 dark:to-[#0D1117] flex items-center justify-center relative overflow-hidden">
      <span class="text-7xl font-black text-primary opacity-10 select-none">{{ post.title?.charAt(0) }}</span>
      <div class="absolute inset-0 flex items-center justify-center">
        <span class="text-5xl font-black text-primary opacity-50 drop-shadow-lg select-none">{{ post.title?.charAt(0) }}</span>
      </div>
    </div>

    <div class="p-5">
      <div class="flex items-center gap-3 text-xs mb-3">
        <span class="bg-primary/10 text-primary border border-primary/20 px-2.5 py-1 rounded-md font-semibold uppercase tracking-wider">{{ post.category }}</span>
        <span class="text-gray-400 dark:text-gray-600 font-mono">{{ formatDate(post.published_at) }}</span>
      </div>

      <h2 class="text-base font-bold text-gray-900 dark:text-white mb-2 leading-snug group-hover:text-primary transition-colors duration-200 line-clamp-2">
        {{ post.title }}
      </h2>

      <p class="text-gray-500 dark:text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">
        {{ post.excerpt }}
      </p>

      <div v-if="post.tags && post.tags.length" class="flex flex-wrap gap-1.5 mb-4">
        <span
          v-for="tag in post.tags"
          :key="tag"
          class="text-xs bg-gray-100 dark:bg-[#21262D] text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-[#30363D] px-2 py-0.5 rounded font-medium"
        >
          #{{ tag }}
        </span>
      </div>

      <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-[#21262D]">
        <div class="flex items-center gap-2">
          <div class="w-7 h-7 rounded-full bg-primary/10 border border-primary/20 flex items-center justify-center text-primary text-xs font-bold">
            {{ post.user?.name?.charAt(0) || 'A' }}
          </div>
          <span class="text-xs text-gray-500 dark:text-gray-500">{{ post.user?.name || 'Admin' }}</span>
        </div>
        <span class="text-xs text-gray-400 dark:text-gray-600 font-mono">{{ post.views }} views</span>
      </div>
    </div>
  </article>
</template>

<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  post: { type: Object, required: true }
});

const router = useRouter();
const goToPost = () => router.push(`/blog/${props.post.slug}`);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>
