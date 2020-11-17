$(document).ready(function () {
    //Сюда помещаем все init-функции
    initFuncName();
});

//Событие после Битриксового ajax
BX.addCustomEvent('onAjaxSuccess', function () {

});

//Функции, которые должны загрузиться при загрузке, называем initFuncName(FuncName - произвольное имя функции)
function initFuncName() {
    return true;
}

//Number_format js
function number_format(number, decimals, dec_point, thousands_sep) {
    var i, j, kw, kd, km;
    if (isNaN(decimals = Math.abs(decimals))) {
        decimals = 2;
    }
    if (dec_point === undefined) {
        dec_point = ",";
    }
    if (thousands_sep === undefined) {
        thousands_sep = ".";
    }
    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
    if ((j = i.length) > 3) {
        j = j % 3;
    } else {
        j = 0;
    }
    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


    return km + kw + kd;
}

//Валидация e-mail
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}

//Возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

//Устанавливает cookie
function setCookie(name, value, options, path) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    if (path) {
        updatedCookie += "; path=" + path;
    }

    document.cookie = updatedCookie;
}

//Удаляет cookie
function deleteCookie(name) {
    setCookie(name, "", "", "/", {
        expires: -1
    });
}

//Склонятор слов  число, слова - "минута", "минуты", "минут"
function declension(num, expressions) {
    let result,
        count;
    count = num % 100;
    if (count >= 5 && count <= 20) {
        result = expressions['2'];
    } else {
        count = count % 10;
        if (count === 1) {
            result = expressions['0'];
        } else if (count >= 2 && count <= 4) {
            result = expressions['1'];
        } else {
            result = expressions['2'];
        }
    }
    return result;
}

//Проверка на историю переходов
function checkRefer() {
    if (document.referrer === "") {
        return false;
    } else {
        window.history.back();
    }
}

//Сравнение двух массивов
function getArrayDiff(a, b) {
    var ret = [],
        merged = [];
    merged = a.concat(b);
    for (var i = 0; i < merged.length; i++) {
        if (merged.indexOf(merged[i]) === merged.lastIndexOf(merged[i])) {
            ret.push(merged[i]);
        }
    }
    return ret;
}

//Замена битриксовых прелоадеров
BX.showWait = function (node, msg) {
    $('.profile-main-loader').show();
    $('.wrapper__loader').show();

};
BX.closeWait = function (node, obMsg) {
    $('.profile-main-loader').hide();
    $('.wrapper__loader').hide();
};