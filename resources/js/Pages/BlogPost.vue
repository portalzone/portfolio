<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
      </div>

      <!-- Blog Post -->
      <article v-else-if="post" class="bg-white rounded-lg shadow-lg overflow-hidden">
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
          <div class="flex items-center gap-4 text-gray-600 mb-6">
            <span class="text-primary font-semibold">{{ post.category }}</span>
            <span>•</span>
            <span>{{ formatDate(post.published_at) }}</span>
            <span>•</span>
            <span>👁️ {{ post.views }} views</span>
          </div>

          <!-- Title -->
          <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ post.title }}</h1>

          <!-- Author -->
          <div class="flex items-center gap-3 mb-8 pb-8 border-b">
            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white text-xl">
              {{ post.user?.name?.charAt(0) || 'A' }}
            </div>
            <div>
              <p class="font-semibold">{{ post.user?.name || 'Admin' }}</p>
              <p class="text-sm text-gray-600">{{ post.user?.title || 'Author' }}</p>
            </div>
          </div>

          <!-- Content -->
          <div class="prose prose-lg max-w-none mb-8" v-html="post.content"></div>

          <!-- Tags -->
          <div v-if="post.tags && post.tags.length" class="mb-8 pb-8 border-b">
            <h3 class="text-lg font-semibold mb-3">Tags</h3>
            <div class="flex flex-wrap gap-2">
              <span 
                v-for="tag in post.tags" 
                :key="tag"
                class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm"
              >
                #{{ tag }}
              </span>
            </div>
          </div>

          <!-- Navigation -->
          <div class="flex justify-between">
            <router-link to="/blog" class="text-primary hover:text-blue-600 font-semibold">
              ← Back to Blog
            </router-link>
            <button 
              @click="sharePost"
              class="text-gray-600 hover:text-gray-800 font-semibold"
            >
              Share →
            </button>
          </div>
        </div>
      </article>

      <!-- Related Posts -->
      <div v-if="relatedPosts && relatedPosts.length > 0" class="mt-12">
        <h2 class="text-3xl font-bold mb-6">Related Posts</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <BlogCard 
            v-for="related in relatedPosts" 
            :key="related.id" 
            :post="related" 
          />
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Post not found</h2>
        <router-link to="/blog" class="text-primary hover:text-blue-600">
          ← Back to Blog
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useApi } from '../composables/useApi';
import BlogCard from '../Components/BlogCard.vue';

const route = useRoute();
const api = useApi();

const post = ref(null);
const relatedPosts = ref([]);
const loading = ref(true);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const sharePost = () => {
  if (navigator.share) {
    navigator.share({
      title: post.value.title,
      url: window.location.href
    });
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(window.location.href);
    alert('Link copied to clipboard!');
  }
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