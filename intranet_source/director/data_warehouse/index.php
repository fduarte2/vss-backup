<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications - Reports";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from CCDS system");
    include("pow_footer.php");
    exit;
  }
  include_once( 'TreeMenuXL.php' );

//  $pgEmailSubj = 'HTML_TreeMenu_Page';

  // Control dynamic style sheet
//  $styleBodyIndent=true;
//  $styleBodyBGcolor="#FFFFFF";

    // Create the Menu object and the menu tree
  $menu00  = new HTML_TreeMenuXL();
  $nodeProperties = array("icon"=>"folder.gif","cssClass"=>"");
 // $nodeProperties = array("cssClass"=>"auto");
  $node0 = new HTML_TreeNodeXL("<b>DSPC Data Warehouse</b>", "#", $nodeProperties);

  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Dwell Times", "/director/data_warehouse/FY_reports/FY_report_v2.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Ship Schedule", "/ship_schedule/", $nodeProperties));
//  $nx1 = &$node0->addItem(new HTML_TreeNodeXL("Warehouse", "#", $nodeProperties));
//  $nx = &$nx1->addItem(new HTML_TreeNodeXL("Warehouse Utilization", "/director/warehouse_rep/index.php", $nodeProperties));
  $nx = &$node0->addItem(new HTML_TreeNodeXL("DSPC Inventory By Date", "/director/olap/whs.php", $nodeProperties));
//  $nx = &$nx1->addItem(new HTML_TreeNodeXL("BNI Inventory", "/director/inventory/bni_inventory.php", $nodeProperties));

//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Cargo Statistics", "/director/cargo/index.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Revenue Statistics", "/director/revenue/index.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Vessel Report", "/director/vessel/index.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Warehouse Report", "/director/warehouse_report/index.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Trucked In Cargo", "/director/olap/truck_in_cargo.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Oppenheimer Cargo", "/director/olap/opp_cargo.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Refrigerate Temperatures", "/director/temperature_rep/index.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("LCS REPORT", "/director/olap/lcs.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("BILLING", "/director/olap/billing.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("RF STORAGE BILLING", "/director/olap/rf_billing.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("VESSEL", "/director/olap/vessel.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("CCDS ACTIVITIES", "/director/olap/ccd_activity.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("TRUCK", "/director/olap/truck.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("PRODUCTIVITY DETAIL", "/director/olap/productivity.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("PRODUCTIVITY SUMMARY", "/director/olap/prod_sum.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Paid Hours vs Labor Tickets", "/director/labor_ticket/index.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Holmen Paper Backhaul", "/director/olap/holmen.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Holmen Paper Activity", "/director/olap/holmen_activity.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Dole Paper Activity", "/director/olap/dole_activity.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Revenue, Tonnage and Hours", "/director/olap/revenue_cost.php", $nodeProperties));
//  $nx = &$node0->
//     addItem(new HTML_TreeNodeXL("Warehouse Floor Positions", "/director/olap/warehouse.php", $nodeProperties));
  $nx = &$node0->
     addItem(new HTML_TreeNodeXL("GL From Solomon", "/director/olap/solomon.php", $nodeProperties));
   $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Vessel Calls", "/director/olap/vessel_info.php", $nodeProperties));
   $nx = &$node0->
     addItem(new HTML_TreeNodeXL("Reband Listing (Argen Juice)", "/director/data_warehouse/reband_report.php", $nodeProperties));
/* 
  $nx2 = &$nx1->
     addItem(new HTML_TreeNodeXL("Inventory", "/director/inventory/bni_inventory.php", $nodeProperties));
  $nx1->addItem(new HTML_TreeNodeXL("Warehouse Revenue", "#link3", $nodeProperties));
  $nx2 = &$nx1->
          addItem(new HTML_TreeNodeXL("... and Deeper","#link4", $nodeProperties));
  $node0->addItem(new HTML_TreeNodeXL("deleted-items", "#link5", $nodeProperties));
  $node0->addItem(new HTML_TreeNodeXL("sent-items",    "#link6", $nodeProperties));
  $node0->addItem(new HTML_TreeNodeXL("drafts",        "#link7", $nodeProperties));
  $node0->addItem(new HTML_TreeNodeXL("spam",          "#link8", $nodeProperties));
*/
 
  $menu00->addItem($node0);
 // $menu00->addItem(new HTML_TreeMenuXL());


  $menu01 = new HTML_TreeMenuXL();
  $node1 = new HTML_TreeNodeXL("<b>Other Finance Reports</b>", "#", $nodeProperties);
  $nx = &$node1->
     addItem(new HTML_TreeNodeXL("Hours Paid vs. Billed Amounts per Vessel", "/finance/reports/Hours_vs_Billed/index.php", $nodeProperties));

  $menu01->addItem($node1);

  $menu02 = new HTML_TreeMenuXL();
  $node2 = new HTML_TreeNodeXL("<b>Financial Analyst Functions</b>", "#", $nodeProperties);
  $nx = &$node2-> addItem(new HTML_TreeNodeXL("Edit LCS Service / Commodity Code", "/finance/FA_functions/EditLCSComm/editcomm.php", $nodeProperties));
  $nx = &$node2-> addItem(new HTML_TreeNodeXL("Edit Revenue-Tons-Hours Grouping", "/finance/FA_functions/EditCommITG/comm_itg.php", $nodeProperties));
  $nx = &$node2-> addItem(new HTML_TreeNodeXL("Customer Profile Budget Entry", "../customer_profile/index.php", $nodeProperties));

  $menu02->AddItem($node2);

?>

   <script src="/functions/TreeMenu.js" language="JavaScript" type="text/javascript"></script>
   <link href="TreeMenu.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/JavaScript">
   <!--
   function MM_callJS(jsStr) { //v2.0
     return eval(jsStr)
   }
   //-->
</script>
<!-- InstanceEndEditable -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
  // Include dynamic style sheet.
  echo '<style type="text/css">'."\n  <!--\n";
  if (file_exists( 'ccSiteStyle.css.php' )) {
    include_once( 'ccSiteStyle.css.php' );
  }
  else {
    include_once( $_SERVER['DOCUMENT_ROOT'] . '/functions/ccSiteStyle.css.php' );
  }
  echo "\n  -->\n</style>\n";
  if (false) {
?>
<link href="/ccSiteStyle.css" rel="stylesheet" type="text/css">
<?php } ?>
<script type="text/javascript" language="JavaScript">
  // Bust my page out of any frames
  if (top != self) top.location.href = location.href;
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">DSPC Data Warehouse 
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
<?
//        $example010 = &new HTML_TreeMenu_DHTMLXL($menu00, array("images"=>"/images/TMimagesAlt2"));
//        $example010->printMenu();

        $example010 = &new HTML_TreeMenu_DHTMLXL($menu00, array("images"=>"/images/TMimages"));
        $example010->printMenu();

/*
  	$menu00->addItem(new HTML_TreeMenuXL());

        $example011 = &new HTML_TreeMenu_DHTMLXL($menu00, array("images"=>"/images/TMimagesAlt"));
        $example011->printMenu();
*/

?>
      </td>
      <td valign="top" width="30%">
        <p><img border="0" src="/director/images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
	  <td valign="top" width="70%">
<?
	 $example011 = &new HTML_TreeMenu_DHTMLXL($menu01, array("images"=>"/images/TMimages"));
	 $example011->printMenu();
?>
      </td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
	  <td valign="top" width="70%">
<?
	 $example012 = &new HTML_TreeMenu_DHTMLXL($menu02, array("images"=>"/images/TMimages"));
	 $example012->printMenu();
?>
      </td>
   </tr>
    <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php");
