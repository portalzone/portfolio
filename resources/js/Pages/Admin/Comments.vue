<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold dark:text-white">Comments</h2>
      <div class="flex gap-2">
        <button 
          @click="filterStatus = null"
          :class="filterStatus === null ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          All ({{ comments.length }})
        </button>
        <button 
          @click="filterStatus = 'pending'"
          :class="filterStatus === 'pending' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          Pending ({{ comments.filter(c => c.status === 'pending').length }})
        </button>
        <button 
          @click="filterStatus = 'approved'"
          :class="filterStatus === 'approved' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          Approved ({{ comments.filter(c => c.status === 'approved').length }})
        </button>
      </div>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
      <div 
        v-for="comment in filteredComments" 
        :key="comment.id"
        class="bg-white dark:bg-gray-800 rounded-lg shadow p-6"
      >
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xl">
              {{ comment.name.charAt(0).toUpperCase() }}
            </div>
            <div>
              <h4 class="font-semibold dark:text-white">{{ comment.name }}</h4>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ comment.email }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-500">
                On: <span class="font-medium">{{ comment.post?.title }}</span>
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ formatDate(comment.created_at) }}</p>
            </div>
          </div>
          <span 
            :class="{
              'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200': comment.status === 'pending',
              'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': comment.status === 'approved',
              'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200': comment.status === 'spam'
            }" 
            class="px-3 py-1 rounded text-sm capitalize"
          >
            {{ comment.status }}
          </span>
        </div>

        <p class="text-gray-700 dark:text-gray-300 mb-4">{{ comment.content }}</p>

        <div class="flex gap-3">
          <button 
            v-if="comment.status !== 'approved'"
            @click="approveComment(comment.id)" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm"
          >
            ✓ Approve
          </button>
          <button 
            v-if="comment.status !== 'spam'"
            @click="markAsSpam(comment.id)" 
            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm"
          >
            ⚠ Mark as Spam
          </button>
          <button 
            @click="deleteComment(comment.id)" 
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm"
          >
            🗑 Delete
          </button>
          <a 
            v-if="comment.website"
            :href="comment.website" 
            target="_blank"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm"
          >
            🔗 Visit Website
          </a>
        </div>
      </div>

      <div v-if="filteredComments.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400">
        No comments found
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const comments = ref([]);
const filterStatus = ref(null);

const filteredComments = computed(() => {
  if (!filterStatus.value) return comments.value;
  return comments.value.filter(c => c.status === filterStatus.value);
});

const formatDate = (date) => {
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchComments = async () => {
  const response = await api.getAdminComments();
  comments.value = response.data.comments;
};

const approveComment = async (id) => {
  await api.approveComment(id);
  fetchComments();
};

const markAsSpam = async (id) => {
  if (confirm('Mark this comment as spam?')) {
    await api.markCommentAsSpam(id);
    fetchComments();
  }
};

const deleteComment = async (id) => {
  if (confirm('Are you sure you want to delete this comment?')) {
    await api.deleteComment(id);
    fetchComments();
  }
};

onMounted(() => {
  fetchComments();
});
</script>