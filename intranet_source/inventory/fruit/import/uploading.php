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

<table width="100%" cellpadding=4 cellspacing=4>
 <tr><td>
   <p><font size="2" face="Verdana">
   Please click on Browse to select the import file. Click the Upload botton to import the file.</font></p>

   <FORM METHOD="POST" ENCTYPE="multipart/form-data" ACTION="process.php" NAME="frmImport" onsubmit="return validate()">
   <input name="selected_type" type="hidden" value="">
   <input type="hidden" name="Cmd" value="New">
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
     <td align="right" valign="top"><font size="2" face="Verdana">Vessel:</font></td>
     <td align="left"><INPUT TYPE="Text" NAME="txtVessel" SIZE="30"></td>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td align="right" valign="top"><font size="2" face="Verdana">Description:</font></td>
     <td align="left"><INPUT TYPE="text" NAME="txtDescription" SIZE="30"></td>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td align="right" valign="top"><font size="2" face="Verdana">Vessel Type:</font></td>
     <td align="left"><select NAME="optVesselType">
        <option>Pacific Seaways</option>
        <option>Lauritzen</option>
        <option>Oppenheimer</option>
        <option>CSAV</option>
        </select></td>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td align="right" valign="top"><font size="2" face="Verdana">Cargo Type:</font></td>
     <td align="left"><select NAME="optCargoType">
        <option>Pacific Seaways</option>
        <option>Lauritzen</option>
        <option>Oppenheimer</option>
        <option>CSAV</option>
        </select></td>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td>&nbsp;</td>
     <td><input type="Submit" value="  Upload  "> <input type="Reset" value="  Reset  "></td>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td colspan="5" height="8"></td>
    </tr>
   </table>
   </FORM>
  </td>
 </tr>
</table>