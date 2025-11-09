import { ref, reactive } from 'vue'

// Estado global das notificações
const notifications = ref([])
let nextId = 1

export function useNotification() {
  // Adicionar notificação
  const showNotification = (message, type = 'info', duration = 5000) => {
    const notification = {
      id: nextId++,
      message,
      type, // success, error, warning, info
      duration,
      timestamp: Date.now(),
    }

    notifications.value.push(notification)

    // Auto remover após duration
    if (duration > 0) {
      setTimeout(() => {
        removeNotification(notification.id)
      }, duration)
    }

    return notification.id
  }

  // Remover notificação
  const removeNotification = (id) => {
    const index = notifications.value.findIndex(n => n.id === id)
    if (index > -1) {
      notifications.value.splice(index, 1)
    }
  }

  // Limpar todas as notificações
  const clearNotifications = () => {
    notifications.value = []
  }

  // Atalhos para tipos específicos
  const showSuccess = (message, duration) => showNotification(message, 'success', duration)
  const showError = (message, duration) => showNotification(message, 'error', duration)
  const showWarning = (message, duration) => showNotification(message, 'warning', duration)
  const showInfo = (message, duration) => showNotification(message, 'info', duration)

  return {
    notifications,
    showNotification,
    removeNotification,
    clearNotifications,
    showSuccess,
    showError,
    showWarning,
    showInfo,
  }
}