<template>
    <AuthLayout subtitle="Ingresa tus credenciales para continuar">
        <AppCard variant="elevated">
            <LoginForm />
        </AppCard>
        
        <template #footer>
            <p class="footer-text">
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

<style scoped>
.footer-text {
    font-size: 0.875rem;
    margin: 0;
    opacity: 0.9;
}
</style>