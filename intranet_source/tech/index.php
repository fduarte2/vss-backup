<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "TS Applications Access";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="1%">&nbsp;</td>
		<td>
			<p align="left">
				<font size="5" face="Verdana" color="#0066CC">
					Technology Solutions Applications
				</font>
				<hr/>
			</p>
		</td>
	</tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td><font size="3" face="Verdana">
			<b>Intranet Documentation</b>
		</td>
		<td valign="top" width="30%" rowspan="100">
			<p><img border="0" src="images/FSI-comp.jpg" width="218" height="170"></p>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../tech/documentation/ScreenDocumentationTemplate.doc">Screen Documentation Template</a>
			</font>
		</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td><font size="3" face="Verdana">
			<b>EPort Wilmington Documentation</b>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			(none yet)
			</font>
		</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td><font size="3" face="Verdana">
			<b>Manifest Upload Links</b>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/juice_import.php">Juice Manifest Upload</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/chilean_original_upload.php">Chilean Original Manifest Upload</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/chilean_manifest_to_CT.php">Chilean move of Original Manifest to Cargo Tracking</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/manifest_comparison.php">Original Manifest to Actual Comparison</a>
			</font>
		</td>
	</tr>
	<tr>	
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="clementine_import.php/">Import Clementine Cargo</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/steel_import.php">Import SSAB Steel</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			Baplie Files:
			<a href="../TS_Program/BaplieIn.php">(Import Baplie to System)</a>
			<a href="../TS_Program/BaplieOut.php">(Create Outgoing Baplie File)</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			Dole Fruit Imports:
			<a href="../TS_Program/dole_inventory_upload.php">(Inventory File)</a>
			<a href="../TS_Program/dole_activity_upload.php">(Activity File)</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/argenfruit_upload.php">Argen Fruit (Patagonia/Bridges) Manifest Upload</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/argenfruit_manifest_to_CT.php">Argen Fruit Move of Manifest to Cargo Tracking</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/rf_import_remove.php/">Remove Imported Manifest from Cargo Tracking</a>
			</font>
		</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td><font size="3" face="Verdana">
			<b>Other Links</b>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="Specifications/Clementine_Regrade_Scanner_with_CBP">Specifications: Clementine Regrade Scanner with CBP</a>
			</font>
		</td>
	</tr>
	<tr>
		<td><font size="3" face="Verdana">
			<img src="images/yellowbulletsmall.gif">
			<a href="../TS_Program/TSphpgrids/chileanPltUpdEmailList/">Chilean Customer Email List</a>
			</font>
		</td>
	</tr>
</table>

<br/>

<? include("pow_footer.php"); ?>
