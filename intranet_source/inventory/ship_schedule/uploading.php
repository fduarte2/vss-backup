<script language="JavaScript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate()
   {
      x = document.myform
      filename = x.file1.value

      if (filename == "")
      {
         alert("You must select a file to import.")
         return false
      }
      return true
   }
</script>

   <p><font size="2" face="Verdana">
   <? if($input){
        printf("<center><b> File Upload Successful! </b></center><br />\n");
      }
   ?>
   Please click on Browse to select the ship schedule PDF file. Click the Upload botton to import the schedule.</font></p>

   <FORM METHOD="POST" ENCTYPE="multipart/form-data" ACTION="process.php" NAME="myform" onsubmit="return validate()">
	<input name="selected_type" type="hidden" value="">
	<table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="0">
	     <tr>
		<td width="5%" height="8"></td>
		<td width="25%" height="8"></td>
		<td width="20%" height="8"></td>
		<td width="45%" height="8"></td>
		<td width="5%" height="8"></td>
	     </tr>
	     <tr>
		<td>&nbsp;</td>
		<td align="right" valign="top">
		   <font size="2" face="Verdana">File Name:</font></td>
		<td colspan="2" align="left">
		   <INPUT TYPE="FILE" NAME="file1" SIZE="30"></td>
		<td>&nbsp;</td>
	     </tr>
	     <tr>
		<td>&nbsp;</td>
	   	<td colspan="3" align="center">
		   <table>
		      <tr>
			 <td width="55%" align="right"> 
			    <input type="Submit" value="  Upload  ">
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
		<td colspan="5" height="8"></td>
	     </tr>
	 </table>
   </FORM>
