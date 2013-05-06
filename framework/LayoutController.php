<?php 
/**
 * This file contains the implementation of the LayoutController.
 * The layout controller loads the layout view (e.g. the frame view that contains header & footer, etc).
 *
 * @author    Young Suk Ahn <ysahn@altenia.com>
 */

defined('APP_PATH') or die('No direct script access.');

require_once 'ControllerBase.php';
Loader::load('utils.php', Loader::LOC_SYSTEM);

/**
 * The Layout Controller class provides automatic layout view loading in the pre_action() method.
 * The layout view must contain a placeholder called 'content' where the variable content is placed.
 *
 */
class LayoutController extends ControllerBase
{
  /* The name of the layout view. To change the layout view, change the value of this 
   * before the post_action() method is called by the framework  */
  public $layout_view_name = 'frame';
    
  /**
   * Constructor. Calls the parent's constructor
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Called by the framework
   * Pre-loads the layout view.
   * 
   * @return none
   */
  public function pre_action() {
    parent::pre_action();
    //$this->view = View::create($this->layout_view_name, array('compress' => 'true'));
	$this->view = View::create($this->layout_view_name);
  }

}
