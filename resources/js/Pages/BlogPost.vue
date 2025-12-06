<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 transition-colors">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
        <p class="mt-4 text-gray-600 dark:text-gray-400">Loading post...</p>
      </div>

      <!-- Blog Post -->
      <article v-else-if="post" class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden animate-fade-in">
        <!-- Featured Image -->
        <div v-if="post.featured_image" class="h-96">
          <img 
            :src="`/storage/${post.featured_image}`" 
            :alt="post.title"
            class="w-full h-full object-cover"
          />
        </div>

        <!-- Content -->
        <div class="p-8">
          <!-- Meta Info -->
          <div class="flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-400 mb-6">
            <span class="text-primary dark:text-blue-400 font-semibold">{{ post.category }}</span>
            <span>•</span>
            <span>{{ formatDate(post.published_at) }}</span>
            <span>•</span>
            <span class="flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              {{ post.views }} views
            </span>
          </div>

          <!-- Title -->
          <h1 class="text-4xl md:text-5xl font-bold mb-6 dark:text-white">{{ post.title }}</h1>

          <!-- Author -->
          <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
            <div class="w-12 h-12 bg-gradient-to-r from-primary to-secondary rounded-full flex items-center justify-center text-white text-xl font-bold">
              {{ post.user?.name?.charAt(0) || 'A' }}
            </div>
            <div>
              <p class="font-semibold dark:text-white">{{ post.user?.name || 'Admin' }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ post.user?.title || 'Author' }}</p>
            </div>
          </div>

          <!-- Content -->
          <div 
            class="prose prose-lg dark:prose-invert max-w-none mb-8 dark:text-gray-300" 
            v-html="post.content"
          ></div>

          <!-- Tags -->
          <div v-if="post.tags && post.tags.length" class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 dark:text-white">Tags</h3>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="tag in post.tags" 
                :key="tag"
                class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition cursor-pointer"
              >
                #{{ tag }}
              </span>
            </div>
          </div>

          <!-- NEW: Social Share Buttons -->
          <div class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 dark:text-white">Share this post</h3>
            <SocialShare 
              :title="post.title"
              :url="currentUrl"
            />
          </div>

          <!-- Navigation -->
          <div class="flex justify-between items-center">
            <router-link 
              to="/blog" 
              class="text-primary dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 font-semibold flex items-center gap-2 transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Back to Blog
            </router-link>
          </div>
        </div>

        <!-- NEW: Comments Section -->
        <div class="border-t border-gray-200 dark:border-gray-700">
          <CommentSection :post-slug="route.params.slug" />
        </div>
      </article>

      <!-- Newsletter Section -->
      <div v-if="post" class="mt-12">
        <NewsletterBox />
      </div>

      <!-- Related Posts -->
      <div v-if="relatedPosts && relatedPosts.length > 0" class="mt-12">
        <h2 class="text-3xl font-bold mb-6 dark:text-white">Related Posts</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <BlogCard 
            v-for="related in relatedPosts" 
            :key="related.id" 
            :post="related" 
          />
        </div>
      </div>

      <!-- Error State -->
      <div v-else-if="!loading && !post" class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <svg class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 mt-4">Post not found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">The blog post you're looking for doesn't exist.</p>
        <router-link 
          to="/blog" 
          class="inline-flex items-center gap-2 text-primary dark:text-blue-400 hover:text-blue-600 dark:hover:text-blue-300 font-semibold"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to Blog
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useApi } from '../composables/useApi';
import BlogCard from '../Components/BlogCard.vue';
import SocialShare from '../Components/SocialShare.vue';
import CommentSection from '../Components/CommentSection.vue';
import NewsletterBox from '../Components/NewsletterBox.vue';

const route = useRoute();
const api = useApi();

const post = ref(null);
const relatedPosts = ref([]);
const loading = ref(true);

const currentUrl = computed(() => window.location.href);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

onMounted(async () => {
  try {
    const response = await api.getPost(route.params.slug);
    post.value = response.data.post;
    relatedPosts.value = response.data.related;
  } catch (error) {
    console.error('Error fetching post:', error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
/* Dark mode prose styles */
:deep(.prose-invert) {
  --tw-prose-body: rgb(209, 213, 219);
  --tw-prose-headings: rgb(255, 255, 255);
  --tw-prose-links: rgb(96, 165, 250);
  --tw-prose-bold: rgb(255, 255, 255);
  --tw-prose-counters: rgb(156, 163, 175);
  --tw-prose-bullets: rgb(107, 114, 128);
  --tw-prose-hr: rgb(55, 65, 81);
  --tw-prose-quotes: rgb(229, 231, 235);
  --tw-prose-quote-borders: rgb(55, 65, 81);
  --tw-prose-captions: rgb(156, 163, 175);
  --tw-prose-code: rgb(229, 231, 235);
  --tw-prose-pre-code: rgb(229, 231, 235);
  --tw-prose-pre-bg: rgb(31, 41, 55);
  --tw-prose-th-borders: rgb(55, 65, 81);
  --tw-prose-td-borders: rgb(55, 65, 81);
}
</style>