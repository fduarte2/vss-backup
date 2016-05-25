<?

  // database parameters
  include("connect.php");

  // Connect to the Oracle Database
  $conn_bni = ora_logon("SAG_OWNER@$bni","SAG");

  if(!$conn_bni)
  {
     printf("Error logging on to the BNI Oracle Server");
     printf("Ora_Errorcode($conn_bni)");
     printf("Please try later");
     exit;
  }

  // Open the Cursor
  $cursor = ora_open($conn_bni);

  
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<script type="text/javascript">

   function trim(s) {
      while (s.substring(0,1) == ' ') {
         s = s.substring(1,s.length);
      }
      while (s.substring(s.length-1,s.length) == ' ') {
        s = s.substring(0,s.length-1);
      }
      return s;
    }

    function validate()
    {
      x = document.miscform;
      filename = x.file1.value;
 
      if (filename == "") {
         alert("You must select a file to import.");
         return false;
      }
        
      return true;
   }
   
</script>

   <p><font size="2" face="Verdana">
   <? if($input){
        printf("<center><b> File Upload Successful! </b></center><br />\n");
      }
   ?>
   Please click on Browse to select the import file (.csv). Click the Upload botton to import the file.</font></p>
 <form action = "misc_process.php" enctype="multipart/form-data" method = "post" name = "miscform" onsubmit = "return validate()">
      <input name="selected_type" type="hidden" value="">
       <table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="0">
	     <tr>
    		<td width="5%" height="8"></td>
		    <td width="30%" height="8"></td>
    		<td width="60%" height="8"></td>
    		<td width="5%" height="8"></td>
	     </tr>
	     <tr>
    		<td>&nbsp;</td>
    		<td align="right" valign="top"><font size="2" face="Verdana">File Name:</font></td>
    		<td align="left"><INPUT TYPE="FILE" NAME="file1" SIZE="30"></td>
    		<td>&nbsp;</td>
       </tr>
             <tr>
	      <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="Submit" value="  Upload  "> <input type="Reset" value="  Reset  "></td>
              <td>&nbsp;</td>
	     </tr>
	     <tr>
    		<td colspan="5" height="8"></td>
	     </tr>
	 </table>
        </form>
<?

ora_close($cursor);
ora_logoff($conn_bni);

?>

