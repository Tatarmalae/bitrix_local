<?

namespace Dev;

/**
 * Класс вспомогательных функций
 * Class Utilities
 */
class Utilities {

    /**
     * Выводит содержимое переменной на экран в ЧП виде
     * @param $ext
     * @param bool $bool
     */
    public static function DB($ext, $bool = false) {
        echo '<pre>';
        if ($bool) {
            var_dump($ext);
        } else {
            print_r($ext);
        }
        echo '</pre>';
    }

    /**
     * Выводит содержимое переменной на экран в ЧП виде, но только при указании REQUEST запроса
     * @param $ext
     * @param bool $bool
     */
    public static function DH($ext, $bool = false) {
        if ($_REQUEST['D'] == 'Y') {
            echo '<pre>';
            if ($bool) {
                var_dump($ext);
            } else {
                print_r($ext);
            }
            echo '</pre>';
        }
    }

    /**
     * Склонятор слов  число, слова - "минута", "минуты", "минут"
     * @param $number
     * @param $suffix
     * @return mixed
     */
    public static function getWord($number, $suffix) {
        $keys = array(
            2,
            0,
            1,
            1,
            1,
            2
        );
        $mod = $number % 100;
        $suffix_key = ($mod > 7 && $mod < 20) ? 2 : $keys[min($mod % 10, 5)];
        return $suffix[$suffix_key];
    }

    /**
     * Производит поиск в ассоциативном массиве по ключу и его значению
     * @param $array
     * @param $field
     * @param $value
     * @return array|bool
     */
    public static function searchInArray($array, $field, $value) {
        $arSelected = array_filter($array, function ($a) use ($field, $value) {
            return $a[$field] == $value;
        });
        if (count($arSelected) > 0) {
            return current($arSelected);
        } else {
            return false;
        }
    }

    /**
     * Переводит массив из линейного вида в лестничный
     * линейный массив - массив меню bitrix
     * лестничный - массив с вложенностями
     * @param $array
     * @return array
     */
    public static function getMultilevelArray($array) {
        $maxLevel = 0;
        $minLevel = 9999;
        foreach ($array as $arItem) {
            if ($arItem['DEPTH_LEVEL'] > $maxLevel) {
                $maxLevel = $arItem['DEPTH_LEVEL'];
            }
            if ($arItem['DEPTH_LEVEL'] < $minLevel) {
                $minLevel = $arItem['DEPTH_LEVEL'];
            }
        }

        $array = array_reverse($array);
        while ($maxLevel >= $minLevel) {
            foreach ($array as $keyItem => $arItem) {
                $currentLevel = $arItem['DEPTH_LEVEL'];
                if ($arItem['DEPTH_LEVEL'] == $maxLevel) {
                    $arTemp[] = $array[$keyItem];
                    if ($arItem['DEPTH_LEVEL'] > $minLevel) {
                        unset($array[$keyItem]);
                    }
                }
                if ($currentLevel < $prevLevel && count($arTemp) > 0) {
                    $array[$keyItem]['ITEMS'] = array_reverse($arTemp);
                    $arTemp = array();
                }
                $prevLevel = $currentLevel;
            }
            $maxLevel--;
        }
        $array = array_reverse($array);
        return $array;
    }

    /**
     * Рекурсивно переворачивает массив
     * @param $arr
     * @return array
     */
    public static function arrayReverseRecursive($arr) {
        foreach ($arr as $key => $val) {
            if (is_array($val))
                $arr[$key] = self::arrayReverseRecursive($val);
        }
        return array_reverse($arr);
    }

    /**
     * рекурсивно удаляет директорию с файлами и папками
     * @param $folder
     */
    public function removeFolder($folder) {
        if ($files = glob($folder . "/*")) {
            foreach ($files as $file) {
                if (is_dir($file)) {
                    $this->removeFolder($file);
                } else {
                    unlink($file);
                }
            }
        }
        rmdir($folder);
    }

    /**
     * Возвращает отформатированную дату, если год начала равен году окончания статьи
     * @param $dateFrom (DD.MM.YYY)
     * @param $dateTo (DD.MM.YYY)
     * @return mixed
     */
    public static function getFormatDateActions($dateFrom, $dateTo) {
        $parseDateFrom = explode('.', $dateFrom);
        $yearFrom = $parseDateFrom[2];
        $parseDateTo = explode('.', $dateTo);
        $yearTo = $parseDateTo[2];
        if ($yearFrom == $yearTo) {
            $dateFromFormat = FormatDateFromDB($dateFrom, "j F");
        } else {
            $dateFromFormat = FormatDateFromDB($dateFrom, "j F Y");
        }
        return $dateFromFormat;
    }

    /**
     * Curl, просто curl
     * @param $url
     * @param $data
     * @return mixed
     */
    public static function getCurlPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Преобразует первый символ в верхний регистр
     * @param string $str - строка
     * @param string $encoding - кодировка, по-умолчанию UTF-8
     * @return string
     */
    public static function mb_ucfirst($str, $encoding = 'UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }

    /**
     * HEX to RGB(a)
     * @param $hex
     * @param $opacity
     * @return string
     */
    public static function hex2rgb($hex, $opacity) {
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
            $opacity
        );
        if ($opacity) {
            $rgb = 'rgba(' . implode(', ', $rgb) . ')';
        } else {
            $rgb = 'rgb(' . implode(', ', $rgb) . ')';
        }
        return $rgb;
    }

    /**
     * Запись в куки
     * @param $name
     * @param $value
     * @param null $expires
     */
    public static function setCookie($name, $value, $expires = null){
        $cookie = new \Bitrix\Main\Web\Cookie($name, $value, $expires);
        $cookie->setSpread(\Bitrix\Main\Web\Cookie::SPREAD_DOMAIN);
        $cookie->setHttpOnly(false);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->addCookie($cookie);
        \Bitrix\Main\Application::getInstance()->getContext()->getResponse()->flush("");
    }

    /**
     * Получение куки
     * @param $name
     * @return array|mixed
     */
    public static function getCookie($name){
        $arResult = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getCookie($name);
        if($arResult){
            return unserialize($arResult);
        } else {
            return array();
        }
    }

    /**
     * Поиск и замена первого вхождения строки
     * @param $search
     * @param $replace
     * @param $text
     * @return null|string|string[]
     */
    public static function str_replace_once($search, $replace, $text) {
        $pattern = '/' . $search . '/i';
        $subject = preg_replace($pattern, $replace, $text, 1);
        return $subject;
    }

    /**
     * Преобразует формат размера файла
     * @param $bytes
     * @return string
     */
    function formatBytes($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }

}