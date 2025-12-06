<template>
  <AdminLayout>
    <h2 class="text-3xl font-bold mb-8 dark:text-white">Profile Settings</h2>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 max-w-4xl transition-colors">
      <form @submit.prevent="saveProfile" class="space-y-6">
        <!-- Personal Info -->
        <div class="grid grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Full Name *</label>
            <input 
              v-model="profile.name" 
              type="text" 
              required 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Title/Role</label>
            <input 
              v-model="profile.title" 
              type="text" 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
              placeholder="Full Stack Developer"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium mb-2 dark:text-gray-300">Bio</label>
          <textarea 
            v-model="profile.bio" 
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
            rows="4"
            placeholder="Tell visitors about yourself..."
          ></textarea>
        </div>

        <!-- Contact Info -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="text-xl font-semibold mb-4 dark:text-white">Contact Information</h3>
          <div class="grid grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">Email *</label>
              <input 
                v-model="profile.email" 
                type="email" 
                required 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">Phone</label>
              <input 
                v-model="profile.phone" 
                type="tel" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                placeholder="+1 (555) 123-4567"
              />
            </div>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Location</label>
            <input 
              v-model="profile.location" 
              type="text" 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
              placeholder="City, Country"
            />
          </div>
        </div>

        <!-- Social Links -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="text-xl font-semibold mb-4 dark:text-white">Social Links</h3>
          
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">GitHub URL</label>
              <input 
                v-model="profile.github_url" 
                type="url" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                placeholder="https://github.com/username"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">LinkedIn URL</label>
              <input 
                v-model="profile.linkedin_url" 
                type="url" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                placeholder="https://linkedin.com/in/username"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">Twitter URL</label>
              <input 
                v-model="profile.twitter_url" 
                type="url" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                placeholder="https://twitter.com/username"
              />
            </div>
          </div>
        </div>

        <!-- Resume -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="text-xl font-semibold mb-4 dark:text-white">Resume</h3>
          <label class="block text-sm font-medium mb-2 dark:text-gray-300">Resume URL</label>
          <input 
            v-model="profile.resume_url" 
            type="url" 
            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
            placeholder="https://example.com/resume.pdf"
          />
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Upload your resume to a cloud service and paste the link here</p>
        </div>

        <!-- Submit -->
        <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
          <button type="submit" :disabled="saving" class="btn-primary flex-1">
            {{ saving ? 'Saving...' : 'Save Changes' }}
          </button>
          <router-link to="/admin" class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg flex-1 text-center hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            Cancel
          </router-link>
        </div>

        <div v-if="successMessage" class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
          {{ successMessage }}
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const profile = ref({
  name: '',
  title: '',
  bio: '',
  email: '',
  phone: '',
  location: '',
  github_url: '',
  linkedin_url: '',
  twitter_url: '',
  resume_url: ''
});

const saving = ref(false);
const successMessage = ref('');

const fetchProfile = async () => {
  const response = await api.getAdminProfile();
  profile.value = response.data;
};

const saveProfile = async () => {
  saving.value = true;
  successMessage.value = '';
  
  try {
    await api.updateAdminProfile(profile.value);
    successMessage.value = 'Profile updated successfully!';
    setTimeout(() => { successMessage.value = ''; }, 3000);
  } catch (error) {
    alert('Error saving profile: ' + error.message);
  } finally {
    saving.value = false;
  }
};

onMounted(() => {
  fetchProfile();
});
</script>