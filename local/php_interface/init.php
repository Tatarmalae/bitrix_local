<?

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

define("SITE_STYLE_PATH", "/local/styles");
define("SITE_INCLUDE_PATH", "/local/include");
define("SITE_USER_CLASS_PATH", "/local/php_interface/user_class");
define("SITE_AJAX_PATH", "/local/ajax");

Loader::registerAutoLoadClasses(null, [
    '\Dev\Iblock' => SITE_USER_CLASS_PATH . '/classIblock.php',
    '\Dev\Catalog' => SITE_USER_CLASS_PATH . '/classCatalog.php',
    '\Dev\Settings' => SITE_USER_CLASS_PATH . '/classSettings.php',
    '\Dev\Utilities' => SITE_USER_CLASS_PATH . '/classUtilities.php',
]);

EventManager::getInstance()->addEventHandler("main", "OnBeforeUserUpdate", [
    '\Dev\Utilities',
    'OnBeforeUserAddHandler',
]);// Пример подключения статического метода класса в обработчике