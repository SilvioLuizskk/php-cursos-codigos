import apiClient from './axios'

export const authService = {
  /**
   * Registrar novo usuário
   * POST /api/auth/register
   */
  async register(data) {
    const response = await apiClient.post('/auth/register', data)
    return response.data
  },

  /**
   * Fazer login
   * POST /api/auth/login
   */
  async login(email, password) {
    const response = await apiClient.post('/auth/login', {
      email,
      password,
    })
    return response.data
  },

  /**
   * Fazer logout
   * POST /api/auth/logout
   */
  async logout() {
    const response = await apiClient.post('/auth/logout')
    return response.data
  },

  /**
   * Obter usuário autenticado
   * GET /api/auth/me
   */
  async getMe() {
    const response = await apiClient.get('/auth/me')
    return response.data
  },

  /**
   * Solicitar reset de senha
   * POST /api/auth/forgot-password
   */
  async forgotPassword(email) {
    const response = await apiClient.post('/auth/forgot-password', { email })
    return response.data
  },

  /**
   * Resetar senha
   * POST /api/auth/reset-password
   */
  async resetPassword(token, password, passwordConfirmation) {
    const response = await apiClient.post('/auth/reset-password', {
      token,
      password,
      password_confirmation: passwordConfirmation,
    })
    return response.data
  },
}