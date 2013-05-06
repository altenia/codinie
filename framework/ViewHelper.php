<?php
/**
 * This file contains helper functions that generates view elements specific to the particular
 * View, in this case PHP view (including Savant)
 * 
 * @todo: convert this into class
 *
 */

Loader::load('utils.php', Loader::LOC_SYSTEM);

/**
 * Generates the route url in terms of contreller & action
 * 
 * @param object $context         Can be either controller or view object. It should contain context_path property in it.
 * @param string $controller_name The controller name
 * @param string $action_name     The action name
 * @param array  $qparams         The query parameters
 *
 * @return string The URL of the page that maps to controller/action 
 */
function route_url($context, $controller_name, $action_name = null, $qparams = null)
{
  $url = '';
  if (Dispatcher::instance()->use_path_info) {
    $url = $context->context_path . '/index.php/' . $controller_name . '/' . $action_name;
  } else {
    $url = $context->context_path . '/index.php?_c=' . $controller_name . '&_a=' . $action_name;
  }
  if (!empty($qparams) && is_array($qparams)) {
    if (Dispatcher::instance()->use_path_info) {
      $url .= '?';
    } else {
      $url .= '&';
    }
    $qparams_list = array();
    foreach ($qparams as $key => $value) {
      $qparams_list[] = $key . '=' . urlencode($value);
    }
    $url .= implode('&', $qparams_list);
  }
  return $url;
}

/**
 * TODO: CHECK WITH THE SOFTWARE TEAM AND MOVE IT TO ONE OF INCLUDE PHP 
 * Generates a select tag from an array of tuples
 * 
 * @param string $el_name         The name to be used for the generated select HTML element
 * @param string $list            The data source that contains the options list
 * @param string $key_field       The name of the field in the $list to be used as the key in he option element
 * @param mixed  $display_fields  Either a string or array of strings that contains name(s) of field(s) to be used as value in the option
 * @param string $selected        The value for selected option
 * @param string $default_display The display of the non-selected, default first option
 *
 * @return string the select tag
 */
function generate_select_html($el_name, $list, $key_field, $display_fields, $selected = "", $default_display = "Select One")
{
  $html = '<select name="' . $el_name . '">';
  if ($default_display != null) {
    $html .= '<option value="" >'. $default_display . '</option>';
  }
  foreach ($list as $row) {
    $selected_attr = ($selected == $row[$key_field] ) ? ' selected="selected"' : '';
    
    $value = null;
    if ( is_array($display_fields)) {
      foreach ($display_fields as $display_field) {
        if (strlen($value) > 0) {
          $value .= ' - ';
        }
        $value .= $row[$display_field];
      }
    } else {
      $value = $row[$display_fields];
    }
    
    $html .= '<option value="' . $row[$key_field] . '"'. $selected_attr. '>' . $value . '</option>';
  }
  $html .= '</select>';
  return $html;
}

/**
 * Converts associative array to string of format name1=value1<delim>[name2=value2<delim>]
 * 
 * @param array  $map   The associative array map
 * @param string $delim The deliminter that separates an entry 
 *
 * @return string The strin gof key value pairs
 */
function array_to_string($map, $delim = "\n") {
  $retval = null;
  if (is_array($map)) {
    $retval = '';
    foreach ($map as $name => $val) {
      $retval .= $name . '=' . $val . $delim;
    }
  }
  return $retval;
}