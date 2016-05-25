<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - CCDS Tracker Report";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
?>

<script type="text/javascript">
   function validate_info()
   {
      x = document.info_form
      lot = x.lot.value

      if (lot == "") {
	 alert("You must select a lot before you print the tracker report!")
         return false
      }
  }
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Tracker Report</font>
         <hr><br>
	 <font size="2" face="Verdana" color="#000080">
	 |<a href="add_ccds.php">Back to Add Claim</a>|
	 |<a href="mark_lookup.php">Mark Lookup</a>|<br />
	 </font>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
	 <font size="2" face="Verdana">Select a lot by its tracking number or lot & mark information from 
	 the ship manifest.</font><br /><br />
      </td>
   </tr>
</table>

<?
   include("track_report_select.php");
   include("pow_footer.php");
?>
