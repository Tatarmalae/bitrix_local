<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/head.php", Array(), Array("SHOW_BORDER" => false)); ?>
    <? $APPLICATION->ShowHead(); ?>
</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/svg.php", Array(), Array("SHOW_BORDER" => false)); ?>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/header.php", Array(), Array("SHOW_BORDER" => false)); ?>
