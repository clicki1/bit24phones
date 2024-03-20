@extends('app_phone')

@section('content')

    <div class="title_h1">Единый формат для телефонных номеров</div>
    @if ($errors)
        <div class="alert alert-danger">
            <h3 class="bg-blue">Повторите попытку</h3>
        </div>
    @endif

    <form class="form-phone" action="{{route('store.phones')}}" method="POST">
        <input type="hidden" id="member_id" name="member_id" value="{{$member_id}}"/>
        @method('post')
        @csrf()
        <div class="form__row">
            <div class="form__section">
                <div class="form__section__title">Формат номеров</div>
                <div class="form__section__inputs">
                    <label>
                        <input type="radio" name="format" id="" value="+7"
                               @if($format === '+7' || $format == '')
                                   checked
                            @endif
                        >
                        +7 <span>123 456 78 91</span>
                    </label>
                    <label>
                        <input type="radio" name="format" id="" value="7"
                               @if($format === '7')
                                   checked
                            @endif
                        >
                        7 <span>123 456 78 91<span>
                    </label>
                    <label>
                        <input type="radio" name="format" id="" value="8"
                               @if($format=='8')
                                   checked
                            @endif
                        >
                        8 <span>123 456 78 91</span>
                    </label>
                </div>
            </div>
            <div class="form__section">
                <div class="form__section__title">Автоматическое изменение номера при создании</div>
                <div class="form__section__inputs settings-inputs">
                    <label class="checkbox-ios settings-inputs__checked">
                        <input name="automatic" type="checkbox"
                               @if($automatic=='on')
                                   checked
                            @endif
                        />
                        <div class="checkbox-ios-switch"></div>

                        Сразу при создании
                    </label>
                </div>
                <button class="bg-blue btn_setting" type="submit">Сохранить</button>
            </div>
        </div>
    </form>
    <form action="{{route('phonesupdate.phones')}}" method="POST">
        <input type="hidden" id="member_id" name="member_id" value="{{$member_id}}"/>
        <button type="submit" class="bg-blue btn_general">Выполнить изменение формата номеров сейчас</button>
    </form>

    @if($all_data)
        <div class="result">
        @foreach($all_data as $k => $data)

                <div class="result__section">
                    <div class="result__section__title">Результат изменений в {{$crm_title['title'][$k]}}: {{ count($data['result']) }}</div>
                    <div class="result__section__content">
                        <div class="result__content__item result__content__item_format">
                            <div class="result__content__title">Отформатировано
                                номеров: {{ count($data['result']) }}</div>
                            <div class="result__content__row">
                                @foreach($data['update'] as $result)
                                    <div class="result__item">
                                        <div class="result__content__before-num">{{$result['VALUE_OLD']}}</div>
                                        <div class="result__content__arrow">
                                            <svg width="23" height="14" viewBox="0 0 23 14" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M2.35898 7L20.0513 7" stroke="black" stroke-linecap="round"
                                                      stroke-linejoin="round"/>
                                                <path d="M15.9229 2.91797L20.0511 7.0013L15.9229 11.0846" stroke="black"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div class="result__content__after-num">
                                            {{$result['VALUE']}}
                                            <div class="result__content__check">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M20 6L9 17L4 12" stroke="#9ECD28" stroke-width="2"
                                                          stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="result__content__item">
                            <div class="result__content__title">Не отформатировано
                                номеров: {{ count($data['errors']) }}</div>
                            <div class="result__content__row">
                                @foreach($data['errors'] as $errors)
                                    @foreach($errors['PHONE'] as $error)
                                        <div class="result__content__item__not">
                                            {{$error['VALUE']}}
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18 6L6 18" stroke="#F55B5B" stroke-width="2"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M6 6L18 18" stroke="#F55B5B" stroke-width="2"
                                                      stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <a href="https://{{$site}}/crm/{{$crm_title['url'][$k]}}/details/{{$errors['ID']}}/"
                                               target="_blank" class="edit__phone__link">Редактировать вручную</a>
                                        </div>
                                    @endforeach
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>



        @endforeach
        </div>
    @endif
<script>
let btn_general = document.querySelector('.btn_general');
let btn_setting = document.querySelector('.btn_setting');
btn_general.addEventListener("click", function (e) {
    btn_general.textContent = 'Ожидайте...';
    btn_setting.style.display = "none";
    document.querySelector(".form-phone").style.opacity = "0.5";
    document.querySelector(".result").style.display = "none";
});
</script>

@endsection
