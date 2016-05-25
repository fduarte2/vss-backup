<?
  // All POW files need this session file included
  include("pow_session.php");

   // Define some vars for the skeleton page
  $title = "Edit a Lot Id";
  $area_type = "TECH";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from TECH system");
    include("pow_footer.php");
    exit;
  }

 $lot_id = $HTTP_GET_VARS[lot_id];
 $arrival = $HTTP_GET_VARS[arrival];
 $mark = $HTTP_GET_VARS[mark];


?>

<script type="text/javascript">
function validate_mod()
   {
      x = document.mod_form

      lot_id  = x.lot_id.value
      arrival = x.arrival.value
      mark = x.mark.value
      receiver = x.receiver.value
      ccode = x.ccode.value
     
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


function retreive()
   {

     x = document.mod_form

     lot_id  = x.lot_id.value
     arrival = x.arrival.value
     mark = x.mark.value

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

     document.location="edit_lot.php?lot_id="+lot_id+"&arrival="+arrival+"&mark="+mark
   }

</script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
               <td width="1%">&nbsp;</td>
               <td>
                  <font size="5" face="Verdana" color="#0066CC">Edit a Lot ID
                  </font>
                  <hr>
               </td>
            </tr>
         </table>

<?
 include("connect.php");

 // Connect to Rf Database
   $conn_rf = ora_logon("SAG_OWNER@$rf", "OWNER");
   if($conn_rf < 1){

      printf("Error logging on to the RF Oracle Server: ");
      printf(ora_errorcode($conn_rf));
      printf("Please try later!");
      exit;
   }

  $cursor = ora_open($conn_rf);


 if ($lot_id <> "" and $arrival <> "" and $mark <> "")
 {

  $sql = "select * from ccd_cargo_tracking where lot_id = '$lot_id'
          and arrival_num = '$arrival' and mark = '$mark'";
  $stmt = ora_parse($cursor,$sql);

     if(!ora_exec($cursor))
     {
        printf("Error in query:$sql");
        ora_close($cursor);
        exit;
     }


     if(ora_fetch($cursor))
     {
       $receiver_id = ora_getcolumn($cursor,3);
       $comm_code = ora_getcolumn($cursor,4);
       $color = ora_getcolumn($cursor,5);
       $loc = ora_getcolumn($cursor,6);
       $qty_expect = ora_getcolumn($cursor,7);
       $qty_receive = ora_getcolumn($cursor,8);
       $qty_house = ora_getcolumn($cursor,9);
       $pallet_expect = ora_getcolumn($cursor,10);
       $pallet_receive = ora_getcolumn($cursor,11);
       $pallet_house = ora_getcolumn($cursor,12);
       $pow_num = ora_getcolumn($cursor,15);
     }

 }

?>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="1%">&nbsp;</td>
                <td valign="top">
                   <font size="3" face="Verdana">

   <a name="enter" />
   Type the lot id,arrival num,mark to retrieve a lot and then edit:<br />

   <form name="mod_form" action="lot_entry_process.php" method="Post" onsubmit="return validate_mod()">
     <input type="hidden" name="input" value="edit">
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
         <input type="textbox" name="lot_id" value="<? echo $lot_id ?>" size="5" maxlength="10">
         </td>
         <td width="5%">&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
           <font size="2" face="Verdana">Pow Num:</font></td>
         <td align="left">
            <input type="textbox" name="pow" value="<? echo $pow_num ?>" size="8" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Arrival Num:</font></td>
         <td align="left">
            <input type="textbox" name="arrival" value="<? echo $arrival ?>" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
<tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Mark:</font></td>
         <td align="left">
            <input type="textbox" name="mark" value="<? echo $mark ?>" size="12" maxlength="15">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Receiver Id:</font></td>
         <td align="left">
            <input type="textbox" name="receiver" value="<? echo $receiver_id ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size=1 color="red">*</font>
            <font size="2" face="Verdana">Commodity Code:</font></td>
         <td align="left">
            <input type="textbox" name="ccode" value="<? echo $comm_code ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Color:</font></td>
         <td align="left">
            <input type="textbox" name="color" value="<? echo $color ?>" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Cargo Location:</font></td>
         <td align="left">
            <input type="textbox" name="loc" value="<? echo $loc ?>" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Hatch:</font></td>
         <td align="left">
            <input type="textbox" name="hatch" size="10" maxlength="12">
         </td>
         <td>&nbsp;</td>
      </tr>
       <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty Expected:</font></td>
         <td align="left">
            <input type="textbox" name="qty_expect" value="<? echo $qty_expect ?>" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty Received:</font></td>
         <td align="left">
            <input type="textbox" name="qty_receive" value="<? echo $qty_receive ?>" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Qty In House:</font></td>
<td align="left">
             <input type="textbox" name="qty_house" value="<? echo $qty_house ?>" size="6" maxlength="10">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count Expected:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_expect" value="<? echo $pallet_expect ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count Received:</font></td>
         <td align="left">
            <input type="textbox" name="pallet_receive" value="<? echo $pallet_receive ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
     <tr>
         <td>&nbsp;</td>
         <td align="right" valign="top">
            <font size="2" face="Verdana">Pallet Count In House:</font></td>
<td align="left">
            <input type="textbox" name="pallet_house" value="<? echo $pallet_house ?>" size="4" maxlength="4">
         </td>
         <td>&nbsp;</td>
      </tr>
    <tr>
         <td>&nbsp;</td>
         <td colspan="2">
            <table>
               <tr>
                  <td width = "30%" align="middle">
                     <input type="button" value="Retrieve" onclick="javascript:retreive();">
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

