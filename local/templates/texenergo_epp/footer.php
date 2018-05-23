<?if(!defined('ERROR_404')):?>

	<?if($_REQUEST['ajax_call'] == 'y') die();?>


	<!-- FOOTER START -->
	<footer class="footer clearfix" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
		  <div class="row">
			<div class="col-xlg-3 col-md-3">
				<div class="footer-logo">
					<img class="img-responsive" src="/local/templates/texenergo_epp/img/main/logo-footer.jpg" alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." width="335" height="95" title="Логотип компании ООО &quot;МФК TEXENERGO&quot;">
					<span class="logo-text">Успех начинается здесь!</span>
				</div>
				<div class="social">
					<a href="https://www.youtube.com/channel/UCpe3yrjA1P7fwhWVJrKjSIQ"><i class="zmdi zmdi-youtube zmdi-hc-3x"></i></a>
					<a href="https://twitter.com/Texenergo_Ru"><i class="zmdi zmdi-twitter zmdi-hc-lg"></i></a>
					<a href="https://www.facebook.com/texnru"><i class="zmdi zmdi-facebook zmdi-hc-lg"></i></a>
					<a href="https://vk.com/texenergo"><i class="zmdi zmdi-vk zmdi-hc-lg"></i></a>
					<a href="https://ok.ru/texenergo"><i class="zmdi zmdi-odnoklassniki zmdi-hc-lg"></i></a>
				</div>
				<div><?$APPLICATION->IncludeFile('/include/counter.php');?></div>
				<div>
					<ul>		
					<li><span class="copyright">© 1990-<?=date("Y");?><br>&laquo;МФК ТЕХЭНЕРГО&raquo;</span></li>
					<li><span class="copyright">Все права защищены.</span></li>
					</ul>
				</div>
			</div>
			<div class="menu1 menu col-xlg-4 col-xlg-offset-1 col-md-4 col-md-offset-1">
			   <div class="row">
			     <div class="col-xlg-5 col-md-5">		
				<div class="menu-order">
				<h3 class="header">Система заказов</h3>
				<hr>
				<ul>
				  <li><a href="https://www.texenergo.ru/catalog/list.html/90000090" class="orange">Перейти в каталог</a></li>
				  <li><a href="https://www.texenergo.ru/eshop/delivery/">Доставка</a></li>
				  <li><a href="https://www.texenergo.ru/eshop/reclamation/">Рекламации</a></li>
				</ul>
				</div>
				<div class="menu-publication">
				<h3 class="header">Публикации</h3>
				<hr>
				<ul>
				  <li><a href="https://www.texenergo.ru/publication/akcii/">Акции</a></li>
				  <li><a href="https://www.texenergo.ru/publication/novosti_texenergo/">Новости</a></li>
				  <li><a href="https://www.texenergo.ru/publication/statyi/">Статьи</a></li>
				  <li><a href="https://www.texenergo.ru/publication/biblioteka_elektrotekhnika/">Библиотека</a></li>
				</ul>
				</div>
			     </div>	
			     <div class="col-xlg-5 col-xlg-offset-2 col-md-5 col-md-offset-2">		
				<div class="menu-about">
				<h3 class="header">О нас</h3>
				<hr>
				<ul>
				  <li><a href="https://www.texenergo.ru/about/company/">Информация о компании</a></li>
				  <li><a href="https://www.texenergo.ru/about/brend/">Бренд</a></li>
				  <li><a href="https://www.texenergo.ru/about/project/">Наши партнеры</a></li>
				  <li><a href="https://www.texenergo.ru/about/vacancy/">Вакансии</a></li>
				  <li><a href="https://www.texenergo.ru/about/contacts/">Контакты</a></li>
				</ul>
				</div>
				<div class="menu-help">	
				<h3 class="header">Помощь</h3>
				<hr>
				<ul>
				  <li><a href="https://www.texenergo.ru/user_help/call_centr/">Call-центр</a></li>
				  <li><a href="https://www.texenergo.ru/user_help/work_web/">Работа с сайтом</a></li>
				  <li><a href="https://www.texenergo.ru/forum/forum3/">Форум</a></li>
				</ul>
				</div>
			    </div>
			  </div><!-- /.row-->
			</div><!-- /.menu1-->
			<div class="menu3 menu col-xlg-3 col-xlg-offset-1 col-md-3 col-md-offset-1">
			  <div class="menu-contact">
			    <h3 class="header">Контакты</h3>
			    <hr>
			    <ul>
				<li>Адрес: 151580, Московская область,</li>
				<li>Солнечногорский р-н, дер.Черная грязь, д.65</li>
				<li>Самовывоз с 8:30-16:45</li>
				<li class="header orange">Отдел продаж:</li>
				<li>+7(495) 651-99-99 доп.1200</li>
				<li class="header orange">Секретариат</li>
				<li>+7(495) 651-99-99 доп.1100</li>
			    </ul>
			  </div>
			    <ul class="menu-other">
				<li><div class="price-list"><span class="lnr lnr-sort-alpha-asc"></span><a href="https://www.texenergo.ru/price_list/">Прайс-листы</a></div></li>
				<li><div class="catalog"><span class="lnr lnr-sort-amount-asc"></span><a href="https://www.texenergo.ru/manufacturers/">Каталоги производителей</a></div></li>
			    </ul>
			</div>
		  </div>
		</div>
		<!-- container -->

	</footer>
	<!-- FOOTER END -->



<?endif;?>
<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
    var _tmr = _tmr || [];
    _tmr.push({id: "50204", type: "pageView", start: (new Date()).getTime()});
    (function (d, w) {
        var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
        ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
        var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
        if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
    })(document, window);
</script><noscript>


    <img src="//top-fwz1.mail.ru/counter?id=50204;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</noscript>
<!-- //Rating@Mail.ru counter -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2697871-1', 'auto');
ga('send', 'pageview');
</script>
</body>

</html>
