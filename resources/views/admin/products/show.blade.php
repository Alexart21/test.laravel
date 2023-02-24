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
    <a href="{{ route('products.edit', [$product->id]) }}">@svg('svg/pencil.svg', 'green-icon')</a>
    <form action="{{ route('products.destroy', [ $product->id ]) }}" method="post" class="del-form">
        @csrf
        @method('DELETE')
        <button class="del-bt">@svg('svg/trash-can.svg', 'red-icon')</button>
    </form>
{{--    <a href="{{ route('products.create') }}" class="btn btn-primary">создать</a>--}}
</x-layouts.admin>
