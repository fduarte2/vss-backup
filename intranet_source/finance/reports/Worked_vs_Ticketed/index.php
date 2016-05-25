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
	    <font size="5" face="Verdana" color="#0066CC">LCS Worked Hours vs. Hours Ticketed
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
		      <td width="9%">&nbsp;</td>
			  <td width="91%">&nbsp;</td>
		    </tr>
		    <tr>
		      <form action="Worked_vs_Ticketed.php" method="post">
			  <td align="left" valign="top">
			     <font size="2" face="Verdana">Date:</font></td>
		      <td align="left">
			     <input type="textbox" name="report_date" value="MM/DD/YYYY" size="15"></td>
		    </tr>
			<tr>
			</tr>
			<tr>
				<td align="left">
					<input type="submit" value="submit"></td>
			</tr>
			</form>
		</table>
	</td>
  </tr>
</table>

<? include("pow_footer.php"); ?>
