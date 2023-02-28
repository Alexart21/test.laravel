<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ApiOrderUpdate;

class OrderApiControllers extends Controller
{
    public function __construct()
    {
        auth()->setDefaultDriver('api'); // ВОТ без этой строчки не работала api&&web аутентификация !!!!
        $this->middleware('auth:api', ['except' => ['login', 'registration']]);
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $order = Order::findOrFail($id);
        return response()->json([
            'order'=>$order,
        ]);
    }

    public function page(Request $request)
    {
        $page_size = $request->input('page_size');
        $pageNum = $request->input('page_num');
        $sort = $request->input('sort');
        // получаем данные по номеру страницы
        switch ($sort){
            case 'date':
                $orders = Order::orderByDesc('updated_at')->paginate($page_size, ['*'], 'page', $pageNum);
                break;
            case 'price_desc':
                $orders = Order::orderByDesc('total')->paginate($page_size, ['*'], 'page', $pageNum);
                break;
            case 'price_asc':
                $orders = Order::orderBy('total')->paginate($page_size, ['*'], 'page', $pageNum);
                break;
            default:
                $orders = Order::orderByDesc('updated_at')->paginate($page_size, ['*'], 'page', $pageNum);
        }
        return response()->json([
            'orders'=> $orders,
//            'sort'=> $sort
        ]);
    }

    public function update(ApiOrderUpdate $request)
    {
        $data = $request->validated();
        $id = $request->id;
        $order = Order::findOrFail($id);
        $order->update($data);
        return response()->json([
            'success'=> true,
            'id'=> $order->id
        ]);
    }

    public function create(ApiOrderUpdate $request)
    {
        $data = $request->validated();
        $order = Order::create($data);
        return response()->json([
            'success'=> true,
            'id'=> $order->id
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json([
            'success'=> true,
            'id'=> $id
        ]);
    }

}
