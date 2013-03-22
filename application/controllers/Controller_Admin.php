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
		//print_r($content->templates);
		
		$this->view->content = $content;
		$this->renderView();
		
    }
	
	public function template_form()
    {
		$breadcrumb = array( array('Templates', 'templates') );
		
		$template = array();
		$template['id'] = $this->getRequestParam('template_id');
		$template['content'] = '';
		$error_fields = array();
		$is_new = false; // Is the form a new template or editing existing
		if ($this->isMethodPost()) {
			$template['content'] = $this->getRequestParam('template_content');
			$error_fields = TemplateManager::instance()->validate($template);
			if (empty($error_fields)) {
				TemplateManager::instance()->save($template);
			}
			$this->redirect( route_url($this, 'Admin', 'index') );
		} else {
			$template['id'] = $this->getRequestParam('id');
			//print_r($template['id']);
			$is_new = empty($template['id']);
			if ($template['id'] == null) {
				$template['id'] = '';
				$template['content'] = '';
			} else {
				$template['content'] = TemplateManager::instance()->get($template['id']);
			}
			
		}
		
		$content = View::create('admin_template_form');
		$content->is_new = $is_new;
		$content->template = $template;
		$content->error_fields = $error_fields;
		$this->view->content = $content;
		$this->renderView();
    }
	
	private function get_templates($pattern = null)
	{
		return get_files(CODE_TEMPLATE_PATH , self::TPL_FILE_SUFIX, $pattern);
	}
	
	
}
