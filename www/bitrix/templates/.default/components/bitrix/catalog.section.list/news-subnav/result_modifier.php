<?
    foreach ($arResult['SECTIONS'] as $k => $section) {
        if ($section['SECTION_PAGE_URL'] == '/news/all/') {
            $arResult['SECTIONS'][$k]['SECTION_PAGE_URL'] = '/news/';
        }
        if ($arParams['LANG'] == 'en') {
        	$arResult['SECTIONS'][$k]['SECTION_PAGE_URL'] = '/en' . $section['SECTION_PAGE_URL'];
        	$arResult['SECTIONS'][$k]['NAME'] = !empty($section['UF_EN_NAME']) ? $section['UF_EN_NAME'] : $section['NAME'];
        }
    }
?>