<x-layouts.admin title="{{ $product->title }}">
    @include('flash::message')
    <div>
        Наименование : <b>{{ $product->title }}</b>
    </div>
    <div>
        Цена : <b>{{ $product->price }}</b>
    </div>
    <br>
    <br>
    <a href="{{ route('products.edit', [$product->id]) }}" class="btn btn-success">изменить</a>
    <form action="{{ route('products.destroy', [ $product->id ]) }}" method="post">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">удалить</button>
    </form>
    <a href="{{ route('products.create') }}" class="btn btn-primary">создать</a>
</x-layouts.admin>
