

$('#mainSlider').show();

$('#mainSlider').cycle({
    speed: 1000,
    timeout: 1000,
    fx: 'fade',
    pauseOnHover: true,
    slides: '.slide',
    pagerEventBubble: true,
});
 $('#catalog-main-slider').owlCarousel({
        items: 4,
        pagination: false,
        navigation: true,
        navigationText: false,
		responsive: true
		// responsive:{
  //       320:{
  //           items:2
  //       },
  //       800:{
  //           items:3
  //       },
  //       1000:{
  //           items:5
  //       }
  //   }
    });
 
