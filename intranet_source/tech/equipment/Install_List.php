<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $user_email = $userdata['user_email'];
  $title = "Equipment Tracking";
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

?>

<font size="5" face="Verdana" color="#0066CC">Equipment Tracking</font><br>
<hr>
<font size="4" face="Verdana" color="#0066CC">Install List</font><br>
<br>

<?
  $CurrentStep = 0 ;
  $CurrentSubStep = "a" ;

  //Check Record
  $sql = "SELECT * FROM EQUIP_INSTALL EI, EQUIP E WHERE EI.EQUIP_ID=E.ID AND EI.COMP_ID='" . $CompID . "'" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  if (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)) {
    $User = $row["ASSIGNED_TO"] ;

    //Record exists, check for update!!
    $CurrentStep = $row["STEP"] ;
    $CurrentSubStep = $row["SUBSTEP"] ;
    
    if (isset($StepID)) {
      $CurrentStep = $StepID ;
      $CurrentSubStep = $SubStepID ;
      //Update Record
      $sql = "UPDATE EQUIP_INSTALL SET STEP=" . $StepID . ", SUBSTEP='" . $SubStepID . "'" ;
      if ($CurrentStep == 4) $sql .= ",FINISH_DATE=TO_DATE('" . date("m/d/Y") ."','MM/DD/YYYY')" ;
      $sql .= " WHERE COMP_ID='" . $CompID . "'" ;
      $statement = ora_parse($cursor, $sql);
      ora_exec($cursor);
    }
  } else {
    //Get new Equipment ID
    $sql = "SELECT EQUIP_SEQ.nextval FROM DUAL" ;
    $statement = ora_parse($cursor, $sql);
    ora_exec($cursor);
    if (ora_fetch($cursor)) {
      $NewID = ora_getcolumn($cursor, 0) ;
    }

    //Create Record (Does not exist!!)
    $sql = "INSERT INTO EQUIP_INSTALL (COMP_ID,STEP,SUBSTEP,START_DATE,EQUIP_ID) VALUES ('" . $CompID . "',0,'a',TO_DATE('" . date("m/d/Y") ."','MM/DD/YYYY'),'" . $NewID . "')" ;
    $statement = ora_parse($cursor, $sql);
    ora_exec($cursor);


    $sql = "INSERT INTO EQUIP (ID,EQUIP_TYPE_ID,ASSIGNED_TO,STATUS) VALUES (" . $NewID . "," . $EquipID . ",'" . $User . "','Install')" ;
    $statement = ora_parse($cursor, $sql);
    ora_exec($cursor);
  }

?>

<b>Installing computer:</b> <? echo $CompID ; ?><br>
<b>Assigned To:</b> <? echo $User ; ?><br>

<center>
<table width=100%>
<?
  $sql = "SELECT * FROM EQUIP_INSTALL_TASK ORDER BY STEP_ID,SUBSTEP_ID" ;
  $statement = ora_parse($cursor, $sql);
  ora_exec($cursor);
  $Step = -1 ;
  $ShowDesc = false ;
  while (ora_fetch_into($cursor, $row, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC)){
    if ($ShowDesc) {
      echo " <tr>\n" ;
      echo "  <td></td>\n" ;
      echo "  <td>" ;
      echo "    <table cellpadding=1 bgcolor=#000000 width=100%><tr><td bgcolor=#FFFFEF>" ;
      echo $CurrentDesc ;
      echo "      <table align=right><tr><td><a href=\"Install_List.php?CompID=" . $CompID . "&StepID=" . $row["STEP_ID"] . "&SubStepID=" . $row["SUBSTEP_ID"] . "\">Done</a></td></tr></table>" ;
      echo "    </td></tr></table>\n" ;
      echo "  </td>\n" ;
      echo " </tr>\n" ;
      $ShowDesc = false ;
    }
    if ($row["STEP_ID"] != $Step) {
      echo "</table>\n" ;
      echo "<br>\n" ;
      echo "<table bgcolor=#99CCFF width=600>" ;
      echo "  <tr>\n" ;
      echo "  <td align=right width=8%><b>" . $row["STEP_ID"] . $row["SUBSTEP_ID"] . ".</b></td>\n" ;
      $Step = $row["STEP_ID"] ;
    } else {
      echo "  <tr>\n" ;
      echo "  <td align=right><b>" . $row["SUBSTEP_ID"] . ".</b></td>\n" ;
    }
    if ($row["STEP_ID"] == $CurrentStep and $row["SUBSTEP_ID"] == $CurrentSubStep) {
      $CurrentDesc = $row["DESCRIPTION"] ;
      $ShowDesc = true ;
    }
    echo "  <td><b>" . $row["LABEL"] . "</b></td>\n" ;
    echo " </tr>\n" ;
  }
  if ($CurrentStep == 4) {
    //Installation Completed
    echo " <tr>\n" ;
    echo "  <td></td>\n" ;
    echo "  <td>\n" ;
    echo "    <table cellpadding=1 bgcolor=#000000 width=100%><tr><td bgcolor=#FFFFEF>" ;
    echo $CurrentDesc ;
    echo "    </td></tr></table>\n" ;
    echo "  </td>\n" ;
    echo " </tr>\n" ;
  }
?>
</table>
<br>
<a href="Install.php">Install Menu</a>

<br>
<? include("pow_footer.php"); ?>

