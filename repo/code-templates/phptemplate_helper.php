<?php
function capitalize($str)
{
	//first we make everything lowercase, and 
	//then make the first letter if the entire string capitalized
	return ucfirst(strtolower($str));
}

function list_pks($structure) 
{
	$retval = array();
	foreach ($structure->field_descriptions as $field_descr)
	{
		if ($field_descr->is_key)
			$retval[] = $field_descr->name;
	}
	return $retval;
}

function list_pks_csv($structure) 
{
	$pks = list_pks($structure);
	return implode(",", $pks);
}

function camel_to_underscore($text)
{
  $retval = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $text));
  return $retval;
}

function remove_prefix($text, $prefix, $capitalization = null, $case = null)
{
  $retval = $text;
  if (strpos($text, $prefix) === 0) {
    $retval = substr($retval, strlen($prefix), strlen($text));
  }
  if ($capitalization == 'l') {
    // lowercase
    $retval = strtolower($retval);
  } else if ($capitalization == 'uf') {
    // uppercase first character
    $retval = ucfirst($retval);
  }
  
  if ($case == 'u') {
    $retval = camel_to_underscore($retval);
  }
  
  return $retval;
}