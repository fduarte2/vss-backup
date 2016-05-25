<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $user = $userdata['username'];
  $title = "Director - HIRE PLAN REVIEW";
  $area_type = "LCS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from LCS system");
    include("pow_footer.php");
    exit;
  }

   $today = date('m/d/Y');
   $dayOfWeek = date('w'); 
   if($dayOfWeek == 5){
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 3 ,date("Y")));
   }else{
        $tomorrow = date('m/d/Y',mktime(0,0,0,date("m"),date("d") + 1 ,date("Y")));
   }



?>

<script language="JavaScript" src="/functions/calendar.js"></script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Hire Plan Review
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
            <form action="print_review.php" method="post" name="prod">
              Date: <input type="textbox" name="vDate" size=15 value="<? echo $tomorrow; ?>"><a href="javascript:show_calendar('prod.vDate');" onmouseover="window.status='Date Picker';return true;" onmouseout="window.status='';return true;"><img src="/images/show-calendar.gif" width=24 height=22 border=0></a><br />
<!--
              <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Factor: <input type="textbox" name="factor" value="29" size="10"><br /><br />
-->
	      <br /><br />
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit">&nbsp;&nbsp;<input type="reset" value="Reset">
            </form>
         </p>
      </td>
      <td valign="middle" width="30%">
        <p><img border="0" src="../images/warehouse_e.jpg" width="218" height="170"></p>
      </td>
   </tr>
   <tr>
      <td colspan="3">&nbsp;</td>     
      </td>
   </tr>
</table>
<br />

<? include("pow_footer.php"); ?>

