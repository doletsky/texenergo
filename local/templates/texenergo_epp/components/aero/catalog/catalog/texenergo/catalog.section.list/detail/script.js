$(window).on('load', function () {
    $('.product-category .subsections').masonry({
        columnWidth: 305,
        gutter: 10,
        transitionDuration: '1s',
        itemSelector: '.cat-unit'
    });
});