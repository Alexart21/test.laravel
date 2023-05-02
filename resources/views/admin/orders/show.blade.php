@php
    use Jenssegers\Date\Date;
@endphp
<x-layouts.admin title="">
    <h2>Заказ № {{ $order->id }}</h2>
    @include('flash::message')
    <table class="admin_tbl">
        <tr>
            <th> Id</th>
            <th> Дата</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Адрес</th>
            <th>Дата</th>
            @if($order->total)
            <th>Итог</th>
            @endif
        </tr>
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->date }}</td>
            <td>{{ $order->phone }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->address}}</td>
            @php
                $date = Date::parse($order->created_at);
            if($date->isYesterday()){
                $date = 'вчера в ' . $date->format('H:i');
            }elseif ($date->isToday()){
                $date = 'сегодня в ' . $date->format('H:i');
            }else{
                $date = $date->format('j F Y H:i');
            }
            @endphp
            <td>{{ $date }}</td>
            @if($order->total > 0)
            <td>{{ $order->total }}</td>
            @endif
        </tr>
    </table>
    <br>
    @if($order->total <= 0)
        <h4>Товаров в заказе нет</h4>
    @else
        <h4>Товары в заказе (условная корзина)</h4>
        <table class="admin_tbl">
            <tr>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th>Действие</th>
            </tr>

        @foreach($order->orderProducts as $product)
            <tr>
                <td>{{$product->title}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->total}}</td>
                <td>
                    <form action="{{ route('orders.destroy', [ $product->id ]) }}" method="post" class="del-form">
                        @csrf
                        @method('DELETE')
                        <button class="del-bt">@svg('svg/trash-can.svg', 'red-icon')</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </table>
    @endif
    <br>
    @if($order->latitude)
        <style>
            #map {
                width: 100%;
                height: 400px;
            }
        </style>
        <div id="map"></div>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey={{ config('yandex.api_key') }}" type="text/javascript"></script>
        <script>
            ymaps.ready(init);

            function init() {
                let myMap = new ymaps.Map("map", {
                        // Координаты центра карты.
                        // Порядок по умолчанию: «долгота, широта».
                        center: [{{ $order->latitude }}, {{ $order->longitude }}],
                        zoom: 17
                    }, {
                        // searchControlProvider: 'yandex#search'
                    }),

                    // Создаем геообъект с типом геометрии "Точка".
                    myGeoObject = new ymaps.GeoObject({
                        // Описание геометрии.
                        geometry: {
                            type: "Point",
                            //деревня
                            coordinates: [{{ $order->latitude }}, {{ $order->longitude }}]
                        },
                        // Свойства.
                        properties: {
                            // Контент метки.
                            iconContent: '',
                            // hintContent: 'Ну давай уже тащи'
                        },
                    }, {
                        // Опции.
                        // Иконка метки будет растягиваться под размер ее содержимого.
                        preset: 'islands#blackStretchyIcon',
                        // Метку можно перемещать.
                        // draggable: true,
                        strokeColor: "#00ff00"
                    });
                myMap.geoObjects
                    .add(myGeoObject)
            }

        </script>
    @endif
    <div class="d-flex">
        <div>
            <a href="{{ route('orders.add', [ $order->id ]) }}" class="btn btn-primary">Добавить товар</a>
        </div>
        &nbsp;&nbsp;
        <form action="{{ route('orders.delete', [$order->id]) }}" method="post" class="del-form">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">удалить заказ</button>
        </form>
    </div>


</x-layouts.admin>
