<x-layouts.admin title="Продукты">
<h2>Товары</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary">создать</a>
@foreach($products as $product)
    <div>
        <span>{{ $product->title }}</span> <span>{{ $product->price }}</span>
    </div>
@endforeach
</x-layouts.admin>
