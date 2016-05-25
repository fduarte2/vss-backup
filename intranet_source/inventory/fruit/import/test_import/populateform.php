<hr>
<p><font size="2" face="Verdana"><b>Populate Database</b></font><br>

<form name="frmPopulate" method="POST" ENCTYPE="multipart/form-data" action="populate.php">
<input type="hidden" name="VesselName" value="<? echo $VesselName ; ?>">
<input type="hidden" name="Tran_Num" value="<? echo $Tran_Num ; ?>">
<table width="100%" align="center" bgcolor="#f0f0f0" border="0" cellpadding="4" callspacing="4">
 <tr><td><? echo $iRec ; ?> records in transaction</td></tr>
 <tr><td><input type="radio" name="CreateVessel" value="0"> Create new vessel</td></tr>
 <tr><td><input type="radio" name="CreateVessel" value="1"> Add to LR Number: <input type="text" name="LRNum"></td></tr>
 <tr><td><hr></td></tr>
 <tr><td><input type="radio" name="ImportMethod" value="0"> Ignore duplicate pallets</td></tr>
 <tr><td><input type="radio" name="ImportMethod" value="1"> Replace pallets</td></tr>
 <tr><td>&nbsp;</td></tr>
 <tr><td><input type="submit" value="Populate"> <input type="reset"></td></tr>
</form>


