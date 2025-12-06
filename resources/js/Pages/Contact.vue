<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-bold mb-4">Get In Touch</h1>
        <p class="text-gray-600 mb-8">
          Have a question or want to work together? I'd love to hear from you!
        </p>

        <!-- Success Message -->
        <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
          {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          {{ errorMessage }}
        </div>

        <!-- Contact Form -->
        <form @submit.prevent="submitForm" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
            <input 
              v-model="form.name"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Your name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="your.email@example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
            <input 
              v-model="form.subject"
              type="text" 
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="What's this about?"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
            <textarea 
              v-model="form.message"
              rows="6"
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              placeholder="Your message..."
            ></textarea>
          </div>

          <button 
            type="submit"
            :disabled="loading"
            class="btn-primary w-full"
          >
            {{ loading ? 'Sending...' : 'Send Message' }}
          </button>
        </form>

        <!-- Contact Info -->
        <div class="mt-12 pt-8 border-t border-gray-200">
          <h3 class="text-xl font-bold mb-4">Other Ways to Reach Me</h3>
          <div class="space-y-3 text-gray-600">
            <p>📧 Email: {{ profile.email }}</p>
            <p>📱 Phone: {{ profile.phone }}</p>
            <p>📍 Location: {{ profile.location }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../composables/useApi';
import { usePortfolioStore } from '../stores/portfolio';

const api = useApi();
const store = usePortfolioStore();
const profile = ref({});

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const loading = ref(false);
const success = ref(false);
const error = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

onMounted(async () => {
  await store.fetchProfile();
  profile.value = store.profile;
});

const submitForm = async () => {
  loading.value = true;
  success.value = false;
  error.value = false;

  try {
    const response = await api.submitContact(form.value);
    
    if (response.data.success) {
      success.value = true;
      successMessage.value = response.data.message;
      
      // Reset form
      form.value = {
        name: '',
        email: '',
        subject: '',
        message: ''
      };
    }
  } catch (err) {
    error.value = true;
    errorMessage.value = err.response?.data?.message || 'Something went wrong. Please try again.';
  } finally {
    loading.value = false;
  }
};
</script>