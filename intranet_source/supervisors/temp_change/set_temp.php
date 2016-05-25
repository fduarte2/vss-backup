<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Temperature Change Request";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);

  $rId = $HTTP_GET_VARS[rId];
  $ast = $HTTP_GET_VARS[ast];	
  $sql = "select actual_set_point from temperature_req where req_id = $rId";
  ora_parse($cursor, $sql);
  ora_exec($cursor);

  if (ora_fetch($cursor)){
	$temp = ora_getcolumn($cursor, 0);
  }
  ora_close($cursor);
  ora_logoff($conn);

?>
<form action="set_temp_process.php"  method="post" name="hire">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Set Warehouse Temperature
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

<table width="100%" align="center"  border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="50%">
          <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
             <tr>
                <td width="5%" height="8"></td>
                <td width="30%" height="8"></td>
                <td width="60%" height="8"></td>
                <td width="5%" height="8"></td>
             </tr>
             <tr>
                <td>&nbsp;</td>
                <td align="right"><font size="2" face="Verdana">Request#:</font></td>
                <td align="left"><?= $rId ?>
                        <input type="hidden" name="rId" size=10 value="<?= $rId?>"></td>
                <td>&nbsp;</td>
             </tr>
             <tr>
                <td>&nbsp;</td>
		<td align="right" valign="top"><font size="2" face="Verdana">Set For (°F): </font>
		<td>
		<select name="set_point">
	        <option value=""></option>
                <option value="Repair" <?if ($temp == "Repair") echo "selected"?>>Repair</option>
				<option value="Shut Down" <?if ($temp == "Shut Down") echo "selected"?>>Shut Down</option>
				<option value="E&M Override" <?if ($temp == "E&M Override") echo "selected"?>>E&amp;M Override</option>
		<?
  		for($i = 0; $i <= 65; $i++){
   			if($i == 30){
     				for(; $i < 38; $i += .5){
       					if ($temp == $i){
                				$strSelected = "selected";
        				}else{
                				$strSelected ="";
        				}
       					printf("<option value=\"%s\" $strSelected>%s</option>\n", $i, $i);
     				}
   			}
    			if ($temp <>"Shut Down" && $temp <> "" && $temp == $i ){
                		$strSelected = "selected";
    			}else{
                		$strSelected ="";
    			}

   			printf("<option value=\"%s\" $strSelected>%s</option>\n", $i, $i);
  		}
		?>
		</select>
		</td>	
<!--                <td align="left"><input type="textbox" name="set_point" size=10 value="<?= $actual ?>">
-->
                <td>&nbsp;</td>
             </tr>
             <tr>
                <td width="5%" height="8"></td>
                <td width="20%" height="8"></td>
                <td width="70%" height="8"></td>
                <td width="5%" height="8"></td>
             </tr>
             <tr>
              <td>&nbsp;</td>
              <td align="right"><input type="Submit" name=save value="   Save  "></td>
              <td align="left"><input type="Submit" name=cancel value=" Cancel "></td>
              <td>&nbsp;&nbsp;</td>
             </tr>
             <tr>
                <td colspan="5" height="8"></td>
             </tr>
          </table>
      </td>
      <td valign="left" width="30%">
        <p><img border="0" src="images/thermometeranimation.gif" width="50" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
      </td>
   </tr>
</table>
 

