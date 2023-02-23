<x-layouts.admin title="API test">
    <h2>API test</h2>
    <p>
        Введите пароль администратора для получения токена (действующий пароль "password" время жизни 1час)
    </p>
    <form action="" onsubmit="return false">
        <input id="pass" type="password" required>
        <button id="get-btn" class="btn btn-primary">Получить токен</button>
    </form>
    <div id="res"></div>
    <br>
    <form id="orders-form" action="" onsubmit="return false">
        <div class="form-group">
            <label for="order-count">Кол-во заказов на странице</label>
            <input id="orders-count" type="number" min="1" value="1">
            <label for="sort">Сортировка</label>
            <select id="sort" name="sort" >
                <option selected value="date">по дате создания</option>
                <option value="price_desc">сумма по убыванию</option>
                <option value="price_asc">сумма по возрастанию</option>
            </select>
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
            getPage(1);
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

    async function getPage(pageNum,) {
        let resultBlock = document.getElementById('orders-res');
        let linksBlock = document.getElementById('links-block');
        resultBlock.innerHTML = '';
        linksBlock.innerHTML = '';
        resultBlock.innerHTML = 'загрузка...';
        let page_size = document.getElementById('orders-count').value;
        let sort = document.getElementById('sort').value;
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        await fetch('/api/auth/page?pageNum=' + pageNum + '&page_size=' + page_size + '&sort=' + sort, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
        })
            .then(response => response.json())
            .then(result => {
                if (result.orders) {
                    console.log(result);
                    let res = `
                        Страница ${result.orders.current_page}<br>
                        Всего страниц: ${result.orders.last_page}<br>
                        Всего заказов: ${result.orders.total}<br><br>
                    `;
                    resultBlock.innerHTML = res;
                    let orders = result.orders.data;
                    orders.map(item => {
                        let sum = item.total ?? 0;
                        let div = document.createElement('div');
                        div.innerHTML = `Id: <b>${item.id}</b> Тел.: <b>${item.phone}</b> Сумма заказа: <b>${sum}</b>`;
                        resultBlock.append(div);
                    })
                    // реализована пагинация
                    if(result.orders.last_page > 1){
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
                                if(pageNum){
                                    // console.log(pageNum)
                                    getPage(pageNum);
                                }
                            });
                        });
                    }
                }
            })
            .catch((error) => {
                console.log("error", error);
                resultBlock.innerHTML = `<span class="text-danger">${error}</span>`;
            });
    }
</script>
