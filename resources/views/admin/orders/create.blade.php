<style>
    #map {
        height: 160px;
        width: 376px;
        margin: 0px 12px 18px 12px;
        position: relative;
    }

    .form-group{
        width: 100% !important;
    }
</style>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=2937914e-0b30-4ff3-b518-b51947516d27" type="text/javascript"></script>

<x-layouts.yandex title="Новый заказ">
    <h2>Новый заказ</h2>
<form id="test-form" style="width: 100%" action="{{ route('orders.store') }}" method="post" name="test-form">
    @csrf
    <h2>Введите персональные данные</h2>
    <div class="form-group">
        <label for="date">Дата <small>(вида 01.02.2023)</small></label>
        <input id="date" class="form-control @error('date') is-invalid @enderror" type="text" name="date" value="{{ old('date') }}">
        @error('date')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="phone">Номер телефона:</label>
        <input id="phone" class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" value="{{ old('phone') }}">
        @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="">Email:</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
        @error('email')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="form-group">
        <label for="">Введите адрес и нажмите проверить:</label>
        <input id="suggest" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
        @error('address')<div class="text-danger">{{ $message }}</div>@enderror
        <span class="btn"  id="button">Проверить</span>
    </div>
</form>
    <p id="notice">Адрес не найден</p>
    <div id="map"></div>
    <div id="footer">
        <div id="messageHeader"></div>
        <div id="message"></div>
    </div>
    <button form="test-form" class="btn btn-success">Сохранить</button>
    {{----}}

    {{----}}
    <script>
        window.onload = () => {
            // маски ввода
            $("#date").mask('99.99.9999');
            $("#phone").mask('+7 (999)-999-99-99');

            // yandex map input_validation.js
            ymaps.ready(init);
        }

        function init() {
            // Подключаем поисковые подсказки к полю ввода.
            var suggestView = new ymaps.SuggestView('suggest'),
                map,
                placemark;

            // При клике по кнопке запускаем верификацию введёных данных.
            $('#button').bind('click', function (e) {
                geocode();
            });

            function geocode() {
                // Забираем запрос из поля ввода.
                var request = $('#suggest').val();
                // Геокодируем введённые данные.
                ymaps.geocode(request).then(function (res) {
                    var obj = res.geoObjects.get(0),
                        error, hint;

                    if (obj) {
                        // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                        switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                            case 'exact':
                                break;
                            case 'number':
                            case 'near':
                            case 'range':
                                error = 'Неточный адрес, требуется уточнение';
                                hint = 'Уточните номер дома';
                                break;
                            case 'street':
                                error = 'Неполный адрес, требуется уточнение';
                                hint = 'Уточните номер дома';
                                break;
                            case 'other':
                            default:
                                error = 'Неточный адрес, требуется уточнение';
                                hint = 'Уточните адрес';
                        }
                    } else {
                        error = 'Адрес не найден';
                        hint = 'Уточните адрес';
                    }

                    // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                    if (error) {
                        showError(error);
                        showMessage(hint);
                    } else {
                        showResult(obj);
                    }
                }, function (e) {
                    console.log(e)
                })

            }
            function showResult(obj) {
                // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
                $('#suggest').removeClass('input_error');
                $('#notice').css('display', 'none');

                var mapContainer = $('#map'),
                    bounds = obj.properties.get('boundedBy'),
                    // Рассчитываем видимую область для текущего положения пользователя.
                    mapState = ymaps.util.bounds.getCenterAndZoom(
                        bounds,
                        [mapContainer.width(), mapContainer.height()]
                    ),
                    // Сохраняем полный адрес для сообщения под картой.
                    address = [obj.getCountry(), obj.getAddressLine()].join(', '),
                    // Сохраняем укороченный адрес для подписи метки.
                    shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
                // Убираем контролы с карты.
                mapState.controls = [];
                // Создаём карту.
                createMap(mapState, shortAddress);
                // Выводим сообщение под картой.
                showMessage(address);
            }

            function showError(message) {
                $('#notice').text(message);
                $('#suggest').addClass('input_error');
                $('#notice').css('display', 'block');
                // Удаляем карту.
                if (map) {
                    map.destroy();
                    map = null;
                }
            }

            function createMap(state, caption) {
                // Если карта еще не была создана, то создадим ее и добавим метку с адресом.
                if (!map) {
                    map = new ymaps.Map('map', state);
                    placemark = new ymaps.Placemark(
                        map.getCenter(), {
                            iconCaption: caption,
                            balloonContent: caption
                        }, {
                            preset: 'islands#redDotIconWithCaption'
                        });
                    map.geoObjects.add(placemark);
                    // Если карта есть, то выставляем новый центр карты и меняем данные и позицию метки в соответствии с найденным адресом.
                } else {
                    map.setCenter(state.center, state.zoom);
                    placemark.geometry.setCoordinates(state.center);
                    placemark.properties.set({iconCaption: caption, balloonContent: caption});
                }
            }

            function showMessage(message) {
                $('#messageHeader').text('Данные получены:');
                $('#message').text(message);
            }
        }
    </script>
</x-layouts.yandex>
