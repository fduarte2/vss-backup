<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Finance System - Closing Fiscal Year";
  $area_type = "FINA";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from Finance system");
    include("pow_footer.php");
    exit;
  }

?>

<script type="text/javascript">

   function validate_year(){
       x = document.close_form
       year = x.close_year.value
       var asless = false;
       if(year == ""){
        alert("You must enter the year to close!");
       }
       else
         asless = true; 
       return asless
   }
</script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Closing Fiscal year
                  </font>
                  <hr>
               </td>
            </tr>
         </table>
  <font size = "3" face = "Verdana" color = "#FF0000"><b>
               <?
                  include("../bni_links.php");
                  if ($msg != "")
                  {
                    printf("$msg</br>");
                  }
               ?>
 </b></font><br/>
<table width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="0">
            <tr>
               <td colspan="4" height="12"></td>
            </tr>
               <form name="close_form" action="closing_fiscal_year_process.php" method="Post"
                                              onsubmit="return validate_year()">
             <tr>
               <td width="50%" align="right" nowrap><font size="2" face="Verdana">Enter the Year to Close:(YYYY)</font></td>
               <td align="left" nowrap>
                   <input type="text" name="close_year" size="16" value="">
               </td>
            </tr>
            <tr>
               <td colspan="4" height="12"></td>
            </tr>
            <tr>
               <td colspan="4" align="center">
                   <table>
                      <tr>
                         <td width="30%" align="center">
                            <input type="Submit" value="Submit">
                         </td>
                         <td width="1%"></td>
                         <td width="30%" align="left">
                            <input type="Reset" value=" Reset ">
                         </td>
                         </form>
                      </tr>
                   </table>
                </td>
            </tr>
            <tr>
               <td colspan="5" height="14"></td>
            </tr>
         </table>
      </td>
   </tr>
</table>


<?
  include("pow_footer.php");
?>

