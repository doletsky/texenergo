<?
if (getenv('PRODUCTION')==1) {
    $_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
} else {
    $_SERVER["DOCUMENT_ROOT"] = "/var/www/dev-hosts/st-texenergo";
}
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

//подключение модуля поиска
if(CModule::IncludeModule('search')) {

    //В этом массиве будут передаваться данные "прогресса". Он же послужит индикатором окончания исполнения.
    $NS = Array();

    //Задаем максимальную длительность одной итерации равной "бесконечности".
    $sm_max_execution_time = 0;

    //Это максимальное количество ссылок обрабатываемых за один шаг.
    //Установка слишком большого значения приведет к значительным потерям производительности.
    $sm_record_limit = 5000;

    do {

        $cSiteMap = new CSiteMap;

        //Выполняем итерацию создания,
        $NS = $cSiteMap->Create("s1", array($sm_max_execution_time, $sm_record_limit), $NS);

        //Пока карта сайта не будет создана.
    } while(is_array($NS));
}