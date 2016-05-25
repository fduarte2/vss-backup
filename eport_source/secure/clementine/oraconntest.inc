<?
/* Fernando Duarte, Nov. 2014.
   Oracle Connection File */

//RF Connection Details //

// Test Database //

$user="SAG_OWNER";
$pass="RFTEST238";
$database="RFTEST"; 

//RF Connection Command //

$rfconn = ocilogon($user, $pass, $database);

// Check in case there is a problem connecting to the database. //

if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}


?>

