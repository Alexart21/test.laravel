'use strict'

// подтверждение удаления
// события на эту ф-ию вешаются далее строка 16..
function confirmTrash(e) {
    e.preventDefault();
    let form = e.target;
    let result = confirm('Точно удалить ? Это действие необратимо !');
    if (result) {
        form.submit();
    }
}
//
window.onload = () => {
    // формы с этим классом требуют подтверждения удаления
    let delForms = document.querySelectorAll('.del-form');
    if (delForms.length) {
        delForms.forEach((form) => {
            form.addEventListener('submit', (e) => {
                confirmTrash(e)
            });
        });
    }
}

// очиска всех сообщений
function clearMsgs(){
    let errs = document.querySelectorAll('.err-msg'); // это в компоненте формы ошибки валидации
    if(errs.length){
        for(let i = 0; i < errs.length; i++){ // очистим поля ошибок
            errs[i].innerHTML = '';
        }
    }
    //
    let alerts = document.querySelectorAll('.alert');
    if(alerts.length){
        for(let i = 0; i < alerts.length; i++){
            alerts[i].innerHTML = '';
            alerts[i].style.display = 'none';
        }
    }
}

function showMsg(block, msg, timeout = null){
    block.innerHTML = msg;
    block.style.display = 'block';
    if(timeout){
        setTimeout(() => {
            block.style.display = 'none';
        }, timeout)
    }
}

function loaderStart(loader){
    loader.style.display = 'block';
}

function loaderStop(loader){
    loader.style.display = 'none';
}
