<?php 
defined('APP_PATH') or die('No direct script access.');

require_once SHARED_MODULES_PATH . 'CodiniController.php';
Loader::load('ProjectManager_Xml.php', Loader::LOC_SITE);
Loader::load('utils.php', Loader::LOC_SYSTEM);

/**
 * The main controller than handles the index
 *
 * @author Young Suk Ahn
 */
class Controller_Home extends CodiniController {
   
	/**
	 * Constructur. Calls the parent's constructor
	 */
    public function __construct() {
        parent::__construct();
    }

	// Called by the framework
	public function pre_action()
	{
		parent::pre_action();
		//
		$this->view->title = "Codini";
	}

	/**
	 * The index action
	 */
    public function action_index()
    {
		$project_manager = new ProjectManager_Xml();
		
		$content = View::create('home_index');
		$content->projects = $project_manager->get_list();
		$this->view->content = $content;
		$this->render_view();
    }
	
}
