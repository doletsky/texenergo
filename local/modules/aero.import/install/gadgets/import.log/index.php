<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
CJSCore::Init(array("jquery"));

$logFile = COption::GetOptionString('aero.import', 'log_path', '');
$logUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $logFile);
$running = COption::GetOptionString('aero.import', 'agent_is_running', 'N');

if (!empty($_FILES) && $running !== 'Y') {
    if ($_FILES['aero_import_file']['error'] == '0') {
        $arFile = $_FILES['aero_import_file'];
        if ($arFile['type'] == 'text/xml') {
            $fileDir = trim(COption::GetOptionString('aero.import', 'data_file', ''), '/');
            $sDataFile = $_SERVER['DOCUMENT_ROOT'] . '/' . $fileDir . '/catTE.xml';
            if (@copy($arFile['tmp_name'], $sDataFile)) {
                if (strlen($logFile) > 0) {
                    @file_put_contents($logFile, "Файл загружен в {$sDataFile}\n");
                }
            }
        }
    }
    LocalRedirect('');
}

$logContent = '';
if (file_exists($logFile) && is_file($logFile)) {
    $logContent = htmlspecialchars(file_get_contents($logFile));
    $logContent = nl2br($logContent);
}



?>
<? if (strlen($logContent) > 0): ?>
    <form method="POST" action="" enctype="multipart/form-data">
        <label>Загрузить catTE.xml</label>
        <input type="file" name="aero_import_file" required<? if ($running == 'Y'): ?> disabled<? endif; ?>><input
            type="submit" name="upload" value="Загрузить"<? if ($running == 'Y'): ?> disabled<? endif; ?>>
    </form>
    <hr>
    <div style="height:300px; overflow:scroll; background:#fff;" id="aero_import_log">
        <?= $logContent; ?>
    </div>
    <p>
        <a href="/bitrix/admin/settings.php?lang=ru&mid=aero.import&mid_menu=1">Изменить настройки</a>
    </p>
    <? if (file_exists($logFile) && is_file($logFile)): ?>
        <script>
            setInterval(function () {
                $.get('<?=$logUrl; ?>', {t: Math.random()}, function (response) {

                    response = response.replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");

                    $('#aero_import_log').html(response.replace(/([^>])\n/g, '$1<br/>'));
                    $('#aero_import_log').scrollTop($("#aero_import_log")[0].scrollHeight);
                });
            }, 10000);
        </script>
    <? endif; ?>
<? else: ?>
    <p>Журнал пока пуст</p>
<? endif; ?>