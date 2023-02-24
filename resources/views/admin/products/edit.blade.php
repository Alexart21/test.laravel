<x-layouts.admin title="Товар {{ $product->id }}">
<h3> {{ $product->title }} </h3>
    <p>Id: {{ $product->id }}</p>
    <form action="{{ route('products.update', [$product->id]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="">Наименование</label>
            <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ $product->title }}">
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="">Цена вида 100.35</label>
            <input id="price" pattern="^\d+(\.\d\d)?$" class="form-control @error('price') is-invalid @enderror" type="text" name="price" value="{{ $product->price }}">
            @error('price')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-success">Сохранить</button>
    </form>
</x-layouts.admin>
