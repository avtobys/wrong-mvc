/* browser.js v1.0.1 | @ajlkn | MIT licensed */
var browser = function () { "use strict"; var t = { name: null, version: null, os: null, osVersion: null, touch: null, mobile: null, _canUse: null, canUse: function (e) { t._canUse || (t._canUse = document.createElement("div")); var n = t._canUse.style, r = e.charAt(0).toUpperCase() + e.slice(1); return e in n || "Moz" + r in n || "Webkit" + r in n || "O" + r in n || "ms" + r in n }, init: function () { for (var e = navigator.userAgent, n = "other", r = 0, i = [["firefox", /Firefox\/([0-9\.]+)/], ["bb", /BlackBerry.+Version\/([0-9\.]+)/], ["bb", /BB[0-9]+.+Version\/([0-9\.]+)/], ["opera", /OPR\/([0-9\.]+)/], ["opera", /Opera\/([0-9\.]+)/], ["edge", /Edge\/([0-9\.]+)/], ["safari", /Version\/([0-9\.]+).+Safari/], ["chrome", /Chrome\/([0-9\.]+)/], ["ie", /MSIE ([0-9]+)/], ["ie", /Trident\/.+rv:([0-9]+)/]], o = 0; o < i.length; o++)if (e.match(i[o][1])) { n = i[o][0], r = parseFloat(RegExp.$1); break } for (t.name = n, t.version = r, n = "other", i = [["ios", /([0-9_]+) like Mac OS X/, function (e) { return e.replace("_", ".").replace("_", "") }], ["ios", /CPU like Mac OS X/, function (e) { return 0 }], ["wp", /Windows Phone ([0-9\.]+)/, null], ["android", /Android ([0-9\.]+)/, null], ["mac", /Macintosh.+Mac OS X ([0-9_]+)/, function (e) { return e.replace("_", ".").replace("_", "") }], ["windows", /Windows NT ([0-9\.]+)/, null], ["bb", /BlackBerry.+Version\/([0-9\.]+)/, null], ["bb", /BB[0-9]+.+Version\/([0-9\.]+)/, null], ["linux", /Linux/, null], ["bsd", /BSD/, null], ["unix", /X11/, null]], o = r = 0; o < i.length; o++)if (e.match(i[o][1])) { n = i[o][0], r = parseFloat(i[o][2] ? i[o][2](RegExp.$1) : RegExp.$1); break } "mac" == n && "ontouchstart" in window && (1024 == screen.width && 1366 == screen.height || 834 == screen.width && 1112 == screen.height || 810 == screen.width && 1080 == screen.height || 768 == screen.width && 1024 == screen.height) && (n = "ios"), t.os = n, t.osVersion = r, t.touch = "wp" == t.os ? 0 < navigator.msMaxTouchPoints : !!("ontouchstart" in window), t.mobile = "wp" == t.os || "android" == t.os || "ios" == t.os || "bb" == t.os } }; return t.init(), t }(); !function (e, n) { "function" == typeof define && define.amd ? define([], n) : "object" == typeof exports ? module.exports = n() : e.browser = n() }(this, function () { return browser });

/* breakpoints.js v1.0 | @ajlkn | MIT licensed */
var breakpoints = function () { "use strict"; function e(e) { t.init(e) } var t = { list: null, media: {}, events: [], init: function (e) { t.list = e, window.addEventListener("resize", t.poll), window.addEventListener("orientationchange", t.poll), window.addEventListener("load", t.poll), window.addEventListener("fullscreenchange", t.poll) }, active: function (e) { var n, a, s, i, r, d, c; if (!(e in t.media)) { if (">=" == e.substr(0, 2) ? (a = "gte", n = e.substr(2)) : "<=" == e.substr(0, 2) ? (a = "lte", n = e.substr(2)) : ">" == e.substr(0, 1) ? (a = "gt", n = e.substr(1)) : "<" == e.substr(0, 1) ? (a = "lt", n = e.substr(1)) : "!" == e.substr(0, 1) ? (a = "not", n = e.substr(1)) : (a = "eq", n = e), n && n in t.list) if (i = t.list[n], Array.isArray(i)) { if (r = parseInt(i[0]), d = parseInt(i[1]), isNaN(r)) { if (isNaN(d)) return; c = i[1].substr(String(d).length) } else c = i[0].substr(String(r).length); if (isNaN(r)) switch (a) { case "gte": s = "screen"; break; case "lte": s = "screen and (max-width: " + d + c + ")"; break; case "gt": s = "screen and (min-width: " + (d + 1) + c + ")"; break; case "lt": s = "screen and (max-width: -1px)"; break; case "not": s = "screen and (min-width: " + (d + 1) + c + ")"; break; default: s = "screen and (max-width: " + d + c + ")" } else if (isNaN(d)) switch (a) { case "gte": s = "screen and (min-width: " + r + c + ")"; break; case "lte": s = "screen"; break; case "gt": s = "screen and (max-width: -1px)"; break; case "lt": s = "screen and (max-width: " + (r - 1) + c + ")"; break; case "not": s = "screen and (max-width: " + (r - 1) + c + ")"; break; default: s = "screen and (min-width: " + r + c + ")" } else switch (a) { case "gte": s = "screen and (min-width: " + r + c + ")"; break; case "lte": s = "screen and (max-width: " + d + c + ")"; break; case "gt": s = "screen and (min-width: " + (d + 1) + c + ")"; break; case "lt": s = "screen and (max-width: " + (r - 1) + c + ")"; break; case "not": s = "screen and (max-width: " + (r - 1) + c + "), screen and (min-width: " + (d + 1) + c + ")"; break; default: s = "screen and (min-width: " + r + c + ") and (max-width: " + d + c + ")" } } else s = "(" == i.charAt(0) ? "screen and " + i : i; t.media[e] = !!s && s } return t.media[e] !== !1 && window.matchMedia(t.media[e]).matches }, on: function (e, n) { t.events.push({ query: e, handler: n, state: !1 }), t.active(e) && n() }, poll: function () { var e, n; for (e = 0; e < t.events.length; e++)n = t.events[e], t.active(n.query) ? n.state || (n.state = !0, n.handler()) : n.state && (n.state = !1) } }; return e._ = t, e.on = function (e, n) { t.on(e, n) }, e.active = function (e) { return t.active(e) }, e }(); !function (e, t) { "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? module.exports = t() : e.breakpoints = t() }(this, function () { return breakpoints });

(function ($) {

    /**
     * Generate an indented list of links from a nav. Meant for use with panel().
     * @return {jQuery} jQuery object.
     */
    $.fn.navList = function () {

        var $this = $(this);
        $a = $this.find('a'),
            b = [];

        $a.each(function () {

            var $this = $(this),
                indent = Math.max(0, $this.parents('li').length - 1),
                href = $this.attr('href'),
                target = $this.attr('target'),
                datatoggle = $this.attr('data-toggle'),
                datatarget = $this.attr('data-target');

            b.push(
                '<a ' +
                'class="link depth-' + indent + '"' +
                ((typeof target !== 'undefined' && target != '') ? ' target="' + target + '"' : '') +
                ((typeof datatoggle !== 'undefined' && datatoggle != '') ? ' data-toggle="' + datatoggle + '"' : '') +
                ((typeof datatarget !== 'undefined' && datatarget != '') ? ' data-target="' + datatarget + '"' : '') +
                ((typeof href !== 'undefined' && href != '') ? ' href="' + href + '"' : '') +
                '>' +
                '<span class="indent-' + indent + '"></span>' +
                $this.text() +
                '</a>'
            );

        });

        return b.join('');

    };

    /**
     * Panel-ify an element.
     * @param {object} userConfig User config.
     * @return {jQuery} jQuery object.
     */
    $.fn.panel = function (userConfig) {

        // No elements?
        if (this.length == 0)
            return $this;

        // Multiple elements?
        if (this.length > 1) {

            for (var i = 0; i < this.length; i++)
                $(this[i]).panel(userConfig);

            return $this;

        }

        // Vars.
        var $this = $(this),
            $body = $('body'),
            $window = $(window),
            id = $this.attr('id'),
            config;

        // Config.
        config = $.extend({

            // Delay.
            delay: 0,

            // Hide panel on link click.
            hideOnClick: false,

            // Hide panel on escape keypress.
            hideOnEscape: false,

            // Hide panel on swipe.
            hideOnSwipe: false,

            // Reset scroll position on hide.
            resetScroll: false,

            // Reset forms on hide.
            resetForms: false,

            // Side of viewport the panel will appear.
            side: null,

            // Target element for "class".
            target: $this,

            // Class to toggle.
            visibleClass: 'visible'

        }, userConfig);

        // Expand "target" if it's not a jQuery object already.
        if (typeof config.target != 'jQuery')
            config.target = $(config.target);

        // Panel.

        // Methods.
        $this._hide = function (event) {

            // Already hidden? Bail.
            if (!config.target.hasClass(config.visibleClass))
                return;

            // If an event was provided, cancel it.
            if (event) {

                event.preventDefault();
                event.stopPropagation();

            }

            // Hide.
            config.target.removeClass(config.visibleClass);

            // Post-hide stuff.
            window.setTimeout(function () {

                // Reset scroll position.
                if (config.resetScroll)
                    $this.scrollTop(0);

                // Reset forms.
                if (config.resetForms)
                    $this.find('form').each(function () {
                        this.reset();
                    });

            }, config.delay);

        };

        // Vendor fixes.
        $this
            .css('-ms-overflow-style', '-ms-autohiding-scrollbar')
            .css('-webkit-overflow-scrolling', 'touch');

        // Hide on click.
        if (config.hideOnClick) {

            $this.find('a')
                .css('-webkit-tap-highlight-color', 'rgba(0,0,0,0)');

            $this
                .on('click', 'a', function (event) {

                    var $a = $(this),
                        href = $a.attr('href'),
                        target = $a.attr('target');

                    if (!href || href == '#' || href == '' || href == '#' + id) return;
                    if (href == '/enter') {
                        $('[href="#navPanel"]').trigger('click');
                        _modal('#sign-in');
                        return false;
                    }
                    if (href == '/comments') {
                        $('[href="#navPanel"]').trigger('click');
                        _modal('#comments');
                        return false;
                    }
                    if (href == '/docs/md__t_o_d_o.html') {
                        $('[href="#navPanel"]').trigger('click');
                        _modal('#todo');
                        return false;
                    }
                    // Cancel original event.
                    event.preventDefault();
                    event.stopPropagation();

                    // Hide panel.
                    $this._hide();

                    // Redirect to href.
                    window.setTimeout(function () {

                        if (target == '_blank')
                            window.open(href);
                        else
                            window.location.href = href;

                    }, config.delay + 10);

                });

        }

        // Event: Touch stuff.
        $this.on('touchstart', function (event) {

            $this.touchPosX = event.originalEvent.touches[0].pageX;
            $this.touchPosY = event.originalEvent.touches[0].pageY;

        })

        $this.on('touchmove', function (event) {

            if ($this.touchPosX === null
                || $this.touchPosY === null)
                return;

            var diffX = $this.touchPosX - event.originalEvent.touches[0].pageX,
                diffY = $this.touchPosY - event.originalEvent.touches[0].pageY,
                th = $this.outerHeight(),
                ts = ($this.get(0).scrollHeight - $this.scrollTop());

            // Hide on swipe?
            if (config.hideOnSwipe) {

                var result = false,
                    boundary = 20,
                    delta = 50;

                switch (config.side) {

                    case 'left':
                        result = (diffY < boundary && diffY > (-1 * boundary)) && (diffX > delta);
                        break;

                    case 'right':
                        result = (diffY < boundary && diffY > (-1 * boundary)) && (diffX < (-1 * delta));
                        break;

                    case 'top':
                        result = (diffX < boundary && diffX > (-1 * boundary)) && (diffY > delta);
                        break;

                    case 'bottom':
                        result = (diffX < boundary && diffX > (-1 * boundary)) && (diffY < (-1 * delta));
                        break;

                    default:
                        break;

                }

                if (result) {

                    $this.touchPosX = null;
                    $this.touchPosY = null;
                    $this._hide();

                    return false;

                }

            }

            // Prevent vertical scrolling past the top or bottom.
            if (($this.scrollTop() < 0 && diffY < 0)
                || (ts > (th - 2) && ts < (th + 2) && diffY > 0)) {

                event.preventDefault();
                event.stopPropagation();

            }

        });

        // Event: Prevent certain events inside the panel from bubbling.
        $this.on('click touchend touchstart touchmove', function (event) {
            event.stopPropagation();
        });

        // Event: Hide panel if a child anchor tag pointing to its ID is clicked.
        $this.on('click', 'a[href="#' + id + '"]', function (event) {

            event.preventDefault();
            event.stopPropagation();

            config.target.removeClass(config.visibleClass);

        });

        // Body.

        // Event: Hide panel on body click/tap.
        $body.on('click touchend', function (event) {
            $this._hide(event);
        });

        // Event: Toggle.
        $body.on('click', 'a[href="#' + id + '"]', function (event) {

            event.preventDefault();
            event.stopPropagation();

            config.target.toggleClass(config.visibleClass);

        });

        // Window.

        // Event: Hide on ESC.
        if (config.hideOnEscape)
            $window.on('keydown', function (event) {

                if (event.keyCode == 27)
                    $this._hide(event);

            });

        return $this;

    };

    /**
     * Apply "placeholder" attribute polyfill to one or more forms.
     * @return {jQuery} jQuery object.
     */
    $.fn.placeholder = function () {

        // Browser natively supports placeholders? Bail.
        if (typeof (document.createElement('input')).placeholder != 'undefined')
            return $(this);

        // No elements?
        if (this.length == 0)
            return $this;

        // Multiple elements?
        if (this.length > 1) {

            for (var i = 0; i < this.length; i++)
                $(this[i]).placeholder();

            return $this;

        }

        // Vars.
        var $this = $(this);

        // Text, TextArea.
        $this.find('input[type=text],textarea')
            .each(function () {

                var i = $(this);

                if (i.val() == ''
                    || i.val() == i.attr('placeholder'))
                    i
                        .addClass('polyfill-placeholder')
                        .val(i.attr('placeholder'));

            })
            .on('blur', function () {

                var i = $(this);

                if (i.attr('name').match(/-polyfill-field$/))
                    return;

                if (i.val() == '')
                    i
                        .addClass('polyfill-placeholder')
                        .val(i.attr('placeholder'));

            })
            .on('focus', function () {

                var i = $(this);

                if (i.attr('name').match(/-polyfill-field$/))
                    return;

                if (i.val() == i.attr('placeholder'))
                    i
                        .removeClass('polyfill-placeholder')
                        .val('');

            });

        // Password.
        $this.find('input[type=password]')
            .each(function () {

                var i = $(this);
                var x = $(
                    $('<div>')
                        .append(i.clone())
                        .remove()
                        .html()
                        .replace(/type="password"/i, 'type="text"')
                        .replace(/type=password/i, 'type=text')
                );

                if (i.attr('id') != '')
                    x.attr('id', i.attr('id') + '-polyfill-field');

                if (i.attr('name') != '')
                    x.attr('name', i.attr('name') + '-polyfill-field');

                x.addClass('polyfill-placeholder')
                    .val(x.attr('placeholder')).insertAfter(i);

                if (i.val() == '')
                    i.hide();
                else
                    x.hide();

                i
                    .on('blur', function (event) {

                        event.preventDefault();

                        var x = i.parent().find('input[name=' + i.attr('name') + '-polyfill-field]');

                        if (i.val() == '') {

                            i.hide();
                            x.show();

                        }

                    });

                x
                    .on('focus', function (event) {

                        event.preventDefault();

                        var i = x.parent().find('input[name=' + x.attr('name').replace('-polyfill-field', '') + ']');

                        x.hide();

                        i
                            .show()
                            .focus();

                    })
                    .on('keypress', function (event) {

                        event.preventDefault();
                        x.val('');

                    });

            });

        // Events.
        $this
            .on('submit', function () {

                $this.find('input[type=text],input[type=password],textarea')
                    .each(function (event) {

                        var i = $(this);

                        if (i.attr('name').match(/-polyfill-field$/))
                            i.attr('name', '');

                        if (i.val() == i.attr('placeholder')) {

                            i.removeClass('polyfill-placeholder');
                            i.val('');

                        }

                    });

            })
            .on('reset', function (event) {

                event.preventDefault();

                $this.find('select')
                    .val($('option:first').val());

                $this.find('input,textarea')
                    .each(function () {

                        var i = $(this),
                            x;

                        i.removeClass('polyfill-placeholder');

                        switch (this.type) {

                            case 'submit':
                            case 'reset':
                                break;

                            case 'password':
                                i.val(i.attr('defaultValue'));

                                x = i.parent().find('input[name=' + i.attr('name') + '-polyfill-field]');

                                if (i.val() == '') {
                                    i.hide();
                                    x.show();
                                }
                                else {
                                    i.show();
                                    x.hide();
                                }

                                break;

                            case 'checkbox':
                            case 'radio':
                                i.attr('checked', i.attr('defaultValue'));
                                break;

                            case 'text':
                            case 'textarea':
                                i.val(i.attr('defaultValue'));

                                if (i.val() == '') {
                                    i.addClass('polyfill-placeholder');
                                    i.val(i.attr('placeholder'));
                                }

                                break;

                            default:
                                i.val(i.attr('defaultValue'));
                                break;

                        }
                    });

            });

        return $this;

    };

    /**
     * Moves elements to/from the first positions of their respective parents.
     * @param {jQuery} $elements Elements (or selector) to move.
     * @param {bool} condition If true, moves elements to the top. Otherwise, moves elements back to their original locations.
     */
    $.prioritize = function ($elements, condition) {

        var key = '__prioritize';

        // Expand $elements if it's not already a jQuery object.
        if (typeof $elements != 'jQuery')
            $elements = $($elements);

        // Step through elements.
        $elements.each(function () {

            var $e = $(this), $p,
                $parent = $e.parent();

            // No parent? Bail.
            if ($parent.length == 0)
                return;

            // Not moved? Move it.
            if (!$e.data(key)) {

                // Condition is false? Bail.
                if (!condition)
                    return;

                // Get placeholder (which will serve as our point of reference for when this element needs to move back).
                $p = $e.prev();

                // Couldn't find anything? Means this element's already at the top, so bail.
                if ($p.length == 0)
                    return;

                // Move element to top of parent.
                $e.prependTo($parent);

                // Mark element as moved.
                $e.data(key, $p);

            }

            // Moved already?
            else {

                // Condition is true? Bail.
                if (condition)
                    return;

                $p = $e.data(key);

                // Move element back to its original location (using our placeholder).
                $e.insertAfter($p);

                // Unmark element as moved.
                $e.removeData(key);

            }

        });

    };

})(jQuery);

/*
    Halcyonic by HTML5 UP
    html5up.net | @ajlkn
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function ($) {

    var $window = $(window),
        $body = $('body');

    // Breakpoints.
    breakpoints({
        xlarge: ['1281px', '1680px'],
        large: ['981px', '1280px'],
        medium: ['737px', '980px'],
        small: [null, '736px']
    });

    // Nav.

    // Title Bar.
    $(
        '<div id="titleBar">' +
        '<a href="#navPanel" class="toggle"></a>' +
        '<span class="title"><a class="text-decoration-none text-white" onclick="return wronginfo();" href="/">' + $('#wrong').html() + '</a></span>' +
        '</div>'
    )
        .appendTo($body);

    // Panel.
    $(
        '<div id="navPanel">' +
        '<nav>' +
        $('#nav').navList() +
        '</nav>' +
        '</div>'
    )
        .appendTo($body)
        .panel({
            delay: 500,
            hideOnClick: true,
            hideOnSwipe: true,
            resetScroll: true,
            resetForms: true,
            side: 'left',
            target: $body,
            visibleClass: 'navPanel-visible'
        });

})(jQuery);

class TextScramble {
    constructor(el) {
        this.el = el
        this.chars = '!<>-_\\/[]{}—=+*^?#________01'
        this.update = this.update.bind(this)
    }
    setText(newText) {
        const oldText = this.el.innerText
        const length = Math.max(oldText.length, newText.length)
        const promise = new Promise((resolve) => this.resolve = resolve)
        this.queue = []
        for (let i = 0; i < length; i++) {
            const from = oldText[i] || ''
            const to = newText[i] || ''
            const start = Math.floor(Math.random() * 40)
            const end = start + Math.floor(Math.random() * 40)
            this.queue.push({
                from,
                to,
                start,
                end
            })
        }
        cancelAnimationFrame(this.frameRequest)
        this.frame = 0
        this.update()
        return promise
    }
    update() {
        let output = ''
        let complete = 0
        for (let i = 0, n = this.queue.length; i < n; i++) {
            let {
                from,
                to,
                start,
                end,
                char
            } = this.queue[i]
            if (this.frame >= end) {
                complete++
                output += to
            } else if (this.frame >= start) {
                if (!char || Math.random() < 0.28) {
                    char = this.randomChar()
                    this.queue[i].char = char
                }
                output += `<span style="color:#444;">${char}</span>`
            } else {
                output += from
            }
        }
        this.el.innerHTML = output
        if (complete === this.queue.length) {
            this.resolve()
        } else {
            this.frameRequest = requestAnimationFrame(this.update)
            this.frame++
        }
    }
    randomChar() {
        return this.chars[Math.floor(Math.random() * this.chars.length)]
    }
}

const phrases = [
    'Wrong MVC',
    'простая система',
    'созданная',
    'для создания',
    'крутых систем!'
]



setTimeout(() => {

    $(function () {
        const el = document.querySelector('#wrong')
        const fx = new TextScramble(el)

        let counter = 0
        const next = () => {
            fx.setText(phrases[counter]).then(() => {
                setTimeout(next, 800)
            })
            counter = (counter + 1) % phrases.length
        }

        next()
    });

    $(function () {
        const el = document.querySelector('#titleBar .title a')
        const fx = new TextScramble(el)

        let counter = 0
        const next = () => {
            fx.setText(phrases[counter]).then(() => {
                setTimeout(next, 800)
            })
            counter = (counter + 1) % phrases.length
        }

        next()
    });
}, 3000);






function wronginfo() {
    if (location.pathname != '/') {
        location.href = '/';
        return;
    }
    $('body,html').animate({
        scrollTop: $('#footer').offset().top - 100
    }, 15000, () => {
        $('body,html').animate({
            scrollTop: $('#header').offset().top - 100
        }, 10000);
    });
    errorModal('<div class="stage"></div>', () => {
        $('#error .modal-dialog').removeClass('modal-sm');
        $('#error .modal-content').removeClass('bg-danger');
        $('#error .modal-content').addClass('rounded-circle');
        $('#error .modal-dialog').addClass('slide-in-elliptic-left-fwd');
        $('#error .modal-content').css({
            background: 'url("/assets/system/img/tux.jpg") 50% 50% / 100% no-repeat',
            boxShadow: '0 0 20px 3px rgba(255, 255, 255, 0.4)',
            opacity: .5,
            border: 0,
            transform: 'scale(.3)',
            transition: 'transform .5s, opacity 1.5s, background 2s ease-in-out'
        });
        setTimeout(() => {
            $('#error .modal-dialog').removeClass('slide-in-elliptic-left-fwd');
        }, 800);
        $('#error .modal-content').css({
            transform: 'scale(1)',
        });
        $('#error .modal-content').animate({
            opacity: 1,
        }, 1400, () => {
            $('#error .modal-content').css({
                transform: 'scale(0) rotateZ(360deg)'
            });
            setTimeout(() => {
                $('#error').css({
                    transition: 'all 2s ease-in-out'
                });
            }, 5000);
            setTimeout(() => {
                $('#error .modal-content').css({
                    transform: 'scale(1.3)',
                    opacity: 0,
                    transition: 'transform 1s, opacity 2s, background 2s ease-in-out'
                });
            }, 1400);

            setTimeout(() => {
                $('#error .modal-content').css({
                    opacity: .9,
                    transform: 'scale(1)',
                    background: 'none',
                    boxShadow: 'none',
                });
                $('.modal-backdrop.show').css({
                    opacity: 0
                });
                setTimeout(() => {
                    $('#error').css({
                        background: 'none',
                        opacity: 1
                    });
                    $('.modal-backdrop.show').animate({
                        opacity: .2
                    }, 10000, () => {
                        $('#error').animate({
                            opacity: 0
                        }, 2000, () => {
                            $('#error .modal-content').css({
                                transform: 'scale(0) rotateZ(900deg)'
                            });
                            setTimeout(() => {
                                $('#error').modal('hide');
                                $('[href="/assets/system/video/wrong-mvc.mp4"]').click();
                            }, 1000);
                        });
                    });
                }, 3000);

            }, 4600);
        });
        $('#error .close').remove();
        setTimeout(() => {
            $('#error .modal-content').height($('#error .modal-content').width());
        }, 200);
        $('#error .modal-body').css({
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            overflow: 'hidden',
        });

        setTimeout(() => {
            const words = ['Сделано\nс\nбольшой', 'Сделано\nс\nогромной', 'Сделано\nс\nчистой'];
            let word = window.initedPrev ? words[Math.floor(Math.random() * 3)] : 'Сделано\nс';
            window.initedPrev = true;
            for (let i = 0; i < 19; i++) {
                $('#error .modal-body .stage').append('<div class="layer" data-text="' + word + '\n❤️‍🔥"></div>');
            }
        }, 6000);
    });
    return false;
}

$(document).on('click', '[href="#donate"]', function (e) {
    e.preventDefault();
    _modal('#donates', null, 'noremove');
});

function typeInputText(input, text, callback) {
    let arr = text.split("")
    if (!arr.length) return callback();
    input.value += arr.shift() + "_";
    setTimeout(function () {
        input.value = input.value.slice(0, -1);
        typeInputText(input, arr.join(""), callback);
    }, 100);
}

function demoStart() {
    $('#sign-up .modal-body').prepend('<div class="border rounded small py-1 px-2 mb-3">Демо панель Wrong MVC доступна после регистрации. Сейчас сделаем Вам аккаунт...</div>');
    setTimeout(loading, 150);
    successToast('Секундочку! Сделаем Вам демо аккаунт!', 5000);
    typeInputText(
        $('#sign-up [name="email"]')[0],
        Math.random().toString(36).slice(2, 10) + '@wrong-mvc.com',
        function () {
            let password = Math.random().toString(36).slice(2, 10);
            typeInputText(
                $('#sign-up [name="password"]')[0],
                password,
                function () {
                    typeInputText(
                        $('#sign-up [name="password2"]')[0],
                        password,
                        function () {
                            $('.toast').trigger('click');
                            successToast('Полетели!', 5000);
                            setTimeout(() => {
                                $('#sign-up form').trigger('submit');
                            }, 1000);
                        }
                    );
                }
            );
        }
    );
}

//= ../../node_modules/aos/dist/aos.js
