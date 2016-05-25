<HTML xmlns:eba>
<!-- Note the namespace defined in the HTML tag. -->
<!-- This page is the main page in the sample. It places the grid on the webpage and sets up its datasource and save handler. -->

<head>
<title>Clementine Locations</title>

<!-- Include the code and stylesheet for the grid control. -->
<script language="JScript.Encode" src="/functions/Grid_2.7/bin/ebagrid.js"></script>
<link type="text/css" rel="stylesheet" href="/functions/Grid_2.7/bin/styles/officexp/eba.css">

<!-- used as an error feedback placeholder -->
<span id="errorSpan"></span>

<script language="Javascript">

// Definition of the errorhandler for the Grid. In case of an error the function would print out the error message to the errorSpan.
function errorHandler() {
	errorSpan.innerHTML=groupsGrid.object.lastError;
}
// Used when the Save button is pressed. Saves the dirty records back to the datasource.
function saveData() {
	groupsGrid.object.save();
	customersGrid.object.save();
}
function OnBeforeSave() {
	saveSpan.innerText="saving data from grid...";
}
function OnAfterSave() {
	// alert(myGrid.object.lastSaveHandlerResponse);  // You can use this code to debug the response of the savehandler.
	saveSpan.innerText="saving data from grid...ok!";
	// clear the saveSpan text after 3 seconds.
	setTimeout("saveSpan.innerText=''",3000);
}
function generateKey() {
	// get the latest key from the server
	return Number(groupsGrid.object.getResponseFromURL("getkey.php"));
}

function showdetails() {
  var GroupsGrid = groupsGrid.object;
  var GroupID = GroupsGrid.getCellValue(GroupsGrid.getRow(),1);
  var CustomersGrid = customersGrid.object;

  CustomersGrid.gethandler = "gethandler2.php?group_id=" + GroupID;
  CustomersGrid.GetPage(0);
}

</script>
</head>
<!-- The body of the webpage. Note the call to 'InitEBAGrids()' that initialises any grids on the page when it loads. -->


<body onload="InitEBAGrids()">
<table border="0" width="100%" cellpadding="4" cellspacing="0">
   <tr>
      <td width="1%">&nbsp;</td>
      <td>
         <p align="left">
            <font size="5" face="Verdana" color="#0066CC">Clementine Locations</font>
            <hr>
         </p>
      </td>
   </tr>
</table>
<table align=center >
    <tr>
	<td>
	    <eba:gridlist id="groupsGrid" getHandler="gethandler1.php"  height="400" width="425" cellHeight="16" rowhighlight="Y" showNav="N" onbeforesave="OnBeforeSave()" onaftersave="OnAfterSave()" onError="errorHandler()" onGenerateRecordKey="generateKey()">
	    <eba:ColumnDefinition type="TEXTAREA" label="GROUP ID" width="90" xdatafld="GROUP_ID" oncellclick="showdetails()"/>
            <eba:ColumnDefinition type="TEXTAREA" label="DESCRIPTION"  width="275"  xdatafld="DESCRIPTION" oncellclick="showdetails()" />
	    </eba:gridlist>
	</td>
    </tr>
    <tr>
        <td>
            <eba:gridlist id="customersGrid" gethandler="gethandler2.php" height="400" width="425" cellHeight="16" showNav="Y" onbeforesave="OnBeforeSave()" onaftersave="OnAfterSave()" onError="errorHandler()" onGenerateRecordKey="generateKey()">
            <eba:ColumnDefinition type="TEXTAREA" label="GROUP_ID" width="90" xdatafld="GROUP_ID" />
            <eba:ColumnDefinition type="TEXTAREA" label="CUSTOMER_ID" width="275" xdatafld="CUSTOMER_ID" />
            </eba:gridlist>
        </td>
    </tr>


</table>
<center>
<!--
	<font size = 2>
	<i>Press the INSERT key to create rows. <BR>Press the DELETE key to delete rows.</i><br><br>
	</font>
-->
	<!-- Insert a save button. Note the call to saveData() when the button is clicked. -->
	<br>
	<button type="submit" onClick="saveData()" id="Button1"> <b>Save</b> </button>
	<br>
	<!-- used as a save-feedback placeholder -->
	<span id="saveSpan" style="font-family: Verdana; font-size: 10px;"></span> <br>
</center>
