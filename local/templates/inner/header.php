<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<html class="no-js" lang="<?= LANGUAGE_ID ?>">
<head>
    <? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/head.php", [], ["SHOW_BORDER" => false]); ?>
    <? $APPLICATION->ShowHead(); ?>
</head>
<body class="page">
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/scripts_after_body.php", [], ["SHOW_BORDER" => false]); ?>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<? $APPLICATION->IncludeFile(SITE_INCLUDE_PATH . "/system/header.php", [], ["SHOW_BORDER" => false]); ?>