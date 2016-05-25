<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Claims System - Reports";
  $area_type = "CLAI";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Claims system");
    include("pow_footer.php");
    exit;
  }
  $user = $userdata['username'];
?>

<style>td {font:12px}</style>
<script language="javascript">
function Tag_Audit(){
  pId= document.pallet_form.pId.value;
  season = document.pallet_form.season.value;
  if (pId == "")
  {
	alert("Enter Pallet Id first!");
	return false;
  }	
  document.location="tag_audit.php?pallet_id="+pId+"&season="+season;
}
function fruit_claim(){
  document.location = "index.php"; 
}

</script>

<form action="" method="POST" name="pallet_form">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Tag Audit
            </font>
            <hr>
         </p>
      </td>
   </tr>
</table>

<table  border="0" width="100%" cellpadding="4" cellspacing="0" bgcolor="#f0f0f0">
    <tr>
        <td colspan="3" align="center">&nbsp;
    </tr>
    <tr>
       <td align="right" valign="top" width=35%>
             <font size="2" face="Verdana">Pallet ID:</font></td>
       <td align="left" valign="middle" width=20%>
             <input type="textbox" name="pId" size="20" maxlength="30">
       </td>
       <td align="left" valign="middle" width=45%>
             <select name="season">
             <?
	       $current = date('Y', mktime(0, 0, 0, (date('m')+4), date('d'), date('Y')));
               for ($i = $current; $i >=2001; $i--)
               {
                 printf("<option value=\"$i\">$i</option>");
               }
             ?>
             </select>
       </td>
    </tr>
    <tr>
        <td colspan="3" align="center">
                <input type="button" value="  View  "  onClick="javascript:Tag_Audit()">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" value=" Cancel " onClick="javascript:fruit_claim()">
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">&nbsp;
    </tr>
</table>

<?
	include("pow_footer.php");
?>