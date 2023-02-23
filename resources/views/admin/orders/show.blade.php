<x-layouts.admin title="">
    <h2>Заказ № {{ $order->id }}</h2>
    @include('flash::message')
    <table class="admin_tbl">
        <tr>
            <th> Id</th>
            <th> Дата</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Адрес</th>
            @if($order->total)
            <th>Итог</th>
            @endif
        </tr>
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->date }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->address}}</td>
            @if($order->total)
            <td>{{ $order->total }}</td>
            @endif
        </tr>
    </table>
    <br>
    @if(!$order->total)
        <h4>Товаров в заказе нет</h4>
        <form action="{{ route('orders.delete', [$order->id]) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">удалить заказ</button>
        </form>
        <br>
    @else
        <h4>Товары в заказе (условная корзина)</h4>
        <table class="admin_tbl">
            <tr>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th>Действие</th>
            </tr>

        @foreach($products as $product)
            <tr>
                <td>{{$product->title}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->total}}</td>
                <td>
                    <form action="{{ route('orders.destroy', [ $product->id ]) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </table>
    @endif
    <a href="{{ route('orders.add', [ $order->id ]) }}" class="btn btn-primary">Добавить товар</a>
</x-layouts.admin>