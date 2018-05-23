$(document).ready(function () {    
  //$('.cmn-toggle-switch').click(function(){
  $('.cmn-toggle-switch').hover(function(){
      $('.header-menu-catalog').show(200);        
      if ($('.cmn-toggle-switch').hasClass('active')) {
        //$('.header-menu-catalog').hide(200);
        //$('.cmn-toggle-switch').removeClass('active');
      } else {
      //$('.header-menu-catalog').show(200);        
      //$('.cmn-toggle-switch').addClass('active');
      }
    },function() {
      $('.header-menu-catalog').hover(function(){
      },function() {
          $('.header-menu-catalog').hide(200);    
      }
      );
   }  
);

	$('.grid-carousel').owlCarousel({
		items: 1,
		navigationText: [ '', '' ],
		navigation: true,
		pagination:false,
		slideSpeed: 500,
		responsive: false
    });

$('#heroSlider, .heroSliderAction').show();
$('#heroSlider').cycle({
    speed: 1500,
    timeout: 3000,
    fx: 'fade',
    //fx: 'scrollHorz',
    //fx: 'none',
    //next: '.heroSlider .next',
    //prev: '.heroSlider .prev',
    pauseOnHover: true,
    slides: '.slide',
    pagerEventBubble: true,
});

  $('.lower-price-cycle').cycle({
      speed: 5000,
      timeout: 3000,
      fx: 'fade',
      pauseOnHover: true,
      slides: '.slide',
  });
  $('.novelties_goods-cycle').cycle({
      speed: 5000,
      timeout: 3000,
      fx: 'fade',
      pauseOnHover: true,
      slides: '.slide',
  });
  $('.shares-cycle').cycle({
      speed: 5000,
      timeout: 3000,
      fx: 'fade',
      pauseOnHover: true,
      slides: '.slide',
  });

    $('.j_product_of_day').hover(function(){

     $(this).next().show();

       }, function(){
          $(this).prev().hide();
       }
    );
        $('.img-product-day-text').hover(function(){

       }, function(){
          $(this).hide();
       }
    );

});
