$(function () {

    $('.address-row').each(function () {
        if ($(this).hasClass('js-address-row')) return;
        $(this).addClass('js-address-row');

        var row = $(this);
        var arZip = [];
        var suggest = $('.location-suggest', row),
            field = $('#' + suggest.data('field'));

        suggest.autocomplete({
            serviceUrl: '/local/components/aero/sale.ajax.locations/search.php',
            paramName: 'search',
            params: {
                group: suggest.data('group')
            },
            showNoSuggestionNotice: false,
            noSuggestionNotice: 'Город не найден',
            minChars: 3,
            autoSelectFirst: false,
            preventBadQueries: false,
            transformResult: function (response) {
                var responseObj = $.parseJSON(response);
                return {
                    suggestions: $.map(responseObj, function (dataItem) {
                        var region = dataItem.REGION_NAME ? ' (' + dataItem.REGION_NAME + ')' : '';
                        return { value: dataItem.NAME + region, data: dataItem };
                    })
                };
            },
            onSelect: function (suggestion) {
                field.val(suggestion.data.ID);
                arZip = suggestion.data.ZIP;
                $('.zip-suggest', row).autocomplete('dispose');
                $('.zip-suggest', row).autocomplete({
                    lookup: arZip,
                    autoSelectFirst: true
                });
                if (arZip.length > 0) {
                    $('.zip-suggest', row).val(arZip[0]);
                }
				$(this).trigger('location-selected');

            }
        });

        suggest.on('keyup', function (e) {
            if (e.keyCode != 13) {
                field.val(0);
            }
        });

        suggest.on('blur', function () {
            if (field.val() <= 0) {
                suggest.val('');
            }
        });

        $('.zip-suggest', row).autocomplete({
            lookup: arZip,
            autoSelectFirst: true
        });
    });


});