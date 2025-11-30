import { defineStore } from 'pinia';
import { useApi } from '../composables/useApi';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token') || null,
    isAuthenticated: !!localStorage.getItem('auth_token'),
  }),

  actions: {
    async login(credentials) {
      try {
        const api = useApi();
        const response = await api.login(credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        this.isAuthenticated = true;
        
        localStorage.setItem('auth_token', this.token);
        
        return true;
      } catch (error) {
        throw error;
      }
    },

    async logout() {
      try {
        const api = useApi();
        await api.logout();
      } catch (error) {
        // Continue with logout even if API call fails
      } finally {
        this.token = null;
        this.user = null;
        this.isAuthenticated = false;
        localStorage.removeItem('auth_token');
      }
    },
  },
});