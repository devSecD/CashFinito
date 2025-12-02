<template>
    <AuthLayout subtitle="Comienza tu viaje financiero hoy">
        <AppCard variant="elevated">
            <RegisterForm />
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
import RegisterForm from '@/components/auth/RegisterForm.vue'
</script>