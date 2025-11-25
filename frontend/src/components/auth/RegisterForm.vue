<template>
    <form @submit.prevent="handleSubmit" class="register-form">
        <AppInput
            id="name"
            v-model="formData.name"
            label="Nombre completo"
            type="text"
            placeholder="Juan Pérez"
            :error="errors.name"
            :disabled="loading"
            required
        >
            <template #icon>
                <User :size="20" />
            </template>
        </AppInput>

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
            hint="Mínimo 8 caracteres"
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

        <AppInput
            id="password_confirmation"
            v-model="formData.password_confirmation"
            label="Confirmar contraseña"
            :type="showPasswordConfirm ? 'text' : 'password'"
            placeholder="••••••••"
            :error="errors.password_confirmation"
            :disabled="loading"
            required
        >
            <template #icon>
                <button
                    type="button"
                    @click="togglePasswordConfirm"
                    class="password-toggle"
                    :disabled="loading"
                >
                    <Eye v-if="showPasswordConfirm" :size="20" />
                    <EyeOff v-else :size="20" />
                </button>
            </template>
        </AppInput>

        <div class="terms-check">
            <label class="terms-label">
                <input
                    type="checkbox"
                    v-model="formData.acceptTerms"
                    :disabled="loading"
                />
                <span>
                    Acepto los 
                    <a href="#" class="terms-link">términos y condiciones</a>
                    y la 
                    <a href="#" class="terms-link">política de privacidad</a>
                </span>
            </label>
            <p v-if="errors.acceptTerms" class="terms-error">
                {{ errors.acceptTerms }}
            </p>
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
            {{ loading ? 'Registrando...' : 'Crear cuenta' }}
        </AppButton>

        <div class="form-footer">
            <p>
                ¿Ya tienes cuenta?
                <router-link to="/login" class="login-link">
                    Inicia sesión aquí
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
import { User, Mail, Eye, EyeOff } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()

const formData = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    acceptTerms: false
})

const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    acceptTerms: '',
    general: ''
})

const loading = ref(false)
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const togglePasswordConfirm = () => {
    showPasswordConfirm.value = !showPasswordConfirm.value
}

const validateForm = () => {
    let isValid = true
    
    // Reset errors
    errors.name = ''
    errors.email = ''
    errors.password = ''
    errors.password_confirmation = ''
    errors.acceptTerms = ''
    errors.general = ''

    // Validar nombre
    if (!formData.name) {
        errors.name = 'El nombre es requerido'
        isValid = false
    } else if (formData.name.length < 3) {
        errors.name = 'El nombre debe tener al menos 3 caracteres'
        isValid = false
    }

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
    } else if (formData.password.length < 8) {
        errors.password = 'La contraseña debe tener al menos 8 caracteres'
        isValid = false
    }

    // Validar confirmación de password
    if (!formData.password_confirmation) {
        errors.password_confirmation = 'Debes confirmar tu contraseña'
        isValid = false
    } else if (formData.password !== formData.password_confirmation) {
        errors.password_confirmation = 'Las contraseñas no coinciden'
        isValid = false
    }

    // Validar términos
    if (!formData.acceptTerms) {
        errors.acceptTerms = 'Debes aceptar los términos y condiciones'
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
        await authStore.register({
            name: formData.name,
            email: formData.email,
            password: formData.password,
            password_confirmation: formData.password_confirmation
        })

        // Redirect to dashboard on success
        router.push('/dashboard')
    } catch (err) {
        // Manejar errores del backend
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            
            if (backendErrors.name) {
                errors.name = backendErrors.name[0]
            }
            if (backendErrors.email) {
                errors.email = backendErrors.email[0]
            }
            if (backendErrors.password) {
                errors.password = backendErrors.password[0]
            }
            if (backendErrors.password_confirmation) {
                errors.password_confirmation = backendErrors.password_confirmation[0]
            }
            if (backendErrors.general) {
                errors.general = backendErrors.general[0]
            }
        } else {
            errors.general = err.response?.data?.message || 'Error al registrarse. Por favor, intenta de nuevo.'
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.register-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
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

.terms-check {
    margin-top: -0.5rem;
}

.terms-label {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #374151;
    cursor: pointer;
}

.terms-label input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    margin-top: 0.125rem;
    cursor: pointer;
    flex-shrink: 0;
}

.terms-link {
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s;
}

.terms-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

.terms-error {
    margin: 0.5rem 0 0 1.5rem;
    font-size: 0.875rem;
    color: #ef4444;
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

.login-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.login-link:hover {
    color: #764ba2;
    text-decoration: underline;
}
</style>