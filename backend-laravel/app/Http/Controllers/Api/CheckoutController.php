<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Processar checkout e criar pedido
     */
    public function process(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'required|array',
            'shipping_address.street' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.state' => 'required|string',
            'shipping_address.zip_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'payment_method' => 'required|in:credit_card,debit_card,pix,boleto',
            'billing_address' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Buscar itens do carrinho
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Carrinho vazio'
            ], 400);
        }

        // Calcular totais
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
            $totalItems += $item->quantity;
        }

        $shippingCost = $this->calculateShipping($request->shipping_address, $totalItems);
        $discountAmount = 0; // TODO: implementar cupons
        $total = $subtotal + $shippingCost - $discountAmount;

        DB::beginTransaction();

        try {
            // Criar pedido
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address ?? $request->shipping_address,
                'notes' => $request->notes ?? null,
            ]);

            // Criar itens do pedido
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'customization' => $cartItem->customization,
                ]);

                // Reduzir estoque do produto
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Limpar carrinho
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'order' => $order->load('items.product'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao processar pedido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcular frete baseado no endereço e quantidade de itens
     */
    private function calculateShipping(array $address, int $totalItems): float
    {
        // Lógica simples de cálculo de frete
        $baseShipping = 10.00;

        // Frete grátis para compras acima de R$ 200
        if ($totalItems >= 5) {
            return 0.00;
        }

        // Taxa adicional por item
        $additionalCost = ($totalItems - 1) * 2.00;

        return $baseShipping + $additionalCost;
    }

    /**
     * Gerar número único do pedido
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Validar checkout (pré-processamento)
     */
    public function validateCheckout(Request $request): JsonResponse
    {
        $user = $request->user();

        // Buscar itens do carrinho
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'valid' => false,
                'message' => 'Carrinho vazio'
            ], 400);
        }

        // Verificar disponibilidade de estoque
        $unavailableItems = [];
        foreach ($cartItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                $unavailableItems[] = [
                    'product' => $item->product->name,
                    'available' => $item->product->stock_quantity,
                    'requested' => $item->quantity,
                ];
            }
        }

        if (!empty($unavailableItems)) {
            return response()->json([
                'valid' => false,
                'message' => 'Produtos fora de estoque',
                'unavailable_items' => $unavailableItems
            ], 400);
        }

        // Calcular totais
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $totalItems = $cartItems->sum('quantity');
        $shippingCost = $this->calculateShipping($request->shipping_address ?? [], $totalItems);
        $total = $subtotal + $shippingCost;

        return response()->json([
            'valid' => true,
            'summary' => [
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'total_items' => $totalItems,
            ]
        ]);
    }
}