 <?php 
 /**
 * The index.php serves as the main entry point for the application.
 * It retrieves the controler and action names form Request (SERVER) information
 * and instantiates a controller class, and executes the method (action) 
 * accordingly.
 * 
 * The developer should not need to modify this file except for adding
 * constant values.
 *
 * @author Young Suk Ahn
 */

error_reporting(E_ALL | E_PARSE);
require('config.php');
require(FRAMEWORK_PATH . 'ClassLoader.php');
require(FRAMEWORK_PATH . 'Dispatcher.php');
require(FRAMEWORK_PATH . 'KLogger.php');


// Dispatche the request to a Controller
$dipatcher = Dispatcher::instance();
$dipatcher->use_path_info = USE_PATH_INFO;

$dipatcher->dispatch();

