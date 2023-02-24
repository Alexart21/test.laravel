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


// подтверждение выхода
// сделано на html <dialog>
let logoutDialog = document.getElementById('logoutDialog');
if (logoutDialog) {
    let logoutBtn = document.getElementById('logout-btn');
    let confirmBtn = logoutDialog.querySelector('#confirmBtn');
    let logoutForm = document.getElementById('logout-form');
    if (typeof logoutDialog.showModal !== 'function') {
        logoutDialog.hidden = true;
    }
    logoutBtn.addEventListener('click', () => {
        if (typeof logoutDialog.showModal === "function") {
            logoutDialog.showModal();
        } else {
            console.log('Sorry, the <dialog> API is not supported by this browser');
            // logoutForm.submit();
            return;
        }
        //
        logoutForm.onsubmit = (e) => {
            e.preventDefault();
        }
        confirmBtn.addEventListener('click', () => {
            console.log('exit');
            logoutForm.submit();
        });
        logoutDialog.addEventListener('close', () => {
            console.log('отмена');
            logoutForm.onsubmit = (e) => {
                e.preventDefault = true;
            }
        });
    });
}
