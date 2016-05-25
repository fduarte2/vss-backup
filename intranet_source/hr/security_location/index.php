<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Security Location Update";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $code = $HTTP_GET_VARS[code];

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
  	printf("Error logging on to the Oracle Server: ");
  	printf(ora_errorcode($conn));
     	printf("Please try later!");
      	exit;
  }
  ora_commitoff($conn);
  $cursor = ora_open($conn);
  
  if(isset($HTTP_POST_VARS[clear])){
        $code = "";
  }

  if(isset($HTTP_POST_VARS[save])){
	$sCode = $HTTP_POST_VARS[location];
	$sDesc = $HTTP_POST_VARS[description];
	$sShort = $HTTP_POST_VARS[short_desc];
	$sStatus = $HTTP_POST_VARS[status];
	$sDesc = stripslashes($sDesc);
        $sDesc = str_replace("'","''", $sDesc);

	if ($sCode <>"") {
		$sql = "delete from security_location where location_code = '".$sCode."'";
		$statement = ora_parse($cursor, $sql);		
		if (ora_exec($cursor)){
			$sql = "insert into security_location (location_code, description, short_desc, status) values ('".$sCode."','".$sDesc."','".$sShort."','".$sStatus."')";

			$statement = ora_parse($cursor, $sql);
	                if (ora_exec($cursor)){
				ora_commit($conn);
                        }else{
				ora_rollback($conn);
                        }
                 }else{
			ora_rollback($conn);
		}
	}
  }
  $sql = "select location_code, description, short_desc, status from security_location where location_code = '". $code. "'";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  if (ora_fetch($cursor)){
	$loc_code = trim(ora_getcolumn($cursor, 0));
	$desc = trim(ora_getcolumn($cursor, 1));
	$short = trim(ora_getcolumn($cursor, 2));
	$status = trim(ora_getcolumn($cursor, 3));
	$desc = str_replace(chr(042), "&#34", $desc);
  }
 
  $sql = "select location_code, description, short_desc, status from security_location order by location_code";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

?>
<form action="index.php" method="post" name="location">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
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

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td >Location:</td>
      <td><input type = "text" name ="location" value = "<? echo $loc_code ?>" size = "3" maxlength = "3"></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>Description:</td>
      <td><input type = "text" name ="description" value = "<? echo $desc ?>" size = "70"></td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td>Short Desc:</td>
      <td><input type = "text" name ="short_desc" value = "<? echo $short ?>" size = "10" maxlength = "10"></td>
   </tr>

   <tr>
      <td width="1%">&nbsp;</td>
      <td>Status:</td>
      <td><select name = "status" >
	  <option value = "A" <? if($status=="A") echo "selected" ?>>Active</option>
	  <option value = "I" <? if($status=="I") echo "selected" ?>>Inactive</option>
	  </select>
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	  <input type ="submit" name ="save" value="Save">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type ="submit" name ="clear" value="clear">

      </td>
   </tr>
   <tr><td colspan="3">&nbsp;</td></tr>
   <tr> 
	<td width="1%">&nbsp;</td>
	<td colspan="2">
	<table border="1"  cellpadding="4" cellspacing="0">
	<tr>
	    <td width = "50">Location</td>
	    <td width = "80"><nobr>Short Desc</nobr></td>
	    <td width = "420">Description</td>
	    <td width = "50">Status</td>
        </tr>
	<? while (ora_fetch($cursor)){ 
	        $iCode = trim(ora_getcolumn($cursor, 0));
        	$iDesc = trim(ora_getcolumn($cursor, 1));
		$iShort = trim(ora_getcolumn($cursor, 2));
        	$iStatus = trim(ora_getcolumn($cursor, 3));
		if ($iStatus =="A") {
			$iStatus = "Active";
		}else{
			$iStatus = "Inactive";
		}
	?>
		<tr>
		   <td align ="center"><a href="index.php?code=<? echo $iCode ?>"><? echo $iCode ?></a></td>
		   <td><? echo $iShort ?></td>
		   <td><? echo $iDesc ?></td>
                   <td><? echo $iStatus ?></td>
		</tr>
        <? } ?>
   	</table>
   </td></tr>
</table>

<? include("pow_footer.php"); ?>
