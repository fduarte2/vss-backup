<?
/*
*	Inigo Thomas 09/24/2008
*
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "HR System";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
//  $conn = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn < 1){
   printf("Error logging on to the Oracle Server: ");
   printf(ora_errorcode($conn));
   printf("</body></html>");
   exit;
  }
  
  $cursor = ora_open($conn);
  $Short_Term_Cursor = ora_open($conn);


require_once 'Excel/reader.php';
// ExcelFile($filename, $encoding);

$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.

$data->setOutputEncoding('CP1251');
	$submit = $HTTP_POST_VARS['submit'];
	if($submit != "" && $HTTP_POST_FILES['import_file']['name'] != ""){
		echo basename($HTTP_POST_FILES['import_file']['name'])."<br>";
		$impfilename = basename($HTTP_POST_FILES['import_file']['name']);
		$target_path_import = "./".$impfilename;

		if(move_uploaded_file($HTTP_POST_FILES['import_file']['tmp_name'], $target_path_import)){
			system("/bin/chmod a+r $target_path_import");
		} else {
			echo "Error on file upload.  Please contact TS";
		}
		echo "Got File\n";
		$data->read($target_path_import);
		error_reporting(E_ALL ^ E_NOTICE);

		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			if ($data->sheets[0]['cells'][$i][1] != "") {
			echo $data->sheets[0]['cells'][$i][1].",";
			echo "1299,"; echo "NS472386,";
			echo $data->sheets[0]['cells'][$i][2];
			echo "-"; 		
			echo $data->sheets[0]['cells'][$i][3]; 		
			echo ",312,";
			echo $data->sheets[0]['cells'][$i][6].",";
			echo $data->sheets[0]['cells'][$i][9].",";
			echo $data->sheets[0]['cells'][$i][9].",";
			echo $data->sheets[0]['cells'][$i][10].",";
			echo $data->sheets[0]['cells'][$i][11].",";
			echo $data->sheets[0]['cells'][$i][12].",";
			echo "GRUPO NACION S.A.";
			echo nl2br("\n");
			echo "\n"; echo "\n"; echo "\n";
		}
		}
	}

?>

</form>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form enctype="multipart/form-data" name="put_data" action="abitibi_test.php" method="post">
	<tr>
		<td colspan="3" align="Center"><font size="2" face="Verdana"><b>Abitibi File Import</b></font></td>
	</tr>

	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Container ID:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="1"><textarea rows="1" cols="30" name="container_id"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" width="25%"><font size="2" face="Verdana">Import File:</font></td>
		<td colspan="1">&nbsp;</td>
	</tr>
	<tr>
		<td width="10%">&nbsp;</td>
		<td colspan="2"><input type="file" name="import_file"></td>
	</tr>
	<tr>
		<td colspan="3" align="center"><input type="submit" name="submit" value="Import File"></td>
	</tr>
</form>
</table>

<?php
// Test CVS



