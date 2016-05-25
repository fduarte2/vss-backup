<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }
  $date = $HTTP_GET_VARS['date'];

  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);
  
  $sql = "select cutoff from employee_hire_cutoff where hire_date = to_date('$date','mm/dd/yyyy')";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch($cursor)){
	$cutoff = ora_getcolumn($cursor, 0);
  }
?>
<form action="hire_cutoff_process.php"  method="post" name="hire">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Daily Hiring Cutoff for B Employees
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
                <td width="20%" height="8"></td>
                <td width="70%" height="8"></td>
                <td width="5%" height="8"></td>
             </tr>
             <tr>
                <td>&nbsp;</td>
                <td align="right"><font size="2" face="Verdana">Date:</font></td>
		<td align="left"><?= $date ?>
			<input type="hidden" name="date" size=10 value="<?= $date?>"></td>
		<td>&nbsp;</td>
             </tr>
             <tr>
                <td>&nbsp;</td>
                <td align="right" valign="top"><font size="2" face="Verdana">Cutoff #:</font></td>
                <td align="left"><input type="textbox" name="cutoff" size=10 value="<?= $cutoff ?>">
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
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
      </td>
   </tr>
</table>

