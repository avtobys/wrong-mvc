'use strict';

$.cachedScript = function (url, options) {
    options = $.extend(options || {}, {
        dataType: "script",
        cache: true,
        url: url
    });
    return $.ajax(options);
};


$(document).on('click', '[data-toggle="modal"][data-target]:not([disabled])', function () {
    let id = $(this).attr('data-target');
    if ($(id).length) {
        return;
    }
    let callback;
    if ($(this).attr('data-callback') && typeof window[$(this).attr('data-callback')] === 'function') {
        callback = window[$(this).attr('data-callback')];
    }
    let data = $(this).data();
    for (key in data) {
        if (typeof data[key] == 'object') {
            delete data[key];
        }
    }
    window.top._modal(id, callback, $.param(data));
});

function _modal(id, callback, data) {
    if ($(id).length && data == 'noremove') {
        $(id).modal();
        return;
    }
    $('[data-toggle="modal"][data-target="' + id + '"]').attr('disabled', true);
    $('.tooltip').tooltip('hide');
    loading();
    $.ajax(
        {
            type: 'GET',
            url: '/api/modal/' + id.replace('#', '') + (data ? '?' + data : ''),
            dataType: 'html',
            cache: false,
            statusCode: {
                404: errorToast,
                403: errorToast
            }
        }
    )
        .done(function (response) {
            if ($(id).length) {
                $(id).nextAll('.modal-backdrop:first').remove();
                $(id).remove();
            }
            $(window.top.document).find('body').append(response);
            callback && callback();
            $(id).modal();
            $(id).on("hidden.bs.modal", function () {
                if (!$(this).attr('data-noremove')) {
                    $(this).next('.modal-backdrop:first').remove();
                    $(this).remove();
                }
                $('[data-toggle="modal"][data-target="' + id + '"]').removeAttr('disabled');
            });
        })
        .always(function () {
            $('[data-toggle="modal"][data-target="' + id + '"]').removeAttr('disabled');
            loading('hide');
        });
}

function errorModal(body, callback) {
    body = body || 'Неизвестная ошибка';
    window.top._modal('#error', function () {
        callback && callback();
        $("#error .modal-body").html(body);
    });
}

function errorToast(body, timeout) {
    if (typeof body == 'object' && body.status == 403) {
        body = 'Недостаточно прав!';
    }
    if (typeof body == 'object' && body.status == 404) {
        body = 'Не найдено!';
    }
    if (typeof body == 'object' && body.statusText) {
        body = body.statusText;
    }
    body = body || 'Неизвестная ошибка';
    toast('<svg xmlns="http://www.w3.org/2000/svg" fill="#f15e5e" viewBox="0 0 512 512"><path d="M367.2 412.5L99.5 144.8C77.1 176.1 64 214.5 64 256c0 106 86 192 192 192c41.5 0 79.9-13.1 111.2-35.5zm45.3-45.3C434.9 335.9 448 297.5 448 256c0-106-86-192-192-192c-41.5 0-79.9 13.1-111.2 35.5L412.5 367.2zM512 256c0 141.4-114.6 256-256 256S0 397.4 0 256S114.6 0 256 0S512 114.6 512 256z"/></svg><div>' + body + '</div>', timeout);
    $('.editable').removeClass('editable');
}

function dangerToast(body, timeout) {
    body = body || 'Предупреждение';
    toast('<svg xmlns="http://www.w3.org/2000/svg" fill="#f15e5e" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-144c-17.7 0-32-14.3-32-32s14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32z"/></svg><div>' + body + '</div>', timeout);
}

function successToast(body, timeout) {
    body = body || 'Успешно';
    toast('<svg xmlns="http://www.w3.org/2000/svg" fill="#1fa43e" viewBox="0 0 512 512"><path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg><div>' + body + '</div>', timeout);
}

$(window).on("load scroll click focus touchstart mouseenter", function () {
    if (window.interactionTime && window.interactionTime + 500 > Date.now()) {
        return;
    }
    window.interactionTime = Date.now();
    $(document).trigger("interaction");
});

function setCsrfForms() {
    $("form").each(function () {
        if ($(this).find("input[name=CSRF]").length == 0) {
            $(this).prepend("<input type=\"hidden\" name=\"CSRF\" value=\"" + window.CSRF + "\">");
        }
    });
}

$(window).on("interaction", function () {
    window.CSRF && setCsrfForms();

    $('[title]').not('[data-original-title]').tooltip({
        html: true
    });

    $('textarea.with-counter').each((i, el) => {
        if (!$(el).parent().find('.badge-counter').length) {
            $(el).parent().append('<span class="badge badge-counter badge-info font-weight-normal position-absolute text-dark" style="right:0;top:0;z-index:5;">' + $(el).val().length + '</span>');
        }
    });

    $('[data-visible-page]:visible').each((i, el) => {
        if (location.pathname != $(el).attr('data-visible-page')) {
            el.hidden = true;
        }
    });

    $('[data-hide-page]:visible').each((i, el) => {
        if (location.pathname == $(el).attr('data-hide-page')) {
            el.hidden = true;
        }
    });
});

$(document).on('keyup input', 'textarea.with-counter', function () {
    $(this).parent().find('.badge-counter').html($(this).val().length);
});

function _action(data, callback) {
    data.CSRF = window.CSRF;
    for (key in data) {
        if (typeof data[key] == 'object') {
            delete data[key];
        }
    }
    loading();
    $.ajax({
        type: "POST",
        url: "/api/action/" + data.action,
        data: data,
        dataType: (data.response || "json"),
        cache: false,
        statusCode: {
            404: errorToast,
            403: errorToast
        }
    }).done(function (response) {
        if (data.callback) {
            window[data.callback](response);
        }
    }).always(function (response) {
        loading('hide');
        callback && callback(response);
    });
}

$(document).on("click", "[data-action]:not([disabled])", function (e) {
    e.preventDefault();
    let button = $(this);
    button.attr("disabled", 1);
    let data = $(this).data();
    if (data.confirm) {
        window.top._modal("#confirm", function () {
            $("#confirm h5").html(data.header);
            $("#confirm .modal-body").html(data.body);
            $("#confirm [data-ok]").off();
            $("#confirm [data-ok]").one("click", function () {
                _action(data);
            });
            button.removeAttr("disabled");
        });
    } else {
        _action(data, function () {
            button.removeAttr("disabled");
        });
    }
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $(".navbar a").each(function (i, el) {
        if (el.getAttribute('href') == location.pathname && location.pathname != '/') {
            $(el).addClass("active");
        }
    });
});

function lockSubmit(button) {
    if (button.attr('disabled')) {
        return;
    }
    loading();
    button.attr("disabled", true);
    !button.data('text') && button.data({ 'text': button.html() });
    button.html('<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="20px" height="20px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#a4a4a4" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(45 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(90 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(135 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(180 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(225 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(270 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(315 64 64)" /><animateTransform attributeName="transform" type="rotate" values="0 64 64;45 64 64;90 64 64;135 64 64;180 64 64;225 64 64;270 64 64;315 64 64" calcMode="discrete" dur="1040ms" repeatCount="indefinite"></animateTransform></g></svg>');
}

function unlockSubmit(button) {
    setTimeout(() => {
        button.removeAttr("disabled");
        button.html(button.data('text'));
        button.data({ 'text': false });
    }, 500);
    loading('hide');
}

function toast(mess, timeout) {
    timeout = timeout && Number.isInteger(timeout) ? timeout : 5000;
    mess = mess.replace(/(<i [^<]+<\/i>)(.+)/, '$1<div>$2</div>');
    let id = "toast" + Date.now();
    $("#toast").append('<div id="' + id + '" class="toast m-1" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false" data-animation="true">\
    <div class="toast-body">' + mess + '</div>\
</div>');
    $("#" + id).toast("show");
    setTimeout(function () {
        $("#" + id).trigger("click");
    }, timeout);
    $("#" + id).on("hidden.bs.toast", function () {
        $(this).remove();
    });
    $('.tooltip').tooltip('hide');
    try {
        var s = new Audio("/assets/system/sound/toast.mp3");
        s.play();
    } catch (error) {
        console.log(error);
    }

}

$(document).on('mouseenter click touchstart', '.toast', function (e) {
    $(this).animate({
        "left": "-300"
    }, function () {
        $(this).remove();
    });
});

$(document).on('hide.bs.modal', () => {
    $('.editable').removeClass('editable');
    $('.toast').toast('hide');
});

$(document).on('hidden.bs.modal', () => {
    if ($('.modal:visible').length) {
        $('body').addClass('modal-open');
    }
});

$(document).on('click', '.editable-act', function () {
    $('.editable').removeClass('editable');
    $(this).parents('tr').addClass('editable');
});

$(document).on('click', function (e) {
    if ($(e.target).parents('tr').length == 0 && $(e.target).parents('.modal').length == 0 && !$(e.target).is('.modal')) {
        $('.editable').removeClass('editable');
    }
    $('.tooltip').tooltip('hide');
});

function loading(state) {
    $('#loading').remove();
    if (!state || state == 'show') {
        $('body').append('<div id="loading" class="position-fixed h-100 w-100 d-flex justify-content-center align-items-center flex-column" style="top: 0; left: 0;z-index:99999;opacity:0;"><?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="128px" height="128px" viewBox="0 0 128 128" xml:space="preserve"><g><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#a4a4a4" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(45 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(90 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(135 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(180 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(225 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(270 64 64)" /><path d="M38.52 33.37L21.36 16.2A63.6 63.6 0 0 1 59.5.16v24.3a39.5 39.5 0 0 0-20.98 8.92z" fill="#e9e9e9" transform="rotate(315 64 64)" /><animateTransform attributeName="transform" type="rotate" values="0 64 64;45 64 64;90 64 64;135 64 64;180 64 64;225 64 64;270 64 64;315 64 64" calcMode="discrete" dur="1040ms" repeatCount="indefinite"></animateTransform></g></svg></div>');
        $('#loading').animate({ opacity: 1 }, 1000);

    }
}

if (location.search == "?main") {
    history.pushState(null, null, location.pathname);
}

function goToTarget(t) {
    $('body,html').animate({
        scrollTop: $(t).offset().top - 50
    }, "slow");
    return false;
}

function loadLibs(cssPathArray, jsPathArray, funcName) { // подгрузка библиотек
    return new Promise((resolve, reject) => {
        cssPathArray && cssPathArray.forEach(path => {
            !$("link[href='" + path + "']").length && $('head').append('<link rel="stylesheet" href="' + path + '">');
        });
        if (jsPathArray && typeof window[funcName] !== 'function') {
            loading();
            let loads = [];
            function func() {
                f = loads.shift();
                if (typeof f === 'function') {
                    f(func);
                } else {
                    loading('hide');
                    resolve();
                }
            }
            jsPathArray.forEach(path => {
                loads.push((callback) => {
                    $.cachedScript(path).done(() => {
                        callback();
                    });
                });
            });
            func();
        } else {
            resolve();
        }
    });
}

$(document).on('click', '.copy-text', function (e) { // копирование текста по клику на элемент
    e.target.focus();
    const selection = window.getSelection();
    selection.removeAllRanges();
    let range = new Range();
    range.selectNodeContents(this);
    selection.addRange(range);
    document.execCommand('copy', false);
    successToast('Скопировано в буфер');
    setTimeout(() => {
        window.getSelection().removeAllRanges();
    }, 500);
});
