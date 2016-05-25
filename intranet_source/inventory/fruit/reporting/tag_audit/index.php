<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RF Reports - Tag Audit";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript" src="/functions/calendar.js"></script>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Fruit Tag Audit</font>
         <br />
	 <hr>
      </td>
   </tr>
   <tr>
      <td>&nbsp;</td>
      <td>
	 <font size="2" face="Verdana">
	 | <a href="../">Home</a>
         </font>
      </td>
   </tr>
   <tr>
      <td colspan="2" height="6"></td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	 <font size="2" face="Verdana"><p>
      <?
        printf("Please enter a Pallet ID (or part of a Pallet ID) to view the Tag Audit report.");
      ?>
         </p></font>
	 <table align="left" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	    <tr>
	       <td width="5%">&nbsp;</td>
	       <td width="20%" align="right" valign="top">
	          <font size="2" face="Verdana">Pallet ID:</font></td>
	       <td width="55%" align="left">
	       <form name="report_form" method="get" action="tag_audit.php" onsubmit="return validate_form()">
                 <input type="textbox" name="pallet_id" size="22" value="<?= $pallet_id ?>">
               </td>
               <td width="20%">&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="2">&nbsp;</td>
	       <td align="left">
	          <input type="submit" value="View the Report">
	       </td>
	       </form>
	       <td>&nbsp;</td>
	    </tr>
	    <tr>
	       <td colspan="4">&nbsp;</td>
	    </tr>
	 </table>
      </td>
   </tr>
</table>
<?
  // Do we have some results from the previous page?
  if($pallet_id != ""){
   $ora_conn = ora_logon("SAG_OWNER@RF", "OWNER");
   if (!$ora_conn) {
     printf("Error logging on to the RF Oracle Server: " . ora_errorcode($ora_conn));
     printf("Please report to TS!");
     exit;
   }
   $cursor = ora_open($ora_conn);
   $stmt = "select pallet_id, cargo_description, to_char(date_received, 'MM/DD/YYYY HH24:MI:SS') date_received from cargo_tracking where pallet_id like '%$pallet_id%'";
   $ora_success = ora_parse($cursor, $stmt);
   $ora_success = ora_exec($cursor);
?>
   <br /><br />
   <table width="100%" border="1">
     <th>Pallet ID</th><th>Description</th><th>Date Received</th>
<?
   while(ora_fetch_into($cursor, $cargo_tracking, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     printf("<tr><td><a href=\"tag_audit.php?pallet_id=%s\">%s</a></td><td><a href=\"tag_audit.php?pallet_id=%s\">%s</a></td><td><a href=\"tag_audit.php?pallet_id=%s\">%s</a></td></a></tr>", $cargo_tracking['PALLET_ID'], $cargo_tracking['PALLET_ID'], $cargo_tracking['PALLET_ID'], $cargo_tracking['CARGO_DESCRIPTION'], $cargo_tracking['PALLET_ID'], $cargo_tracking['DATE_RECEIVED']);
   }
  printf("</table>");
  }
include("pow_footer.php"); ?>
