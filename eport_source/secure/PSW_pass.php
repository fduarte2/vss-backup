<?
  // 12-MAY-03 - Created by Seth Morecraft
  // Validates an eport user
  include("connect.php");
  include("defines.php");

  $web_user = $HTTP_POST_VARS["user"];
  $username = $web_user;
  $web_pass = $HTTP_POST_VARS["pass"];
  $success = "f";

  // open a connection to the database server
  $connection = pg_connect ("host=$host dbname=$db user=$dbuser");

  if(!$connection){
    printf("Could not open connection to database server.  Please go back to <a href=\"../rf_login.php\">Chilean Fruit Login Page</a> and try again later!<br />"); 
    exit;
   }
   // Log the login attempt
   $timestamp = date('Y-m-d H:i:s');
   $ip_address = $HTTP_SERVER_VARS['REMOTE_ADDR'];

   

   // generate and execute a query
   $query = "SELECT * FROM eport_login where username = '" . $web_user . "' and user_type like 'RF%'";
   $result = pg_query($connection, $query) or 
             die("Error in query: $query. " .  pg_last_error($connection));
	print $query;
   // get the number of rows in the resultset
   $row_num = pg_num_rows($result);

   // if records present
   if ($row_num > 0){
      // check the password user entered
	  $user_type  = "";
      $row = pg_fetch_row($result, 0);
      $test_pass = $row[EPORT_PASSWORD];
      $username = $row[EPORT_USERNAME];
      $mail = $row[EPORT_EMAIL];
      $theme = $row[EPORT_THEME];
      $customer_id = $row[EPORT_CUSTOMER_ID];
	  $user_type = $row[USER_TYPE];
	  $user_subtype = "subtype - " | $row[user_subtype] | "<--";

 //----- test
  if ( $user_subtype == "exporter" ) {
		print "Exporter     ";
		print $user | "    ";
		print $user_type | "    ";
		print $row | "    ";
		print $test_pass | "    ";
        print $username | "    ";
        print $mail | "    ";
        print $theme | "    ";
        print $customer_id | "    ";
	    print $user_type | "    ";
	    print $user_subtype | "    ";

		 //header("Location: rf/index.php");
		 // exit;
		  
	   }
	   else {
 		 print "Not Exporter     ";
		
		print $user | "    ";
		print $user_type | "    ";
		print $row | "    ";
		print $test_pass | "    ";
        print $username | "    ";
        print $mail | "    ";
        print $theme | "    ";
        print $customer_id | "    ";
	    print $user_type | "    ";
	    print $user_subtype | "    ";
	     //header("Location: rf/index.php");
		 //exit;
	   }
 //-----

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
       setcookie("eport_user", "$username", time() + 86400);
       setcookie("eport_email", "$mail", time() + 86400);
       setcookie("eport_theme", "$theme", time() + 86400);
       setcookie("eport_customer_id", "$customer_id", time() + 86400);
	   setcookie("eport_user_type", "$user_type", time() + 86400);
	   setcookie("eport_user_subtype", "$user_subtype", time() + 86400);
       $test_pass = "";
       $web_pass = "";
       $success = "t";
       $sql = "insert into eport_user_logins values ('$username', '$timestamp', '$ip_address', '$success')";
       $result = pg_query($connection, $sql) or 
             die("Error in query: $sql. " .  pg_last_error($connection));
       if ( $user = "PSWExporter" ) {
		  header("Location: rf/index.php");		
		  exit;
			}
			else {
				header("Location: rf/index.php");
				exit;
			}
	   
      }
    }
  $sql = "insert into eport_user_logins values ('$username', '$timestamp', '$ip_address', '$success')";
  $result = pg_query($connection, $sql) or 
             die("Error in query: $sql. " .  pg_last_error($connection));
  pg_close($connection);
 
  header("Location: rf_login.php?input=1");
 
?>
