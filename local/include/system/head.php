<?
use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
//Meta
Asset::getInstance()->addString('<meta charset="UTF-8">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1, minimum-scale=1" charset="utf-8">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta http-equiv="X-UA-Compatible" content="ie=edge">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="imagetoolbar" content="no">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="msthemecompatible" content="no">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="cleartype" content="no">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="HandheldFriendly" content="True">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="format-detection" content="telephone=no">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="format-detection" content="address=no">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="google" content="notranslate">', true, 'BEFORE_CSS');
//Favicon
Asset::getInstance()->addString('<meta name="theme-color" content="#ffffff">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="apple-mobile-web-app-capable" content="yes">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="application-name" content="">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="msapplication-tooltip" content="">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="msapplication-TileColor" content="">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<meta name="msapplication-TileImage" content="">', true, 'BEFORE_CSS');
Asset::getInstance()->addString('<link rel="shortcut ico" href="/favicon.ico" type="image/x-icon">', true, 'BEFORE_CSS');
//Fonts
Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800" rel="stylesheet">', true, 'BEFORE_CSS');
//CSS
Asset::getInstance()->addCss(SITE_STYLE_PATH . "/css/backend.css");
//JS
Asset::getInstance()->addJs(SITE_STYLE_PATH . "/js/backend.js");
/*Подключается, т.к. в js используются функции BX*/
CJSCore::Init(array('fx'));
?>
<script>
    (function (H) {
        H.className = H.className.replace(/\bno-js\b/, 'js')
    })(document.documentElement)
</script>
<title><? $APPLICATION->ShowTitle() ?></title>