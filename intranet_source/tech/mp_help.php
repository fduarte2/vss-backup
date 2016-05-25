<?
   $name = $HTTP_COOKIE_VARS[directoruser];
   list($first_name) = split(" ", $name);
   
//   if ($first_name == "Inigo"){
//	$isITDir = true;
//   }else{
//	$isITDir = false;
//   }

   $today = date("M j, Y");

   // Solid defines for the DB
   include("defines.php");

   // database access parameters
   //include("connect.php");
   $host = "localhost";
   $user = "admin";
   $db = "ccds";
 
  $data = "ts_help_desk";

   // open a connection to the database server
   $connection = pg_connect ("host=$host dbname=$db user=$user");
   $temp = 0;

   if (!$connection){
      printf("Could not open connection to database server.   Please go back to <a href=\"director_login.php\"> Director Login Page</a> and try again later!<br />");
      exit;
   }


   if (isset($HTTP_POST_VARS[save])){
	$user = $HTTP_POST_VARS[user];
        $dept = $HTTP_POST_VARS[dept];
        $system = $HTTP_POST_VARS[system];
        $priority = $HTTP_POST_VARS[priority];
        $problem = $HTTP_POST_VARS[problem];
	$dead_line = $HTTP_POST_VARS[dead_line];
	

	if ($user <>"" && $problem <>"" && $dead_line<>""){
		$query = "insert into $data (date, dept, user_name, system, problem, priority, dead_line, status) values ('".$today."','".$dept."','".$user."','".$system."','".$problem."','".$priority."','".$dead_line."', 'Pending')";
		$result = pg_query($connection, $query) or
        	  	die("Error in query: $query. " .  pg_last_error($connection));

	}    
   }


   if (isset($HTTP_POST_VARS[save_edit])){
	$case_nbr=$HTTP_POST_VARS[case_nbr];
	$status = $HTTP_POST_VARS[status];
        $target = $HTTP_POST_VARS[target];
        $assignedTo = $HTTP_POST_VARS[assignedTo];
        $reviewed = $HTTP_POST_VARS[reviewed];

	$size = count($case_nbr);

	for ($i = 0; $i < $size; $i++) {
		if ($reviewed[$i] =="" && $status[$i] <>"Pending") {
			$query = "update ts_help_desk set status= '".$status[$i]."', target='".$target[$i]."',assigned_To='".$assignedTo[$i]."', reviewed='".$today."' where case_nbr = ".$case_nbr[$i];
		}else {
			$query = "update ts_help_desk set status= '".$status[$i]."', target='".$target[$i]."',assigned_To='".$assignedTo[$i]."' where case_nbr = ".$case_nbr[$i];

		}
		$result = pg_query($connection, $query) or
                        die("Error in query: $query. " .  pg_last_error($connection));


	}

   }



if (isset($HTTP_POST_VARS[to_print])){
       header("Location: ts_help_print.php");
   }

  // generate and execute a query
//   $query = "select case_nbr, date, dept, user_name, system, problem, priority, dead_line, status, reviewed, target, assigned_to from $data order by case_nbr";
   $query = "select case_nbr, priority, dead_line, user_name, dept, system, problem, assigned_to, date, reviewed, target, status from $data order by Priority, case_nbr";

   $result = pg_query($connection, $query) or
             die("Error in query: $query. " .  pg_last_error($connection));

   // get the number of rows in the resultset
   $rows = pg_num_rows($result);

   $Priority = 'H' ;

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>TS --Help Desk</title>

<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body link="#336633" vlink="#999999" alink="#999999">

<table width="99%" Border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
      <td colspan = "2" width = "100%">
         <? include("header.php"); ?>
      </td>
   </tr>
   <tr>
      <td width = "10%" valign = "top"  bgcolor="#4D76A5">
         <table border="0" cellpadding="4" cellspacing="0">
            <tr>
               <td width = "10%">&nbsp;</td>
               <td width = "90%" height = "500" valign = "top">
                  <? include("leftnav.php"); ?>
               </td>
            </tr>
         </table>
      </td>
      <td width = "90%" valign="top">
         <table border="0" width="65%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <p align="left">
                     <font size="5" face="Verdana" color="#0066CC">TS Help Desk</font>
                   <hr>
                 </p>
               </td>
            </tr>

        </table>

        <table border="0" width="65%" cellpadding="0" cellspacing="1">
           <form name = "help"  method="Post" action="">
            <tr>
               <td width="2%">&nbsp;</td>
               <td>
        	<table border="1" cellpadding="0" cellspacing="0">

                <tr bgcolor="#4D76A5">

                        <td width="12%" align="center"><b><font size="2" face="Verdana">Case#</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Priority (H/M/L)</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Deadline</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">From</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Dept</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">System</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Problem/Issue</font></b></td>
			<td width="12%" align="center"><b><font size="2" face="Verdana">Assigned To</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Submitted</font></b></td>
			<td width="12%" align="center"><b><font size="2" face="Verdana">Reviewed</font></b></td>
			<td width="12%" align="center"><b><font size="2" face="Verdana">Target Completion</font></b></td>
                        <td width="12%" align="center"><b><font size="2" face="Verdana">Status</font></b></td>

        	</tr>
<?
        	if ($rows > 0){
                	// iterate through resultset
                	for ($i=0; $i<$rows; $i++){
                        	$row = pg_fetch_row($result, $i);

?>


<?
				if ($Priority != $row[1]) {
					$Priority = $row[1] ;
?>
	        <tr bgcolor=#000000><td colspan=12>&nbsp;</td></tr>
<?
				}
?>

        	<tr>
                	<td align="center" valign="top"><font size="2"><?php echo $row[0] ?></font></td>
                        <input type="hidden" name = "case_nbr[]" value = "<?php echo $row[0] ?>">
<?
   $arrDate = explode('-', $row[8]);
   $mDate = mktime(0,0,0,$arrDate[1], $arrDate[2], $arrDate[0]);
   $display_date = date("m/d/Y", $mDate);

?>
                        <td align="center" valign="top"><font size="2"><?php echo $row[1] ?></font></td>
                        <td align="center" valign="top"><font size="2"><?php echo $row[2] ?></font></td>
                        <td align="center" valign="top"><font size="2"><?php echo $row[3] ?></font></td>
                        <td align="center" valign="top"><font size="2"><?php echo $row[4] ?></font></td>
                        <td align="left" valign="top"><font size="2"><?php echo $row[5] ?></font></td>
                        <td align="center" valign="top"><font size="2"><?php echo $row[6] ?></font></td>
                        <td align="center" valign="top"><font size="2"><?php echo $row[7] ?></font></td>
<?
   if ($isITDir){
	
?>
			<td align="center" valign="top"><font size="2"><select name ="status[]">
				<option value="Pending" <? if ($row[11] == "Pending") echo("selected") ?>>Pending</option>
                                <option value="Assigned" <? if ($row[11] == "Assigned") echo("selected") ?>>Assigned</option>
                                <option value="In Progress" <? if ($row[11] == "In Progress") echo("selected") ?>>In Progress</option>
                                <option value="Completed" <? if ($row[11] == "Completed") echo("selected") ?>>Completed</option>
			</font></td>


<? } else{ ?>
                        <td align="center" valign="top"><font size="2"><?php echo $row[8] ?></font></td>
<? }
   $value = $row[9];
   if ($value<>""){

   	$arrDate = explode('-', $row[9]);
   	$mDate = mktime(0,0,0,$arrDate[1], $arrDate[2], $arrDate[0]);
   	$display_date = date("m/d/Y", $mDate);

   }else { $display_date = "&nbsp";}
?>

			<td align="center" valign="top"><font size="2"><?php echo $display_date ?></font></td>
			<input type="hidden" name = "reviewed[]" value = "<?php echo $row[9] ?>">
<? 
   $value = $row[10];
   if ($value==""){ $value = "&nbsp";}
   if ($isITDir){
?>
			<td align="center" valign="top"><font size="2"><input type="text" name="target[]" size = "10" value ="<?php echo $row[10] ?>"><font size="2"></td>

<? } else { ?>

			<td align="center" valign="top"><font size="2"><?php echo $value ?><font size="2"></td>
<? }
   $value = $row[11];
   if ($value==""){ $value = "&nbsp";}
   if ($isITDir) {
?>
			<td align="center" valign="top"><font size="2"><input type="text" name="assignedTo[]" size="10" value = "<?php echo $row[11] ?>"><font size="2"></td>

<? } else { ?>
                        <td align="center" valign="top"><font size="2"><?php echo $value ?><font size="2"></td>
<? } ?> 
        	</tr>
<?
                }
        }
?>
		
			 
				
	     
	</table>
<p align = "right">
<?
   if ($isITDir) {
?>
<input type="submit" name="save_edit" value="   Save   ">
</p>
<? } ?>
<HR>
	<table>
		<tr>
			<td>User:<input type="text" name = "user" size="15"></td>
			<td>&nbsp;&nbsp;</td>
			<td>Dept:<select name="dept">
				<option value="OPS">OPS</option>
                                <option value="HR">HR</option>
                                <option value="MKT">MKT</option>
                                <option value="FNA">FNA</option>
                                <option value="ENG">ENG</option>
                                <option value="ADMIN">ADMIN</option>
			</select></td>
			<td>&nbsp;&nbsp;</td>
		        <td>System:<select name="system">
                                <option value="BNI-Bill">BNI-Bill</option>
                                <option value="BNI-Inventory">BNI-Invertory</option>
                                <option value="CCDS">CCDS</option>
				<option value="LCS">LCS</option>
                                <option value="Network">Network</option>
				<option value="Hardware">Hardware</option>
				<option value="Print">Print</option>
				<option value="Other">Other</option>
                        </select></td>
                        <td>&nbsp;&nbsp;</td>
                        <td>Priority:<select name="priority">
                                <option value="H">H</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                        </select></td>
			<td>&nbsp;&nbsp;</td>
			<td><nobr>Deadline:</nobr><input type="text" name="dead_line" size="10"></td>		
		</tr>
		<tr>
			<td colspan="9">Problem/Issue:<input type="text" name="problem" size = "120"><td>
		</tr>
		<tr>
			<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
			<td></td>
			<td></td>
                        <td algin = "right"><input type="submit" name="to_print" value = "    Print    ">&nbsp;&nbsp;<input type="submit" name="save" value = "   Save   "></td>

		</tr>
	</table>
	</td>                      
    </tr>

</table>
</body>
</html>

