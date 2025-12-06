import { createRouter, createWebHistory } from 'vue-router';

// Public Pages
import Home from './Pages/Home.vue';
import Projects from './Pages/Projects.vue';
import ProjectDetail from './Pages/ProjectDetail.vue';
import Blog from './Pages/Blog.vue';
import BlogPost from './Pages/BlogPost.vue';

// Admin Pages
import Login from './Pages/Login.vue';
import AdminDashboard from './Pages/Admin/Dashboard.vue';
import AdminProjects from './Pages/Admin/Projects.vue';
import AdminSkills from './Pages/Admin/Skills.vue';
import AdminBlog from './Pages/Admin/Blog.vue';
import AdminProfile from './Pages/Admin/Profile.vue';
import AdminAnalytics from './Pages/Admin/Analytics.vue';

const routes = [
  // Public routes
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { title: 'Home' }
  },
  {
    path: '/projects',
    name: 'projects',
    component: Projects,
    meta: { title: 'Projects' }
  },
  {
    path: '/projects/:slug',
    name: 'project-detail',
    component: ProjectDetail,
    meta: { title: 'Project' }
  },
  {
    path: '/blog',
    name: 'blog',
    component: Blog,
    meta: { title: 'Blog' }
  },
  {
    path: '/blog/:slug',
    name: 'blog-post',
    component: BlogPost,
    meta: { title: 'Blog Post' }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { title: 'Login' }
  },
  
  // Admin routes
  {
    path: '/admin',
    name: 'admin',
    component: AdminDashboard,
    meta: { requiresAuth: true, title: 'Dashboard' }
  },
  {
    path: '/admin/projects',
    name: 'admin-projects',
    component: AdminProjects,
    meta: { requiresAuth: true, title: 'Manage Projects' }
  },
  {
    path: '/admin/skills',
    name: 'admin-skills',
    component: AdminSkills,
    meta: { requiresAuth: true, title: 'Manage Skills' }
  },
  {
    path: '/admin/blog',
    name: 'admin-blog',
    component: AdminBlog,
    meta: { requiresAuth: true, title: 'Manage Blog' }
  },
  {
    path: '/admin/profile',
    name: 'admin-profile',
    component: AdminProfile,
    meta: { requiresAuth: true, title: 'Profile Settings' }
  },
  {
    path: '/admin/analytics',
    name: 'admin-analytics',
    component: AdminAnalytics,
    meta: { requiresAuth: true, title: 'Analytics' }
  },
  {
  path: '/contact',
  name: 'contact',
  component: () => import('./Pages/Contact.vue'),
  meta: { title: 'Contact' }
},
{
  path: '/api-docs',
  name: 'api-docs',
  component: () => import('./Pages/ApiDocs.vue'),
  meta: { title: 'API Documentation' }
},
// Admin routes
{
  path: '/admin/contact-messages',
  name: 'admin-contact-messages',
  component: () => import('./Pages/Admin/ContactMessages.vue'),
  meta: { requiresAuth: true, title: 'Contact Messages' }
},
{
  path: '/admin/comments',
  name: 'admin-comments',
  component: () => import('./Pages/Admin/Comments.vue'),
  meta: { requiresAuth: true, title: 'Comments' }
},
{
  path: '/admin/newsletter',
  name: 'admin-newsletter',
  component: () => import('./Pages/Admin/Newsletter.vue'),
  meta: { requiresAuth: true, title: 'Newsletter' }
},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  }
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');
  
  if (to.meta.requiresAuth && !token) {
    next('/login');
  } else {
    document.title = to.meta.title ? `${to.meta.title} - Portfolio` : 'Portfolio';
    next();
  }
});

export default router;