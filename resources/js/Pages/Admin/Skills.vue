<template>
  <AdminLayout>
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold dark:text-white">Manage Skills</h2>
      <button @click="showModal = true" class="btn-primary">
        + Add Skill
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Skills</div>
        <div class="text-2xl font-bold dark:text-white">{{ skills.length }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Frontend</div>
        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
          {{ skills.filter(s => s.category === 'frontend').length }}
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Backend</div>
        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
          {{ skills.filter(s => s.category === 'backend').length }}
        </div>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Avg Proficiency</div>
        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
          {{ avgProficiency }}%
        </div>
      </div>
    </div>

    <!-- Skills by Category -->
    <div v-for="(categorySkills, category) in groupedSkills" :key="category" class="mb-8">
      <h3 class="text-2xl font-semibold mb-4 capitalize dark:text-white">
        {{ category }} ({{ categorySkills.length }})
      </h3>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden transition-colors">
        <table class="min-w-full">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Proficiency</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="skill in categorySkills" :key="skill.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
              <td class="px-6 py-4 font-medium dark:text-white">{{ skill.name }}</td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 max-w-xs">
                    <div 
                      class="h-2.5 rounded-full transition-all"
                      :class="getColorClass(skill.proficiency)"
                      :style="{ width: `${skill.proficiency}%` }"
                    ></div>
                  </div>
                  <span class="text-sm font-semibold dark:text-white">{{ skill.proficiency }}%</span>
                </div>
              </td>
              <td class="px-6 py-4 dark:text-gray-300">{{ skill.order || 0 }}</td>
              <td class="px-6 py-4">
                <button 
                  @click="editSkill(skill)" 
                  class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 mr-3 transition"
                >
                  Edit
                </button>
                <button 
                  @click="deleteSkill(skill.id)" 
                  class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="skills.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
      </svg>
      <p class="text-lg font-medium text-gray-900 dark:text-white">No skills yet</p>
      <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Click "Add Skill" to create your first skill</p>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-lg w-full transition-colors">
        <h3 class="text-2xl font-bold mb-4 dark:text-white">{{ editMode ? 'Edit' : 'Add' }} Skill</h3>
        
        <form @submit.prevent="saveSkill" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Skill Name *</label>
            <input 
              v-model="form.name" 
              type="text" 
              required 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              placeholder="e.g., Laravel, Vue.js, Docker"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Category *</label>
            <select 
              v-model="form.category" 
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
            >
              <option value="frontend">Frontend</option>
              <option value="backend">Backend</option>
              <option value="database">Database</option>
              <option value="tools">Tools</option>
              <option value="devops">DevOps</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">
              Proficiency ({{ form.proficiency }}%)
            </label>
            <input 
              v-model.number="form.proficiency" 
              type="range" 
              min="1" 
              max="100" 
              class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-primary"
            >
            <div class="mt-2 bg-gray-200 dark:bg-gray-700 rounded-full h-3">
              <div 
                class="h-3 rounded-full transition-all"
                :class="getColorClass(form.proficiency)"
                :style="{ width: `${form.proficiency}%` }"
              ></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
              <span>Beginner</span>
              <span>Intermediate</span>
              <span>Expert</span>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-2 dark:text-gray-300">Order (for sorting)</label>
            <input 
              v-model.number="form.order" 
              type="number" 
              min="0"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary"
              placeholder="0"
            />
          </div>

          <div class="flex gap-4 pt-4">
            <button type="submit" class="btn-primary flex-1">
              {{ editMode ? 'Update' : 'Create' }} Skill
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

const skills = ref([]);
const showModal = ref(false);
const editMode = ref(false);
const form = ref({
  name: '',
  category: 'frontend',
  proficiency: 50,
  order: 0
});

const groupedSkills = computed(() => {
  const grouped = {};
  skills.value.forEach(skill => {
    if (!grouped[skill.category]) {
      grouped[skill.category] = [];
    }
    grouped[skill.category].push(skill);
  });
  return grouped;
});

const avgProficiency = computed(() => {
  if (skills.value.length === 0) return 0;
  const sum = skills.value.reduce((acc, skill) => acc + skill.proficiency, 0);
  return Math.round(sum / skills.value.length);
});

const getColorClass = (proficiency) => {
  if (proficiency >= 80) return 'bg-green-500';
  if (proficiency >= 60) return 'bg-blue-500';
  if (proficiency >= 40) return 'bg-yellow-500';
  return 'bg-gray-500';
};

const fetchSkills = async () => {
  const response = await api.getAdminSkills();
  skills.value = response.data;
};

const editSkill = (skill) => {
  form.value = { ...skill };
  editMode.value = true;
  showModal.value = true;
};

const saveSkill = async () => {
  try {
    if (editMode.value) {
      await api.updateSkill(form.value.id, form.value);
    } else {
      await api.createSkill(form.value);
    }
    closeModal();
    fetchSkills();
  } catch (error) {
    alert('Error saving skill: ' + error.message);
  }
};

const deleteSkill = async (id) => {
  if (confirm('Are you sure you want to delete this skill?')) {
    await api.deleteSkill(id);
    fetchSkills();
  }
};

const closeModal = () => {
  showModal.value = false;
  editMode.value = false;
  form.value = {
    name: '',
    category: 'frontend',
    proficiency: 50,
    order: 0
  };
};

onMounted(() => {
  fetchSkills();
});
</script>