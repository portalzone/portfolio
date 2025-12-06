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

  // Auth endpoints
  const login = (credentials) => api.post('/login', credentials);
  const logout = () => api.post('/logout');
  
  // ========================================
  // NEW FEATURES - Contact Form
  // ========================================
  const submitContact = (data) => api.post('/contact', data);
  
  // ========================================
  // NEW FEATURES - Comments
  // ========================================
  const getComments = (postSlug) => api.get(`/posts/${postSlug}/comments`);
  const submitComment = (postSlug, data) => api.post(`/posts/${postSlug}/comments`, data);
  
  // ========================================
  // NEW FEATURES - Newsletter
  // ========================================
  const subscribeNewsletter = (data) => api.post('/newsletter/subscribe', data);
  const verifyNewsletter = (token) => api.get(`/newsletter/verify/${token}`);
  const unsubscribeNewsletter = (email) => api.post(`/newsletter/unsubscribe/${email}`);
  
  // ========================================
  // ADMIN - Projects
  // ========================================
  const getAdminProjects = () => api.get('/admin/projects');
  const createProject = (data) => api.post('/admin/projects', data);
  const updateProject = (id, data) => api.put(`/admin/projects/${id}`, data);
  const deleteProject = (id) => api.delete(`/admin/projects/${id}`);
  
  // ========================================
  // ADMIN - Skills
  // ========================================
  const getAdminSkills = () => api.get('/admin/skills');
  const createSkill = (data) => api.post('/admin/skills', data);
  const updateSkill = (id, data) => api.put(`/admin/skills/${id}`, data);
  const deleteSkill = (id) => api.delete(`/admin/skills/${id}`);
  
  // ========================================
  // ADMIN - Posts
  // ========================================
  const getAdminPosts = () => api.get('/admin/posts');
  const createPost = (data) => api.post('/admin/posts', data);
  const updatePost = (id, data) => api.put(`/admin/posts/${id}`, data);
  const deletePost = (id) => api.delete(`/admin/posts/${id}`);
  
  // ========================================
  // ADMIN - Profile
  // ========================================
  const getAdminProfile = () => api.get('/admin/profile');
  const updateAdminProfile = (data) => api.post('/admin/profile', data);
  
  // ========================================
  // ADMIN - Analytics
  // ========================================
  const getAnalytics = (params = {}) => api.get('/admin/analytics', { params });
  const getDashboard = () => api.get('/admin/dashboard');
  
  // ========================================
  // ADMIN - Contact Messages
  // ========================================
  const getContactMessages = () => api.get('/admin/contact-messages');
  const getContactMessage = (id) => api.get(`/admin/contact-messages/${id}`);
  const updateContactStatus = (id, status) => api.put(`/admin/contact-messages/${id}/status`, { status });
  const deleteContactMessage = (id) => api.delete(`/admin/contact-messages/${id}`);
  
  // ========================================
  // ADMIN - Comments
  // ========================================
  const getAdminComments = () => api.get('/admin/comments');
  const approveComment = (id) => api.put(`/admin/comments/${id}/approve`);
  const markCommentAsSpam = (id) => api.put(`/admin/comments/${id}/spam`);
  const deleteComment = (id) => api.delete(`/admin/comments/${id}`);
  
  // ========================================
  // ADMIN - Newsletter
  // ========================================
  const getNewsletterSubscribers = () => api.get('/admin/newsletter');
  const deleteNewsletterSubscriber = (id) => api.delete(`/admin/newsletter/${id}`);
  const exportNewsletterSubscribers = () => api.get('/admin/newsletter/export');

  return {
    // ========================================
    // PUBLIC ENDPOINTS
    // ========================================
    getProfile,
    getProjects,
    getProject,
    getSkills,
    getPosts,
    getPost,
    getCategories,
    trackView,
    
    // Contact Form (Public)
    submitContact,
    
    // Comments (Public)
    getComments,
    submitComment,
    
    // Newsletter (Public)
    subscribeNewsletter,
    verifyNewsletter,
    unsubscribeNewsletter,
    
    // ========================================
    // AUTH
    // ========================================
    login,
    logout,
    
    // ========================================
    // ADMIN - CONTENT MANAGEMENT
    // ========================================
    
    // Projects
    getAdminProjects,
    createProject,
    updateProject,
    deleteProject,
    
    // Skills
    getAdminSkills,
    createSkill,
    updateSkill,
    deleteSkill,
    
    // Posts
    getAdminPosts,
    createPost,
    updatePost,
    deletePost,
    
    // Profile
    getAdminProfile,
    updateAdminProfile,
    
    // ========================================
    // ADMIN - ANALYTICS & ENGAGEMENT
    // ========================================
    
    // Analytics
    getAnalytics,
    getDashboard,
    
    // Contact Messages
    getContactMessages,
    getContactMessage,
    updateContactStatus,
    deleteContactMessage,
    
    // Comments
    getAdminComments,
    approveComment,
    markCommentAsSpam,
    deleteComment,
    
    // Newsletter
    getNewsletterSubscribers,
    deleteNewsletterSubscriber,
    exportNewsletterSubscribers,
  };
};