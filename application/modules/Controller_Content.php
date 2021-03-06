<?php 
defined('APP_PATH') or die('No direct script access.');

require_once SHARED_MODULES_PATH . 'CodinieController.php';
Loader::load('utils.php');

/**
 * The controller than opens static pages
 *
 * @author Young Suk Ahn
 */
class Controller_Content extends CodinieController {
    
	/**
	 * Constructur. Calls the parent's constructor
	 */
    public function __construct() {
        parent::__construct();
    }

	// Called by the framework
	public function before_action()
	{
		parent::beforeAction();
		$this->view->title = "";
	}

	/**
	 * The index action
	 */
    public function action_index()
    {
        $this->action_show();
    }
    
    public function action_show()
    {
		$page = $_GET['page'];
		$file_path = SYS_PATH . 'pages/' . $page;
		if (!file_exists($file_path)) {
			header('HTTP/1.0 404 Not Found');
			echo '<h1>404 Not Found</h1>';
			echo 'The page that you have requested could not be found.';
			exit(); 
		}

		$fh = fopen($file_path, "r");
		$content = fread($fh, filesize($file_path));
		fclose($fh);

		$this->view->content = $content;
		$this->render_view();
	}
}
