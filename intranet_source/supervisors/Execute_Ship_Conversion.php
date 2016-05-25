<?
/* Created by Adam Walter, June 2006.
* This script is used by Truck_To_Ship_Conversion.php
* Changes all values in cargo_activity and cargo_tracking
* in the RF database from Arrival_num 20071 to whatever value
* is specified, provided it was a valid number.
*/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Supervisors Applications";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  $vessel_number = $HTTP_POST_VARS['vessel_number'];
  $warehouse_choice = $HTTP_POST_VARS['warehouse_choice'];
  $orig_vessel = $HTTP_POST_VARS['orig_vessel'];

//  echo $vessel_number."\n";
//  echo $warehouse_choice."\n";

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
//  $conn = ora_logon("SAG_OWNER@RF.DEV", "RFOWNER");
  if($conn < 1){
      printf("Error logging on to the Oracle Server: ");
      printf(ora_errorcode($conn));
      printf("<br />Please try later!</body></html>");
      exit;
  }

  $tracking_cursor = ora_open($conn);
  $activity_cursor = ora_open($conn);
  $other_activity_cursor = ora_open($conn);
  $tracking_count_cursor = ora_open($conn);
  $activity_count_cursor = ora_open($conn);
  $testing_cursor = ora_open($conn);

  $activity_changed = 0;
  $tracking_changed = 0;


?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Vessel to Inbound Truck Conversion
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<?
  $sql = "UPDATE CARGO_TRACKING SET QTY_DAMAGED = '0', QTY_UNIT = NULL, CARGO_STATUS = '".$warehouse_choice."', BILL = '0', ARRIVAL_NUM = '".$vessel_number."', RECEIVING_TYPE = 'T', MANIFESTED = NULL, FROM_SHIPPING_LINE = NULL, SHIPPING_LINE = NULL WHERE ARRIVAL_NUM = '".$orig_vessel."'";
  $statement = ora_parse($tracking_cursor, $sql);
  $ora_success = ora_exec($tracking_cursor);

  $sql = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '".$vessel_number."', TO_MISCBILL = NULL, ORDER_NUM = '".$vessel_number."', SERVICE_CODE = 8 WHERE ARRIVAL_NUM = '".$orig_vessel."' AND ACTIVITY_NUM = 1";
  $statement = ora_parse($activity_cursor, $sql);
  $ora_success = ora_exec($activity_cursor);

  $sql = "UPDATE CARGO_ACTIVITY SET ARRIVAL_NUM = '".$vessel_number."', TO_MISCBILL = NULL WHERE ARRIVAL_NUM = '".$orig_vessel."' AND ACTIVITY_NUM != '1'";
  $statement = ora_parse($other_activity_cursor, $sql);
  $ora_success = ora_exec($other_activity_cursor);

   ora_close($tracking_cursor);
   ora_close($activity_cursor);
   ora_close($other_activity_cursor);
   ora_commit($conn);

   $sql = "SELECT COUNT(*) TOTAL FROM CARGO_TRACKING WHERE ARRIVAL_NUM = '".$vessel_number."'";
   $statement = ora_parse($tracking_count_cursor, $sql);
   ora_exec($tracking_count_cursor);
   ora_fetch_into($tracking_count_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

   $tracking_changed = $data['TOTAL'];

   $sql = "SELECT COUNT(*) TOTAL FROM CARGO_ACTIVITY WHERE ARRIVAL_NUM = '".$vessel_number."'";
   $statement = ora_parse($activity_count_cursor, $sql);
   ora_exec($activity_count_cursor);
   ora_fetch_into($activity_count_cursor, $data, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);

   $activity_changed = $data['TOTAL'];
?>

<table>
	<tr>
		<td><font size="2" face="Verdana">&nbsp;Program complete, <? echo $tracking_changed; ?> inventory records and <? echo $activity_changed; ?> activity records changed.</td>
	</tr>
</table>
<? include("pow_footer.php"); ?>
