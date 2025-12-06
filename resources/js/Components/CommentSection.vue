<template>
  <div class="mt-12 pt-8 border-t border-gray-200">
    <h2 class="text-2xl font-bold mb-6">Comments ({{ comments.length }})</h2>

    <!-- Comment Form -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8">
      <h3 class="text-lg font-semibold mb-4">Leave a Comment</h3>
      
      <div v-if="success" class="bg-green-100 text-green-700 p-3 rounded mb-4">
        Comment submitted! It will appear after approval.
      </div>

      <form @submit.prevent="submitComment" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input 
            v-model="form.name"
            type="text"
            placeholder="Name *"
            required
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
          />
          <input 
            v-model="form.email"
            type="email"
            placeholder="Email *"
            required
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
          />
        </div>
        <input 
          v-model="form.website"
          type="url"
          placeholder="Website (optional)"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
        />
        <textarea 
          v-model="form.content"
          rows="4"
          placeholder="Your comment *"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
        ></textarea>
        <button type="submit" class="btn-primary" :disabled="loading">
          {{ loading ? 'Submitting...' : 'Post Comment' }}
        </button>
      </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-6">
      <div 
        v-for="comment in comments" 
        :key="comment.id"
        class="bg-white rounded-lg p-6 shadow-sm"
      >
        <div class="flex items-start gap-4">
          <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold">
            {{ comment.name.charAt(0).toUpperCase() }}
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
              <h4 class="font-semibold">{{ comment.name }}</h4>
              <span class="text-sm text-gray-500">•</span>
              <span class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</span>
            </div>
            <p class="text-gray-700">{{ comment.content }}</p>
            <a 
              v-if="comment.website" 
              :href="comment.website" 
              target="_blank"
              class="text-sm text-primary hover:underline mt-2 inline-block"
            >
              Visit Website →
            </a>
          </div>
        </div>
      </div>

      <div v-if="comments.length === 0" class="text-center py-12 text-gray-500">
        No comments yet. Be the first to comment!
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../composables/useApi';

const props = defineProps({
  postSlug: {
    type: String,
    required: true
  }
});

const api = useApi();
const comments = ref([]);
const loading = ref(false);
const success = ref(false);

const form = ref({
  name: '',
  email: '',
  website: '',
  content: ''
});

onMounted(async () => {
  await loadComments();
});

const loadComments = async () => {
  try {
    const response = await api.getComments(props.postSlug);
    comments.value = response.data.comments;
  } catch (error) {
    console.error('Error loading comments:', error);
  }
};

const submitComment = async () => {
  loading.value = true;
  success.value = false;

  try {
    await api.submitComment(props.postSlug, form.value);
    success.value = true;
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      website: '',
      content: ''
    };

    // Reload comments after 2 seconds
    setTimeout(() => {
      success.value = false;
    }, 3000);
  } catch (error) {
    console.error('Error submitting comment:', error);
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>