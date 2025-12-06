<template>
  <div class="bg-gradient-to-r from-primary to-secondary rounded-lg p-8 text-white">
    <div class="max-w-2xl mx-auto text-center">
      <h3 class="text-2xl font-bold mb-2">Subscribe to My Newsletter</h3>
      <p class="mb-6 opacity-90">Get the latest updates on my projects and blog posts directly in your inbox.</p>

      <div v-if="success" class="bg-white text-green-600 p-4 rounded-lg mb-4">
        ✅ {{ successMessage }}
      </div>

      <form @submit.prevent="subscribe" class="flex gap-2">
        <input 
          v-model="email"
          type="email"
          placeholder="Enter your email"
          required
          class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white"
        />
        <button 
          type="submit"
          :disabled="loading"
          class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
        >
          {{ loading ? 'Subscribing...' : 'Subscribe' }}
        </button>
      </form>

      <p class="text-sm mt-4 opacity-75">No spam, unsubscribe anytime.</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useApi } from '../composables/useApi';

const api = useApi();
const email = ref('');
const loading = ref(false);
const success = ref(false);
const successMessage = ref('');

const subscribe = async () => {
  loading.value = true;
  success.value = false;

  try {
    const response = await api.subscribeNewsletter({ email: email.value });
    
    if (response.data.success) {
      success.value = true;
      successMessage.value = response.data.message;
      email.value = '';
    }
  } catch (error) {
    if (error.response?.data?.errors?.email) {
      successMessage.value = 'This email is already subscribed!';
    } else {
      successMessage.value = 'Something went wrong. Please try again.';
    }
    success.value = true;
  } finally {
    loading.value = false;
    
    setTimeout(() => {
      success.value = false;
    }, 5000);
  }
};
</script>