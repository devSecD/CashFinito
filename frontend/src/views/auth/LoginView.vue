<template>
    <AuthLayout subtitle="Ingresa tus credenciales para continuar">
        <AppCard variant="elevated">
            <LoginForm />
        </AppCard>
        
        <template #footer>
            <p class="text-sm m-0 opacity-90">
                © 2024 CashFinito. Todos los derechos reservados.
            </p>
        </template>
    </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/components/layout/AuthLayout.vue'
import AppCard from '@/components/common/AppCard.vue'
import LoginForm from '@/components/auth/LoginForm.vue'
</script>