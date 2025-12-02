<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4 py-12">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">
            Restablecer contraseña
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Ingresa tu nueva contraseña
        </p>
        </div>

        <!-- Success Message -->
        <div 
        v-if="successMessage" 
        class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"
        role="alert"
        >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ successMessage }}</span>
        </div>
        <div class="mt-4">
            <router-link 
            to="/login" 
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition"
            >
            Ir al inicio de sesión →
            </router-link>
        </div>
        </div>

        <!-- Error Message -->
        <div 
        v-if="errorMessage" 
        class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"
        role="alert"
        >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ errorMessage }}</span>
        </div>
        </div>

        <!-- Form -->
        <form v-if="!successMessage" @submit.prevent="handleSubmit" class="mt-8 space-y-6">
        <div class="bg-white shadow-lg rounded-lg p-8 space-y-6">
            <!-- Email (readonly) -->
            <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Correo electrónico
            </label>
            <input
                id="email"
                v-model="email"
                type="email"
                readonly
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
            />
            </div>

            <!-- Password -->
            <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Nueva contraseña
            </label>
            <div class="relative">
                <input
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                autocomplete="new-password"
                class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                :class="{ 'border-red-500': errors.password }"
                placeholder="••••••••"
                />
                <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                <svg v-if="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                </button>
            </div>
            <p v-if="errors.password" class="mt-2 text-sm text-red-600">
                {{ errors.password[0] }}
            </p>
            </div>

            <!-- Password Confirmation -->
            <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Confirmar contraseña
            </label>
            <div class="relative">
                <input
                id="password_confirmation"
                v-model="passwordConfirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                required
                autocomplete="new-password"
                class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                :class="{ 'border-red-500': !passwordsMatch && passwordConfirmation }"
                placeholder="••••••••"
                />
                <button
                type="button"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                >
                <svg v-if="showPasswordConfirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
                </button>
            </div>
            <p v-if="!passwordsMatch && passwordConfirmation" class="mt-2 text-sm text-red-600">
                Las contraseñas no coinciden
            </p>
            </div>

            <!-- Password Requirements -->
            <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-xs font-medium text-gray-700 mb-2">La contraseña debe contener:</p>
            <ul class="space-y-1 text-xs text-gray-600">
                <li class="flex items-center" :class="passwordRequirements.minLength ? 'text-green-600' : ''">
                <svg class="w-4 h-4 mr-2" :class="passwordRequirements.minLength ? 'text-green-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Mínimo 8 caracteres
                </li>
                <li class="flex items-center" :class="passwordRequirements.hasUpperCase ? 'text-green-600' : ''">
                <svg class="w-4 h-4 mr-2" :class="passwordRequirements.hasUpperCase ? 'text-green-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Al menos una mayúscula
                </li>
                <li class="flex items-center" :class="passwordRequirements.hasLowerCase ? 'text-green-600' : ''">
                <svg class="w-4 h-4 mr-2" :class="passwordRequirements.hasLowerCase ? 'text-green-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Al menos una minúscula
                </li>
                <li class="flex items-center" :class="passwordRequirements.hasNumber ? 'text-green-600' : ''">
                <svg class="w-4 h-4 mr-2" :class="passwordRequirements.hasNumber ? 'text-green-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Al menos un número
                </li>
            </ul>
            </div>

            <!-- Submit Button -->
            <button
            type="submit"
            :disabled="loading || !canSubmit"
            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Restableciendo...' : 'Restablecer contraseña' }}
            </button>
        </div>
        </form>

        <!-- Back to Login -->
        <div class="text-center">
        <router-link 
            to="/login" 
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition"
        >
            ← Volver al inicio de sesión
        </router-link>
        </div>
    </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const token = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const errors = ref({})

// Password validation
const passwordRequirements = computed(() => ({
    minLength: password.value.length >= 8,
    hasUpperCase: /[A-Z]/.test(password.value),
    hasLowerCase: /[a-z]/.test(password.value),
    hasNumber: /\d/.test(password.value)
}))

const passwordsMatch = computed(() => {
    if (!passwordConfirmation.value) return true
    return password.value === passwordConfirmation.value
})
const isPasswordValid = computed(() => {
    return Object.values(passwordRequirements.value).every(req => req === true)
})
const canSubmit = computed(() => {
    return isPasswordValid.value && passwordsMatch.value && password.value && passwordConfirmation.value
})
onMounted(() => {
// Obtener token y email de la URL
token.value = route.query.token || ''
email.value = route.query.email || ''
// Si no hay token o email, redirigir
if (!token.value || !email.value) {
    errorMessage.value = 'Enlace de recuperación inválido'
    setTimeout(() => {
        router.push('/forgot-password')
    }, 3000)
}
})

const handleSubmit = async () => {
    loading.value = true
    successMessage.value = ''
    errorMessage.value = ''
    errors.value = {}
    try {
        const response = await authStore.resetPassword({
            email: email.value,
            token: token.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value
        })

        successMessage.value = response.message

        // Redirigir al login después de 3 segundos
        setTimeout(() => {
            router.push('/login')
        }, 3000)
    } catch (error) {
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
            errorMessage.value = 'Por favor corrige los errores en el formulario'
        } else {
            errorMessage.value = error.response?.data?.message || 'Ocurrió un error al restablecer la contraseña'
        }
    } finally {
        loading.value = false
    }
}
</script>