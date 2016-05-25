<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Productivity";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  $web_user = $userdata['username'];
  $mail = $userdata['user_email'];

  $date = $HTTP_GET_VARS['date'];
  if ($date == "")
        $date = date("m/d/Y");

  $conn_lcs = ora_logon("LABOR@LCS", "LABOR");
  if($conn_lcs < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_lcs = ora_open($conn_lcs);

  $conn_bni = ora_logon("SAG_OWNER@BNI", "SAG");
  if($conn_bni < 1){
    printf("Error logging on to the Oracle Server: ");
    printf(ora_errorcode($conn));
    printf("</body></html>");
    exit;
  }
  $cursor_bni = ora_open($conn_bni);

  //get user id
  $sql = "select user_id from lcs_user where email_address = '$mail'";
  $statement = ora_parse($cursor_lcs, $sql);
  ora_exec($cursor_lcs);

  if (ora_fetch($cursor_lcs)){
        $user_id = ora_getcolumn($cursor_lcs, 0);
  }else{
        $msg = "Invalid user!";
        echo $msg;
        exit;
  }

?>
<script language="JavaScript" src="/functions/calendar.js"></script>
<form action="print.php" method="post" name="prod" target=_>

<?
  printf("<input type=\"hidden\" name=\"user\" value=\"%s\">", $web_user);
  printf("<input type=\"hidden\" name=\"mail\" value=\"%s\">", $mail);
  printf("<input type=\"hidden\" name=\"user_id\" value=\"%s\">", $user_id);
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Activity Report
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
         <p>
            <font size="2" face="Verdana">Please select start date and end date.<br /><br /><br />

         </p>
         <p>
               Start Date: <input type="textbox" name="sDate" size=10 value="<? echo $yesterday; ?>"><a href="javascript:show_calendar('prod.sDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a><br />

              <br /> End Date:&nbsp;&nbsp;&nbsp;<input type="textbox" name="eDate" size=10 value="<? echo $today; ?>"><a href="javascript:show_calendar('prod.eDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a><br />

              <br \><br \><br \>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">

            </form>
         </p>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>

