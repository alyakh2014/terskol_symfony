var $cont = document.querySelector('.cont');
var $body = document.querySelector('body');
var $elsArr = [].slice.call(document.querySelectorAll('.el'));
var $closeBtnsArr = [].slice.call(document.querySelectorAll('.el__close-btn'));
var mapInitialized = false;

setTimeout(function () {
    $cont.classList.remove('s--inactive');
}, 200);

$elsArr.forEach(function ($el) {
    $el.addEventListener('click', function () {
        if (this.classList.contains('s--active')) return;
        $cont.classList.add('s--el-active');
        $body.classList.add('no-scroll');
        this.classList.add('s--active');
        setTimeout(function () {
            initSlider($el);
        }, 2000);// Инициализация строго после полного разворота окна
        if (this.classList.contains('has-map') && !mapInitialized){
            setTimeout(function () {
                // Создание карты.
                var myMap = new ymaps.Map("yandexMap", {
                    // Координаты центра карты.
                    // Порядок по умолчанию: «широта, долгота».
                    // Чтобы не определять координаты центра карты вручную,
                    // воспользуйтесь инструментом Определение координат.
                    center: [43.256677, 42.513495],
                    // Уровень масштабирования. Допустимые значения:
                    // от 0 (весь мир) до 19.
                    zoom: 18
                });
                mapInitialized = true;
            }, 2000);
        }
    });
});

$closeBtnsArr.forEach(function ($btn) {
    $btn.addEventListener('click', function (e) {
        e.stopPropagation();
        $cont.classList.remove('s--el-active');
        $body.classList.remove('no-scroll');
        document.querySelector('.el.s--active').classList.remove('s--active');
    });
});

function initSlider($context) {
    var $slider = $('.el__photo-slider', $context);
    var $paginSlider = $('.el__photo-thumb', $context);

    // TODO вызвать 'unslick', если слайдеры уже инициализированы, или делать это при закрытии окна

    $slider.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: $paginSlider,
        dots: false,
        infinite: true,
        adaptiveHeight: false,
        fade: true,
        arrows: true,
        nextArrow: '<div class="el__arrow is-right"></div>',
        prevArrow: '<div class="el__arrow is-left"></div>',
        lazyLoad: 'progressive',
    });
    $paginSlider.slick({
        asNavFor: $slider,
        focusOnSelect: true,
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        adaptiveHeight: false,
        arrows: false,
        lazyLoad: 'progressive',
        responsive: [
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 2,
                }
            },
        ]
    });
}

/**
 * провряет, является ли переданная строка форматом json
 * @param str
 * @returns {boolean}
 * @constructor
 */
window.IsJsonString = function (str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
};

$(function () {

    $('.el').each(function () {
        var $context = $(this),
            $tabNav = $('.el__tab-nav', $context),
            $tab = $('.el__order-tab', $context);
        $tabNav.on('click', function () {
            var tabIndex = $(this).attr('data-index');
            $tabNav.removeClass('is-active');
            $tab.removeClass('is-active');
            $('[data-index="' + tabIndex + '"]', $context).addClass('is-active');
        });
    });

    $(".js-phone").inputmask("+7(999)999-99-99");

    $('.mail-form').each(function(i, form){
        var $from = $(this).children('.is-from-to').children('input[name="from"]'),
            $to = $(this).children('.is-from-to').children('input[name="to"]'),
            dateParam = {
                lang: "ru",
                sundayFirst: false,
                years: "80",
                format: "DD.MM.YYYY",
                onClick: function(date){
                    $("#result-1").html("onClick: " + date);
                }
            };
        $from.ionDatePicker(dateParam);
        $to.ionDatePicker(dateParam);

        $(form).on('submit', function (e) {
            e.preventDefault();

            var $form = $(this),
                sendUrl = $form.attr('action'),
                sendData = $form.serialize(),
                $errorMess = $form.children('.error-message'),
                $requiredFields = $form.children('.is-required').children('input'),
                $fio = $form.children('.is-fio'),
                $phone = $form.children('.is-phone'),
                $successBlock = $form.siblings('.success-message');

            $requiredFields.on('focus', function(){
                $requiredFields.closest('.el__input').removeClass('is-error');
                $errorMess.hide();
            });
            $.ajax({
                type: 'post',
                url: sendUrl,
                data: sendData,
                beforeSend: function (data) {
                    $errorMess.hide();
                },
                success: function (data) {
                    if(data === 'success'){
                        $form.hide();
                        $successBlock.show();
                    } else if(data === 'error') {
                        $errorMess.html('Произошла ошибка. Обратитесь по телефону: <a href="tel:79287075517">+ 7 928 707 55 17</a>').show();
                    } else if(IsJsonString(data)) {
                        var result = JSON.parse(data);
                        if(result['no-phone']){
                            $phone.addClass('is-error');
                            $phone.children('input').focus();
                        }
                        if(result['no-fio']){
                            $fio.addClass('is-error');
                            $fio.children('input').focus();
                        }
                        $errorMess.html('Заполните все обязательные поля').show();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $errorMess.html('Произошла ошибка. Обратитесь по телефону: <a href="tel:79287075517">+ 7 928 707 55 17</a>').show();
                }

            });

        });
    });
});