<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Admin Navbar -->
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700">Admin</span>
            <button @click="handleLogout" class="text-red-600 hover:text-red-800">
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Admin Sidebar & Content -->
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-lg min-h-screen">
        <nav class="p-4">
          <router-link to="/admin" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">
            📊 Dashboard
          </router-link>
          <router-link to="/admin/projects" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">
            📁 Projects
          </router-link>
          <router-link to="/admin/skills" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">
            🛠️ Skills
          </router-link>
          <router-link to="/admin/blog" class="block px-4 py-3 rounded-lg mb-2 bg-primary text-white">
            ✍️ Blog
          </router-link>
          <router-link to="/admin/profile" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">
            👤 Profile
          </router-link>
          <router-link to="/admin/analytics" class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100">
            📈 Analytics
          </router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold">Manage Blog Posts</h2>
          <button @click="showModal = true" class="btn-primary">
            + New Post
          </button>
        </div>

        <!-- Posts Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
          <table class="min-w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Published</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="post in posts" :key="post.id">
                <td class="px-6 py-4">
                  <div class="font-medium">{{ post.title }}</div>
                  <div class="text-sm text-gray-500">{{ post.slug }}</div>
                </td>
                <td class="px-6 py-4">{{ post.category }}</td>
                <td class="px-6 py-4">
                  <span 
                    :class="{
                      'bg-green-100 text-green-800': post.status === 'published',
                      'bg-yellow-100 text-yellow-800': post.status === 'draft',
                      'bg-gray-100 text-gray-800': post.status === 'archived'
                    }" 
                    class="px-2 py-1 rounded text-sm capitalize"
                  >
                    {{ post.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm">
                  {{ post.published_at ? formatDate(post.published_at) : '-' }}
                </td>
                <td class="px-6 py-4">{{ post.views }}</td>
                <td class="px-6 py-4">
                  <button @click="editPost(post)" class="text-blue-600 hover:text-blue-800 mr-3">
                    Edit
                  </button>
                  <button @click="deletePost(post.id)" class="text-red-600 hover:text-red-800">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
          <div class="bg-white rounded-lg p-8 max-w-4xl w-full my-8 max-h-screen overflow-y-auto">
            <h3 class="text-2xl font-bold mb-4">{{ editMode ? 'Edit' : 'New' }} Blog Post</h3>
            
            <form @submit.prevent="savePost" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2">Title</label>
                <input v-model="form.title" type="text" required class="w-full px-4 py-2 border rounded-lg">
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Excerpt (Short summary)</label>
                <textarea v-model="form.excerpt" class="w-full px-4 py-2 border rounded-lg" rows="2"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Content</label>
                <textarea 
                  v-model="form.content" 
                  required 
                  class="w-full px-4 py-2 border rounded-lg font-mono text-sm" 
                  rows="12"
                  placeholder="You can use HTML tags: <h2>, <p>, <strong>, <em>, <ul>, <li>, etc."
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Tip: Use HTML for formatting (e.g., &lt;h2&gt;Heading&lt;/h2&gt;, &lt;p&gt;Paragraph&lt;/p&gt;)</p>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-2">Category</label>
                  <input v-model="form.category" type="text" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div>
                  <label class="block text-sm font-medium mb-2">Status</label>
                  <select v-model="form.status" class="w-full px-4 py-2 border rounded-lg">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Tags (comma-separated)</label>
                <input v-model="tagsInput" type="text" class="w-full px-4 py-2 border rounded-lg" placeholder="laravel, vue, tutorial">
              </div>

              <div v-if="form.status === 'published'">
                <label class="block text-sm font-medium mb-2">Publish Date (optional)</label>
                <input v-model="form.published_at" type="datetime-local" class="w-full px-4 py-2 border rounded-lg">
              </div>

              <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1">{{ form.status === 'published' ? 'Publish' : 'Save' }}</button>
                <button type="button" @click="closeModal" class="bg-gray-200 px-4 py-2 rounded-lg flex-1">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useApi } from '../../composables/useApi';

const router = useRouter();
const authStore = useAuthStore();
const api = useApi();

const posts = ref([]);
const showModal = ref(false);
const editMode = ref(false);
const form = ref({
  title: '',
  excerpt: '',
  content: '',
  category: 'general',
  tags: [],
  status: 'draft',
  published_at: null
});

const tagsInput = computed({
  get: () => form.value.tags ? form.value.tags.join(', ') : '',
  set: (value) => {
    form.value.tags = value.split(',').map(t => t.trim()).filter(t => t);
  }
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const fetchPosts = async () => {
  const response = await api.getAdminPosts();
  posts.value = response.data;
};

const editPost = (post) => {
  form.value = { 
    ...post,
    published_at: post.published_at ? new Date(post.published_at).toISOString().slice(0, 16) : null
  };
  editMode.value = true;
  showModal.value = true;
};

const savePost = async () => {
  try {
    const postData = { ...form.value };
    
    // Set published_at to now if publishing for first time
    if (postData.status === 'published' && !postData.published_at) {
      postData.published_at = new Date().toISOString();
    }

    if (editMode.value) {
      await api.updatePost(form.value.id, postData);
    } else {
      await api.createPost(postData);
    }
    closeModal();
    fetchPosts();
  } catch (error) {
    alert('Error saving post: ' + error.message);
  }
};

const deletePost = async (id) => {
  if (confirm('Are you sure you want to delete this post?')) {
    await api.deletePost(id);
    fetchPosts();
  }
};

const closeModal = () => {
  showModal.value = false;
  editMode.value = false;
  form.value = {
    title: '',
    excerpt: '',
    content: '',
    category: 'general',
    tags: [],
    status: 'draft',
    published_at: null
  };
};

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

onMounted(() => {
  fetchPosts();
});
</script>