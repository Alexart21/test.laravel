<x-layouts.admin title="API test">
    <h2>API test</h2>
    <p>
        Введите пароль администратора для получения токена (действующий пароль "password" время жизни 1час)
    </p>
    <form action="" onsubmit="return false">
        <input id="pass" type="password" required>
        <button id="get-btn" class="btn btn-primary">Получить токен</button>
    </form>
    <div id="loader" class="text-success">загрузка...</div>
    <div id="succes-block" class="alert alert-success" role="alert" style="display: none;"></div>
    <div id="error-block" class="alert alert-danger" role="alert" style="display: none;"></div>
    <br>
    <div class="api-main">
        <div class="left">
            <form id="orders-form" action="" onsubmit="return false">
                <div class="form-group">
                    <label for="order-count">Кол-во заказов на странице</label>
                    <input id="orders-count" type="number" min="1" value="20">
                    <br>
                    <br>
                    <label for="sort">Сортировка</label>
                    <select id="sort" name="sort">
                        <option selected value="date">по дате создания</option>
                        <option value="price_desc">сумма по убыванию</option>
                        <option value="price_asc">сумма по возрастанию</option>
                    </select>
                    <br>
                    <br>
                </div>
                <button id="orders-btn" class="btn btn-primary">Получить список заказов</button>
            </form>
            <div id="orders-res"></div>
            <div id="links-block"></div>
        </div>
        <div class="right">
            <div id="right-res"></div>
        </div>
    </div>
    <br>
    <h5><a href="{{ route('admin.api.create') }}">create test</a></h5>
</x-layouts.admin>
<script>
    let alertSuccess = document.querySelector('#succes-block');
    let alertError = document.querySelector('#error-block');
    let loader = document.querySelector('#loader');
    window.onload = () => {
        let pass = document.getElementById('pass');
        let getBtn = document.getElementById('get-btn');
        let res = document.getElementById('res');
        getBtn.addEventListener('click', () => {
            let password = pass.value;
            let email = "{{ auth()->user()->email }}";
            saveToken(email, password);
        });
        //
        let ordersBtn = document.getElementById('orders-btn');
        ordersBtn.addEventListener('click', () => {
            getPage(1);
        });
    }
    //
    async function saveToken(email, password) {
        clearMsgs();
        loaderStart(loader);
        let formData = new FormData();
        formData.append("email", email);
        formData.append("password", password);
        await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                // 'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                if (result.access_token) {
                    console.log(result);
                    localStorage.setItem('jwt', JSON.stringify(result));
                    showMsg(alertSuccess, 'Токен сохранен!', 3000);
                } else {
                    console.log(result.error);
                    showMsg(alertError, `${result.error}`);
                }
            })
            .catch((error) => {
                console.log("error", error);
            });
        loaderStop(loader);
    }

    async function getPage(pageNum,) {
        let resultBlock = document.getElementById('orders-res');
        let linksBlock = document.getElementById('links-block');
        loaderStart(loader);
        clearMsgs();
        linksBlock.innerHTML = '';
        let page_size = document.getElementById('orders-count').value;
        let sort = document.getElementById('sort').value;
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        if (!jwt) {
            showMsg(alertError, 'Получите токен!');
            loaderStop(loader);
            return;
        }
        let url = '/api/auth/page';
        let data = {
            page_size: page_size,
            page_num: pageNum,
            sort: sort
        }
        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
            body: JSON.stringify(data)
        });
        if (response.redirected) { // если чето не так с токеном laravel может бросить на страницу логина
            loaderStop(loader);
            showMsg(alertError, 'Обновите токен!');
            return;
        }
        if (!response.ok) {
            console.log(response);
            loaderStop(loader);
            showMsg(alertError, `Ошибка ${response.status} ${response.statusText}`);
        } else { // 200
            let result = await response.json();
            if (result.orders) {
                console.log(result);
                let res = `
                        Страница ${result.orders.current_page} из ${result.orders.last_page}<br>
                        Всего заказов: ${result.orders.total}<br><br>
                    `;
                resultBlock.innerHTML = res;
                let orders = result.orders.data;
                orders.map(item => {
                    let sum = item.total ?? 0;
                    let div = document.createElement('div');
                    div.innerHTML = `Id: <b>${item.id}</b> Тел.: <b>${item.phone}</b> Сумма заказа: <b>${sum}</b>
                                    <button data-id="${item.id}" class="show-bt">показать</button>
                                    <a href="api-update?id=${item.id}" class="text-success">изменить</a>
                                    <button data-id="${item.id}" class="del-bt text-danger">удалить</button>
                                    `;
                    resultBlock.append(div);
                })
                addEvents() // вешаем обработчик на событие клика
                // реализована пагинация
                if (result.orders.last_page > 1) {
                    let links = result.orders.links;
                    // console.log(links)
                    let str = '';
                    links.map(item => {
                        str += `<a class="pg-link" href="${item.url}">${item.label}</a>`;
                    })
                    linksBlock.innerHTML = str;
                    let allLiks = document.querySelectorAll('.pg-link');
                    allLiks.forEach((link) => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            let pageNum = e.target.href.split('?page=')[1]; // номер страницы получили из ссылкиs
                            if (pageNum) {
                                // console.log(pageNum)
                                getPage(pageNum);
                            }
                        });
                    });
                }
            } else {
                console.log(result);
                if (result.message) {
                    showMsg(alertError, result.message);
                }
            }
        }
        loaderStop(loader);
    }
    // вешаем обработчик
    function addEvents(){
        let swowBtns = document.querySelectorAll('.show-bt');
        if (swowBtns.length) {
            swowBtns.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    showOrder(e.target.dataset.id)
                });
            });
        }

        let delBtns = document.querySelectorAll('.del-bt');
        if (delBtns.length) {
            delBtns.forEach((btn) => {
                btn.addEventListener('click', (e) => {
                    delOrder(e.target.dataset.id)
                });
            });
        }
    }

    // показать конкретный заказ
    async function showOrder(id){
        let resBlock = document.getElementById('right-res');
        resBlock.innerHTML = '';
        loaderStart(loader);
        clearMsgs();
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        if (!jwt) {
            showMsg(alertError, 'Получите токен!');
            loaderStop(loader);
            return;
        }
        let url = '/api/auth/show';
        let data = {
            id: id
        }
        let response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
            body: JSON.stringify(data)
        });
        if (response.redirected) {
            loaderStop(loader);
            showMsg(alertError, 'Обновите токен!');
            return;
        }
        if (!response.ok) {
            console.log(response);
            let msg = `Ошибка ${response.status} ${response.statusText}`;
            showMsg(alertError, msg);
        } else { // 200
            let result = await response.json();
            if(result.order){
                console.log(result.order);
                let order = result.order;
                let totalSum = order.total ? `${order.total} ₽` : 0;
                let res = `
                            <p>
                            Id: <b>${order.id}</b>
                            </p>
                            <p>
                            Дата: <b>${order.date}</b>
                            </p>
                            <p>
                            Тел.: <b>${order.phone}</b>
                            </p>
                            <p>
                            Email: <a href="mailto:${order.email}"><b>${order.email}</b></a>
                            </p>
                            <p>
                            Адрес: <b>${order.address}</b>
                            </p>
                            <p>
                            Сумма заказа: <b>${totalSum}</b>
                            </p>
                           `;
                resBlock.innerHTML = res;
            }else{
                console.log(result);
                if (result.message) {
                    showMsg(alertError, result.message);
                }
            }
        }
        loaderStop(loader);
    }
    // удалить заказ
    async function delOrder(id){
        let ok = confirm('Точно удалить ?');
        if (!ok) {
            return;
        }
        loaderStart(loader);
        clearMsgs();
        let resBlock = document.getElementById('right-res');
        resBlock.innerHTML = '';
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        if (!jwt) {
            showMsg(alertError, 'Получите токен!');
            loaderStop(loader);
            return;
        }
        let url = '/api/auth/destroy';
        let data = {
            id: id
        }
        let response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
            body: JSON.stringify(data)
        });
        if (response.redirected) {
            loaderStop(loader);
            showMsg(alertError, 'Обновите токен!');
            return;
        }
        if (!response.ok) {
            console.log(response);
            let msg = `Ошибка ${response.status} ${response.statusText}`;
            showMsg(alertError, msg);
        } else { // 200
            let result = await response.json();
            console.log(result);
            if(result.success){
                showMsg(alertSuccess, `Заказ Id: ${result.id} удален`);
                // return
                getPage(1); // обновили список заказов
            }else{
                console.log(result);
                if (result.message) {
                    showMsg(alertError, result.message);
                }
            }
        }
        loaderStop(loader);
    }
</script>
