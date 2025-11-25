<template>
    <AuthLayout subtitle="Comienza tu viaje financiero hoy">
        <AppCard variant="elevated">
            <RegisterForm />
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
import RegisterForm from '@/components/auth/RegisterForm.vue'
</script>

<style scoped>
.footer-text {
    font-size: 0.875rem;
    margin: 0;
    opacity: 0.9;
}
</style>