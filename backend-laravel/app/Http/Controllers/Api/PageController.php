<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    /**
     * GET /api/pages
     * Listar páginas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $pages = Page::ordered()->get();

            return response()->json([
                'data' => $pages,
                'meta' => [
                    'total' => $pages->count(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch pages', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erro ao buscar páginas',
            ], 500);
        }
    }

    /**
     * GET /api/pages/{id}
     * Obter detalhes de uma página
     */
    public function show(Page $page): JsonResponse
    {
        return response()->json([
            'data' => $page,
        ]);
    }

    /**
     * GET /api/pages/slug/{slug}
     * Obter página por slug
     */
    public function showBySlug($slug): JsonResponse
    {
        $page = Page::findBySlug($slug);

        if (!$page) {
            return response()->json([
                'message' => 'Página não encontrada',
            ], 404);
        }

        return response()->json([
            'data' => $page,
        ]);
    }

    /**
     * POST /api/pages
     * Criar nova página
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'active' => 'boolean',
            'order' => 'integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $page = Page::create($validator->validated());

            return response()->json([
                'data' => $page,
                'message' => 'Página criada com sucesso',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create page', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Erro ao criar página',
            ], 500);
        }
    }

    /**
     * PUT /api/pages/{id}
     * Atualizar página
     */
    public function update(Request $request, Page $page): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'active' => 'boolean',
            'order' => 'integer|min:1',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $page->update($validator->validated());

            return response()->json([
                'data' => $page,
                'message' => 'Página atualizada com sucesso',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update page', [
                'error' => $e->getMessage(),
                'page_id' => $page->id,
                'data' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Erro ao atualizar página',
            ], 500);
        }
    }

    /**
     * DELETE /api/pages/{id}
     * Deletar página
     */
    public function destroy(Page $page): JsonResponse
    {
        try {
            $page->delete();

            return response()->json([
                'message' => 'Página deletada com sucesso',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete page', [
                'error' => $e->getMessage(),
                'page_id' => $page->id,
            ]);

            return response()->json([
                'message' => 'Erro ao deletar página',
            ], 500);
        }
    }
}
