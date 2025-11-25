import { helpers } from '@vuelidate/validators'

// Validador personalizado para nombre de usuario
export const alphaSpaces = helpers.regex(/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/)

// Validador para contraseña fuerte
export const strongPassword = helpers.regex(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/)

// Validador para número de teléfono (formato flexible)
export const phoneNumber = helpers.regex(/^[\d\s\-\+\(\)]+$/)

// Mensajes de error personalizados
export const errorMessages = {
    required: 'Este campo es requerido',
    email: 'Correo electrónico inválido',
    minLength: (min) => `Debe tener al menos ${min} caracteres`,
    maxLength: (max) => `Debe tener máximo ${max} caracteres`,
    sameAs: (field) => `Debe coincidir con ${field}`,
    alphaSpaces: 'Solo se permiten letras y espacios',
    strongPassword: 'Debe contener mayúsculas, minúsculas y números',
    phoneNumber: 'Número de teléfono inválido'
}

// Helper para extraer el primer error de vuelidate
export const getFirstError = (validation) => {
    if (!validation.$errors || validation.$errors.length === 0) {
    return ''
    }
    return validation.$errors[0].$message
}

// Helper para validar todo el formulario
export const validateForm = async (v$) => {
    const isValid = await v$.$validate()
    return isValid
}