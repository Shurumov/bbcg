<?
    CModule::IncludeModule('iblock');

    if (isset($arResult['PROPERTIES']['AREA']['VALUE'][0])) {
	    $res = CIBlockElement::GetList(
	    	['ID' => 'ASC'],
	    	['IBLOCK_ID' => AREAS_IBLOCK, 'ID' => $arResult['PROPERTIES']['AREA']['VALUE'][0]],
	    	false,
	    	false,
	    	['ID', 'NAME', 'PROPERTY_EN_NAME']
	    );
	    $area = $res->Fetch();
	    if ($arParams['LANG'] == 'en') {
	    	$arResult['AREA'] = !empty($area['PROPERTY_EN_NAME_VALUE']) ? $area['PROPERTY_EN_NAME_VALUE'] : $area['NAME'];
	    } else {
	    	$arResult['AREA'] = $area['NAME'];
	    }
	} else {
		$arResult['AREA'] = null;
	}

	if (is_array($arResult['PROPERTIES']['SPEAKERS']['VALUE']) && count($arResult['PROPERTIES']['SPEAKERS']['VALUE'])) {
		$res = CIBlockElement::GetList(
			['SORT' => 'ASC'],
			['IBLOCK_ID' => SPEAKERS_IBLOCK, 'ID' => $arResult['PROPERTIES']['SPEAKERS']['VALUE'], 'ACTIVE' => 'Y'],
			false,
			false,
			['ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_EN_NAME', 'PROPERTY_EN_PREVIEW_TEXT']
		);
		while ($speaker = $res->GetNext()) {
			if ($arParams['LANG'] == 'en') {
				$speaker['NAME'] = (!empty($speaker['PROPERTY_EN_NAME_VALUE'])) ? $speaker['PROPERTY_EN_NAME_VALUE'] : $speaker['NAME'];
				$speaker['PREVIEW_TEXT'] = (!empty($speaker['PROPERTY_EN_PREVIEW_TEXT_VALUE'])) ? $speaker['PROPERTY_EN_PREVIEW_TEXT_VALUE']['TEXT'] : $speaker['PREVIEW_TEXT'];
				$speaker['DETAIL_PAGE_URL'] = '/en' . $speaker['DETAIL_PAGE_URL'];
			}

			if (empty($speaker['PREVIEW_PICTURE'])) {
				$exploded = explode(' ', $speaker['NAME']);
				if (is_array($exploded) && count($exploded) == 2) {
					$speaker['LETTERS'] = mb_substr($exploded[0], 0, 1) . mb_substr($exploded[1], 0, 1);
				} else {
					$speaker['LETTERS'] = '?';
				}
				$speaker['COLOR'] = $colors[array_rand($colors)];
			}

		    $arResult['SPEAKERS'][] = $speaker;
		}
	}

	if ($arParams['LANG'] == 'en') {
		$arResult['REGISTRATION_URL'] = '/en/include/academy/modal-registration.php?id=' . $arResult['ID'];
        $arResult['NAME'] = !empty($arResult['PROPERTIES']['EN_NAME']['VALUE']) ? $arResult['PROPERTIES']['EN_NAME']['VALUE'] : $arResult['NAME']; 
        $arResult['~DETAIL_TEXT'] = !empty($arResult['PROPERTIES']['EN_DETAIL_TEXT']['~VALUE']['TEXT']) 
        	? $arResult['PROPERTIES']['EN_DETAIL_TEXT']['~VALUE']['TEXT'] 
        	: $arResult['~DETAIL_TEXT'];
        $arResult['DATE'] = mb_strtolower(PHPFormatDateTime($arResult["ACTIVE_FROM"], 'j F'));
	} else {
		$arResult['REGISTRATION_URL'] = '/include/academy/modal-registration.php?id=' . $arResult['ID'];
		$arResult['DATE'] = mb_strtolower(FormatDate('j F', MakeTimeStamp($arResult["ACTIVE_FROM"], "DD.MM.YYYY HH:MI:SS")));
	}
?>