<template>
    <AuthLayout subtitle="Ingresa tu correo para recuperar tu contraseña">
        <AppCard variant="elevated">
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
                <AppAlert
                    v-if="successMessage"
                    type="success"
                    :message="successMessage"
                    :dismissible="false"
                />

                <AppAlert
                    v-if="errorMessage"
                    type="error"
                    :message="errorMessage"
                    :dismissible="true"
                    @dismiss="errorMessage = ''"
                />

                <template v-if="!successMessage">
                    <AppInput
                        id="email"
                        v-model="email"
                        label="Correo electrónico"
                        type="email"
                        placeholder="tu@email.com"
                        :error="emailError"
                        :disabled="loading"
                        required
                    >
                        <template #icon>
                            <Mail :size="20" />
                        </template>
                    </AppInput>

                    <AppButton
                        type="submit"
                        variant="primary"
                        size="lg"
                        :loading="loading"
                        :disabled="loading"
                        full-width
                    >
                        {{ loading ? 'Enviando...' : 'Enviar enlace de recuperación' }}
                    </AppButton>
                </template>

                <div class="text-center pt-4 border-t border-gray-200">
                    <router-link 
                        to="/login" 
                        class="inline-flex items-center gap-2 text-purple-500 no-underline text-sm font-medium transition-all duration-200 hover:text-purple-700 hover:gap-3"
                    >
                        <ArrowLeft :size="16" />
                        <span>Volver al inicio de sesión</span>
                    </router-link>
                </div>
            </form>
        </AppCard>

        <template #footer>
            <p class="text-sm m-0 opacity-90">
                © 2024 CashFinito. Todos los derechos reservados.
            </p>
        </template>
    </AuthLayout>
</template>

<script setup>
import { ref } from 'vue'
import AuthLayout from '@/components/layout/AuthLayout.vue'
import AppCard from '@/components/common/AppCard.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import { Mail, ArrowLeft } from 'lucide-vue-next'
import apiClient from '@/api/axios'

const email = ref('')
const emailError = ref('')
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const validateEmail = () => {
    emailError.value = ''
    
    if (!email.value) {
        emailError.value = 'El correo electrónico es requerido'
        return false
    }
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        emailError.value = 'El correo electrónico no es válido'
        return false
    }
    
    return true
}

const handleSubmit = async () => {
    if (!validateEmail()) {
        return
    }

    loading.value = true
    errorMessage.value = ''
    successMessage.value = ''

    try {
        await apiClient.post('/auth/password/email', {
            email: email.value
        })

        successMessage.value = 'Se ha enviado un enlace de recuperación a tu correo electrónico. Por favor, revisa tu bandeja de entrada.'
    } catch (err) {
        errorMessage.value = err.response?.data?.message || 'Error al enviar el correo. Por favor, intenta de nuevo.'
    } finally {
        loading.value = false
    }
}
</script>