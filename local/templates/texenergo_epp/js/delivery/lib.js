$(function () {
    $(document).on('keypress', '[data-digits-only]', function (event) {
        var char = getChar(event);
        if (char === null) {
            return true;
        }

        if (!(/\d|,/.test(char)) || ($(this).val().indexOf(",") >= 0 && char == ',')) {
            return false;
        }
    });

    $('[data-phone-mask]').mask('8(999)999-99-99');

    $('[data-fancybox]').fancybox({
        helpers: {
            overlay: {
                locked: false
            }
        }
    });

    $(document).on('click change', '[data-submit-target]', function (e) {
        var $this = $(this);
        if ($this.data('not-click') == true && e.type == 'click') {
            return;
        }
        setTimeout(function () {
            $("." + $this.data('submit-target')).trigger('submit');
        }, 50);
    });

    $(document).on('submit', '[data-ajax-form]', function (e) {
        e.preventDefault();

        var $form = $(this),
            data = (window.FormData === undefined ? $form.serialize() : new FormData($form[0])),
            $fieldsBlock = $('[data-fields]', $form),
            $messageBlock = $('[data-form-message]', $form);

        $form.trigger('forms.submit.before', [data]);
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    if ($form.data('slide-enable') == true) {
                        $fieldsBlock.slideUp();
                    }
                    $('.input-text').val('');

                    $form.trigger('forms.submit.success', [data]);

                    if ($form.data('target')) {
                        var currentModal = $form.parents('.modal[role="dialog"]');
                        if (currentModal.length) {
                            currentModal.modal('hide');
                        }
                        $($form.data('target')).modal('show');
                    }
                }
                else {
                    $form.trigger('forms.submit.error', [data]);
                }
                if (data.message && $messageBlock) {
                    var place = $('[data-place-text]', $messageBlock);
                    var holder = $('[data-text-holder]', $messageBlock);

                    if (holder) {
                        if (data.success) {
                            holder.addClass('success-msg').removeClass('text-error');
                        }
                        else {
                            holder.addClass('text-error').removeClass('success-msg');
                        }
                    }
                    if (place) {
                        place.html(data.message);
                    }
                    else {
                        $messageBlock.html(data.message);
                    }
                    if ($form.data('slide-enable') == true) {
                        $messageBlock.slideDown();
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $form.trigger('forms.submit.error', [xhr, textStatus, errorThrown]);
            },
            complete: function (xhr, textStatus) {
                $form.trigger('forms.submit.complete', [xhr, textStatus]);
            }
        });
        return false;
    });
});

// event.type должен быть keypress
function getChar(event) {
    if (event.which == null) {  // IE
        if (event.keyCode < 32) return null; // спец. символ
        return String.fromCharCode(event.keyCode)
    }

    if (event.which != 0 && event.charCode != 0) { // все кроме IE
        if (event.which < 32) return null; // спец. символ
        return String.fromCharCode(event.which); // остальные
    }

    return null; // спец. символ
}

function setcookie(name, value, expires, path, domain, secure) {	// Send a cookie
    //
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)

    expires instanceof Date ? expires = expires.toGMTString() : typeof(expires) == 'number' && (expires = (new Date(+(new Date) + expires * 1e3)).toGMTString());
    var r = [name + "=" + escape(value)], s, i;
    for (i in s = {expires: expires, path: path, domain: domain}) {
        s[i] && r.push(i + "=" + s[i]);
    }
    return secure && r.push("secure"), document.cookie = r.join(";"), true;
}

function getcookie(name) {
    var cookie = " " + document.cookie;
    var search = " " + name + "=";
    var setStr = null;
    var offset = 0;
    var end = 0;

    if (cookie.length > 0) {
        offset = cookie.indexOf(search);

        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(";", offset)

            if (end == -1) {
                end = cookie.length;
            }

            setStr = unescape(cookie.substring(offset, end));
        }
    }

    return (setStr);
}