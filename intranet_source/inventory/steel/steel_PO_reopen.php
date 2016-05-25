<?
/*
*
*	Adam Walter, Dec 2012.
*
*	A screen for inventory to reopen orders by PO#.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel orderss";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from INVE system");
    include("pow_footer.php");
    exit;
  }

	$PO_num = $HTTP_POST_VARS['PO_num'];
	$inits = $HTTP_POST_VARS['inits'];
	$submit = $HTTP_POST_VARS['submit'];
///	echo $submit."aaa<br>";

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	$bniconn = ocilogon("SAG_OWNER", "SAG", "BNI");
//	$bniconn = ocilogon("SAG_OWNER", "BNITEST238", "BNITEST");
	if($bniconn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}

	if($submit == "ReOpen"){
		if($PO_num == ""){
			echo "<font color=\"#FF0000\">No PO was selected.</font>";
			$submit = "";
		}
		if($inits == ""){
			echo "<font color=\"#FF0000\">You must enter your initials.</font>";
			$submit = "";
		}
	}

	if($submit == "ReOpen"){
		$sql = "SELECT TLS_ROW_ID
				FROM STEEL_ORDERS
				WHERE PORT_ORDER_NUM = '".$PO_num."'";
//		echo $sql;
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		ocifetch($stid);
		$tls_row = ociresult($stid, "TLS_ROW_ID");

		$sql = "UPDATE TLS_TRUCK_LOG
				SET CHECKED_OUT_BY = CHECKED_IN_BY,
					TIME_OUT = TIME_IN
				WHERE RECORD_ID = '".$tls_row."'";
//		echo $sql;
		$update = ociparse($bniconn, $sql);
		ociexecute($update);

		$sql = "UPDATE STEEL_ORDERS
				SET ORDER_STATUS = NULL,
					CLERK_INITIALS = '".$inits."'
				WHERE PORT_ORDER_NUM = '".$PO_num."'";
	//	echo $sql;
		$update_rf = ociparse($rfconn, $sql);
		ociexecute($update_rf);

		echo "<font color=\"#0000FF\">PO# ".$PO_num." Reopened.</font>";
	}
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Re-Open Port Order - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="save" action="steel_PO_reopen.php" method="post">
	<tr>
		<td align="left"><select name="PO_num"><option value="">Select a PO:</option>
<?

	$sql = "SELECT PORT_ORDER_NUM
			FROM STEEL_ORDERS
			WHERE ORDER_STATUS = 'COMPLETE'
				AND CREATED_DATE >= SYSDATE - 365
			ORDER BY PORT_ORDER_NUM DESC";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "PORT_ORDER_NUM"); ?>"<? if($DO_num == ociresult($stid, "PORT_ORDER_NUM")){?> selected <?}?>><? echo ociresult($stid, "PORT_ORDER_NUM"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td>Initials:  <input type="text" name="inits" size="5" maxlength="5"></td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="ReOpen"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");