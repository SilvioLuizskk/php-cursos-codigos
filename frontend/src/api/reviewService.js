import apiClient from './axios'

export const reviewService = {
  /**
   * Obter avaliações de um produto
   * GET /api/products/{productId}/reviews
   */
  async getReviews(productId, page = 1, perPage = 10) {
    const response = await apiClient.get(`/products/${productId}/reviews`, {
      params: { page, per_page: perPage },
    })
    return response.data
  },

  /**
   * Criar avaliação
   * POST /api/products/{productId}/reviews
   */
  async createReview(productId, data) {
    const response = await apiClient.post(`/products/${productId}/reviews`, data)
    return response.data
  },

  /**
   * Atualizar avaliação
   * PUT /api/reviews/{id}
   */
  async updateReview(id, data) {
    const response = await apiClient.put(`/reviews/${id}`, data)
    return response.data
  },

  /**
   * Deletar avaliação
   * DELETE /api/reviews/{id}
   */
  async deleteReview(id) {
    const response = await apiClient.delete(`/reviews/${id}`)
    return response.data
  },
}

export const categoryService = {
  /**
   * Listar categorias
   * GET /api/categories
   */
  async getCategories() {
    const response = await apiClient.get('/categories')
    return response.data
  },

  /**
   * Obter categoria por ID
   * GET /api/categories/{id}
   */
  async getCategory(id) {
    const response = await apiClient.get(`/categories/${id}`)
    return response.data
  },
}