<?php


$arResult['MAX_RESULTS'] = COption::GetOptionInt('search', 'max_result_size', 10000);
$arResult["REQUEST"]["QUERY"] = rtrim($arResult["REQUEST"]["QUERY"], '*');