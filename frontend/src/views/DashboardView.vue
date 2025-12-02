<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <div class="mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8 gap-4 flex-wrap">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 m-0 mb-2">
                        Bienvenido, {{ user?.name || 'Usuario' }}! 👋
                    </h1>
                    <p class="text-base text-gray-600 m-0">
                        Aquí está el resumen de tus finanzas
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <AppCard variant="elevated" class="transition-transform duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 text-white flex items-center justify-center flex-shrink-0">
                            <DollarSign :size="24" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 m-0 mb-1">Balance Total</p>
                            <h2 class="text-3xl font-bold text-gray-900 m-0">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="transition-transform duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-700 text-white flex items-center justify-center flex-shrink-0">
                            <TrendingUp :size="24" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 m-0 mb-1">Ingresos del Mes</p>
                            <h2 class="text-3xl font-bold text-gray-900 m-0">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="transition-transform duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center flex-shrink-0">
                            <TrendingDown :size="24" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 m-0 mb-1">Gastos del Mes</p>
                            <h2 class="text-3xl font-bold text-gray-900 m-0">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="transition-transform duration-200 hover:-translate-y-1">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 text-white flex items-center justify-center flex-shrink-0">
                            <Wallet :size="24" />
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 m-0 mb-1">Ahorros</p>
                            <h2 class="text-3xl font-bold text-gray-900 m-0">$0.00</h2>
                        </div>
                    </div>
                </AppCard>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <AppCard title="Transacciones Recientes" variant="elevated">
                    <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 text-white flex items-center justify-center mb-6">
                            <Receipt :size="48" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 m-0 mb-2">No hay transacciones aún</h3>
                        <p class="text-sm text-gray-600 m-0 mb-6">Comienza a registrar tus ingresos y gastos</p>
                        <AppButton variant="primary">
                            <Plus :size="20" />
                            <span>Nueva Transacción</span>
                        </AppButton>
                    </div>
                </AppCard>

                <AppCard title="Categorías de Gasto" variant="elevated">
                    <div class="flex flex-col items-center justify-center py-12 px-6 text-center">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 text-white flex items-center justify-center mb-6">
                            <PieChart :size="48" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 m-0 mb-2">Sin datos para mostrar</h3>
                        <p class="text-sm text-gray-600 m-0">Tus categorías de gasto aparecerán aquí</p>
                    </div>
                </AppCard>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuth } from '@/composables/useAuth'
import AppCard from '@/components/common/AppCard.vue'
import AppButton from '@/components/common/AppButton.vue'
import { 
    DollarSign, 
    TrendingUp, 
    TrendingDown, 
    Wallet,
    Receipt,
    PieChart,
    Plus
} from 'lucide-vue-next'

const { user } = useAuth()

</script>