$(function(){
	$('.j_owl_slider_4', toolbar).owlCarousel({
		items: 4,
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