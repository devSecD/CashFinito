// frontend/src/composables/useAuth.js
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

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
    router.push('/login')
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