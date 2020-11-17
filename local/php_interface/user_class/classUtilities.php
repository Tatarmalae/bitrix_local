<?

namespace Dev;

/**
 * Класс вспомогательных функций
 * Class Utilities
 * @package Dev
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
        $keys = [
            2,
            0,
            1,
            1,
            1,
            2,
        ];
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
                    $arTemp = [];
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
        $rgb = [
            $r,
            $g,
            $b,
            $opacity,
        ];
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
    public static function setCookie($name, $value, $expires = null) {
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
    public static function getCookie($name) {
        $arResult = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getCookie($name);
        if ($arResult) {
            return unserialize($arResult);
        } else {
            return [];
        }
    }

    /**
     * Поиск и замена первого вхождения строки
     * @param $search
     * @param $replace
     * @param $text
     * @return string|string[]|null
     */
    public static function str_replace_once($search, $replace, $text) {
        $pattern = '/' . $search . '/i';
        return preg_replace($pattern, $replace, $text, 1);
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

    /**
     * Проверяет мобильное устройство
     * @return bool
     */
    public function IsMobileDevice() {
        if (preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $_SERVER['HTTP_USER_AGENT'])
            || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($_SERVER['HTTP_USER_AGENT'], 0, 4))
        )
            return true;
        return false;
    }

}