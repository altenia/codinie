<?php 
defined('APP_PATH') or die('No direct script access.');

require_once FRAMEWORK_PATH . 'LayoutController.php';
require_once CLASSES_PATH . 'TemplateManager.php';
require_once CLASSES_PATH . 'utils.php';

/**
 * The controller than handles admin pages
 *
 * @author Young Suk Ahn
 */
class Controller_Admin extends LayoutController {

	
    
	/**
	 * Constructur. Calls the parent's constructor
	 */
    public function __construct() {
        parent::__construct();
    }

	// Called by the framework
	public function beforeAction()
	{
		parent::beforeAction();
		$this->view->title = "Admin";
	}

	/**
	 * The index action
	 */
    public function index()
    {
		$this->template_list();
    }
	
	public function template_list()
    {
		$pattern = ifndef('pattern', $_GET, '*');
		$breadcrumb = array( array('Templates', 'templates') );
		
		$content = View::create('admin_template_list');

		$content->pattern = $pattern;
		$content->templates = TemplateManager::instance()->get_list($pattern);
		print_r($content->templates);
		
		$this->view->content = $content;
		$this->renderView();
    }
	
	public function template_form()
    {
		$breadcrumb = array( array('Templates', 'templates') );
		
		$content = View::create('admin_template_form');
		
		$template_id = ifndef($_GET['name']);
		$content->pattern = '';
		$content->template_name = $filename;
		$content->template_content = $this->read_file($filename);
		
		$this->view->content = $content;
		$this->renderView();
    }
	
	private function get_templates($pattern = null)
	{
		return get_files(CODE_TEMPLATE_PATH , self::TPL_FILE_SUFIX, $pattern);
	}
	
	public function read_file($filename)
    {
		$file_path = CODE_TEMPLATE_PATH . $filename;
		if (!file_exists($file_path)) {
			return null; 
		}

		$fh = fopen($file_path, "r");
		$content = fread($fh, filesize($file_path));
		fclose($fh);

		return $content;
	}
}
