<?
  // 12-MAY-03 - Created by Seth Morecraft
  // Validates an eport user
//  include("connect.php");
//  include("defines.php");

  $web_user = $HTTP_POST_VARS["user"];
  $username = $web_user;
  $web_pass = $HTTP_POST_VARS["pass"];
  $success = "f";
/*
  // open a connection to the database server
  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");

  if(!$connection){
    printf("Could not open connection to database server.  Please go back to <a href=\"../rf_login.php\">Chilean Fruit Login Page</a> and try again later!<br />"); 
    exit;
   }
   // Log the login attempt
   $timestamp = date('Y-m-d H:i:s'); */
   $ip_address = $HTTP_SERVER_VARS['REMOTE_ADDR'];

     $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RFTEST", "RFTEST238");
  if($conn < 1){
    printf("Error logging on to the BNI Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("Please try later!");
    exit;
  }
  $Short_Term_Cursor = ora_open($conn);
  
/*
   // generate and execute a query
   $query = "SELECT * FROM eport_login where username = '" . $web_user . "' and user_type like 'RF%'";
   $result = pg_query($connection, $query) or 
             die("Error in query: $query. " .  pg_last_error($connection));

   // get the number of rows in the resultset
   $row_num = pg_num_rows($result);
*/
   // if records present
	$sql = "SELECT * FROM eport_login where username = '" . $web_user . "' and user_type = 'RF'";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
   if (ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
      // check the password user entered
	  $user_type  = "";
//      $row = pg_fetch_row($result, 0);
      $test_pass = $row['PASSWORD'];
//      $username = $row[EPORT_USERNAME];
      $mail = $row['EMAIL'];
      $theme = $row['THEME'];
      $customer_id = $row['CUSTOMER_ID'];
	  $user_type = $row['USER_TYPE'];
	  $user_subtype = $row['USER_SUBTYPE'];
 
      if($test_pass != $web_pass){
// Incorrect password...
?>
<html>
<head>
<title>Chilean Fruit Login Error</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>

<body link="#336633" vlink="#999999" alink="#999999">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="#4D76A5">
         <table border="0" cellpadding="4" cellspacing="0">
            <tr>
               <td width = "10%">&nbsp;</td>
               <td width = "90%" height = "500" valign = "top">
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
                     <font size="5" face="Verdana" color="#0066CC">Login Error</font>
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
                  <font size="3" face="Verdana" color="#000080">Incorrect
Password!  Please <a href="../rf_login.php">go back and try again.</a>
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

<?
        //endif
      }
      else{ // Password matches!
       setcookie("eport_user", "$username", time() + 86400, "/");
       setcookie("eport_email", "$mail", time() + 86400, "/");
       setcookie("eport_theme", "$theme", time() + 86400, "/");
       setcookie("eport_customer_id", "$customer_id", time() + 86400, "/");
	   setcookie("eport_user_type", "$user_type", time() + 86400, "/");
	   setcookie("eport_user_subtype", "$user_subtype", time() + 86400, "/");
       $test_pass = "";
       $web_pass = "";
       $success = "T";
       $sql = "insert into eport_user_logins
					(USERNAME, LOGIN_DATE, IP_ADDRESS, RESULT, LOGIN_SYSTEM)
				values 
					('".$username."', SYSDATE, '".$ip_address."', '".$success."', 'rf')";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
       if ( strtoupper($user) == "PSWEXPORTER" ) {
		  header("Location: xptr/index.php");		
		  exit;
		}
		else {
			header("Location: rf/index.php");
			exit;
		}
	   
      }
    }
	$sql = "insert into eport_user_logins
				(USERNAME, LOGIN_DATE, IP_ADDRESS, RESULT, LOGIN_SYSTEM)
			values 
				('".$username."', SYSDATE, '".$ip_address."', '".$success."', 'rf')";
	ora_parse($Short_Term_Cursor, $sql);
	ora_exec($Short_Term_Cursor);
 
  header("Location: rf_login.php?input=1");
 
?>
