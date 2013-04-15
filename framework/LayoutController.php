<?php 
defined('APP_PATH') or die('No direct script access.');

require_once FRAMEWORK_PATH . 'ControllerBase.php';
require_once CLASSES_PATH . 'DataStructure_Serializer.php';
require_once CLASSES_PATH . 'CodeGen_PhpTemplate.php';
require_once CLASSES_PATH . 'utils.php';

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
