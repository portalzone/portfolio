<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-secondary text-white py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-5xl md:text-6xl font-bold mb-4">
            {{ profile?.name || 'Your Name' }}
          </h1>
          <p class="text-xl md:text-2xl mb-8">
            {{ profile?.title || 'Full Stack Developer' }}
          </p>
          <p class="text-lg max-w-2xl mx-auto mb-8">
            {{ profile?.bio || 'Building amazing web applications' }}
          </p>
          <div class="flex gap-4 justify-center">
            <router-link to="/projects" class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
              View Projects
            </router-link>
            <a href="#contact" class="border-2 border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition">
              Get in Touch
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Skills Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center mb-12">Skills & Technologies</h2>
        
        <div v-for="(categorySkills, category) in groupedSkills" :key="category" class="mb-8">
          <h3 class="text-2xl font-semibold mb-4 capitalize">{{ category }}</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <SkillBadge 
              v-for="skill in categorySkills" 
              :key="skill.id" 
              :skill="skill" 
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Projects -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-4xl font-bold">Featured Projects</h2>
          <router-link to="/projects" class="text-primary hover:text-blue-600 font-semibold">
            View All →
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <ProjectCard 
            v-for="project in featuredProjects" 
            :key="project.id" 
            :project="project" 
          />
        </div>
      </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
          <h2 class="text-4xl font-bold">Latest Posts</h2>
          <router-link to="/blog" class="text-primary hover:text-blue-600 font-semibold">
            View All →
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <BlogCard 
            v-for="post in latestPosts" 
            :key="post.id" 
            :post="post" 
          />
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-900 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-4">Let's Work Together</h2>
        <p class="text-xl mb-8">Have a project in mind? Let's discuss!</p>
        <a 
          :href="`mailto:${profile?.email}`"
          class="bg-primary text-white px-8 py-4 rounded-lg font-semibold hover:bg-blue-600 transition inline-block"
        >
          Contact Me
        </a>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePortfolioStore } from '../stores/portfolio';
import { storeToRefs } from 'pinia';
import ProjectCard from '../Components/ProjectCard.vue';
import BlogCard from '../Components/BlogCard.vue';
import SkillBadge from '../Components/SkillBadge.vue';

const portfolioStore = usePortfolioStore();
const { profile, projects, skills, posts } = storeToRefs(portfolioStore);

const featuredProjects = computed(() => {
  return projects.value.filter(p => p.featured).slice(0, 3);
});

const latestPosts = computed(() => {
  return posts.value.data ? posts.value.data.slice(0, 3) : [];
});

const groupedSkills = computed(() => {
  return skills.value;
});

onMounted(async () => {
  portfolioStore.fetchProfile();
  portfolioStore.fetchProjects({ featured: true });
  portfolioStore.fetchSkills();
  portfolioStore.fetchPosts({ per_page: 3 });
  portfolioStore.trackPageView(window.location.href);
});
</script>