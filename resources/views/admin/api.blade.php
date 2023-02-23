<x-layouts.admin title="API test">
    <h2>API test</h2>
    <p>
        Введите пароль администратора для получения токена (действует "password")
    </p>
    <form action="" onsubmit="return false">
        <input id="pass" type="password" required>
        <button id="get-btn" class="btn btn-primary">Получить токен</button>
    </form>
    <div id="res"></div>

    <form id="orders-form" action="" onsubmit="return false">
        <div class="form-group">
            <label for="order-count">Кол-во заказов на странице</label>
            <input id="orders-count" type="number" min="1" value="1">
        </div>
        <button id="orders-btn" class="btn btn-primary">Получить список заказов</button>
    </form>

    <div id="orders-res"></div>
    <div id="links-block"></div>


</x-layouts.admin>
<script>
    window.onload = () => {
        let pass = document.getElementById('pass');
        let getBtn = document.getElementById('get-btn');
        let res = document.getElementById('res');
        getBtn.addEventListener('click', () => {
            let password = pass.value;
            let email = "{{ auth()->user()->email }}";
            console.log(password);
            console.log(email);
            res.innerHTML = '';
            getToken(email, password);
        });
        //
        let ordersBtn = document.getElementById('orders-btn');
        let ordersInp = document.getElementById('orders-count');
        let ordersResult = document.getElementById('orders-res');
        ordersBtn.addEventListener('click', () => {
            getOrders();
        });
    }

    //
    async function getToken(email, password) {
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
                    res.innerHTML = 'Токен сохранен';
                } else {
                    console.log(result.error);
                    res.innerHTML = `<span class="text-danger">${result.error}</span>`;
                }
            })
            .catch((error) => {
                console.log("error", error);
            });
    }

    //
    async function getOrders() {
        let page_size = document.getElementById('orders-count').value;
        let resultBlock = document.getElementById('orders-res');
        let linksBlock = document.getElementById('links-block');
        resultBlock.innerHTML = '';
        linksBlock.innerHTML = '';
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        await fetch('/api/auth/orders?page_size=' + page_size, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
        })
            .then(response => response.json())
            .then(result => {
                if (result.orders) {
                    // console.log(result.orders.links);
                    let res = `
                        Страница ${result.orders.current_page}<br>
                        Всего страниц: ${result.orders.last_page}<br>
                        Всего заказов: ${result.orders.total}<br><br>
                    `;
                    resultBlock.innerHTML = res;
                    let orders = result.orders.data;
                    let links = result.orders.links;
                    orders.map(item => {
                        let sum = item.total ?? 0;
                        let div = document.createElement('div');
                        div.innerHTML = `Id: <b>${item.id}</b> Тел.: <b>${item.phone}</b> Сумма заказа: <b>${sum}</b>`;
                        resultBlock.append(div);
                    })
                    if (result.orders.next_page_url) { // реализована пагинация
                        console.log(links)
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
                                if(pageNum){
                                    // console.log(pageNum)
                                    getPage(pageNum);
                                }

                            });
                        });
                    }

                } else {
                    if (result.message) {
                        console.log(result.message);
                        resultBlock.innerHTML = `<span class="text-danger">${result.message}</span>`;
                    }

                }
            })
            .catch((error) => {
                console.log("error", error);
                resultBlock.innerHTML = `<span class="text-danger">${error}</span>`;
            });
    }

    async function getPage(pageNum,) {
        let page_size = document.getElementById('orders-count').value;
        let resultBlock = document.getElementById('orders-res');
        resultBlock.innerHTML = '';
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        await fetch('/api/auth/page?pageNum=' + pageNum + '&page_size=' + page_size, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
        })
            .then(response => response.json())
            .then(result => {
                if (result.orders) {
                    console.log(result.orders.data);
                    let orders = result.orders.data;
                    orders.map(item => {
                        let sum = item.total ?? 0;
                        let div = document.createElement('div');
                        div.innerHTML = `Id: <b>${item.id}</b> Тел.: <b>${item.phone}</b> Сумма заказа: <b>${sum}</b>`;
                        resultBlock.append(div);
                    })
                }
            })
            .catch((error) => {
                console.log("error", error);
                resultBlock.innerHTML = `<span class="text-danger">${error}</span>`;
            });
    }
</script>
