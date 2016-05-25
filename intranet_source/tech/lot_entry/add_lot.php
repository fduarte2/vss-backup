<?
  // All POW files need this session file included
  include("pow_session.php");

   // Define some vars for the skeleton page
  $title = "Add a Lot Id";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }

?>

<script type="text/javascript" src="/functions/calendar.js"></script>

<script type="text/javascript">
   function validate_mod()
   {
      x = document.mod_form

      lot_id  = x.lot_id.value
      arrival = x.arrival.value
      mark = x.mark.value
      receiver = x.receiver.value
      ccode = x.ccode.value
      input = x.input.value               
      
   
      if (lot_id == "") {
         alert("Please enter the Lot ID!")
         return false
      }

      if (arrival == "") {
         alert("Please enter the Arrival Number!")
         return false
      }

      if (mark == "") {
         alert("Please enter the Mark!")
         return false
      }

      if (receiver == "") {
         alert("Please enter the Receiver ID!")
         return false
      }

      if (ccode == "") {
         alert("Please enter the Commodity Code!")
         return false
      }

     
    return true;
   }

</script>

 
<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Add a Lot ID 
                  </font>
                  <hr>
               </td>
            </tr>
         </table>

 <table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="1%">&nbsp;</td>
                <td valign="top">
                   <font size="3" face="Verdana">

   <a name="enter" />
  Insert values shown here to add a lot:<br />

   <form name="mod_form" action="lot_entry_process.php" method="Post" onsubmit="return validate_mod()">
      <input type="hidden" name="input" value="add">
      <table width="99%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
         <td width="5%">&nbsp;</td>
         <td width="25%" align="right" valign="top">
           <font size=1 color="red">*</font> 
          <font size="2" face="Verdana">Lot Id:</font></td>
         <td width="65%" align="left">
         <input type="textbox" name="lot_id" size="5" maxlength="10">
         </td>
         <td width="5%">&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
           <font size="2" face="Verdana">Pow Num:</font></td>
         <td align="left">
            <input type="textbox" name="pow" size="8" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Arrival Num:</font></td>
         <td align="left">
            <input type="textbox" name="arrival" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Mark:</font></td>
         <td align="left">
            <input type="textbox" name="mark" size="12" maxlength="15">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Receiver Id:</font></td>
         <td align="left">
            <input type="textbox" name="receiver" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr> 
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Commodity Code:</font></td>
         <td align="left">
            <input type="textbox" name="ccode" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Color:</font></td>
         <td align="left">
            <input type="textbox" name="color" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Cargo Location:</font></td>
         <td align="left">
            <input type="textbox" name="loc" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Hatch:</font></td>
         <td align="left">
            <input type="textbox" name="hatch" size="12" maxlength="15">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty Expected:</font></td>
         <td align="left">
            <input type="textbox" name="qty_expect" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty Received:</font></td>
         <td align="left">
            <input type="textbox" name="qty_receive" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty In House:</font></td>
         <td align="left">
             <input type="textbox" name="qty_house" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count Expected:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_expect" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count Received:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_receive" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
     <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count In House:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_house" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
    <tr>
         <td>&nbsp;</td>
         <td colspan="2">
            <table>
               <tr>
                  <td width = "30%" align="right">
                     <input type="Submit" value="Save">&nbsp;&nbsp;
                     <input type="Reset" value="Reset">
                  </td>
               </tr>
            </table>
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td colspan="4">&nbsp;</td>
      </tr>
   </table>
</form>


<?
   include("pow_footer.php");
?>

