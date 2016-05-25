<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Scanner Assignment";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

  $conn = ora_logon("SAG_OWNER@RF", "OWNER");
  if($conn < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor = ora_open($conn);

  $LinesPerPage = 30 ;
  if (isset($txtDate)) {
    $today = date("m/d/Y", strtotime($txtDate)) ;
  } else {
    $today = date("m/d/Y") ;
  }
  $Start = "TO_DATE('" . date("m", strtotime($today)) . "/" . date("d", strtotime($today)) . "/" . date("Y", strtotime($today)) . " 12:00:00 AM','MM/DD/YYYY HH:MI:SS AM')" ;
  $NextDay = mktime(0, 0, 0, date("m", strtotime($today)), date("d", strtotime($today))+1, date("Y", strtotime($today))) ;
  $Finish = "TO_DATE('" . date("m", $NextDay) . "/" . date("d", $NextDay) . "/" . date("Y", $NextDay) . " 12:00:00 AM','MM/DD/YYYY HH:MI:SS AM')" ;

?>

<font size="5" face="Verdana" color="#0066CC">Scanner Inventory</font><br>
<hr>
<br>

<center>

<script language="JavaScript" src="/functions/calendar.js"></script>
<form name="frmGoTo" action="">
<table border=0 width=100%>
 <tr>
  <td>Date <input type="text" name="txtDate" value="<? echo date("m/d/Y", strtotime($today)) ; ?>">
      <a href="javascript:show_calendar('frmGoTo.txtDate');" onmouseover="window.status='Date Picker';return true;"
      onmouseout="window.status='';return true;"><img src="/tech/images/Calendar.gif" width=24 height=22 border=0></a>
      <a href="javascript: document.frmGoTo.submit() ;"><img src="/tech/images/Go.gif" border=0></a></td>
  <td align=right>
      <a href="http://dspc-s16/tech/scanners/"><img src="/tech/images/Refresh.gif" border=0></a>
      <a href="Inventory.php"><img src="/tech/images/Inventory.gif" border=0></a>
      </td>
 </tr>
</table>
</form>

<!-- Start Pagination Table -->
<table width=100%>
<tr valign=top><td>

<table border=0 width=100% bgcolor=#99CCFF>
 <tr><td bgcolor=#66AAEE align=center colspan=4><font size=+1><b>Logins on <? echo date("M d,Y", strtotime($today)) ; ?></b></font></td></tr>
 <tr>
  <td><b>Time</b></td>
  <td><b>Unit</b></td>
  <td><b>Login</b></td>
  <td><b>Location</b></td>
 </tr>
 <tr><td colspan=4 bgcolor=#000000></td></tr>
<?php
   $sql = "SELECT TO_CHAR(SCAN_DATE, 'MM/DD/YYYY HH:MI:SS AM') AS DATETIME,s.* FROM SCAN_LOGIN s WHERE SCAN_DATE>=" . $Start . " AND SCAN_DATE<" . $Finish . " ORDER BY SCAN_DATE" ;
   $statement = ora_parse($cursor, $sql);
   ora_exec($cursor);
   if (!isset($Offset)) $Offset = 0 ;
   $iCount = 0 ;
   while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
     if ($iCount >= $Offset AND $iCount < $Offset + $LinesPerPage) {
       echo " <tr>\n" ;
       echo "  <td>" . date("h:i:s", strtotime($row["DATETIME"])) . "</td>\n" ;
       echo "  <td>" . $row["UNIT_ID"] . "</td>\n" ;
       echo "  <td>" . $row["LOGIN"] . "</td>\n" ;
       echo "  <td>" . $row["LOCATION"] . "</td>\n" ;
       echo " </tr>\n" ;
     }
     $iCount++ ;
   }
   $iMax = intval(($iCount - 1) / $LinesPerPage) + 1 ;
   echo "<tr><td colspan=4 bgcolor=#000000></td></tr>\n" ;
   echo "<tr><td colspan=4 align=right><b>Total: " . $iCount . "</td></tr>\n" ;
   echo "</table>" ;

   echo "Viewing page " . ($Offset/$LinesPerPage+1) . " of " . ($iMax) . " [ " ;
   $iCount = 0 ;
   while ($iCount < $iMax) {
     if ($iCount*$LinesPerPage == $Offset) {
       echo ($iCount+1) ;
     } else {
       echo " <a href=\"index.php?txtDate=" . date("m/d/Y", strtotime($today)) . "&Offset=" . $iCount*$LinesPerPage . "\">" . ($iCount+1) . "</a> " ;
     }
     $iCount++ ;
   }
   echo " ] " ;
?>

</td><td align=right>

<table border=0 bgcolor=#99CCFF width=100%>
 <tr><td bgcolor=#66AAEE colspan=3 align=center><font size=+1>Summary by Supervisor</font></td></tr>
 <tr>
  <td><b>Supervisor</b></td>
  <td>&nbsp;</td>
  <td align=right><b>Units</b></td>
 </tr>
 <tr><td colspan=3 bgcolor=#000000></td></tr>
<?php
  $sql = "SELECT DISTINCT s.Name, l.UNIT_ID, l.LOGIN FROM SCAN_LOGIN l, SCAN_SUPER s WHERE s.ID=l.LOGIN AND SCAN_DATE>=" . $Start . " AND SCAN_DATE<" . $Finish . " ORDER BY LOGIN" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $iTotal = 0 ;
  $Super = "" ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($row["LOGIN"] != $Super) {
      if ($iTotal > 0) {
        echo "<td>&nbsp;</td>\n" ;
        echo "<td align=right>" . $iSubTotal . "</td>\n" ;
        echo " </tr>\n" ;
      }
      echo " <tr>\n" ;
      echo "  <td>" . $row["NAME"] . "</td>\n" ;
      $iSubTotal = 1 ;
      $Super = $row["LOGIN"] ;
    } else {
      $iSubTotal++ ;
    }
    $iTotal++ ;
  }
  echo "<td>&nbsp;</td>\n" ;
  echo "<td align=right>" . $iSubTotal . "</td></tr>\n" ;
  echo "<tr><td colspan=3 bgcolor=#000000></td></tr>" ;
  echo "<tr><td colspan=2><b>Total</td><td align=right><b>" . $iTotal . "</td></tr>\n" ;
?>
</table>

<br>

<table border=0 bgcolor=#99CCFF width=100%>
 <tr><td bgcolor=#66AAEE colspan=3 align=center><font size=+1>Summary by Location</font></td></tr>
 <tr>
  <td><b>Location</b></td>
  <td>&nbsp;</td>
  <td align=right><b>Units</b></td>
 </tr>
 <tr><td colspan=3 bgcolor=#000000></td></tr>
<?php
  $sql = "SELECT DISTINCT UNIT_ID, LOCATION FROM SCAN_LOGIN WHERE SCAN_DATE>=" . $Start . " AND SCAN_DATE<" . $Finish . " AND LOGIN='TEST' ORDER BY LOCATION" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $iTotal = 0 ;
  $iSubTotal = 0 ;
  $Location = "" ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($row["LOCATION"] != $Location) {
      if ($iTotal > 0) {
        echo "<td>&nbsp;</td>\n" ;
        echo "<td align=right>" . $iSubTotal . "</td>\n" ;
        echo " </tr>\n" ;
      }
      echo " <tr>\n" ;
      if ($row["LOCATION"] == "T") {
        echo "  <td>Data Center</td>\n" ;
      } else {
        echo "  <td>Wing " . $row["LOCATION"] . "</td>\n" ;
      }
      $iSubTotal = 1 ;
      $Location = $row["LOCATION"] ;
    } else {
      $iSubTotal++ ;
    }
    $iTotal++ ;
  }
  if ($iSubTotal > 0) {
    echo "<td>&nbsp;</td>\n" ;
    echo "<td align=right>" . $iSubTotal . "</td></tr>\n" ;
  }
  echo "<tr><td colspan=3 bgcolor=#000000></td></tr>" ;
  echo "<tr><td colspan=2><b>Total</td><td align=right><b>" . $iTotal . "</td></tr>\n" ;
?>
</table>

<br>

<table border=0 bgcolor=#99CCFF width=100%>
 <tr><td bgcolor=#66AAEE colspan=3 align=center><font size=+1>Summary by Type</font></td></tr>
 <tr>
  <td><b>Type</b></td>
  <td>&nbsp;</td>
  <td align=right><b>Units</b></td>
 </tr>
 <tr><td colspan=3 bgcolor=#000000></td></tr>
<?php
  $sql = "SELECT DISTINCT l.UNIT_ID,u.PROGRAM FROM SCAN_LOGIN l, SCAN_UNIT u WHERE u.ID=l.UNIT_ID AND SCAN_DATE>=" . $Start . " AND SCAN_DATE<" . $Finish . " AND l.LOGIN='TEST' ORDER BY PROGRAM" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $iTotal = 0 ;
  $iSubTotal = 0 ;
  $Program = "" ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($row["PROGRAM"] != $Program) {
      if ($iTotal > 0) {
        echo "<td>&nbsp;</td>\n" ;
        echo "<td align=right>" . $iSubTotal . "</td>\n" ;
        echo " </tr>\n" ;
      }
      echo " <tr>\n" ;
      echo "  <td>" . $row["PROGRAM"] . "</td>\n" ;
      $iSubTotal = 1 ;
      $Program = $row["PROGRAM"] ;
    } else {
      $iSubTotal++ ;
    }
    $iTotal++ ;
  }
  if ($iSubTotal > 0) {
    echo "<td>&nbsp;</td>\n" ;
    echo "<td align=right>" . $iSubTotal . "</td></tr>\n" ;
  }
  echo "<tr><td colspan=3 bgcolor=#000000></td></tr>" ;
  echo "<tr><td colspan=2><b>Total</td><td align=right><b>" . $iTotal . "</td></tr>\n" ;
?>
</table>

<!-- End Pagination Table -->
</td></tr>
</table>

<br>
<? include("pow_footer.php"); ?>

