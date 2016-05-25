<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Ship Barcode Duplication Check";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

  $submit = $HTTP_POST_VARS['submit'];

  if($submit == "submit" && $HTTP_POST_FILES['check_file']['name'] != ""){
	  // delete old versions such that file space is preserved
	  $cmd = "rm -f *.csv";
	  system($cmd);
	  $cmd = "rm -f *.txt";
	  system($cmd);

	  $temp = basename($HTTP_POST_FILES['check_file']['name'], ".csv");
	  $target = $temp.".txt";

	  move_uploaded_file($HTTP_POST_FILES['check_file']['tmp_name'], $target);

	  $file = fopen($target, "r");
	  $output_overall = fopen("overall".$target, "w");
	  $output_dup = fopen("duplicate".$target, "w");

	  $good_array = array();
	  $dup_array = array();

	  while(!feof($file)){
		  $line = fgets($file);

		  $temp = split(",", $line);
		  $pallet = $temp[2].$temp[3].$temp[4].$temp[5];

		  // this next logic is tricky, bear with me:
		  // if the current pallet is not in the array, add it
		  // if it is, then check which is a later date/time.
		  //     if the original entry, add the new entry to duplicate output and be done with it
		  //     if the new one, REPLACE the existing entry (array index and all) with the new one,
		  //			and move old one to duplicate output
		  //
		  // that should allow this logic to flow
		  $index = array_search($pallet, $dup_array);
		  if(!$index){
			  array_push($dup_array, $pallet);
			  array_push($good_array, $line);
		  } else {
			  $keeper = "current";

			  $temp = split(",", $good_array[$index]);
			  $date_temp = split("/", $temp[6]);
			  $current_year = $date_temp[2];
			  $current_day = $date_temp[1];
			  $current_month = $date_temp[0];
			  $current_time = $temp[7];

			  $temp = split(",", $line);
			  $date_temp = split("/", $temp[6]);
			  $new_year = $date_temp[2];
			  $new_day = $date_temp[1];
			  $new_month = $date_temp[0];
			  $new_time = $temp[7];

			  // not pretty, but I'm under the clock here...
			  if($new_year > $current_year){
				  $keeper = "new";
			  }
			  if(($new_year == $current_year) && ($new_month > $current_month)){
				  $keeper = "new";
			  }
			  if(($new_year == $current_year) && ($new_month == $current_month) && ($new_day > $current_day)){
				  $keeper = "new";
			  }
			  if(($new_year == $current_year) && ($new_month == $current_month) && ($new_day == $current_day) && ($new_time > $current_time)){
				  $keeper = "new";
			  }

			  if($keeper == "current"){
				  fwrite($output_dup, $line);
			  } else {
				  fwrite($output_dup, $good_array[$index]);
				  $good_array[$index] = $line;
			  }
		  }
	  }
	  fclose($output_dup);
	  for($i = 0; $i < sizeof($good_array); $i++){
		  fwrite($output_overall, $good_array[$i]);
	  }
	  fclose($output_overall);
  }
?>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Barcode Duplication Check</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
	<tr>
		<td width="3%">
		<td><a href="<? echo "overall".$target; ?>" target="<? echo "overall".$target; ?>">Import Information</a></td>
	</tr>
	<tr>
		<td width="3%">
		<td><a href="<? echo "duplicate".$target; ?>" target="<? echo "duplicate".$target; ?>">Earlier-Date Duplicat Information</a></td>
	</tr>
</table>

<?
	include("pow_footer.php");
?>