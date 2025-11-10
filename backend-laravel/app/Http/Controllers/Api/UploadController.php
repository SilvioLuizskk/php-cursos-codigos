<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * POST /api/upload/image
     * Upload de imagem
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'folder' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('image');
            $folder = $request->input('folder', 'uploads');

            // Gerar nome único para o arquivo
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Caminho completo
            $path = $folder . '/' . date('Y/m/d') . '/' . $filename;

            // Salvar imagem
            $path = $file->storeAs($folder . '/' . date('Y/m/d'), $filename, 'public');

            // URL da imagem - usar caminho relativo para funcionar com proxy do Vite
            $url = '/storage/' . $path;

            return response()->json([
                'data' => [
                    'url' => $url,
                    'path' => $path,
                    'filename' => $filename,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ],
                'message' => 'Imagem enviada com sucesso',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload image', [
                'error' => $e->getMessage(),
                'file' => $request->file('image')?->getClientOriginalName(),
            ]);

            return response()->json([
                'message' => 'Erro ao fazer upload da imagem',
            ], 500);
        }
    }

    /**
     * DELETE /api/upload/image
     * Deletar imagem
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Caminho da imagem é obrigatório',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');

            // Verificar se o arquivo existe
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'message' => 'Imagem não encontrada',
                ], 404);
            }

            // Deletar arquivo
            Storage::disk('public')->delete($path);

            return response()->json([
                'message' => 'Imagem deletada com sucesso',
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete image', [
                'error' => $e->getMessage(),
                'path' => $request->input('path'),
            ]);

            return response()->json([
                'message' => 'Erro ao deletar imagem',
            ], 500);
        }
    }

    /**
     * GET /api/upload/images
     * Listar imagens de uma pasta
     */
    public function listImages(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'folder' => 'nullable|string|max:50',
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $folder = $request->input('folder', 'uploads');
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);

            // Listar arquivos da pasta
            $files = Storage::disk('public')->files($folder, true);

            // Filtrar apenas imagens
            $images = array_filter($files, function ($file) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            });

            // Ordenar por data de modificação (mais recentes primeiro)
            usort($images, function ($a, $b) {
                return Storage::disk('public')->lastModified($b) <=> Storage::disk('public')->lastModified($a);
            });

            // Paginação
            $total = count($images);
            $offset = ($page - 1) * $perPage;
            $paginatedImages = array_slice($images, $offset, $perPage);

            // Formatar resposta
            $formattedImages = array_map(function ($file) {
                return [
                    'path' => $file,
                    'url' => asset('storage/' . $file),
                    'filename' => basename($file),
                    'size' => Storage::disk('public')->size($file),
                    'last_modified' => Storage::disk('public')->lastModified($file),
                ];
            }, $paginatedImages);

            return response()->json([
                'data' => $formattedImages,
                'meta' => [
                    'total' => $total,
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'last_page' => ceil($total / $perPage),
                    'from' => $offset + 1,
                    'to' => min($offset + $perPage, $total),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to list images', [
                'error' => $e->getMessage(),
                'folder' => $request->input('folder'),
            ]);

            return response()->json([
                'message' => 'Erro ao listar imagens',
            ], 500);
        }
    }
}
