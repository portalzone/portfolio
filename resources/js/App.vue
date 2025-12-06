<template>
  <div id="app" class="min-h-screen bg-gray-50">
    <Navbar v-if="!isAdminRoute" />
    <main>
      <router-view />
    </main>
    <Footer v-if="!isAdminRoute" />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './Components/Navbar.vue';
import Footer from './Components/Footer.vue';
import { onMounted } from 'vue';
import { useThemeStore } from './stores/theme';


const route = useRoute();
const isAdminRoute = computed(() => route.path.startsWith('/admin'));
const themeStore = useThemeStore();

onMounted(() => {
  themeStore.init();
});
</script>