$(window).on('load', function () {

    $(".j_pub_carousel").owlCarousel({
        items: 1,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        pagination: false,
        navigation: true,
        navigationText: false,
        afterMove: function () {
            $(".j_pub_carousel_num").text(this.currentItem + 1);
        }
    });
});