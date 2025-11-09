import { ref } from "vue";
import { useNotification } from "./useNotification";
import { apiClient } from "@/services/api";

export function useImageUpload() {
    const { showNotification } = useNotification();
    const uploading = ref(false);

    const uploadImage = async (file, folder = "uploads") => {
        if (!file) return null;

        // Validar tipo de arquivo
        if (!file.type.startsWith("image/")) {
            showNotification(
                "Por favor, selecione apenas arquivos de imagem.",
                "error",
            );
            return null;
        }

        // Validar tamanho (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification("A imagem deve ter no máximo 5MB.", "error");
            return null;
        }

        uploading.value = true;

        try {
            // Criar FormData para envio
            const formData = new FormData();
            formData.append("image", file);
            formData.append("folder", folder);

            // Fazer upload via API
            const response = await apiClient.post("/upload/image", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            showNotification("Imagem enviada com sucesso!", "success");
            return response.data.data.url;
        } catch (error) {
            console.error("Erro ao fazer upload da imagem:", error);
            showNotification("Erro ao fazer upload da imagem.", "error");
            return null;
        } finally {
            uploading.value = false;
        }
    };

    const validateImageUrl = (url) => {
        if (!url) return true;
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    };

    return {
        uploading,
        uploadImage,
        validateImageUrl,
    };
}
