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
          <router-link 
            to="/admin" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
          >
            📊 Dashboard
          </router-link>
          <router-link 
            to="/admin/projects" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
          >
            📁 Projects
          </router-link>
          <router-link 
            to="/admin/skills" 
            class="block px-4 py-3 rounded-lg mb-2 bg-primary text-white"
          >
            🛠️ Skills
          </router-link>
          <router-link 
            to="/admin/blog" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
          >
            ✍️ Blog
          </router-link>
          <router-link 
            to="/admin/profile" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
          >
            👤 Profile
          </router-link>
          <router-link 
            to="/admin/analytics" 
            class="block px-4 py-3 rounded-lg mb-2 hover:bg-gray-100"
          >
            📈 Analytics
          </router-link>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-3xl font-bold">Manage Skills</h2>
          <button @click="showModal = true" class="btn-primary">
            + Add Skill
          </button>
        </div>

        <!-- Skills by Category -->
        <div v-for="(categorySkills, category) in groupedSkills" :key="category" class="mb-8">
          <h3 class="text-2xl font-semibold mb-4 capitalize">{{ category }}</h3>
          
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proficiency</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="skill in categorySkills" :key="skill.id">
                  <td class="px-6 py-4 font-medium">{{ skill.name }}</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <div class="flex-1 bg-gray-200 rounded-full h-2.5 max-w-xs">
                        <div 
                          class="h-2.5 rounded-full"
                          :class="getColorClass(skill.proficiency)"
                          :style="{ width: `${skill.proficiency}%` }"
                        ></div>
                      </div>
                      <span class="text-sm">{{ skill.proficiency }}%</span>
                    </div>
                  </td>
                  <td class="px-6 py-4">{{ skill.order }}</td>
                  <td class="px-6 py-4">
                    <button @click="editSkill(skill)" class="text-blue-600 hover:text-blue-800 mr-3">
                      Edit
                    </button>
                    <button @click="deleteSkill(skill.id)" class="text-red-600 hover:text-red-800">
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-8 max-w-lg w-full">
            <h3 class="text-2xl font-bold mb-4">{{ editMode ? 'Edit' : 'Add' }} Skill</h3>
            
            <form @submit.prevent="saveSkill" class="space-y-4">
              <div>
                <label class="block text-sm font-medium mb-2">Skill Name</label>
                <input v-model="form.name" type="text" required class="w-full px-4 py-2 border rounded-lg">
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Category</label>
                <select v-model="form.category" class="w-full px-4 py-2 border rounded-lg">
                  <option value="frontend">Frontend</option>
                  <option value="backend">Backend</option>
                  <option value="database">Database</option>
                  <option value="tools">Tools</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Proficiency (1-100)</label>
                <input 
                  v-model.number="form.proficiency" 
                  type="number" 
                  min="1" 
                  max="100" 
                  required 
                  class="w-full px-4 py-2 border rounded-lg"
                >
                <div class="mt-2 bg-gray-200 rounded-full h-2.5">
                  <div 
                    class="h-2.5 rounded-full transition-all"
                    :class="getColorClass(form.proficiency)"
                    :style="{ width: `${form.proficiency}%` }"
                  ></div>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Order</label>
                <input 
                  v-model.number="form.order" 
                  type="number" 
                  min="0"
                  class="w-full px-4 py-2 border rounded-lg"
                >
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

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};

onMounted(() => {
  fetchSkills();
});
</script>