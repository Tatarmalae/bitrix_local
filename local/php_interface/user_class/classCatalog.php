<?

namespace Dev;

use Bitrix\Catalog\CatalogViewedProductTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockResult;

/**
 * Класс для работы с каталогом
 * Class Catalog
 * @package Dev
 */
class Catalog {

    /**
     * Возвращает значения свойств инфоблока типа "список"
     * @param $iblockID
     * @param $propCode
     * @return array|false
     * @throws LoaderException
     */
    public static function getIblockPropertyEnum($iblockID, $propCode) {
        if (!Loader::includeModule('iblock')) return false;

        $arResult = [];
        $property_enums = \CIBlockPropertyEnum::GetList([
            "SORT" => "ASC",
        ], [
            "IBLOCK_ID" => $iblockID,
            "CODE" => $propCode,
        ]);
        while ($enum_fields = $property_enums->GetNext()) {
            $arResult[] = $enum_fields;
        }
        return $arResult;
    }

    /**
     * Возвращает список разделов в виде дерева
     * @param $iblockID
     * @return bool|mixed
     * @throws LoaderException
     */
    public static function getSectTreeList($iblockID) {
        if (!Loader::includeModule('iblock')) return false;

        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => IntVal($iblockID),
            'GLOBAL_ACTIVE' => 'Y',
        ];
        $arSelect = [
            'IBLOCK_ID',
            'ID',
            'NAME',
            'DEPTH_LEVEL',
            'IBLOCK_SECTION_ID',
        ];
        $arOrder = [
            'DEPTH_LEVEL' => 'ASC',
            'SORT' => 'ASC',
        ];
        $rsSections = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
        $sectionLinc = [];
        $arResult['ROOT'] = [];
        $sectionLinc[0] = &$arResult['ROOT'];
        while ($arSection = $rsSections->GetNext()) {
            $sectionLinc[(int)$arSection['IBLOCK_SECTION_ID']]['CHILD'][$arSection['ID']] = $arSection;
            $sectionLinc[$arSection['ID']] = &$sectionLinc[(int)$arSection['IBLOCK_SECTION_ID']]['CHILD'][$arSection['ID']];
        }
        unset($sectionLinc);
        $arResult['ROOT'] = $arResult['ROOT']['CHILD'];
        return $arResult['ROOT'];
    }

    /**
     * Возвращает информацию о разделе по ID
     * @param $iblockID
     * @param $sectionID
     * @param array $arOrder
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSectionsByID($iblockID, $sectionID, $arOrder = []) {
        if (!Loader::includeModule('iblock')) return false;

        $arRes = [];
        $arFilter = [
            'ACTIVE ' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockID,
            '=ID' => $sectionID,
        ];
        $arSelect = [];
        $rsSect = \CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect, false);
        while ($obSect = $rsSect->GetNext()) {
            $arRes[] = $obSect;
        }
        return $arRes;
    }

    /**
     * Возвращает название раздела по ID
     * @param $iblockID
     * @param $sectionID
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSectionNameByID($iblockID, $sectionID) {
        if (!Loader::includeModule('iblock')) return false;

        $arFilter = [
            'ACTIVE ' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockID,
            '=ID' => $sectionID,
        ];
        $arSelect = ['NAME'];
        $arRes = \CIBlockSection::GetList([], $arFilter, false, $arSelect, false)->Fetch();
        return $arRes['NAME'];
    }

    /**
     * Возвращает название раздела по CODE
     * @param $iblockID
     * @param $code
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSectionNameByCODE($iblockID, $code) {
        if (!Loader::includeModule('iblock')) return false;

        $arFilter = [
            'ACTIVE ' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'IBLOCK_ID' => $iblockID,
            '=CODE' => $code,
        ];
        $arSelect = ['NAME'];
        $arRes = \CIBlockSection::GetList([], $arFilter, false, $arSelect, false)->Fetch();
        return $arRes['NAME'];
    }

    /**
     * Возвращает параметры родительского раздела
     * @param $url
     * @return array|bool
     * @throws LoaderException
     */
    public static function getParentSectParams($url) {
        if (!Loader::includeModule('iblock')) return false;

        $arResult = [];

        $arLinks = array_diff(explode('/', $url), ['']);
        $arLink = array_pop($arLinks);
        if (count($arLinks) > 0) {
            $sectUrl = implode('/', $arLinks);
        } else {
            $sectUrl = $arLink;
        }

        //Получим название раздела
        $sSectionName = '';
        $sPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $sectUrl . '/' . '.section.php';
        @include($sPath);//Теперь в переменной $sSectionName содержится название раздела
        $arResult['NAME'] = $sSectionName;
        $arResult['URL'] = SITE_DIR . $sectUrl . '/';
        $arResult['PARENT_SECT'] = current($arLinks);
        $arSectDiff = array_diff(explode('/', $url), ['']);
        $arResult['CHILD_SECT'] = end($arSectDiff);

        return $arResult;
    }

    /**
     * Возвращает разделы инфоблока с элементами
     * @param $iblockID
     * @param bool $active
     * @param array $params
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSectionList($iblockID, $active = true, $params = []) {
        if (!Loader::includeModule('iblock')) return false;

        $arResult = [];
        $arOrder = ['SORT' => 'ASC'];
        $arFilter = [
            'IBLOCK_ID' => $iblockID,
            "ACTIVE" => "Y",
            "INCLUDE_SUBSECTIONS" => "Y",
        ];
        $arFilter = array_merge($arFilter, $params);
        $arSelect = [];
        $rsSect = \CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect, false);
        while ($arSect = $rsSect->GetNext()) {
            if ($active) {
                if ($arSect['ELEMENT_CNT'] > 0) {
                    $arResult[$arSect['ID']] = $arSect;
                    $arResult[$arSect['ID']]['ITEMS'] = self::getElementList($iblockID, ["IBLOCK_SECTION_ID" => $arSect['ID']]);
                }
            } else {
                $arResult[$arSect['ID']] = $arSect;
                $arResult[$arSect['ID']]['ITEMS'] = self::getElementList($iblockID, ["IBLOCK_SECTION_ID" => $arSect['ID']]);
            }
        }
        return $arResult;
    }

    /**
     * Возвращает подразделы раздела инфоблока
     * Если нет подраздела, выведем соседние из родительского раздела
     * @param $iblockID
     * @param array $params
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSectionIDList($iblockID, $params = []) {
        if (\CModule::IncludeModule('iblock')) {
            $arResult = [];
            $arOrder = ['SORT' => 'ASC'];
            $arFilter = ['IBLOCK_ID' => $iblockID];
            $arFilter = array_merge($arFilter, $params);
            $arSelect = [
                'NAME',
                'ID',
                'CODE',
                'DESCRIPTION',
                'SECTION_PAGE_URL',
            ];
            $rsSect = \CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect, false);
            while ($arSect = $rsSect->GetNext()) {
                $arResult[] = $arSect;
            }
            if (empty($arResult)) {
                $arSect = current(self::getSectionsByID($iblockID, $params['=SECTION_ID']));
                unset($arFilter['=SECTION_ID']);
                $paramsFilter = [
                    'DEPTH_LEVEL' => $arSect['DEPTH_LEVEL'],
                    '=SECTION_ID' => $arSect['IBLOCK_SECTION_ID'],
                ];
                $arFilter = array_merge($arFilter, $paramsFilter);
                $rsSectParent = \CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect, false);
                while ($arSect = $rsSectParent->GetNext()) {
                    $arResult[] = $arSect;
                }
            }
            return $arResult;
        } else {
            return false;
        }
    }

    /**
     * Возвращает информацию о корневом разделе по ID элемента
     * @param $elementId
     * @return bool
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getRootSectionElementById($elementId) {
        if (!Loader::includeModule('iblock')) return false;

        $element = ElementTable::getRow([
            'select' => [
                'ID',
                'IBLOCK_ID',
                'IBLOCK_SECTION_ID',
            ],
            'filter' => [
                '=ID' => $elementId,
            ],
        ]);
        if (!$element || !$element['IBLOCK_SECTION_ID']) return false;

        $currentElementSection = SectionTable::getRow([
            'select' => [
                'ID',
                'CODE',
                'LEFT_MARGIN',
                'RIGHT_MARGIN',
                'DEPTH_LEVEL',
            ],
            'filter' => [
                '=IBLOCK_ID' => $element['IBLOCK_ID'],
                '=ID' => $element['IBLOCK_SECTION_ID'],
            ],
        ]);
        if (!$currentElementSection) return false;
        if ($currentElementSection['DEPTH_LEVEL'] == 1) return $currentElementSection;

        $rootSection = SectionTable::getRow([
            'select' => [
                'ID',
                'CODE',
            ],
            'filter' => [
                '=IBLOCK_ID' => $element['IBLOCK_ID'],
                '<LEFT_MARGIN' => $currentElementSection['LEFT_MARGIN'],
                '>RIGHT_MARGIN' => $currentElementSection['RIGHT_MARGIN'],
                '=DEPTH_LEVEL' => 1,
            ],
        ]);

        return $rootSection;
    }

    /**
     * Возвращает информацию о элементе со свойствами
     * @param $iblockID
     * @param array $arParams
     * @param array $order
     * @param array $navParams
     * @return array|bool
     * @throws LoaderException
     */
    public static function getElementList($iblockID, $arParams = [], $order = [], $navParams = []) {
        if (!Loader::includeModule('iblock')) return false;

        $arResult = [];
        $arOrder = $order;
        $arSelect = [];
        $arFilter = [
            "IBLOCK_ID" => IntVal($iblockID),
            "ACTIVE" => "Y",
        ];
        $arFilter = array_merge($arFilter, $arParams);
        $res = \CIBlockElement::GetList($arOrder, $arFilter, false, $navParams, $arSelect);
        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();
            $arFields['PROPERTIES'] = [];
            $arProps = $ob->GetProperties();

            $arFields['PROPERTIES'] = $arProps;
            array_push($arResult, $arFields);
        }
        return $arResult;
    }

    /**
     * Получим название элемента по ID
     * @param $iblockID
     * @param $elementID
     * @param array $arProps
     * @return array|bool
     * @throws LoaderException
     */
    public static function getElementNameByID($iblockID, $elementID, $arProps = []) {
        if (!Loader::includeModule('iblock')) return false;

        $arSelect = ["NAME"];
        $arFilter = [
            "IBLOCK_ID" => IntVal($iblockID),
            "ID" => $elementID,
            "ACTIVE" => "Y",
        ];
        $arFilter = array_merge($arFilter, $arProps);
        $res = \CIBlockElement::GetList([], $arFilter, $arSelect, false, [])->Fetch();
        return $res['NAME'];
    }

    /**
     * Получим название элемента по XML_ID
     * @param $iblockID
     * @param $elementXML_ID
     * @param array $arProps
     * @return array|bool
     * @throws LoaderException
     */
    public static function getElementNameByXML_ID($iblockID, $elementXML_ID, $arProps = []) {
        if (!Loader::includeModule('iblock')) return false;

        $arSelect = ["NAME"];
        $arFilter = [
            "IBLOCK_ID" => IntVal($iblockID),
            "XML_ID" => $elementXML_ID,
            "ACTIVE" => "Y",
        ];
        $arFilter = array_merge($arFilter, $arProps);
        $res = \CIBlockElement::GetList([], $arFilter, $arSelect, false, [])->Fetch();
        return $res['NAME'];
    }

    /**
     * Возвращает список просмотренных элементов
     * @return array
     * @throws ArgumentException
     */
    public static function getViewedProduct() {
        $arResult = [];
        $basketUserId = (int)\CSaleBasket::GetBasketUserID(false);
        if ($basketUserId > 0) {
            $viewedIterator = CatalogViewedProductTable::getList([
                'select' => [
                    'PRODUCT_ID',
                    'ELEMENT_ID',
                ],
                'filter' => [
                    '=FUSER_ID' => $basketUserId,
                    '=SITE_ID' => SITE_ID,
                ],
                'order' => ['DATE_VISIT' => 'DESC'],
                'limit' => 10,
            ]);
            while ($arFields = $viewedIterator->Fetch()) {
                $arFilter = [
                    "ID" => $arFields['ELEMENT_ID'],
                ];
                $arOrder = [];
                $res = \CIBlockElement::GetList([], $arFilter, false, ["nTopCount" => 1], $arOrder);
                while ($arRes = $res->GetNext()) {
                    if (mb_strpos($arRes['DETAIL_PAGE_URL'], 'ostalnoe')) continue;
                    $arResult[] = $arRes['ID'];
                }
            }
        }
        return $arResult;
    }

    /**
     * Возвращает поисковую статистику
     * @param int $nPageSize
     * @return array|bool
     * @throws LoaderException
     */
    public static function getSearchStatistic($nPageSize = 5) {
        if (!Loader::includeModule('search')) return false;

        $arResult = [];
        $arOrder = ['COUNT' => 'DESC'];
        $arFilter = [
            'PAGES' => 1,
            '>RESULT_COUNT' => 0,
        ];
        $arSelect = [
            'PHRASE',
            'RESULT_COUNT',
        ];
        $bGroup = ['PHRASE'];
        $dbStatistic = \CSearchStatistic::GetList($arOrder, $arFilter, $arSelect, $bGroup);
        $dbStatistic->NavStart($nPageSize);
        while ($arStatistic = $dbStatistic->Fetch()) {
            $arResult[] = $arStatistic;
        }
        return $arResult;
    }

    /**
     * Вывод алфавита из списка элементов
     * @param $iblockID
     * @param bool $cache
     * @return array|bool
     * @throws LoaderException
     */
    public static function getBrandsListAlphabet($iblockID, $cache = true) {
        $obCache = new \CPHPCache();
        if ($obCache->InitCache($cache ? Settings::CACHE_TIME : 0, "el_list_alphabet", "alphabet")) {
            $arResult = $obCache->GetVars();
        } else {
            $arResult = [];
            if (!Loader::includeModule('iblock')) $arResult = false;

            $arSort = ['NAME' => 'ASC'];
            $arSelect = [
                "ID",
                "IBLOCK_ID",
                "NAME",
            ];
            $arFilter = [
                "IBLOCK_ID" => IntVal($iblockID),
                "ACTIVE" => "Y",
                "INCLUDE_SUBSECTIONS" => "Y",
            ];
            $res = \CIBlockElement::GetList($arSort, $arFilter, false, false, $arSelect);
            while ($ob = $res->Fetch()) {
                $arResult[] = mb_strtoupper(mb_substr($ob['NAME'], 0, 1));
            }
            $arResult = array_unique($arResult);

        }
        if ($obCache->StartDataCache()) {
            $obCache->EndDataCache($arResult);
        }
        return $arResult;
    }

    /**
     * Возвращает сгруппированный массив по первой букве названия
     * @param $arResult
     * @param $iblockID
     * @param bool $cache
     * @return array
     */
    public static function getGroupFirstLetter($arResult, $iblockID, $cache = true) {
        $obCache = new \CPHPCache();
        if ($obCache->InitCache($cache ? Settings::CACHE_TIME : 0, "el_list_alphabet", "alphabet")) {
            $groups = $obCache->GetVars();
        } else {
            $groups = [];
            foreach ($arResult as $key => $arItem) {

                /*Проверим есть ли товары с данным брендом*/
                $arFilter = [
                    "IBLOCK_ID" => IntVal($iblockID),
                    "ACTIVE" => "Y",
                    'PROPERTY_BRAND' => $arItem['NAME'],
                ];
                $arCheck = \CIBlockElement::GetList(["SORT" => "ASC"], $arFilter, [], false, false);
                if ($arCheck > 0) {
                    $arItem['USE_PROD'] = 'Y';
                }

                $firstLetter = mb_strtoupper($arItem['NAME'][0]);
                $groups[$firstLetter][] = $arItem;
            }
        }
        if ($obCache->StartDataCache()) {
            $obCache->EndDataCache($groups);
        }
        return $groups;
    }

    /**
     * Определим детальную каталога
     * @param $iblockID
     * @return CIBlockResult
     */
    public static function isDetail($iblockID) {
        global $APPLICATION;
        $array = array_filter(explode('/', $APPLICATION->GetCurDir()));
        $code = array_pop($array);
        $arFilter = [
            "IBLOCK_ID" => IntVal($iblockID),
            "=CODE" => $code,
        ];
        return \CIBlockElement::GetList([], $arFilter, [], [], []);
    }

    /**
     * Возвращает рейтинг элемента по CODE и количество проголосовавших
     * @param $iblockID
     * @param $code
     * @return bool
     * @throws LoaderException
     */
    public static function getRatingExler($iblockID, $code) {
        if (!Loader::includeModule('iblock')) return false;

        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $iblockID,
            "PROPERTY_ID.CODE" => $code,
        ];
        $arOrder = [
            'ID',
            'CODE',
            'XML_ID',
            'NAME',
            'PROPERTY_RATING',
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, [], $arOrder);
        $sum = '';
        $count = 0;
        while ($el = $res->Fetch()) {
            $count++;
            $sum += $el['PROPERTY_RATING_VALUE'];
        }
        if ($count > 0) {
            $rating = ceil(($sum / $count));
            $arResult['VOTE_RATING'] = $rating;
            $arResult['VOTE_COUNT'] = $count;
        } else {
            $arResult['VOTE_RATING'] = 0;
            $arResult['VOTE_COUNT'] = 0;
        }
        return $arResult;
    }

    /**
     * Возвращает отзывы элемента по CODE
     * @param $code
     * @return array|bool
     * @throws LoaderException
     */
    public static function getReviewsElem($iblockID, $code) {
        if (!Loader::includeModule('iblock')) return false;

        $arResult = [];
        $arFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $iblockID,
            "PROPERTY_ID.CODE" => $code,
        ];
        $arOrder = [
            'DATE_ACTIVE_FROM',
            'PROPERTY_RATING',
            'PROPERTY_NAME',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
        ];
        $res = \CIBlockElement::GetList([], $arFilter, false, [], $arOrder);
        while ($el = $res->Fetch()) {
            $arResult[] = $el;
        }
        return $arResult;
    }

}