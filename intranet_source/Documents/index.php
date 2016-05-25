<?
  // All POW files need this session file included
  include("pow_session.php");

  // Define some vars for the skeleton page
  $title = "Applications Access";
  $area_type = "ROOT";

  // Provides header / leftnav
  include("pow_header.php");
?>
<script language="JavaScript">
function PhoneBook()
{
  var now = new Date();
  document.location = "EmployeeDir.xls?time="+now;
}
</script>

<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
	 <p align="left">
	    <font size="5" face="Verdana" color="#0066CC">Documents</font>
	    <hr>
	 </p>
      </td>
   </tr>
</table>

<table border="0" width="100%" cellpadding="4" cellspacing="0"> 
   <tr>
      <td>&nbsp;</td>
      <td valign="top" width="88%">
         <p align="left">
         <font size="2" face="Verdana"><a href="/documents/manuals/">Copier Manuals</a><br /><br />
         <font size="2" face="Verdana"><a href="/hr/security_gate/">Visitor Request</a><br /><br />
         <font size="2" face="Verdana"><a href="/ship_schedule/">DSPC Ship Schedule</a><br /><br />
         <font size="2" face="Verdana"><a href="javascript:PhoneBook()">DSPC Phone Book</a><br /><br />
<!--
         <font size="2" face="Verdana"><a href="ComputerSurvey.doc">Computer User Survey</a><br /><br />
-->
         <font size="2" face="Verdana"><a href="http://www.superpages.com/">Wilmington Phone Book</a><br /><br />
<!--         
	<font size="2" face="Verdana"><a href="CellRequestForm.doc">Cellular Phone Request Form</a><br />
-->
<!--         <font size="2" face="Verdana"><a href="/documents/XEROX/training.html">Second Floor Copier Manual</a><br /><br />
-->
         <font face="Verdana" size="2" color="#000080"><a href="REQUEST FOR LEAVE.xls">Request For Leave</a></font><br /><br />
        <font face="Verdana" size="2" color="#000080"><a href="1expense claim form.xls">Expense Claim</a></font><br /><br />
         <font face="Verdana" size="2" color="#000080"><a href="Request for check.doc">Request for Check</a></font><br /><br />
         <font size="2" face="Verdana"><a href="CellRequestForm.doc">Cell Phone & Repair Request</a><br /><br />
         <font size="2" face="Verdana"><a href="ComputerSurvey.doc">Computer Training Request</a><br /><br />
         <font size="2" face="Verdana"><a href="NewEmployee.pdf">New Employee - Computer Account Request</a><br /><br />
         <font size="2" face="Verdana"><a href="POW logo in black.jpg">PoW Logo (black)</a><br /><br />
         <font size="2" face="Verdana"><a href="Port logo small.jpg">PoW Logo (color)</a><br /><br />

	 <p align="left">
	    <font size="2" face="Verdana"><b>Safety/Security/HR Procedures</font>
     	 </p>
 
    	<font size="2" face="Verdana"><a href="DSPC Drug Policy.doc">DSPC Drug Policy</a><br /><br />

 <table border="0" width="100%" cellpadding="2" cellspacing="0">

	<tr>
		<td width="2%">&nbsp;</td>
		<td align="left"><a href="Reasonable Suspicion Checklist.doc"><font size="2" face="Verdana">Reasonable Suspicion Checklist</font></a></td>
	</tr>
 </table>

	<br /><font size="2" face="Verdana"><a href="Escorting Procedure.doc">Escorting Procedure</a><br /><br />

<table border="0" width="100%" cellpadding="2" cellspacing="0">

	<tr>
		<td width="2%">&nbsp;</td>
		<td align="left"><a href="Escorting Compliance Form.docx"><font size="2" face="Verdana">Escorting Compliance Form</font></a></td>
	</tr>
</table>
<!--
	<font size="2" face="Verdana"><a href="2010 ILA Agreement.tif">2010 ILA Agreement</a><br /><br />
-->

 
	 <p align="left">
	    <font size="2" face="Verdana"><b>Phone System Documentation</font>
     </p>

    	<font size="2" face="Verdana"><a href="Mitel Unified Messaging User Guide.pdf">Voicemail Manual</a><br /><br />
	<font size="2" face="Verdana"><a href="Mitel Unified Msg Quick Reference.pdf">Voicemail Quick Reference</a><br /><br />
	<font size="2" face="Verdana"><a href="5224 IP Phone Quick Reference (use Page 2).pdf">Phone Quick Reference</a><br /><br />
	<font size="2" face="Verdana"><a href="5224 IP Phone User Manual.pdf">Phone Manual</a><br /><br />

	 <p align="left">
	    <font size="2" face="Verdana"><b>Accounting Policies & Procedures:</font>
         </p>
	 <table border="0" width="100%" cellpadding="2" cellspacing="0">
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <font face="Verdana" size="2" color="#000080"><a href="cover page.doc">Cover Page</a></font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle" width="%10"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left" width="90%">
		  <font face="Verdana" size="2" color="#000080"><a href="Contents.doc">Contents</a></font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="01 Introduction.doc">01 Introduction</a></font>
	       </td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="02 Internal control.doc">02 Internal Control</a></font>
	       </td>
	    </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="03 Basis of Accounting.doc">03 Basis of Accounting</a></font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="04 chart of Accounts.doc">04 Chart of Accounts</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="05 AP.doc">05 Accounts Payable</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="06 BillingRevised.doc">06 Billing</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="07 ARRevised.doc">07 Collections and Accounts Receivable</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="08 Fixed Assets.doc">08 Fixed Assets Recording</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="09 Reconciliations.doc">09 Monthly Reconciliations</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="10 Records Retention.doc">10 Records Retention</a></font>
	       </td>
            </tr>
            <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="11 Travel.doc">11 Travel Policy</a></font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="12 Credit Card WTC.doc">12 Management Credit Card Program</a></font>
	       </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="1expense claim form.xls">Expense Claim Form</a></font>
	       </td>
            </tr>
            <tr>
               <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
               <td valign="middle" align="left">
                  <font face="Verdana" size="2" color="#000080"><a href="Request for check.doc">Request for check Form</a></font>
               </td>
            </tr>
	    <tr>
	       <td valign="middle"><img src="images/yellowbulletsmall.gif"></td>
	       <td valign="middle" align="left">
		  <font face="Verdana" size="2" color="#000080"><a href="Del. of Auth. Matrix.xls">Delegation of Authority Matrix</a></font>
	       </td>
            </tr>
	 </table>
      </td>
   </tr>
</table>

<? include("pow_footer.php"); ?>
