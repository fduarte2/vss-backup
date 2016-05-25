<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Security Scan Upload";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  $msg = $HTTP_GET_VARS['msg'];
  $count = $HTTP_GET_VARS['count'];
?>

<script language="JavaScript">
function abcd()
{
    document.upload_form.upload.disabled=true;
    document.location = "http://scanner-trm-sec/security_upload/guard_upload.php";		
}
</script>
<script language="JavaScript" src="/functions/calendar.js"></script>
<form name=upload_form>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Security Log Upload
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
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="upload" value=" Upload " onclick="abcd()">
        </p>
<?	if ($msg <>""){		?>
        <p>
                <font color=red><?= $msg?></font>
        </p>


<? 	}else if ($count <> ""){	?>
	<p>
		<font color=red> Total upload:<?= $count?></font> 
        </p>
<?	}	?>
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
<br />

<? include("pow_footer.php"); ?>
