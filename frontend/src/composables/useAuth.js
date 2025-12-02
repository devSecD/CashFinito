// frontend/src/composables/useAuth.js
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'


export function usePasswordValidation() {
    const password = ref('')
    const confirmPassword = ref('')

    const passwordRequirements = computed(() => ({
        minLength: password.value.length >= 8,
        hasUpperCase: /[A-Z]/.test(password.value),
        hasLowerCase: /[a-z]/.test(password.value),
        hasNumber: /\d/.test(password.value),
        hasSpecialChar: /[!@#$%^&*(),.?":{}|<>]/.test(password.value)
    }))

    const passwordsMatch = computed(() => {
        if (!confirmPassword.value) return true
        return password.value === confirmPassword.value
    })

    const isPasswordValid = computed(() => {
        return Object.values(passwordRequirements.value).every(req => req === true)
    })

    const canSubmit = computed(() => {
        return isPasswordValid.value && passwordsMatch.value && password.value && confirmPassword.value
    })

    return {
        password,
        confirmPassword,
        passwordRequirements,
        passwordsMatch,
        isPasswordValid,
        canSubmit
    }
}

export function useAuth() {
    const authStore = useAuthStore()
    const router = useRouter()

    // Getters
    const user = computed(() => authStore.user)
    const isAuthenticated = computed(() => authStore.isAuthenticated)
    const isLoading = computed(() => authStore.loading)
    const error = computed(() => authStore.error)

    // Actions
    const login = async (credentials) => {
    try {
        await authStore.login(credentials)
        return { success: true }
    } catch (error) {
        return { 
        success: false, 
        error: error.response?.data?.message || 'Error al iniciar sesión' 
        }
    }
    }

    const register = async (userData) => {
    try {
        await authStore.register(userData)
        return { success: true }
    } catch (error) {
        return { 
        success: false, 
        error: error.response?.data?.message || 'Error al registrarse' 
        }
    }
    }

    const logout = async () => {
    await authStore.logout()
    }

    const updateProfile = async (profileData) => {
    try {
        await authStore.updateProfile(profileData)
        return { success: true }
    } catch (error) {
        return { 
        success: false, 
        error: error.response?.data?.message || 'Error al actualizar perfil' 
        }
    }
    }

    const updateCurrency = async (currencyId) => {
    try {
        await authStore.updateCurrency(currencyId)
        return { success: true }
    } catch (error) {
        return { 
        success: false, 
        error: error.response?.data?.message || 'Error al actualizar moneda' 
        }
    }
    }

    // Helpers
    const requireAuth = () => {
    if (!isAuthenticated.value) {
        router.push('/login')
        return false
    }
    return true
    }

    const requireGuest = () => {
    if (isAuthenticated.value) {
        router.push('/dashboard')
        return false
    }
    return true
    }

    return {
    // State
    user,
    isAuthenticated,
    isLoading,
    error,
    // Actions
    login,
    register,
    logout,
    updateProfile,
    updateCurrency,
    // Helpers
    requireAuth,
    requireGuest
    }
}