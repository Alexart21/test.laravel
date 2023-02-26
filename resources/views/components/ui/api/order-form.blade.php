@props([
    'id',
    'date',
    'phone',
    'email',
    'address'
])
<form id="test-form" action="" method="post" name="test-form">
    @method('POST')
    <input type="hidden" name="id" value="{{ $id }}">
    <div class="form-group">
        <label for="date">Дата вида 01.02.2023</label>
        <input id="date" class="form-control" type="text" name="date" value="{{ $date }}">
        <div id="date-err-index" class="err-msg text-danger"></div>
    </div>

    <div class="form-group">
        <label for="phone">Номер телефона:</label>
        <input id="phone" class="form-control" type="text" name="phone" value="{{ $phone }}">
        <div id="phone-err-index" class="err-msg text-danger"></div>
    </div>

    <div class="form-group">
        <label for="">Email:</label>
        <input type="email" class="form-control" name="email" value="{{ $email }}">
        <div id="email-err-index" class="err-msg text-danger"></div>
    </div>

    <div class="form-group">
        <label for="">Адрес:</label>
        <input type="text" class="form-control" name="address" value="{{ $address }}">
        <div id="address-err-index" class="err-msg text-danger"></div>
    </div>
    <button class="btn btn-success">Отправить</button>
</form>
