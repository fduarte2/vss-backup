<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CCDS system");
    include("pow_footer.php");
    exit;
  }

  $date = date('m/d/Y');
?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Work Flow Analysis
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<a href="/director/flow_chart/Process Flow.ppt">Power Point</a>
         
      </td>
      <td valign="middle" width="30%">
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
        <a href="/director/flow_chart/Workflow Analysis Followup Items.doc">Follow_up questions</a>

      </td>
      <td valign="middle" width="30%">

        <p><img border="0" src="/director/images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>
