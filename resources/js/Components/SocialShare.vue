<template>
  <div class="flex items-center gap-3">
    <span class="text-sm font-medium text-gray-600">Share:</span>
    
    <!-- Twitter -->
    <a 
      :href="twitterUrl" 
      target="_blank"
      class="p-2 rounded-full bg-blue-400 hover:bg-blue-500 text-white transition"
      title="Share on Twitter"
    >
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
      </svg>
    </a>

    <!-- Facebook -->
    <a 
      :href="facebookUrl" 
      target="_blank"
      class="p-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white transition"
      title="Share on Facebook"
    >
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
      </svg>
    </a>

    <!-- LinkedIn -->
    <a 
      :href="linkedInUrl" 
      target="_blank"
      class="p-2 rounded-full bg-blue-700 hover:bg-blue-800 text-white transition"
      title="Share on LinkedIn"
    >
      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/>
        <circle cx="4" cy="4" r="2"/>
      </svg>
    </a>

    <!-- Copy Link -->
    <button 
      @click="copyLink"
      class="p-2 rounded-full bg-gray-600 hover:bg-gray-700 text-white transition"
      title="Copy link"
    >
      <svg v-if="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
      </svg>
      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  title: String,
  url: String,
});

const copied = ref(false);

const encodedTitle = computed(() => encodeURIComponent(props.title));
const encodedUrl = computed(() => encodeURIComponent(props.url));

const twitterUrl = computed(() => 
  `https://twitter.com/intent/tweet?text=${encodedTitle.value}&url=${encodedUrl.value}`
);

const facebookUrl = computed(() => 
  `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl.value}`
);

const linkedInUrl = computed(() => 
  `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl.value}`
);

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(props.url);
    copied.value = true;
    setTimeout(() => {
      copied.value = false;
    }, 2000);
  } catch (err) {
    console.error('Failed to copy:', err);
  }
};
</script>