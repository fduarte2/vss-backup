<table bgcolor="#f0f0f0" border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
       <td align="right" valign="top">
    	  <font size="2" face="Verdana">Pallet ID:</font></td>
       <td align="left" valign="middle">
 	  <form action="set_pallet.php" method="Post" name="lot_form">
    	     <input type="textbox" name="lot_free" size="20" maxlength="30" value="">
       </td>
       <td align="left" valign="middle">
             <select name="schema">
             <?
               $current = date('Y');
               for($i = 1999; $i <= $current; $i++){
                 printf("<option value=\"ARCH_$i\">$i</option>");
               }
               printf("<option value=\"SAG_OWNER\" selected>Current Season</option>\n");
             ?>
             </select>
       </td>
	    <tr>
	       <td colspan="4" align="center">
		   <table border="0">
		      <tr>
			 <td width="10%">&nbsp;</td>
			 <td width="30%" align="right"> 
			    <input type="Submit" value="Select the Pallet">
			 </td>
			 <td width="5%">&nbsp;</td>
			 <td width="10%" align="left">
			    <input type="Reset" value=" Reset ">
			 </td>
         		 </form>
			 <td width="5%">&nbsp;</td>
			  </form>
			 <td width="10%">&nbsp;</td>
      		      </tr>
		   </table>		
	   	</td>
	     </tr>
	     <tr>
		<td colspan="4" height="8"></td>
	     </tr>
</table>
