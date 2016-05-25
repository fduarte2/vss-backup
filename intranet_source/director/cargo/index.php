<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Cargo Stats";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
?>

<script language="JaveScript">
function exportToExcel()
{
        //alert('export to excel');
}
</script>
<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Cargo Statistics by Commodity
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p>
	    <font size="2" face="Verdana">Please select start date and end date.<br /><br /><br />

	 </p>
	 <p>
            <form action="cargo.php" method="post" name="cargo">
	       Start Date: <input type="textbox" name="start_date" size=15 value="<?// echo $date; ?>"><a href="javascript:show_calendar('cargo.start_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><br />

              <br /> End Date:&nbsp;&nbsp;&nbsp;<input type="textbox" name="end_date" size=15 value="<?// echo $date; ?>"><a href="javascript:show_calendar('cargo.end_date');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="../images/show-calendar.gif" width=24 height=22 border=0></a><br />
              <br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset">
      	      <br /><br />
	      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' value="Export to Excel" name="export">
            </form>
         </p>
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
<br />

<? include("pow_footer.php"); ?>
