<div class="three three-nomargin">
	<nav class="sidebar sidebar-main">
		
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"tree",
			Array(				
				"ROOT_MENU_TYPE" => "left_info",
				"MAX_LEVEL" => "3",
				"CHILD_MENU_TYPE" => "child",
				"USE_EXT" => "Y",
				"DELAY" => "N",
				"ALLOW_MULTI_SELECT" => "N",
				"MENU_CACHE_TYPE" => "N",
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"MENU_CACHE_GET_VARS" => array()
			),
			$component
		);?>			

	</nav>
</div>