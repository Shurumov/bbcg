<?
global $newsFilter;
$newsFilter = ['PROPERTY_SUMMIT' => $arResult['ID']];
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "news-block",
    Array(
        "FILTER_NAME" => "newsFilter",
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(),
        "IBLOCK_ID" => NEWS_IBLOCK,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "NEWS_COUNT" => "30",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "main",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PROPERTY_CODE" => array("*"),
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "TITLE" => $arResult['NEWS_TITLE']['title'],
        "SUBTITLE" => $arResult['NEWS_TITLE']['subtitle'],
        "INDEX_PAGE_URL" => $arResult['NEWS_TITLE']['link'],
        "LANG" => $arParams['LANG'],
    )
);?>