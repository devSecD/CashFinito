<template>
    <nav class="bg-white border-b px-6 py-3 flex justify-between items-center">

        <RouterLink 
            to="/dashboard" 
            class="text-xl font-bold hover:opacity-80 transition"
        >
            CashFinito
        </RouterLink>

        <div class="relative" @click="toggleMenu">

            <div class="flex items-center gap-2 cursor-pointer">
                <CircleUser class="w-8 h-8 text-gray-700" />
                <span>{{ user.name }}</span>
            </div>

            <div
                v-if="menuOpen"
                class="absolute right-0 mt-2 w-48 bg-white border shadow-lg rounded-lg py-2"
            >
                <RouterLink
                    to="/profile"
                    class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100"
                >
                    <User size="16"/> Mi Perfil
                </RouterLink>

                <div class="border-t my-1"></div>

                <button
                    @click.stop="logout"
                    class="flex items-center gap-2 px-4 py-2 w-full text-left text-red-600 hover:bg-red-50"
                >
                    <LogOut size="16"/> Cerrar sesión
                </button>
            </div>

        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

// Lucide icons
import { CircleUser, LogOut, User } from 'lucide-vue-next'

const router = useRouter()
const auth = useAuthStore()
const user = auth.user

const menuOpen = ref(false)
const toggleMenu = () => (menuOpen.value = !menuOpen.value)

const logout = async () => {
    await auth.logout()
    router.push('/login')
}
</script>
