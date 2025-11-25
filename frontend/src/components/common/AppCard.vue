<template>
    <div :class="cardClasses">
        <div v-if="$slots.header || title" class="card-header">
            <slot name="header">
                <h2 v-if="title" class="card-title">{{ title }}</h2>
            </slot>
        </div>
        <div class="card-body">
            <slot />
        </div>
        <div v-if="$slots.footer" class="card-footer">
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

const cardClasses = computed( () => {
    const base = 'card'
    const variant = `card-${props.variant}`

    return [base, variant].join()
})
</script>

<style scoped>
.card {
    background: white;
    border-radius: 0.75rem;
    overflow: hidden;
}

.card-default {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-elevated {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card-bordered {
    border: 1px solid #e5e7eb;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.card-footer {
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}
</style>
