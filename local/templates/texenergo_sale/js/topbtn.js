$(document).ready(function(){
	var aero_backtotop_button_opacity = 80;
	var aero_backtotop_skip = 300;
	var document_height = $(document).height();	
	var aero_backtodown_skip = document_height - 1500;
	var aero_backtotop_scroll_speed = 500;
	var aero_backtotop_position = 'bottom-right';
	var aero_backtotop_position_indent_y = 60;
	var aero_backtotop_position_indent_x = 60;
	var aero_backtodown_position_indent_y = 10;
    if ( $(window).width() < 768 ) {
        var aero_backtotop_position_indent_y = 15;
        var aero_backtotop_position_indent_x = 15;
    }

	$( "body" ).append( "<a title='Наверх' class='BackToTop' href='#'><img src='/local/templates/texenergo/img/back_to_top.png' alt='Перейти к началу страницы'></a>" ) ;
	$( "body" ).append( "<a title='Вниз' class='BackToDown' href='#'><img src='/local/templates/texenergo/img/back_to_down.png' alt='Перейти в конец страницы'></a>" ) ;
	$('.BackToTop').fadeTo( 0, aero_backtotop_button_opacity / 100 ) ;
	$('.BackToDown').fadeTo( 0, aero_backtotop_button_opacity / 100 ) ;
	if ($(this).scrollTop() > aero_backtotop_skip ) {
        $('.BackToTop').fadeIn();
    }
	else {
        $('.BackToTop').fadeOut();
		$('.BackToTop').css( "display", 'none' ) ;
    }

	if( aero_backtotop_position == 'top-left' ){
		$('.BackToTop').css( "top", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "left", aero_backtotop_position_indent_x + 'px' ) ;
	}
	else if( aero_backtotop_position == 'top-center' ){
		$('.BackToTop').css( "top", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "left", ( $( window ).width() / 2 ) - ( aero_backtotop_image_width / 2 ) + 'px' ) ;
	}
	else if(aero_backtotop_position=='top-right'){
		$('.BackToTop').css( "top", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "right", aero_backtotop_position_indent_x + 'px' ) ;
	}
	else if( aero_backtotop_position == 'middle-left' ){
		$('.BackToTop').css( "top", ( $( window ).height() / 2 ) - ( aero_backtotop_image_height / 2 ) + 'px' ) ;
		$('.BackToTop').css( "left", aero_backtotop_position_indent_x + 'px' ) ;
	}
	else if( aero_backtotop_position == 'middle-center' ){
		$('.BackToTop').css( "top", ( $( window ).height() / 2 ) - ( aero_backtotop_image_height / 2 ) + 'px' ) ;
		$('.BackToTop').css( "left", ( $( window ).width() / 2 ) - ( aero_backtotop_image_width / 2 ) + 'px' ) ;
	}
	else if(aero_backtotop_position=='middle-right'){
		$('.BackToTop').css( "top", ( $( window ).height() / 2 ) - ( aero_backtotop_image_height / 2 ) + 'px' ) ;
		$('.BackToTop').css( "right", aero_backtotop_position_indent_x + 'px' ) ;
	}
	else if( aero_backtotop_position == 'bottom-left' ){
		$('.BackToTop').css( "bottom", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "left", aero_backtotop_position_indent_x + 'px' ) ;
	}
	else if( aero_backtotop_position == 'bottom-center' ){
		$('.BackToTop').css( "bottom", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "left", ( $( window ).width() / 2 ) - ( aero_backtotop_image_width / 2 ) + 'px' ) ;
	}
	else if(aero_backtotop_position=='bottom-right'){
		$('.BackToTop').css( "bottom", aero_backtotop_position_indent_y + 'px' ) ;
		$('.BackToTop').css( "right", aero_backtotop_position_indent_x + 'px' ) ;
		$('.BackToDown').css( "bottom", aero_backtodown_position_indent_y + 'px' ) ;
		$('.BackToDown').css( "right", aero_backtotop_position_indent_x + 'px' ) ;
	}

    $(window).scroll(function(){
		if ($(this).scrollTop() > aero_backtotop_skip ) {
            $('.BackToTop').fadeIn() ;
        }
		else {
            $('.BackToTop').fadeOut() ;
        };
	var scrollBottom = $(this).scrollTop() + $(this).height();
	if (scrollBottom < aero_backtodown_skip ) {
            $('.BackToDown').fadeIn() ;
        }
		else {
            $('.BackToDown').fadeOut() ;
        }
    });
    $('.BackToTop').click(function(){
        $("html, body").animate({ scrollTop: 0 }, aero_backtotop_scroll_speed ) ;
        return false ;
    });
    $('.BackToDown').click(function(){
        $("html, body").animate({ scrollTop: $(document).height() }, aero_backtotop_scroll_speed ) ;
        return false ;
    });
});
