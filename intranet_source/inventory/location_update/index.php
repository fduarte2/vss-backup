<?
// All POW files need this session file included
include("pow_session.php");

// Define some vars for the skeleton page
$title = "TS Location Update";
$area_type = "INVE";

// Provides header / leftnav
include("pow_header.php");
if($access_denied){
  printf("Access Denied from INVE system");
  include("pow_footer.php");
  exit;
}
?>


<table>

<form action="main.php" method="post">
<tr>
<td>
<p>Arrival Number: </td><td><input type="text" name="arrival_num" /></td></p>
</tr>

<tr>
<td>
<p>Exporter Code : </td><td><input type="text" name="exporter_code" /></td></p>
</tr>

<tr>
<td>
<p>Cargo Size    : </td><td><input type="text" name="cargo_size" /></td></p>
</tr>


<tr>
<td>
<p>Cargo Location: </td><td><input type="text" name="cargo_loc" /></td></p>
</tr>

<tr>
<td>
<p><input type="submit" /></p>
</td>
</tr>
</form>

</table>

<?
// Don't forget the footer
include("pow_footer.php");
?>
