<template>
    <div class="auth-layout">
        <div class="auth-container">
            <div class="auth-header">
                <h1 class="auth-logo">💰 CashFinito</h1>
                <p v-if="subtitle" class="auth-subtitle">{{ subtitle }}</p>
            </div>

            <slot />

            <div v-if="$slots.footer" class="auth-footer">
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

<style scoped>
.auth-layout {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
}

.auth-container {
    width: 100%;
    max-width: 28rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-logo {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.auth-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
}

.auth-footer {
    margin-top: 1.5rem;
    text-align: center;
    color: white;
}
</style>
