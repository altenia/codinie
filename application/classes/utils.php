<?php

function ifndef($key, $arr, $default_val = '')
{
	return array_key_exists($key, $arr) ? $arr[$key] : $default_val;
}

/**
 * Returns array of array(file) that prefix matches, without the prefix
 *
 * @param prefix the prefix (e.g. 
 * @param suffix the suffix
 */
function get_files($prefix, $suffix, $pattern = '*', $remove_suffix = true)
{
	$dsi_files = glob($prefix . $pattern. $suffix);
	$retval = array();
	
	// Whether to remove the suffix or not
	$endpos = -strlen($suffix);
	foreach($dsi_files as $dsi_file) {
		if ($remove_suffix) {
			$retval[] = array(substr($dsi_file, strlen($prefix), $endpos));
		} else {
			$retval[] = array(substr($dsi_file, strlen($prefix)));
		}
	}
	return $retval;
}

/**
 * Save a string to file
 */
function save_to_file($file_path, $data)
{
	$fh = fopen($file_path, 'w');
	fwrite($fh, $data);
	fclose($fh);
}

/**
 * Save a string to file
 */
function read_from_file($file_path)
{
	$fh = fopen($file_path, "r");
	$content = fread($fh, filesize($file_path));
	fclose($fh);
	return $content;
}

/**
 * Returns true if keys exists in multidimensional array
 * E.g. 
 * $keys = array('a', 'b', 'c');
 * $multidim = array('a' => array('b' => array('c' => 'hello') ) );
 *
 */
function array_key_exists_md($keys, $array_md)
{
	$array_dim = $array_md;
	foreach($keys as $key) {
		if (array_key_exists($key, $array_dim)) {
			$array_dim = $array_dim[$key];
		} else {
			return false;
		}
	}
	return true;
}

function to_bool($value)
{
	return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/**
 * Returns true if trim is empty
 */
function trim_empty($val)
{
	return (trim($val) == false);
}