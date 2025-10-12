<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    <img src="/images/tukel-app-logo.png" alt="TUKEL" class="w-24 mb-6" />
    <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md rounded-lg">
      <slot />
    </div>
  </div>
</template>

