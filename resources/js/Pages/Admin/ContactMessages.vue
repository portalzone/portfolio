<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold dark:text-white">Contact Messages</h2>
      <div class="flex gap-2">
        <button 
          @click="filterStatus = null"
          :class="filterStatus === null ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          All ({{ messages.length }})
        </button>
        <button 
          @click="filterStatus = 'new'"
          :class="filterStatus === 'new' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          New ({{ messages.filter(m => m.status === 'new').length }})
        </button>
        <button 
          @click="filterStatus = 'read'"
          :class="filterStatus === 'read' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
          class="px-4 py-2 rounded-lg transition"
        >
          Read ({{ messages.filter(m => m.status === 'read').length }})
        </button>
      </div>
    </div>

    <!-- Messages Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
      <table class="min-w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subject</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="message in filteredMessages" :key="message.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4 dark:text-white">{{ message.name }}</td>
            <td class="px-6 py-4 dark:text-gray-300">{{ message.email }}</td>
            <td class="px-6 py-4 dark:text-gray-300">{{ message.subject }}</td>
            <td class="px-6 py-4">
              <span 
                :class="{
                  'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': message.status === 'new',
                  'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200': message.status === 'read',
                  'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200': message.status === 'replied'
                }" 
                class="px-2 py-1 rounded text-sm capitalize"
              >
                {{ message.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm dark:text-gray-300">{{ formatDate(message.created_at) }}</td>
            <td class="px-6 py-4">
              <button @click="viewMessage(message)" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3">
                View
              </button>
              <button @click="deleteMessage(message.id)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                Delete
              </button>
            </td>
          </tr>
          
          <tr v-if="filteredMessages.length === 0">
            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
              No messages found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- View Message Modal -->
    <div v-if="viewingMessage" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-2xl w-full">
        <h3 class="text-2xl font-bold mb-4 dark:text-white">Message from {{ viewingMessage.name }}</h3>
        
        <div class="space-y-4 mb-6">
          <div>
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Email:</label>
            <p class="dark:text-white">{{ viewingMessage.email }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Subject:</label>
            <p class="dark:text-white">{{ viewingMessage.subject }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Message:</label>
            <p class="dark:text-white whitespace-pre-wrap">{{ viewingMessage.message }}</p>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Received:</label>
            <p class="dark:text-white">{{ formatDate(viewingMessage.created_at) }}</p>
          </div>
        </div>

        <div class="flex gap-4">
          <button 
            v-if="viewingMessage.status === 'new'"
            @click="markAsRead(viewingMessage.id)"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Mark as Read
          </button>
          <a 
            :href="`mailto:${viewingMessage.email}?subject=Re: ${viewingMessage.subject}`"
            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600"
          >
            Reply via Email
          </a>
          <button @click="viewingMessage = null" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg">
            Close
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const messages = ref([]);
const filterStatus = ref(null);
const viewingMessage = ref(null);

const filteredMessages = computed(() => {
  if (!filterStatus.value) return messages.value;
  return messages.value.filter(m => m.status === filterStatus.value);
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

const fetchMessages = async () => {
  const response = await api.getContactMessages();
  messages.value = response.data.messages;
};

const viewMessage = async (message) => {
  viewingMessage.value = message;
  if (message.status === 'new') {
    await markAsRead(message.id);
  }
};

const markAsRead = async (id) => {
  await api.updateContactStatus(id, 'read');
  fetchMessages();
  if (viewingMessage.value && viewingMessage.value.id === id) {
    viewingMessage.value.status = 'read';
  }
};

const deleteMessage = async (id) => {
  if (confirm('Are you sure you want to delete this message?')) {
    await api.deleteContactMessage(id);
    fetchMessages();
  }
};

onMounted(() => {
  fetchMessages();
});
</script>