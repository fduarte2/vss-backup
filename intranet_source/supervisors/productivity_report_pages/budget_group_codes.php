<?
  // All POW files need this session file included
  include("pow_session.php");

  $conn = ora_logon("SAG_Owner@BNI", "SAG");
  if($conn < 1){
			printf("Error logging on to the Oracle Server: ");
			printf(ora_errorcode($conn));
			exit;
  }
  $cursor1 = ora_open($conn);
  $cursor2 = ora_open($conn);
?>
<html>
<head>
<title>Commodity Groupings</title>
</head>
<body  BGCOLOR=#FFFFFF topmargin="0" leftmargin="0" link="<? echo $link; ?>" vlink="<? echo $vlink; ?>" 
       alink="<? echo $alink; ?>">
<table border="0" width="100%" cellpadding="2" cellspacing="1">
	<tr>
		<td width="5%">&nbsp;</td>
		<td>
		<table border="1" width="100%" cellpadding="2" cellspacing="0">
			<tr bgcolor="#66CCCC">
				<td width="50%" align="left">Commodity Grouping</td>
				<td width="50%" align="left">Included Commodity Codes</td>
			</tr>
<?
	$sql = "SELECT DISTINCT GROUP_CODE FROM COMMODITY_PROFILE WHERE GROUP_CODE IS NOT NULL ORDER BY GROUP_CODE";
	ora_parse($cursor1, $sql);
	ora_exec($cursor1);
	while(ora_fetch_into($cursor1, $row1, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr bgcolor="FFCC33">
<?
		$sql = "SELECT COMMODITY_NAME FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '".$row1['GROUP_CODE']."'";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC);
?>
				<td><? echo $row2['COMMODITY_NAME']; ?></td>
				<td>&nbsp;</td>
			</tr>
<?
		$sql = "SELECT * FROM COMMODITY_PROFILE WHERE GROUP_CODE = '".$row1['GROUP_CODE']."' ORDER BY COMMODITY_NAME";
		ora_parse($cursor2, $sql);
		ora_exec($cursor2);
		while(ora_fetch_into($cursor2, $row2, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
?>
			<tr>
				<td>&nbsp;</td>
				<td><? echo $row2['COMMODITY_NAME']; ?></td>
			</tr>
<?
		}
	}
?>
		</table></td>
		<td width="40%">&nbsp;</td>
	</tr>
</table></body></html>