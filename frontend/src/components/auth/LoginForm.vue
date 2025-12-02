<template>
    <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
        <AppInput
            id="email"
            v-model="formData.email"
            label="Correo electrónico"
            type="email"
            placeholder="tu@email.com"
            :error="errors.email"
            :disabled="loading"
            required
        >
            <template #icon>
                <Mail :size="20" />
            </template>
        </AppInput>

        <AppInput
            id="password"
            v-model="formData.password"
            label="Contraseña"
            :type="showPassword ? 'text' : 'password'"
            placeholder="••••••••"
            :error="errors.password"
            :disabled="loading"
            required
        >
            <template #icon>
                <button
                    type="button"
                    @click="togglePassword"
                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="loading"
                >
                    <Eye v-if="showPassword" :size="20" />
                    <EyeOff v-else :size="20" />
                </button>
            </template>
        </AppInput>

        <div class="flex justify-between items-center -mt-2">
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input
                    type="checkbox"
                    v-model="formData.remember"
                    :disabled="loading"
                    class="w-4 h-4 cursor-pointer"
                />
                <span>Recordarme</span>
            </label>

            <router-link 
                to="/forgot-password" 
                class="text-sm text-purple-500 no-underline transition-colors duration-200 hover:text-purple-700 hover:underline"
            >
                ¿Olvidaste tu contraseña?
            </router-link>
        </div>

        <AppAlert
            v-if="errors.general"
            type="error"
            :message="errors.general"
            :dismissible="true"
            @dismiss="errors.general = ''"
        />

        <AppButton
            type="submit"
            variant="primary"
            size="lg"
            :loading="loading"
            :disabled="loading"
            full-width
        >
            {{ loading ? 'Iniciando sesión...' : 'Iniciar sesión' }}
        </AppButton>

        <div class="text-center pt-4 border-t border-gray-200">
            <p class="m-0 text-sm text-gray-600">
                ¿No tienes cuenta?
                <router-link 
                    to="/register" 
                    class="text-purple-500 no-underline font-medium transition-colors duration-200 hover:text-purple-700 hover:underline"
                >
                    Regístrate aquí
                </router-link>
            </p>
        </div>
    </form>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import { Mail, Eye, EyeOff } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const formData = reactive({
    email: '',
    password: '',
    remember: false
})

const errors = reactive({
    email: '',
    password: '',
    general: ''
})

const loading = ref(false)
const showPassword = ref(false)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const validateForm = () => {
    let isValid = true
    
    // Reset errors
    errors.email = ''
    errors.password = ''
    errors.general = ''

    // Validar email
    if (!formData.email) {
        errors.email = 'El correo electrónico es requerido'
        isValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
        errors.email = 'El correo electrónico no es válido'
        isValid = false
    }

    // Validar password
    if (!formData.password) {
        errors.password = 'La contraseña es requerida'
        isValid = false
    } else if (formData.password.length < 6) {
        errors.password = 'La contraseña debe tener al menos 6 caracteres'
        isValid = false
    }

    return isValid
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    loading.value = true

    try {
        await authStore.login({
            email: formData.email,
            password: formData.password
        })

        // Redirect to dashboard on success
        router.push('/dashboard')
    } catch (err) {
        // Manejar errores del backend
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            
            if (backendErrors.email) {
                errors.email = backendErrors.email[0]
            }
            if (backendErrors.password) {
                errors.password = backendErrors.password[0]
            }
            if (backendErrors.general) {
                errors.general = backendErrors.general[0]
            }
        } else {
            errors.general = err.response?.data?.message || 'Error al iniciar sesión. Por favor, intenta de nuevo.'
        }
    } finally {
        loading.value = false
    }
}
</script>