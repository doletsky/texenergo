$(function () {  

    $('.b-filterPeriod em').on('click', function () {
        $('#filter_dates').slideToggle(300);
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

});