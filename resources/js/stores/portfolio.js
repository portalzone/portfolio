import { defineStore } from 'pinia';
import { useApi } from '../composables/useApi';

export const usePortfolioStore = defineStore('portfolio', {
  state: () => ({
    profile: null,
    projects: [],
    skills: [],
    posts: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchProfile() {
      this.loading = true;
      try {
        const api = useApi();
        const response = await api.getProfile();
        this.profile = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    async fetchProjects(params = {}) {
      this.loading = true;
      try {
        const api = useApi();
        const response = await api.getProjects(params);
        this.projects = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    async fetchSkills(params = {}) {
      this.loading = true;
      try {
        const api = useApi();
        const response = await api.getSkills(params);
        this.skills = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    async fetchPosts(params = {}) {
      this.loading = true;
      try {
        const api = useApi();
        const response = await api.getPosts(params);
        this.posts = response.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    trackPageView(url) {
      const api = useApi();
      api.trackView(url);
    },
  },
});