$(function () {

    $('#compareAll').on('init', function () {
        var that = this;


        // cell height
        var rowCount = $('.compItem:first .specCell', that).size(),
            arCellHeight = [];

        for (var i = 0; i < rowCount; i++) {
            arCellHeight.push(0);
        }

        $('.compItem', that).each(function () {
            $('.specCell', this).each(function (index) {
                if ($(this).height() > arCellHeight[index]) {
                    arCellHeight[index] = $(this).height();
                }
            });
        });

        $('.compItem', that).each(function () {
            $('.specCell', this).each(function (index) {
                $(this).height(arCellHeight[index]);
            });
        });

        $('.specCell-header', that).each(function (index) {
            $(this).height(arCellHeight[index]);
        });


        // scroll
        $('.compBlock', this).jScrollPane();

        //switches
        $('.group-switch.similar .subSwitch', this).on('change', function (e) {

            if ($(this).hasClass('subSwitch-on')) {
                $('.specCell.similar').hide();
            } else {
                $('.specCell.similar').show();
            }
            $('.compBlock .jspContainer').css({height: $('.compItem').outerHeight(true) + 'px'});
            $('.compBlock').data('jsp').reinitialise();

        });

        if ($('.specCell.similar', this).size() <= 0 || $('.specCell.similar', this).size() == $('.specCell', this).size()) {
            $('.group-switch.similar').hide();
        }

        if ($('.compItem.main-vendor', this).size() <= 0 || $('.compItem.main-vendor', this).size() == $('.compItem', this).size()) {
            if(!$('.group-switch.vendor .subSwitch').hasClass('subSwitch-on')){
				$('.group-switch.vendor').hide();
			}
        }

        $('.subSwitch:not(.jSubSwitch)', this).each(function () {
            $(this).addClass('jSubSwitch');
            $(this).click(function () {
                $(this).toggleClass('subSwitch-on');
                $(this).trigger('change');
            });
        });

        $('.group-switch.vendor .subSwitch', this).on('change', function (e) {
            if ($(this).hasClass('subSwitch-on')) {
                //$('.compItem:not(.main-vendor)').hide();
                document.location = '/catalog/compare/?own=yes';
            } else {
                //$('.compItem:not(.main-vendor)').show();
                document.location = '/catalog/compare/';
            }
            //$('.compCanvas').css({width: 179 * $('.compItem:visible').size() + 'px'});
            //$('.compBlock').data('jsp').reinitialise();
        });

        // remove button
        $('.compItem .compare-remove', this).on('click', function (e) {
            e.preventDefault();
            //$(this).closest('.compItem').remove();
            //$(that).empty();
            $(that).css('opacity', 0.7);
            deleteCompareAjax.call(this, e, function (respJSON) {
                $.get('/catalog/compare/', {compare_refresh: 'yes'}, function (response) {
                    $(that).html(response);
                    $(that).trigger('init');
                    $(that).css('opacity', 1);
                });

            });

            return false;
        });


    });

    $('#compareAll').trigger('init');

});