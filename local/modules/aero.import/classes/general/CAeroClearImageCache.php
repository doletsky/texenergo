<?php
Class CAeroClearImageCache
{

    //инфоблок каталога
    private $IBLOCK_CATALOG;

    //файл выгрузки
    private $filePath;

    //указатель на файл импорта
    private $handle;

    //объект доступа к инфоблоку каталога
    private $catalog;

    //отметка предыдущей очистки
    private $datetime;

    //соответствие полей и столбцов
    protected $arFields = [
        0 => 'DATETIME',
        1 => 'PATH',
        2 => 'REFERENCE'
    ];

    public function __construct($filePath) {
        $this->filePath = $filePath;

        $moduleId = 'aero.import';

        $this->IBLOCK_CATALOG = COption::GetOptionInt($moduleId, 'iblock_catalog', 0);

        $this->datetime = COption::GetOptionInt($moduleId, 'clear_image_cache_datetime', false);

        $this->catalog = new CAeroIBlockTools($this->IBLOCK_CATALOG);
    }

    public function import() {
        $this->handle = fopen($this->filePath, 'r');

        if($this->handle !== false) {
            while ($arFields = $this->getImageArray()) {
                $iDateTime = $arFields['DATETIME'];
                if($iDateTime > $this->datetime || !$this->datetime) {
                    $sPath = str_replace('\\', '/', $arFields['PATH']);
                    foreach($arFields['REFERENCE'] as $sReference){
                        $arPhotos = $this->catalog->getProductPhotosByReference($sReference);
                        foreach($arPhotos as $arPhoto){
                            if($arPhoto['PATH'] == $sPath){
                                foreach(glob($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/' . $arPhoto['XML_ID'] . '*') as $sFile){
                                    $bDeleted = unlink($sFile);
                                    $this->log(date('d.m.Y H:i:s') . ' - ' . $sFile . ' - ' . ($bDeleted ? 'удалён' : 'ошибка удаления'));
                                }
                            }
                        }
                    }
                }

            }

            fclose($this->handle);
            return true;
        }else{
            return false;
        }

    }

    private function getImageArray(){
        $data = fgets($this->handle);
        if($data) {
            $data = explode('|', $data);
            $arResult = false;

            foreach ($this->arFields as $key => $val) {
                $arResult[$val] = trim($data[$key]);
            }

            $arResult['DATETIME'] = strtotime($arResult['DATETIME']);
            $arResult['REFERENCE'] = explode(',', $arResult['REFERENCE']);

            return $arResult;
        }else{
            return false;
        }

    }

    private function log($mess) {
            @file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/log.txt', $mess."\n", FILE_APPEND);
    }
}