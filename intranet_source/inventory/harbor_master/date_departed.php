<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Inventory System";
  $area_type = "INVE";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Inventory system");
    include("pow_footer.php");
    exit;
  }

  // make database connections
  include("connect.php");
  $conn = ora_logon("SAG_OWNER@$bni", "SAG");
  $cursor = ora_open($conn);

  $lr_num = trim($HTTP_POST_VARS['lr_num']);
  $date = trim($HTTP_POST_VARS['date']);
  
  if ($lr_num =="")
   	$lr_num = trim($HTTP_GET_VARS['lr_num']);

  if ($HTTP_POST_VARS['save']<>""){
	if ($lr_num <>""){
		$sql = "update voyage set date_departed = to_date('$date','mm/dd/yy hh12:mi am') 
			where lr_num = $lr_num";
	        ora_parse($cursor, $sql);
        	ora_exec($cursor);
	}
  }
  if ($lr_num <>""){
	$sql = "select vessel_name, to_char(date_departed, 'mm/dd/yy hh12:mi AM') from vessel_profile p, voyage v
		where p.lr_num = v.lr_num and p.lr_num = $lr_num";
	ora_parse($cursor, $sql);
	ora_exec($cursor);
	if (ora_fetch($cursor)){
		$vName = ora_getcolumn($cursor, 0);
		$date = ora_getcolumn($cursor, 1);
	}else{
		$date = "";
		$msg = "This vessel is not in the systme.";
	}
  } 

  if ($msg =="")
        $msg = trim($HTTP_GET_VARS['msg']);

?>
<style>td{font:12px}</style>
<script language="javascript">
function clickSave()
{
   document.date_departed.action="date_departed_process.php";
   document.date_departed.submit();
}

function clickExit()
{
  document.location = "../index.php";
}

</script>
<form name=date_departed action="" method="post">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Harbor Master-Vessel Departure Date
	    </font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<table cellpadding="4" cellspacing="0">
	    <tr>
		<td><b>Vessel #:</b></td>
		<td><input type="textbox" name=lr_num value="<?= $lr_num?>" size=10 onchange="javascript:submit()"></td>
	    </tr>
            <tr>
                <td><b>Vessel Name</b></td>
                <td><input type="textbox" value="<?= $vName?>" size=40></td>
            </tr>
            <tr>
                <td><b>Date Departed:</b></td>
                <td><input type="textbox" name=date value="<?= $date?>">(MM/DD/YY HH:MI AM)</td>
            </tr>
	    <tr>
		<td colspan=2>&nbsp;</td>
	    </tr>
            <tr>
                <td colspan=2 align=center>
		    <input type=button name=save value="  Save  " onclick="javascript:clickSave()">
		    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type=button name=exit value="   Exit   " onclick="javascript:clickExit()">
		</td>
            </tr>

	</table>
	<? if ($msg <>"") {?> 
		<p> <font color="red"><?=$msg?></font>
        <? } ?>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
	<b>Undeparted Vessel:</b>
	<table cellpadding="4" cellspacing="0">
<?
	$sql = "select vessel_name from vessel_profile p, voyage v
                where p.lr_num = v.lr_num and date_departed is null and p.lr_num >1000 and p.lr_num <>9999
		order by p.lr_num desc";
        ora_parse($cursor, $sql);
        ora_exec($cursor);
        while(ora_fetch($cursor)){
?>
	    <tr>
		<td><?=ora_getcolumn($cursor, 0)?></td>
            </tr>
<?	}	?>
	</table>  
      </td>
      <td valign="middle" width="30%">
      </td>
   <tr>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />
<?
  include("pow_footer.php");
?>
