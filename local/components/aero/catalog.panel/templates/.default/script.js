$(function () {
    var toolbar = $('.toolbar'),
        tabs = $('.toolbar-tabs');

    toolbar.on('click', '.control', function (e) {
        e.preventDefault();

        toolbar.toggleClass('opened');

        if (toolbar.hasClass('opened')) {
            toolbar.trigger('opened');
        } else {
            toolbar.trigger('closed');
        }
        return false;
    });

    tabs.on('click', '.tab', function (e) {

        if (toolbar.hasClass('opened') && $(this).hasClass('active')) {
            return true;
        }

        e.preventDefault();

        $('.active', tabs).removeClass('active');
        $(this).addClass('active');

        if (!toolbar.hasClass('opened')) {
            toolbar.addClass('opened');
        }
        toolbar.trigger('opened');

        return false;
    });

    toolbar.on('opened', function () {
        //$('.toolbar-content .container', this).empty();

        $('.toolbar-content .container', this).load('/catalog/ajax/panel.php', {section: $('.active', tabs).attr('id')}, function (data) {

            $('.j_owl_slider_5', toolbar).owlCarousel({
                items: 5,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: false,
                itemsMobile: false,
                pagination: false,
                navigation: true,
                navigationText: false,
                slideSpeed: 500,
                afterInit: function () {
                    var max_height = 0;
                    $('.cat-product', toolbar).each(function () {
                        if ($(this).height() > max_height) {
                            max_height = $(this).height();
                        }
                    });
                    $('.cat-product', toolbar).height(max_height);
                }
            });

        });
    });

    toolbar.on('reload', function (e, section) {
        if (!section.length) return;

        if ($('.' + section, tabs).hasClass('active') && toolbar.hasClass('opened')) {
            toolbar.trigger('opened');
        }
    });


    $('.catalog-favorite-toggle').on('toggle', function () {
        var count = parseInt($('.j-favorites-count', toolbar).html());
        if ($(this).hasClass('active')) {
            count++;
        } else {
            count--;
        }
        $('.j-favorites-count', toolbar).html(count);
    });
});