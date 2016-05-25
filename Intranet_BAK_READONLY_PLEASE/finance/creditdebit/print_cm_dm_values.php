<?
/*
*	Adam Walter, Sep 2013
*
*	Select a Credit/Debit Memo for printing
*****************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }
/*
//  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }

  $Short_Term_Cursor = ora_open($conn);
  $modify_cursor = ora_open($conn); */
?>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Print Credit/Debit Memos
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="meh" action="print_cm_dm.php" method="post">
	<tr>
		<td width="15%"><input type="radio" name="printout_type" value="Memo Number" checked>&nbsp;&nbsp;Memo #:</td>
		<td><input type="text" name="memo_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td colspan="2"><b>--- OR ---</b></td>
	</tr>
	<tr>
		<td width="15%"><input type="radio" name="printout_type" value="Single Original Invoice">&nbsp;&nbsp;Original Invoice #<br>(For Freeform,<br>use Memo#):</td>
		<td><input type="text" name="inv_num" size="15" maxlength="15"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>

<? include("pow_footer.php"); ?>