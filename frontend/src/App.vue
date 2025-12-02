<template>
  <AppNavbar v-if="auth.isAuthenticated" />
  <router-view />
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import AppNavbar from '@/components/layout/AppNavbar.vue'

const auth = useAuthStore()
</script>