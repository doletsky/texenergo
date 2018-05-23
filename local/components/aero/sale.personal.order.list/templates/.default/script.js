$(function () {

    $('.b-filterStatus select').on('change', function () {
        document.location = this.value;
    });

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

    $('.b-filterStatus select.j-select').select2({dropdownCssClass: "status-filter"});

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

});