<%@ page language="C#" autoeventwireup="true" inherits="Sign, App_Web_vnvmcz2t" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>Signature</title>
<script type="text/javascript" language="javascript">
<!--

function OnClear()
{
    document.FORM1.SigPlus1.ClearTablet(); //Clears the signature, in case of error or mistake
}

function OnSign()
{
    document.FORM1.SigPlus1.TabletState = 1; //Turns tablet on
}

function OnSave()
{
    if(document.FORM1.SigPlus1.NumberOfTabletPoints > 0)
    {
       document.FORM1.SigPlus1.TabletState = 0; //Turns tablet off
       document.FORM1.hiddenSigString.value = document.FORM1.SigPlus1.SigString;
       document.FORM1.submit();
    }
    else
    {
       alert("Please Sign Before Saving.");
       return false;
    }
}

function OnClose()
{
    document.FORM1.SigPlus1.TabletState = 0; //Turn tablet off
    history.back();
}

//-->
</script>
	</head>
	<body>
		<form id="FORM1" method="post" name="FORM1" action="ServerImage.aspx">
			 
            <h2><b><font color="blue">
            <img src="Images/ePortClementineSign.JPG" alt="Images/ePortClementineSign.JPG" /></font></b>&nbsp;</h2>
		    <table>
		        <tr>
		            <td style="width: 503px">
                        &nbsp;<asp:Label ID="lblVessel" runat="server" Font-Names="Arial" Font-Size="Smaller"
                            Text="Vessel" Width="497px"></asp:Label></td>
		        </tr>
		        <tr>
		            <td style="width: 503px">
                        &nbsp;<asp:Label ID="lblOrderNum" runat="server" Font-Names="Arial" Font-Size="Smaller"
                            Text="OrderNum" Width="496px"></asp:Label></td>
		        </tr>
		    </table>
			<table border="1" cellpadding="0">
				<tr>
					<td style="height: 180px; width: 500px;">
						<object id="SigPlus1" style="LEFT: 0px; WIDTH: 500px; TOP: 0px; HEIGHT: 180px" height="75"
							classid="clsid:69A40DA3-4D42-11D0-86B0-0000C025864A" name="SigPlus1" VIEWASTEXT="">
							<param name="_Version" value="131095" />
							<param name="_ExtentX" value="8467" />
							<param name="_ExtentY" value="4763" />
							<param name="_StockProps" value="9" />
						</object>
					</td>
				</tr>
				<tr>
				 </tr>
			</table>
		    <table>
		        <tr>
		            <td style="width: 43px"><input id="SignButton" onclick="OnSign()" type="button" value="Sign" name="SignButton" /></td><td></td>
		            <td><input id="ClearButton" onclick="OnClear()" type="button" value="Clear" name="ClearButton" /></td><td style="width: 36px"></td>
		            <td style="width: 35px"><input id="SaveButton" onclick="OnSave()" type="button" value="Save" name="SaveButton" /></td><td></td>
		            <td style="width: 251px"></td><td style="width: 22px"><input id="CloseButton" onclick="OnClose()" type="button" value="Close" name="CloseButton" /></td>
		        </tr>
		    </table>
            <input id="hiddenSigString" type="hidden" name="hiddenSigString" />
            <input id="hiddenVessel" type="hidden" name="hiddenVessel" runat="server" />
            <input id="hiddenOrderNum" type="hidden" name="hiddenOrderNum" runat="server" />
        </form>
	</body>
</html>
