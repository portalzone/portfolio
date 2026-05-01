<template>
  <div id="app" class="min-h-screen bg-white dark:bg-[#0D1117] transition-colors duration-300">
    <Navbar v-if="!isAdminRoute" />
    <main>
      <router-view />
    </main>
    <Footer v-if="!isAdminRoute" />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useThemeStore } from './stores/theme';
import Navbar from './Components/Navbar.vue';
import Footer from './Components/Footer.vue';

const route = useRoute();
const themeStore = useThemeStore();
const isAdminRoute = computed(() => route.path.startsWith('/admin'));

onMounted(() => {
  themeStore.init();
});
</script>
