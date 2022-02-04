<?php

if (file_exists(Bitrix\Main\Application::getDocumentRoot() . '/local/php_interface/include/delight.php')) {
    require_once(Bitrix\Main\Application::getDocumentRoot() . '/local/php_interface/include/delight.php');
}

// Events
$obDelightExchange = new \Delight\Exchange();
Bitrix\Main\EventManager::getInstance()->addEventHandler("iblock", "OnBeforeIBlockElementAdd", Array($obDelightExchange, "OnBeforeIBlockElementAddAndUpdateHandler"));
Bitrix\Main\EventManager::getInstance()->addEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array($obDelightExchange, "OnBeforeIBlockElementAddAndUpdateHandler"));
