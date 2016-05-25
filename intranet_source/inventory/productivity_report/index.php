<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Productivity Report
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
	    <font size="2" face="Verdana"><b>From here you can create a productivity report for a given ship.</b></font>
         </p>
	  </td>
   </tr>
   <tr>
      <td width="1%">&nbsp</td>
	  <td>
		<table border="0" width="100%" cellpadding="4" cellspacing="0">
		    <tr>
		      <td width="20%">&nbsp;</td>
			  <td width="80%">&nbsp;</td>
		    </tr>
		    <tr>
		      <form action="create_ship_productivity.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Ship number:</font></td>
		      <td align="left">
			     <input type="textbox" name="vessel_number" size="5"></td>
		    </tr>
		    <tr>
		      <td align="left" valign="top">
			     <font size="2" face="Verdana">Commodity Code(s):</font></td>
			  <td align="left">
			     <input type="textbox" name="commodity1" size="5">&nbsp;&nbsp;
			     <input type="textbox" name="commodity2" size="5">&nbsp;&nbsp;
			     <input type="textbox" name="commodity3" size="5">&nbsp;&nbsp;
			     <input type="textbox" name="commodity4" size="5">&nbsp;&nbsp;
			     <input type="textbox" name="commodity5" size="5">
			  </td>
			</tr>
			<tr>
				<td align="left" valign="top">
					<font size="2" face="Verdana">Tonnage:</font></td>
				<td align="left">
					<input type="textbox" name="tonnage1" size="5">&nbsp;&nbsp;
					<input type="textbox" name="tonnage2" size="5">&nbsp;&nbsp;
					<input type="textbox" name="tonnage3" size="5">&nbsp;&nbsp;
					<input type="textbox" name="tonnage4" size="5">&nbsp;&nbsp;
					<input type="textbox" name="tonnage5" size="5">
				</td>
			</tr>
		    <tr>
		      <td align="left" valign="top">
			     <font size="2" face="Verdana">Completion date:</font></td>
			  <td align="left">
			     <input type="textbox" name="completion_date" size="15" value="MM/DD/YYYY"></td>
			</tr>
			<tr>
				<td align="left" valign="top">
					<font size="2" face="Verdana">Completion time:</font></td>
				<td align="left">
					<input type="textbox" name="completion_time" size="15" value="HH:MM AM/PM"></td>
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
