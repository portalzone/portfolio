<template>
  <div>

    <!-- ─── HERO ─────────────────────────────────────────────────────────── -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gray-50 dark:bg-[#0D1117] transition-colors duration-300">

      <!-- Dark-mode animated backdrop -->
      <div class="absolute inset-0 opacity-0 dark:opacity-100 bg-gradient-to-br from-[#0D1117] via-[#0d1b2e] to-[#0D1117] animate-gradient-shift bg-[length:400%_400%] transition-opacity duration-300 pointer-events-none"></div>

      <!-- Floating orbs (dark only) -->
      <div class="absolute top-1/4 left-1/4 w-72 h-72 rounded-full blur-3xl animate-float pointer-events-none opacity-0 dark:opacity-100 bg-primary/5 transition-opacity duration-300"></div>
      <div class="absolute bottom-1/4 right-1/4 w-96 h-96 rounded-full blur-3xl animate-float-slow pointer-events-none opacity-0 dark:opacity-100 bg-secondary/5 transition-opacity duration-300"></div>

      <!-- Subtle grid (dark only) -->
      <div class="absolute inset-0 opacity-0 dark:opacity-100 pointer-events-none transition-opacity duration-300"
           style="background-image: linear-gradient(rgba(0,212,255,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(0,212,255,0.04) 1px, transparent 1px); background-size: 64px 64px;"></div>

      <!-- Light-mode soft gradient -->
      <div class="absolute inset-0 opacity-100 dark:opacity-0 pointer-events-none transition-opacity duration-300"
           style="background: radial-gradient(ellipse at 60% 20%, rgba(0,212,255,0.06) 0%, transparent 60%), radial-gradient(ellipse at 20% 80%, rgba(124,58,237,0.05) 0%, transparent 60%);"></div>

      <!-- Content -->
      <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">

        <!-- Status badge -->
        <div class="inline-flex items-center gap-2 bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] text-gray-600 dark:text-primary text-xs font-mono px-4 py-2 rounded-full mb-8 animate-fade-in shadow-sm dark:shadow-none">
          <span class="w-2 h-2 rounded-full bg-primary animate-pulse inline-block"></span>
          Available for opportunities · St. John's, NL
        </div>

        <!-- Name -->
        <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold text-gray-900 dark:text-white tracking-tight mb-4 animate-slide-up" style="animation-delay: 0.1s; opacity: 0;">
          VICTOR
          <span class="block text-primary drop-shadow-[0_0_30px_rgba(0,212,255,0.3)]">
            CHISOM MUOJEKE
          </span>
        </h1>

        <!-- Tagline -->
        <p class="text-xl sm:text-2xl text-gray-600 dark:text-gray-300 font-light mb-3 animate-slide-up" style="animation-delay: 0.2s; opacity: 0;">
          Full-Stack Engineer &amp; ML Systems Developer
        </p>

        <!-- Sub-tagline -->
        <p class="text-base sm:text-lg text-gray-500 dark:text-gray-500 font-mono mb-10 animate-slide-up" style="animation-delay: 0.3s; opacity: 0;">
          Building AI-powered systems ·
          <span class="text-red-500 dark:text-red-400">Laravel</span> ·
          <span class="text-blue-500 dark:text-blue-400">React</span> ·
          <span class="text-green-600 dark:text-green-400">Vue.js</span> ·
          <span class="text-purple-600 dark:text-purple-400">ML&nbsp;Engines</span>
        </p>

        <!-- CTAs -->
        <div class="flex flex-wrap gap-4 justify-center mb-12 animate-slide-up" style="animation-delay: 0.4s; opacity: 0;">
          <router-link
            to="/projects"
            class="group inline-flex items-center gap-2 bg-primary text-[#0D1117] px-7 py-3.5 rounded-lg font-semibold text-sm transition-all duration-200 hover:bg-cyan-300 hover:shadow-[0_0_20px_rgba(0,212,255,0.4)] hover:-translate-y-0.5"
          >
            View Projects
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>

          <router-link
            to="/contact"
            class="inline-flex items-center gap-2 border border-primary/50 text-primary px-7 py-3.5 rounded-lg font-semibold text-sm transition-all duration-200 hover:border-primary hover:bg-primary/10 hover:-translate-y-0.5"
          >
            Get in Touch
          </router-link>

          <a
            v-if="profile?.resume_url"
            :href="profile.resume_url"
            target="_blank"
            download
            class="inline-flex items-center gap-2 border border-gray-300 dark:border-[#30363D] text-gray-600 dark:text-gray-400 px-7 py-3.5 rounded-lg font-semibold text-sm transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 hover:text-gray-900 dark:hover:text-white hover:-translate-y-0.5"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Resume
          </a>
        </div>

        <!-- Social icons -->
        <div class="flex gap-5 justify-center animate-slide-up" style="animation-delay: 0.5s; opacity: 0;">
          <a v-if="profile?.github_url" :href="profile.github_url" target="_blank"
             class="text-gray-400 dark:text-gray-600 hover:text-gray-900 dark:hover:text-primary transition-colors duration-200" title="GitHub">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
            </svg>
          </a>
          <a v-if="profile?.linkedin_url" :href="profile.linkedin_url" target="_blank"
             class="text-gray-400 dark:text-gray-600 hover:text-gray-900 dark:hover:text-primary transition-colors duration-200" title="LinkedIn">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
            </svg>
          </a>
          <a v-if="profile?.twitter_url" :href="profile.twitter_url" target="_blank"
             class="text-gray-400 dark:text-gray-600 hover:text-gray-900 dark:hover:text-primary transition-colors duration-200" title="Twitter">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
              <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
            </svg>
          </a>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-gray-400 dark:text-gray-600 animate-float">
          <span class="text-xs font-mono">scroll</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </div>
      </div>
    </section>

    <!-- ─── SKILLS ─────────────────────────────────────────────────────────── -->
    <section class="py-24 bg-gray-50 dark:bg-[#0D1117] transition-colors duration-300">
      <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-3">Tech Stack</h2>
          <p class="text-gray-500 text-sm">What I build with</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div v-for="group in techStack" :key="group.label"
               class="bg-white dark:bg-[#161B22] border border-gray-200 dark:border-[#21262D] rounded-xl p-5 hover:border-gray-300 dark:hover:border-[#30363D] transition-colors duration-200 shadow-sm dark:shadow-none">
            <div class="flex items-center gap-2.5 mb-4">
              <div :class="group.accent" class="w-1 h-5 rounded-full flex-shrink-0"></div>
              <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ group.label }}</h3>
            </div>
            <div class="flex flex-wrap gap-2">
              <span v-for="tech in group.items" :key="tech"
                    :class="group.badge"
                    class="text-xs px-2.5 py-1 rounded-md font-medium">
                {{ tech }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ─── FEATURED PROJECTS ──────────────────────────────────────────────── -->
    <section class="py-24 bg-white dark:bg-[#161B22]/30 transition-colors duration-300">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between items-end mb-16">
          <div>
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Featured Projects</h2>
            <p class="text-gray-500 text-sm">Selected work</p>
          </div>
          <router-link to="/projects" class="text-sm text-gray-500 dark:text-gray-500 hover:text-primary transition-colors duration-200 font-mono flex items-center gap-1">
            View all
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>
        </div>

        <div v-if="featuredProjects.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <ProjectCard v-for="project in featuredProjects" :key="project.id" :project="project"/>
        </div>
        <div v-else class="text-center py-16 text-gray-400 dark:text-gray-600 text-sm">
          No featured projects yet — add them via the admin panel.
        </div>
      </div>
    </section>

    <!-- ─── LATEST POSTS ───────────────────────────────────────────────────── -->
    <section class="py-24 bg-gray-50 dark:bg-[#0D1117] transition-colors duration-300">
      <div class="max-w-6xl mx-auto px-6">
        <div class="flex justify-between items-end mb-16">
          <div>
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Latest Posts</h2>
            <p class="text-gray-500 text-sm">Thoughts &amp; writing</p>
          </div>
          <router-link to="/blog" class="text-sm text-gray-500 dark:text-gray-500 hover:text-primary transition-colors duration-200 font-mono flex items-center gap-1">
            View all
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </router-link>
        </div>

        <div v-if="latestPosts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <BlogCard v-for="post in latestPosts" :key="post.id" :post="post"/>
        </div>
        <div v-else class="text-center py-16 text-gray-400 dark:text-gray-600 text-sm">
          No posts yet.
        </div>
      </div>
    </section>

    <!-- ─── NEWSLETTER ─────────────────────────────────────────────────────── -->
    <section class="py-16 bg-white dark:bg-[#161B22]/30 transition-colors duration-300">
      <div class="max-w-2xl mx-auto px-6">
        <NewsletterBox />
      </div>
    </section>

    <!-- ─── CONTACT CTA ────────────────────────────────────────────────────── -->
    <section id="contact" class="py-24 bg-gray-900 dark:bg-[#0D1117] relative overflow-hidden transition-colors duration-300">
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary/5 to-transparent pointer-events-none"></div>
      <div class="relative z-10 max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Let's Work Together</h2>
        <p class="text-gray-400 text-lg mb-10">
          Open to full-stack, ML, and AI-driven projects.<br>Based in St. John's, NL — available remotely worldwide.
        </p>

        <div class="flex flex-wrap gap-4 justify-center mb-12">
          <a v-if="profile?.email" :href="`mailto:${profile.email}`"
             class="inline-flex items-center gap-2 bg-primary text-[#0D1117] px-8 py-4 rounded-lg font-semibold hover:bg-cyan-300 hover:shadow-[0_0_20px_rgba(0,212,255,0.4)] transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Email Me
          </a>
          <router-link to="/contact"
             class="inline-flex items-center gap-2 border border-gray-700 text-gray-300 px-8 py-4 rounded-lg font-semibold hover:border-primary hover:text-primary transition-all duration-200 hover:-translate-y-0.5">
            Contact Form
          </router-link>
        </div>

        <div class="flex flex-wrap gap-8 justify-center text-sm text-gray-500 font-mono">
          <span v-if="profile?.email">{{ profile.email }}</span>
          <span v-if="profile?.location">{{ profile.location }}</span>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { usePortfolioStore } from '../stores/portfolio';
import { storeToRefs } from 'pinia';
import ProjectCard from '../Components/ProjectCard.vue';
import BlogCard from '../Components/BlogCard.vue';
import NewsletterBox from '../Components/NewsletterBox.vue';

const portfolioStore = usePortfolioStore();
const { profile, projects, posts } = storeToRefs(portfolioStore);

const featuredProjects = computed(() => projects.value.filter(p => p.featured).slice(0, 3));
const latestPosts = computed(() => posts.value.data ? posts.value.data.slice(0, 3) : []);

const techStack = [
  {
    label: 'Backend',
    accent: 'bg-red-500',
    badge: 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20',
    items: ['Laravel', 'PHP', 'Node.js', 'FastAPI', 'Sanctum'],
  },
  {
    label: 'Frontend',
    accent: 'bg-blue-500',
    badge: 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20',
    items: ['React 18', 'Next.js 16', 'Vue.js', 'TypeScript', 'Tailwind CSS'],
  },
  {
    label: 'ML & AI',
    accent: 'bg-purple-500',
    badge: 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20',
    items: ['LangChain', 'RAG', 'PySpark', 'TF-IDF', 'LLM APIs'],
  },
  {
    label: 'Database',
    accent: 'bg-yellow-500',
    badge: 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20',
    items: ['PostgreSQL', 'MySQL', 'Redis', 'ChromaDB'],
  },
  {
    label: 'DevOps & Cloud',
    accent: 'bg-cyan-500',
    badge: 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20',
    items: ['Docker', 'Kubernetes', 'Terraform', 'GitHub Actions', 'DigitalOcean'],
  },
  {
    label: 'Security & Testing',
    accent: 'bg-green-500',
    badge: 'bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-500/20',
    items: ['Pest', 'App Security', 'Sanctum Auth', 'Spatie Logs'],
  },
];

onMounted(() => {
  portfolioStore.fetchProfile();
  portfolioStore.fetchProjects({ featured: true });
  portfolioStore.fetchPosts({ per_page: 3 });
  portfolioStore.trackPageView(window.location.href);
});
</script>
