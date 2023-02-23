<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\OrderFormRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class OrdersController extends Controller
{
    const PAGE_SIZE = 10;

    public function index()
    {
        $orders = Order::orderByDesc('date')->paginate(self::PAGE_SIZE);
        $count = $orders->count();
        $total = $orders->total();
        return view('admin.orders.index', compact('orders', 'count', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderFormRequest $request)
    {
//        die('here');
        $data = $request->validated();
        $order = Order::create($data);
        return redirect()->route('orders.add', ['id' => $order->id]);
    }

    public function add(Request $request)
    {
        $id = (int)$request->id;
        $order = Order::findOrFail($id);
        return view('admin.orders.addProduct', compact('order'));
    }

    public function search(Request $request)
    {
        $product = trim($request->product);
        $result = DB::table('products')
            ->where('title', 'LIKE', '%' . $product . '%')
            ->get()
            ->toArray();
//        $count = $result->count();
        if ($result) {
            return response()->json([
                'success' => true,
                'product' => $result,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'product' => 'required|string|max:255',
            'qty' => 'required|integer'
        ]);
        $product = Product::where('title', $data['product'])->firstOrFail();
        $order = Order::findOrFail($data['order_id']);

        $orderProduct = new OrderProduct();
        $orderProduct->title = $product->title;
        $orderProduct->price = $product->price;
        $orderProduct->qty = $orderProduct->qty + $data['qty'];
        $orderProduct->total = $orderProduct->total + $orderProduct->price * $orderProduct->qty;
        $orderProduct->order_id = $data['order_id'];
        $orderProduct->product_id = $product->id;
        $orderProduct->save();

        $order->total = $order->total + $orderProduct->total;
        $order->save();

        return view('admin.orders.addProduct', compact('order'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        $products = $order->orderProducts()->orderByDesc('created_at')->get();
        return view('admin.orders.show', compact('order', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderFormRequest $request, string $id)
    {
        $data = $request->validated();
        $order = Order::findOrFail($id);
        $order->update($data);
        flash('Обновлено !')->success();

        return redirect()->route('orders.show', [$id]);
    }

    // удаление товара из заказа
    public function destroy(string $id)
    {
//        dd($id);
        $orderProduct = OrderProduct::findOrFail($id);
        try {
            DB::beginTransaction();
            $order = $orderProduct->order;
            $deleted_sum = $orderProduct->total;
            $orderProduct->delete();
            $order->total = $order->total - $deleted_sum;
            $order->save();
            DB::commit();
            return redirect()->route('orders.show', [$order->id]);
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    // удаление заказа
    public function delete(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index');
    }
}
