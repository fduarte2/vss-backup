<?
/*
*	Adam Walter, Jun 2011.
*
*	Page to dump Vered's "contact list" into an Excel file
*	(Her current improt program is apparently insufficient in this respect)
*
*************************************************************************/


  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Marketing System";
  $area_type = "MKTG";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from MKTG system");
    include("pow_footer.php");
    exit;
  }
 
	$conn = ora_logon("SAG_OWNER@BNI", "SAG");
//	$conn = ora_logon("SAG_OWNER@BNITEST", "BNITEST238");
	if($conn < 1){
		printf("Error logging on to the BNI Oracle Server: ");
		printf(ora_errorcode($conn));
		exit;
	}
	$modify_cursor = ora_open($conn);
	$Short_Term_Cursor = ora_open($conn);

	$fp = fopen("ContactList.xls", "w");
	if(!$fp){
		echo "error opening file.  Please contact TS";
		include("pow_footer.php");
		exit;
	} else {
		fwrite($fp, "CONTACT_ID\t");   fwrite($fp, "TITLE\t");   fwrite($fp, "FIRSTNAME\t");   fwrite($fp, "MIDDLENAME\t");
		fwrite($fp, "FAMILYNAME\t");   fwrite($fp, "SUFFIX\t");   fwrite($fp, "COMPANY\t");   fwrite($fp, "JOB_TITLE\t");
		fwrite($fp, "CONTACT_GROUP\t");   fwrite($fp, "BOARD\t");   fwrite($fp, "ALERT\t");   fwrite($fp, "TARIFF\t");
		fwrite($fp, "GIFTS\t");   fwrite($fp, "PORT_ILLUSTRATED\t");   fwrite($fp, "HOLIDAY_PARTY\t");   fwrite($fp, "GOLF_OUTING\t");
		fwrite($fp, "AGENT_SHIPPING_LINE\t");   fwrite($fp, "AUTO_RORO\t");   fwrite($fp, "HOLIDAY_CARD\t");   fwrite($fp, "FOREST_PRODUCTS\t");
		fwrite($fp, "CHILEAN_FRUIT\t");   fwrite($fp, "FRUIT\t");   fwrite($fp, "GOVT_AGENCIES\t");   fwrite($fp, "JUICE\t");
		fwrite($fp, "LIQUID_DRY_BULK\t");   fwrite($fp, "LIVESTOCK\t");   fwrite($fp, "NEW_BIZ_PROSPECTS\t");   fwrite($fp, "PROJECT_CARGO\t");
		fwrite($fp, "WIND_ENERGY\t");   fwrite($fp, "REEFER\t");   fwrite($fp, "REGIONAL_CUSTOMERS\t");   fwrite($fp, "STEEL\t");
		fwrite($fp, "BULK\t");   fwrite($fp, "TENANTS\t");   fwrite($fp, "BUSINESSSTREET\t");   fwrite($fp, "BUSINESSCITY\t");
		fwrite($fp, "BUSINESSSTATE\t");   fwrite($fp, "BUSINESSPOSTALCODE\t");   fwrite($fp, "BUSINESSCOUNTRY\t");   fwrite($fp, "HOMESTREET\t");
		fwrite($fp, "HOMECITY\t");   fwrite($fp, "HOMESTATE\t");   fwrite($fp, "HOMEPOSTALCODE\t");   fwrite($fp, "HOMECOUNTRY\t");
		fwrite($fp, "BUSINESSFAX\t");   fwrite($fp, "BUSINESSPHONE\t");   fwrite($fp, "BUSINESSPHONE2\t");   fwrite($fp, "HOMEPHONE\t");
		fwrite($fp, "MOBILEPHONE\t");   fwrite($fp, "EMAILADDRESS\t");   fwrite($fp, "NOTES\t");   fwrite($fp, "WEBPAGE\n");

		$sql = "SELECT * 
				FROM MKTG_OWNER.CONTACT_LIST
				ORDER BY CONTACT_ID";
		ora_parse($Short_Term_Cursor, $sql);
		ora_exec($Short_Term_Cursor);
		while(ora_fetch_into($Short_Term_Cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			$replace_array = array("\r", "\n", "\t");

			$row['CONTACT_ID'] = str_replace($replace_array, "", $row['CONTACT_ID']);   
			$row['TITLE'] = str_replace($replace_array, "", $row['TITLE']);   
			$row['FIRSTNAME'] = str_replace($replace_array, "", $row['FIRSTNAME']);   
			$row['MIDDLENAME'] = str_replace($replace_array, "", $row['MIDDLENAME']);
			$row['FAMILYNAME'] = str_replace($replace_array, "", $row['FAMILYNAME']);   
			$row['SUFFIX'] = str_replace($replace_array, "", $row['SUFFIX']);   
			$row['COMPANY'] = str_replace($replace_array, "", $row['COMPANY']);   
			$row['JOB_TITLE'] = str_replace($replace_array, "", $row['JOB_TITLE']);
			$row['CONTACT_GROUP'] = str_replace($replace_array, "", $row['CONTACT_GROUP']);   
			$row['BOARD'] = str_replace($replace_array, "", $row['BOARD']);   
			$row['ALERT'] = str_replace($replace_array, "", $row['ALERT']);   
			$row['TARIFF'] = str_replace($replace_array, "", $row['TARIFF']);
			$row['GIFTS'] = str_replace($replace_array, "", $row['GIFTS']);   
			$row['PORT_ILLUSTRATED'] = str_replace($replace_array, "", $row['PORT_ILLUSTRATED']);   
			$row['HOLIDAY_PARTY'] = str_replace($replace_array, "", $row['HOLIDAY_PARTY']);   
			$row['GOLF_OUTING'] = str_replace($replace_array, "", $row['GOLF_OUTING']);
			$row['AGENT_SHIPPING_LINE'] = str_replace($replace_array, "", $row['AGENT_SHIPPING_LINE']);   
			$row['AUTO_RORO'] = str_replace($replace_array, "", $row['AUTO_RORO']);   
			$row['HOLIDAY_CARD'] = str_replace($replace_array, "", $row['HOLIDAY_CARD']);   
			$row['FOREST_PRODUCTS'] = str_replace($replace_array, "", $row['FOREST_PRODUCTS']);
			$row['CHILEAN_FRUIT'] = str_replace($replace_array, "", $row['CHILEAN_FRUIT']);   
			$row['FRUIT'] = str_replace($replace_array, "", $row['FRUIT']);   
			$row['GOVT_AGENCIES'] = str_replace($replace_array, "", $row['GOVT_AGENCIES']);   
			$row['JUICE'] = str_replace($replace_array, "", $row['JUICE']);
			$row['LIQUID_DRY_BULK'] = str_replace($replace_array, "", $row['LIQUID_DRY_BULK']);   
			$row['LIVESTOCK'] = str_replace($replace_array, "", $row['LIVESTOCK']);   
			$row['NEW_BIZ_PROSPECTS'] = str_replace($replace_array, "", $row['NEW_BIZ_PROSPECTS']);   
			$row['PROJECT_CARGO'] = str_replace($replace_array, "", $row['PROJECT_CARGO']);
			$row['WIND_ENERGY'] = str_replace($replace_array, "", $row['WIND_ENERGY']);   
			$row['REEFER'] = str_replace($replace_array, "", $row['REEFER']);   
			$row['REGIONAL_CUSTOMERS'] = str_replace($replace_array, "", $row['REGIONAL_CUSTOMERS']);   
			$row['STEEL'] = str_replace($replace_array, "", $row['STEEL']);
			$row['BULK'] = str_replace($replace_array, "", $row['BULK']);   
			$row['TENANTS'] = str_replace($replace_array, "", $row['TENANTS']);   
			$row['BUSINESSSTREET'] = str_replace($replace_array, "", $row['BUSINESSSTREET']);   
			$row['BUSINESSCITY'] = str_replace($replace_array, "", $row['BUSINESSCITY']);
			$row['BUSINESSSTATE'] = str_replace($replace_array, "", $row['BUSINESSSTATE']);   
			$row['BUSINESSPOSTALCODE'] = str_replace($replace_array, "", $row['BUSINESSPOSTALCODE']);   
			$row['BUSINESSCOUNTRY'] = str_replace($replace_array, "", $row['BUSINESSCOUNTRY']);   
			$row['HOMESTREET'] = str_replace($replace_array, "", $row['HOMESTREET']);
			$row['HOMECITY'] = str_replace($replace_array, "", $row['HOMECITY']);   
			$row['HOMESTATE'] = str_replace($replace_array, "", $row['HOMESTATE']);   
			$row['HOMEPOSTALCODE'] = str_replace($replace_array, "", $row['HOMEPOSTALCODE']);   
			$row['HOMECOUNTRY'] = str_replace($replace_array, "", $row['HOMECOUNTRY']);
			$row['BUSINESSFAX'] = str_replace($replace_array, "", $row['BUSINESSFAX']);   
			$row['BUSINESSPHONE'] = str_replace($replace_array, "", $row['BUSINESSPHONE']);   
			$row['BUSINESSPHONE2'] = str_replace($replace_array, "", $row['BUSINESSPHONE2']);   
			$row['HOMEPHONE'] = str_replace($replace_array, "", $row['HOMEPHONE']);
			$row['MOBILEPHONE'] = str_replace($replace_array, "", $row['MOBILEPHONE']);   
			$row['EMAILADDRESS'] = str_replace($replace_array, "", $row['EMAILADDRESS']);   
			$row['NOTES'] = str_replace($replace_array, "", $row['NOTES']);   
			$row['WEBPAGE'] = str_replace($replace_array, "", $row['WEBPAGE']);


			fwrite($fp, $row['CONTACT_ID']."\t");   fwrite($fp, $row['TITLE']."\t");   fwrite($fp, $row['FIRSTNAME']."\t");   fwrite($fp, $row['MIDDLENAME']."\t");
			fwrite($fp, $row['FAMILYNAME']."\t");   fwrite($fp, $row['SUFFIX']."\t");   fwrite($fp, $row['COMPANY']."\t");   fwrite($fp, $row['JOB_TITLE']."\t");
			fwrite($fp, $row['CONTACT_GROUP']."\t");   fwrite($fp, $row['BOARD']."\t");   fwrite($fp, $row['ALERT']."\t");   fwrite($fp, $row['TARIFF']."\t");
			fwrite($fp, $row['GIFTS']."\t");   fwrite($fp, $row['PORT_ILLUSTRATED']."\t");   fwrite($fp, $row['HOLIDAY_PARTY']."\t");   fwrite($fp, $row['GOLF_OUTING']."\t");
			fwrite($fp, $row['AGENT_SHIPPING_LINE']."\t");   fwrite($fp, $row['AUTO_RORO']."\t");   fwrite($fp, $row['HOLIDAY_CARD']."\t");   fwrite($fp, $row['FOREST_PRODUCTS']."\t");
			fwrite($fp, $row['CHILEAN_FRUIT']."\t");   fwrite($fp, $row['FRUIT']."\t");   fwrite($fp, $row['GOVT_AGENCIES']."\t");   fwrite($fp, $row['JUICE']."\t");
			fwrite($fp, $row['LIQUID_DRY_BULK']."\t");   fwrite($fp, $row['LIVESTOCK']."\t");   fwrite($fp, $row['NEW_BIZ_PROSPECTS']."\t");   fwrite($fp, $row['PROJECT_CARGO']."\t");
			fwrite($fp, $row['WIND_ENERGY']."\t");   fwrite($fp, $row['REEFER']."\t");   fwrite($fp, $row['REGIONAL_CUSTOMERS']."\t");   fwrite($fp, $row['STEEL']."\t");
			fwrite($fp, $row['BULK']."\t");   fwrite($fp, $row['TENANTS']."\t");   fwrite($fp, $row['BUSINESSSTREET']."\t");   fwrite($fp, $row['BUSINESSCITY']."\t");
			fwrite($fp, $row['BUSINESSSTATE']."\t");   fwrite($fp, $row['BUSINESSPOSTALCODE']."\t");   fwrite($fp, $row['BUSINESSCOUNTRY']."\t");   fwrite($fp, $row['HOMESTREET']."\t");
			fwrite($fp, $row['HOMECITY']."\t");   fwrite($fp, $row['HOMESTATE']."\t");   fwrite($fp, $row['HOMEPOSTALCODE']."\t");   fwrite($fp, $row['HOMECOUNTRY']."\t");
			fwrite($fp, $row['BUSINESSFAX']."\t");   fwrite($fp, $row['BUSINESSPHONE']."\t");   fwrite($fp, $row['BUSINESSPHONE2']."\t");   fwrite($fp, $row['HOMEPHONE']."\t");
			fwrite($fp, $row['MOBILEPHONE']."\t");   fwrite($fp, $row['EMAILADDRESS']."\t");   fwrite($fp, $row['NOTES']."\t");   fwrite($fp, $row['WEBPAGE']."\n");
		}

		fclose($fp);
	}

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Export All Contacts To Excel 
</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td>Click <a href="<? echo "ContactList.xls"; ?>">Here</a> for the file.</td>
	</tr>
</table>
<?
	include("pow_footer.php");
?>
