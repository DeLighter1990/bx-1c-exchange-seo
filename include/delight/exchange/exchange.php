<?php

namespace Delight;

use Bitrix\Iblock\PropertyTable;

class Exchange
{
    const PROP_CODE_ALIAS = "SEO_KEYWORD";                  // Код свойства, содержащий символьный код элемента
    const PROP_CODE_META_TITLE = "META_TITLE";              // Код свойства, содержащий meta:title
    const PROP_CODE_META_DESC = "META_DESCRIPTION";         // Код свойства, содержащий meta:description
    const PROP_CODE_META_KEYWORDS = "META_KEYWORDS";        // Код свойства, содержащий meta:keywords
    const DB_TTL = 86400;                                   // Время кеширования запросов к БД

    public function OnBeforeIBlockElementAddAndUpdateHandler(array &$arFields)
    {
        // Запись мета-данных из свойств в SEO-блок
        $rsProperty = PropertyTable::getList(array(
            'filter' => array(
                'IBLOCK_ID' => $arFields["IBLOCK_ID"],
                'CODE'      => array(
                    self::PROP_CODE_ALIAS,
                    self::PROP_CODE_META_TITLE,
                    self::PROP_CODE_META_DESC,
                    self::PROP_CODE_META_KEYWORDS
                )
            ),
            'select' => array('ID', 'CODE'),
            'cache'  => array(
                'ttl' => self::DB_TTL,
            )
        ));
        while ($arProperty = $rsProperty->fetch()) {
            switch ($arProperty["CODE"]) {
                case self::PROP_CODE_ALIAS:
                    if ( ! empty($arFields["PROPERTY_VALUES"][$arProperty["ID"]][array_key_first($arFields["PROPERTY_VALUES"][$arProperty["ID"]])]["VALUE"])) {
                        $arFields["CODE"] = $arFields["PROPERTY_VALUES"][$arProperty["ID"]][array_key_first($arFields["PROPERTY_VALUES"][$arProperty["ID"]])]["VALUE"];
                    } elseif (isset($arFields["NAME"])) {
                        $arFields["CODE"] = \Cutil::translit(trim($arFields["NAME"]), "ru", array("replace_space" => "-", "replace_other" => "-"));
                    }
                    break;
                case self::PROP_CODE_META_TITLE:
                    $arFields["IPROPERTY_TEMPLATES"]["ELEMENT_META_TITLE"] = $arFields["PROPERTY_VALUES"][$arProperty["ID"]][array_key_first($arFields["PROPERTY_VALUES"][$arProperty["ID"]])]["VALUE"];
                    break;
                case self::PROP_CODE_META_DESC:
                    $arFields["IPROPERTY_TEMPLATES"]["ELEMENT_META_DESCRIPTION"] = $arFields["PROPERTY_VALUES"][$arProperty["ID"]][array_key_first($arFields["PROPERTY_VALUES"][$arProperty["ID"]])]["VALUE"];
                    break;
                case self::PROP_CODE_META_KEYWORDS:
                    $arFields["IPROPERTY_TEMPLATES"]["ELEMENT_META_KEYWORDS"] = $arFields["PROPERTY_VALUES"][$arProperty["ID"]][array_key_first($arFields["PROPERTY_VALUES"][$arProperty["ID"]])]["VALUE"];
                    break;
            }
        }
    }
}