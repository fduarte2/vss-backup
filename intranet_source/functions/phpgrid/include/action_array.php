<?php
include_once("cls_db.php");

if(!session_id()){ session_start();}
// Fix incompatibility issuse with PHP5 when register_long_arrays = off in config
if (!isset($HTTP_POST_VARS) && isset($_POST)){ $HTTP_POST_VARS = $_POST;}

$uid = $_GET["_uid_"];
$grid_data			= isset($_SESSION[$uid]) ? $_SESSION[$uid] : array();
// $hostName 			= isset($_SESSION["hostName"]) ? $_SESSION["hostName"] : "";
// $userName 			= isset($_SESSION["userName"]) ? $_SESSION["userName"] : "";
// $password 			= isset($_SESSION["password"]) ? $_SESSION["password"] : "";
// $databaseName 		= isset($_SESSION["databaseName"]) ? $_SESSION["databaseName"] : "";
$fields_readonly		= isset($_SESSION["fields_readonly"]) ? $_SESSION["fields_readonly"] : "";
$ctrls				= isset($_SESSION["ctrls"]) ? unserialize($_SESSION["ctrls"]) : array();
$col_hiddens			= isset($_SESSION["col_hiddens"]) ? $_SESSION["col_hiddens"] : array();

// echo $hostName ." ". $userName ." ". $password ." ". $databaseName;
$name_actiontype		= "action_type";
$name_btnsubmit	 		= "btnSubmit";
$name_btnclose			= "btnClose";
$name_pk			= "pk";
$name_pkval			= "pk_val";
$name_table			= "tbl_name";
$name_querypostbk		= "query_postbk";
$onload 			= "";
$pk				= "";
$pk_val				= "";
$query				= ""; // $query is now obtained via $_SESSION["sql"]
$action				= "";
// $db 				= new C_DataBase($hostName, $userName, $password, $databaseName);

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
			print("<td bgcolor='#6699FF' align='right' width='20%'><font size='2' face='verdana,arial' color='white'><b>". ucwords($field_name) ."</b></font></td>");
			print("<td bgcolor=\"#f8f8f8\" align=\"left\">");

			if(!$is_disabled){

				// render user defined html controls to the fields
				if(isset($ctrls[$field_name])){


					render_user_ctrl($ctrls, $field_name, $field_value);


				// render default html controls to fields (text & textarea)
				}else{


					// render default text or textarea HTML control by analyzing field length
					if(($field_type == "string" || $field_type == "blob")&& $field_len > 20){
						print("<font size=\"1\">
									<textarea cols=\"35\" rows=\"6\" wrap=\"virtual\" name=\"$field_name\" class=\"form_field\"
										onKeyDown=\"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\"
										onKeyUp=  \"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\"/>$field_value</textarea>
								</font>
								<span name=\"count_$field_name\" id=\"count_$field_name\" style=\"font-size:5pt;font-family:arial;color:gray\">&nbsp;". ($field_len - strlen($field_value)) ."</span>"
							 );
					}else{
						print("<font size=\"1\">
									<input name=\"$field_name\" size=\"30\" class=\"form_field\"
										onKeyDown=\"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\"
										onKeyUp=  \"charsLimit(this, document.getElementById('count_$field_name'), $field_len);\"
										value=\"$field_value\"
							   </font>
							   <span name=\"count_$field_name\" id=\"count_$field_name\" style=\"font-size:5pt;font-family:arial;color:gray\">".
										($field_len - strlen($field_value)).
								"</span>"
							 );
					}


				}


			// display only if it is disabled
			}else{
				print("<font size='2'>". nl2br($field_value) ."</font>");
			}

			print("</td></tr>\n");
			break;

		// view & delete
		default:
			print("<tr>");
			print("<td bgcolor='#6699FF' align='right' width='20%'>
						<font size='1' face='verdana,arial' color='white'>
						<b>". ucwords($field_name). "</b>
						</font>
				   </td>"
				  );
			print("<td bgcolor='#f8f8f8' align='left'>
						<font size='2'>". nl2br($field_value) .
						"</font>
				   </td>"
				  );
			print("</tr>\n");
			break;
	}
}


// ------------- render user defined html controls to the fields -----------------
function render_user_ctrl($ctrls, $field_name, $field_value){

		$ctrl_group = $ctrls[$field_name];
		$ctrl_type = strtolower(get_class($ctrl_group[0]));
		print("<font size=\"2\">");

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

		print("</font>");
}












// ---------------------------postback action: UPDATE execute ---------------------------
if(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "edit"){

	$query	= isset($_POST[$name_querypostbk])  ? $_POST[$name_querypostbk] : die("Error: Cannot get SQL Query.");
	$pk		= isset($_POST[$name_pk]) 	  		? $_POST[$name_pk] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$pk_val	= isset($_POST[$name_pkval])  		? $_POST[$name_pkval]: die("phpGrid says: Error. Cannot get Primary Key value.");
//	$tbl_name= isset($_POST[$name_table]) ? $_POST[$name_table] : die("Error: Cannot get SQL Table Name.");


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
					echo $key ." => " . $value . "<br />";
					$grid_data[$pk_val][$key] = $value;
			}
		}// if
	}
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up





// -------------------------------postback action: DELETE execute ------------------------------------
}elseif(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "delete"){

	$query	= isset($_POST[$name_querypostbk])  ? $_POST[$name_querypostbk] : die("Error: Cannot get SQL Query.");
	$pk		= isset($_POST[$name_pk]) 	  		? $_POST[$name_pk] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$pk_val	= isset($_POST[$name_pkval])  		? $_POST[$name_pkval]: die("phpGrid says: Error. Cannot get Primary Key value.");

	array_splice($grid_data, $pk_val, 1);
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up


// ---------------------------------postback action: ADD NEW execute -----------------------------------
}elseif(isset($_POST[$name_actiontype]) && $_POST[$name_actiontype] == "addnew"){

	$new_row = array();
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
					echo $key ." => " . $value . "<br />";
					$new_row[$key] = $value;
			}
		}// if
	}
//	array_push($grid_data, $new_row);
	$grid_data[] = $new_row;
	$onload = "window.opener.document.location.reload();self.close();";	// reload the parent window and close the pop up




// -----------------------------------------postback action: VIEW execute ------------------------------------
// ------------(Also called when actions.php is popped-up for the first time to initialize values) -----------
}else{
	$pk		= isset($_GET["pk"]) 	? $_GET["pk"] 	: die("phpGrid says: Error. Cannot get Primary Key name.");
	$pk_val	= isset($_GET["pk_v"]) 	? $_GET["pk_v"]	: die("phpGrid says: Error. Cannot get Primary Key value.");
	$action	= isset($_GET["a"])  	? $_GET["a"] 	: die("phpGrid says: Error. Cannot get Action type.");
	$query 	= isset($_SESSION["sql"]) ? $_SESSION["sql"] : "";
//	$tbl_name= $db->field_table_by_querystr($query);
}











// resave the manipulated array back into the session
$_SESSION[$uid] = $grid_data;
?>




















<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>phpDataGrid - Admin</title>
	<link href="css/datagrid.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/common.js"></script>
</head>
<!--header-->

<!--body-->
<body onload="<?php echo $onload?>">
<div align="center">
<?php
switch($action){

	// ----------------------  view -----------------------------
	case "v":
//		$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."='". $pk_val ."'") or die("phpGrid says: query failed in admin.view: <br><i>$query</i>");
//		$rows 	= $db->fetch_row($result);
		$rows 	= $grid_data[$pk_val];

		print("<table width='80%' cellspacing='1' cellpadding='3' style='border:1pt solid'>\n");
		for($i = 0; $i < sizeof($rows); $i++){
//			$field_name = $db->field_name($result, $i);
			$field_name = key($rows);

			$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;

			if(!$is_hidden){
				render_tbl_columns($action, $field_name, "", "", $rows[$field_name]);
			}
			next($rows);
		}
		print("</tr></table>\n");
		print("<br /><input name='btnClose' value='Close Me' type='button' class='btn_med' onclick='self.close();' />");
		print("<input name='$name_actiontype' value='view' type='hidden' />");
		break;

	//  ---------------------- delete  ---------------------------
	case "d":
	?>
	  	<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']. '?_uid_='. $uid;?>" method="post">
			<?php
//			$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."='". $pk_val ."'") or die("phpGrid says: query failed in admin.delete: <br><i>$query</i>");
//			$rows = $db->fetch_row($result);
			$rows 	= $grid_data[$pk_val];

			print("<table width='80%' cellspacing='1' cellpadding='3' style='border:1pt solid'>\n");
			for($i = 0; $i < sizeof($rows); $i++){
//				$field_name = $db->field_name($result, $i);
				$field_name = key($rows);
				$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;

				if(!$is_hidden){
					render_tbl_columns($action, $field_name, "", "", $rows[$field_name]);
				}
				next($rows);
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Confirm Delete" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="delete" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<!--input name="<?php echo $name_table?>" value="<php echo $tbl_name>" type="hidden" /-->
			<input name="<?php echo $name_pkval?>" value="<?php echo $pk_val?>" type="hidden" />
			<input name="<?php echo $name_querypostbk?>" value="<?php echo $query?>" type="hidden" />
		</form>

		<?php
		break;

	//  ---------------------- edit  -----------------------------
	case "e":
		?>
		<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']. '?_uid_='. $uid;?>" method="post">
			<?php
//			$result = $db->db_query("SELECT * FROM $tbl_name WHERE ". $pk ."='". $pk_val ."'") or die("phpGrid says: query failed in admin.update: <br><i>$query</i>");
//			$rows   = $db->fetch_row($result);
			$rows 	= $grid_data[$pk_val];
			$is_disabled = false;
			$arr_dbfields = array();

			global $fields_readonly;

			$arr_temp = ($fields_readonly != "")? explode(",", trim($fields_readonly)) : "";
			if($arr_temp != ""){
				for($i = 0; $i < sizeof($arr_temp); $i++){
					$arr_dbfields[trim($arr_temp[$i])] = trim($arr_temp[$i]);
				}
			}
			print("<table width='80%' cellspacing='1' cellpadding='3' style='border:1pt solid'>\n");
			for($i = 0; $i < sizeof($rows); $i++){
//				$field_name = $db->field_name($result, $i);
//				$field_type = $db->field_type($result, $i);
//				$field_len  = $db->field_len($result, $i);
				$field_name = key($rows);
				$field_type = "string";
				$field_len  = 20;

				$is_disabled = (isset($arr_dbfields[$field_name])) ? true : false;
				$is_hidden   = (isset($col_hiddens[$field_name])) ? true : false;

				if(!$is_hidden){
					// print("<b>Debug: $action - $field_name - $field_type - $field_len - $rows[$field_name] - $is_disabled</b>");
					render_tbl_columns($action, $field_name, $field_type, $field_len, $rows[$field_name], $is_disabled);
				}
				next($rows);
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Update" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="edit" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<!--input name="<?php echo $name_table?>" value="<php echo $tbl_name>" type="hidden" /-->
			<input name="<?php echo $name_pkval?>" value="<?php echo $pk_val?>" type="hidden" />
			<input name="<?php echo $name_querypostbk?>" value="<?php echo $query?>" type="hidden" />
		</form>

		<?php
		break;

	//  ---------------------- add new  -----------------------------
	case "a":
		?>
		<form name="frmGridAction" action="<?php echo $_SERVER['PHP_SELF']. '?_uid_='. $uid;?>" method="post">
			<?php
			$rows 	= $grid_data[0];

			print("<table width='80%' cellspacing='1' cellpadding='3' style='border:1pt solid'>\n");
			for($i = 0; $i < sizeof($rows); $i++){
				$field_name = key($rows);
				$field_type = "string";
				$field_len  = 20;

				render_tbl_columns($action, $field_name, $field_type, $field_len, "");

				next($rows);
			}
			print("</tr></table>\n");
			?>
			<br />
			<input name="<?php echo $name_btnsubmit?>" value="Add" type="submit" class="btn_med" />
			<input name="<?php echo $name_btnclose?>" value="Cancel" type="button" class="btn_med" onclick="self.close();" />
			<input name="<?php echo $name_actiontype?>" value="addnew" type="hidden" />
			<input name="<?php echo $name_pk?>" value="<?php echo $pk?>" type="hidden" />
			<!--input name="<php echo $name_table>" value="<php echo $tbl_name>" type="hidden" /-->
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
