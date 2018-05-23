<?php

define ('SALE_PERSON_FIZ', 1); // тип плательщика физ. лицо
define ('SALE_PERSON_YUR', 2); // тип плательщика юр. лицо
define ('SALE_PERSON_IP', 3); // тип плательщика ИП

define ('WF_QUICK_BASKET', 1); // ИД вебформы заказ в один клик в корзине
define ('WF_CALLBACK', 2); // ИД вебформы обратный звонок
define ('WF_QUICK_PRODUCT', 3); // ИД вебформы заказ в один клик в карточке товара
define ('WF_VACANCY_CB', 4); // ИД вебформы отклика на вакансию
define ('WF_SETTLEMENT_CB', 5); // ИД вебформы заявки на рассчет оборудования для НКУ

define('PROP_IS_OWN_Y_ID', 34819); // ID значения св-ва "собственное производство"

define ('SUBSCIPTION_ON_NEW_GOODS', 1);

define ('IBLOCK_ID_PUBLICATIONS', 3); // ИБ публикации

define ('IBLOCK_ID_BANNERS_MAIN', 34); // Баннеры на главной

define ('IBLOCK_ID_HELP', 30);

define ('IBLOCK_ID_INVOICES', 22); // ИБ счета
define ('IBLOCK_ID_SALES', 23); // ИБ реализация товаров
define ('IBLOCK_ID_PAYMENTS', 24); // ИБ платежные поручения
define ('IBLOCK_ID_CARGO', 25); // ИБ отправка груза
define ('IBLOCK_ID_CONTRACTS', 26); // ИБ договоры контрагентов
define ('IBLOCK_ID_AGENTS', 27); // ИБ контрагенты
define ('IBLOCK_ID_MESSAGES', 28); // ИБ события
define ('IBLOCK_ID_DELIVERY_ZONES', 29); // ИБ зоны доставки
define('IBLOCK_ID_DELIVERY_COMPANIES', CIBlockTools::GetIBlockId('tk-delivery')); // ИБ транспортные компании
define ('IBLOCK_ID_COMPANIES', 35); // ИБ юридические лица
define ('IBLOCK_ID_OFFICES', 36); // ИБ офисы
define ('IBLOCK_ID_DEPARTMENTS', 37); // ИБ отделы
define ('IBLOCK_ID_EMPLOYEES', 38); // ИБ Сотрудники
define ('IBLOCK_ID_EVENTS', 39); // ИБ о компании -> события
define ('IBLOCK_ID_BRANDS', 20); // ИБ Производители
define ('IBLOCK_ID_TRACKING', 40); // ИБ Отслеживание товаров
define ('IBLOCK_ID_RECLAMATIONS', 42); // ИБ Рекламации
define ('IBLOCK_ID_PRICELIST', 41); // ИБ Прайс-листы
define ('IBLOCK_ID_MAIN_BLOCK', 43); // ИБ Блок на главной о компании
define ('IBLOCK_ID_VACANCIES', 44); // ИБ Вакансии
define ('IBLOCK_ID_ADVICES', 46); // ИБ Советы
define ('IBLOCK_ID_COMMENTS', 45); // ИБ Комментарии
define('IBLOCK_ID_ESHOP', 48); // ИБ Интернет-магазин
define('IBLOCK_ID_ABOUT', 47); // ИБ О нас
define('IBLOCK_ID_USER_HELP', 46); // ИБ Помощь пользователям
define('IBLOCK_ID_SITE_SETTINGS', 51); // ИБ Настройки сайта
define('IBLOCK_ID_DELIVERY', CIBlockTools::GetIBlockId('delivery')); // ИБ Доставка в заказа
define('IBLOCK_ID_REFUNDS', CIBlockTools::GetIBlockId('REFUNDS')); // ИБ Возвраты по реализациям в заказах
define('STATIC_IB_ID', CIBlockTools::GetIBlockId('static-blocks')); // ИБ для различных надписей и статических страниц
define('REALIZATION_STATUS_DICTIONARY', 1); // Справочник статусов реализации
