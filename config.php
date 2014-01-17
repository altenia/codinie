<?php
/**
 * Main configuration
 */
date_default_timezone_set('America/New_York');
define('APP_NAME', 'Codinie');
define('APP_TAGLINE', 'Speeding up the agility!');
define('APP_VERSION', '0.1.1');

define('CONTEXT_PATH', 'codini');
define('SYS_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('LIB_PATH', SYS_PATH . 'lib/' );
define('FRAMEWORK_PATH', SYS_PATH . 'framework/' );

define('APP_PATH', SYS_PATH . 'application/');
define('SYSTEM_CLASSES_PATH', APP_PATH . 'classes/');
define('SITE_CLASSES_PATH', SYSTEM_CLASSES_PATH);
define('MODULES_PATH', APP_PATH . 'modules/');
define('SHARED_MODULES_PATH', MODULES_PATH . '_shared/');
define('SHARED_VIEWS_PATH', SHARED_MODULES_PATH . 'views/');

define('USE_PATH_INFO', false);
define('VIEW_CLASS', 'SavantView'); // Available views: PhpView, SavantView, MoustacheView


//////////////////////////////////////////////////
// Application Specific

define('REPO_PATH', SYS_PATH . 'repo/');
define('CODE_TEMPLATE_PATH', REPO_PATH . 'code-templates/');
define('PROJECTS_PATH', REPO_PATH . 'projects/');

define('DATE_TIME_FORMAT', 'y/m/d-h:i:s');
