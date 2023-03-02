@php
    use Jenssegers\Date\Date;
@endphp
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
            <th>Дата</th>
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
            @php
                $date = Date::parse($order->created_at);
            if($date->isYesterday()){
                $date = 'вчера в ' . $date->format('H:i');
            }elseif ($date->isToday()){
                $date = 'сегодня в ' . $date->format('H:i');
            }else{
                $date = $date->format('j F Y H:i');
            }
            @endphp
            <td>{{ $date }}</td>
            @if($order->total > 0)
            <td>{{ $order->total }}</td>
            @endif
        </tr>
    </table>
    <br>
    @if($order->total <= 0)
        <h4>Товаров в заказе нет</h4>
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

        @foreach($order->orderProducts as $product)
            <tr>
                <td>{{$product->title}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->total}}</td>
                <td>
                    <form action="{{ route('orders.destroy', [ $product->id ]) }}" method="post" class="del-form">
                        @csrf
                        @method('DELETE')
                        <button class="del-bt">@svg('svg/trash-can.svg', 'red-icon')</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </table>
    @endif
    <br>
    <div class="d-flex">
        <div>
            <a href="{{ route('orders.add', [ $order->id ]) }}" class="btn btn-primary">Добавить товар</a>
        </div>
        &nbsp;&nbsp;
        <form action="{{ route('orders.delete', [$order->id]) }}" method="post" class="del-form">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">удалить заказ</button>
        </form>
    </div>


</x-layouts.admin>
