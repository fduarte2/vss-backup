<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Commodity List";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

?>

<html>
<head>
<title>Port of Wilmington -- Clementine Locations</title>
</head>

<body link="#000080" vlink="#000080" alink="#000080">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td width = "90%" valign="top">
      <?
         include("main.php");
      ?>
      </td>
   </tr>
</table>

</body>
</html>
<?
  // Don't forget the footer
  include("pow_footer.php");
?>

