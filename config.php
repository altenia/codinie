<?php
/**
 * Main configuration
 */
 
define('APP_NAME', 'Codini');
define('APP_TAGLINE', 'Speeding up the agility!');
define('APP_VERSION', '0.4');

define('CONTEXT_PATH', 'codini');
//define('SYS_PATH', realpath('.') . DIRECTORY_SEPARATOR);
define('SYS_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('FRAMEWORK_PATH', SYS_PATH . 'framework/' );

define('APP_PATH', SYS_PATH . 'application/');
define('CLASSES_PATH', APP_PATH . 'classes/');
define('VIEWS_PATH', APP_PATH . 'views/');
define('CONTROLLERS_PATH', APP_PATH . 'controllers/');

define('REPO_PATH', SYS_PATH . 'repo/');
define('CODE_TEMPLATE_PATH', REPO_PATH . 'code-templates/');
define('PROJECTS_PATH', REPO_PATH . 'projects/');

define('VIEW_CLASS', 'SavantView');

define('USE_PATH_INFO', false);

define('DATE_TIME_FORMAT', 'y/m/d-h:i:s');
