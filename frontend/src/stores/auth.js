import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/api/authService'

export const useAuthStore = defineStore('auth', () => {
  // Estado
  const user = ref(null)
  const token = ref(localStorage.getItem('token'))
  const loading = ref(false)
  const error = ref(null)

  // Computados
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')

  // Ações
  async function register(data) {
    loading.value = true
    error.value = null

    try {
      const response = await authService.register(data)
      token.value = response.token
      user.value = response.user
      localStorage.setItem('token', response.token)
      localStorage.setItem('user', JSON.stringify(response.user))
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Erro ao registrar'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function login(email, password) {
    loading.value = true
    error.value = null

    try {
      const response = await authService.login(email, password)
      token.value = response.token
      user.value = response.user
      localStorage.setItem('token', response.token)
      localStorage.setItem('user', JSON.stringify(response.user))
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Email ou senha inválidos'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await authService.logout()
    } catch (err) {
      console.error('Erro ao fazer logout:', err)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }

  async function fetchMe() {
    loading.value = true

    try {
      const response = await authService.getMe()
      user.value = response.data || response
      localStorage.setItem('user', JSON.stringify(user.value))
      return user.value
    } catch (err) {
      token.value = null
      localStorage.removeItem('token')
      throw err
    } finally {
      loading.value = false
    }
  }

  function setToken(newToken) {
    token.value = newToken
    localStorage.setItem('token', newToken)
  }

  function setUser(newUser) {
    user.value = newUser
    localStorage.setItem('user', JSON.stringify(newUser))
  }

  // Inicializar usuário do localStorage se existir
  if (token.value && !user.value) {
    const storedUser = localStorage.getItem('user')
    if (storedUser) {
      user.value = JSON.parse(storedUser)
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    register,
    login,
    logout,
    fetchMe,
    setToken,
    setUser,
  }
})