<template>
  <article class="card hover:shadow-xl transition-shadow cursor-pointer" @click="goToPost">
    <!-- Featured Image -->
    <div 
      v-if="post.featured_image" 
      class="h-48 bg-gray-200 rounded-t-lg overflow-hidden"
    >
      <img 
        :src="`/storage/${post.featured_image}`" 
        :alt="post.title"
        class="w-full h-full object-cover"
      />
    </div>

    <!-- Content -->
    <div class="p-6">
      <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
        <span class="text-primary font-semibold">{{ post.category }}</span>
        <span>{{ formatDate(post.published_at) }}</span>
        <span>👁️ {{ post.views }} views</span>
      </div>

      <h2 class="text-2xl font-bold mb-3 hover:text-primary transition">
        {{ post.title }}
      </h2>

      <p class="text-gray-600 mb-4 line-clamp-3">
        {{ post.excerpt }}
      </p>

      <!-- Tags -->
      <div v-if="post.tags && post.tags.length" class="flex flex-wrap gap-2 mb-4">
        <span 
          v-for="tag in post.tags" 
          :key="tag"
          class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"
        >
          #{{ tag }}
        </span>
      </div>

      <!-- Author & Read More -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white">
            {{ post.user?.name?.charAt(0) || 'A' }}
          </div>
          <span class="text-sm text-gray-600">{{ post.user?.name || 'Admin' }}</span>
        </div>
        <span class="text-primary font-semibold hover:text-blue-600">
          Read More →
        </span>
      </div>
    </div>
  </article>
</template>

<script setup>
import { useRouter } from 'vue-router';

const props = defineProps({
  post: {
    type: Object,
    required: true
  }
});

const router = useRouter();

const goToPost = () => {
  router.push(`/blog/${props.post.slug}`);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>