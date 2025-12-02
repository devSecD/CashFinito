<template>
    <div v-if="show" :class="alertClasses" role="alert">
        <div class="flex-shrink-0 mt-0.5">
            <component :is="iconComponent" :size="20" />
        </div>

        <div class="flex-1">
            <p v-if="title" class="font-semibold m-0 mb-1">{{ title }}</p>
            <p class="m-0 text-sm">
                <slot>{{ message }}</slot>
            </p>
        </div>

        <button 
            v-if="dismissible" 
            class="flex-shrink-0 bg-transparent border-none p-0 cursor-pointer opacity-70 hover:opacity-100 transition-opacity duration-200"
            @click="handleDismiss"
        >
            <XIcon :size="16" />
        </button>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { AlertCircle, CheckCircle, Info, XCircle, X as XIcon } from 'lucide-vue-next'

const props = defineProps({
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    }, 
    title: {
        type: String,
        default: ''
    }, 
    message: {
        type: String,
        default: ''
    }, 
    dismissible: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['dismiss'])
const show = ref(true)

const alertClasses = computed(() => {
    const baseClasses = [
        'flex',
        'items-start',
        'gap-3',
        'p-4',
        'border',
        'relative'
    ]

    const typeClasses = {
        success: [
            'bg-green-50',
            'border-green-200',
            'text-green-800'
        ],
        error: [
            'bg-red-50',
            'border-red-200',
            'text-red-800'
        ],
        warning: [
            'bg-yellow-50',
            'border-yellow-200',
            'text-yellow-800'
        ],
        info: [
            'bg-blue-50',
            'border-blue-200',
            'text-blue-800'
        ]
    }

    return [
        ...baseClasses,
        ...typeClasses[props.type]
    ]
})

const iconComponent = computed(() => {
    const icons = {
        success: CheckCircle, 
        error: XCircle, 
        warning: AlertCircle, 
        info: Info
    }
    return icons[props.type]
})

const handleDismiss = () => {
    show.value = false
    emit('dismiss')
}
</script>