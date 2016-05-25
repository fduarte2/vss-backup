<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Clementine Pictures";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");


  if($access_denied){
    printf("Access Denied from CCDS system");
    include("pow_footer.php");
    exit;
  }

  $user = $userdata['username'];

  include_once( 'TreeMenuXL.php' );


  $dir1 = "/web/web_pages/tech/clementine_pics";
//  $dir2 = "Not In Use";

  $pic_array = array();

	if ($dh = opendir($dir1)) {
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".." && $file != "listing.php"){
				array_push($pic_array, $file);
			}
		}
		closedir($dh);
	}

	sort($pic_array);

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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
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
<? } ?>
<script type="text/javascript" language="JavaScript">
  // Bust my page out of any frames
  if (top != self) top.location.href = location.href;
</script>

<?
  $menu00  = new HTML_TreeMenuXL();
  $nodeProperties = array("icon"=>"folder.gif","cssClass"=>"");

  $node0 = new HTML_TreeNodeXL("<b>Clementine Pictures, October 2006</b>", "#", $nodeProperties);
  $nodeProperties = array("icon"=>"", "cssClass"=>"");
  for($i = 0; $i < count($pic_array); $i++){
	 $nx = &$node0->
     addItem(new HTML_TreeNodeXL($pic_array[$i], "/tech/clementine_pics/".$pic_array[$i], $nodeProperties));
  }

  $menu00->addItem($node0);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 
	    <font size="5" face="Verdana" color="#0066CC">Clementine Pictures
	    </font>
	    <hr>
	 
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
<?
	    $example010 = &new HTML_TreeMenu_DHTMLXL($menu00, array("images"=>"/images/TMimages"));
        $example010->printMenu();

?>
      </td>
      <td valign="top" width="30%">
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
