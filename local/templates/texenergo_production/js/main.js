$(document).ready(function () {    
  $('.cmn-toggle-switch').click(function(){
      if ($('.cmn-toggle-switch').hasClass('active')) {
        $('.header-menu-collapsible').hide('slow');
        $('.cmn-toggle-switch').removeClass('active');
      } else {
      $('.header-menu-collapsible').show('slow');        
      $('.cmn-toggle-switch').addClass('active');
    }
  });
  $('input[type=file]').bootstrapFileInput();
  $('.file-inputs').bootstrapFileInput();
});

$(document).ready(function(){
  var animate_params = {}; 
  var clickCircle;
  var activeCircle;
  var clickCircleHeader;
  var activeCircleHeader;

  var activeCircleAnimateParam;
  var activeCircleCssParam;
  var activeCircleTextCssParam;
  var activeCircleHeaderCssParam;

  var clickCircleCssParam;
  var clickCircleAnimateParam;
  var clickCircleHeaderCssParam;
  var clickCircleTextCssParam;

  $('.circle_header').click(function(){
    clickCircleHeader = '#'+ this.id;
    activeCircleHeader = '#' + $('.activeCircle').attr('id');
  if (clickCircleHeader != activeCircleHeader) {

    if(clickCircleHeader == '#circle_header3' &&  activeCircleHeader == '#circle_header2') {
      activeCircle = '.circle2';
      clickCircle = '.circle3';  
      activeCircleCssParam = {
        'border-color':'#434343',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'left':'440px',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'15px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'25px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'width':'350px',
      };
    }
    else if(clickCircleHeader == '#circle_header2' &&  activeCircleHeader == '#circle_header3') {
      activeCircle = '.circle3';
      clickCircle = '.circle2';  
      activeCircleCssParam = {
        'border-color':'#434343',
        'z-index':'999',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'right':'0',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'75px',
        'width':'245px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'160px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'left':'420px',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
        'left':'70px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'left':'75px',
        'width':'350px',
      };
    } 
    else if(clickCircleHeader == '#circle_header1' &&  activeCircleHeader == '#circle_header2') {
      activeCircle = '.circle2';
      clickCircle = '.circle1';  
      activeCircleCssParam = {
        'border-color':'#434343',
        'z-index':'999',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'left':'530px',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'35px',
        'width':'360px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'40px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'left':'0',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
        'left':'70px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'left':'75px',
        'width':'350px',
      };
    }  
    else if(clickCircleHeader == '#circle_header2' &&  activeCircleHeader == '#circle_header1') {
      activeCircle = '.circle1';
      clickCircle = '.circle2';  
      activeCircleCssParam = {
        'border-color':'#434343',
        'z-index':'999',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'left':'0',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'35px',
        'width':'360px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'40px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'left':'420px',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
        'left':'70px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'left':'75px',
        'width':'350px',
      };
    }  
    else if(clickCircleHeader == '#circle_header3' &&  activeCircleHeader == '#circle_header1') {
      activeCircle = '.circle1';
      clickCircle = '.circle3';  
      activeCircleCssParam = {
        'border-color':'#434343',
        'z-index':'999',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'left':'0',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'35px',
        'width':'360px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'40px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'right':'0',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
        'left':'160px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'left':'75px',
        'width':'350px',
      };
      $('.circle2').css({'left':'440px','z-index':'999',});
      $('.circle2 .circle-text').css({'left':'15px',});
    }
    else if(clickCircleHeader == '#circle_header1' &&  activeCircleHeader == '#circle_header3') {
      activeCircle = '.circle3';
      clickCircle = '.circle1';  
      activeCircleCssParam = {
        'border-color':'#434343',
        'z-index':'999',
      };
      activeCircleAnimateParam = {
        'border-color':'#434343',
        'width':'470px',
        'height':'470px',
        'border-width':'30px',
        'top':'65px',
        'right':'0',
      };
    activeCircleTextCssParam = {
        'top':'210px',
        'left':'35px',
        'width':'245px',
      };
    activeCircleHeaderCssParam = {
        'font-size':'35px',
        'left':'160px',
      };
    clickCircleCssParam = {
        'border-color':'#fa9229',
        'z-index':'1000',
        'border-radius':'300px',
      };    
    clickCircleAnimateParam = {
        'top':'0',
        'right':'0',
        'width':'600px',
        'height':'600px',        
        'border-width':'50px',
        'border-color':'#fa9229',
      };
    clickCircleHeaderCssParam = {
        'font-size':'48px',
        'left':'70px',
      };
    clickCircleTextCssParam = {
        'top':'270px',
        'left':'75px',
        'width':'350px',
      };
      $('.circle2').css({'left':'530px','z-index':'999',});
      $('.circle2 .circle-text').css({'left':'35px',});
    }

    $(activeCircleHeader)
		.hide()
		.queue(function() {
			$(activeCircle).css(activeCircleCssParam).animate(activeCircleAnimateParam,500);
      $(activeCircle+' .circle__link').hide();
      $(activeCircle+' .circle-text').hide().css(activeCircleTextCssParam).show();
			$(this).dequeue();
			})
		.queue(function() {
      $(activeCircleHeader).css(activeCircleHeaderCssParam).show();
			$(this).dequeue();
		})
		//.delay(500)
		.queue(function() {
			$(clickCircle).css(clickCircleCssParam).animate(clickCircleAnimateParam, 500);
      $(clickCircleHeader).css(clickCircleHeaderCssParam);
      $(clickCircle+' .circle-text').hide().css(clickCircleTextCssParam).show(500);
      $(clickCircle+' .circle__link').show();
      $(activeCircleHeader).removeClass('activeCircle');
      $(clickCircleHeader).addClass('activeCircle');      
			$(this).dequeue();
		});
  };

  });

});		