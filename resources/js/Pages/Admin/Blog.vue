<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors">
    <!-- Admin Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-primary dark:text-blue-400">Admin Panel</h1>
          </div>
          <div class="flex items-center gap-4">
            <span class="text-gray-700 dark:text-gray-300">{{ authStore.user?.name || 'Admin' }}</span>
            <button 
              @click="handleLogout" 
              class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Admin Sidebar & Content -->
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg min-h-screen transition-colors">
        <nav class="p-4 space-y-1">
          <router-link 
            to="/admin" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📊 Dashboard
          </router-link>
          <router-link 
            to="/admin/projects" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📁 Projects
          </router-link>
          <router-link 
            to="/admin/skills" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            🛠️ Skills
          </router-link>
          <router-link 
            to="/admin/blog" 
            class="block px-4 py-3 rounded-lg bg-primary text-white transition"
            active-class="bg-primary text-white"
          >
            ✍️ Blog
          </router-link>
          <router-link 
            to="/admin/profile" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            👤 Profile
          </router-link>
          <router-link 
            to="/admin/analytics" 
            class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
            active-class="bg-primary text-white dark:bg-primary"
          >
            📈 Analytics
          </router-link>

          <!-- NEW: Additional Menu Items -->
          <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <router-link 
              to="/admin/contact-messages" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              ✉️ Messages
            </router-link>
            <router-link 
              to="/admin/comments" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              💬 Comments
            </router-link>
            <router-link 
              to="/admin/newsletter" 
              class="block px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition"
              active-class="bg-primary text-white dark:bg-primary"
            >
              📧 Newsletter
            </router-link>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold dark:text-white">Manage Blog Posts</h2>
          <button @click="showModal = true" class="btn-primary">
            + New Post
          </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Posts</div>
            <div class="text-2xl font-bold dark:text-white">{{ posts.length }}</div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Published</div>
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
              {{ posts.filter(p => p.status === 'published').length }}
            </div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Drafts</div>
            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
              {{ posts.filter(p => p.status === 'draft').length }}
            </div>
          </div>
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
            <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Views</div>
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
              {{ posts.reduce((sum, p) => sum + (p.views || 0), 0) }}
            </div>
          </div>
        </div>

        <!-- Posts Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors">
          <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Published</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Views</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4">
                  <div class="font-medium dark:text-white">{{ post.title }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ post.slug }}</div>
                </td>
                <td class="px-6 py-4 dark:text-gray-300">{{ post.category }}</td>
                <td class="px-6 py-4">
                  <span 
                    :class="{
                      'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': post.status === 'published',
                      'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200': post.status === 'draft',
                      'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200': post.status === 'archived'
                    }" 
                    class="px-2 py-1 rounded text-sm capitalize"
                  >
                    {{ post.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm dark:text-gray-300">
                  {{ post.published_at ? formatDate(post.published_at) : '-' }}
                </td>
                <td class="px-6 py-4 dark:text-gray-300">{{ post.views || 0 }}</td>
                <td class="px-6 py-4">
                  <button 
                    @click="editPost(post)" 
                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 transition"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deletePost(post.id)" 
                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                  >
                    Delete
                  </button>
                </td>
              </tr>
              
              <!-- Empty State -->
              <tr v-if="posts.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-medium">No blog posts yet</p>
                    <p class="text-sm mt-1">Click "New Post" to create your first blog post</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto p-4">
          <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-4xl w-full my-8 max-h-screen overflow-y-auto transition-colors">
            <h3 class="text-2xl font-bold mb-4 dark:text-white">{{ editMode ? 'Edit' : 'New' }} Blog Post</h3>
            
            <form @submit.prevent="savePost" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Title *</label>
                <input 
                  v-model="form.title" 
                  type="text" 
                  required 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                  placeholder="Enter post title"
                />
              </div>

              <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Excerpt (Short summary)</label>
                <textarea 
                  v-model="form.excerpt" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                  rows="2"
                  placeholder="Brief summary of your post"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Content *</label>
                <textarea 
                  v-model="form.content" 
                  required 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg font-mono text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                  rows="12"
                  placeholder="You can use HTML tags: <h2>, <p>, <strong>, <em>, <ul>, <li>, etc."
                ></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tip: Use HTML for formatting (e.g., &lt;h2&gt;Heading&lt;/h2&gt;, &lt;p&gt;Paragraph&lt;/p&gt;)</p>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-2 dark:text-gray-300">Category *</label>
                  <input 
                    v-model="form.category" 
                    type="text" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                    placeholder="e.g., Laravel, Vue.js, Tutorial"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-2 dark:text-gray-300">Status</label>
                  <select 
                    v-model="form.status" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                  >
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Tags (comma-separated)</label>
                <input 
                  v-model="tagsInput" 
                  type="text" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
                  placeholder="laravel, vue, tutorial"
                />
              </div>

              <div v-if="form.status === 'published'">
                <label class="block text-sm font-medium mb-2 dark:text-gray-300">Publish Date (optional)</label>
                <input 
                  v-model="form.published_at" 
                  type="datetime-local" 
                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
                />
              </div>

              <div class="flex gap-4 pt-4">
                <button type="submit" class="btn-primary flex-1">
                  {{ form.status === 'published' ? 'Publish' : 'Save Draft' }}
                </button>
                <button 
                  type="button" 
                  @click="closeModal" 
                  class="bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2 rounded-lg flex-1 hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                  Cancel
                </button>
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