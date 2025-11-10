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
            console.log(
                `Iniciando upload de ${file.name} (${file.size} bytes)`,
            );

            // Criar FormData para envio
            const formData = new FormData();
            formData.append("image", file);
            formData.append("folder", folder);

            // Fazer upload via API com timeout
            const response = await apiClient.post("/upload/image", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
                timeout: 30000, // 30 segundos timeout
            });

            console.log("Upload concluído:", response.data);

            if (response.data?.data?.url) {
                showNotification("Imagem enviada com sucesso!", "success");
                return response.data.data.url;
            } else {
                throw new Error("Resposta inválida do servidor");
            }
        } catch (error) {
            console.error("Erro ao fazer upload da imagem:", error);

            let errorMessage = "Erro ao fazer upload da imagem.";
            if (error.code === "ECONNABORTED") {
                errorMessage = "Upload cancelado por timeout. Tente novamente.";
            } else if (error.response?.status === 413) {
                errorMessage = "Imagem muito grande. Máximo 5MB.";
            } else if (error.response?.status === 422) {
                errorMessage = "Formato de imagem inválido.";
            }

            showNotification(errorMessage, "error");
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
