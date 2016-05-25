<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "RADIO AND REPAIR REQUEST";
  $area_type = "ROOT"; 
  $user = $userdata['username'];
  if($user == "Anonymous"){
    $area_type = "ROOT";
    include("pow_header.php");
    printf("Access Denied!");
    include("pow_footer.php");
    exit;
  }
  include("pow_header.php");
 
  $date = date('m/d/Y'); 
  $user = $userdata['username'];
 
  include("connect.php");
  $db = "ccds";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $ticket_no = $HTTP_GET_VARS['ticket_no'];
  if ($ticket_no <>""){
	$sql = "select to_char(date,'mm/dd/yyyy'), user_name, model_no, serial_no, ticket_no, problem 
		from radio_repair_header
		where ticket_no = $ticket_no";
	$result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
	$rows = pg_num_rows($result);
    	if ($rows > 0){
		$row = pg_fetch_row($result, 0);
		$date = $row[0];
		$user = $row[1];
		$model = $row[2];
		$serial = $row[3];
		$ticket_no = $row[4];
		$problem = $row[5];

                $sql = "select id, replace_id from radio_replace c left outer join radio_replace_requested r
                        on c.id = r.replace_id and ticket_no = $ticket_no order by id";
                $replace_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
                $replace_rows = pg_num_rows($replace_result);
                for ($i = 0; $i < $replace_rows; $i++){
                        $row = pg_fetch_row($replace_result, $i);
                        if ($row[1] <> ""){
                                $rChecked[$i+1] = "checked";
                        }else{
                                $rChecked[$i+1] = "";
                        }
                }

		$sql = "select id, channel_id from radio_channel c left outer join radio_channel_requested r 
			on c.id = r.channel_id and ticket_no = $ticket_no order by id";
        	$channel_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
        	$channel_rows = pg_num_rows($channel_result);
		for ($i = 0; $i < $channel_rows; $i++){
			$row = pg_fetch_row($channel_result, $i);
			if ($row[1] <> ""){
				$cChecked[$i+1] = "checked";
			}else{
				$cChecked[$i+1] = "";
			}
		}
     	}else{
		$ticket_no = "";
	}
  }

?>
<style>
  td {font-size: 12px;}
</style>
<script language ="javascript">
function changeTicket(){
  var ticket_no = document.radio_req.ticket_no.value;
  document.location = "index.php?ticket_no="+ticket_no
}

function checkValue(){
  var web_user = "<?echo $userdata['username'];?>";
  var model = document.radio_req.model.value;
  var serial = document.radio_req.serial.value;
  var user = document.radio_req.user.value;
  var problem = document.radio_req.problem.value;

  if (web_user != user){
	alert("You can't edit other users request!");
	return false;
  } 

  if (model == "" || serial ==""){
	alert("Please enter Model# and Serial#");
	return false;
  }
  if (problem == ""){
	alert("Please enter problem detail!");
	return false;
  }

  if (problem.length >= 300){
	alert("Problem detail must be 300 characters or less.");
	return false;
  }
  return true;
}

function printRequest(){
  var ticket_no = document.radio_req.ticket_no.value;
  if (ticket_no == ""){
	alert("Please create request first!");
	exit;
  }
  document.location = "print.php?ticket_no="+ticket_no

}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <form name = "radio_req"  method="Post" action="process.php">

   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">New Radio or Radio Repair Request</font>&nbsp;&nbsp;&nbsp;
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <input type="submit" name="save" value=" Save " onclick ="return checkValue()">&nbsp;&nbsp;&nbsp;
         <input type="button" name="print" value=" Print " onclick="printRequest()">
         <hr>
      </td>
   </tr>
  
   <table border="0" align= left width="100%" cellpadding="2" cellspacing="1">
      <tr>
        <td width="1%">&nbsp;</td>
        <td>Date:&nbsp;&nbsp;&nbsp;&nbsp;<input type="textbox" name=date value="<?echo $date?>" readonly></td>
	<td width = "1%">&nbsp;</td>
	<td>User:&nbsp;&nbsp;&nbsp;&nbsp;<input type="textbox" name=user value="<?echo $user?>" readonly></td>
        <td width = "1%">&nbsp;</td>
        <td align=left width="40%">Ticket#:<input type="textbox" name="ticket_no" value="<?echo $ticket_no ?>" onchange="changeTicket()" size=4></td>	  
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td>Model#:<input type="textbox" name="model" value="<?echo $model?>"></td>
        <td>&nbsp;</td>
        <td>Serial#:<input type="textbox" name="serial" value="<?echo $serial?>"></td>
	<td colspan=2>&nbsp;</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5><i>(You can find Model# and Serial# on the back of the radio after removed battery.)</i></td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5>&nbsp;</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5 ><b>Problem Detail:</b></td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
	<td colspan=5><textarea name=problem rows=3 cols=120 wrap maxlength=300><?echo $problem?></textarea>
	</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5>&nbsp;</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5><b>Please Replace:</b></td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5>
            <table>
                <tr>
                   <td><input type="checkbox" name="replace[]" value=1 <?echo $rChecked[1]?>> &nbsp;Speaker MIC</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=2 <?echo $rChecked[2]?>> &nbsp;Headset</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=3 <?echo $rChecked[3]?>> &nbsp;Battery</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=4 <?echo $rChecked[4]?>> &nbsp;Belt Clip</td>
                   <td width="1%">&nbsp;</td>
                </tr>
                <tr>
                   <td><input type="checkbox" name="replace[]" value=5 <?echo $rChecked[5]?>> &nbsp;Antenna</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=6 <?echo $rChecked[6]?>> &nbsp;Charger</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=7 <?echo $rChecked[7]?>> &nbsp;Spare battery</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="replace[]" value=8 <?echo $rChecked[8]?>> &nbsp;Case/Holder</td>
                   <td colspan=3 width="1%">&nbsp;</td>
                </tr>
            </table>
        </td>
     </tr>
<!--
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5> 
   	    <table border="0" align= left width="100%" cellpadding="2" cellspacing="0">
      		<tr>
        	   <td><b>Catergory</b></td>
        	   <td><b>Problem</b></td>
     		</tr>       
                <tr>
		   <td>Speaker MIC</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[1]?>" size=110><td>
		   <input type="hidden" name="catergory_id[]" value =1>
		</tr>
		<tr>
                   <td>Headset</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[2]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =2>
                </tr>
                <tr>
                   <td>Battery</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[3]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =3>
                </tr>
                <tr>
                   <td>Belt Clip</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[4]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =4>
                </tr>
                <tr>
                   <td>Antenna</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[5]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =5>
                </tr>
                <tr>
                   <td>Charger</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[6]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =6>
                </tr>
                <tr>
                   <td>Spare battery</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[7]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =7>
                </tr>
                <tr>
                   <td>Case/Holder</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[8]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =8>
                </tr>
                <tr>
                   <td>Other</td>
                   <td><input type="textbox" name="problems[]" value="<?echo $problems[9]?>" size=110><td>
                   <input type="hidden" name="catergory_id[]" value =9>
                </tr>
   	    </table>
         </td>
     </tr>
-->
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5>&nbsp;</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5><b>Channels requested:</b></td>
     </tr>
     <tr>
	<td width="1%">&nbsp;</td>
      	<td colspan=5>
	    <table>
		<tr>
		   <td><input type="checkbox" name="channels[]" value=1 <?echo $cChecked[1]?>> &nbsp;Port 1</td>
		   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=2 <?echo $cChecked[2]?>> &nbsp;Port 2</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=3 <?echo $cChecked[3]?>> &nbsp;Port 3</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=4 <?echo $cChecked[4]?>> &nbsp;Port 4</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=5 <?echo $cChecked[5]?>> &nbsp;Port 5</td>
                   <td width="1%">&nbsp;</td>
		</tr>
                <tr>
                   <td><input type="checkbox" name="channels[]" value=6 <?echo $cChecked[6]?>> &nbsp;Security</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=7 <?echo $cChecked[7]?>> &nbsp;HR</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=8 <?echo $cChecked[8]?>> &nbsp;Crane</td>
                   <td width="1%">&nbsp;</td>
                   <td><input type="checkbox" name="channels[]" value=9 <?echo $cChecked[9]?>> &nbsp;Supervisor</td>
                   <td colspan=3 width="1%">&nbsp;</td>
                </tr>
            </table>
	</td>
     </tr>  
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5>&nbsp;</td>
     </tr>
     <tr>
        <td width="1%">&nbsp;</td>
        <td colspan=5><b><i>Please print this request form, have it initialed by your department director, and drop it off in Inigo's Office. </i></b></td>
     </tr>

   </table>
</table>
<? include("pow_footer.php"); ?>
