<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 16.01.2015
 * Time: 17:31
 */
namespace aero;

include_once "docgen.php";

class CDeliveryDocGenerator {
    static $templateFolder;
    static $tmpDir;

    protected static function init(){
        static::$templateFolder = $_SERVER['DOCUMENT_ROOT'] . '/include/deilvery_blanks';
        static::$tmpDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/delivery_docs';
    }

    public static function generateDoc($arFields, $templateName){
        static::init();

        $templateFile = static::getBlank($templateName);

        $tmpDir = date('d_m_Y_H_i_s') . rand(10, 20);
        $dstDir = static::$tmpDir . '/tmp/' . $tmpDir;
        $template = static::$templateFolder . '/' . $templateFile;

        CDir::checkDir($dstDir);

        if(CZip::extract($template, $dstDir)){
            $docPath = $dstDir . '/word/document.xml';
            static::replaceVars($docPath, $arFields, $templateName);
            $fileName = 'delivery_' . date('d_m_Y_H_i_s') . '.docx';
            if(!CZip::addToArchive($dstDir, static::$tmpDir . '/' . $fileName)){
                CDir::deleteDir($dstDir);
                throw new \Exception('Ошибка запаковки архива');
            }
        }else{
            CDir::deleteDir($dstDir);
            throw new \Exception('Ошибка распаковки архива');
        }

        CDir::deleteDir($dstDir);

        return static::$tmpDir . '/' . $fileName;
    }

    protected static function replaceVars($docPath, $arFields, $type){
        switch($type){
            case 'private_delivery':
                static::replacePrivateVars($docPath, $arFields);
                break;

            case 'third_party_delivery':
                static::replaceThirdPartyVars($docPath, $arFields);
                break;

            case 'available_delivery':
                static::replaceAvailableVars($docPath, $arFields);
                break;

            case 'selection_delivery':
                static::replaceSelectionVars($docPath, $arFields);
                break;

            default:
                throw new \Exception('Неизвестный тип доставки');
                break;
        }
    }

    private static function replacePrivateVars($docPath, $arFields){

        $arReplace = array(
            '#FIO#' => $arFields['FIO'],
            '#EMAIL#' => $arFields['EMAIL'],
            '#PHONE#' => $arFields['PHONE'],
            '#FROM#' => $arFields['WORK_HOURS_FROM'],
            '#TO#' => $arFields['WORK_HOURS_TO'],
            '#BILLS#' => implode(", ", $arFields['BILLS']),
            '#ADDRESS#' => $arFields['DELIVERY_ADDRESS'],
            '#ROUTE_EXIST#' => isset($arFields['ROUTE_FILE']) ? ' схема прилагается' : '',
            '#SEND_ALL#' => $arFields['SEND_ALL'] ? 'по полной комплектации' : 'из наличия',
        );

        $content = file_get_contents($docPath);
        $content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

        file_put_contents($docPath, $content);
    }

    private static function replaceSelectionVars($docPath, $arFields){

        $arReplace = array(
            '#ORG_NAME#' => $arFields['ORG_NAME'],
            '#CONTACTS#' => implode(", ", array(
                $arFields['FIO'],
                $arFields['PHONE'],
                $arFields['EMAIL'],
            )),
            '#CONTACT_ADDRESS#' => $arFields['CONTACT_ADDRESS'],
            '#PICKUP_DATE#' => $arFields['PICKUP_DATE'],
            '#BILLS#' => implode(", ", $arFields['BILLS']),
            '#INN#' => $arFields['INN'],
            '#KPP#' => $arFields['KPP'],
            '#SEND_ALL#' => $arFields['SEND_ALL'] ? 'по полной комплектации' : 'из наличия',
        );

        $content = file_get_contents($docPath);
        $content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

        file_put_contents($docPath, $content);
    }

    private static function replaceThirdPartyVars($docPath, $arFields){

        $arReplace = array(
            '#ORG_NAME#' => $arFields['ORG_NAME'],
            '#CONTACTS#' => implode(", ", array(
                $arFields['FIO'],
                $arFields['PHONE'],
                $arFields['EMAIL'],
            )),
            '#BILLS#' => implode(", ", $arFields['BILLS']),
            '#RECEIVER_ORG#' => $arFields['RECEIVER_ORG'],
            '#TK#' => $arFields['TK'],
            '#DELIVERY_ADDRESS#' => $arFields['DELIVERY_ADDRESS'],
            '#RECEIVER_INN#' => $arFields['RECEIVER_INN'],
            '#RECEIVER_KPP#' => $arFields['RECEIVER_KPP'],
            '#RECEIVER_OKPO#' => $arFields['RECEIVER_OKPO'],
            '#DELIVERY_POINT#' => $arFields['DELIVERY_POINT'],
            '#RECEIVER_PHONE#' => implode(", ", $arFields['RECEIVER_PHONE']),
            '#SEND_DOCUMENTS_ADDRESS#' => $arFields['SEND_DOCUMENTS_ADDRESS'],
            '#PAYER_ORG#' => $arFields['PAYER_ORG'],
            '#PAYER_INN#' => $arFields['PAYER_INN'],
            '#PAYER_KPP#' => $arFields['PAYER_KPP'],
            '#PAYER_PHONE#' => implode(", ", $arFields['PAYER_PHONE']),
        );

        $content = file_get_contents($docPath);
        $content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

        file_put_contents($docPath, $content);
    }

    private static function replaceAvailableVars($docPath, $arFields){

        $arReplace = array(
            '#ORG_NAME#' => $arFields['ORG_NAME'],
            '#CONTACT_ADDRESS#' => $arFields['CONTACT_ADDRESS'],
            '#CONTACTS#' => implode(", ", array(
                $arFields['FIO'],
                $arFields['PHONE'],
                $arFields['EMAIL'],
            )),
            '#BILLS#' => implode(", ", $arFields['BILLS']),
            '#INN#' => $arFields['INN'],
            '#KPP#' => $arFields['KPP'],
            '#TK#' => $arFields['TK'],
        );

        $content = file_get_contents($docPath);
        $content = str_replace(array_keys($arReplace), array_values($arReplace), $content);

        file_put_contents($docPath, $content);
    }

    protected function getBlank($type){
        switch($type){
            case "private_delivery" :
                $template = "private_delivery.docx";
                break;
            case "third_party_delivery" :
                $template = "third_party_delivery.docx";
                break;
            case "available_delivery" :
                $template = "available_delivery.docx";
                break;

            case "selection_delivery" :
                $template = "selection_delivery.docx";
                break;

            default:
                break;
        }
        return $template;
    }

}