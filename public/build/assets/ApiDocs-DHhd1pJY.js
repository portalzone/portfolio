import{r as u,c as o,a as e,f as b,F as i,i as n,j as g,k as h,l as x,g as a,t as l,b as m,n as y,d as f}from"./app-CHPwn2SE.js";const v={class:"min-h-screen bg-gray-50 py-12"},w={class:"max-w-6xl mx-auto px-4"},k={class:"bg-white rounded-lg shadow-lg p-8"},_={class:"space-y-8"},S={class:"text-xl font-bold mb-4"},T={class:"flex items-center gap-3 mb-2"},E={class:"text-lg"},G={class:"text-gray-600 mb-3"},P={key:0,class:"mb-3"},q={class:"list-disc list-inside text-sm text-gray-600"},j={class:"bg-gray-900 text-gray-100 p-4 rounded-lg mb-3"},V={key:1,class:"bg-gray-100 p-4 rounded-lg"},A={class:"mt-12 text-center p-8 bg-gradient-to-r from-primary to-secondary text-white rounded-lg"},B={__name:"ApiDocs",setup(C){const c=u([{name:"Portfolio",endpoints:[{method:"GET",path:"/portfolio/profile",description:"Get portfolio profile information",example:"curl https://basepan.com/api/portfolio/profile",response:`{
  "name": "M. C. Victor",
  "title": "Full Stack Developer",
  "bio": "Passionate software engineer...",
  "email": "victor@basepan.com",
  "github": "https://github.com/victor",
  "linkedin": "https://linkedin.com/in/victor"
}`},{method:"GET",path:"/portfolio/projects",description:"Get all projects",example:"curl https://basepan.com/api/portfolio/projects",response:`[
  {
    "id": 1,
    "title": "VMS - Vehicle Management System",
    "slug": "vms-vehicle-management-system",
    "description": "A comprehensive vehicle management system...",
    "technologies": ["Laravel", "Vue.js", "MySQL"],
    "category": "web",
    "status": "active",
    "featured": true,
    "views": 150
  }
]`},{method:"GET",path:"/portfolio/projects/{slug}",description:"Get a specific project by slug",example:"curl https://basepan.com/api/portfolio/projects/vms-vehicle-management-system",response:`{
  "id": 1,
  "title": "VMS - Vehicle Management System",
  "slug": "vms-vehicle-management-system",
  "description": "Full description here...",
  "technologies": ["Laravel", "Vue.js", "MySQL"],
  "url": "https://vms.basepan.com",
  "views": 150
}`},{method:"GET",path:"/portfolio/skills",description:"Get all skills grouped by category",example:"curl https://basepan.com/api/portfolio/skills",response:`{
  "Backend": [
    {
      "name": "Laravel",
      "proficiency": 95,
      "category": "Backend"
    }
  ],
  "Frontend": [...]
}`}]},{name:"Blog",endpoints:[{method:"GET",path:"/portfolio/posts",description:"Get all published blog posts",params:["category (optional)","search (optional)","page (optional)"],example:"curl https://basepan.com/api/portfolio/posts?category=Development",response:`{
  "data": [...],
  "current_page": 1,
  "total": 10
}`},{method:"GET",path:"/portfolio/posts/{slug}",description:"Get a specific blog post by slug",example:"curl https://basepan.com/api/portfolio/posts/welcome-to-my-portfolio"}]},{name:"Contact",endpoints:[{method:"POST",path:"/contact",description:"Submit a contact form message",params:["name (required)","email (required)","subject (required)","message (required)"],example:`curl -X POST https://basepan.com/api/contact \\
  -H "Content-Type: application/json" \\
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Question about your work",
    "message": "I would like to discuss..."
  }'`,response:`{
  "success": true,
  "message": "Thank you for your message! I will get back to you soon."
}`}]},{name:"Comments",endpoints:[{method:"GET",path:"/posts/{slug}/comments",description:"Get approved comments for a blog post",example:"curl https://basepan.com/api/posts/welcome-to-my-portfolio/comments"},{method:"POST",path:"/posts/{slug}/comments",description:"Submit a comment on a blog post",params:["name (required)","email (required)","content (required)","website (optional)"]}]},{name:"Newsletter",endpoints:[{method:"POST",path:"/newsletter/subscribe",description:"Subscribe to newsletter",params:["email (required)","name (optional)"],example:`curl -X POST https://basepan.com/api/newsletter/subscribe \\
  -H "Content-Type: application/json" \\
  -d '{"email": "user@example.com"}'`}]}]);return(N,t)=>{const d=x("router-link");return a(),o("div",v,[e("div",w,[e("div",k,[t[7]||(t[7]=b('<div class="mb-12"><h1 class="text-4xl font-bold mb-4">Portfolio API Documentation</h1><p class="text-lg text-gray-600"> RESTful API for accessing portfolio data. All endpoints return JSON responses. </p><div class="mt-4 flex gap-4"><span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold"> Version 1.0 </span><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold"> REST API </span><span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold"> JSON </span></div></div><div class="mb-8 p-4 bg-gray-100 rounded-lg"><h3 class="font-semibold mb-2">Base URL</h3><code class="text-primary">https://basepan.com/api</code></div><section class="mb-12"><h2 class="text-2xl font-bold mb-4">Authentication</h2><p class="text-gray-600 mb-4"> Public endpoints don&#39;t require authentication. Admin endpoints require a Bearer token. </p><div class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto"><pre><code>Authorization: Bearer YOUR_TOKEN_HERE</code></pre></div></section>',3)),e("section",_,[t[3]||(t[3]=e("h2",{class:"text-2xl font-bold mb-6"},"Endpoints",-1)),(a(!0),o(i,null,n(c.value,r=>(a(),o("div",{key:r.name,class:"border-b pb-8"},[e("h3",S,l(r.name),1),(a(!0),o(i,null,n(r.endpoints,s=>(a(),o("div",{key:s.path,class:"mb-6"},[e("div",T,[e("span",{class:y(["px-3 py-1 rounded font-semibold text-sm",s.method==="GET"?"bg-blue-100 text-blue-700":s.method==="POST"?"bg-green-100 text-green-700":s.method==="PUT"?"bg-yellow-100 text-yellow-700":"bg-red-100 text-red-700"])},l(s.method),3),e("code",E,l(s.path),1)]),e("p",G,l(s.description),1),s.params?(a(),o("div",P,[t[0]||(t[0]=e("h5",{class:"font-semibold text-sm mb-2"},"Parameters:",-1)),e("ul",q,[(a(!0),o(i,null,n(s.params,p=>(a(),o("li",{key:p},l(p),1))),128))])])):m("",!0),e("div",j,[t[1]||(t[1]=e("p",{class:"text-xs text-gray-400 mb-2"},"Example Request:",-1)),e("pre",null,[e("code",null,l(s.example),1)])]),s.response?(a(),o("div",V,[t[2]||(t[2]=e("p",{class:"text-xs text-gray-600 mb-2"},"Example Response:",-1)),e("pre",null,[e("code",null,l(s.response),1)])])):m("",!0)]))),128))]))),128))]),t[8]||(t[8]=e("section",{class:"mt-12 p-6 bg-yellow-50 border border-yellow-200 rounded-lg"},[e("h3",{class:"text-xl font-bold mb-2"},"⚠️ Rate Limiting"),e("p",{class:"text-gray-700"}," API requests are limited to 60 requests per minute for unauthenticated users and 120 requests per minute for authenticated users. ")],-1)),e("section",A,[t[5]||(t[5]=e("h3",{class:"text-2xl font-bold mb-2"},"Need Help?",-1)),t[6]||(t[6]=e("p",{class:"mb-4"},"Have questions about the API? Get in touch!",-1)),g(d,{to:"/contact",class:"btn-primary bg-white text-primary hover:bg-gray-100"},{default:h(()=>[...t[4]||(t[4]=[f(" Contact Me ",-1)])]),_:1})])])])])}}};export{B as default};
