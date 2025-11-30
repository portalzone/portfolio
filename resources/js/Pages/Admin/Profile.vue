<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700">Admin</span>
            <button @click="handleLogout" class="text-red-600 hover:text-red-800">Logout</button>
          </div>
        </div>
      </div>
    </nav>

    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-lg min-h-screen">
        <nav class="p-4">
          <router-link to="/admin" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">📊 Dashboard</router-link>
          <router-link to="/admin/projects" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">📁 Projects</router-link>
          <router-link to="/admin/skills" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">🛠️ Skills</router-link>
          <router-link to="/admin/blog" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">✍️ Blog</router-link>
          <router-link to="/admin/profile" class="block px-4 py-3 rounded-lg mb-2 bg-primary text-white">👤 Profile</router-link>
          <router-link to="/admin/analytics" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">📈 Analytics</router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <h2 class="text-3xl font-bold mb-8">Profile Settings</h2>

        <div class="bg-white rounded-lg shadow p-8 max-w-4xl">
          <form @submit.prevent="saveProfile" class="space-y-6">
            <!-- Personal Info -->
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium mb-2">Full Name</label>
                <input v-model="profile.name" type="text" required class="w-full px-4 py-2 border rounded-lg">
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Title/Role</label>
                <input v-model="profile.title" type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="Full Stack Developer">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Bio</label>
              <textarea v-model="profile.bio" class="w-full px-4 py-2 border rounded-lg" rows="4"></textarea>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium mb-2">Email</label>
                <input v-model="profile.email" type="email" required class="w-full px-4 py-2 border rounded-lg">
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Phone</label>
                <input v-model="profile.phone" type="tel" class="w-full px-4 py-2 border rounded-lg">
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Location</label>
              <input v-model="profile.location" type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="City, Country">
            </div>

            <!-- Social Links -->
            <div class="border-t pt-6">
              <h3 class="text-xl font-semibold mb-4">Social Links</h3>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium mb-2">GitHub URL</label>
                  <input v-model="profile.github_url" type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://github.com/username">
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2">LinkedIn URL</label>
                  <input v-model="profile.linkedin_url" type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://linkedin.com/in/username">
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2">Twitter URL</label>
                  <input v-model="profile.twitter_url" type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://twitter.com/username">
                </div>
              </div>
            </div>

            <!-- Resume -->
            <div class="border-t pt-6">
              <label class="block text-sm font-medium mb-2">Resume URL</label>
              <input v-model="profile.resume_url" type="url" class="w-full px-4 py-2 border rounded-lg" placeholder="https://example.com/resume.pdf">
            </div>

            <!-- Submit -->
            <div class="flex gap-4 pt-6">
              <button type="submit" :disabled="saving" class="btn-primary flex-1">
                {{ saving ? 'Saving...' : 'Save Changes' }}
              </button>
              <router-link to="/admin" class="bg-gray-200 px-4 py-2 rounded-lg flex-1 text-center">
                Cancel
              </router-link>
            </div>

            <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
              {{ successMessage }}
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useApi } from '../../composables/useApi';

const router = useRouter();
const authStore = useAuthStore();
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

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

onMounted(() => {
  fetchProfile();
});
</script>