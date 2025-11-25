<template>
    <div v-if="show" :class="alertClasses" role="alert">

        <div class="alert-icon">
            <component :is="iconComponent" :size="20" />
        </div>

        <div class="alert-content">
            <p v-if="title" class="alert-title">{{ title }}</p>
            <p class="alert-message">
                <slot>{{ message }}</slot>
            </p>
        </div>

        <button v-if="dismissible" class="alert-close" @click="handleDismiss">
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

const alertClasses = computed( ()=> {
    const base = 'alert'
    const type = `alert-${props.type}`

    return [base, type].join(' ')
})

const iconComponent = computed( ()=>{
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

<style scoped>
.alert {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 0.5rem;
    border: 1px solid;
    position: relative;
}

.alert-success {
    background: #f0fdf4;
    border-color: #86efac;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border-color: #fca5a5;
    color: #991b1b;
}

.alert-warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}

.alert-info {
    background: #eff6ff;
    border-color: #93c5fd;
    color: #1e40af;
}

.alert-icon {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.alert-message {
    margin: 0;
    font-size: 0.875rem;
}

.alert-close {
    flex-shrink: 0;
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: currentColor;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.alert-close:hover {
    opacity: 1;
}
</style>
