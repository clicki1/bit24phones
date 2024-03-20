<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ЕДИНЫЙ ФОРМАТ НОМЕРОВ</title>

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('laravel/assets/css/styles.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        :root {
            font-family: 'Montserrat', Inter, system-ui, Avenir, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            font-weight: 400;
            color: var(--color-text-black);

            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;

            --color-text-gray: #717171;
            --color-btn-blue: #20b7e7;
            --color-green: #9ecd28;
            --color-grey-light: #f7f7f7;
            --color-grey-lines: #e5e5e5;
            --color-text-black: #000;
            --color-main-bg: #fff;
        }

        body {
            margin: 0;
            margin-bottom: 50px;
            background: var(--color-grey-light);
        }

        h1 {
            font-size: 3.2em;
            line-height: 1.1;
        }

        a {
            font-weight: 500;
            text-decoration: inherit;
            color: var(--color);
        }

        a:hover {
            color: var(--color-btn-blue);
        }

        .title_h1 {
            font-size: 34px;
            font-weight: 500;
            line-height: 41px;
        }

        .title_h2 {
            font-size: 26px;
            font-weight: 500;
        }

        .title_h3 {
            font-size: 22px;
        }

        button {
            border-radius: 5px;
            border: 1px solid transparent;
            padding: 0 20px;
            cursor: pointer;
            transition: border-color 0.25s;
            height: 52px;
            display: flex;
            align-items: center;
            user-select: none;
            background: none;
        }

        .bg-blue {
            background: var(--color-btn-blue);
            color: #fff;
            font-weight: 600;
        }

        .bg-blue:hover {
            background: #1B9EC8;
            color: #fff;
        }


        :focus,
        :focus-visible {
            outline: none;
        }

        .container {
            padding: 0 50px;
        }


        .navbar {
            color: #000;
            height: 82px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--color-grey-lines);
            background-color: var(--color-main-bg);
        }

        .navbar > div {
            display: flex;
            gap: 30px;
        }

        .navbar .active,
        .navbar a:hover {
            color: var(--color-btn-blue);
        }


        .checkbox-ios {
            display: inline-block;
            height: 28px;
            line-height: 28px;
            margin-right: 10px;
            position: relative;
            vertical-align: middle;
            font-size: 14px;
            user-select: none;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        .checkbox-ios .checkbox-ios-switch {
            position: relative;
            display: inline-block;
            box-sizing: border-box;
            width: 37px;
            height: 20px;
            border: 1px solid #9F9F9F;
            border-radius: 25%/50%;
            vertical-align: top;
            background: #fff;
            transition: .2s;
        }
        .checkbox-ios .checkbox-ios-switch:before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: white;
            transition: .15s;
            background: #9F9F9F;
        }
        .checkbox-ios input[type=checkbox] {
            display: block;
            width: 0;
            height: 0;
            position: absolute;
            z-index: -1;
            opacity: 0;
        }
        .checkbox-ios input[type=checkbox]:not(:disabled):active + .checkbox-ios-switch:before {
            /* box-shadow: inset 0 0 2px rgba(0, 0, 0, .3); */
        }

        /* Hover */
        .checkbox-ios input[type="checkbox"]:not(:disabled) + .checkbox-ios-switch {
            cursor: pointer;
            border-color: #9F9F9F;
        }

        /* Disabled */
        .checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch {
            filter: grayscale(70%);
            border-color: #9F9F9F;
        }
        .checkbox-ios input[type=checkbox]:disabled + .checkbox-ios-switch:before {
            background: #eee;
        }

        /* Focus */
        .checkbox-ios.focused .checkbox-ios-switch:before {
            box-shadow: inset 0px 0px 4px #ff5623;
        }
        /*
        .checkbox-ios input[type=checkbox]:checked  {
            background: #20B7E7;
            border-color: #20B7E7;
        } */
        .checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch {
            border-color: #20B7E7;
        }
        .checkbox-ios input[type=checkbox]:checked + .checkbox-ios-switch:before {
            transform:translateX(17px);
            background: #20B7E7;
            border-color: #20B7E7;
        }

        form {
            margin-top: 50px;
        }

        .form__section {
            border: 1px solid var(--color-grey-lines);
            border-radius: 10px;
            padding: 50px;
            width: 50%;
        }

        .title_h1 {
            margin-top: 50px;
        }

        .form__section__inputs {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .form__section__title {
            width: 365px;
            font-size: 24px;
            font-weight: 500;
        }

        .form__section__inputs label span {
            color: #A8A8A8;
        }

        .form__section__btns {
            display: flex;
            gap: 32px;
        }

        .result__section {
            margin-top: 100px;
        }

        .result__section__title {
            font-weight: 500;
            font-size: 22px;
        }

        .result__content__item {
            border: 1px solid var(--color-grey-lines);
            border-radius: 10px;
            margin-top: 17px;
            padding: 30px;
            width: 50%;
        }

        .result__content__title {
            font-weight: 400;
            font-size: 22px;
        }

        .result__content__row {
            margin-top: 34px;
            display: flex;
            flex-direction: column;
            gap: 25px;
            overflow-y: auto;
            height: 170px;
        }

        .result__content__row::-webkit-scrollbar {
            width: 5px;
        }

        .result__content__row::-webkit-scrollbar-track {
            background-color: #eee;
            border-radius: 100px;
        }

        .result__content__row::-webkit-scrollbar-thumb {
            box-shadow: inset 0 0 6px #ccc;
            border-radius: 100px;
        }

        .result__item {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            line-height: 18px;
        }

        .result__content__after-num {
            display: flex;
            gap: 10px;
        }

        .result__section__content {
            display: flex;
            gap: 50px;
        }

        .result__content__arrow {
            display: flex;
            justify-content: center;
        }

        .result__content__item__not {
            display: flex;
        }

        .result__content__item__not span {
            width: 140px;
        }

        .form__section__inputs input {
            accent-color: #1a89c8;
            margin-right: 20px;
            font-size: 16px;
        }

        .form__row {
            display: flex;
            gap: 66px;
        }

        .bg-blue {
            margin-top: 40px;
        }

        .edit__phone__link {
            font-size: 16px;
            text-decoration: underline;
            color: #1a89c8;
            margin-left: 30px;
        }

        .result__content__check {
            position: relative;
            top: -4px;
        }
    </style>
    <script>
        //ползунок
        $(window).keyup(function(e){
            var target = $('.checkbox-ios input:focus');
            if (e.keyCode == 9 && $(target).length){
                $(target).parent().addClass('focused');
            }
            console.log('keyup')
        });

        $('.checkbox-ios input').focusout(function(){
            $(this).parent().removeClass('focused');
            console.log('focusout')

        });
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar">
        <div class="container">
            <a class="active" href="/">Единый формат для телефонных номеров</a>
            <a class="" href="/licenses">Лицензии</a>
            <a href="https://auth2.bitrix24.net/oauth/select/?preset=im&amp;IM_DIALOG=networkLines7575d3350d49d47db7bea9beac4b7994" target="_blank">Помощь</a>
            <a class="" href="/offer">Предложение по доработкам</a>
            <a class="" href="/instructions">Instructions</a>
            <a class="" href="/about-us">О нас</a>
        </div>
    </nav>
    <div class="container">
        @yield('content')
    </div>

</div>
</body>
</html>
