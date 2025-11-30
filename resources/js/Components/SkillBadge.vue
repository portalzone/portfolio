<template>
  <div class="card">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-lg">{{ skill.name }}</h3>
      <span class="text-sm text-gray-500">{{ skill.proficiency }}%</span>
    </div>

    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-2.5">
      <div 
        class="h-2.5 rounded-full transition-all duration-1000"
        :class="getColorClass(skill.proficiency)"
        :style="{ width: `${skill.proficiency}%` }"
      ></div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  skill: {
    type: Object,
    required: true
  }
});

const getColorClass = (proficiency) => {
  if (proficiency >= 80) return 'bg-green-500';
  if (proficiency >= 60) return 'bg-blue-500';
  if (proficiency >= 40) return 'bg-yellow-500';
  return 'bg-gray-500';
};
</script>