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

  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");

  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $today = date('m/d/Y');
?>
<style>td{font:12px}</style>
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language="JavaScript">
function print(date)
{
   document.upload.action = "print_seniority_list.php";
   document.upload.date.value = date;
   document.upload.submit();
}
</script>
<form action="update_seniority_process.php" enctype="multipart/form-data" method="post" name="upload">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Update the Seniority of B Employees
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

<table width="100%" align="center"  border="0" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
          <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">
             <tr>
                <td width="5%" height="8"></td>
                <td width="20%" height="8"></td>
                <td width="70%" height="8"></td>
                <td width="5%" height="8"></td>
             </tr>
             <tr>
                <td>&nbsp;</td>
                <td align="left" valign="top"><font size="2" face="Verdana">File Name:</font></td>
                <td align="left"><INPUT TYPE="FILE" NAME="file1" SIZE="30"></td>
                <td>&nbsp;</td>
             </tr>
             <tr>
                <td>&nbsp;</td>
                <td align="left" valign="bottom"><font size="2" face="Verdana">Effective Date:</font></td>
                <td align="left" ><input type="textbox" Name="sDate" value = "<?= $today?>"><a href="javascript:show_calendar('upload.sDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a></td>
                <td>&nbsp;</td>
             </tr>
             <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="Submit" value="  Update  "> <input type="Reset" value="  Reset  "></td>
              <td>&nbsp;&nbsp;</td>
             </tr>
             <tr>
                <td colspan="4" height="8">&nbsp;</td>
             </tr>
             <tr>
                <td colspan="4" height="8">&nbsp;</td>
             </tr>

             <tr>
	      <td>&nbsp;</td>
	      <td colspan="2"><b>Update History:</b></td>
	      <td>&nbsp;</td>
	      <input type=hidden name="date">
             </tr>
<?
        $sql = "select distinct eff_date from employee_seniority order by eff_date desc";
        ora_parse($cursor, $sql);
        ora_exec($cursor);
        while (ora_fetch($cursor)){
                $eff_date = date('m/d/Y', strtotime(ora_getcolumn($cursor, 0)));
?>
             <tr>
              <td>&nbsp;</td>
	      <td colspan=2><a href="javascript:print('<?=$eff_date?>')"><b><?=$eff_date?></b></a></td>
	      <td >&nbsp;</td>
	     </tr>
<?
	}
	ora_close($cursor);
	ora_logoff($conn);
?>	 
		</table>
              <td>&nbsp;</td>
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
</form>
