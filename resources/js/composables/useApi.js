import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const useApi = () => {
  // Portfolio endpoints
  const getProfile = () => api.get('/portfolio/profile');
  const getProjects = (params = {}) => api.get('/portfolio/projects', { params });
  const getProject = (slug) => api.get(`/portfolio/projects/${slug}`);
  const getSkills = (params = {}) => api.get('/portfolio/skills', { params });
  const getPosts = (params = {}) => api.get('/portfolio/posts', { params });
  const getPost = (slug) => api.get(`/portfolio/posts/${slug}`);
  const getCategories = () => api.get('/portfolio/categories');
  const trackView = (pageUrl) => api.post('/portfolio/track-view', { page_url: pageUrl });

  // Admin endpoints
  const login = (credentials) => api.post('/login', credentials);
  const logout = () => api.post('/logout');
  
  // Admin - Projects
  const getAdminProjects = () => api.get('/admin/projects');
  const createProject = (data) => api.post('/admin/projects', data);
  const updateProject = (id, data) => api.put(`/admin/projects/${id}`, data);
  const deleteProject = (id) => api.delete(`/admin/projects/${id}`);
  
  // Admin - Skills
  const getAdminSkills = () => api.get('/admin/skills');
  const createSkill = (data) => api.post('/admin/skills', data);
  const updateSkill = (id, data) => api.put(`/admin/skills/${id}`, data);
  const deleteSkill = (id) => api.delete(`/admin/skills/${id}`);
  
  // Admin - Posts
  const getAdminPosts = () => api.get('/admin/posts');
  const createPost = (data) => api.post('/admin/posts', data);
  const updatePost = (id, data) => api.put(`/admin/posts/${id}`, data);
  const deletePost = (id) => api.delete(`/admin/posts/${id}`);
  
  // Admin - Profile
  const getAdminProfile = () => api.get('/admin/profile');
  const updateAdminProfile = (data) => api.post('/admin/profile', data);
  
  // Admin - Analytics
  const getAnalytics = (params = {}) => api.get('/admin/analytics', { params });
  const getDashboard = () => api.get('/admin/dashboard');

  return {
    // Public
    getProfile,
    getProjects,
    getProject,
    getSkills,
    getPosts,
    getPost,
    getCategories,
    trackView,
    
    // Auth
    login,
    logout,
    
    // Admin
    getAdminProjects,
    createProject,
    updateProject,
    deleteProject,
    getAdminSkills,
    createSkill,
    updateSkill,
    deleteSkill,
    getAdminPosts,
    createPost,
    updatePost,
    deletePost,
    getAdminProfile,
    updateAdminProfile,
    getAnalytics,
    getDashboard,
  };
};