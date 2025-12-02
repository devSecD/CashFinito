<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 m-0 mb-2">Mi Perfil</h1>
                <p class="text-base text-gray-600 m-0">Administra tu información personal</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8">
                <!-- Tarjeta de información del usuario -->
                <AppCard title="Información Personal" variant="elevated">
                    <form @submit.prevent="handleUpdateProfile" class="flex flex-col gap-6">
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
                            :disabled="true"
                            hint="El correo electrónico no se puede modificar por seguridad"
                        >
                            <template #icon>
                                <Mail :size="20" />
                            </template>
                        </AppInput>

                        <div class="flex gap-4 pt-2">
                            <AppButton
                                type="submit"
                                variant="primary"
                                :loading="loading"
                                :disabled="loading || !hasProfileChanges"
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

                <!-- Tarjeta de configuración de moneda -->
                <AppCard title="Moneda Base" variant="elevated">
                    <form @submit.prevent="handleUpdateCurrency" class="flex flex-col gap-6">
                        <AppAlert
                            v-if="currencySuccessMessage"
                            type="success"
                            :message="currencySuccessMessage"
                            :dismissible="true"
                            @dismiss="currencySuccessMessage = ''"
                        />

                        <AppAlert
                            v-if="currencyErrorMessage"
                            type="error"
                            :message="currencyErrorMessage"
                            :dismissible="true"
                            @dismiss="currencyErrorMessage = ''"
                        />

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                                Selecciona tu moneda preferida
                            </label>
                            <div class="relative">
                                <select
                                    id="currency"
                                    v-model="selectedCurrencyId"
                                    :disabled="currencyLoading || currenciesStore.loading"
                                    class="appearance-none w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    :class="{ 'border-red-500': currencyErrors.currency_id }"
                                >
                                    <option value="" disabled>Selecciona una moneda</option>
                                    <option
                                        v-for="currency in currenciesStore.currencies"
                                        :key="currency.id"
                                        :value="currency.id"
                                    >
                                        {{ currency.code }} - {{ currency.name }} ({{ currency.symbol }})
                                        {{ currency.id === initialCurrencyId ? '✓ Actual' : '' }}
                                    </option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <DollarSign :size="20" class="text-gray-400" />
                                </div>
                            </div>
                            <p v-if="currencyErrors.currency_id" class="mt-2 text-sm text-red-600">
                                {{ currencyErrors.currency_id }}
                            </p>
                            <p class="mt-2 text-sm text-gray-500">
                                Esta será la moneda predeterminada para todas tus transacciones
                            </p>
                        </div>

                        <!-- Mostrar moneda actual -->
                        <div v-if="user?.setting?.currency" class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-100 rounded-full p-2">
                                    <DollarSign :size="20" class="text-purple-600" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700 m-0 mb-1">Moneda actual</p>
                                    <p class="text-lg font-semibold text-purple-600 m-0">
                                        {{ user.setting.currency.code }} - {{ user.setting.currency.name }} ({{ user.setting.currency.symbol }})
                                    </p>
                                </div>
                                <div v-if="!hasCurrencyChanges" class="flex items-center gap-1 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Mostrar si hay cambios pendientes -->
                        <div v-if="hasCurrencyChanges" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-900 m-0 mb-1">Cambio pendiente</p>
                                    <p class="text-sm text-blue-700 m-0">
                                        Cambiarás de 
                                        <strong>{{ getCurrencyName(initialCurrencyId) }}</strong>
                                        a 
                                        <strong>{{ getCurrencyName(selectedCurrencyId) }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-2">
                            <AppButton
                                type="submit"
                                variant="primary"
                                :loading="currencyLoading"
                                :disabled="currencyLoading || currenciesStore.loading || !hasCurrencyChanges"
                            >
                                {{ currencyLoading ? 'Actualizando...' : 'Actualizar moneda' }}
                            </AppButton>

                            <AppButton
                                type="button"
                                variant="ghost"
                                :disabled="currencyLoading"
                                @click="resetCurrencyForm"
                            >
                                Cancelar
                            </AppButton>
                        </div>
                    </form>
                </AppCard>

                <!-- Tarjeta de cambio de contraseña -->
                <AppCard title="Cambiar Contraseña" variant="elevated">
                    <form @submit.prevent="handleChangePassword" class="flex flex-col gap-6">
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
                                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500"
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
                            required
                        >
                            <template #icon>
                                <button
                                    type="button"
                                    @click="showNewPassword = !showNewPassword"
                                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500"
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
                                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500"
                                >
                                    <Eye v-if="showConfirmPassword" :size="20" />
                                    <EyeOff v-else :size="20" />
                                </button>
                            </template>
                        </AppInput>

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

                        <div class="flex gap-4 pt-2">
                            <AppButton
                                type="submit"
                                variant="primary"
                                :loading="passwordLoading"
                                :disabled="passwordLoading || !canSubmitPassword"
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
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { useCurrenciesStore } from '@/stores/currencies'
import AppCard from '@/components/common/AppCard.vue'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import { User, Mail, Eye, EyeOff, DollarSign } from 'lucide-vue-next'
import apiClient from '@/api/axios'
import { useRouter } from 'vue-router'

const { user, logout, updateProfile, updateCurrency } = useAuth()
const currenciesStore = useCurrenciesStore()
const router = useRouter()

// Profile form
const profileData = reactive({
    name: '',
    email: ''
})

const initialProfileData = ref({})

const errors = reactive({
    name: '',
    email: ''
})

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// 🆕 Currency form
const selectedCurrencyId = ref('')
const initialCurrencyId = ref('')

const currencyErrors = reactive({
    currency_id: ''
})

const currencyLoading = ref(false)
const currencySuccessMessage = ref('')
const currencyErrorMessage = ref('')

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

// Password validation
const passwordRequirements = computed(() => ({
    minLength: passwordData.new_password.length >= 8,
    hasUpperCase: /[A-Z]/.test(passwordData.new_password),
    hasLowerCase: /[a-z]/.test(passwordData.new_password),
    hasNumber: /\d/.test(passwordData.new_password)
}))

const passwordsMatch = computed(() => {
    if (!passwordData.new_password_confirmation) return true
    return passwordData.new_password === passwordData.new_password_confirmation
})

const isPasswordValid = computed(() => {
    return Object.values(passwordRequirements.value).every(req => req === true)
})

const canSubmitPassword = computed(() => {
    return (
        passwordData.current_password &&
        passwordData.new_password &&
        passwordData.new_password_confirmation &&
        isPasswordValid.value &&
        passwordsMatch.value
    )
})

const hasProfileChanges = computed(() => {
    return profileData.name !== initialProfileData.value.name
})

const hasCurrencyChanges = computed(() => {
    return selectedCurrencyId.value !== initialCurrencyId.value && selectedCurrencyId.value !== ''
})

// Load user data and currencies
onMounted(async () => {
    // Cargar currencies
    try {
        await currenciesStore.fetchCurrencies()
    } catch (error) {
        console.error('Error al cargar monedas:', error)
    }

    // Cargar datos del usuario
    if (user.value) {
        profileData.name = user.value.name || ''
        profileData.email = user.value.email || ''
        initialProfileData.value = {
            name: user.value.name || '',
            email: user.value.email || ''
        }
        if (user.value.currency) {
            selectedCurrencyId.value = user.value.currency.id
            initialCurrencyId.value = user.value.currency.id
        }
    }
})

// Watch for user changes
watch(user, (newUser) => {
    if (newUser) {
        profileData.name = newUser.name || ''
        profileData.email = newUser.email || ''
        initialProfileData.value = {
            name: newUser.name || '',
            email: newUser.email || ''
        }

        // Actualizar moneda
        if (newUser.setting?.currency) {
            selectedCurrencyId.value = newUser.setting.currency.id
            initialCurrencyId.value = newUser.setting.currency.id
        }
    }
})

const resetForm = () => {
    profileData.name = initialProfileData.value.name
    profileData.email = initialProfileData.value.email
    errors.name = ''
    errors.email = ''
    errorMessage.value = ''
    successMessage.value = ''
}

const resetCurrencyForm = () => {
    selectedCurrencyId.value = initialCurrencyId.value
    currencyErrors.currency_id = ''
    currencyErrorMessage.value = ''
    currencySuccessMessage.value = ''
}

const resetPasswordForm = () => {
    passwordData.current_password = ''
    passwordData.new_password = ''
    passwordData.new_password_confirmation = ''
    passwordErrors.current_password = ''
    passwordErrors.new_password = ''
    passwordErrors.new_password_confirmation = ''
    passwordErrorMessage.value = ''
    passwordSuccessMessage.value = ''
}

const validateProfileForm = () => {
    let isValid = true
    errors.name = ''

    if (!profileData.name) {
        errors.name = 'El nombre es requerido'
        isValid = false
    } else if (profileData.name.length > 255) {
        errors.name = 'El nombre no puede tener más de 255 caracteres'
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
            name: profileData.name
        })

        if (result.success) {
            successMessage.value = 'Perfil actualizado correctamente'
            initialProfileData.value.name = profileData.name
        } else {
            errorMessage.value = result.error
        }
    } catch (err) {
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            if (backendErrors.name) {
                errors.name = backendErrors.name[0]
            }
        }
        errorMessage.value = err.response?.data?.message || 'Error al actualizar el perfil'
    } finally {
        loading.value = false
    }
}

// 🆕 Handle currency update
const handleUpdateCurrency = async () => {
    if (!selectedCurrencyId.value) {
        currencyErrors.currency_id = 'Debes seleccionar una moneda'
        return
    }

    currencyLoading.value = true
    currencySuccessMessage.value = ''
    currencyErrorMessage.value = ''
    currencyErrors.currency_id = ''

    try {
        const result = await updateCurrency(selectedCurrencyId.value)

        if (result.success) {
            currencySuccessMessage.value = 'Moneda actualizada correctamente'
            initialCurrencyId.value = selectedCurrencyId.value
        } else {
            currencyErrorMessage.value = result.error
        }
    } catch (err) {
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            if (backendErrors.currency_id) {
                currencyErrors.currency_id = backendErrors.currency_id[0]
            }
        }
        currencyErrorMessage.value = err.response?.data?.message || 'Error al actualizar la moneda'
    } finally {
        currencyLoading.value = false
    }
}

const handleChangePassword = async () => {
    passwordLoading.value = true
    passwordSuccessMessage.value = ''
    passwordErrorMessage.value = ''
    
    // Reset errors
    passwordErrors.current_password = ''
    passwordErrors.new_password = ''
    passwordErrors.new_password_confirmation = ''

    try {
        await apiClient.post('/auth/change-password', passwordData)
        passwordSuccessMessage.value = 'Contraseña actualizada correctamente'
        await logout()
        router.push('/')
    } catch (err) {
        if (err.response?.data?.errors) {
            const backendErrors = err.response.data.errors
            if (backendErrors.current_password) {
                passwordErrors.current_password = backendErrors.current_password[0]
            }
            if (backendErrors.new_password) {
                passwordErrors.new_password = backendErrors.new_password[0]
            }
            if (backendErrors.new_password_confirmation) {
                passwordErrors.new_password_confirmation = backendErrors.new_password_confirmation[0]
            }
        }
        passwordErrorMessage.value = err.response?.data?.message || 'Error al cambiar la contraseña'
    } finally {
        passwordLoading.value = false
    }
}

// Helper para obtener el nombre de la moneda por ID
const getCurrencyName = (currencyId) => {
    if (!currencyId) return ''
    const currency = currenciesStore.currencies.find(c => c.id === currencyId)
    return currency ? `${currency.code} (${currency.symbol})` : ''
}

</script>