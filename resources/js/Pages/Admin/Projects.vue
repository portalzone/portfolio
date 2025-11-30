<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Copy the same navbar and sidebar from Dashboard.vue -->
    
    <main class="flex-1 p-8">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold">Manage Projects</h2>
        <button @click="showModal = true" class="btn-primary">
          + Add Project
        </button>
      </div>

      <!-- Projects Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Views</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="project in projects" :key="project.id">
              <td class="px-6 py-4">
                <div class="font-medium">{{ project.title }}</div>
                <div class="text-sm text-gray-500">{{ project.slug }}</div>
              </td>
              <td class="px-6 py-4">{{ project.category }}</td>
              <td class="px-6 py-4">
                <span :class="project.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 rounded text-sm">
                  {{ project.status }}
                </span>
              </td>
              <td class="px-6 py-4">{{ project.views }}</td>
              <td class="px-6 py-4">
                <button @click="editProject(project)" class="text-blue-600 hover:text-blue-800 mr-3">
                  Edit
                </button>
                <button @click="deleteProject(project.id)" class="text-red-600 hover:text-red-800">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Add/Edit Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-2xl w-full max-h-screen overflow-y-auto">
          <h3 class="text-2xl font-bold mb-4">{{ editMode ? 'Edit' : 'Add' }} Project</h3>
          
          <form @submit.prevent="saveProject" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-2">Title</label>
              <input v-model="form.title" type="text" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Description</label>
              <textarea v-model="form.description" required class="w-full px-4 py-2 border rounded-lg" rows="4"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">URL</label>
              <input v-model="form.url" type="url" required class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div>
              <label class="block text-sm font-medium mb-2">Technologies (comma-separated)</label>
              <input v-model="technologiesInput" type="text" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="flex gap-4">
              <div class="flex-1">
                <label class="block text-sm font-medium mb-2">Category</label>
                <select v-model="form.category" class="w-full px-4 py-2 border rounded-lg">
                  <option value="web">Web</option>
                  <option value="mobile">Mobile</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="flex-1">
                <label class="block text-sm font-medium mb-2">Status</label>
                <select v-model="form.status" class="w-full px-4 py-2 border rounded-lg">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="archived">Archived</option>
                </select>
              </div>
            </div>

            <div class="flex items-center">
              <input v-model="form.featured" type="checkbox" class="mr-2">
              <label class="text-sm font-medium">Featured Project</label>
            </div>

            <div class="flex gap-4 pt-4">
              <button type="submit" class="btn-primary flex-1">Save</button>
              <button type="button" @click="closeModal" class="bg-gray-200 px-4 py-2 rounded-lg flex-1">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useApi } from '../../composables/useApi';

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
  get: () => form.value.technologies.join(', '),
  set: (value) => {
    form.value.technologies = value.split(',').map(t => t.trim());
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
  if (editMode.value) {
    await api.updateProject(form.value.id, form.value);
  } else {
    await api.createProject(form.value);
  }
  closeModal();
  fetchProjects();
};

const deleteProject = async (id) => {
  if (confirm('Are you sure?')) {
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