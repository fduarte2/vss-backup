<?
/*
*		Adam Walter, Sep/Oct 2008
*
*		A screen for "PORT" employees only to add containers.
*
*****************************************************************/

	$cursor = ora_open($conn);
	$short_term_data_cursor = ora_open($conn);

	$submit = $HTTP_POST_VARS['submit'];
	if($submit == "Upload File"){
		$vessel = $HTTP_POST_VARS['vessel'];
		$target_path = "./" . basename($HTTP_POST_FILES['upload_file']['name']);
		$counter = 0;
		if(!move_uploaded_file($HTTP_POST_FILES['upload_file']['tmp_name'], $target_path)){ // copying file to server didn't work
			echo "<font color=\"#ff0000\" size=\"2\">File Upload Failed.  Please contact TS.</font><br>";
		}else{ // start loop to write from 
			$handle = fopen($target_path, "r");
			while(!feof($handle)){
				$line = fgets($handle);
				$line = trim(strtoupper($line));
				if($line != ""){
					$sql = "INSERT INTO PAPER_VALID_CONTAINERS (ARRIVAL_NUM, CONTAINER_ID) VALUES('".$vessel."', '".$line."')";
					@ora_parse($cursor, $sql);
					@ora_exec($cursor);
					$counter++;
				}
			}
			echo "<font size=\"3\" face=\"Verdana\">$counter Containers Uploaded.</font><br>";
		}
	}



?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Container Upload
</font>
	    <hr>
	 </p>
      </td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" action="upload_cont.php" method="post" name="the_upload">
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Vessel #:</b></font></td>
		<td><select name="vessel"><option value="">Select a Vessel:</option>
<?
	$sql = "SELECT VO.LR_NUM, VP.VESSEL_NAME FROM VOYAGE VO, VESSEL_PROFILE VP WHERE VO.LR_NUM = VP.LR_NUM AND VP.SHIP_PREFIX = 'DOLE' AND VO.DATE_DEPARTED IS NULL ORDER BY VO.LR_NUM";
	ora_parse($short_term_data_cursor, $sql);
	ora_exec($short_term_data_cursor);
	while(ora_fetch_into($short_term_data_cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
								<option value="<? echo $row['LR_NUM']; ?>"<? if($vessel == $row['LR_NUM']){?> selected <?}?>><? echo $row['VESSEL_NAME']; ?></option>
<?
	}
?>
				</select></td>
	</tr>
	<tr>
		<td width="10%"><font size="2" face="Verdana"><b>Container File:</b></font></td>
		<td><input type="file" name="upload_file"></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><font size="2" face="Verdana">Note:  File must be a single-column *.csv file.  One container per row.</font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="Upload File">
	</tr>
</form>
</table>
