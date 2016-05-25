<?
/* Adam Walter, 8/25/07
*
*	This page will allow designated HR representatives to re-run
*	The ATS crosscheck spreadsheet (which normally is only done
*	Tuesdays at noon).
*
*	To prevent confusion, I am locking it out prior to Tuesday at noon,
*	As I don't want people getting old or "preview" versions of it.
***************************************************************************/
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ATS Crosscheck Spreadsheet Rerun";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");

  if($access_denied){
    printf("Access Denied from ATS");
    include("pow_footer.php");
    exit;
  }

//	11/24/08: 10:30 AM IT Note:  Adam, noticed hardcoding here.  Need it replaced by table like for LCS.  11/24/08
//	if(date("w") == 0 || date("w") == 1 || date("w") == 6 || (date("w") == 2 && date("H") < 8)){

//     IT Note:  Took out hardcoding to not allow Monday "w" == 1
//	if(date("w") == 0 || date("w") == 6 || (date("w") == 2 && date("H") < 8)){

//	11/24/08: 11 AM IT Note:  Put things back to how they were before 10:30

//  Dec 20, 2008.  changing to allow force-run of croscheck to after Monday, 10AM.
//  Cannot really go any earlier, as the auto-gen timesheets happen at 10AM.
	if(date("w") == 0 || date("w") == 6 || (date("w") == 1  && date("H") < 10)){
//	if(date("w") == 6 || (date("w") == 1  && date("H") < 10)){ // the line to use if you want to run this page on Sunday.  10/27/2012
		printf("This page can only be accessed after the Monday @ 10:00AM Auto-generation of employee timesheets.<br>Please check back then.");
		include("pow_footer.php");
		exit;
	}

	if ($submit != ""){
		exec("/web/web_pages/TS_Program/ATScron/ATS_hr_crosscheck.sh");
		printf("Re-run of Crosscheck Spreadsheet complete.  Please check Outlook for the result.");
		include("pow_footer.php");
		exit;
	} else {
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">ATS Crosscheck Rerun
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="employee_select" action="ATS_crosscheck_rerun.php" method="post">
	<tr>
		<td align="center"><input type="submit" name="submit" value="Resend Crosscheck Spreadsheet"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");
	}
?>
