<?php 
/**
 * This file contains the implementation of an abstract controller class that must be
 * extended by all web services controller classes.
 *
 * PHP version 5
 * 
 * @author    Young Suk Ahn <yahnpark@wayfair.com>
 * @copyright 2011 Wayfair, LLC - All rights reserved
 * @version   SVN: $Id
 */
 
defined('APP_PATH') or die('No direct script access.');

Loader::load('ControllerBase.php', Loader::LOC_FRAMEWORK);


/**
 * This abstract controller class is the base of all web services controller classes.
 * This clas contains utility functions for handling web services.
 *
 * @author Young Suk Ahn
 */
abstract class WebServiceControllerBase extends ControllerBase
{
  const VIEW_PREFIX = 'content_';
  public $protocol = 'json'; // Or alternatively 'xml'
  
  /**
   * Constructor. Calls the parent's constructor
   */
  public function __construct($protocol = 'json') {
    parent::__construct();
    $this->auto_render = true; // Autorenders the result (e.g. JSON response) 
    $this->protocol = $protocol;
  }
  
  /**
   * Called by the framework
   *
   * @return none
   */
  public function pre_action() {
    parent::pre_action();
    // Creates a JSON view
    
    $protocol = wfrequest("_format", WF_STRING);
    if (!empty($protocol) && ($protocol == 'json' || $protocol == 'xml')) {
      $this->protocol = $protocol;
    }
    
    $this->view = $this->create_view('content_'.$this->protocol, true);
  }
 
  /**
   * Fetches the JSON request from the POST request body and returns it as a parsed associative array. 
   *
   * @return Request JSON associative array. False if error.
   */
  function parse_request_body() {
    $request_body = file_get_contents('php://input');
    //$request_body = @http_get_request_body_stream();

    if (!is_string($request_body)) {
      return false;
    }
    $request_arr = json_decode($request_body, /*assoc*/true);

    return $request_arr;
  }
}
