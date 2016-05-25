<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Director Applications";
  $area_type = "DIRE";

  // Provides header / leftnav
  include("pow_header.php");
/*
  if($access_denied){
    printf("Access Denied from DIRE system");
    include("pow_footer.php");
    exit;
  }
*/

function htmlText($text){
	if ($text =="")
		$text = "&nbsp;";
	return $text;
}
   $user = $HTTP_COOKIE_VARS[lcs_user];
   if($HTTP_SERVER_VARS["argv"][1]<>"email" && $user == ""){
//      header("Location: ../../lcs_login.php");
//      exit;
   }
   $conn = ora_logon("LABOR@LCS", "LABOR");
   if($conn < 1){
//    printf("Error logging on to the Oracle Server: ");
//    printf(ora_errorcode($conn));
//    printf("</body></html>");
    exit;
   }
   $cursor = ora_open($conn);
   $cursor2 = ora_open($conn);

   $sDate = $HTTP_POST_VARS[sDate];
   $eDate = $HTTP_POST_VARS[eDate];
   $isPrint = $HTTP_POST_VARS[rPrint];

   $arrHeading1 = array('sup'=>'', 'sHours'=>'<b>Business Hours</b> (8am-12pm & 1pm-5pm)', 'other'=>'<b>Non Business Hours</b>', 'tVar'=>'<b>Total</b>');

   $arrHeading2 = array('sup'=>'<b>Supervisor</b>', 'bPaid'=>'<b>Paid</b>', 'bTicketed'=>'<b>Ticketed</b>', 'bVar'=>'<b>Balance</b>', 'nbPaid'=>'<b>Paid</b>','nbTicketed'=>'<b>Ticketed</b>','nbVar'=>'<b>Balance</b>', 'tPaid'=>'<b>Paid</b>','tTicketed'=>'<b>Ticketed</b>', 'tVar'=>'<b>Balance</b>');


   $arrCol1 = array('sup'=>array('width'=>120, 'justification'=>'left'),
                   'sHours'=>array('width'=>201, 'justification'=>'center'),
                   'other'=>array('width'=>201, 'justification'=>'center'),
		   'tVar'=>array('width'=>201, 'justification'=>'center'));

   $arrCol2 = array('sup'=>array('width'=>120, 'justification'=>'left'),
                   'bPaid'=>array('width'=>67, 'justification'=>'center'),
                   'bTicketed'=>array('width'=>67, 'justification'=>'center'),
		   'bVar'=>array('width'=>67, 'justification'=>'center'),
		   'nbPaid'=>array('width'=>67, 'justification'=>'center'),
                   'nbTicketed'=>array('width'=>67, 'justification'=>'center'),
                   'nbVar'=>array('width'=>67, 'justification'=>'center'),
                   'tPaid'=>array('width'=>67, 'justification'=>'center'),
                   'tTicketed'=>array('width'=>67, 'justification'=>'center'),
                   'tVar'=>array('width'=>67, 'justification'=>'center'));

   $heading1 = array();
   $heading2 = array();	
   array_push($heading1, $arrHeading1);
   array_push($heading2, $arrHeading2);

   //retrive data to display
   $type[0] = "Operations";
   $type[1] = "Crane";
   $type[2] = "Maintenance";
   $type[3] = "Security";

   $data[0] = array();
   $data[1] = array();
   $data[2] = array();
   $data[3] = array();

for($i = 0; $i < count($type); $i++){
   $tot_bPaid = 0;
   $tot_nbPaid = 0;
   $tot_bTicketed = 0;
   $tot_nbTicketed = 0;


   $sql = "select user_name, sum(bPaid), sum(nbPaid), sum(bTicketed), sum(nbTicketed), p.user_id
	   from paid_hours_vs_labor_ticket p, supervisor_type s
	   where p.user_id = s.user_id and s.type = '$type[$i]' and  
	   hire_date >= to_date('$sDate','mm/dd/yyyy') and hire_date <= to_date('$eDate','mm/dd/yyyy')	
	   group by user_name, p.user_id order by user_name";	

   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   while (ora_fetch($cursor)){
	$user = ora_getcolumn($cursor, 0);
        $bPaid = ora_getcolumn($cursor, 1);
        $nbPaid = ora_getcolumn($cursor, 2);
        $bTicketed = ora_getcolumn($cursor, 3);
        $nbTicketed = ora_getcolumn($cursor, 4);
	$user_id = ora_getcolumn($cursor, 5);


	$tot_bPaid += $bPaid;
	$tot_nbPaid += $nbPaid;
	$tot_bTicketed += $bTicketed;
	$tot_nbTicketed += $nbTicketed;

	array_push($data[$i], array('user_id'=>$user_id,
				'sup'=>ucwords(strtolower($user)), 
				'bPaid'=>number_format($bPaid,1,'.',','), 
				'bTicketed'=>number_format($bTicketed,1,'.',','),
				'bVar'=>number_format($bPaid - $bTicketed, 1,'.',','),
				'nbPaid'=>number_format($nbPaid,1,'.',','),
				'nbTicketed'=>number_format($nbTicketed,1,'.',','),
				'nbVar'=>number_format($nbPaid - $nbTicketed,1,'.',','),
				'tPaid'=>number_format($bPaid + $nbPaid,1,'.',','),
                                'tTicketed'=>number_format($bTicketed + $nbTicketed,1,'.',','), 
				'tVar'=>number_format($bPaid + $nbPaid - $bTicketed - $nbTicketed ,1,'.',',')));
   }
	
   array_push($data[$i], array('sup'=>'<b>Total</b>',
                           'bPaid'=>'<b>'.number_format($tot_bPaid,1,'.',',').'</b>',
                           'bTicketed'=>'<b>'.number_format($tot_bTicketed,1,'.',',').'</b>',
                           'bVar'=>'<b>'.number_format($tot_bPaid - $tot_bTicketed, 1,'.',',').'</b>',
                           'nbPaid'=>'<b>'.number_format($tot_nbPaid,1,'.',',').'</b>',
                           'nbTicketed'=>'<b>'.number_format($tot_nbTicketed,1,'.',',').'</b>',
                           'nbVar'=>'<b>'.number_format($tot_nbPaid - $tot_nbTicketed,1,'.',',').'</b>',
                           'tPaid'=>'<b>'.number_format($tot_bPaid + $tot_nbPaid,1,'.',',').'</b>',
                           'tTicketed'=>'<b>'.number_format($tot_bTicketed + $tot_nbTicketed,1,'.',',').'</b>',
                           'tVar'=>'<b>'.number_format($tot_bPaid + $tot_nbPaid - $tot_bTicketed - $tot_nbTicketed ,1,'.',',').'</b>'));
}
?>
<form action=print.php method=post>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Paid Hours vs Labor Tickets
            </font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <input type=submit name = "rPrint" value="  Print  ">
            <hr>
         </p>
      </td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td><font size = 4><b>Date: <? echo $sDate ?> to <? echo $eDate?></b></font></td>
   </tr>
   <tr>
        <td width="1%">&nbsp;</td>
        <td></td>
   </tr>

</table>
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <input type="hidden" name="sDate" value="<? echo $sDate ?>">
   <input type="hidden" name="eDate" value="<? echo $eDate ?>">

<? for($i = 0; $i < count($type); $i++){
  	if (count($data[$i])>0) {
?>
<tr>
   <td width="1%">&nbsp;</td>
   <td> <b><?echo $type[$i]?></b></td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td>
      <table border="1" cellpadding="0" cellspacing="0" width=890>
        <tr>
                <td align=center width=150 rowspan=2><b>Supervisor</b></td>
                <td align=center colspan=3><b>Business Hours</b><br><font size = 2>(8am-12pm & 1pm-5pm)</font></td>
                <td align=center colspan=3><b>Non Business Hours</b></td>
		<td align=center colspan=3><b>Total</b></td>
        </tr>

      	<tr>
                <td width = 80 align=center><b>Paid</b></td>
                <td width = 80 align=center><b>Ticketed</b></td>
                <td width = 80 align=center><b>Balance</b></td>
   
                <td width = 80 align=center><b>Paid</b></td>
                <td width = 80 align=center><b>Ticketed</b></td>
                <td width = 80 align=center><b>Balance</b></td>

                <td width = 80 align=center><b>Paid</b></td>
                <td width = 80 align=center><b>Ticketed</b></td>
                <td width = 80 align=center><b>Balance</b></td>

	</tr>
<? for($j = 0; $j <count($data[$i]); $j++){ ?>
	<tr>
<? if ($data[$i][$j][user_id] <>""){ ?>
		<td align=left>
		<a href="detail.php?sDate=<?echo $sDate?>&eDate=<?echo $eDate?>&uId=<?echo $data[$i][$j][user_id]?>&sup=<?echo htmlText($data[$i][$j][sup])?>" target="blank" size=full><?echo htmlText($data[$i][$j][sup])?></a></td>
<? }else{ ?>
	<td align=right><?echo htmlText($data[$i][$j][sup])?></td>
<? } ?>
                <td align=right><?echo htmlText($data[$i][$j][bPaid])?></td>
                <td align=right><?echo htmlText($data[$i][$j][bTicketed])?></td>
                <td align=right><?echo htmlText($data[$i][$j][bVar])?></td>
                <td align=right><?echo htmlText($data[$i][$j][nbPaid])?></td>
                <td align=right><?echo htmlText($data[$i][$j][nbTicketed])?></td>
                <td align=right><?echo htmlText($data[$i][$j][nbVar])?></td>
<? if ($data[$i][$j][tPaid] <>""){ ?>
		<td align=right>
		<a href="summary.php?sDate=<?echo $sDate?>&eDate=<?echo $eDate?>&uId=<?echo $data[$i][$j][user_id]?>&type=<?echo $type[$i]?>" target="Summary" size=full><?echo htmlText($data[$i][$j][tPaid])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$i][$j][tPaid])?></td>
<? } ?>
<? if ($data[$i][$j][tTicketed] <>"0"){ ?>
                <td align=right>
                <a href="labor_ticket.php?sDate=<?echo $sDate?>&eDate=<?echo $eDate?>&uId=<?echo $data[$i][$j][user_id]?>&type=<?echo $type[$i]?>" target="Summary" size=full><?echo htmlText($data[$i][$j][tTicketed])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$i][$j][tTicketed])?></td>
<? } ?>
                <td align=right><?echo htmlText($data[$i][$j][tVar])?></td>
	</tr>
<? } ?>

     </table>
   </td>
</tr>
<tr>
   <td width="1%">&nbsp;</td>
   <td></td>
</tr>

<?
	}
   }
?>

<tr>
   <td width="1%">&nbsp;</td>
   <td></td>
</tr>
</table>
<?
  include("pow_footer.php"); 

?>		

