<?php 
defined('APP_PATH') or die('No direct script access.');

require_once FRAMEWORK_PATH . 'LayoutController.php';

/**
 * The main controller than handles the index
 *
 * @author Young Suk Ahn
 */
class CodiniController extends LayoutController {
    
	/**
	 * Constructur. Calls the parent's constructor
	 */
    public function __construct() {
        parent::__construct();
		$this->require_js_main = '/codini/public/main';
    }

	// Called by the framework
	public function beforeAction()
	{
		parent::beforeAction();
	}

}
