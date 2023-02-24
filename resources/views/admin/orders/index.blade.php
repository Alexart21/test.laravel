<x-layouts.admin title="Заказы">
<h2>Заказы</h2>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">создать</a>
    <br>
    <br>
    @if($total)
        <table class="admin_tbl">
            <tr>
                <th>Id</th>
                <th>Дата</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Адрес</th>
                <th>Сумма</th>
                <th>Действия</th>
            </tr>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ $order->address }}</td>
                <td>{{ $order->total }}</td>
                <td>
                    <a href="{{ route('orders.show', [ $order->id ]) }}">@svg('svg/eye.svg', 'blue-icon')</a>
                    <a href="{{ route('orders.edit', [ $order->id ]) }}">@svg('svg/pencil.svg', 'green-icon')</a>
                    <form action="{{ route('orders.delete', [$order->id]) }}" method="post" class="del-form">
                        @csrf
                        @method('DELETE')
                        <button class="del-bt">@svg('svg/trash-can.svg', 'red-icon')</button>
                    </form>

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
