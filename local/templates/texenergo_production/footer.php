<?if(!defined('ERROR_404')):?>

	<?if($_REQUEST['ajax_call'] == 'y') die();?>


	<!-- FOOTER START -->
	<footer class="footer clearfix" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
		  <div class="row">
			<div class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="footer-logo">
					<img class="img-responsive" src="/local/templates/texenergo_production/img/main/logo-footer.jpg" alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." width="334" height="97" title="Логотип компании ООО &quot;МФК TEXENERGO&quot;">
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
			</div>
			<div class="menu1 menu col-xlg-4 col-xlg-offset-1 col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-1 col-sm-6 col-xs-12">
			   <div class="row">
			     <div class="col-xlg-5 col-md-5 col-sm-6 col-xs-6">		
				<div class="menu-order">
				<h3 class="footer__header">Меню</h3>
				<ul>
				  <li><a href="/our-production/">Производство в России</a></li>
				  <li><a href="/production-in-china/">Производство в Китае</a></li>
				  <li><a href="/patents/">Патенты</a></li>
				  <li><a href="/quality-assurance/">Гарантия качества</a></li>
				  <li><a href="/news-production/">Новости</a></li>
				  <li><a href="/articles-production/">Статьи</a></li>
				</ul>
				</div>
			     </div>	
			     <div class="col-xlg-5 col-xlg-offset-2 col-lg-5 col-lg-offset-2 col-md-5 col-md-offset-2 col-sm-6 col-xs-6">		
				<div class="menu-help">	
				<h3 class="footer__header">Помощь</h3>
				<ul>
				  <li><a href="https://www.texenergo.ru/user_help/call_centr/">Call-центр</a></li>
				  <li><a href="https://www.texenergo.ru/user_help/work_web/">Работа с сайтом</a></li>
				  <li><a href="https://www.texenergo.ru/forum/forum/">Форум</a></li>
				  <li><a href="https://www.texenergo.ru/about/contacts/">Контакты</a></li>
				</ul>
				</div>
			    </div>
			  </div><!-- /.row-->
			</div><!-- /.menu1-->
			<div class="menu3 menu col-xlg-3 col-xlg-offset-1 col-md-3 col-md-offset-1 col-sm-6 col-xs-12">
			  <div class="menu-contact">
			    <h3 class="footer__header menu-contact__title">Контакты</h3>
			    <ul>
				<li>Адрес: 151580, Московская область,</li>
				<li>Солнечногорский р-н, дер.Черная грязь, д.65</li>
				<li>Самовывоз с 8:30-16:45</li>
				<li class="footer__header">Отдел продаж:</li>
				<li>+7(495) 651-99-99 доп.1200</li>
				<li class="footer__header">Секретариат</li>
				<li>+7(495) 651-99-99 доп.1100</li>
			    </ul>
			  </div>
			</div>
		  </div>
		  <div class="row">
			<div class="col-xlg-3 col-md-3">
				<ul>		
					<li><span class="copyright">© 1990-<?=date("Y");?><br>&laquo;МФК ТЕХЭНЕРГО&raquo;</span></li>
					<li><span class="copyright">Все права защищены.</span></li>
				</ul>
			</div>
		 </div>
		</div>
		<!-- container -->

	</footer>
	<!-- FOOTER END -->



<?endif;?>

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
