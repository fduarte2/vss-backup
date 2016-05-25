<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Temperature Change Request";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="5" face="Verdana" color="#0066CC">Temperature Change Request</font> 
         <hr>
      </td>
   </tr>
</table>

<table border="0" align= left width="100%" cellpadding="4" cellspacing="1">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>

<p align="center">
   <font size="3" face="Verdana" color="#000080"></font>
<?
   // 8/12/02 - Created by Seth Morecraft
   $web_user = $userdata['username'];
   $mail = $userdata['user_email'];
   $group_id = 1;

  $whse = $HTTP_GET_VARS[whse];
  $bx = $HTTP_GET_VARS[box];
  $temp = $HTTP_GET_VARS[temp];
  $lTemp = $HTTP_GET_VARS[lTemp];
  $hTemp = $HTTP_GET_VARS[hTemp];
  $effect = $HTTP_GET_VARS[effect];
  $expired = $HTTP_GET_VARS[expired];
  $time = $HTTP_GET_VARS[time];
  $exp_time = $HTTP_GET_VARS[exp_time];
  $product = $HTTP_GET_VARS[product];
  $duration = $HTTP_GET_VARS[duration];
  $comments = $HTTP_GET_VARS[comments];
  $msg = $HTTP_GET_VARS[msg];
  

  include("connect_data_warehouse.php");
  $db = "data_warehouse";
/*
  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
*/
  $conn = ora_logon("LABOR@LCS", "LABOR");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);
  $ex_postgres_cursor_whs = ora_open($conn);
  $ex_postgres_cursor_box = ora_open($conn);

  if ($temp == "")
	$temp = "Shut Down";
  if ($temp <>"Shut Down"){
        if ($lTemp == "")
                $lTemp = $temp - 1.5;
        if ($hTemp == "")
                $hTemp = $temp + 1.5;

  }


  if ($whse =="") $whse = "A";
/*
  $sql = "select distinct whse from warehouse_location";
  $whs_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $whs_rows = pg_num_rows($whs_result);

  $sql = "select box from warehouse_location where whse = '$whse' and box <>'Unknown' order by box";
  $box_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $box_rows = pg_num_rows($box_result);
*/
  $sql = "select id, product, low_temp, high_temp from product_temp order by id";
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  while (ora_fetch($cursor)){
        $id = ora_getcolumn($cursor, 0);
	$prod[$id] = ora_getcolumn($cursor, 1);
	$low_temp[$id] = ora_getcolumn($cursor, 2);
        $high_temp[$id] = ora_getcolumn($cursor, 3);
  }


?>
</p>
<!--
<p align="center">Please fill out the following form to submit a temperature change request.<br /><br />
-->
<form name = "temp_req"  method="Post" action="process.php">
<?
  printf("<input type=\"hidden\" name=\"user\" value=\"%s\">", $web_user);
  printf("<input type=\"hidden\" name=\"mail\" value=\"%s\">", $mail);
?>
<!--
<a href="map.php">Map of the Port of Wilmington</a><br /><br />
-->
<script language="JavaScript" src="/functions/calendar.js"></script>
<script language ="javascript">
function changeWhse()
{
	var whse = document.temp_req.whse.options[document.temp_req.whse.selectedIndex].value;
        var box = document.temp_req.box.options[document.temp_req.box.selectedIndex].value;
        var temp = document.temp_req.temp.options[document.temp_req.temp.selectedIndex].value;
	var effect = document.temp_req.effect.value;
        var time = document.temp_req.time.options[document.temp_req.time.selectedIndex].value;
	var expired = document.temp_req.expired.value;
	var exp_time = document.temp_req.time.options[document.temp_req.exp_time.selectedIndex].value;
	var product = document.temp_req.product.options[document.temp_req.product.selectedIndex].value;
//	var duration = document.temp_req.duration.options[document.temp_req.duration.selectedIndex].value;
	var duration = "";
        var comments = document.temp_req.comments.value;

	document.location = 'index.php?whse='+whse+'&box='+box+'&temp='+temp+'&effect='+effect+'&time='+time+'&expired='+expired+'&exp_time='+exp_time+'&product='+product+'&duration='+duration+'&comments='+comments;
}

function history()
{
	document.location ='current.php';
}

function validate()
{
        var temp = document.temp_req.temp.options[document.temp_req.temp.selectedIndex].value;
        var product = document.temp_req.product.options[document.temp_req.product.selectedIndex].value;
	var effect = document.temp_req.effect.value;
        var expired = document.temp_req.expired.value;
        var j = 0;
      	var i = <? echo $id ?>;
        var prod = new Array();
        var low_temp = new Array();
        var high_temp = new Array();
<?
	for ($i = 1; $i <= $id; $i++){
?>
		prod[<? echo $i ?>] = "<? echo $prod[$i]?>";
                low_temp[<? echo $i ?>] = "<? echo $low_temp[$i]?>";
                high_temp[<? echo $i ?>] = "<? echo $high_temp[$i]?>";

		if (product == prod[<? echo $i ?>]) {
			j = <? echo $i ?>;
		} 
<?	
        }
?>

       	if (product == ""){
		alert("Please Select Product!");
		return false;
	}else if (effect == ""){
		alert("Please enter Effective Date!");
                return false;
	}else if (expired ==""){
		alert("Please enter Expiration Date!");
                return false;
	}else if (temp =="Shut Down" && product !="Empty"){
		alert("Can not Shut Down if there are pallets in warehouse!");	
		return false;
        }else if (temp != "Shut Down" && product =="Empty"){
                reply = confirm("Are you sure you want to set Temperature to "+temp+"?");
                return reply;
	}else if (product != "" && product !="Empty"){
		if (temp - 0 < low_temp[j] - 0 || temp - 0  > high_temp[j] - 0){
			reply = confirm("The request temperature is out of normal range of selected product.  \n\n Are you sure you want to setup temperature to " + temp +  "?");
                	return reply;
		}
        }
}
</script>
<nobr>
<font size = 2>
Warehouse:
</font>
<select name="whse" onChange="changeWhse()">
<?
/*
	for($i = 0; $i < $whs_rows; $i++){
		$row = pg_fetch_row($whs_result, $i);
		$whs = $row[0];
                if ($whse ==$whs){
			$strSelected = "SELECTED";
		}else{
			$strSelected = "";
		}
		printf("<option value = '$whs' $strSelected>$whs</option>");
	}
*/
	$sql = "select distinct WHSE from warehouse_location order by WHSE";
	ora_parse($ex_postgres_cursor_whs, $sql);
	ora_exec($ex_postgres_cursor_whs);
	while(ora_fetch_into($ex_postgres_cursor_whs, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<option value="<? echo $row['WHSE']; ?>"<? if($row['WHSE'] == $whse){?> selected <?}?>><? echo $row['WHSE']; ?></option>
<?
	}
?>
</select>
&nbsp;
<font size = 2>
Box:
</font>
<select name="box">
<?
/*
	for($i = 0; $i < $box_rows; $i++){
		$row = pg_fetch_row($box_result, $i);
		$box = $row[0];
		if ($box == $bx){
			$strSelected = "SELECTED";
                }else{
                        $strSelected = "";
                }
		printf("<option value = '$box' $strSelected>$box</option>");
	}
*/
	$sql = "select BOX from warehouse_location where whse = '$whse' and box <>'Unknown' order by box";
	ora_parse($ex_postgres_cursor_box, $sql);
	ora_exec($ex_postgres_cursor_box);
	while(ora_fetch_into($ex_postgres_cursor_box, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
		<option value="<? echo $row['BOX']; ?>"<? if($row['BOX'] == $bx){?> selected <?}?>><? echo $row['BOX']; ?></option>
<?
	}
?>
</select>
&nbsp;
<font size = 2>
Commodity:
</font>
<select name="product">
<option value="" <? if ($product =="") echo "selected" ?>></option>
<option value="Empty" <? if ($product =="Empty") echo "selected" ?>>Empty</option>
<?
for ($i = 1 ; $i <= $id; $i++){
   if ($product == $prod[$i]) {
	$strSelect = "selected";
   }else{
	$strSelect = "";
   }
?>
<option value="<? echo $prod[$i] ?>" <? echo $strSelect ?>><? echo $prod[$i] ?></option>
<?
} 
?>
<!--
<option value="Meat" <? if ($product =="Meat") echo "selected" ?>>Meat</option>
<option value="Apple Juice" <? if ($product =="Apple Juice") echo "selected" ?>>Apple Juice</option>
<option value="Orange Juice" <? if ($product =="Orange Juice") echo "selected" ?>>Orange Juice</option>
<option value="Apples" <? if ($product =="Apples") echo "selected" ?>>Apples</option>
<option value="Clementines" <? if ($product =="Clementines") echo "selected" ?>>Clementines</option>
<option value="Grapes" <? if ($product =="Grapes") echo "selected" ?>>Grapes</option>
<option value="Kiwi" <? if ($product =="Kiwi") echo "selected" ?>>Kiwi</option>
<option value="Melons" <? if ($product =="Melons") echo "selected" ?>>Melons</option>
<option value="Oranges" <? if ($product =="Oranges") echo "selected" ?>>Oranges</option>
<option value="Pears" <? if ($product =="Pears") echo "selected" ?>>Pears</option>
<option value="Pineapples" <? if ($product =="Pineapples") echo "selected" ?>>Pineapples</option>
<option value="Plums" <? if ($product =="Plums") echo "selected" ?>>Plums</option>
-->
</select>
<!--
</nobr>
<br \><br \>
<nobr>
-->
&nbsp;
<font size = 2>
Requested(°F):
</font>
<select name="temp">
<option value="Shut Down" <?if ($temp=="Shut Down") echo "selected"?>>Shut Down</option>
<?

  for($i = 0; $i <= 65; $i += 1){
   if($i == 30){
     for(; $i < 38; $i += .5){
       if ($temp <> "Shut Down" && $temp == $i){
                $strSelected = "selected";
        }else{
                $strSelected ="";
        }
       printf("<option value=\"%s\" $strSelected>%s</option>\n", $i, $i);
     }
   }
    if ($temp <> "Shut Down" && $temp == $i){
                $strSelected = "selected";
    }else{
                $strSelected ="";
    }

   printf("<option value=\"%s\" $strSelected>%s</option>\n", $i, $i);
  }
?>
</select>
<!--
&nbsp;
<font size = 2>
Temp Range from:
</font>
<select name=lTemp>
<? 
  if ($temp <>"Shut Down" && $temp <>""){
	for ($i = -7; $i <= $temp - 1.5; $i += 0.5){
		if ($i == $lTemp){
			$strSelect = "selected";
		}else{
			$strSelect = "";
		}		

?>
<option value="<?=$i?>" <?= $strSelect?>><?=$i?></option>	
<?	}
   }else{
?>
<option value=""></option>
<? } ?>
</select>
&nbsp;
<font size = 2>
to:
</font>
<select name=hTemp>
<?
   if ($temp <>"Shut Down" && $temp <>""){
        for ($i = $temp + 1.5; $i<=65; $i += 0.5){
                if ($i == $hTemp){
                        $strSelect = "selected";
                }else{
                        $strSelect = "";
                }

?>
<option value="<?=$i?>" <?= $strSelect?>><?=$i?></option>
<?      }
   }else{
?>
<option value=""></option>
<? } ?>
</select>
-->
<br \><br \>
<nobr>
<font size = 2>
Start:
</font>
<input type="textbox" name="effect" size=10 value="<? echo $effect; ?>" readonly><a href="javascript:show_calendar('temp_req.effect');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
&nbsp;
<font size = 2>
Time:
</font>
<select name = "time">
<?
   $eTime = date("h:i A", mktime(0,0,0,date("m"),date("d"), date("y")));
   for($i = 1 ; $i <= 24; $i++){
     if ($time == $eTime){
	$strSelected = "selected";
     }else{
	$strSelected = "";
     }	
     printf("<option value=\"%s\" $strSelected>%s</option>\n", $eTime, $eTime);
     $eTime = date("h:i A", mktime($i,0,0,date("m"),date("d"), date("y")));

   }
?>
</select>

&nbsp;
<font size = 2>
<!--
Duration:
</font>
<select name="duration">
<option value="Less than one Week" <? if ($duration =="Less than one Week") echo "selected" ?>>Less than one Week</option>
<option value="Greater than one Week"  <? if ($duration =="Greater than one Week") echo "selected" ?>>Greater than one Week (but less than a month)</option>
<option value="Greater than one month"  <? if ($duration =="Greater than one month") echo "selected" ?>>Greater than one month</option>
</select>
-->
<!--
<br \><br \>
<nobr>
-->
<font size = 2>
Expiration Date:
</font>
<input type="textbox" name="expired" size=10 value="<? echo $expired; ?>" readonly><a href="javascript:show_calendar('temp_req.expired');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a>
&nbsp;
<font size = 2>
Time:
</font>
<select name = "exp_time">
<?
   $eTime = date("h:i A", mktime(0,0,0,date("m"),date("d"), date("y")));
   for($i = 1 ; $i <= 24; $i++){
     if ($exp_time == $eTime){
        $strSelected = "selected";
     }else{
        $strSelected = "";
     }
     printf("<option value=\"%s\" $strSelected>%s</option>\n", $eTime, $eTime);
     $eTime = date("h:i A", mktime($i,0,0,date("m"),date("d"), date("y")));

   }
?>
</select>

</nobr>
<br \><br \>
<nobr>

<font size = 2>
Comments:
</font>
<input type="textbox" name="comments" size="80" maxlength="200" value ="<? echo $comments?>">
</nobr>
<?if ($msg <>""){
?>
<br \><br \>
<center><font color=red><?=$msg?></font></center>
<?}?>

<br \><br \>
<input type="Submit" value="     Submit     " onClick="return validate()"> &nbsp;&nbsp;&nbsp;&nbsp;
<input type="Reset" value="      Clear      ">&nbsp;&nbsp;&nbsp;&nbsp;
<?

  if($group_id ==1){
      printf("<input type=\"button\" value=\"     History     \"  onClick=\"history()\">");
  }
?>
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="Current Temp" onclick="document.location='current_temp.php'";>&nbsp;&nbsp;&nbsp;&nbsp;

</form>

<p align="center">

<br \>
<b>Port Of Wilmington Map</b>
<br \>
<img src="images/whs.jpg" width = "700" hight=100>
</p>
      </td> 
   </tr>
</table>

<? include("pow_footer.php"); ?>
