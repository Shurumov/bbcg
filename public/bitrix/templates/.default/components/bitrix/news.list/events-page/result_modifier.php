<?
    define('EVENTS_STEP', 10);
    CModule::IncludeModule('iblock');

    //Gets areas
    $arResult['AREAS'] = $areaSequence = [];

    $res = CIblockElement::GetList(
        ['SORT' => 'ASC'],
        ['IBLOCK_ID' => AREAS_IBLOCK, 'ACTIVE' => 'Y'],
        false,
        false,
        ['ID', 'NAME']
    );

    while ($area = $res->Fetch())
    {
        if (empty($arResult['AREAS'])){
            $arResult['AREAS'][$area['ID']] = ['NAME' => $area['NAME'], 'ITEMS' => [], 'FIRST' => true];
        } else {
            $arResult['AREAS'][$area['ID']] = ['NAME' => $area['NAME'], 'ITEMS' => []];
        }

        $areaSequence[] = $area['ID'];
    }

    $arParams['CELL_WIDTH'] = 600;

    $arResult['DAY'] = FormatDate('j', MakeTimeStamp($arParams['DATE'], "DD.MM.YYYY"));
    $arResult['MONTH'] = FormatDate('F', MakeTimeStamp($arParams['DATE'], "DD.MM.YYYY"));

    //Sets first & last hours
    $arResult['FIRST_HOUR'] = 24;
    $arResult['LAST_HOUR'] = 0;
    foreach ($arResult['ITEMS'] as $item) {
        $timeBegin = new DateTime($item['PROPERTIES']['BEGIN']['VALUE']);
        $timeEnd = new DateTime($item['PROPERTIES']['END']['VALUE']);
        if ((integer) $timeBegin->format('G') < $arResult['FIRST_HOUR']) {
            $arResult['FIRST_HOUR'] = (integer) $timeBegin->format('G');
        }
        if ((integer) $timeEnd->format('G') > $arResult['LAST_HOUR']) {
            $arResult['LAST_HOUR'] = (integer) $timeEnd->format('G');
        }
    }

    //Generates left hours line
    $currentHour = $arResult['FIRST_HOUR'];
    $arResult['HOURS'] = [];
    do {
        $arResult['HOURS'][$currentHour] = [
            'steps' => []
        ];
        $minutes = '00';
        do {
            $arResult['HOURS'][$currentHour]['steps'][] = "$currentHour:$minutes";
            $minutes += EVENTS_STEP;
        } while ((integer) $minutes < 60);
        $currentHour++;
    } while ($currentHour < $arResult['LAST_HOUR'] && $currentHour < 24);

    //Sets additional fields for events & puts event into area pull
    foreach ($arResult['ITEMS'] as $item) {
        $begin = new DateTime($item['PROPERTIES']['BEGIN']['VALUE']);
        $item['begin'] = $begin->format('H:i');
        $end = new DateTime($item['PROPERTIES']['END']['VALUE']);
        $item['end'] = $end->format('H:i');

        $item['minutes'] = abs($end->getTimestamp() - $begin->getTimestamp()) / 60;

        if ($area = $item['PROPERTIES']['AREA']['VALUE'][0]) {
            if (count($item['PROPERTIES']['AREA']['VALUE']) > 1) {
                $areaIndex = 0;
                foreach ($areaSequence as $k => $v) {
                    if ($v == $area) {
                        $areaIndex = $k;
                    }
                }
                $eventWidht = 0;
                for ($j = $areaIndex; $j < count($areaSequence); $j++) {
                    if (in_array($areaSequence[$j], $item['PROPERTIES']['AREA']['VALUE'])) {
                        $eventWidht++;
                    }
                }
                $item['width'] = $eventWidht;
            }
            $arResult['AREAS'][$area]['ITEMS'][] = $item;
        } else {
            $arResult['GLOBALS']['ITEMS'][] = $item;
        }
    }

    //Generates an empty timeline
    $counter = 0;
    $arResult['TIMELINE'] = [];
    for ($i = $arResult['FIRST_HOUR']; $i <= $arResult['LAST_HOUR']; $i++) {
        $arResult['TIMELINE'][$counter++] = [];
    }

    //Fills the timeline with global events
    foreach ($arResult['GLOBALS']['ITEMS'] as $j => $item) {
        $begin = new DateTime($item['PROPERTIES']['BEGIN']['VALUE']);
        $end = new DateTime($item['PROPERTIES']['END']['VALUE']);

        $morning = new DateTime($arParams['DATE']);
        $morning->modify("+" . $arResult['FIRST_HOUR'] . " hours");
        $offset = $begin->format('i');
        $begin->modify('-' . $offset . ' minutes');

        $counter = 0;
        while ($morning < $begin)
        {
            $counter++;
            $morning->modify('+1 hour');
        }

        $arResult['TIMELINE'][$counter]['GLOBALS'][] = [
            'id' => $item['ID'],
            'name' => $item['NAME'],
            'description' => $item['PREVIEW_TEXT'],
            'duration' => $item['minutes'],
            'offset' => $offset,
            'begin' => $item['begin'],
            'end' => $item['end'],
            'url' => $item['DETAIL_PAGE_URL'],
            'speakers' => $eventSpeakers,
            'width' => $item['width'],
            'color' => $item['color'],
            'open' => $item['PROPERTIES']['noopen']['VALUE'] != 'Y',
            'subtitle' => $item['subtitle']
        ];
    }

    //Fills the timeline with areas-based events
    foreach ($arResult['AREAS'] as $k => $area) {

        foreach ($area['ITEMS'] as $j => $item) {
            $begin = new DateTime($item['PROPERTIES']['BEGIN']['VALUE']);
            $end = new DateTime($item['PROPERTIES']['END']['VALUE']);

            $morning = new DateTime($arParams['DATE']);
            $morning->modify("+" . $arResult['FIRST_HOUR'] . " hours");
            $offset = $begin->format('i');
            $begin->modify('-' . $offset . ' minutes');

            $counter = 0;
            while ($morning < $begin)
            {
                $counter++;
                $morning->modify('+1 hour');
            }

            $arResult['TIMELINE'][$counter][$k][] = [
                'id' => $item['ID'],
                'name' => $item['NAME'],
                'description' => $item['PREVIEW_TEXT'],
                'duration' => $item['minutes'],
                'offset' => $offset,
                'begin' => $item['begin'],
                'end' => $item['end'],
                'url' => $item['DETAIL_PAGE_URL'],
                'speakers' => $eventSpeakers,
                'width' => $item['width'],
                'color' => $item['color'],
                'open' => $item['PROPERTIES']['noopen']['VALUE'] != 'Y',
                'subtitle' => $item['subtitle']
            ];
        }

        if (! count($area['ITEMS'])) {
            unset($arResult['AREAS'][$k]);
        }
    }
?>