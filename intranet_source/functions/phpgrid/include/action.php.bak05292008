<?php 
include_once("cls_db.php");
include_once("cls_control.php");
include_once("cls_util.php");
require("../adodb/adodb.inc.php");

if(!session_id()){ session_start();}
// Fix incompatibility issuse with PHP5 when register_long_arrays = off in config
if (!isset($HTTP_POST_VARS) && isset($_POST)){ $HTTP_POST_VARS = $_POST;}

$hostName 			= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : ""; 
$userName 			= isset($_SESSION["userName"]) ? $_SESSION["userName"] : ""; 
$password 			= isset($_SESSION["password"]) ? $_SESSION["password"] : ""; 
$databaseName 		= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";
$dbType		 		= isset($_SESSION["dbType"]) ? $_SESSION["dbType"] : "";
$fields_readonly	= isset($_SESSION["fields_readonly"]) ? $_SESSION["fields_readonly"] : "";
$ctrls				= isset($_SESSION["ctrls"]) ? unserialize($_SESSION["ctrls"]) : array();
$col_hiddens		= isset($_SESSION["col_hiddens"]) ? $_SESSION["col_hiddens"] : array();
$theme				= isset($_SESSION["theme"]) ? $_SESSION["theme"] : "theme/classic";

// echo $hostName ." ". $userName ." ". $password ." ". $databaseName;
$name_actiontype	= "action_type";
$name_btnsubmit	 	= "btnSubmit";
$name_btnclose		= "btnClose";
$name_pk			= "pk";
$name_pkval			= "pk_val";
$name_table			= "tbl_name";
$name_querypostbk	= "query_postbk";
$onload 			= "";
$pk					= "";
$pk_val				= "";
$query				= ""; // $query is now obtained via $_SESSION["sql"]
$action				= "";
$db 				= new C_DataBase($hostName, $userName, $password, $databaseName, $dbType);
$util				= new C_Utility();

global $col_hiddens;

// checkbox validation (NOT YET USED ANYWHERE)
function dg_is_checked($val, $val_comp){
	$is_checked = (strstr($val, $val_comp) == false) ? "" : "checked";
	return $is_checked;
}
	

// display and populate the HTML form (except Add action)
function render_tbl_columns($action_type, $field_name, $field_type, $field_len, $field_value, $is_disabled = false)
{ 
	global $db;
	global $ctrls;
	
	switch($action_type){
		// edit & add
		case "e":
		case "a":
		// if($field_name != $pk){
			print("<tr>");
			print("<td class=\"field_name\">". $field_name ."</td>");
			print("<td class=\"field_value\">");
			
			if(!$is_disabled){
				
				// render user defined html controls to the fields 
				if(isset($ctrls[$field_name])){
							
					
					render_user_ctrl($ctrls, $field_name, $field_value);
					
					
				// render default html controls to fields (text & textarea)
				}else{	
				
				
					// render default text or textarea HTML control by analyzing field length
					if(($field_type == "string" || $field_type == "blob")&& $field_len > 20){
						print("<textarea cols=\"35\" rows=\"6\" wrap=\"virtual\" name=\"$field_name\" class=\"form_textarea\" 
								onKeyDown=\"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\" 
								onKeyUp=  \"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\"/>$field_value</textarea><span name=\"count_$field_name\" id=\"count_$field_name\" class=\"field_len_count\">". ($field_len - strlen($field_value)) ."</span>"
							 );
					}else{
						print("<input name=\"$field_name\" size=\"30\" class=\"form_text\"
								onKeyDown=\"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\" 
								onKeyUp=  \"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\" 
								value=\"$field_value\"><span name=\"count_$field_name\" id=\"count_$field_name\" class=\"field_len_count\">". ($field_len - strlen($field_value)). "</span>"
							 );
					}
					
					
				}
				
				
			// readonly display when it is set to disabled
			}else{
				print(nl2br($field_value));	
			}
			
			print("</td></tr>\n");
			break;
			
		// view & delete 
		default:
			print("<tr>");
			print("<td class=\"field_name\">". $field_name. "</td>");
			print("<td class=\"field_value\">". nl2br($field_value) ."</td>"
);
			print("</tr>\n");
			break;
	}
}


// ------------- render user defined html controls to the fields -----------------
function render_user_ctrl($ctrls, $field_name, $field_value){

		$ctrl_group = $ctrls[$field_name];
		$ctrl_type = strtolower(get_class($ctrl_group[0]));
		
		// render single select menu
		// Note: use index 0 since there is AT LEAST one element in array
		if($ctrl_type == "radio"){
			for($i=0; $i<sizeof($ctrl_group); $i++){
				$obj_ctrl = $ctrl_group[$i];
				$cur_value= $obj_ctrl->get_value();
				$obj_ctrl->is_choosen = ($cur_value == trim($field_value)) ? true : false;
				$obj_ctrl->render();	
				print("<br />\n");
			}
		}elseif($ctrl_type == "checkbox"){
			$arr_fieldvalue = explode($ctrl_group[0]->get_delimiter(), trim($field_value));
			// loop each control
			for($i=0; $i<sizeof($ctrl_group); $i++){
				$obj_ctrl = $ctrl_group[$i];
				$cur_value= $obj_ctrl->get_value();
				// loop each value delimited by var $delimiter to determine selectivity
				foreach($arr_fieldvalue as $key => $value){
					// once is true break out of inner loop
					if($cur_value == trim($value)){
						$obj_ctrl->is_choosen = true;
						break;
					}
				}
				$obj_ctrl->render();
				print("<br />\n");
			}
		}elseif($ctrl_type == "dropdown"){
			print("<select name='". $ctrl_group[0]->varname ."' id='". $ctrl_group[0]->varname ."'>");
			for($i=0; $i<sizeof($ctrl_group); $i++){
				$obj_ctrl = $ctrl_group[$i];
				$cur_value= $obj_ctrl->get_value();
				$obj_ctrl->is_choosen = ($cur_value == trim($field_value)) ? true : false;
				$obj_ctrl->render();	
				print("<br />\n");
			}
			print("</select>");
		}
		
		// render mutliple select menu
		elseif($ctrl_type == "multiselect"){
			print("<select name='". $ctrl_group[0]->varname ."[]' id='". $ctrl_group[0]->varname ."[]' multiple size='". $ctrl_group[0]->size ."'>");
			$arr_fieldvalue = explode($ctrl_group[0]->get_delimiter(), trim($field_value));
			// loop each control
			for($i=0; $i<sizeof($ctrl_group); $i++){
				$obj_ctrl = $ctrl_group[$i];
				$cur_value= $obj_ctrl->get_value();
				// loop each value delimited by var $delimiter to determine selectivity
				foreach($arr_fieldvalue as $key => $value){
					// once is true break out of inner loop
					if($cur_value == trim($value)){
						$obj_ctrl->is_choosen = true;
						break;
					}
				}
				$obj_ctrl->render();
				print("\n");
			}
			print("</select>");
		}
}
















// ---------------------------postback action: UPDATE execute ---------------------------
if(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "edit"){
	
	$query	= isset($_POST[$name_querypostbk]) ? $_POST[$name_querypostbk] : die("Error: Cannot get SQL Query.");
	$tbl_name= isset($_POST[$name_table]) ? $_POST[$name_table] : die("Error: Cannot get SQL Table Name.");

/*	
	if($_POST["id"] != $_GET["id"] && is_array($rows)){
		print("Record with that ID already existed in the database. Pls fix it!");
		exit();
	}
*/	
	$set_str 	= "";
	$pk			= "";
	$pk_val		= "";
	// dyanmically get query table from query sql
	$update_query = "UPDATE $tbl_name SET ";	
	foreach($HTTP_POST_VARS as $key => $value){
		if(	$key != $name_actiontype && 
			$key != $name_querypostbk && 
			$key != $name_btnsubmit && 
			$key != $name_btnclose &&
			$key != $name_table
		   ){
			// exclude primary key and its value from update sql string
			switch($key){
				case "pk": 				// Primary Key
					$pk = $value;
					break;
				case "pk_val": 			// Key of PK
					$pk_val = $value;
					break;
				default:
					// -------- construct values of group control with delimiter --------
					if(is_array($value)){
						// there is AT LEAST one valued selected/checked
						$value_set = $value[0];
						// loop through control group and add delimiter e.g , | 
						if(sizeof($value) > 1){
							global $ctrls;
							for($i = 1; $i<sizeof($value); $i++){
								$value_set .= $ctrls[$key][0]->get_delimiter(). trim($value[$i]);
							}
						}
						$value = $value_set;
					}
					// ---------------- end of construct --------------------------------
		
					$set_str = ($set_str == "") 
								? $key ."='". $util->add_slashes($value) ."'" 
								: $set_str .", ". $key ."='". $util->add_slashes($value) ."'";
			}
		}// if
	}// foreach
	
	$pk_val = is_numeric($pk_val) ? $pk_val : "'".trim($pk_val, "'")."'";
	$update_query .=  $set_str ." WHERE $pk = $pk_val";
	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'><br>Double-checking against database schema... </div>";
	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'>Making sure the data type is in correct format without any key constrains...</div><br>";
	// $dg->check_constrain();
	$db->db_query($update_query);
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up
	
	
	
// -------------------------------postback action: DELETE execute ------------------------------------
}elseif(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "delete"){
	
	$query	= isset($_POST[$name_querypostbk]) ? $_POST[$name_querypostbk] : die("Error: Cannot get SQL Query.");
	$pk		= isset($_POST[$name_pk]) 	  ? $_POST[$name_pk] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$pk_val	= isset($_POST[$name_pkval])  ? $_POST[$name_pkval]: die("phpGrid says: Error. Cannot get Primary Key value.");
	$tbl_name= isset($_POST[$name_table]) ? $_POST[$name_table] : die("Error: Cannot get SQL Table Name.");
	
	// dyanmically get query table from query sql
	$pk_val = is_numeric($pk_val) ? $pk_val : "'".trim($pk_val, "'")."'";
	$delete_query = "DELETE FROM $tbl_name WHERE $pk = $pk_val";	

	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'><br>Double-checking against database schema... </div>";
	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'>Making sure the data type is in correct format without any key constrains...</div><br>";
	// $dg->check_constrain();
	$db->db_query($delete_query);
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up


// ---------------------------------postback action: ADD NEW execute -----------------------------------
}elseif(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "addnew"){
	
	$query	= isset($_POST[$name_querypostbk]) ? $_POST[$name_querypostbk] : die("Error: Cannot get SQL Query.");
	$pk		= isset($_POST[$name_pk]) 	  ? $_POST[$name_pk] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$tbl_name= isset($_POST[$name_table]) ? $_POST[$name_table] : die("Error: Cannot get SQL Table Name.");
	
	$insert_keys  	= "";	
	$insert_values 	= "";
	$addnew_query	= "";
	
	// dyanmically get query table insert columns
	foreach($HTTP_POST_VARS as $key => $value){
		if(	$key != $name_actiontype && 
			$key != $name_querypostbk && 
			$key != $name_btnsubmit && 
			$key != $name_btnclose &&
			$key != $name_pk &&
			$key != $name_table
		   ){
		   	
		   	// exclude primary key and its value from update sql string
			switch($key){
				case "pk": 				// Primary Key
					$pk = $value;
					break;
				case "pk_val": 			// Key of PK
					$pk_val = $value;
					break;
				default:
					$insert_keys .= $key .",";
			}
		}// if
	}// foreach
	
	// dyanmically get query insert value
	foreach($HTTP_POST_VARS as $key => $value){
		if(	$key != $name_actiontype && 
			$key != $name_querypostbk && 
			$key != $name_btnsubmit && 
			$key != $name_btnclose &&
			$key != $name_pk &&
			$key != $name_table
		   ){
		   	
		   	// exclude primary key and its value from update sql string
			switch($key){
				case "pk": 				// Primary Key
					$pk = $value;
					break;
				case "pk_val": 			// Key of PK
					$pk_val = $value;
					break;
				default:
					// -------- construct values of group control with delimiter --------
					if(is_array($value)){
						// there is AT LEAST one valued selected/checked
						$value_set = $value[0];
						// loop through control group and add delimiter e.g , | 
						if(sizeof($value) > 1){
							global $ctrls;
							for($i = 1; $i<sizeof($value); $i++){
								$value_set .= $ctrls[$key][0]->get_delimiter(). trim($value[$i]);
							}
						}
						$value = $value_set;
					}
					// ---------------- end of construct --------------------------------
					$insert_values .= "'". $util->add_slashes($value) ."',";
			}
		}// if
	}// foreach
	
	
	$insert_keys 	= substr($insert_keys, 0, -1);			// remove the last comma;
	$insert_values  = substr($insert_values, 0, -1);		// remove the last comma;
	$addnew_query   = "INSERT INTO $tbl_name($insert_keys) VALUES($insert_values)";

	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'><br>Double-checking against database schema... </div>";
	echo "<div style='font-size:8pt;color:lightgrey;font-family:arial;'>Making sure the data type is in correct format without any key constrains...</div><br>";
	// $dg->check_constrain();
	$db->db_query($addnew_query);
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up
	
	
	
// -----------------------------------------postback action: VIEW execute ------------------------------------
// ------------(Also called when actions.php is popped-up for the first time to initialize values) -----------
}else{
	$pk		= isset($_GET["pk"]) 	? $_GET["pk"] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$pk_val	= isset($_GET["pk_v"]) 	? $_GET["pk_v"]	: die("phpGrid says: Error. Cannot get Primary Key value.");
	$action	= isset($_GET["a"])  	? $_GET["a"] 	: die("phpGrid says: Error. Cannot get Action type.");
	$query 	= isset($_SESSION["sql"]) ? $_SESSION["sql"] : ""; 
	$tbl_name= $_SESSION["sql_table"]; 
}
?>




















<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>phpDataGrid - Admin</title>
	<link href="<?php echo $theme?>/css/datagrid.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/common.js"></script>
</head>
<!--header-->

<!--body-->
<body onload="<?php echo $onload?>">
<div id="action_window" align="center">
<?php
switch($action){
				
	// ----------------------  view -----------------------------
	case "v":
		$pk_val = is_numeric($pk_val) ? $pk_val : "'".trim($pk_val, "'")."'";
		$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."=". $pk_val) or die("phpGrid says: query failed in admin.view: <br><i>$query</i>");
		$rows 	= $db->fetch_array($result);
		
		print("<table cellspacing=\"0\" cellpadding=\"0\">\n");
		for($i = 0; $i < sizeof($rows); $i++){	// size is half because rs returns both index & assoc arrays
			$field_name = $db->field_name($result, $i);
			$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;
				
			if(!$is_hidden && $field_name != ""){
				render_tbl_columns($action, $field_name, "", "", $rows[$field_name]);
			}
		}
		print("</tr></table>\n");
		print("<br /><input name='btnClose' value=' Close ' type='button' class='btn_med' onclick='self.close();' />");
		print("<input name='$name_actiontype' value='view' type='hidden' />");
		break;
	
	//  ---------------------- delete  ---------------------------
	case "d":
	?>
	  	<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<?php
			$pk_val = is_numeric($pk_val) ? $pk_val : "'".trim($pk_val, "'")."'";
			$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."=". $pk_val) or die("phpGrid says: query failed in admin.delete: <br><i>$query</i>");
			$rows = $db->fetch_array($result);
			
			print("<table cellspacing=\"0\" cellpadding=\"0\">\n");
			for($i = 0; $i < sizeof($rows); $i++){
				$field_name = $db->field_name($result, $i);
				$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;
				
				if(!$is_hidden && $field_name != ""){
					render_tbl_columns($action, $field_name, "", "", $rows[$field_name]);
				}
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Confirm Delete" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="delete" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<input name="<?php echo $name_table?>" value="<?php echo $tbl_name?>" type="hidden" />
			<input name="<?php echo $name_pkval?>" value="<?php echo $pk_val?>" type="hidden" />
			<input name="<?php echo $name_querypostbk?>" value="<?php echo $query?>" type="hidden" />
		</form>
		
		<?php
		break;
		
	//  ---------------------- edit  -----------------------------
	case "e":
		?>
		<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<?php
			$pk_val = is_numeric($pk_val) ? $pk_val : "'".trim($pk_val, "'")."'";
			$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."=". $pk_val) or die("phpGrid says: query failed in admin.update: <br><i>$query</i>");
			$rows   = $db->fetch_array($result);
			$is_disabled = false;
			$arr_dbfields = array();
			
			global $fields_readonly;
			// save fields_readonly into array and trim each value
			$arr_temp = ($fields_readonly != "")? explode(",", trim($fields_readonly)) : "";
			if($arr_temp != ""){
				for($i = 0; $i < sizeof($arr_temp); $i++){	
					$arr_dbfields[trim($arr_temp[$i])] = trim($arr_temp[$i]);
				}
			}
			print("<table cellspacing=\"0\" cellpadding=\"0\">\n");
			for($i = 0; $i < sizeof($rows); $i++){
				$field_name = $db->field_name($result, $i);
				$field_type = $db->field_type($result, $i);
				$field_len  = $db->field_len($result, $i);
					
				$is_disabled = (isset($arr_dbfields[$field_name])) ? true : false;
				$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;
				
				// there is a bug in the adobe lib. We must ensure that the field_name must be 
				// checked against the rows so it's not blank. Don't render if it is blank.
				if(!$is_hidden && $field_name != ""){
					/* DEBUG 
					print("action = ".$action ."<br>");
					print("field_name = " .$field_name."<br>");
					print("field_type = " .$field_type."<br>");
					print("field_len = " .$field_len."<br>");
					print("rows[field_name] = " .$rows[$field_name]."<br>");
					print("is_disabled = " .$is_disabled."<br>");
					*/
					render_tbl_columns($action, $field_name, $field_type, $field_len, $rows[$field_name], $is_disabled);
				}
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Update" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="edit" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<input name="<?php echo $name_table?>" value="<?php echo $tbl_name?>" type="hidden" />
			<input name="<?php echo $name_pkval?>" value="<?php echo $pk_val?>" type="hidden" />
			<input name="<?php echo $name_querypostbk?>" value="<?php echo $query?>" type="hidden" />
		</form>
	
		<?php
		break;

	//  ---------------------- add new  -----------------------------
	case "a":
		?>
		<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
			<?php
			$result  = $db->db_query("SELECT * FROM $tbl_name");
			$rows 	= $db->fetch_array($result);
			
			print("<table cellspacing=\"0\" cellpadding=\"0\">\n");
			for($i = 0; $i < sizeof($rows); $i++){
				$field_name = $db->field_name($result, $i);
				$field_type = $db->field_type($result, $i);
				$field_len  = $db->field_len($result, $i);
				
				$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;
				
				if(!$is_hidden && $field_name != ""){
					render_tbl_columns($action, $field_name, $field_type, $field_len, "");
				}
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Add" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="addnew" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<input name="<?php echo $name_table?>" value="<?php echo $tbl_name?>" type="hidden" />
			<input name="<?php echo $name_querypostbk?>" value="<?php echo $query?>" type="hidden" />
		</form>
	
		<?php
		break;
} // switch 
?>

</div>

<br><div align='center'><a href='http://www.phpgrid.com' target='_new'><img src='images/phpgrid_logo.gif' border='0' alt='Powered by phpGrid' /></a></div>
</body>
</html>
