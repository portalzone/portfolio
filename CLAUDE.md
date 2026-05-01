# Portfolio — Visual Overhaul
# CLAUDE.md — basepan.com / github.com/portalzone/portfolio

## Context
Victor Muojeke. Full-Stack Software Engineer & ML Systems Developer.
MASc candidate at Memorial University of Newfoundland.
Interview Monday at 8:30 PM with BioLabMate for a Webmaster & Digital Design role.
They specifically want to see React work, graphic design sensibility, and Wix familiarity.

The portfolio is the FIRST thing they will look at during the 20-minute call.
It must look impressive within 5 seconds of loading.

## What the portfolio is (as of 2026-05-01 audit)
- Backend: Laravel 12 API — solid, do not touch
- Frontend: Vue.js SPA (Vue Router + Pinia) — solid, do not touch logic
- Styling: Tailwind CSS 4
- All features are built: Home, Projects, Blog, Contact, Admin (Filament), Newsletter, Comments, Analytics
- Problem: Visual layer is entirely unexecuted. Every component still uses the
  generic `from-primary to-secondary` gradient. Primary is blue, secondary is green.
  ProjectCard has a raw letter on a plain gradient. Navbar is plain white/gray.
  No animations. No dark theme tokens. None of the CLAUDE.md visual plan has been applied yet.

## What it needs to become
A visually striking developer portfolio that immediately communicates:
  - Full-Stack Engineer (Laravel, React, Vue, Node.js)
  - ML & AI Developer (five-algorithm ML engine, LangChain, LLM APIs)
  - Security-conscious engineer
  - Based in St. John's, NL — committed to the local tech sector

Target aesthetic: dark, modern, professional. Think Linear.app or Vercel.com.
Not colourful and playful — sharp, technical, confident.

## Ground Rules

### Git commits — no co-author tags
Never add "Co-authored-by: Anthropic" or any AI attribution to commit messages.
All commits are authored by Victor Muojeke only.
Commit message format:
  feat: redesign hero section with animated gradient background
  fix: project card image fallback gradient
  style: update colour tokens to dark theme

### Cost discipline
This portfolio runs on Hostinger Business — already paid for. Do not suggest:
- Vercel, Netlify, Railway, or any other hosting (Hostinger handles this)
- Any paid image service, animation library with a cost, or SaaS tool
- Use CSS animations only (no GSAP, no paid libraries)

### No new paid services / No new npm packages
The portfolio must run on existing infrastructure and existing dependencies.
- Hosting: Hostinger Business (already live at basepan.com)
- Database: MySQL on Hostinger (already configured)
- Images: Laravel storage or free external URLs
- Fonts: Google Fonts (free) — Inter is already configured in Tailwind
- Icons: inline SVG only (already used throughout)
- Animations: pure CSS keyframes only

## How I want you to help me

### Build approach
- One component at a time, execute immediately — don't describe, just do
- After each component, ask: "Does this look right on your screen?"
- If something doesn't look good, adjust immediately — don't explain, just fix

### What to do freely
- Rewrite Tailwind classes for visual improvement
- Add CSS animations and transitions
- Restructure the layout of existing components
- Add missing content sections directly in Vue templates

### What to check with me first
- Any new npm package install
- Any change to the Laravel backend or API
- Any database migration

## Execution order (confirmed 2026-05-01)

### Step 1 — tailwind.config.js [DONE]
Replace existing color tokens with:
```js
primary: '#00D4FF',    // electric cyan — main accent
secondary: '#7C3AED',  // deep purple — secondary accent
dark: {
  900: '#0D1117',      // page background
  800: '#161B22',      // card background
  700: '#21262D',      // hover / border
  600: '#30363D',      // subtle borders
}
```
Also add CSS keyframe animations for: gradientShift, float, blink-caret

### Step 2 — Home.vue hero section [DONE]
- Background: dark-900 (#0D1117) with animated CSS gradient overlay
- Name: "VICTOR CHISOM MUOJEKE" — large, white, bold, letter-spaced
- Tagline: "Full-Stack Engineer & ML Systems Developer"
- Sub-tagline: "Building AI-powered systems in St. John's, NL"
- CTA: "View Projects" (solid cyan) + "Get in Touch" (ghost cyan border)
- Social icons: GitHub + LinkedIn, cyan on hover
- Animated: CSS gradient background-position shift, subtle floating dots via pseudo-elements

### Step 3 — Home.vue skills section [DONE]
- Dark category cards (bg dark-800) with pill badges per skill
- Category header with a coloured left-border accent
- Badge colours by category: Backend = red, Frontend = blue, ML/AI = purple, Security = green, Tools = grey

### Step 4 — ProjectCard.vue [DONE]
- Dark card: bg dark-800, border dark-700
- Coloured top border by category (Full-Stack = cyan, ML = purple, React = blue)
- Tech badge colour map: React/Next.js = blue, Laravel/PHP = red, ML/AI = purple, Vue = green, Tailwind = teal
- Letter placeholder: large initial on a dark-to-accent radial gradient, not plain
- "View Live" button: solid small button, not a text link
- Hover lift: translateY(-4px) + shadow-dark-lg transition

### Step 5 — Navbar.vue [DONE]
- Background: bg-dark-900/80 backdrop-blur-md border-b border-dark-700
- Logo: "VM" monogram or name in cyan
- Active route: cyan underline (border-b-2 border-primary)
- All text: white/gray-300 in default, white on hover
- Mobile menu: dark slide-down panel

### Step 6 — Footer.vue [DONE]
- Keep existing 3-column structure
- Name in primary cyan colour
- Add tagline: "Built with Laravel & Vue.js · St. John's, NL"
- Add border-t border-dark-700 separator
- Copyright in gray-500

## What to keep unchanged
- All Laravel backend routes and API logic
- Filament admin panel (/admin/* Filament routes)
- Vue Router configuration
- Pinia stores (portfolio, auth, theme)
- Contact form logic
- Newsletter logic
- Blog functionality
- All admin Vue pages

## Definition of done — before Monday interview
- [x] tailwind.config.js: dark theme color tokens applied
- [x] Hero section: dark, striking, animated — loads fast
- [x] Skills section: visually grouped, highlights ML and AI skills
- [x] ProjectCard: dark card, coloured badges, hover effect
- [ ] All 4 projects listed via admin with correct URLs and descriptions
- [x] Navigation: dark sticky with frosted glass
- [x] Footer: complete with tagline and professional finish
- [ ] Mobile responsive — check on iPhone XS Max screen size
- [ ] Loads in under 3 seconds — no heavy libraries added
- [ ] basepan.com deployed and live with changes

## 4 projects to add via admin (Priority 4 — manual step)
Project 1: Vehicle Management System
  URL: vms.basepan.com | Tags: Laravel, Vue.js, ML Engine, PHP
  Description: Fleet management platform with a five-algorithm ML engine
    (predictive maintenance, anomaly detection, health scoring, performance
    ranking, EWMA forecasting) integrated into a Laravel REST API.
  Category: Full-Stack + ML | Set as Featured

Project 2: VMS React Version
  URL: myvms.basepan.com | Tags: React, Laravel, Tailwind CSS
  Description: React 18 implementation of VMS sharing the Laravel API backend.
  Category: Full-Stack | Set as Featured

Project 3: MUNext Board Portal
  URL: munext.basepan.com | Tags: Laravel, Vue.js, ML, TF-IDF
  Description: Networking platform with ML skill-matching engine using TF-IDF
    vectorization and cosine similarity.
  Category: Full-Stack + ML | Set as Featured

Project 4: Basepan Tech
  URL: basepan.com | Tags: Laravel, Vue.js, Filament
  Description: Personal portfolio and tech consulting platform.
  Category: Full-Stack
