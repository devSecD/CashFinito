// stores/currencies.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiClient from '@/api/axios'

export const useCurrenciesStore = defineStore('currencies', () => {
    const currencies = ref([])
    const loading = ref(false)
    const error = ref(null)
    
    async function fetchCurrencies(forceReload = false) {
        if (!forceReload && currencies.value.length > 0) {
            return currencies.value
        }
        
        loading.value = true
        error.value = null
        
        try {
            const response = await apiClient.get('/currencies')

            // Validar que exista la propiedad currencies
            if (!response.data || !Array.isArray(response.data.currencies)) {
                throw new Error('Formato de datos inválido en /currencies')
            }

            currencies.value = response.data.currencies
            return currencies.value

        } catch (err) {
            error.value = 'Error al cargar monedas'
            throw err
        } finally {
            loading.value = false
        }
    }
    
    function getDefaultCurrency() {
        return currencies.value.find(c => c.code === 'MXN') || null
    }

    function getCurrencyByCode(code) {
        return currencies.value.find(c => c.code === code) || null
    }
    
    return {
        currencies,
        loading,
        error,
        fetchCurrencies,
        getDefaultCurrency,
        getCurrencyByCode
    }
})
