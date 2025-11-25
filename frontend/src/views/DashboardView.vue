<!-- Vista simple por ahora -->
<template>
    <div class="dashboard-page">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="header-content">
                    <h1 class="dashboard-title">
                        Bienvenido, {{ user?.name || 'Usuario' }}! 👋
                    </h1>
                    <p class="dashboard-subtitle">
                        Aquí está el resumen de tus finanzas
                    </p>
                </div>
                <div class="header-actions">
                    <AppButton variant="ghost" @click="goToProfile">
                        <User :size="20" />
                        <span>Mi Perfil</span>
                    </AppButton>
                    <AppButton variant="danger" @click="handleLogout">
                        <LogOut :size="20" />
                        <span>Salir</span>
                    </AppButton>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <AppCard variant="elevated" class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-primary">
                            <DollarSign :size="24" />
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Balance Total</p>
                            <h2 class="stat-value">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-success">
                            <TrendingUp :size="24" />
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Ingresos del Mes</p>
                            <h2 class="stat-value">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-danger">
                            <TrendingDown :size="24" />
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Gastos del Mes</p>
                            <h2 class="stat-value">$0.00</h2>
                        </div>
                    </div>
                </AppCard>

                <AppCard variant="elevated" class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon stat-icon-warning">
                            <Wallet :size="24" />
                        </div>
                        <div class="stat-info">
                            <p class="stat-label">Ahorros</p>
                            <h2 class="stat-value">$0.00</h2>
                        </div>
                    </div>
                </AppCard>
            </div>

            <!-- Main Content -->
            <div class="dashboard-content">
                <AppCard title="Transacciones Recientes" variant="elevated">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <Receipt :size="48" />
                        </div>
                        <h3>No hay transacciones aún</h3>
                        <p>Comienza a registrar tus ingresos y gastos</p>
                        <AppButton variant="primary">
                            <Plus :size="20" />
                            <span>Nueva Transacción</span>
                        </AppButton>
                    </div>
                </AppCard>

                <AppCard title="Categorías de Gasto" variant="elevated">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <PieChart :size="48" />
                        </div>
                        <h3>Sin datos para mostrar</h3>
                        <p>Tus categorías de gasto aparecerán aquí</p>
                    </div>
                </AppCard>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import AppCard from '@/components/common/AppCard.vue'
import AppButton from '@/components/common/AppButton.vue'
import { 
    User, 
    LogOut, 
    DollarSign, 
    TrendingUp, 
    TrendingDown, 
    Wallet,
    Receipt,
    PieChart,
    Plus
} from 'lucide-vue-next'

const router = useRouter()
const { user, logout } = useAuth()

const goToProfile = () => {
    router.push('/profile')
}

const handleLogout = async () => {
    await logout()
}
</script>

<style scoped>
.dashboard-page {
    min-height: 100vh;
    background: #f3f4f6;
    padding: 2rem;
}

.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 1rem;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.dashboard-subtitle {
    font-size: 1rem;
    color: #6b7280;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-icon-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-icon-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.stat-icon-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 0.25rem 0;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.dashboard-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1.5rem;
    text-align: center;
}

.empty-icon {
    width: 5rem;
    height: 5rem;
    border-radius: 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0 0 1.5rem 0;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
    }
    
    .header-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-content {
        grid-template-columns: 1fr;
    }
}
</style>