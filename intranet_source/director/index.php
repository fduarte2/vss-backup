<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Director Applications
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
	 <p align="left">
	    <font size="2" face="Verdana"><b>From here you can access director applications.</b></font>
         </p>
		<font size="2" face="Verdana"><a href="/documents/Remote Email Access card.doc">Remote Email Access Card</a><br /><br />
		<font size="2" face="Verdana"><a href="chilean_OTR.php">Chilean Over-The-Road Cargo</a><br /><br />
		<font size="2" face="Verdana"><a href="/hr/security_gate/NONTWICautorefresh.php">NONTWIC Barcode Monitor</a><br /><br />
		<font size="2" face="Verdana"><a href="Hurricane_Action_Plan_Draft_11Apr_2012.doc">Hurricane Action Plan Draft (4/11/2012)</a><br /><br />
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="images/warehouse_e.jpg" width="218" height="170"></p>
      </td>

   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
