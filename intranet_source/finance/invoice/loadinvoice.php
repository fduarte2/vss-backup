<?
$BNI = $HTTP_GET_VARS[BNI];
$RF = $HTTP_GET_VARS[RF];
$CM_DM = $HTTP_GET_VARS[CM_DM];

session_start();
if ($_SESSION['isLoading'] == "Yes"){
     echo "isLoading";
}else{
	echo "loading";
?>

	<html>
	<head>
	</head>
	<body>
	Process Loading Invoice ....... <br \>
	Please wait......
<?
	$_SESSION['isLoading'] = "Yes";

	if ($BNI == "BNI"){include("bni_invoice.php");}
	if ($RF =="RF"){include("rf_invoice.php");}
	if ($CM_DM == "CM_DM"){include("cm_dm.php");}

	$_SESSION['bni_count']=$bni_count;
	$_SESSION['rf_count']=$rf_count;
	$_SESSION['memo_count']=$memo_count;
	$_SESSION['invalid_custs']=$invalid_custs;
	$_SESSION['invalid_address']=$invalid_address;
	$_SESSION['invalid_service_code']=$invalid_service_code;
	$_SESSION['invalid_gl_code']=$invalid_gl_code;
	$_SESSION['invalid_commodity_code']=$invlid_commodity_code;
	$_SESSION['invalid_asset_code']=$invalid_asset_code;

//	$_SESSION['isLoading']="";
?>
	</body>
	<script language="JavaScript">
<!--	window.close();-->
	</script>
	</html>	

<? 
} 
?>
