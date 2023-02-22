<x-layouts.admin title="Товары">
    <h2>Товары</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">создать</a>
    @if($total)
        <table class="admin_tbl">
            <tr>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Действие</th>
            </tr>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        <a href="{{ route('products.edit', [$product->id]) }}" class="btn btn-success">изменить</a>
                        <form action="{{ route('products.destroy', [ $product->id ]) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">удалить</button>
                        </form>
                    </td>
                </tr>
                <br>
            @endforeach
        </table>
        <br>
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    @else
        <h3>Товаров нет</h3>
    @endif

</x-layouts.admin>
