<?php 
defined('APP_PATH') or die('No direct script access.');

require_once FRAMEWORK_PATH . 'LayoutController.php';
require_once CLASSES_PATH . 'ProjectManager_Xml.php';
require_once CLASSES_PATH . 'utils.php';

/**
 * The main controller than handles the index
 *
 * @author Young Suk Ahn
 */
class Controller_Home extends LayoutController {
   
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
		//
		$this->view->title = "Codini";
	}

	/**
	 * The index action
	 */
    public function index()
    {
		$project_manager = new ProjectManager_Xml();
		
		$content = View::create('home_index');
		$content->projects = $project_manager->get_list();
		$this->view->content = $content;
		$this->renderView();
    }
	
}