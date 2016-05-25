<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Load Invoices to Oracle";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }
?>

<?
 $BNI = $HTTP_POST_VARS[BNI];
 $RF = $HTTP_POST_VARS[RF];
 $CM_DM = $HTTP_POST_VARS[CM_DM];

 if ($BNI == "BNI"){include("bni_invoice.php");}
 if ($RF =="RF"){include("rf_invoice.php");}
 if ($CM_DM == "CM_DM"){include("cm_dm_finapps.php");}
 if ($V2 == "V2"){include("bill_v2_orapush.php");}

 $msg = "";
 $msg2 = "";


 if($bni_count>0){
	$msg .= $bni_count." BNI invoice(s) were loaded to Oracle.<br \>";
 }
 if($rf_count>0){
	$msg .= $rf_count." RF invoices(s) were loaded to Oracle.<br \>";
 }
 if ($memo_count >0){
	$msg .= $memo_count." Credit/Debit Memo(s) were loaded to Oracle.<br \>";
 }
 if ($v2_count >0){
	$msg .= $v2_count." V2 Bills were loaded to Oracle.<br \>";
 }
 if($invalid_custs <>""){
        $msg2 .= "The following customers were not found in Oracle's Database:<br \>$invalid_custs<br \>";
 }

 if($invalid_address <>""){
        $msg2 .= "The following customers did not have bill to address in Oracle's Database:<br \>$invalid_address<br \>";
 }
 

 if($invalid_service_code <>""){
        $msg2 .= "The following service code were not found in Oracle's Database:<br \>$invalid_service_code<br \>";
 }

 if($invalid_gl_code <>""){
        $msg2 .= "The following gl code were not found in Oracle's Database:<br \>$invalid_gl_code<br \>";
 }

 if($invalid_commodity_code <>""){
        $msg2 .= "The following commodity code were not found in Oracle's Database:<br \>$invalid_commodity_code<br \>";
 }

 if($invalid_asset_code <>""){
        $msg2 .= "The following asset code were not found in Oracle's Database:<br \>$invalid_asset_code<br \>";
 }

?>

<script language="JavaScript"> 
function submitForm(){
	if (document.main.isLoading.value=="Yes")
	{
		alert("Another Process is loading invoice(s) right now. Please try later!");
		return false;
	}
	else
	{	var reply = confirm("Are you sure you want to load invoice to Oracle Finance?")
		if (reply){
			document.main.isLoading.value = "Yes";
			showPopup();
			return true;
		}
		else
		{
			return false;
		}
	}
}
function showPopup(){
    if (document.main.isLoading.value=="Yes")
    {	
	var myPopup = window.createPopup();
	var myPopupBody = myPopup.document.body;
	myPopupBody.style.border = "solid red 3px";
	myPopupBody.innerHTML ="Loading Invoice(s)......       <br \> Please Wait......"
	myPopup.show(150, 270, 200, 50, document.body);
    }
}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Load Invoices to Oracle
          </font>
          <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <p align="left">
	    <font size="2" face="Verdana"><b>Please select invoice type.</b></font>
         </p>
         <p>
            <form action="index.php" method="post" name="main" onSubmit="return submitForm();">
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="BNI" value="BNI">BNI Invoice<br \>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="RF" value="RF">RF Invoice<br \>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="CM_DM" value="CM_DM">Credit/Debit Memo<br \>
                &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="V2" value="V2">V2 Billing System<br \><br \>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit" name="btnSubmit">&nbsp;&nbsp;&nbsp;
	 <!--	<input type="reset" value="Reset" name="btnReset"> -->
		<input type="hidden" name="isLoading" value="">
            </form>
         </p>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="/finance/images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
	<td></td>
	<td colspan = "2"><? echo $msg ?>
	</td>
   </tr>
   <tr>
        <td></td>
        <td colspan = "2"><font color="FF0000"><? echo $msg2 ?></font>
        </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
