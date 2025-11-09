import apiClient from './axios'

export const productService = {
  /**
   * Listar produtos com filtros
   * GET /api/products
   */
  async getProducts(filters = {}) {
    const response = await apiClient.get('/products', { params: filters })
    return response.data
  },

  /**
   * Obter produtos em destaque
   * GET /api/products/featured
   */
  async getFeatured(limit = 8) {
    const response = await apiClient.get('/products/featured', {
      params: { limit },
    })
    return response.data
  },

  /**
   * Obter detalhes de um produto
   * GET /api/products/{id}
   */
  async getProduct(id) {
    const response = await apiClient.get(`/products/${id}`)
    return response.data
  },

  /**
   * Criar produto (Admin)
   * POST /api/products
   */
  async createProduct(data) {
    const response = await apiClient.post('/products', data)
    return response.data
  },

  /**
   * Atualizar produto (Admin)
   * PUT /api/products/{id}
   */
  async updateProduct(id, data) {
    const response = await apiClient.put(`/products/${id}`, data)
    return response.data
  },

  /**
   * Deletar produto (Admin)
   * DELETE /api/products/{id}
   */
  async deleteProduct(id) {
    const response = await apiClient.delete(`/products/${id}`)
    return response.data
  },

  /**
   * Buscar produtos
   * GET /api/products?search=termo
   */
  async searchProducts(term, limit = 20) {
    const response = await apiClient.get('/products', {
      params: { search: term, per_page: limit },
    })
    return response.data
  },
}