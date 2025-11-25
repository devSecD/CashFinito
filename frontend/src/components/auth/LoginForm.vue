<template>
    <form @submit.prevent="handleSubmit" class="login-form">
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
                    class="password-toggle"
                    :disabled="loading"
                >
                    <Eye v-if="showPassword" :size="20" />
                    <EyeOff v-else :size="20" />
                </button>
            </template>
        </AppInput>

        <div class="form-options">
            <label class="remember-me">
                <input
                    type="checkbox"
                    v-model="formData.remember"
                    :disabled="loading"
                />
                <span>Recordarme</span>
            </label>

            <router-link to="/forgot-password" class="forgot-link">
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

        <div class="form-footer">
            <p>
                ¿No tienes cuenta?
                <router-link to="/register" class="register-link">
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

<style scoped>
.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: -0.5rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #374151;
    cursor: pointer;
}

.remember-me input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    cursor: pointer;
}

.forgot-link {
    font-size: 0.875rem;
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s;
}

.forgot-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

.password-toggle {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #9ca3af;
    display: flex;
    align-items: center;
    transition: color 0.2s;
}

.password-toggle:hover:not(:disabled) {
    color: #667eea;
}

.password-toggle:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.form-footer {
    text-align: center;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.form-footer p {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.register-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.register-link:hover {
    color: #764ba2;
    text-decoration: underline;
}
</style>