<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
/*
  if($access_denied){
    printf("Access Denied from FINA system");
    include("pow_footer.php");
    exit;
  }
*/
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hours Paid vs. Billed Amount Report
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp</td>
	  <td>
		<table border="0" width="100%" cellpadding="4" cellspacing="0">
		    <tr>
		      <td width="20%">&nbsp;</td>
			  <td width="80%">&nbsp;</td>
		    </tr>
		    <tr>
		      <form action="create_ship_finance.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Ship number:</font></td>
		      <td align="left">
			     <input type="textbox" name="vessel_number" size="6"></td>
		    </tr>
		    <tr>
		      <form action="create_ship_finance.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Weighted Average Cost:</font></td>
		      <td align="left">
			     $<input type="textbox" name="WAC" size="10"></td>
		    </tr>
			<tr>
			</tr>
			<tr>
				<td align="left">
					<input type="submit" value="Generate Report"></td>
			</tr>
			</form>
		</table>
			  
	 
</table>

<? include("pow_footer.php"); ?>
