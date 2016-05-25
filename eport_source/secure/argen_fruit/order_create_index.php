<?
	include("v2_valid.php");
	include("argen_fruit_db_def.php");
	include("set_values.php");
	$user = $HTTP_COOKIE_VARS["eport_user_v2"];
	if($user == ""){
      header("Location: ../argen_fruit_login.php");
      exit;
   }

	$auth_exp = PageUserCheck($user, "ARGEN_FRUIT_EXPED", $rfconn);
	$auth_port = PageUserCheck($user, "ARGEN_FRUIT_PORT", $rfconn);

	// these values come fromt he child page.  The logic is being done here because I need to page-redirect afterwards,
	// which I can't do if the page output has already been passed.
	$submit = $HTTP_POST_VARS['submit'];
	if($auth_exp == "WRITE" && $submit == "Create New Order"){
		$order_num = strtoupper($HTTP_POST_VARS['order_num']);
		$order_num = str_replace(" ", "", $order_num);
		$cust = $HTTP_POST_VARS['cust'];
		$cons = $HTTP_POST_VARS['cons'];
		$trans_num = $HTTP_POST_VARS['trans_num'];
		$expec_date = $HTTP_POST_VARS['expec_date'];
//		$voucher = $HTTP_POST_VARS['voucher'];
		$cust_po = str_replace("'", "`", $HTTP_POST_VARS['cust_po']);
		$cust_po = str_replace("\\", "", $cust_po);

		$bad_message = "";

		// mak sure they aren't creating a new order the same as an old one
		$sql = "SELECT COUNT(*) THE_COUNT
				FROM ARGENFRUIT_ORDER_HEADER
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		if(ociresult($stid, "THE_COUNT") >= 1){
			$bad_message .= "The entered Order# already exists.<br>";
		}

		// also, make sure that the "non-null" selections are made
		if($order_num == ""){
			$bad_message .= "An Order# must be entered.<br>";
		}
		if($cust == ""){
			$bad_message .= "A Customer must be selected.<br>";
		}
		if($expec_date != "" && !ereg("^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$", $expec_date)){
			$bad_message .= "Expected Date must be in MM/DD/YYYY format.<br>";
		}
//		if($voucher == ""){
//			$bad_message .= "A Voucher must be selected.<br>";
//		}
//		if($cons == ""){
//			$bad_message .= "A Consignee must be selected.<br>";
//		}

		// if the checks pass, do it.
		if($bad_message != ""){
//			echo "<font color=\"#FF0000\">New Order could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		} else {
			//VOUCHER_NUM, '".$voucher."',
			$sql = "INSERT INTO ARGENFRUIT_ORDER_HEADER
						(STATUS,
						ORDER_NUM,
						CUSTOMER_ID,
						CUSTOMER_PO,
						EXPECTED_DATE, 
						CONSIGNEE_ID,
						LOAD_SEQ,
						TRANSPORT_ID)
					VALUES
						('1',
						'".$order_num."',
						'".$cust."',
						'".$cust_po."',
						TO_DATE('".$expec_date."', 'MM/DD/YYYY'),
						'".$cons."',
						'1',
						'".$trans_num."')";
//			echo $sql."<br>";
			$stid = ociparse($rfconn, $sql);
			ociexecute($stid);

			header("Location: ./order_edit_index.php?order_num=".$order_num);
		}
	}


?>

<html>
<head>
<title>Eport of Wilmington - Dole Wing-H Home</title>
</head>

<body  BGCOLOR=#FFFF99 topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("header.php"); ?> 
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="<? echo $left_color; ?>">
         <table cellpadding="1">
	    <tr>
	       <td width = "10%">&nbsp;</td>
	       <td width = "90%" valign = "top" height = "500">
		  <? include("leftnav.php"); ?>
	       </td>
	    </tr>
	 </table>
      </td>
      <td width = "90%" valign="top">
<? 
	if($auth_exp !== false || $auth_port !== false){
		include("order_create.php");
	} else {
		echo "<font color=\"#FF0000\"><b>User not authorized to view this page.</b></font>";
	}
?>
	 <? include("footer.php"); ?>
      </td>
   </tr>
</table>

</body>
</html>
