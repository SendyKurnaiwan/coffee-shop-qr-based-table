<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Crud;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Order store request received:', $request->all());

        // FIXED VALIDATION - user_id is now at root level
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Moved from inside orders array
            'orders' => 'required|array|min:1',
            'orders.*.crud_id' => 'required|exists:cruds,id',
            'orders.*.quantity' => 'required|integer|min:1',
            'orders.*.special_instructions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $tableUser = User::where('id', $validated['user_id'])
            ->where('role', 'meja')
            ->first();

        if (!$tableUser) {
            return response()->json([
                'success' => false,
                'error' => 'Table not found or invalid table user'
            ], 404);
        }

        // Create the order
        $order = Order::create([
            'order_number' => 'ORD-' . time() . rand(100, 999),
            'user_id' => $tableUser->id,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'total_amount' => 0, // Will be calculated below
        ]);

        $totalAmount = 0;
        $orderItems = [];

        // Create order items (junction table records)
        foreach ($validated['orders'] as $orderData) {
            $crud = Crud::find($orderData['crud_id']);

            if (!$crud) {
                continue; // Skip if menu not found
            }

            $subtotal = $crud->harga * $orderData['quantity'];

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'crud_id' => $crud->id,
                'quantity' => $orderData['quantity'],
                'unit_price' => $crud->harga,
                'subtotal' => $subtotal,
                'special_instructions' => $orderData['special_instructions'] ?? null,
            ]);

            $totalAmount += $subtotal;
            $orderItems[] = $orderItem;
        }

        // Update order total
        $order->update(['total_amount' => $totalAmount]);

        // Mark table as occupied
        $tableUser->update(['is_occupied' => true]);

        Log::info('Order created with items:', ['order_id' => $order->id, 'item_count' => count($orderItems)]);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'order' => $order->load(['items.menu', 'user']),
            'order_number' => $order->order_number,
        ], 201);
    }

    public function getTableOrders($tableUserId)
    {
        $orders = Order::with(['items.menu'])
            ->where('user_id', $tableUserId)
            ->where('status', '!=', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders,
            'total_active_orders' => $orders->count()
        ]);
    }

    public function addItem(Request $request, $orderId)
    {
        $validated = $request->validate([
            'crud_id' => 'required|exists:cruds,id',
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string',
        ]);

        $order = Order::findOrFail($orderId);
        $crud = Crud::findOrFail($validated['crud_id']);

        // Check if same item already exists in order
        $existingItem = $order->items()
            ->where('crud_id', $crud->id)
            ->where('special_instructions', $validated['special_instructions'])
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->update([
                'quantity' => $existingItem->quantity + $validated['quantity'],
            ]);
            $item = $existingItem;
        } else {
            // Create new item
            $item = OrderItem::create([
                'order_id' => $order->id,
                'crud_id' => $crud->id,
                'quantity' => $validated['quantity'],
                'unit_price' => $crud->harga,
                'special_instructions' => $validated['special_instructions'],
            ]);
        }

        // Update order total
        $order->update(['total_amount' => $order->calculateTotal()]);

        return response()->json([
            'message' => 'Item added to order',
            'item' => $item->load('menu'),
            'order_total' => $order->total_amount
        ]);
    }
    public function show($orderNumber)
    {
        // Use where to find by order_number field
        $order = Order::with(['items.menu'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Generate dynamic QR data without storing
        $qrData = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total' => $order->total_amount,
            'status' => $order->status,
            'timestamp' => now()->toISOString()
        ];

        return view('order-details', compact('order', 'qrData'));
    }
}
