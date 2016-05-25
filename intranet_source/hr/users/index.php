<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "PDA Users";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $name = $HTTP_GET_VARS[name];

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
	$sName = $HTTP_POST_VARS[username];
	$sPwd = $HTTP_POST_VARS[password];
	$sStatus = $HTTP_POST_VARS[status];

	if ($sName <>"") {
		$sql = "delete from security_user where user_name = '".$sName."'";
		$statement = ora_parse($cursor, $sql);		
		if (ora_exec($cursor)){
			$sql = "insert into security_user (user_name, password, status) values ('".$sName."','".$sPwd."','".$sStatus."')";

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
  $sql = "select user_name, password, status from security_user where user_name = '". $name. "'";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

  if (ora_fetch($cursor)){
	$user_name = trim(ora_getcolumn($cursor, 0));
	$pwd = trim(ora_getcolumn($cursor, 1));
	$st = trim(ora_getcolumn($cursor, 2));
  }
 
  $sql = "select user_name, password, status from security_user order by user_name";

  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);

?>
<form action="index.php" method="post" name="location">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Security PDA Users
            </font>
	    <hr>
         </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td width="25%">User Name:
      <input type = "text" name ="username" value = "<? echo $user_name ?>" size = "15" maxlength = "15"></td>
  
      
      <td width="25%">Password:
      <input type = "text" name ="password" value = "<? echo $pwd ?>" size = "15" maxlength = "15"></td>
   
      <td width="50%">Status:
          <select name = "status" >
	  <option value = "A" <? if($st=="A") echo "selected" ?>>Active</option>
	  <option value = "I" <? if($st=="I") echo "selected" ?>>Inactive</option>
	  </select>
      </td>
   </tr>
   <tr>
      <td align = "center" colspan = 3>    
	 &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

	  <input type ="submit" name ="save" value="  Save  ">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

          <input type ="submit" name ="clear" value="  Clear  ">

      </td>
   </tr>
   <tr><td colspan="3">&nbsp;</td></tr>
   <tr> 
	<td width="1%">&nbsp;</td>
	<td colspan="2">
	<table border="1"  cellpadding="4" cellspacing="0">
	<tr>
	    <td width = "250"><nobr>User Name</nobr></td>
	    <td width = "250"><nobr>Password</nobr></td>
	    <td width = "150">Status</td>
        </tr>
	<? while (ora_fetch($cursor)){ 
	        $iName = trim(ora_getcolumn($cursor, 0));
        	$iPwd = trim(ora_getcolumn($cursor, 1));
                if ($iPwd =="")
			$iPwd = "&nbsp";
		$iStatus = trim(ora_getcolumn($cursor, 2));
		if ($iStatus =="A") {
			$iStatus = "Active";
		}else{
			$iStatus = "Inactive";
		}
	?>
		<tr>
		   <td><a href="index.php?name=<? echo $iName ?>"><? echo $iName ?></a></td>
		   <td><? echo $iPwd ?></td>
                   <td><? echo $iStatus ?></td>
		</tr>
        <? } ?>
   	</table>
   </td></tr>
</table>
<?
ora_logoff($conn);
ora_close($cursor);

include("pow_footer.php");
?>
