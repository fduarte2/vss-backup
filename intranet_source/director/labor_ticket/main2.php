<?
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

   $sDate = $HTTP_POST_VARS[sDate];
   $eDate = $HTTP_POST_VARS[eDate];
   $isPrint = $HTTP_POST_VARS[rPrint];

   $arrHeading1 = array('date'=>'', 'sup'=>'', 'pHour'=>'<b>Paid Hours</b>', 'lTicket'=>'<b>Labor Tickets</b>');

   $arrHeading2 = array('date'=>'<b>Date</b>', 'sup'=>'<b>Supervisor</b>', 'pReg'=>'<b>Reg</b>', 'pOT'=>'<b>OT</b>', 'pDT'=>'<b>DT</b>', 'pOther'=>'<b>Other</b>','lST'=>'<b>ST</b>','lOT'=>'<b>OT</b>', 'lDT'=>'<b>DT</b>','lMH'=>'<b>MH</b>','lDF'=>'<b>DF</b>');


   $arrCol1 = array('date'=>array('width'=>100, 'justification'=>'left'),
                   'sup'=>array('width'=>150, 'justification'=>'left'),
                   'pHour'=>array('width'=>180, 'justification'=>'center'),
                   'lTicket'=>array('width'=>225, 'justification'=>'center'));

   $arrCol2 = array('date'=>array('width'=>100, 'justification'=>'left'),
                   'sup'=>array('width'=>150, 'justification'=>'left'),
                   'pReg'=>array('width'=>45, 'justification'=>'center'),
                   'pOT'=>array('width'=>45, 'justification'=>'center'),
                   'pDT'=>array('width'=>45, 'justification'=>'center'),
                   'pOther'=>array('width'=>45, 'justification'=>'center'),
		   'lST'=>array('width'=>45, 'justification'=>'center'),
                   'lOT'=>array('width'=>45, 'justification'=>'center'),
                   'lDT'=>array('width'=>45, 'justification'=>'center'),
                   'lMH'=>array('width'=>45, 'justification'=>'center'),
                   'lDF'=>array('width'=>45, 'justification'=>'center'));

   $heading1 = array();
   $heading2 = array();	
   array_push($heading1, $arrHeading1);
   array_push($heading2, $arrHeading2);
   $data = array();

   include 'class.ezpdf.php';
   $pdf = new Cezpdf('letter','landscape');

   $pdf->ezSetMargins(40,40,50,40);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
   $tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
   $pdf->setFontFamily('Helvetica.afm', $tmp);

   $format = "Printed On: " . date('m/d/y g:i A');

   $all = $pdf->openObject();
   $pdf->saveState();
   $pdf->setStrokeColor(0,0,0,1);
   $pdf->addText(650, 580,8, $format);
   $pdf->restoreState();
   $pdf->closeObject();
   $pdf->addObject($all,'all');

   // Write out the intro.
   // Print Receiving Header
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b>Paid Hours vs Labor Tickets</b>", 24, $center);
   $pdf->ezSetDy(-10);
   $pdf->ezText("<b><i> $sDate to $eDate </i></b>", 18, $center);
   $pdf->ezSetDy(-15);

  // $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');

   $tot_pReg = 0;
   $tot_pOT = 0;
   $tot_pDT = 0;
   $tot_pOther = 0;
   $tot_lST = 0;
   $tot_lOT = 0;
   $tot_lDT = 0;
   $tot_lOther = 0;


   $sTime = strtotime($sDate);
   $eTime = strtotime($eDate);
   while ($sTime <= $eTime){
        $vDate = date('m/d/Y', $sTime);
	$sTime += 24*60*60;

	$pre_user = "";
   	$user = array();
   	$pReg = array();
   	$pOT = array();
   	$pDT = array();
   	$pOther = array();
   	$lST = array();
   	$lOT = array();
   	$lDT = array();
   	$lMH = array();
	$lDF = array();
   	$pHours = 0;
   	$lHours = 0;
   	$i = 0;

   	$sql = "select user_name, earning_type_id, sum(duration) from hourly_detail d, lcs_user u
           	where hire_date =to_date('$vDate','mm/dd/yyyy') and  u.user_id = d.user_id
           	group by user_name, earning_type_id order by user_name, earning_type_id";   
	$statement = ora_parse($cursor, $sql);
   	ora_exec($cursor);
   	while (ora_fetch($cursor)){
		$name = ora_getcolumn($cursor, 0);

		if ($i == 0 && $pre_user ==""){
                	$pReg[$i] = 0;
                	$pOT[$i] = 0;
                	$pDT[$i] = 0;
                	$pOther[$i] = 0;
			$pre_user = $name;
		}else if($name <> $pre_user){
			$i++;
			$pre_user = $name;
			$pReg[$i] = 0;
			$pOT[$i] = 0;
			$pDT[$i] = 0;
			$pOther[$i] = 0;
		}
        
		$user[$i] = ora_getcolumn($cursor, 0);
        	$eType = ora_getcolumn($cursor, 1);
        	$pHours = ora_getcolumn($cursor, 2);
 
      		switch ($eType){
        		case "REG":
				$pReg[$i] = $pHours;
				break;
                	case "OT":
				$pOT[$i] = $pHours;
				break;
			case "DT":
				$pDT[$i] = $pHours;
				break;
			default:
				$pOther[$i] +=$pHours;
		}
		
   	}

   	for ($i = 0; $i < count($user); $i++){

		$sql = "select rate_type, sum(hours) from labor_ticket_header h, labor_ticket t, lcs_user u
			where t.ticket_num = h.ticket_num and h.user_id = u.user_id and u.user_name = '$user[$i]' and 
			service_date =to_date('$vDate','mm/dd/yyyy') 
			group by rate_type";
		$statement = ora_parse($cursor, $sql);
  	 	ora_exec($cursor);
		$lST[$i] = 0;
		$lOT[$i] = 0;
		$lDT[$i] = 0;
		$lMH[$i] = 0;
		$lDF[$i] = 0;
		while (ora_fetch($cursor)){
			$rType = ora_getcolumn($cursor, 0);
			$lHours = ora_getcolumn($cursor, 1);

        		switch ($rType){
                		case "ST":
                        		$lST[$i] = $lHours;
                        		break;
                		case "OT":
                        		$lOT[$i] = $lHours;
                        		break;
                		case "DT":
                        		$lDT[$i] = $lHours;
                        		break;
                		case "MH":
                        		$lMH[$i] = $lHours;
					break;
				case "DF":
					$lDF[$i] = $lHours;
        		}
		}
   	}

        $sql = "select user_name, rate_type, sum(hours) from labor_ticket_header h, labor_ticket t, lcs_user u
                where t.ticket_num = h.ticket_num and h.user_id = u.user_id and service_date =to_date('$vDate','mm/dd/yyyy')
                and h.user_id not in 
		(select distinct user_id from hourly_detail where service_date =to_date('$vDate','mm/dd/yyyy'))
		group by user_name, rate_type order by user_name, rate_type";
        $statement = ora_parse($cursor, $sql);
        ora_exec($cursor);
        $cnt = count($user);
	$pre_user = "";
        $i = $cnt; 
       	while (ora_fetch($cursor)){
                $name = ora_getcolumn($cursor, 0);

                if ($i == $cnt && $pre_user ==""){
                        $lST[$i] = 0;
                        $lOT[$i] = 0;
                        $lDT[$i] = 0;
                        $lMH[$i] = 0;
			$lDF[$i] = 0;
                        $pre_user = $name;
                }else if($name <> $pre_user){
                        $i++;
                        $pre_user = $name;
                        $lST[$i] = 0;
                        $lOT[$i] = 0;
                        $lDT[$i] = 0;
                        $lMH[$i] = 0;
			$lDF[$i] = 0;
                }
		$user[$i] = ora_getcolumn($cursor, 0);
             	$rType = ora_getcolumn($cursor, 1);
                $lHours = ora_getcolumn($cursor, 2);

              	switch ($rType){
              		case "ST":
                   		$lST[$i] = $lHours;
                        	break;
              		case "OT":
                   		$lOT[$i] = $lHours;
                   		break;
                   	case "DT":
                        	$lDT[$i] = $lHours;
                     		break;
             		case "MH":
                     		$lMH[$i] = $lHours;
                       		break;
             		case "DF":
                       		$lDF[$i] = $lHours;
                }
	}
        

   	for ($i = 0; $i < count($user); $i++){
		array_push($data, array('date'=>$vDate, 'sup'=>$user[$i], 'pReg'=>$pReg[$i], 'pOT'=>$pOT[$i], 'pDT'=>$pDT[$i], 'pOther'=>$pOther[$i], 'lST'=>$lST[$i], 'lOT'=>$lOT[$i], 'lDT'=>$lDT[$i], 'lMH'=>$lMH[$i],'lDF'=>$lDF[$i]));
		$tot_pReg += $pReg[$i];
    		$tot_pOT += $pOT[$i];
		$tot_pDT += $pDT[$i];
        	$tot_pOther += $pOther[$i];
        	$tot_lST += $lST[$i];
  		$tot_lOT += $lOT[$i];
		$tot_lDT += $lDT[$i];
		$tot_lMH += $lMH[$i];
                $tot_lDF += $lDF[$i];
 	}

   }
   array_push($data, array('date'=>'<b>Total</b>', 'pReg'=>$tot_pReg, 'pOT'=>$tot_pOT, 'pDT'=>$tot_pDT, 'pOther'=>$tot_pOther, 'lST'=>$tot_lST, 'lOT'=>$tot_lOT, 'lDT'=>$tot_lDT, 'lMH'=>$tot_lMH, 'lDF'=>$tot_lDF));

if ($isPrint <>""){
   $pdf->ezSetDy(-15);
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica-Bold.afm');
   $pdf->ezTable($heading1, $arrHeading1, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol1));
   $pdf->ezTable($heading2, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');

   $pdf->ezTable($data, $arrHeading2, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol2));

   $pdf->ezStream();

}else{
		
?>
<form action=main2.php method=post>

<table border="0" width="65%" cellpadding="4" cellspacing="0">
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
<table border="0" width="65%" cellpadding="4" cellspacing="0">
   <input type="hidden" name="sDate" value="<? echo $sDate ?>">
   <input type="hidden" name="eDate" value="<? echo $eDate ?>">

<tr>
   <td width="1%">&nbsp;</td>
   <td>
      <table border="1" cellpadding="0" cellspacing="0" width=740>
        <tr>
                <td align=center width=100 rowspan=2><b>Date</b></td>
                <td align=center width=150 rowspan=2><b>Supervisor</b></td>
                <td align=center width=200 colspan=4><b>Paid Hours</b></td>
                <td align=center width=200 colspan=5><b>Labor Tickets</b></td>
        </tr>

      	<tr>
                <td width=50 align=center><b>Reg</b></td>
                <td width=50 align=center><b>OT</b></td>
                <td width=50 align=center><b>DT</b></td>
                <td width=50 align=center><b>Other</b></td>
                <td width=50 align=center><b>ST</b></td>
                <td width=50 align=center><b>OT</b></td>
                <td width=60 align=center><b>DT</b></td>
                <td width=60 align=center><b>MH</b></td>
		<td width=60 align=center><b>DF</b></td>
	</tr>
<? for($j = 0; $j <count($data); $j++){ ?>
	<tr>
		<td align=center><?echo htmlText($data[$j][date])?></td>
                <td align=left><?echo htmlText($data[$j][sup])?></td>
                <td align=right><?echo htmlText($data[$j][pReg])?></td>
                <td align=right><?echo htmlText($data[$j][pOT])?></td>
                <td align=right><?echo htmlText($data[$j][pDT])?></td>
                <td align=right><?echo htmlText($data[$j][pOther])?></td>
<? if ($j < count($data)-1 && $data[$j][lST] > 0) { ?>
                <td align=right><a href="labor_ticket.php?date=<?echo $data[$j][date]?>&sup=<?echo $data[$j][sup]?>&type=ST" target="blank"><?echo htmlText($data[$j][lST])?></a></td>
<? }else{ ?>
		<td align=right><?echo htmlText($data[$j][lST])?></td>
<? } ?>
<? if ($j < count($data)-1 && $data[$j][lOT] > 0) { ?>
                <td align=right><a href="labor_ticket.php?date=<?echo $data[$j][date]?>&sup=<?echo $data[$j][sup]?>&type=OT" target="blank"><?echo htmlText($data[$j][lOT])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$j][lOT])?></td>
<? } ?>
<? if ($j < count($data)-1 && $data[$j][lDT] > 0) { ?>
                <td align=right><a href="labor_ticket.php?date=<?echo $data[$j][date]?>&sup=<?echo $data[$j][sup]?>&type=DT" target="blank"><?echo htmlText($data[$j][lDT])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$j][lDT])?></td>
<? } ?>
<? if ($j < count($data)-1 && $data[$j][lMH] > 0) { ?>
                <td align=right><a href="labor_ticket.php?date=<?echo $data[$j][date]?>&sup=<?echo $data[$j][sup]?>&type=MH" target="blank"><?echo htmlText($data[$j][lMH])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$j][lMH])?></td>
<? } ?>
<? if ($j < count($data)-1 && $data[$j][lDF] > 0) { ?>
                <td align=right><a href="labor_ticket.php?date=<?echo $data[$j][date]?>&sup=<?echo $data[$j][sup]?>&type=DF" target="blank"><?echo htmlText($data[$j][lDF])?></a></td>
<? }else{ ?>
                <td align=right><?echo htmlText($data[$j][lDF])?></td>
<? } ?>
<!--
                <td align=right><?echo htmlText($data[$j][lOT])?></td>
                <td align=right><?echo htmlText($data[$j][lDT])?></td>
                <td align=right><?echo htmlText($data[$j][lMH])?></td>
                <td align=right><?echo htmlText($data[$j][lDF])?></td>
-->	</tr>
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
?>		

<?

/*
   if($HTTP_SERVER_VARS["argv"][1]<>"email"){
//        $pdf->ezStream();
   }else{

        // output
        $pdfcode = $pdf->ezOutput();

        $File=chunk_split(base64_encode($pdfcode));


        $mailTo1 = "rwang@port.state.de.us";

	$mailTo = "gbailey@port.state.de.us,";
	$mailTo .= "ithomas@port.state.de.us,";
//        $mailTo .= "ffitzgerald@port.state.de.us,";
	$mailTo .="parul@port.state.de.us";

        $mailsubject = "Productivity Report";

        $mailheaders = "From: MailServer@port.state.de.us\r\n";
        $mailheaders .= "Cc: wstans@port.state.de.us,jjaffe@port.state.de.us\r\n";
        $mailheaders .= "Bcc: " . "hdadmin@port.state.de.us,rwang@port.state.de.us\r\n";
        $mailheaders .= "MIME-Version: 1.0\r\n";
        $mailheaders .= "Content-Type: multipart/mixed; boundary=\"MIME_BOUNDRY\"\r\n";
        $mailheaders .= "X-Sender: MailServer@port.state.de.us\r\n";
        $mailheaders .= "X-Mailer: PHP4\r\n";
        $mailheaders .= "X-Priority: 3\r\n";
        $maileaders  .= "Return-Path: MailServer@port.state.de.us\r\n";
        $maileaders  .= "This is a multi-part Contentin MIME format.\r\n";


        $Content="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
        $Content.="Content-Transfer-Encoding: quoted-printable\r\n";
        $Content.="\r\n";
        //$Content.=" Just sent you the attached file for review.\n";
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY\r\n";
        $Content.="Content-Type: application/pdf; name=\"Productivity Report.pdf\"\r\n";
        $Content.="Content-disposition: attachment\r\n";
        $Content.="Content-Transfer-Encoding: base64\r\n";
        $Content.="\r\n";
        $Content.=$File;
        $Content.="\r\n";
        $Content.="--MIME_BOUNDRY--\n";

//      mail($mailTo1, $mailsubject, $Content, $mailheaders);
//      mail($mailTo, $mailsubject, $Content, $mailheaders);
*/


?> 
