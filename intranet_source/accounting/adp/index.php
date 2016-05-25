<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "ADP Import";
  $area_type = "ACCT";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from ACCT system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">ADP Import</font>
         <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
   <p><font size="2" face="Verdana">
   Please click on Browse to select the file for import.
   Click Import botton to begin the transaction.</font></p>

   <FORM METHOD="POST" ENCTYPE="multipart/form-data" ACTION="process.php" NAME="myform">
	<table align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	     <tr>
		<td colspan="4">&nbsp;</td>
	     </tr>
	     <tr>
		<td width="5%">&nbsp;</td>
		<td width="25%" align="right" valign="top">
		   <font size="2" face="Verdana">File Name:</font></td>
		<td width="65%" align="left">
		   <INPUT TYPE="FILE" NAME="file1" SIZE="30"><br /></td>
		<td width="5%">&nbsp;</td>
	     </tr>
	     <tr>
		<td>&nbsp;</td>
	   	<td colspan="2" align="center">
		   <table>
		      <tr>
			 <td width="55%" align="right"> 
			    <input type="Submit" value="  Import  ">&nbsp;&nbsp;
			 </td>
			 <td width="10%">&nbsp;</td>
			 <td width="35%" align="left">
			    <input type="Reset" value="  Reset  ">
			 </td>
    		      </tr>
		   </table>		
	   	</td>
		<td>&nbsp;</td>
	     </tr>
	     <tr>
		<td colspan="4">&nbsp;</td>
	     </tr>
	 </table>
   </FORM>
  </td>
 </tr>
 <tr>
	<td width="1%">&nbsp;</td>
	<td align="center"><a href="adp_table.php"><font size="2" face="Verdana">Update GL Benefit Table</font></a></td>
 </tr>
 <tr>
	<td colspan="2">&nbsp;</td>
 </tr>
 <tr>
	<td colspan="2">&nbsp;</td>
 </tr>
 <tr>
	<td width="1%">&nbsp;</td>
	<td colspan="2" align="center"><a href="import_tips.doc" target="_new"><font size="2" face="Verdana">Import Documentation</font></a></td>
 </tr>
</table>


<? include("pow_footer.php"); ?>
