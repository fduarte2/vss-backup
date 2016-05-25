<?
/* Fernando Duarte, Nov. 2014.
   Oracle Connection File */

//RF Connection Details //

// Live Database //

$user="SAG_OWNER";
$pass="OWNER";
$database="RF";

$rfconn = ocilogon($user, $pass, $database);

// Check in case there is a problem connecting to the database. //

if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}


?>

