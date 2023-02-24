<x-layouts.admin title="Товары">
    <h2>Товары</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">создать</a>
    <br>
    <br>
    @if($total)
        <table class="admin_tbl">
            <tr>
                <th>Id</th>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Действие</th>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="{{ route('products.edit', [$product->id]) }}">@svg('svg/pencil.svg', 'green-icon')</a>
                        <form action="{{ route('products.destroy', [ $product->id ]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="del-bt">@svg('svg/trash-can.svg', 'red-icon')</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        <br>
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    @else
        <h3>Товаров нет</h3>
    @endif

</x-layouts.admin>
