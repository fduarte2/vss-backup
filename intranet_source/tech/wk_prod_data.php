<?

	 //All POW files need this session file included
	include("pow_session.php");
	
	//Define some vars for the skeleton page
	$user = $userdata['username'];
	$title = "TS - Weekly Productivity";
	$area_type = "TECH";

	// Provides header / leftnav
	include("pow_header.php");
	if($access_denied)
	{
		printf("Access Denied from LCS system");
		include("pow_footer.php");
		exit;
	}
	
	// Only Jon Jaffe will be granted access to this page
	//if ($user == "jjaffe" || $user == "hdadmin")
	//{
	//	// Do nothing 		
	//}
	//else
	//{
	//	printf("You do not have access to this page.");
	//	include("pow_footer.php");
	//	exit;
	//
	//}
?>
	<br><br><br>
	<form action="gen_wk_prod_data.php" enctype="multipart/form-data" method="post" name="wkprod" >
		<input type="Submit" value="Email Me Weekly Productivity Report Data" >
	</form>
	<p>Data will be emailed to Inigo, Jon and Paul</p>
