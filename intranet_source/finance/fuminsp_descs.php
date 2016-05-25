<?
/*
*
*	Adam Walter, May 2014.
*
*	A screen for finance to add items to the dropdown box for FUMINSP's.
*
*	Therea re more elegant ways to create the update/insrt SQLs, but given the 
*	history of this port, I dont want to have to un-elegant them later.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "FUMINSP";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }

	$conn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$conn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}

	$submit = $HTTP_POST_VARS['submit'];
	$sql = "";

	if($submit == "Save Type"){
		$ID = $HTTP_POST_VARS['ID'];
		$text = str_replace("'", "`", $HTTP_POST_VARS['type_text']);
		$text = str_replace("\\", "", $text);

		$sql = "UPDATE FUMINSP_TYPES
				SET TYPE_TEXT = '".$text."'
				WHERE DESC_ID = '".$ID."'";
	} elseif($submit == "Add New Type" && $HTTP_POST_VARS['type_text'] != ""){
//		$ID = $HTTP_POST_VARS['ID'];
		$text = str_replace("'", "`", $HTTP_POST_VARS['type_text']);
		$text = str_replace("\\", "", $text);

		$sql = "INSERT INTO FUMINSP_TYPES
					(DESC_ID,
					TYPE_TEXT)
				(SELECT MAX(DESC_ID) + 1, '".$text."'
				FROM FUMINSP_TYPES)";
	} elseif($submit == "Save Description"){
		$ID = $HTTP_POST_VARS['ID'];
		$text = str_replace("'", "`", $HTTP_POST_VARS['desc_text']);
		$text = str_replace("\\", "", $text);

		$sql = "UPDATE FUMINSP_DESCRIPTIONS
				SET DESC_TEXT = '".$text."'
				WHERE DESC_ID = '".$ID."'";
	} elseif($submit == "Add New Description" && $HTTP_POST_VARS['desc_text'] != ""){
//		$ID = $HTTP_POST_VARS['ID'];
		$text = str_replace("'", "`", $HTTP_POST_VARS['desc_text']);
		$text = str_replace("\\", "", $text);

		$sql = "INSERT INTO FUMINSP_DESCRIPTIONS
					(DESC_ID,
					DESC_TEXT)
				(SELECT MAX(DESC_ID) + 1, '".$text."'
				FROM FUMINSP_DESCRIPTIONS)";
	}
	if($sql != ""){
		$mod = ociparse($conn, $sql);
		ociexecute($mod);
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC"><b>Fumigation/Inspection Description Maintenance<b>
            </font>
         </p>
			<font size="3" face="Verdana" color="#330000"><a href="fum_insp_bill.php">Click Here</a> to return to the Fumigation&Inspection screen.</font>
		 <hr>
      </td>
   </tr>
</table>

<table border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Types</b></font></td>
	</tr>
<?
	$type_counter = 0;

	$sql = "SELECT DESC_ID, TYPE_TEXT
			FROM FUMINSP_TYPES
			ORDER BY DESC_ID";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
<form name="edit_type<? echo $type_counter; ?>" action="fuminsp_descs.php" method="post">
<input type="hidden" name="ID" value="<? echo ociresult($short_term_data, "DESC_ID");?>">
	<tr>
		<td><input type="text" name="type_text" size="30" maxlength="50" value="<? echo ociresult($short_term_data, "TYPE_TEXT"); ?>"></td>
		<td><input type="submit" name="submit" value="Save Type"></td>
	</tr>
</form>
<?
		$type_counter++;
	}
?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
<form name="add_type" action="fuminsp_descs.php" method="post">
<input type="hidden" name="ID" value="New">
	<tr>
		<td><input type="text" name="type_text" size="30" maxlength="50" value=""></td>
		<td><input type="submit" name="submit" value="Add New Type"></td>
	</tr>
</form>
</table>

<hr><br><hr>

<table border="1" cellpadding="2" cellspacing="0">
	<tr>
		<td colspan="2" align="center"><font size="2" face="Verdana"><b>Descriptions</b></font></td>
	</tr>
<?
	$type_counter = 0;

	$sql = "SELECT DESC_ID, DESC_TEXT
			FROM FUMINSP_DESCRIPTIONS
			ORDER BY DESC_ID";
	$short_term_data = ociparse($conn, $sql);
	ociexecute($short_term_data);
	while(ocifetch($short_term_data)){
?>
<form name="edit_desc<? echo $type_counter; ?>" action="fuminsp_descs.php" method="post">
<input type="hidden" name="ID" value="<? echo ociresult($short_term_data, "DESC_ID"); ?>">
	<tr>
		<td><input type="text" name="desc_text" size="50" maxlength="100" value="<? echo ociresult($short_term_data, "DESC_TEXT"); ?>"></td>
		<td><input type="submit" name="submit" value="Save Description"></td>
	</tr>
</form>
<?
		$type_counter++;
	}
?>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
<form name="add_desc" action="fuminsp_descs.php" method="post">
<input type="hidden" name="ID" value="New">
	<tr>
		<td><input type="text" name="desc_text" size="50" maxlength="100" value=""></td>
		<td><input type="submit" name="submit" value="Add New Description"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");