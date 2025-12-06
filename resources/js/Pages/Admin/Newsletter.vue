<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold dark:text-white">Newsletter Subscribers</h2>
      <button @click="exportSubscribers" class="btn-primary">
        📥 Export CSV
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Subscribers</div>
        <div class="text-3xl font-bold dark:text-white">{{ stats.total }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Active</div>
        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ stats.active }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Verified</div>
        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ stats.verified }}</div>
      </div>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
      <table class="min-w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Verified</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subscribed</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="subscriber in subscribers" :key="subscriber.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4 dark:text-white">{{ subscriber.email }}</td>
            <td class="px-6 py-4 dark:text-gray-300">{{ subscriber.name || '-' }}</td>
            <td class="px-6 py-4">
              <span 
                :class="{
                  'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': subscriber.status === 'active',
                  'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200': subscriber.status === 'unsubscribed'
                }" 
                class="px-2 py-1 rounded text-sm capitalize"
              >
                {{ subscriber.status }}
              </span>
            </td>
            <td class="px-6 py-4 dark:text-gray-300">
              {{ subscriber.verified_at ? '✓ Yes' : '✗ No' }}
            </td>
            <td class="px-6 py-4 text-sm dark:text-gray-300">{{ formatDate(subscriber.created_at) }}</td>
            <td class="px-6 py-4">
              <button 
                @click="deleteSubscriber(subscriber.id)" 
                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
              >
                Delete
              </button>
            </td>
          </tr>
          
          <tr v-if="subscribers.length === 0">
            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
              No subscribers yet
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const subscribers = ref([]);
const stats = ref({
  total: 0,
  active: 0,
  verified: 0
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const fetchSubscribers = async () => {
  const response = await api.getNewsletterSubscribers();
  subscribers.value = response.data.subscribers;
  stats.value = response.data.stats;
};

const deleteSubscriber = async (id) => {
  if (confirm('Are you sure you want to delete this subscriber?')) {
    await api.deleteNewsletterSubscriber(id);
    fetchSubscribers();
  }
};

const exportSubscribers = async () => {
  const response = await api.exportNewsletterSubscribers();
  const emails = response.data.subscribers.map(s => s.email).join('\n');
  
  const blob = new Blob([emails], { type: 'text/plain' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'newsletter-subscribers.txt';
  a.click();
};

onMounted(() => {
  fetchSubscribers();
});
</script>