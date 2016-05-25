<HTML xmlns:eba>
<!-- Note the namespace defined in the HTML tag. -->
<!-- This page is the main page in the sample. It places the grid on the webpage and sets up its datasource and save handler. -->

<head>
<title>Commodity List For Temperature Change Request</title>

<!-- Include the code and stylesheet for the grid control. -->
<script language="JScript.Encode" src="/functions/Grid_2.6/ebagrid.js"></script>
<link type="text/css" rel="stylesheet" href="/functions/Grid_2.6/styles/officexp/eba.css">

<!-- used as an error feedback placeholder -->
<span id="errorSpan"></span>

<script language="Javascript">

// Definition of the errorhandler for the Grid. In case of an error the function would print out the error message to the errorSpan.
function errorHandler() {
	errorSpan.innerHTML=myGrid.object.lastError;
}
// Used when the Save button is pressed. Saves the dirty records back to the datasource.
function saveData() {
	myGrid.object.save();
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
	return Number(myGrid.object.getResponseFromURL("getkey.php"));
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
            <font size="5" face="Verdana" color="#0066CC">Commodity List For Temperature Change Request</font>
            <hr>
         </p>
      </td>
   </tr>
</table>
<table align=center >
    <tr>
	<td> <!-- Insert the grid. Note the id of the grid 'myGrid' which is used to reference the control. -->
	    <eba:gridlist id="myGrid" getHandler="gethandler.php" saveHandler="savehandler.php" height="400" width="725" cellHeight="16" showNav="Y" onbeforesave="OnBeforeSave()" onaftersave="OnAfterSave()" onError="errorHandler()" onGenerateRecordKey="generateKey()">
		<eba:ColumnDefinition type="NUMBER" label="NO" width="40" xdatafld="ID" mask="###" />
		<eba:ColumnDefinition type="TEXT" label="PRODUCT" width="120" xdatafld="PRODUCT" />
		<eba:columndefinition type="NUMBER" label="LOW TEMP"  width="90"  xdatafld="LOW_TEMP" mask="##0.0" />
                <eba:columndefinition type="NUMBER" label="HIGH TEMP"  width="90"  xdatafld="HIGH_TEMP" mask="##0.0" />
                <eba:columndefinition type="NUMBER" label="LOW ALERT"  width="90"  xdatafld="LOW_ALERT" mask="##0.0" />
                <eba:columndefinition type="NUMBER" label="HIGH ALERT"  width="90"  xdatafld="HIGH_ALERT" mask="##0.0" />
                <eba:columndefinition type="NUMBER" label="LOW WARNING" width="100" xdatafld="LOW_WARNING" mask="##0.0" />
                <eba:columndefinition type="NUMBER" label="HIGH WARNING" width="100" xdatafld="HIGH_WARNING" mask="##0.0" />
         
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
