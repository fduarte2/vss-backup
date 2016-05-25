 <?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Add Comments - Warehouse Box";
  $area_type = "SUPV";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from SUPV system");
    include("pow_footer.php");
    exit;
  }

  include("utility.php");

  $wngclear = $HTTP_GET_VARS[wing_clear];
  
  if ($wngclear <> "") {
    list($wng,$comment) = split(":",$wngclear);
    $comment = "";
  }


  $wingcomm = $HTTP_GET_VARS[wing_comm];

  if ($wng == "" and $comm == "") {
   list($wng,$comment) = split(":",$wingcomm);
  }
 
?>
<style>td{font:14px}</style>

<script language ="javascript">
  function changeWing()
  {
   var wing = document.add_form.wing.options[document.add_form.wing.selectedIndex].value;
   document.location = 'box_comments.php?wing_comm='+wing;
  }


function clear_comment()
{
  var wing = document.add_form.wing.options[document.add_form.wing.selectedIndex].value;
  document.location="box_comments.php?wing_clear="+wing
}

function validate()
{
  var wing = document.add_form.wing.value;
  var comments = document.add_form.comments.value;
  var len = comments.length;

    if ( wing == "")
    {
      alert("You must select the Warehouse");
      return false;
    }

  
   if (comments != "")
   {
      if (len > 100) {
       alert("Comments is too big enough to store into the system");
       document.add_form.comments.value = "";
       return false;
      }
   }

  return true;
}

</script>


<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <font size="4" face="Verdana" color="#0066CC">Add Comments - Warehouse Box</font>
         <hr>
      </td>
   </tr>
</table>
<form name="add_form" method="Post" action="box_comments_process.php" onsubmit = "return validate()">
<table align="center" width="95%" bgcolor="#f0f0f0" border="0" cellpadding="4" cellspacing="4">
<tr>
 <td colspan="4">&nbsp;</td>
</tr>
<tr>
 <td width="5%">&nbsp;</td>
 <td width="10%" align="left" valign="top">
<?
include("connect.php");

 $conn = ora_logon("LABOR@$lcs", "LABOR");
 $cursor = ora_open($conn);
 $cursor1 = ora_open($conn);


include("connect_data_warehouse.php");
$db = "data_warehouse";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }

  $sql = "select distinct whse from warehouse_location";
  $whs_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
  $whs_rows = pg_num_rows($whs_result);


  $stmt = "select * from warehouse_status order by warehouse_box";
  ora_parse($cursor, $stmt);

   if (!ora_exec($cursor))
   {
      printf("Select query failed - $stmt.Please report to TS");
      exit;
   }


 ?>

<b>Warehouse:</b></td>
<td width=50% align="left">
<select name="wing" onChange="changeWing()">
<option value = ""></option>
<?
for($i = 0; $i < $whs_rows; $i++){
    $row = pg_fetch_row($whs_result, $i);
    $whs = $row[0];

        $sql = "select box from warehouse_location where whse = '$whs' and box <>'Unknown' order by box";
        $box_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
        $box_rows = pg_num_rows($box_result);

        for($j = 0; $j < $box_rows; $j++){
           $row1 = pg_fetch_row($box_result, $j);
           $box = $row1[0];

           $wing = $whs. "-". $box;
       
           if ($wing ==$wng){
              $strSelected = "SELECTED";
           }else{
              $strSelected = "";
           }


         $stmt = "select comments from warehouse_status where warehouse_box = '$wing'";
         ora_parse($cursor1, $stmt);

         if (!ora_exec($cursor1))
         {
          printf("Select query failed - $stmt.Please report to TS");
          exit;
         }

           if (ora_fetch($cursor1))
           {
              $comm = ora_getcolumn($cursor1,0);
           }

          $wing_comm = $wing.":".$comm;
        
           printf("<option value = '$wing_comm' $strSelected>$wing</option>");
       // printf("<option value = '$wing'>$wing</option>"); 


        }
  }
?>
</select></td>
<td width=25%>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td  align="left" valign="top"><b>Comments:</b></td>
<td align="left"><input type="textbox" name="comments" size="80" maxlength="100" value ="<? echo $comment?>"></td>
<td>&nbsp;</td>
</tr>
<tr>
 <td>&nbsp;</td>
 <td align="center"  colspan=2>
 <input type="Submit" value="   Save ">
 
   &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="  Clear  " onclick="javascript:clear_comment();">
  </td>
  </form>
  <td></td>
</tr>
 </table>
<br/>

<!-- Warehouse Box Information -->

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
    <td colspan="4">&nbsp;</td>
   </tr>
   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top">
         <p align="left">
            <font size="3" face="Verdana" color="#0066CC">Comments:</font>
         </p>

     <table width="95%" border="1" cellpadding="0" cellspacing="0">
         <tr bgcolor = #4D76A5>
           <th width="5%" align="center"><b>WING</b></td>
           <th width="30%" align="center"><b>Comments</b></td>
          </tr>


<?
  while (ora_fetch_into($cursor, $whse_box, ORA_FETCHINTO_NULLS|ORA_FETCHINTO_ASSOC))
  {
             $whse = $whse_box['WAREHOUSE_BOX'];
             $comments = $whse_box['COMMENTS'];
                          
?>
            <tr>
               <td align="center"><? echo $whse ?></td>
               <td align="left"><? echo html($comments) ?></td>
            </tr>
<?
  }

?>
          </table>
      </td>
   </tr>
</table>
<br />


<? include("pow_footer.php"); ?>

