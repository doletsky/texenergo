$(function () {

    $('.b-invoiceHeadline').click(function () {
        $(this).parent().parent().children('.b-invoiceBody').toggle();
        $(this).toggleClass('is-invoiceHeadline-open');

        if ($(this).hasClass('is-invoiceHeadline-open')) {

            $(this)
                .parent()
                .find('.b-switchDocuments')
                .removeClass('hidden');
        } else {
            $(this)
                .parent()
                .find('.b-switchDocuments')
                .addClass('hidden');
        }

    });
    //$('.is-invoiceBody-toggled').toggle();

    $('.b-filterStatus select.j-select').select2();

    $('.b-filterStatus select').on('change', function () {
        document.location = this.value;
    });

    $('.b-filterPeriod em').on('click', function (e) {
        e.stopPropagation();

        $('#filter_dates').toggle();

        if ($('#filter_dates').is(':visible')) {
            $('html').one('click', function () {
                $('#filter_dates').hide();
            });
        }
    });

    $('#filter_dates').on('click', function (e) {
        e.stopPropagation();
    });


    $('#filter_dates .calendar').DatePicker({
        format: 'd.m.Y',
        flat: true,
        date: [$('#orders_date_from').val(), $('#orders_date_to').val()],
        calendars: 2,
        mode: 'range',
        starts: 1,
        onChange: function (formated, dates) {
            $('#orders_date_from').val(formated[0]);
            $('#orders_date_to').val(formated[1]);
        }
    });

    $('#filter_dates').hide();

    $('.box-popup').prependTo('body');

    // показ всплывающих окон при клике на кнопки страницы user-account-shipping
    $('.shipButtons .rounded-contract').on('click', function(){
        var template = $(this).data('template'),
            bills = [];
        $('[data-bill]:checked').each(function(){
            bills.push($(this).val());
        });
        if(bills.length > 0){
            $.ajax({
                url: '/ajax/deliveryForm.php',
                data: {
                    template: template,
                    bills: bills
                },
                type: 'get',
                dataType: 'html',
                success: function(data){
                    // очищаем все попапы, чтобы избежать ошибки с повторяющимися name полей
                    $('.box-popup').html('');
                    $('.box-popup.'+template).removeClass('hidden').html(data);
                    $('[data-phone-mask]').mask('8(999)999-99-99');
                    $('[data-hours-mask]').mask('99:99');
                    $('.deliveryForm').parsley();
                    if($('.calendar', '.box-popup.'+template)){
                        $('.calendar', '.box-popup.'+template).DatePicker({
                            format: 'd.m.Y',
                            flat: true,
                            date: new Date(),
                            calendars: 2,
                            starts: 1,
                            onChange: function (formated, dates) {
                                $('[name="fields[pickup_date]"]').val(formated);
                            }
                        });
                    }
                }
            });
        }
    });

});