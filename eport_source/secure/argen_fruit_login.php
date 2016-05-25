<?
   $user = $HTTP_COOKIE_VARS["eport_user_v2"];
   if($user != ""){
      header("Location: argen_fruit/index.php");
      exit;
   }

	$conn = ora_logon("SAG_OWNER@RF", "OWNER");
	//$conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		printf("Please try later!");
		exit;
	}
	$Short_Term_Cursor = ora_open($conn);


	
	$submit = $HTTP_POST_VARS["submit"];
	if($submit != ""){

		$ip_address = $HTTP_SERVER_VARS['REMOTE_ADDR'];

		$web_user = $HTTP_POST_VARS["user"];
		$web_pass = $HTTP_POST_VARS["pass"];

		$sql = "SELECT * FROM eport_login_v2 where username = '".$web_user."' and PASSWORD = '".$web_pass."' AND ARGEN_FRUIT_MODULE = 'Y'";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		if (ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			setcookie("eport_user_v2", "$web_user", time() + 86400, "/");
			setcookie("eport_customer_id", "1626", time() + 86400, "/");
			$sql = "insert into eport_user_logins
						(USERNAME, LOGIN_DATE, IP_ADDRESS, RESULT)
					values 
						('".$web_user."', SYSDATE, '".$ip_address."', 'T')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
		    header("Location: argen_fruit/index.php");
			exit;
		} else {
			$sql = "insert into eport_user_logins
						(USERNAME, LOGIN_DATE, IP_ADDRESS, RESULT)
					values 
						('".$web_user."', SYSDATE, '".$ip_address."', 'F')";
			ora_parse($Short_Term_Cursor, $sql);
			ora_exec($Short_Term_Cursor);
			$bad_display = "<br><font color=\"#FF0000\"><b>Username/Password incorrect.</b></font><br>";
		}
	}












?>
<html>
<head>
<title>Port of Wilmington Eport - Chilean Fruit Access Page</title>
</head>

<body  bgcolor="#FFFF99" topmargin="0" leftmargin="0" link="#336633" vlink="#999999" alink="#999999">

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
            	  <font size="5" face="Verdana" color="#330000"><b>Argentine Fruit Customer Login</b></font>
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
		  <font size="3" face="Verdana" color="#330000">
		     <b>Welcome to the Argentine Fruit Login Page!</b>
		  </font><br /><br />
		  <font size="2" face="Verdana" color="#330000">
		     Please enter your user name and password.<br />
		     Click Submit to enter or Reset button to clear this form.<br /><br />
		  </font><? echo $bad_display; ?>
        	  </p>
	       </td>
	    </tr>
	    <tr>
	       <td>&nbsp;</td>
	       <td align="center">
		  <form method="Post" action="argen_fruit_login.php">
		  <table border="0" cellpadding="4" cellspacing="4">
		     <tr>
			<td width="30%" align="center">
			   <font size="2" face="Verdana" color="#330000">Username:</font>
			</td>
			<td width="70%" align="left">
                        <input type="textbox" name="user" size="24">
		        </td>
		     </tr>
	 	     <tr>
	   	        <td align="center"><font size="2" face="Verdana" color="#330000">Password:</font></td>
	   	        <td align="left"><input type="password" name="pass" size="24"></td>
		     </tr>
		     <tr>
	   	        <td colspan="2" align="center">
			   <table>
			      <tr>
				 <td width="45%" align="center"> 
			   	    <input type="Submit" name="submit" value="Submit">
				 </td>
				 <td width="10%">&nbsp;</td>
				 <td width="45%" align="center">
			            <input type="Reset" value="Reset">
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
