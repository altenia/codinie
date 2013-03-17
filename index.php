 <?php 
error_reporting(E_ALL | E_PARSE);
require('config.php');
require(FRAMEWORK_PATH . 'KLogger.php');

 /**
 * The index.php serves as the main entry point for the application.
 * It retrieves the controler and action names form Request (SERVER) information
 * and instantiates a controller class, and executes the method (action) 
 * accordingly.
 * 
 * The developer should not need to modify this file except for adding
 * contant values.
 *
 * @author Young Suk Ahn
 */

// Obtains the controller and action names
$controllerName = "Index";
$actionName = "index";

if(USE_PATH_INFO) {
  $pathInfo = null;
  if ( array_key_exists('PATH_INFO', $_SERVER) )
      $pathInfo = $_SERVER['PATH_INFO'];

  // The URL is parsed as <URL_ROOT>/index.php/<controller_name>/<action_name>
  if ($pathInfo != null) {
      // Parse parseInfo to obtain controller and action names
      $tokens = explode("/", $pathInfo);
      if ( sizeof($tokens) > 1 ) {
          $controllerName = $tokens[1];
          if ( sizeof($tokens) > 2 ) {
              $actionName = $tokens[2];
          }
      }
  }
} else {
  if (isset($_GET['_c']))
    $controllerName = $_GET['_c'];
  if (isset($_GET['_a']))
    $actionName = $_GET['_a'];
}

// Dynamically instantiates and excutes the action from the controller
$controllerClassName = 'Controller_' . $controllerName;

$controllerFilename = CONTROLLERS_PATH . $controllerClassName . '.php';
if (file_exists($controllerFilename)) {
	require_once $controllerFilename;

	$controller = new $controllerClassName();
	$controller->setControllerName($controllerName);
	$controller->setActionName($actionName);
	$controller->init();

	// Invoke the action of the instantiated controller
	if (method_exists($controller, $actionName)) {
		$controller->beforeAction();
		$controller->$actionName();
		$controller->afterAction();
	} else {
		die('Controller [' . $controllerClassName . '] does not include action [' . $actionName . '].');
	}
} else {
	die('Controller [' . $controllerFilename . '] not found.');
}

