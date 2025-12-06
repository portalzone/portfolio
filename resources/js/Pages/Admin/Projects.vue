<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold dark:text-white">Manage Projects</h2>
      <button @click="showModal = true" class="btn-primary">
        + Add Project
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Projects</div>
        <div class="text-2xl font-bold dark:text-white">{{ projects.length }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Active</div>
        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
          {{ projects.filter(p => p.status === 'active').length }}
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Featured</div>
        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
          {{ projects.filter(p => p.featured).length }}
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Views</div>
        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
          {{ projects.reduce((sum, p) => sum + (p.views || 0), 0) }}
        </div>
      </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors">
      <table class="min-w-full">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Featured</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Views</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="project in projects" :key="project.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4">
              <div class="font-medium dark:text-white">{{ project.title }}</div>
              <div class="text-sm text-gray-500 dark:text-gray-400">{{ project.slug }}</div>
            </td>
            <td class="px-6 py-4 dark:text-gray-300 capitalize">{{ project.category }}</td>
            <td class="px-6 py-4">
              <span 
                :class="{
                  'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': project.status === 'active',
                  'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200': project.status === 'inactive',
                  'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200': project.status === 'archived'
                }" 
                class="px-2 py-1 rounded text-sm capitalize"
              >
                {{ project.status }}
              </span>
            </td>
            <td class="px-6 py-4 dark:text-gray-300">
              <span v-if="project.featured" class="text-yellow-500">⭐ Yes</span>
              <span v-else class="text-gray-400">No</span>
            </td>
            <td class="px-6 py-4 dark:text-gray-300">{{ project.views || 0 }}</td>
            <td class="px-6 py-4">
              <button 
                @click="editProject(project)" 
                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 transition"
              >
                Edit
              </button>
              <button 
                @click="deleteProject(project.id)" 
                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
              >
                Delete
              </button>
            </td>
          </tr>

          <!-- Empty State -->
          <tr v-if="projects.length === 0">
            <td colspan="6" class="px-6 py-12 text-center">
              <div class="text-gray-500 dark:text-gray-400">
                <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-lg font-medium">No projects yet</p>
                <p class="text-sm mt-1">Click "Add Project" to create your first project</p>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-2xl w-full max-h-screen overflow-y-auto transition-colors">
        <h3 class="text-2xl font-bold mb-4 dark:text-white">{{ editMode ? 'Edit' : 'Add' }} Project</h3>
        
        <form @submit.prevent="saveProject" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Title *</label>
            <input 
              v-model="form.title" 
              type="text" 
              required 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              placeholder="Project title"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Description *</label>
            <textarea 
              v-model="form.description" 
              required 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary" 
              rows="4"
              placeholder="Describe your project"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">URL *</label>
            <input 
              v-model="form.url" 
              type="url" 
              required 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              placeholder="https://example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Technologies (comma-separated)</label>
            <input 
              v-model="technologiesInput" 
              type="text" 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              placeholder="Laravel, Vue.js, MySQL"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">Category</label>
              <select 
                v-model="form.category" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              >
                <option value="web">Web</option>
                <option value="mobile">Mobile</option>
                <option value="api">API</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2 dark:text-gray-300">Status</label>
              <select 
                v-model="form.status" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="archived">Archived</option>
              </select>
            </div>
          </div>

          <div class="flex items-center">
            <input 
              v-model="form.featured" 
              type="checkbox" 
              class="mr-2 w-4 h-4 text-primary rounded focus:ring-2 focus:ring-primary"
            />
            <label class="text-sm font-medium dark:text-gray-300">⭐ Featured Project</label>
          </div>

          <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary flex-1">
              {{ editMode ? 'Update' : 'Create' }} Project
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
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const api = useApi();

const projects = ref([]);
const showModal = ref(false);
const editMode = ref(false);
const form = ref({
  title: '',
  description: '',
  url: '',
  technologies: [],
  category: 'web',
  status: 'active',
  featured: false
});

const technologiesInput = computed({
  get: () => form.value.technologies ? form.value.technologies.join(', ') : '',
  set: (value) => {
    form.value.technologies = value.split(',').map(t => t.trim()).filter(t => t);
  }
});

const fetchProjects = async () => {
  const response = await api.getAdminProjects();
  projects.value = response.data;
};

const editProject = (project) => {
  form.value = { ...project };
  editMode.value = true;
  showModal.value = true;
};

const saveProject = async () => {
  try {
    if (editMode.value) {
      await api.updateProject(form.value.id, form.value);
    } else {
      await api.createProject(form.value);
    }
    closeModal();
    fetchProjects();
  } catch (error) {
    alert('Error saving project: ' + error.message);
  }
};

const deleteProject = async (id) => {
  if (confirm('Are you sure you want to delete this project?')) {
    await api.deleteProject(id);
    fetchProjects();
  }
};

const closeModal = () => {
  showModal.value = false;
  editMode.value = false;
  form.value = {
    title: '',
    description: '',
    url: '',
    technologies: [],
    category: 'web',
    status: 'active',
    featured: false
  };
};

onMounted(() => {
  fetchProjects();
});
</script>