<template>
    <div class="input-group">
        <label v-if="label" :for="id" class="input-label">
            {{ label }}
            <span v-if="required" class="required"></span>
        </label>

        <div class="input-wrapper">
            <input 
                :id="id" 
                :type="type" 
                :value="modelValue" 
                :placeholder="placeholder" 
                :disabled="disabled" 
                :required="required" 
                :class="inputClasses" 
                @input="handleInput" 
                @blur="handleBlur" 
            />
            <div v-if="$slots.icon" class="input-icon">
                <slot name="icon" />
            </div>
        </div>
        <p v-if="error" class="input-error">{{ error }}</p>
        <p v-else-if="hint" class="input-hint">{{ hint }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    }, 
    modelValue: {
        type: [String, Number],
        default: ''
    }, 
    label: {
        type: String,
        default: ''
    }, 
    type: {
        type: String,
        default: 'text'
    }, 
    placeholder: {
        type: String,
        default: ''
    }, 
    error: {
        type: String,
        default: ''
    }, 
    hint: {
        type: String,
        default: ''
    }, 
    disabled: {
        type: Boolean,
        default: false
    }, 
    require: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'blur'])

const inputClasses = computed( () => {
    const base = 'input'
    const hasError = props.error ? 'input-error-state' : ''
    const disabled = props.disabled ? 'input-disabled' : ''

    return [base, hasError, disabled].filter(Boolean).join(' ')
})

const handleInput = (event) => {
    emit('update:modelValue', event.target.value)
}

const handleBlur = (event) => {
    emit('blur', event)
}
</script>

<style scoped>
.input-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.required {
    color: #ef4444;
    margin-left: 0.125rem;
}

.input-wrapper {
    position: relative;
}

.input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    outline: none;
    transition: all 0.2s;
    background: white;
}

.input:focus {
    border-color: #667eea;
    ring: 2px;
    ring-color: rgba(102, 126, 234, 0.2);
}

.input-error-state {
    border-color: #ef4444;
}

.input-error-state:focus {
    ring-color: rgba(239, 68, 68, 0.2);
}

.input-disabled {
    background: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.6;
}

.input-icon {
    position: absolute;
    right: 0.875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
}

.input-error {
    font-size: 0.875rem;
    color: #ef4444;
    margin: 0;
}

.input-hint {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}
</style>