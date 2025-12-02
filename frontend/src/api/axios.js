import axios from "axios"
axios.defaults.withXSRFToken = true;
// Función para obtener el CSRF cookie
export const getCsrfCookie = async () => {
    await axios.get('http://localhost:8000/sanctum/csrf-cookie', {
        withCredentials: true
    })
}

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api', 
    headers: {
    'Content-Type': 'application/json', 
    'Accept': 'application/json'
    }, 
    withCredentials: true  // MUY IMPORTANTE para cookies
})

// Interceptor para agregar token a todas las requests
// Obtener CSRF token antes de cada request
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    }, 
    (error) => Promise.reject(error)
)

// Interceptor para manejar errores de autenticación
apiClient.interceptors.response.use(
    (response) => response, 
    (error) => {
        if (error.response?.status === 401) {
            // Token inválido o expirado
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

export default apiClient