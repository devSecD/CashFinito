<template>
    <button 
        :type="type"
        :disabled="disabled || loading"
        :class="buttonClasses"
        @click="handleClick"
    >
        <span v-if="loading" class="inline-block w-4 h-4 border-2 border-transparent border-t-current rounded-full animate-spin"></span>
        <slot v-else />
    </button>
</template>

<script setup>
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
    const baseClasses = [
        'inline-flex',
        'items-center',
        'justify-center',
        'font-medium',
        'transition-all',
        'duration-200',
        'cursor-pointer',
        'border-none',
        'outline-none',
        'focus-visible:ring-2',
        'focus-visible:ring-offset-2'
    ]

    // Sizes
    const sizeClasses = {
        sm: 'px-4 py-2 text-sm',
        md: 'px-5 py-2.5 text-base',
        lg: 'px-6 py-3 text-lg'
    }

    // Variants
    const variantClasses = {
        primary: [
            'bg-gradient-to-br',
            'from-purple-500',
            'to-purple-700',
            'text-white',
            'hover:opacity-90',
            'hover:-translate-y-0.5',
            'hover:shadow-lg',
            'hover:shadow-purple-500/40',
            'focus-visible:ring-purple-500'
        ],
        secondary: [
            'bg-gray-200',
            'text-gray-700',
            'hover:bg-gray-300',
            'focus-visible:ring-gray-400'
        ],
        danger: [
            'bg-red-500',
            'text-white',
            'hover:bg-red-600',
            'focus-visible:ring-red-500'
        ],
        ghost: [
            'bg-transparent',
            'text-purple-500',
            'border',
            'border-gray-200',
            'hover:bg-gray-50',
            'hover:border-purple-500',
            'focus-visible:ring-purple-500'
        ]
    }

    const classes = [
        ...baseClasses,
        ...sizeClasses[props.size].split(' '),
        ...variantClasses[props.variant]
    ]

    if (props.fullWidth) {
        classes.push('w-full')
    }

    if (props.disabled || props.loading) {
        classes.push('opacity-50', 'cursor-not-allowed', 'pointer-events-none')
    }

    return classes
})

const handleClick = (event) => {
    if (!props.disabled && !props.loading) {
        emit('click', event)
    }
}
</script>