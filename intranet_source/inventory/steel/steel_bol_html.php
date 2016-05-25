<?
/*
*
*	Adam Walter, Nov 2012.
*
*	A screen for inventory to pribnt Steel picklists.
*
***********************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Steel BoL";
  $area_type = "TELR";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TELR system");
    include("pow_footer.php");
    exit;
  }

	if ((array_search('INVE', $user_types) !== FALSE) || (array_search('ROOT', $user_types) !== FALSE)){ // this comes from pow_header.php
		$display_steel_links = true;
	} else {
		$display_steel_links = false;
	}


?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">SSAB Steel Truck Check Out<? if($display_steel_links){?> - </font><a href="index_steel.php">Return to Main Steel Page</a><?}?>
			</p>
		 <hr>
      </td>
   </tr>
</table>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
<form name="new" action="steel_bol_print_html.php" method="post">
	<tr>
		<td colspan="2">Port Order#: <input type="text" name="PORT_num" size="12" maxlength="12"></td>
	</tr>
	<tr>
		<td colspan="2">Checkout Clerk: <input type="text" name="clerk" size="12" maxlength="12"></td>
	</tr>
	<tr>
		<td colspan="2"><font size="2">Note:  Printing the BOL will update the TLS system's Check Out Time and Check Out User information,<br>And mark the order as "completed", preventing further scanning. </font></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><input type="submit" name="submit" value="View Order with Current Status"></td>
<!--		<td align="right"><input type="submit" name="submit" value="COMPLETE ORDER+Print BoL and Tally"></td> !-->
	</tr>
</form>
</table>
<?
	include("pow_footer.php");