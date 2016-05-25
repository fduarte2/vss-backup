<?
/*
*	File of generic functions for any eport page.
*
*********************************************************/

function AlphaNumericAndTick($string){
	$return = $string;

	$return = str_replace("'", "`", $return);
	$return = str_replace("/", "", $return);
	$return = str_replace("\\", "", $return);
	$return = str_replace("\"", "", $return);
	$return = str_replace("[", "", $return);
	$return = str_replace("]", "", $return);
	$return = str_replace("?", "", $return);
	$return = str_replace("~", "", $return);
	$return = str_replace("!", "", $return);
	$return = str_replace("@", "", $return);
	$return = str_replace("#", "", $return);
	$return = str_replace("$", "", $return);
	$return = str_replace("%", "", $return);
	$return = str_replace("^", "", $return);
	$return = str_replace("&", "", $return);
	$return = str_replace("*", "", $return);
	$return = str_replace("(", "", $return);
	$return = str_replace(")", "", $return);
	$return = str_replace("=", "", $return);
	$return = str_replace("+", "", $return);
	$return = str_replace("|", "", $return);
	$return = str_replace("<", "", $return);
	$return = str_replace(">", "", $return);
	$return = str_replace("/", "", $return);
	$return = str_replace(":", "", $return);
	$return = str_replace("{", "", $return);
	$return = str_replace("}", "", $return);

	return $return;
}

