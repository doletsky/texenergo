$(function () {


    $('.b-filterStatus .j-select').select2();

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


    $('.orderItem').each(function () {

        var options = {
            segmentShowStroke: true,
            segmentStrokeColor: "#fff",
            segmentStrokeWidth: 2,

            percentageInnerCutout: 40,

            animationSteps: 100,
            animationEasing: "easeOutBounce",
            animateRotate: true,
            animateScale: true,

            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };

        var canvas_days = $('.chart-days', this)[0],
            canvas_money = $('.chart-money', this)[0];

        var chart_days = new Chart(canvas_days.getContext('2d')).Pie([
            {
                value: $(this).data('days-r'),
                color: "#f25a29",
                highlight: "#ff6d3e",
                label: "Долг"
            },
            {
                value: $(this).data('days-d'),
                color: "#4e5157",
                highlight: "#63666c",
                label: "Лимит"
            }
        ], options);


        var chart_money = new Chart(canvas_money.getContext('2d')).Pie([
            {
                value: $(this).data('money-r'),
                color: "#f25a29",
                highlight: "#ff6d3e",
                label: "Долг"
            },
            {
                value: $(this).data('money-d'),
                color: "#4e5157",
                highlight: "#63666c",
                label: "Лимит"
            }
        ], options);

    });

});