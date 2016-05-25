<?
/*	Adam Walter, April 22, 2008.
*
*	This program generates a CSV file showing over-the-road cargo for
*	Chilean Fruit.  O-T-R cargo for Chuilean Fruit is defined as anything
*	Not from customers 439 or 440 (or, after Dominion left, 835) 
*	which has a service code of 2.
*************************************************************************/

  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Chilean Fruit OTR";
  $area_type = "DIRE";

	$submit = $HTTP_POST_VARS['submit'];
	$type = $HTTP_POST_VARS['type'];
	if($submit == "Generate Report"){

		$last_oct = date('m/d/Y', mktime(0,0,0,10,1, date("Y") - 1));
		//  echo $last_oct;

		$conn = ora_logon("SAG_OWNER@RF", "OWNER");
		//  $conn = ora_logon("SAG_OWNER@RF.TEST", "RFOWNER");
		if(!$conn){
			$body = "Error logging on to the RF Oracle Server: " . ora_errorcode($conn);
			mail($mailTO, $mailsubject, $body, $mailheaders);
			exit;
		}
		$cursor = ora_open($conn);         // general purpose

		$filename1 = "ChileanOTR.xls";
		$handle = fopen($filename1, "w");
		if (!$handle){
			echo "could not create file.  Please contact TS.";
			exit;
		}

		$sql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, CUSTOMER_NAME, SUM(QTY_LEFT) THE_QTY, COUNT(*) THE_COUNT
				FROM CARGO_ACTIVITY CA, CUSTOMER_PROFILE CP
				WHERE SERVICE_CODE = '8'
				AND CA.CUSTOMER_ID = CP.CUSTOMER_ID ";
			switch($type){
				case "Chilean":
					$sql .= "AND CA.CUSTOMER_ID NOT IN ('439', '440', '441', '312', '835') ";
					break;
				case "Abitibi":
					$sql .= "AND CA.CUSTOMER_ID = '312' ";
					break;
				case "Clementine":
					$sql .= "AND CA.CUSTOMER_ID IN ('439', '440', '441', '835') ";
					break;
				default:
					// nothing for all
			}
		$sql .=	"AND DATE_OF_ACTIVITY >= TO_DATE('".$last_oct."', 'MM/DD/YYYY')
				GROUP BY TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY'), CUSTOMER_NAME
				ORDER BY TO_DATE(THE_DATE, 'MM/DD/YYYY'), CUSTOMER_NAME";
		ora_parse($cursor, $sql);
		ora_exec($cursor);
		if(!ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
			fwrite($handle, "No Pallets in record");
		} else {
			switch($type){
				case "Chilean":
					fwrite($handle, "CHILEAN FRUIT CARGO\n\n");
					break;
				case "Abitibi":
					fwrite($handle, "ABITIBI CARGO (Customer 312)\n\n");
					break;
				case "Clementine":
					fwrite($handle, "CLEMENTINE CARGO (Customers 439, 440, 441, 835)\n\n");
					break;
				default:
					fwrite($handle, "ALL CUSTOMER'S CARGO\n\n");
			}

			$sunday = GetSunday($row['THE_DATE']);
			$week_total_case = 0;
			$week_total_pallet = 0;
			$total_case = 0;
			$total_pallet = 0;

			fwrite($handle, "Week of (Sunday)\tReceived Date\tCustomer\tCases\tPallets\n");
			fwrite($handle, $sunday."\n");

			do {
				$temp_date = split("/", $row['THE_DATE']);
				$temp_current_sun = split("/", $sunday);

				if(mktime(0,0,0,$temp_date[0],$temp_date[1],$temp_date[2]) >= mktime(0,0,0,$temp_current_sun[0],$temp_current_sun[1]+7,$temp_current_sun[2])){
					fwrite($handle, "\t\tTOTALS\t".$week_total_case."\t".$week_total_pallet."\n\n");
					$sunday = GetSunday($row['THE_DATE']);
					fwrite($handle, $sunday."\n");
					$week_total_case = 0;
					$week_total_pallet = 0;
				}	

				fwrite($handle, "\t".$row['THE_DATE']."\t".$row['CUSTOMER_NAME']."\t".$row['THE_QTY']."\t".$row['THE_COUNT']."\n");
				$week_total_case += $row['THE_QTY'];
				$week_total_pallet += $row['THE_COUNT'];
				$total_case += $row['THE_QTY'];
				$total_pallet += $row['THE_COUNT'];
			} while(ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC));

			fwrite($handle, "\t\tTOTALS\t".$week_total_case."\t".$week_total_pallet."\n\n");
			fwrite($handle, "\t\tGRAND TOTALS\t".$total_case."\t".$total_pallet."\n\n");

		}
		fclose($handle);
		header("Location: ./".$filename1);
	}



  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }

?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Over the Road Cargo</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
<form name="meh" action="chilean_OTR.php" method="post">
	<tr>
		<td align="center"><input type="radio" name="type" value="ALL"><font size="2" face="Verdana">All</font></td>
	</tr>
	<tr>
		<td align="center"><input type="radio" name="type" value="Chilean"><font size="2" face="Verdana">Chilean</font></td>
	</tr>
	<tr>
		<td align="center"><input type="radio" name="type" value="Abitibi"><font size="2" face="Verdana">Abitibi</font></td>
	</tr>
	<tr>
		<td align="center"><input type="radio" name="type" value="Clementine"><font size="2" face="Verdana">Clementine</font></td>
	</tr>

	<tr>
		<td align="center"><input type="submit" name="submit" value="Generate Report"></td>
	</tr>
</form>
</table>
<?
	include("pow_footer.php");

function GetSunday($earliest_date){
	$date_array = split("/", $earliest_date);

	if(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Sun"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2]));
	} elseif(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Mon"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 1,$date_array[2]));
	} elseif(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Tue"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 2,$date_array[2]));
	} elseif(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Wed"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 3,$date_array[2]));
	} elseif(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Thu"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 4,$date_array[2]));
	} elseif(date('D', mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2])) == "Fri"){
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 5,$date_array[2]));
	} else {
		return date('m/d/Y', mktime(0,0,0,$date_array[0],$date_array[1] - 6,$date_array[2]));
	}
}