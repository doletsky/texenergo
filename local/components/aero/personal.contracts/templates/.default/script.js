$(function () {

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
        format: 'Y.m.d',
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