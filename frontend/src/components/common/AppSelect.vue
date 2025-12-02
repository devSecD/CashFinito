<template>
    <div class="flex flex-col gap-2">
        <label v-if="label" :for="id" class="text-sm font-semibold text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-0.5">*</span>
        </label>

        <div class="relative">
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
            <div v-if="$slots.icon" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <slot name="icon" />
            </div>
        </div>
        
        <p v-if="error" class="text-sm text-red-600 m-0 font-medium">{{ error }}</p>
        <p v-else-if="hint" class="text-sm text-gray-500 m-0">{{ hint }}</p>
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
    options: {
        type: Array,
        required: true
    }, 
    optionLabel: {
        type: String,
        default: 'label'
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

const selectClasses = computed(() => {
    const baseClasses = [
        'w-full',
        'px-4',
        'py-3.5',
        'text-base',
        'border',
        'rounded-xl',
        'outline-none',
        'transition-all',
        'duration-200',
        'bg-white',
        'cursor-pointer',
        'appearance-none'
    ]

    if (props.error) {
        baseClasses.push(
            'border-red-400',
            'focus:border-red-500',
            'focus:ring-4',
            'focus:ring-red-100'
        )
    } else {
        baseClasses.push(
            'border-gray-300',
            'focus:border-purple-500',
            'focus:ring-4',
            'focus:ring-purple-100'
        )
    }

    if (props.disabled) {
        baseClasses.push(
            'bg-gray-50',
            'cursor-not-allowed',
            'opacity-60'
        )
    }

    return baseClasses
})

const handleChange = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>