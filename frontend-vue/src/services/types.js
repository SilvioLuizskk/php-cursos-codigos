/**
 * @typedef {Object} ApiResponse
 * @property {any} data - Dados da resposta
 * @property {string} [message] - Mensagem opcional
 * @property {'success' | 'error'} status - Status da resposta
 */

/**
 * @typedef {Object} PaginatedResponse
 * @property {Array} data - Array de dados
 * @property {number} current_page - Página atual
 * @property {number} last_page - Última página
 * @property {number} per_page - Itens por página
 * @property {number} total - Total de itens
 */

/**
 * @typedef {Object} User
 * @property {number} id - ID do usuário
 * @property {string} name - Nome do usuário
 * @property {string} email - Email do usuário
 * @property {string} [email_verified_at] - Data de verificação do email
 * @property {boolean} is_admin - Se é administrador
 * @property {string} created_at - Data de criação
 * @property {string} updated_at - Data de atualização
 */

/**
 * @typedef {Object} Product
 * @property {number} id - ID do produto
 * @property {string} name - Nome do produto
 * @property {string} slug - Slug do produto
 * @property {string} description - Descrição do produto
 * @property {number} price - Preço do produto
 * @property {number} [sale_price] - Preço de venda (opcional)
 * @property {number} stock_quantity - Quantidade em estoque
 * @property {string} sku - SKU do produto
 * @property {string[]} images - Array de URLs de imagens
 * @property {number} category_id - ID da categoria
 * @property {boolean} is_active - Se está ativo
 * @property {string} created_at - Data de criação
 * @property {string} updated_at - Data de atualização
 */

/**
 * @typedef {Object} CartItem
 * @property {number} id - ID do item no carrinho
 * @property {Product} product - Produto
 * @property {number} quantity - Quantidade
 * @property {number} price - Preço unitário
 * @property {number} total - Preço total
 */

/**
 * @typedef {Object} Order
 * @property {number} id - ID do pedido
 * @property {number} user_id - ID do usuário
 * @property {'pending' | 'processing' | 'shipped' | 'delivered' | 'cancelled'} status - Status do pedido
 * @property {number} total - Total do pedido
 * @property {Address} shipping_address - Endereço de entrega
 * @property {Address} billing_address - Endereço de cobrança
 * @property {OrderItem[]} items - Itens do pedido
 * @property {string} created_at - Data de criação
 * @property {string} updated_at - Data de atualização
 */

/**
 * @typedef {Object} OrderItem
 * @property {number} id - ID do item do pedido
 * @property {Product} product - Produto
 * @property {number} quantity - Quantidade
 * @property {number} price - Preço unitário
 * @property {number} total - Preço total
 */

/**
 * @typedef {Object} Address
 * @property {number} id - ID do endereço
 * @property {number} user_id - ID do usuário
 * @property {string} name - Nome do destinatário
 * @property {string} street - Rua
 * @property {string} city - Cidade
 * @property {string} state - Estado
 * @property {string} zip_code - CEP
 * @property {string} country - País
 * @property {boolean} is_default - Se é o endereço padrão
 */

/**
 * @typedef {Object} Category
 * @property {number} id - ID da categoria
 * @property {string} name - Nome da categoria
 * @property {string} slug - Slug da categoria
 * @property {string} [description] - Descrição da categoria
 * @property {string} [image] - URL da imagem da categoria
 * @property {number} [parent_id] - ID da categoria pai
 * @property {boolean} is_active - Se está ativa
 */

/**
 * @typedef {Object} Banner
 * @property {number} id - ID do banner
 * @property {string} title - Título do banner
 * @property {string} image - URL da imagem
 * @property {string} [link] - Link do banner
 * @property {string} position - Posição do banner
 * @property {boolean} is_active - Se está ativo
 * @property {number} order - Ordem de exibição
 */

/**
 * @typedef {Object} ABTest
 * @property {number} id - ID do teste A/B
 * @property {string} name - Nome do teste
 * @property {string} description - Descrição do teste
 * @property {'draft' | 'running' | 'paused' | 'finished'} status - Status do teste
 * @property {ABVariant[]} variants - Variantes do teste
 * @property {string} [start_date] - Data de início
 * @property {string} [end_date] - Data de fim
 * @property {string} created_at - Data de criação
 * @property {string} updated_at - Data de atualização
 */

/**
 * @typedef {Object} ABVariant
 * @property {number} id - ID da variante
 * @property {string} name - Nome da variante
 * @property {any} content - Conteúdo da variante
 * @property {number} traffic_percentage - Percentual de tráfego
 * @property {number} conversions - Número de conversões
 * @property {number} views - Número de visualizações
 */

/**
 * @typedef {Object} ContactForm
 * @property {string} name - Nome do contato
 * @property {string} email - Email do contato
 * @property {string} subject - Assunto
 * @property {string} message - Mensagem
 */

/**
 * @typedef {Object} CheckoutData
 * @property {CartItem[]} items - Itens do checkout
 * @property {Partial<Address>} shipping_address - Endereço de entrega
 * @property {Partial<Address>} billing_address - Endereço de cobrança
 * @property {string} payment_method - Método de pagamento
 * @property {string} [coupon_code] - Código do cupom
 */

/**
 * @typedef {Object} PaymentMethod
 * @property {string} id - ID do método de pagamento
 * @property {string} name - Nome do método
 * @property {'credit_card' | 'debit_card' | 'paypal' | 'bank_transfer'} type - Tipo do método
 * @property {boolean} is_active - Se está ativo
 */

/**
 * @typedef {Object} Coupon
 * @property {number} id - ID do cupom
 * @property {string} code - Código do cupom
 * @property {'percentage' | 'fixed'} discount_type - Tipo de desconto
 * @property {number} discount_value - Valor do desconto
 * @property {number} [minimum_amount] - Valor mínimo para aplicar
 * @property {string} [expires_at] - Data de expiração
 * @property {boolean} is_active - Se está ativo
 */

/**
 * @typedef {Object} Notification
 * @property {number} id - ID da notificação
 * @property {number} user_id - ID do usuário
 * @property {string} title - Título da notificação
 * @property {string} message - Mensagem da notificação
 * @property {'info' | 'success' | 'warning' | 'error'} type - Tipo da notificação
 * @property {boolean} is_read - Se foi lida
 * @property {string} created_at - Data de criação
 */

/**
 * @typedef {Object} SiteSettings
 * @property {string} site_name - Nome do site
 * @property {string} site_description - Descrição do site
 * @property {string} contact_email - Email de contato
 * @property {string} [contact_phone] - Telefone de contato
 * @property {Object} social_links - Links das redes sociais
 * @property {string} [social_links.facebook] - Link do Facebook
 * @property {string} [social_links.instagram] - Link do Instagram
 * @property {string} [social_links.twitter] - Link do Twitter
 * @property {string} [social_links.youtube] - Link do YouTube
 * @property {string} currency - Moeda
 * @property {string} language - Idioma
 */
