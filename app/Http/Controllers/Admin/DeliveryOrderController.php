<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\StockMovement;
use App\Helpers\UploadManager;
use App\Models\WarehouseStock;
use App\Models\DeliveryOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderController extends Controller
{
    protected $deliveryOrder;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.delivery-order.index')->only('index');
        $this->middleware('can:admin.delivery-order.show')->only('show');
        $this->middleware('can:admin.delivery-order.create')->only('create', 'store');
        $this->middleware('can:admin.delivery-order.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.delivery-order.delete')->only('destroy');
        $this->middleware('can:admin.delivery-order.reduce-stock')->only('reduceStock');
        
        
    }

    public function index(Request $request)
    {
        $query = DeliveryOrder::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('delivery_no', 'like', '%' . $request->search . '%')
                    ->orWhere('date', 'like', '%' . $request->search . '%')
                    ->orWhere('note', 'like', '%' . $request->search . '%')
                    ->orWhereHas('salesOrder', function ($q) use ($request) {
                        $q->where('reference_no', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('warehouse', function ($q) use ($request) {
                        $q->where('title', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.delivery-order.index', compact('items'));
    }

    public function show(Request $request, DeliveryOrder $deliveryOrder)
    {
        // Query dasar relasi items
        $query = $deliveryOrder->items()->with('product');

        // Filter pencarian jika ada
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('qty', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('deliveryOrder', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $deliveryOrderItems = $query->paginate($perPage)->appends($request->all());

        // Ambil data produk untuk dropdown
        $products = Product::select('id', 'title')->get();

        return view('admin.delivery-order.show', compact('deliveryOrder', 'deliveryOrderItems', 'products'));
    }

    public function create()
    {
        $salesOrders = SalesOrder::where('status', 'draft')->get();
        return view('admin.delivery-order.form', compact('salesOrders'));
    }

    public function store(Request $request)
    {
        // Cek apakah SO sudah di-deliver
        $salesOrder = SalesOrder::find($request->input('sales_order_id'));
        if ($salesOrder->isComplete()) {
            return redirect()->back()->withErrors(['sales_order_id' => 'Sales Order ini sudah di-deliver.']);
        }

        $request->validate([
            'sales_order_id' => 'required|unique:delivery_orders,sales_order_id|exists:sales_orders,id',
            'delivery_date' => 'required|date',
            'note' => 'nullable|string',
        ], [
            'sales_order_id.unique' => 'Sales Order ini sudah digunakan untuk Delivery Order.',
        ]);

        $deliveryOrder = new DeliveryOrder();
        $deliveryOrder->delivery_no = 'DO-' . str_pad(DeliveryOrder::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $deliveryOrder->sales_order_id = $salesOrder->id;
        $deliveryOrder->warehouse_id = $salesOrder->warehouse_id;
        $deliveryOrder->delivery_date = $request->input('delivery_date');
        $deliveryOrder->note = $request->input('note');

        $deliveryOrder->save();

        // Auto-create delivery_order_items from salesOrder->items
        $salesOrder = SalesOrder::with('items')->find($request->input('sales_order_id'));
        foreach ($salesOrder->items as $item) {
            $deliveryOrderItem = new DeliveryOrderItem();
            $deliveryOrderItem->delivery_order_id = $deliveryOrder->id;
            $deliveryOrderItem->product_id = $item->product_id;
            $deliveryOrderItem->qty = $item->qty;
            $deliveryOrderItem->deliverd_qty = $item->qty;

            $deliveryOrderItem->save();
        }
        $deliveryOrder->salesOrder->status = 'confirmed';
        $deliveryOrder->salesOrder->approved_by = Auth::id();
        $deliveryOrder->salesOrder->approved_at = now();
        $deliveryOrder->salesOrder->save();

        return redirect()->route('admin.delivery-order.index')
            ->with('success', 'Delivery Order created successfully.');
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        // Cek apakah SO sudah di-deliver
        if ($deliveryOrder->salesOrder->isComplete()) {
            return redirect()->back()->withErrors(['sales_order_id' => 'Sales Order ini sudah di-deliver.']);
        }

        $salesOrders = SalesOrder::where('status', 'pending_approval')->get();
        return view('admin.delivery-order.form', compact('deliveryOrder', 'salesOrders'));
    }

    public function update(Request $request, DeliveryOrder $deliveryOrder)
    {
        // Cek apakah SO sudah di-deliver
        if ($deliveryOrder->salesOrder->isComplete()) {
            return redirect()->back()->withErrors(['sales_order_id' => 'Sales Order ini sudah di-deliver.']);
        }

        $request->validate([
            'delivery_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $deliveryOrder->delivery_date = $request->input('delivery_date');
        $deliveryOrder->note = $request->input('note');
        $deliveryOrder->save();

        return redirect()->route('admin.delivery-order.index')
            ->with('success', 'Delivery Order updated successfully.');
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        // Cek apakah SO sudah di-deliver
        if ($deliveryOrder->salesOrder->isComplete()) {
            return redirect()->back()->withErrors(['sales_order_id' => 'Sales Order ini sudah di-deliver.']);
        }

        $deliveryOrder->delete();
        $deliveryOrder->salesOrder->status = 'draft';
        $deliveryOrder->salesOrder->approved_by = null;
        $deliveryOrder->salesOrder->approved_at = null;
        $deliveryOrder->salesOrder->save();

        return redirect()->route('admin.delivery-order.index')
            ->with('success', 'Delivery Order deleted successfully.');
    }

    public function reduceStock(Request $request, DeliveryOrder $deliveryOrder)
    {
        // Check if the Sales Order is already completed
        if ($deliveryOrder->salesOrder->isComplete()) {
            return redirect()->back()->withErrors(['sales_order_id' => 'This Sales Order has already been delivered.']);
        }

        DB::transaction(function () use ($deliveryOrder) {
            foreach ($deliveryOrder->items as $item) {
                // Check if there's enough stock
                $stock = WarehouseStock::where([
                    'warehouse_id' => $deliveryOrder->warehouse_id,
                    'product_id' => $item->product_id,
                ])->first();

                if (!$stock || $stock->stock < $item->delivered_qty) {
                    throw new \Exception("Insufficient stock for product ID: {$item->product_id}");
                }

                // Update stock
                $stock->stock = $stock->stock - $item->qty;
                $stock->save();

                // Update product stock (total / global)
                $maxStock = WarehouseStock::where('product_id', $item->product_id)->sum('stock');
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock = $maxStock;
                    $product->save();
                }

                // Record stock movement
                $movement = new StockMovement();
                $movement->product_id = $item->product_id;
                $movement->warehouse_id = $deliveryOrder->warehouse_id;
                $movement->type = 'out';
                $movement->qty = $item->qty;
                $movement->reference_type = 'delivery_order';
                $movement->reference_id = $deliveryOrder->id;
                $movement->note = 'Stock delivered for SO #' . $deliveryOrder->sales_order_id;
                $movement->save();
            }

            // Update sales order & delivery order status
            $deliveryOrder->salesOrder->status = 'completed';
            $deliveryOrder->salesOrder->save();
        });

        return redirect()->route('admin.delivery-order.index')
            ->with('success', 'Stock reduced successfully.');
    }
}
