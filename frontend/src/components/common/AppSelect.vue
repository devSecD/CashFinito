<template>
    <div class="select-group">
        <label v-if="label" :for="id" class="select-label">
            {{ label }}
            <span v-if="required" class="required">*</span>
        </label>

        <select 
            :id="id"
            :value="modelValue"
            :disabled="disabled"
            :required="required"
            :class="selectClasses"
            @change="handleChange"
        >
            <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
            <option 
                v-for="option in options" 
                :key="option[optionValue]" 
                :value="option[optionValue]"
            >
                {{ option[optionLabel] }}
            </option>
        </select>
        <p v-if="error" class="select-error">{{ error }}</p>
        <p v-else-if="hint" class="select-hint">{{ hint }}</p>
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
        type: String,
        required: true
    }, 
    label: {
        type: String,
        default: ''
    }, 
    options: {
        type: Array,
        required: true
    }, 
    optionLabel: {
        type: Array,
        required: true
    }, 
    optionValue: {
        type: String,
        default: 'value'
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
    required: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const selectClasses = computed( () => {
    const base = 'select'
    const hasError = props.error ? 'select-error-state' : ''
    const disabled = props.disabled ? 'select-disabled' : ''

    return [base, hasError, disabled].filter(Boolean).join(' ')
})

const handleChange = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<style scoped>
.select-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.select-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.required {
    color: #ef4444;
    margin-left: 0.125rem;
}

.select {
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-size: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    outline: none;
    transition: all 0.2s;
    background: white;
    cursor: pointer;
}

.select:focus {
    border-color: #667eea;
    ring: 2px;
    ring-color: rgba(102, 126, 234, 0.2);
}

.select-error-state {
    border-color: #ef4444;
}

.select-error-state:focus {
    ring-color: rgba(239, 68, 68, 0.2);
}

.select-disabled {
    background: #f3f4f6;
    cursor: not-allowed;
    opacity: 0.6;
}

.select-error {
    font-size: 0.875rem;
    color: #ef4444;
    margin: 0;
}

.select-hint {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
}
</style>
