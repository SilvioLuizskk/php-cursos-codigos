import axios from "axios";
import { useAuthStore } from "@/stores/auth";

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
  withCredentials: true,
});

// Interceptador de requisição
apiClient.interceptors.request.use(
  (config) => {
    const authStore = useAuthStore();

    if (authStore.token) {
      config.headers.Authorization = `Bearer ${authStore.token}`;
    }

    return config;
  },
  (error) => Promise.reject(error)
);

// Interceptador de resposta
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    const authStore = useAuthStore();
    const originalRequest = error.config;

    // Se token expirou (401) e não é uma tentativa de retry
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;

      try {
        // Tentar renovar token
        const response = await axios.post(
          `${import.meta.env.VITE_API_URL}/auth/refresh`,
          {},
          {
            headers: {
              Authorization: `Bearer ${authStore.token}`,
            },
          }
        );

        authStore.setToken(response.data.token);
        originalRequest.headers.Authorization = `Bearer ${response.data.token}`;

        return apiClient(originalRequest);
      } catch (refreshError) {
        // Se falhar ao renovar, fazer logout
        authStore.logout();
        window.location.href = "/login";
        return Promise.reject(refreshError);
      }
    }

    return Promise.reject(error);
  }
);

export default apiClient;
