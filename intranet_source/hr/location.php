<?
   $user = $HTTP_COOKIE_VARS[hruser];
   if($user == ""){
      header("Location: ../hr_login.php");
      exit;
   }

  $code = $HTTP_POST_VARS[code];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  $cursor = ora_open($conn);

  $sql = "select location_code, description, status from security_location where location_code = '". $code. "'";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  if (ora_fetch($cursor)){
	$loc_code = trim(ora_getcolumn($cursor, 0));
	$desc = trim(ora_getcolumn($cursor, 1));
	$status = trim(ora_getcolumn($cursor, 2));
  }
 
  $sql = "select location_code, description, status from security_location order by location_code";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

?>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Security Location
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

<form action="index.php" method="post" name="location">
<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>Location Code:</td>
      <td><input type = "text" name ="location" value = "<? echo $loc_code ?>" size = "3" maxlength = "3"></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>Description:</td>
      <td><input type = "text" name ="desc" value = "<? echo $desc ?>" size = "70"></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>Status:</td>
      <td><select name = "status" >
	  <option value = "A" <? if($status=="A") echo "selected" ?>>Active</option>
	  <option value = "I" <? if($status=="I") echo "selected" ?>>Inactive</option>
	  </select>
      </td>
   </tr>
   
   <tr> <td colspan="3">
	<table>
	<? while (ora_fetch($cursor)){ 
	        $iCode = trim(ora_getcolumn($cursor, 0));
        	$iDesc = trim(ora_getcolumn($cursor, 1));
        	$iStatus = trim(ora_getcolumn($cursor, 2));
	?>
		<tr>
		   <td><a href="index.php?code=<? echo $iCode ?>"><? echo $iCode ?></a></td>
		   <td><? $iDesc ?></td>
                   <td><? $iStatus ?></td>
		</tr>
        <? } ?>
   	</table>
   </td></tr>
</table>
