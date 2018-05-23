$(function () {

    function getFormValidator(form) {
        var validation_rules = [],
            validator;

        if ($('.required', form).size() == 0) return false;

        $('input,textarea', form).each(function () {

            if ($(this).attr('type') == 'hidden') return;

            if (!$(this).attr('id')) $(this).attr('id', 'form_field_' + (100000 - Math.round(Math.random() * 100000)));

            var field = {
                name: this.name,
                id: $(this).attr('id'),
                display: $(this).data('title') || '',
                rules: ($(this).hasClass('required') ? 'required' : '') + ($(this).data('rules') ? '|' + $(this).data('rules') : '')
            };


            validation_rules.push(field);
        });

        validator = new FormValidator(form.attr('id'), validation_rules, function (errors, event) {
            $('.field-error', form).removeClass('field-error');
            $('.field-error-message', form).remove();
            if (errors.length > 0) {
                $(errors).each(function () {
                    var field = $('#' + this.id);

                    if (field.hasClass('nicefileinput')) {
                        field = field.closest('.NFI-wrapper');
                    }

                    field.addClass('field-error');
                    field.next('.field-error-message').remove();
                    if (form.data('messages') && !field.data('inline') && field.attr('type') != 'checkbox' && field.attr('type') != 'radio') {
                        field.after('<div class="field-error-message">' + this.message + '</div>');
                    }
                });

                var top = $('.field-error:first').offset().top;
                $('body').animate({scrollTop: top - 40}, 500);

            }
            return true;
        });

        validator.registerCallback('check_date',function (value) {
            var arValue = value.split('.'), now = new Date();
            if (arValue.length != 3) return false;
            arValue[0] = parseInt(arValue[0]);
            arValue[1] = parseInt(arValue[1]);
            arValue[2] = parseInt(arValue[2]);
            if (arValue[0] > 31 || arValue[0] < 1) return false;
            if (arValue[1] > 12 || arValue[1] < 1) return false;
            if (arValue[2] > now.getFullYear() || arValue[2] < (now.getFullYear() - 100)) return false;
            return true;
        }).setMessage('check_date', 'Введите дату в формате ДД.ММ.ГГГГ');

        return validator;
    }

    $(document).on('submit', '.form', function (e) {
        if ($(this).hasClass('form-ajax')) return;
        var form = $(this),
            validator = getFormValidator(form);

        if (validator && !validator._validateForm()) {
            e.preventDefault();
            return false;
        }
    });

    $(document).on('submit', '.form-ajax', function (e) {
        var form = $(this),
            url = form.attr('action'),
            validator = getFormValidator(form);
        e.preventDefault();

        if (!validator || validator._validateForm()) {

            form.addClass('form-loading');

            $('.form-submit', form).attr('disabled', true);

            $.post(url, form.serialize(), function (response) {

                var complete_event = jQuery.Event("complete");
                form.trigger(complete_event, [response]);
                if (complete_event.isDefaultPrevented()) return true;

                var tree = $('<div>' + response + '</div>');

                form.removeClass('form-loading');
                $('.form-submit', form).attr('disabled', false);

                if ($('.form-ajax', tree).size() > 0) {
                    form.html($('.form-ajax', tree).contents());
                } else {
                    form.html(response);
                }
                form.trigger('init');
            });

        }

        return false;
    });

});