<template>
    <form @submit.prevent="handleSubmit" class="flex flex-col gap-6">
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

        <AppSelect
            id="currency_id"
            v-model="formData.currency_id"
            label="Moneda"
            :options="currencies"
            option-label="name"
            option-value="id"
            placeholder="Selecciona tu moneda"
            :error="errors.currency_id"
            :disabled="loading || loadingCurrencies"
            required
        >
            <template #icon>
                <DollarSign :size="20" />
            </template>
        </AppSelect>

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
                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500 disabled:cursor-not-allowed disabled:opacity-50"
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
                    class="bg-transparent border-none p-0 cursor-pointer text-gray-400 flex items-center transition-colors duration-200 hover:text-purple-500 disabled:cursor-not-allowed disabled:opacity-50"
                    :disabled="loading"
                >
                    <Eye v-if="showPasswordConfirm" :size="20" />
                    <EyeOff v-else :size="20" />
                </button>
            </template>
        </AppInput>

        <div class="-mt-2">
            <label class="flex items-start gap-2 text-sm text-gray-700 cursor-pointer">
                <input
                    type="checkbox"
                    v-model="formData.acceptTerms"
                    :disabled="loading"
                    class="w-4 h-4 mt-0.5 cursor-pointer flex-shrink-0"
                />
                <span>
                    Acepto los 
                    <a href="#" class="text-purple-500 no-underline transition-colors duration-200 hover:text-purple-700 hover:underline">
                        términos y condiciones
                    </a>
                    y la 
                    <a href="#" class="text-purple-500 no-underline transition-colors duration-200 hover:text-purple-700 hover:underline">
                        política de privacidad
                    </a>
                </span>
            </label>
            <p v-if="errors.acceptTerms" class="mt-2 ml-6 text-sm text-red-500 m-0">
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

        <div class="text-center pt-4 border-t border-gray-200">
            <p class="m-0 text-sm text-gray-600">
                ¿Ya tienes cuenta?
                <router-link 
                    to="/login" 
                    class="text-purple-500 no-underline font-medium transition-colors duration-200 hover:text-purple-700 hover:underline"
                >
                    Inicia sesión aquí
                </router-link>
            </p>
        </div>
    </form>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCurrenciesStore } from '@/stores/currencies';
import { useAuthStore } from '@/stores/auth'
import AppInput from '@/components/common/AppInput.vue'
import AppButton from '@/components/common/AppButton.vue'
import AppAlert from '@/components/common/AppAlert.vue'
import AppSelect from '@/components/common/AppSelect.vue';
import { User, Mail, Eye, EyeOff, DollarSign } from 'lucide-vue-next'

const router = useRouter()
const authStore = useAuthStore()
const currenciesStore = useCurrenciesStore();

const currencies = ref([])
const loadingCurrencies = ref(false)

const formData = reactive({
    name: '',
    email: '',
    currency_id: '',
    password: '',
    password_confirmation: '',
    acceptTerms: false
})

const errors = reactive({
    name: '',
    email: '',
    currency_id: '',
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

// Cargar monedas al montar el componente
onMounted(async () => {
    await fetchCurrencies()
})

const fetchCurrencies = async () => {
    loadingCurrencies.value = true

    try {
        const list = await currenciesStore.fetchCurrencies()

        // AGREGADO: Guardar las monedas para mostrarlas en AppSelect
        currencies.value = list

        // AGREGADO: Seleccionar moneda por defecto (MXN)
        formData.currency_id = currenciesStore.getDefaultCurrency()?.id || ''
    }
    catch (err) {
        console.error('Error al cargar monedas:', err)
        errors.general = 'Error al cargar las monedas disponibles'
    }
    finally {
        loadingCurrencies.value = false
    }
}

const validateForm = () => {
    let isValid = true
    
    // Reset errors
    errors.name = ''
    errors.email = ''
    errors.currency_id = ''
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

    // Validar currency_id
    if (!formData.currency_id) {
        errors.currency_id = 'Debes seleccionar una moneda'
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
            currency_id: formData.currency_id,
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
            if (backendErrors.currency_id) {
                errors.currency_id = backendErrors.currency_id[0]
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