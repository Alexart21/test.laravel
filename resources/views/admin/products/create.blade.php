<x-layouts.admin title="Новый товар">
    <h2>Новый товар</h2>
<form action="{{ route('products.store') }}" method="post">
    @csrf
    <div class="form-group">
        <label for="">Наименование</label>
        <input class="form-control @error('title') is-invalid @enderror" type="text" name="title" value="{{ old('title') }}">
        @error('title')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="">Цена вида 100.35</label>
        <input id="price" pattern="^\d+(\.\d\d)?$" class="form-control @error('price') is-invalid @enderror" type="text" name="price" value="{{ old('price') }}">
        @error('price')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <button class="btn btn-success">Отправить</button>
</form>
    <script>
        window.onload = () => {
            // console.log('here');
            // $("#price").mask('99?999.?99');
        }
    </script>
</x-layouts.admin>
