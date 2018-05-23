$(window).load(function () {
    $(".j_cat_product_list").each(function () {
        var parentHeight = $(this).height();
        /*if (parentHeight > 0) {
            $(this).find(".j_cat_product_card").height(parentHeight);
            $(this).find(".cat-hoverbox").css('padding-top', parentHeight + 15 + 'px');
        }*/
    });
});