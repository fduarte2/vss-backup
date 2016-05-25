<?
   $user = $HTTP_COOKIE_VARS[inventory_user];
   if($user == ""){
//      header("Location: ../inventory_login.php");
//      exit;
   }
  
  $vesId = $HTTP_POST_VARS[vessel];
  $custId = $HTTP_POST_VARS[customer];

  $conn = ora_logon("papinet@rf", "owner");
  if($conn < 1){
        printf("Error logging on to the Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  ora_commitoff($conn);
  $cursor = ora_open($conn);
  $cursor2 = ora_open($conn);
  //TABLES
/*
  $cargo_manifest = "cargo_manifest_backup";
  $cargo_manifest_changes = "cargo_manifest_changes_backup";
  $cargo_tracking = "cargo_tracking_backup";
  $cargo_tracking_changes = "cargo_tracking_changes_backup";
*/
  $cargo_tracking = "cargo_tracking";
  $cargo_log = "cargo_log";
  $vessel_profile = "vessel_profile";

  $rid = $HTTP_POST_VARS[rid];

  if(isset($HTTP_POST_VARS[save])){
	$arrLr_num = $HTTP_POST_VARS[lr_num];
        $arrLot_num = $HTTP_POST_VARS[lot_num];
        $arrCurrWeight = $HTTP_POST_VARS[curr_weight];
        $arrNewWeight = $HTTP_POST_VARS[new_weight];
	for ($i = 0; $i < count($arrLr_num); $i ++){
		//weight changed
		if($arrCurrWeight[$i] <> $arrNewWeight[$i]){
			//update cargo_manifest
			$sql = "update $cargo_tracking set gross_weight = '$arrNewWeight[$i]' where arrival_num = '$arrLr_num[$i]' and mill_order_num = '$arrLot_num[$i]' ";
		  	$statement = ora_parse($cursor, $sql);
        		if (!ora_exec($cursor)){
//echo "error";
				ora_rollback($conn);
				continue;
			}
		}
  	}
  }			
  
 
  $sql = "select arrival_num, mill_order_num, gross_weight from $cargo_tracking where barcode = '$rid'";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);  


?>
<html>
<head>
<script language ="JavaScript">
function setColor(ob,weight){
	if (ob.value == weight){
		ob.style.color = 'black';
	}else{
		ob.style.color = 'red';
	}
}
function trim(s) {
  while (s.substring(0,1) == ' ') {
    s = s.substring(1,s.length);
  }
  while (s.substring(s.length-1,s.length) == ' ') {
    s = s.substring(0,s.length-1);
  }
  return s;
}

</script>
</head>

<body link="#336633" vlink="#999999" alink="#999999">

<form action = "index.php" method = "post" name = "location">
<table border = "0" width = "65%" cellpadding = "4" cellspacing = "0">
   <tr>
	<td width = "1%">&nbsp;</td>
	<td>
	   <p align = "left">
		<font size = "5 " face = "Verdana" color = "##0066cc">Change Paper Weight
	   	</font>
	   	<hr>
	   </p>
	</td>
   </tr>
</table>

<table border="0" width="65%"  cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = "2"><b>Barcode:</b><input type = "text" size = "16" name = "rid" value = "<? echo $rid?>">
	
   </tr>
 
   <tr>
      <td width="1%">&nbsp;</td>
      	<td colspan = "2" align = "center">
      		<input type ="submit" name = "search" value="Search">&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;
		&nbsp;&nbsp;

		<input type = "submit" name = "save" value = " Save ">
 	</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td colspan = "2">
        <table border="1"  cellpadding="4" cellspacing="0">
           <tr>
		<td width = "100"><b>LR#</b></td>
		<td width = "200"><b>Mill Order#</b></td>
		<td width = "100"><b>Weight</b></td>
		<td width = "100"><nobr><b>New Weight</b></nobr></td>
           </tr>
	<? $index = 0;
	   while (ora_fetch($cursor)){
		$lr_num = trim(ora_getcolumn($cursor, 0));
		$lot_num = trim(ora_getcolumn($cursor, 1));
		$weight = trim(ora_getcolumn($cursor,2));
	?>
	   <tr>
		<td><? echo $lr_num ?></td><input type = "hidden" name = "lr_num[]" value = "<? echo $lr_num ?>">
		<td><? echo $lot_num ?></td><input type = "hidden" name = "lot_num[]" value = "<? echo $lot_num ?>">
		<td><? echo $weight ?></td><input type = "hidden" name = "curr_weight[]" value = "<? echo $weight ?>">
		<td><input type = "text"  name ="new_weight[]" value = "<? echo $weight ?>" size = "6" onChange = "setColor(this, '<? echo $weight ?>')">
           </tr>
	
	<?	
		$index ++; 
	  } 
	?>

 	</table>
      </td>
   </tr>
</table>

<? 
   ora_close($cursor);
   ora_close($cursor2);
   ora_logoff($conn);
?>
