<template>
    <button 
        :type="type"
        :disabled="disabled || loading"
        :class="buttonClasses"
        @click="handleClick"
    >
        <span v-if="loading" class="apinner"></span>
        <slot v-else />
    </button>
</template>

<script setup lang="ts">

import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String, 
        default: 'button', 
        validator: (value) => ['button', 'submit', 'reset'].includes(value)
    }, 
    variant: {
        type: String, 
        default: 'primary', 
        validator: (value) => ['primary', 'secondary', 'danger', 'ghost'].includes(value)
    }, 
    size: {
        type: String, 
        default: 'md', 
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    }, 
    disabled: {
        type: Boolean, 
        default: false
    }, 
    loading: {
        type: Boolean, 
        default: false
    }, 
    fullWidth: {
        type: Boolean, 
        default: false
    }
})

const emit = defineEmits(['click'])
const buttonClasses = computed(() => {
    const base = 'btn'
    const variant = `btn-${props.variant}`
    const size = `btn-${props.size}`
    const fullWidth = props.fullWidth ? 'btn-full' : ''
    const disabled = props.disabled || props.loading ? 'btn-disabled' : ''

    return [base, variant, size, fullWidth, disabled].filter(Boolean).join(' ')
})

const handleClick = (event) => {
    if (!props.disabled && !props.loading) {
        emit('click', event)
    }
}

</script>

<style scoped>
/* Base button styles */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    border-radius: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    outline: none;
}

.btn:focus-visible {
    ring: 2px;
    ring-offset: 2px;
}

/* Sizes */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-md {
    padding: 0.625rem 1.25rem;
    font-size: 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.125rem;
}

/* Variants */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover:not(.btn-disabled) {
    opacity: 0.9;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}

.btn-secondary:hover:not(.btn-disabled) {
    background: #d1d5db;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover:not(.btn-disabled) {
    background: #dc2626;
}

.btn-ghost {
    background: transparent;
    color: #667eea;
    border: 1px solid #e5e7eb;
}

.btn-ghost:hover:not(.btn-disabled) {
    background: #f9fafb;
    border-color: #667eea;
}

/* States */
.btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-full {
    width: 100%;
}

/* Loading spinner */
.spinner {
    display: inline-block;
    width: 1em;
    height: 1em;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>