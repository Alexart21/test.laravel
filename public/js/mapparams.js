ymaps.ready(init);

 function init() {
    let myMap = new ymaps.Map("map", {
            // Координаты центра карты.
            // Порядок по умолчанию: «долгота, широта».
            //деревня
            center: [55.821740, 47.008830],
            // город (Николаева 42)
            // center: [56.129392, 47.282766],
            // Уровень масштабирования. Допустимые значения:
            // от 0 (весь мир) до 19.
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
                coordinates: [55.821740, 47.008830]
                // город (Николаева 42)
                // coordinates: [56.129392, 47.282766],
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: 'Мой дом',
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
