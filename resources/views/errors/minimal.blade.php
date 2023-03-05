<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
{{--        <base href="/">--}}
        <title>@yield('title')</title>

        <style>
            .body{
                background: lime !important;
            }
            .err-block {
                position: relative;
            }

            .err-bg, .err-img {
                position: absolute;
            }

            .err-img {
                /*bottom: 0;*/
                display: block;
                z-index: -1;
                width: 574px;
                height: 376px;
            }

            .err-bg {
                z-index: -2;
                background: #7b63b0;
                width: 100vw;
                height: calc(100vh - 376px);
                top: 250px;
            }

            .houme {
                position: absolute;
                z-index: 1;
                left: 2em;
                top: 376px;
                text-align: center;
                font-family: Arial;
            }

            .houme a {
                border: 1px solid #e61b05;
                border-radius: 1em;
                background: #fff;
                text-decoration: none;
                padding: .5em;
            }

            .err-msg{
                font-family: Arial;
                position: absolute;
                width: 100vw;
                top: calc(50vh - 2em);
                text-align: center;
                color: #fff;
                text-shadow: 1px 1px red;
                font-size: 200%;
            }

            footer {
                position: absolute;
                bottom: 0;
                display: flex;
                justify-content: center;
                width: 100%;
                max-height: 100px;
                /*background: #2aabd2;*/
            }

            .footer-block {
                margin: 1em;
            }

            .footer-block a {
                text-decoration: none;
                font-size: 110%;
            }
        </style>
    </head>
    <body class="antialiased">
    <div class="err-block">
        <img class="err-img" src="{{ asset('img/bg.png') }}" alt="tag">
        <div class="err-bg"></div>
        <h2 class="houme">
            <a href="/">на главную</a>
        </h2>
        <div class="err-msg">
            @yield('code') <br> @yield('message')
        </div>
    </div>


    </body>
</html>
