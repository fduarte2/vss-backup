<?
   $user = $HTTP_COOKIE_VARS["eport_user"];
   $type = $HTTP_COOKIE_VARS["eport_user_type"];
   if($user != "" && $type == "CAN_REL"){
      header("Location: canadian_release/index_canadian.php");
      exit;
   }
?>

<html>
<head>
<title>Port of Wilmington Eport - Canadian Release Access Page</title>
</head>

<body  bgcolor="#FFFFFF" topmargin="0" leftmargin="0" link="#336633" vlink="#999999" alink="#999999">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" height = "500" valign = "top"  bgcolor="#4D76A5">
         <table>
            <tr>
               <td width = "10%">&nbsp;</td>
               <td width = "90%">
                  <? include("leftnav.php"); ?>
               </td>
            </tr>
         </table>
      </td>
      <td width = "90%" valign="top">
	 <table border="0" width="65%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
      	       <td>
         	  <p align="left">
            	  <font size="5" face="Verdana" color="#0066CC">All Container Release Login</font>
            	  <hr>
         	  </p>
	       </td>
            </tr>
         </table>
	 
	 <table border="0" width="65%" cellpadding="4" cellspacing="1">
	    <tr>
      	       <td width="1%">&nbsp;</td>
	       <td>
		  <p align="center">
		  <font size="3" face="Verdana" color="#000080">
		     <b>Welcome to the Login Page!</b>
		  </font><br /><br />
		  <font size="2" face="Verdana">
	       <?
	       if($input == "1"){
		 echo "<b>Invalid Username- please try again!<br /></b>";
	       }
               ?>
		     Please enter your user name and password.<br />
		     Click Submit to enter or Reset button to clear this form.<br /><br />
		  </font>
        	  </p>
	       </td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="center">
		  <form method="Post" action="canadian_release_pass.php">
		  <table border="0" cellpadding="4" cellspacing="4">
		     <tr>
			<td width="30%" align="center">
			   <font size="2" face="Verdana">Username:</font>
			</td>
			<td width="70%" align="left">
                        <input type="textbox" name="user" size="24">
		        </td>
		     </tr>
	 	     <tr>
	   	        <td align="center"><font size="2" face="Verdana">Password:</font></td>
	   	        <td align="left"><input type="password" name="pass" size="24"></td>
		     </tr>
		     <tr>
	   	        <td colspan="2" align="center">
			   <table>
			      <tr>
				 <td width="45%" align="center"> 
			   	    <input type="Submit" value="  Submit  ">
				 </td>
				 <td width="10%">&nbsp;</td>
				 <td width="45%" align="center">
			            <input type="Reset" value="  Reset   ">
				 </td>
    			      </tr>
		           </table>		
	   	        </td>
		     </tr>
		  </table>
	          </form><br /><br />
	       </td>
	    </tr>
         </table>
         </p>
         <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
