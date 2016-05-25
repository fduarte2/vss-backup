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
	$order_num = $HTTP_POST_VARS['order_num'];
	if($order_num  == ""){
		$order_num = $HTTP_GET_VARS['order_num'];
	}
	if($auth_port == "WRITE" && $submit == "Create New CheckInID"){
		$sql = "SELECT ARGEN_CHECKIN_ID_SEQ.NEXTVAL THE_NEXT FROM DUAL";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$driver_num = ociresult($stid, "THE_NEXT");

		$sql = "INSERT INTO ARGENFRUIT_CHECKIN_ID
					(CHECKIN_ID)
				VALUES
					('".$driver_num."')";
		$insert = ociparse($rfconn, $sql);
		ociexecute($insert);
	}
	if($auth_port == "WRITE" && $submit == "Save CheckIn Details"){
		$driver_num = $HTTP_POST_VARS['driver_num'];
		$driver_num = $HTTP_POST_VARS['driver_num'];
		$bad_message = "";

		$temp_rec = str_replace("'", "`", $HTTP_POST_VARS['temp_rec']);
		$temp_rec = str_replace("\\", "", $temp_rec);
		$driver_name = str_replace("'", "`", $HTTP_POST_VARS['driver_name']);
		$driver_name = str_replace("\\", "", $driver_name);
		$driver_phone = str_replace("'", "`", $HTTP_POST_VARS['driver_phone']);
		$driver_phone = str_replace("\\", "", $driver_phone);
		$in_time = str_replace("'", "`", $HTTP_POST_VARS['in_time']);
		$in_time = str_replace("\\", "", $in_time);
//		$out_time = str_replace("'", "`", $HTTP_POST_VARS['out_time']);
//		$out_time = str_replace("\\", "", $out_time);
//		$signature = str_replace("'", "`", $HTTP_POST_VARS['signature']);
//		$signature = str_replace("\\", "", $signature);
		$lic = str_replace("'", "`", $HTTP_POST_VARS['lic']);
		$lic = str_replace("\\", "", $lic);
		$trailer_lic = str_replace("'", "`", $HTTP_POST_VARS['trailer_lic']);
		$trailer_lic = str_replace("\\", "", $trailer_lic);

		if($in_time != "" && !ereg("^([0-9]{1,2}):([0-9]{1,2})$", $in_time)) {
			$bad_message .= "Checkin time wasn't in HH:MM format.<br>";
		}
		if($out_time != "" && !ereg("^([0-9]{1,2}):([0-9]{1,2})$", $out_time)) {
			$bad_message .= "Checkout time wasn't in HH:MM format.<br>";
		}
		
		if($bad_message != ""){
			echo "<font color=\"#FF0000\">Save could not be processed for the following reasons:<br><br>".$bad_message."<br>Please correct and resubmit.</font><br>";
		} else {
//						CHECK_OUT = '".$out_time."',
//						SIGNATURE = '".$signature."',
			$sql = "UPDATE ARGENFRUIT_CHECKIN_ID
					SET DRIVER_NAME = '".$driver_name."',
						DRIVER_PHONE = '".$driver_phone."',
						CHECK_IN = SYSDATE,
						TEMP_RECORDER = '".$temp_rec."',
						TRAILER_LIC_AND_STATE = '".$trailer_lic."',
						TRUCK_LIC_AND_STATE = '".$lic."'
					WHERE CHECKIN_ID = '".$driver_num."'";
//			echo $sql."<br>";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
			$sql = "UPDATE ARGENFRUIT_ORDER_HEADER
					SET CHECKIN_ID = '".$driver_num."'
					WHERE ORDER_NUM = '".$order_num."'";
			$update = ociparse($rfconn, $sql);
			ociexecute($update);
//			echo $sql."<br>";
//			echo "<font color=\"#0000FF\">Save Complete.</font><br>";
			header("Location: ./order_edit_index.php?order_num=".$order_num);
		}
	}

	if($driver_num == "" && $order_num != ""){
		$sql = "SELECT CHECKIN_ID FROM ARGENFRUIT_ORDER_HEADER
				WHERE ORDER_NUM = '".$order_num."'";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$driver_num = ociresult($stid, "CHECKIN_ID");
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
		include("driver_checkin_info.php");
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
