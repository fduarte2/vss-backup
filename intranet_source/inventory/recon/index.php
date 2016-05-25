<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

         <table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">On Hold Pallets
                  </font>
                  <hr>
               </td>
            </tr>
         </table>
         <table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="1%">&nbsp;</td>
                <td valign="top">
                 
   		<p><font size="2" face="Verdana">
			Please select a file to process on hold pallets.
   		</font></p>

   <form method="post"  enctype="multipart/form-data" action="process.php" name="myform">
	<table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
             
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
			    <input type="Submit" value="  Update  ">&nbsp;&nbsp;
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
   </form>
                  </font></p>
               </td>
            </tr>
	    <tr>
		<td width="1%">&nbsp;</td>
		<td><font size="2" face="Verdana" color = "#ff0000">
		    <? echo $HTTP_GET_VARS[msg] ?>
		    </font>	
		</td>
	    </tr>	
         </table>
         <?
            include("pow_footer.php");
         ?>
