<?php 
defined('APP_PATH') or die('No direct script access.');

require_once 'CodiniController.php';
require_once CLASSES_PATH . 'TemplateManager.php';
require_once CLASSES_PATH . 'utils.php';

/**
 * The controller than handles admin pages
 *
 * @author Young Suk Ahn
 */
class Controller_Admin extends CodiniController {
    
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
		
		$template_details = array();
		$template_details['id'] = $this->getRequestParam('template_id');
		$template_details['content'] = '';
		$error_fields = array();
		$is_new = false; // Is the form a new template or editing existing
		if ($this->isMethodPost()) {
			$template_details['content'] = $this->getRequestParam('template_content');
			$template_details['info_raw'] = $this->getRequestParam('template_info');
			$error_fields = TemplateManager::instance()->validate($template_details);
			if (empty($error_fields)) {
				TemplateManager::instance()->save($template_details);
			}
			$this->redirect( route_url($this, 'Admin', 'index') );
		} else {
			$template_details['id'] = $this->getRequestParam('id');
			$is_new = empty($template_details['id']);
			if ($template_details['id'] == null) {
				$template_details['id'] = '';
				$template_details['content'] = '';
				$template_details['info_raw'] = TemplateManager::info_to_ini(null);
			} else {
				$template_details = TemplateManager::instance()->get($template_details['id']);
			}
		}
//print_r($template_details);
		
		$content = View::create('admin_template_form');
		$content->is_new = $is_new;
		$content->template_details = $template_details;
		$content->error_fields = $error_fields;
		$this->view->content = $content;
		
		$this->require_js_main = '/codini/public/admin_template_form';
		$this->renderView();
    }
	
	private function get_templates($pattern = null)
	{
		return get_files(CODE_TEMPLATE_PATH , self::TPL_FILE_SUFIX, $pattern);
	}
	
	
}
