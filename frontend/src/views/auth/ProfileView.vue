<template>
    <div class="profile-page">
        <div class="profile-container">
            <div class="profile-header">
                <h1 class="profile-title">Mi Perfil</h1>
                <p class="profile-subtitle">Administra tu información personal</p>
            </div>

            <div class="profile-content">
                <!-- Tarjeta de información del usuario -->
                <AppCard title="Información Personal" variant="elevated">
                    <form @submit.prevent="handleUpdateProfile" class="profile-form">
                        <AppAlert
                            v-if="successMessage"
                            type="success"
                            :message="successMessage"
                            :dismissible="true"
                            @dismiss="successMessage = ''"
                        />

                        <AppAlert
                            v-if="errorMessage"
                            type="error"
                            :message="errorMessage"
                            :dismissible="true"
                            @dismiss="errorMessage = ''"
                        />

                        <AppInput
                            id="name"
                            v-model="profileData.name"
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
                            v-model="profileData.email"
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

                        <div class="form-actions">
                            <AppButton
                                type="submit"
                                variant="primary"
                                :loading="loading"
                                :disabled="loading"
                            >
                                {{ loading ? 'Guardando...' : 'Guardar cambios' }}
                            </AppButton>

                            <AppButton
                                type="button"
                                variant="ghost"
                                :disabled="loading"
                                @click="resetForm"
                            >
                                Cancelar
                            </AppButton>
                        </div>
                    </form>
                </AppCard>

                <!-- Tarjeta de cambio de contraseña -->
                <AppCard title="Cambiar Contraseña" variant="elevated">
                    <form @submit.prevent="handleChangePassword" class="password-form">
                        <AppAlert
                            v-if="passwordSuccessMessage"
                            type="success"
                            :message="passwordSuccessMessage"
                            :dismissible="true"
                            @dismiss="passwordSuccessMessage = ''"
                        />

                        <AppAlert
                            v-if="passwordErrorMessage"
                            type="error"
                            :message="passwordErrorMessage"
                            :dismissible="true"
                            @dismiss="passwordErrorMessage = ''"
                        />

                        <AppInput
                            id="current_password"
                            v-model="passwordData.current_password"
                            label="Contraseña actual"
                            :type="showCurrentPassword ? 'text' : 'password'"
                            placeholder="••••••••"
                            :error="passwordErrors.current_password"
                            :disabled="passwordLoading"
                            required
                        >
                            <template #icon>
                                <button
                                    type="button"
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="password-toggle"
                                >
                                    <Eye v-if="showCurrentPassword" :size="20" />
                                    <EyeOff v-else :size="20" />
                                </button>
                            </template>
                        </AppInput>

                        <AppInput
                            id="new_password"
                            v-model="passwordData.new_password"
                            label="Nueva contraseña"
                            :type="showNewPassword ? 'text' : 'password'"
                            placeholder="••••••••"
                            :error="passwordErrors.new_password"
                            :disabled="passwordLoading"
                            hint="Mínimo 8 caracteres"
                            required
                        >
                            <template #icon>
                                <button
                                    type="button"
                                    @click="showNewPassword = !showNewPassword"
                                    class="password-toggle"
                                >
                                    <Eye v-if="showNewPassword" :size="20" />
                                    <EyeOff v-else :size="20" />
                                </button>
                            </template>
                        </AppInput>

                        <AppInput
                            id="new_password_confirmation"
                            v-model="passwordData.new_password_confirmation"
                            label="Confirmar nueva contraseña"
                            :type="showConfirmPassword ? 'text' : 'password'"
                            placeholder="••••••••"
                            :error="passwordErrors.new_password_confirmation"
                            :disabled="passwordLoading"
                            required
                        >
                            <template #icon>
                                <button
                                    type="button"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="password-toggle"
                                >
                                    <Eye v-if="showConfirmPassword" :size="20" />
                                    <EyeOff v-else :size="20" />
                                </button>
                            </template>
                        </AppInput>

                        <div class="form-actions">
                            <AppButton
                                type="submit"
                                variant="primary"
                                :loading="passwordLoading"
                                :disabled="passwordLoading"
                            >
                                {{ passwordLoading ? 'Actualizando...' : 'Cambiar contraseña' }}
                            </AppButton>

                            <AppButton
                                type="button"
                                variant="ghost"
                                :disabled="passwordLoading"
                                @click="resetPasswordForm"
                            >
                                Cancelar
                            </AppButton>
                        </div>
                    </form>
                </AppCard>

                <!-- Botón de cerrar sesión -->
                <AppCard variant="elevated">
                    <div class="logout-section">
                        <div class="logout-info">
                            <h3>Cerrar sesión</h3>
                            <p>Sal de tu cuenta de forma segura</p>
                        </div>
                        <AppButton
                            variant="danger"
                            @click="handleLogout"
                            :disabled="loading || passwordLoading"
                        >
                            <LogOut :size="16" />
                            <span>Cerrar sesión</span>
                        </AppButton>
                    </div>
                </AppCard>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuth } from '@/composables/useAuth'
import AppCard from '@/components/common/AppCard.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import { User, Mail, Eye, EyeOff, LogOut } from 'lucide-vue-next'
import apiClient from '@/api/axios'

const { user, logout, updateProfile } = useAuth()

// Profile form
const profileData = reactive({
    name: '',
    email: ''
})

const errors = reactive({
    name: '',
    email: ''
})

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Password form
const passwordData = reactive({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
})

const passwordErrors = reactive({
    current_password: '',
    new_password: '',
    new_password_confirmation: ''
})

const passwordLoading = ref(false)
const passwordSuccessMessage = ref('')
const passwordErrorMessage = ref('')

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)

// Load user data
onMounted(() => {
    if (user.value) {
        profileData.name = user.value.name || ''
        profileData.email = user.value.email || ''
    }
})

const resetForm = () => {
    if (user.value) {
        profileData.name = user.value.name || ''
        profileData.email = user.value.email || ''
    }
    errors.name = ''
    errors.email = ''
    errorMessage.value = ''
}

const resetPasswordForm = () => {
    passwordData.current_password = ''
    passwordData.new_password = ''
    passwordData.new_password_confirmation = ''
    passwordErrors.current_password = ''
    passwordErrors.new_password = ''
    passwordErrors.new_password_confirmation = ''
    passwordErrorMessage.value = ''
}

const validateProfileForm = () => {
    let isValid = true
    errors.name = ''
    errors.email = ''

    if (!profileData.name) {
        errors.name = 'El nombre es requerido'
        isValid = false
    }

    if (!profileData.email) {
        errors.email = 'El correo es requerido'
        isValid = false
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profileData.email)) {
        errors.email = 'Correo electrónico inválido'
        isValid = false
    }

    return isValid
}

const validatePasswordForm = () => {
    let isValid = true
    passwordErrors.current_password = ''
    passwordErrors.new_password = ''
    passwordErrors.new_password_confirmation = ''

    if (!passwordData.current_password) {
        passwordErrors.current_password = 'La contraseña actual es requerida'
        isValid = false
    }

    if (!passwordData.new_password) {
        passwordErrors.new_password = 'La nueva contraseña es requerida'
        isValid = false
    } else if (passwordData.new_password.length < 8) {
        passwordErrors.new_password = 'Debe tener al menos 8 caracteres'
        isValid = false
    }

    if (!passwordData.new_password_confirmation) {
        passwordErrors.new_password_confirmation = 'Debes confirmar la contraseña'
        isValid = false
    } else if (passwordData.new_password !== passwordData.new_password_confirmation) {
        passwordErrors.new_password_confirmation = 'Las contraseñas no coinciden'
        isValid = false
    }

    return isValid
}

const handleUpdateProfile = async () => {
    if (!validateProfileForm()) return

    loading.value = true
    successMessage.value = ''
    errorMessage.value = ''

    try {
        const result = await updateProfile({
            name: profileData.name,
            email: profileData.email
        })

        if (result.success) {
            successMessage.value = 'Perfil actualizado correctamente'
        } else {
            errorMessage.value = result.error
        }
    } catch (err) {
        errorMessage.value = 'Error al actualizar el perfil'
    } finally {
        loading.value = false
    }
}

const handleChangePassword = async () => {
    if (!validatePasswordForm()) return

    passwordLoading.value = true
    passwordSuccessMessage.value = ''
    passwordErrorMessage.value = ''

    try {
        await apiClient.post('/auth/change-password', passwordData)
        passwordSuccessMessage.value = 'Contraseña actualizada correctamente'
        resetPasswordForm()
    } catch (err) {
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            if (backendErrors.current_password) {
                passwordErrors.current_password = backendErrors.current_password[0]
            }
            if (backendErrors.new_password) {
                passwordErrors.new_password = backendErrors.new_password[0]
            }
        }
        passwordErrorMessage.value = err.response?.data?.message || 'Error al cambiar la contraseña'
    } finally {
        passwordLoading.value = false
    }
}

const handleLogout = async () => {
    await logout()
}
</script>

<style scoped>
.profile-page {
    min-height: 100vh;
    background: #f3f4f6;
    padding: 2rem;
}

.profile-container {
    max-width: 800px;
    margin: 0 auto;
}

.profile-header {
    margin-bottom: 2rem;
}

.profile-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.profile-subtitle {
    font-size: 1rem;
    color: #6b7280;
    margin: 0;
}

.profile-content {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-form,
.password-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    padding-top: 0.5rem;
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

.password-toggle:hover {
    color: #667eea;
}

.logout-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.logout-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.25rem 0;
}

.logout-info p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}

@media (max-width: 640px) {
    .logout-section {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>