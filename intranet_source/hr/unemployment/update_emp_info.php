<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Unemployment";
  $area_type = "HRMS";

  // Provides header / leftnav
  include("pow_header.php");
  if($access_denied){
    printf("Access Denied from HRMS system");
    include("pow_footer.php");
    exit;
  }

  //get DB connection
  include("connect.php");
  $conn = ora_logon("LABOR@$lcs", "LABOR");
  //$conn = ora_logon("LABOR@BNI_BACKUP", "LABOR_DEV");
  if($conn < 1){
        printf("Error logging on to the LCS Oracle Server: ");
        printf(ora_errorcode($conn));
        printf("Please try later!");
        exit;
  }
  $cursor = ora_open($conn);

  $today = date('m/d/Y');
?>

<style>td{font:12px}</style>
<script language="JavaScript" src="/functions/calendar.js"></script>

<form action="update_emp_info_process.php" enctype="multipart/form-data" method="post" name="upload">

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Update Employee Information By Uploading An Input File
            </font>
            <hr>
         </p>

      </td>
   </tr>
   
      <tr>
         <td width="1%">&nbsp;</td>
         <td>
            <p align="left">
               <font size="2" face="Verdana" color="#000000">
               <b>Please note that input file has to follow certain format.</b><br>
		<b>Headers must appear at the 1st line in the input file.&nbsp&nbsp</b><br>
		<b><a href="emp_info_input_sample.php">-> An Input File Example <-</a></b><br>
               </font>
        	</p>
         </td>
   </tr>

</table>
<br><br>
<table width="100%" align="center"  border="0" cellpadding="4" cellspacing="0">

   <tr>
      <td width="1%">&nbsp;</td>
      <td valign="top" width="70%">
          <table width="100%" align="center" border="0" cellpadding="4" cellspacing="0">

             <tr>
	        <td width="5%" height="8"></td>
	        <td width="20%" height="8"></td>
	        <td width="70%" height="8"></td>
	        <td width="5%" height="8"></td>
             </tr>
             <tr>
                <td>&nbsp</td>
                <td align="left" valign="top"><font size="2" face="Verdana">File Name:</font></td>
                <td align="left"><INPUT TYPE="FILE" NAME="file1" SIZE="30"></td>
                <td>&nbsp;</td>
             </tr>

             <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="Submit" value="Process"> <input type="Reset" value="  Reset  "></td>
              <td>&nbsp;&nbsp;</td>
             </tr>
             <tr>
                <td colspan="4" height="8">&nbsp;</td>
             </tr>
 	</table>
              <td>&nbsp</td>
             </tr>

          </table>
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
</form>

