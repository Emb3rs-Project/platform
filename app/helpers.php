<?php

if (!function_exists('closetags')) {
    function closetags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i ++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}

if (!function_exists('cleanCharacterization')) {
    function cleanCharacterization($collection, $withStreamsCount = false)
    {
        return $collection->map(function ($i) use ($withStreamsCount) {
            if (Arr::has($i['values'], 'characterization')) {
                $values = $i['values'];
                if ($withStreamsCount && Arr::has($i['values']['characterization'], 'streams')) {
                    $values['streams_length'] = count($values['characterization']['streams']);
                }
                $values['characterization'] = null;
                $i['values'] = $values;
            }
            return $i;
        });
    }
}
