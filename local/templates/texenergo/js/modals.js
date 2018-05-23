$(function () {

    $.extend($.fancybox.defaults, {
        width: 570,
        height: 'auto',
        maxWidth: 570,
        autoHeight: true,
        autoSize: false,
        fitToView: false,
        live: true,
        padding: 30,
        margin: [40, 40, 40, 40],
        title: null,
        scrolling: 'no',
        type: 'inline',
        afterShow: function () {
            $('body').trigger('popup_init', [this.wrap]);
        }
    });

    $('.popup').each(function () {
        var href = $(this).attr('href'),
            width = $(this).data('width') || '100%',
            height = $(this).data('height') || '100%',
            type = (href.substr(0, 1) == '#' ? 'inline' : 'ajax'),
            picsRegex = /(jpe?g|gif|png)$/i;

        if (picsRegex.test(href)) {
            type = 'image';
        }

        if($(this).hasClass('popup_cb')){
			paddingVal = [0, 30, 30, 30];	
		}else{
			paddingVal = 30;
		}
		
		$(this).fancybox({
            width: width,
            height: height,
            maxWidth: width,
            title: null,
			padding: paddingVal,
            type: type
        });

    });
});