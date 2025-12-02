import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import apiClient from '@/api/axios'
import { getCsrfCookie } from '@/api/axios'

export const useAuthStore = defineStore('auth', () => {
    // State
    const user = ref(null)
    const token = ref(null)
    const loading = ref(false)
    const error = ref(null)

    // Getters
    const isAuthenticated = computed( () => !!token.value)
    const currentUser = computed( () => user.value)

    // Actions
    async function register(userData) {
        loading.value = true
        error.value = null

        try {

            await getCsrfCookie()
            const response = await apiClient.post('/auth/register', userData)

            token.value = response.data.access_token
            user.value = response.data.user

            // Guardar en localStorage
            localStorage.setItem('token', token.value)
            localStorage.setItem('user', JSON.stringify(user.value))

            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al registrar'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    async function login(credentials) {
        loading.value = true
        error.value = null

        try {

            await getCsrfCookie()    
            const response = await apiClient.post('/auth/login', credentials)

            token.value = response.data.access_token
            user.value = response.data.user

            localStorage.setItem('token', token.value)
            localStorage.setItem('user', JSON.stringify(user.value))

            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al iniciar sesión'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    async function logout() {
        loading.value = true

        try {
            await apiClient.post('/auth/logout')
        } catch (err) {
            console.error('Error al cerrar sesión:', err)
        } finally {
            token.value = null
            user.value = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            loading.value = false
        }
    }

    async function fetchUser() {
        loading.value = true

        try {
            const response = await apiClient.get('/auth/me')
            user.value = response.data.user
            localStorage.setItem('user', JSON.stringify(user.value))
        } catch (err) {
            console.error('Error al obtener usuario:', err)
            // Si falla, limpiar sesión
            token.value = null
            user.value = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        } finally {
            loading.value = false
        }
    }

    async function updateProfile(profileData) {
        loading.value = true
        error.value = null

        try {
            const response = await apiClient.put('/auth/profile', profileData)
            user.value = response.data.user
            localStorage.setItem('user', JSON.stringify(user.value))
            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al actualizar perfil'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    async function updateCurrency(currencyId) {
        loading.value = true
        error.value = null

        try {
            const response = await apiClient.post('/auth/settings/currency', {
            currency_id: currencyId
            })
            user.value = response.data.user
            localStorage.setItem('user', JSON.stringify(user.value))
            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al actualizar moneda'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    // Solicitar enlace de recuperación de contraseña
    async function forgotPassword(email) {
        loading.value = true
        error.value = null

        try {
            await getCsrfCookie()
            const response = await apiClient.post('/auth/password/email', { email })
            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al solicitar recuperación'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    // Resetear contraseña con token
    async function resetPassword(resetData) {
        loading.value = true
        error.value = null

        try {
            await getCsrfCookie()
            const response = await apiClient.post('/auth/password/reset', resetData)
            return response.data
        } catch (err) {
            error.value = err.response?.data?.errors || { general: ['Error al restablecer contraseña'] }
            throw err
        } finally {
            loading.value = false
        }
    }

    // Restaurar sesión desde localStorage
    function initAuth() {
        const savedToken  = localStorage.getItem('token')
        const savedUser  = localStorage.getItem('user')

        if (savedToken && savedUser) {
            token.value = savedToken
            user.value = JSON.parse(savedUser)
            // Verificar que el token siga válido
            fetchUser()
        }
    }

    return {
        // State
        user,
        token,
        loading,
        error,
        // Getters
        isAuthenticated,
        currentUser,
        // Actions
        register,
        login,
        logout,
        fetchUser,
        updateProfile,
        updateCurrency,
        forgotPassword,
        resetPassword,
        initAuth
    }
})