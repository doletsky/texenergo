$(function () {

    $('.landing-sections').masonry({
        columnWidth: 305,
        gutter: 10,
        transitionDuration: '1s',
        itemSelector: '.bar'
    });

    $('.list-toggle').on('click', function () {

        $(this)
            .toggleClass('open')
            .parent('.li-bar-parent')
            .toggleClass('is-open');
        $('.landing-sections').masonry();
    });



});