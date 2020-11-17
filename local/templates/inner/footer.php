<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/footer.php", [], ["SHOW_BORDER" => false]); ?>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/popup.php", [], ["SHOW_BORDER" => false]); ?>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/svg.php", [], ["SHOW_BORDER" => false]); ?>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/scripts_before_body.php", [], ["SHOW_BORDER" => false]); ?>
</body>
</html>