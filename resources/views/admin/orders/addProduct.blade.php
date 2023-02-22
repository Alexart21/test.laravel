<x-layouts.admin title="Добавление товара">
<h2>Добавление товаров для заказа № {{ $order->id }}</h2>
    @if($order->total)
         Сумма: {{ $order->total }}
    @endif
    <form id="add-form" action="{{ route('orders.save') }}" method="post">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
    <div class="form-group">
        <label for="product">Начните вводить название товара</label>
        <input id="product" list="product_list" class="form-control"  type="text" name="product">
        <datalist id="product_list">
        </datalist>
        @error('date')<div class="text-danger">{{ $message }}</div>@enderror

        <div class="form-group">
            <label for="qty">Количество</label>
            <input id="qty" type="number" name="qty" value="1">
        </div>
    </div>
        <button class="btn btn-success">добавить</button>
    </form>
    @if($order->total)
        <a href="{{ route('orders.show', [$order->id]) }}" class="btn btn-primary">Завершить</a>
    @endif
    <script>
        let inp = document.getElementById('product');
        let datalist = document.getElementById('product_list');
        let addrForm = document.getElementById('add-form');
        let productId = document.getElementById('product_id');
        inp.addEventListener('input', (e) => {
            q = e.target.value;
            if (q.length > 2) { // со скольки букв начинать живой поиск
                // console.log('here');
                fetchData(addrForm);
            }
        });

        async function fetchData(form){
            let formData = new FormData(form);
            let response = await fetch("{{ route('orders.search') }}", {
                method: 'POST',
                body: formData
            });
            if (!response.ok) {
                console.log(response);
            } else {// статус 200
                result = await response.json();
                if (result.success) { // успешно
                    let product = result.product;
                    console.log(product)
                    if (product) {
                        datalist.innerHTML = '';
                        let arr = result.product;
                        arr.map((item) => {
                            let option = document.createElement('option');
                            option.value = item.title;
                            datalist.prepend(option);
                        })
                    }
                } else { // фиг знает че за ошибка
                    console.log(response);
                }
            }
        }
    </script>
</x-layouts.admin>
