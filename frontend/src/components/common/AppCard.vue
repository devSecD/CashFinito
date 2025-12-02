<template>
    <div :class="cardClasses">
        <div v-if="$slots.header || title" class="px-6 py-4 border-b border-gray-200">
            <slot name="header">
                <h2 v-if="title" class="text-xl font-semibold text-gray-900 m-0">
                    {{ title }}
                </h2>
            </slot>
        </div>
        <div class="p-6">
            <slot />
        </div>
        <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <slot name="footer" />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: ''
    }, 
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'elevated', 'bordered'].includes(value)
    }
})

const cardClasses = computed(() => {
    const baseClasses = [
        'bg-white',
        'overflow-hidden'
    ]

    const variantClasses = {
        default: ['shadow-sm'],
        elevated: ['shadow-2xl'],
        bordered: ['border', 'border-gray-200']
    }

    return [
        ...baseClasses,
        ...variantClasses[props.variant]
    ]
})
</script>