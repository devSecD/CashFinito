<template>
    <div class="flex flex-col gap-2">
        <label v-if="label" :for="id" class="text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <div class="relative">
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
            <div v-if="$slots.icon" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-auto">
                <slot name="icon" />
            </div>
        </div>
        
        <p v-if="error" class="text-sm text-red-500 m-0">{{ error }}</p>
        <p v-else-if="hint" class="text-sm text-gray-600 m-0">{{ hint }}</p>
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
    required: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue', 'blur'])

const inputClasses = computed(() => {
    const baseClasses = [
        'w-full',
        'p-4', 
        'text-base',
        'border',
        'outline-none',
        'transition-all',
        'duration-200',
        'bg-white'
    ]

    if (props.error) {
        baseClasses.push(
            'border-red-500',
            'focus:border-red-500',
            'focus:ring-2',
            'focus:ring-red-500/20'
        )
    } else {
        baseClasses.push(
            'border-gray-300',
            'focus:border-purple-500',
            'focus:ring-2',
            'focus:ring-purple-500/20'
        )
    }

    if (props.disabled) {
        baseClasses.push(
            'bg-gray-100',
            'cursor-not-allowed',
            'opacity-60'
        )
    }

    return baseClasses
})

const handleInput = (event) => {
    emit('update:modelValue', event.target.value)
}

const handleBlur = (event) => {
    emit('blur', event)
}
</script>