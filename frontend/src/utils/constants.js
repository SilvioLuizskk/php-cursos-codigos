export const ORDER_STATUS = {
  PENDING: "pending",
  CONFIRMED: "confirmed",
  PROCESSING: "processing",
  SHIPPED: "shipped",
  DELIVERED: "delivered",
  CANCELLED: "cancelled",
  REFUNDED: "refunded",
};

export const ORDER_STATUS_LABELS = {
  pending: "Pendente",
  confirmed: "Confirmado",
  processing: "Processando",
  shipped: "Enviado",
  delivered: "Entregue",
  cancelled: "Cancelado",
  refunded: "Reembolsado",
};

export const PAYMENT_STATUS = {
  UNPAID: "unpaid",
  PAID: "paid",
  PARTIALLY_REFUNDED: "partially_refunded",
  FULLY_REFUNDED: "fully_refunded",
};

export const PAYMENT_METHODS = {
  CREDIT_CARD: "credit_card",
  DEBIT_CARD: "debit_card",
  PIX: "pix",
  BOLETO: "boleto",
};

export const USER_ROLES = {
  USER: "user",
  ADMIN: "admin",
  SELLER: "seller",
};

export const PAGINATION_LIMITS = {
  PRODUCTS: 20,
  ORDERS: 10,
  REVIEWS: 5,
};

export const API_TIMEOUT = 30000; // 30 segundos
