<x-layouts.admin title="Заказы">
<h2>Заказы</h2>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">создать</a>
    @if($total)
        <table class="admin_tbl">
            <tr>
                <th>Id</th>
                <th>Дата</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Адрес</th>
                <th></th>
            </tr>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->address }}</td>
                <td>
                    <a href="{{ route('orders.show', [ $order->id ]) }}" class="btn btn-success">показать</a>
                    <a href="{{ route('orders.edit', [ $order->id ]) }}" class="btn btn-success">редактировать</a>
                </td>
            </tr>
        @endforeach
        </table>
    @else
        <h3>Заказов нет</h3>
    @endif
    <br>
    {{ $orders->links('vendor.pagination.bootstrap-4') }}
</x-layouts.admin>
