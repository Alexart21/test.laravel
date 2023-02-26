<x-layouts.admin title="API test">
    <h3>Create test</h3>
    <div id="loader" class="text-success">загрузка...</div>
    <div id="succes-block" class="alert alert-success" role="alert" style="display: none;"></div>
    <div id="error-block" class="alert alert-danger" role="alert" style="display: none;"></div>
    <x-ui.api.order-form id="" date="" phone="" email="" address="" />
</x-layouts.admin>

<script>
    let alertSuccess = document.querySelector('#succes-block');
    let alertError = document.querySelector('#error-block');
    let loader = document.querySelector('#loader');
    let form = document.getElementById('test-form');
    form.onsubmit = async (e) => {
        e.preventDefault();
        loaderStart(loader);
        clearMsgs();
        let jwt = JSON.parse(localStorage.getItem('jwt'));
        if (!jwt) {
            showMsg(alertError, 'Получите токен!');
            loaderStop(loader);
            return;
        }
        let formData = new FormData(form);
        let response = await fetch("{{ route('api.create') }}", {
            method: 'POST',
            headers: {
                // 'Content-Type': 'application/json;charset=utf-8',
                'Authorization': jwt.token_type + ' ' + jwt.access_token
            },
            body: formData
        });
        if (response.redirected) { // если чето не так с токеном laravel может бросить на страницу логина
            loaderStop(loader);
            showMsg(alertError, 'Обновите токен!');
            return;
        }
        if (!response.ok) {
            console.log(response);
            if (response.status == 422) { // ошибки валидации
                result = await response.json();
                console.log('validate errors');
                let errors = result.errors;
                for (let key in errors) {
                    try {
                        let errBlock = document.getElementById(key + '-err-index');
                        errBlock.innerText = errors[key][0];
                    } catch (e) {
                        continue;
                    }
                }
            }else {
                let msg = `Ошибка ${response.status} ${response.statusText}`;
                showError(alertError, msg);
            }
        }else{ // 200
            let result = await response.json();
            if(result.success){
                showMsg(alertSuccess, `Новый заказ создан. Id заказа - ${result.id}`, 3000);
            }
            console.log(result);
        }
        loaderStop(loader);
    }
</script>
