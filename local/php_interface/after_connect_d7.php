<?
$connection = \Bitrix\Main\Application::getConnection();
//$connection->queryExecute("SET LOCAL time_zone='Europe/Moscow'");
$connection->queryExecute("SET NAMES 'utf8'");
$connection->queryExecute('SET collation_connection = "utf8_unicode_ci"');
?>