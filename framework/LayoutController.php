<?php 
defined('APP_PATH') or die('No direct script access.');

require_once 'ControllerBase.php';
Loader::load('utils.php', Loader::LOC_SYSTEM);

/**
 * The main controller than handles the index
 *
 * @author Young Suk Ahn
 */
class LayoutController extends ControllerBase {
    
	public $layout_view_name = 'frame';
    
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
		$this->view = View::create($this->layout_view_name);
	}

}
