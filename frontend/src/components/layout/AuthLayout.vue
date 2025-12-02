<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-500 to-purple-700 p-8">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white m-0 mb-2 drop-shadow-md">
                    💰 CashFinito
                </h1>
                <p v-if="subtitle" class="text-base text-white/90 m-0">
                    {{ subtitle }}
                </p>
            </div>

            <slot />

            <div v-if="$slots.footer" class="mt-6 text-center text-white">
                <slot name="footer" />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    subtitle: {
        type: String, 
        default: ''
    }
})
</script>