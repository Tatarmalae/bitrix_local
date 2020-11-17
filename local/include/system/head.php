<?

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);
/*META*/
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
/*FAVICON*/
Asset::getInstance()->addString('<link rel="icon" type="image/x-icon" href="/favicon.ico">', true, 'BEFORE_CSS');
/*FONTS*/
Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800" rel="stylesheet">', true, 'BEFORE_CSS');
/*CSS*/
Asset::getInstance()->addCss(SITE_STYLE_PATH . "/css/backend.css", true);
/*JS*/
Asset::getInstance()->addJs(SITE_STYLE_PATH . "/js/backend.js");
/*Подключается, т.к. в js используются функции BX*/
CJSCore::Init(['fx']);
?>
<script type="text/javascript" data-skip-moving="true">
    (function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)
</script>
<title><? $APPLICATION->ShowTitle() ?></title>