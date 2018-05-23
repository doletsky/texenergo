function toggleFavorite(id, callback) {
    $.get('/catalog/ajax/favorites.php', {id: id}, function (data) {
        if (typeof(callback) == 'function') {
            callback.call(this, (data.success == 1));
        }
    }, 'json');
}

$(function () {

    $('body').on('click', '.catalog-favorite-toggle', function (e) {
        if($(this).hasClass('unauthorized')) return true;
		e.preventDefault();		
        var that = $(this),
            id = that.data('id');

        toggleFavorite(id, function (success) {
            if (success) {
                var btn = $('.catalog-favorite-toggle[data-id=' + id + ']');
                if (!btn.hasClass('active')) {
					if(that.hasClass('favorite-accessories')){
						var productWrap = that.closest('.single-accessories');
					}else{
						var productWrap = that.parents('.pProductInLine');
						if(productWrap.length == 0){
							productWrap = that.closest('.cell');
						}
						if(productWrap.length == 0){
							productWrap = that.closest('tr');
						}
						if(productWrap.length == 0){
							productWrap = that.closest('.twelve').find('.pContent');
						}
					}
					
                    var img = productWrap.find('.j-image-to-animate');
                    var imgPosTop = that.offset().top + 15;
                    var imgPosLeft = that.offset().left + 5;
                    var toolBarTop = $('.j-toolbar').offset().top + 30;
                    var toolBarLeft = $('.j-toolbar .toolbar-tabs').offset().left;
                    var animatedImg = img.clone();
                    animatedImg.appendTo('body').addClass('animated-img').css({
                        "left": imgPosLeft,
                        "top": imgPosTop
                    });
                    var timeLine = new TimelineMax();
                    timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
                        .to(animatedImg, 1, {top: toolBarTop, left: toolBarLeft + 100})
                        .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                            animatedImg.remove();
                        }});
                }

                btn.toggleClass('active');
                that.trigger('toggle');
            }
        });

        $('.toolbar').trigger('reload', ['favorites']);

        return false;
    });

});