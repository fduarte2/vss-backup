<?
/*
*
*	Adam Walter, Dec 2012.
*
*	A screen for inventory to view steel orders by DO#.
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

	$DO_num = $HTTP_POST_VARS['DO_num'];
	$submit = $HTTP_POST_VARS['submit'];
	$status = $HTTP_POST_VARS['status'];
	if($status == "NORMAL"){
		$extra_sql = " AND ORDER_STATUS IS NULL ";
	} elseif($status == "All"){
		$extra_sql = "";
	} else {
		$extra_sql = " AND ORDER_STATUS = '".$status."'";
	}
//	echo $submit."aaa<br>";

	$rfconn = ocilogon("SAG_OWNER", "OWNER", "RF");
//	$rfconn = ocilogon("SAG_OWNER", "RFTEST238", "RFTEST");
	if($rfconn < 1){
		printf("Error logging on to the RF Oracle Server: ");
//		printf(ora_errorcode($conn));
		exit;
	}
	

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">DO/PO#s List - </font><a href="index_steel.php">Return to Main Steel Page</a>
         </p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
<form name="get" action="steel_DOPO_lookup.php" method="post">
	<tr>
		<td align="left"><font size="2">DO#: <select name="DO_num"><option value="">Select a DO:</option>
<?

	$sql = "SELECT DISTINCT REMARK
			FROM CARGO_TRACKING
			WHERE COMMODITY_CODE IN
					(SELECT COMMODITY_CODE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE = 'STEEL')
				AND QTY_IN_HOUSE > 0
				AND REMARK IS NOT NULL
				AND REMARK != 'NO DO'
			ORDER BY REMARK desc";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "REMARK"); ?>"<? if($DO_num == ociresult($stid, "REMARK")){?> selected <?}?>><? echo ociresult($stid, "REMARK"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td align="left"><font size="2">Order Status: <select name="status"><option value="All">All</option>
<?

	$sql = "SELECT DISTINCT NVL(ORDER_STATUS, 'NORMAL') THE_STAT
			FROM STEEL_ORDERS
			ORDER BY NVL(ORDER_STATUS, 'NORMAL')";
	$stid = ociparse($rfconn, $sql);
	ociexecute($stid);
	while(ocifetch($stid)){
?>
							<option value="<? echo ociresult($stid, "THE_STAT"); ?>"<? if($status == ociresult($stid, "THE_STAT")){?> selected <?}?>><? echo ociresult($stid, "THE_STAT"); ?></option>
<?
	}
?>
					</select></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="submit" value="Retrieve"></td>
	</tr>
</form>
</table>
<?
	if($submit == "Retrieve"){
?>
<table border="1" width="50%" cellpadding="4" cellspacing="0">
	<tr>
		<td>PO#</td>
		<td>Status</td>
	</tr>
<?
		$sql = "SELECT PORT_ORDER_NUM, NVL(ORDER_STATUS, 'OPEN') THE_STAT 
				FROM STEEL_ORDERS 
				WHERE DONUM = '".$DO_num."'".$extra_sql." 
				ORDER BY PORT_ORDER_NUM";
		$stid = ociparse($rfconn, $sql);
		ociexecute($stid);
		if(!ocifetch($stid)){
?>
	<tr>
		<td colspan="2" align="center"><font size="2">No PO#s in system for DO# <? echo $DO_num; ?> Status <? echo $status; ?></font></td>
	</tr>
<?
		} else { 
			do {
?>
	<tr>
		<td><font size="2"><? echo ociresult($stid, "PORT_ORDER_NUM"); ?></font></td>
		<td><font size="2"><? echo ociresult($stid, "THE_STAT"); ?></font></td>
	</tr>
<?
			}while(ocifetch($stid));
		}
?>
</table>
<?
	}
	include("pow_footer.php");
?>