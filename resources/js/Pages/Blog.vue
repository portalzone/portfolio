<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Blog</h1>
        <p class="text-xl text-gray-600">Articles, tutorials, and thoughts on web development</p>
      </div>

      <!-- Search & Filter -->
      <div class="mb-8 flex flex-col md:flex-row gap-4">
        <!-- Search -->
        <div class="flex-1">
          <input 
            v-model="searchQuery"
            @input="handleSearch"
            type="text" 
            placeholder="Search articles..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
          />
        </div>

        <!-- Category Filter -->
        <select 
          v-model="selectedCategory"
          @change="handleFilter"
          class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat.category" :value="cat.category">
            {{ cat.category }} ({{ cat.count }})
          </option>
        </select>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading posts...</p>
      </div>

      <!-- Blog Posts Grid -->
      <div v-else-if="posts.data && posts.data.length > 0">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
          <BlogCard 
            v-for="post in posts.data" 
            :key="post.id" 
            :post="post" 
          />
        </div>

        <!-- Pagination -->
        <div v-if="posts.last_page > 1" class="flex justify-center gap-2">
          <button 
            @click="changePage(posts.current_page - 1)"
            :disabled="posts.current_page === 1"
            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>

          <button 
            v-for="page in visiblePages" 
            :key="page"
            @click="changePage(page)"
            :class="page === posts.current_page ? 'bg-primary text-white' : 'bg-white text-gray-700'"
            class="px-4 py-2 border rounded-lg hover:bg-gray-50"
          >
            {{ page }}
          </button>

          <button 
            @click="changePage(posts.current_page + 1)"
            :disabled="posts.current_page === posts.last_page"
            class="px-4 py-2 bg-white border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-xl font-medium text-gray-900">No posts found</h3>
        <p class="mt-1 text-gray-500">Try adjusting your search or filter</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useApi } from '../composables/useApi';
import BlogCard from '../Components/BlogCard.vue';

const api = useApi();

const posts = ref({ data: [], current_page: 1, last_page: 1 });
const categories = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const selectedCategory = ref('');

const visiblePages = computed(() => {
  const current = posts.value.current_page;
  const last = posts.value.last_page;
  const pages = [];
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i);
  }
  
  return pages;
});

const fetchPosts = async (params = {}) => {
  loading.value = true;
  try {
    const response = await api.getPosts(params);
    posts.value = response.data;
  } catch (error) {
    console.error('Error fetching posts:', error);
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await api.getCategories();
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
};

const handleSearch = () => {
  const params = {};
  if (searchQuery.value) params.search = searchQuery.value;
  if (selectedCategory.value) params.category = selectedCategory.value;
  fetchPosts(params);
};

const handleFilter = () => {
  const params = {};
  if (searchQuery.value) params.search = searchQuery.value;
  if (selectedCategory.value) params.category = selectedCategory.value;
  fetchPosts(params);
};

const changePage = (page) => {
  const params = { page };
  if (searchQuery.value) params.search = searchQuery.value;
  if (selectedCategory.value) params.category = selectedCategory.value;
  fetchPosts(params);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
  fetchPosts();
  fetchCategories();
});
</script>