<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppNotifier;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:sanctum');
        $this->orderService = $orderService;
    }

    /**
     * GET /api/orders
     * Listar pedidos do usuário
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->validate([
                'status' => 'string|in:pending,processing,shipped,delivered,cancelled',
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:50',
            ]);

            $orders = $this->orderService->getUserOrders(
                $request->user()->id,
                $filters
            );

            return response()->json([
                'orders' => OrderResource::collection($orders),
                'meta' => [
                    'total' => $orders->total(),
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch user orders', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Erro ao carregar pedidos',
            ], 500);
        }
    }

    /**
     * POST /api/orders
     * Criar novo pedido
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder(
                userId: $request->user()->id,
                data: $request->validated()
            );

            return response()->json([
                'message' => 'Pedido criado com sucesso',
                'order' => new OrderResource($order),
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create order', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'data' => $request->validated(),
            ]);

            // Notify admin via WhatsApp (best-effort)
            try {
                $adminPhone = config('app.admin_whatsapp') ?: env('ADMIN_WHATSAPP');
                if ($adminPhone) {
                    $notifier = app(WhatsAppNotifier::class);
                    $text = "[ALERTA] Falha ao criar pedido para user_id={$request->user()->id}: {$e->getMessage()}";
                    $notifier->send($adminPhone, $text);
                }
            } catch (\Throwable $t) {
                Log::warning('Failed to send WhatsApp alert: ' . $t->getMessage());
            }

            // Retornar códigos de status específicos baseados no tipo de erro
            $statusCode = 400;
            $message = $e->getMessage();

            if (str_contains($message, 'Carrinho vazio')) {
                $statusCode = 422;
            } elseif (str_contains($message, 'sem estoque')) {
                $statusCode = 422;
            } elseif (str_contains($message, 'Cupom')) {
                $statusCode = 422;
            }

            return response()->json([
                'message' => $message,
            ], $statusCode);
        }
    }

    /**
     * GET /api/orders/{order}
     * Obter detalhes do pedido
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        try {
            // Verificar se o pedido pertence ao usuário
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Pedido não encontrado',
                ], 404);
            }

            return response()->json([
                'order' => new OrderResource($order->load(['items.product', 'shippingAddress'])),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch order details', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'order_id' => $order->id,
            ]);

            return response()->json([
                'message' => 'Erro ao carregar detalhes do pedido',
            ], 500);
        }
    }

    /**
     * PATCH /api/orders/{order}/cancel
     * Cancelar pedido
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        try {
            // Verificar se o pedido pertence ao usuário
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Pedido não encontrado',
                ], 404);
            }

            $cancelledOrder = $this->orderService->cancelOrder($order->id, $request->user()->id);

            return response()->json([
                'message' => 'Pedido cancelado com sucesso',
                'order' => new OrderResource($cancelledOrder),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to cancel order', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'order_id' => $order->id,
            ]);

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * GET /api/orders/{order}/tracking
     * Obter informações de rastreamento
     */
    public function tracking(Request $request, Order $order): JsonResponse
    {
        try {
            // Verificar se o pedido pertence ao usuário
            if ($order->user_id !== $request->user()->id) {
                return response()->json([
                    'message' => 'Pedido não encontrado',
                ], 404);
            }

            $trackingInfo = $this->orderService->getTrackingInfo($order->id);

            return response()->json([
                'tracking' => $trackingInfo,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch tracking info', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
                'order_id' => $order->id,
            ]);

            return response()->json([
                'message' => 'Erro ao carregar informações de rastreamento',
            ], 500);
        }
    }

    /**
     * GET /api/orders/stats
     * Obter estatísticas dos pedidos do usuário
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $stats = $this->orderService->getUserOrderStats($request->user()->id);

            return response()->json([
                'stats' => $stats,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to fetch order stats', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'message' => 'Erro ao carregar estatísticas',
            ], 500);
        }
    }
}
