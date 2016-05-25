<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="PrintDC_OrderPage.aspx.cs" Inherits="ePortDC.UI.PrintDC_OrderPage" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.PrintDC_OrderPage" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head id="Head1" runat="server">
	<title>PrintDC_OrderPage</title>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="../Styles/Style.css"/>
	</head>
	<body id="Body1" runat="server" class="pBack">
	<form id="Form1" method="post" runat="server">
		<BaseClasses:ScrollCoordinates id="ScrollCoordinates" runat="server"></BaseClasses:ScrollCoordinates>
		<BaseClasses:BasePageSettings id="PageSettings" runat="server" LoginRequired="NOT_ANONYMOUS" ></BaseClasses:BasePageSettings>
		<script language="JavaScript" type="text/javascript">clearRTL()</script>
		<asp:ScriptManager ID="scriptManager1" runat="server" EnablePartialRendering="True" EnablePageMethods="True" />
		
		<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
		<tr>
		<td class="pAlign">
		<table cellspacing="0" cellpadding="0" border="0" class="pbTable">
			<tr>
			<td class="pbTL"><img src="../Images/space.gif" alt=""/></td>
			<td class="pbT"><img src="../Images/space.gif" alt=""/></td>
			<td class="pbTR"><img src="../Images/space.gif" alt=""/></td>
			</tr>
			<tr>
			<td class="pbL"><img src="../Images/space.gif" alt=""/></td>
			<td class="pbC">
			<table cellspacing="0" cellpadding="0" border="0" class="pcTable">
			<tr>
				<td class="pcT" colspan="2">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
				<td>
					<asp:HyperLink runat="server" id="SkipNavigationLinks" CssClass="skipNavigationLinks" NavigateURL="#StartOfPageContent" Text="&lt;%# GetResourceValue(&quot;Txt:SkipNavigation&quot;, &quot;ePortDC&quot;) %>" ToolTip="&lt;%# GetResourceValue(&quot;Txt:SkipNavigation&quot;, &quot;ePortDC&quot;) %>">

		</asp:HyperLink>
				</td>
				</tr>
				</table>
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
				<td>
					<ePortDC:Header runat="server" id="PageHeader">
		</ePortDC:Header>
				</td>
				</tr>
				</table>
				</td>
				<td class="pcTR"></td>
			</tr>
			<tr>
				<td class="pcL">
					
				</td>
				<td class="pcC">			
					<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%">
					<tr>
					<td>
						<ePortDC:Menu runat="server" id="Menu">
		</ePortDC:Menu>
					</td>
					</tr>
					<tr>
					<td>
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
						<td class="pContent">
							<a name="StartOfPageContent"></a>
							
                
                        <asp:UpdateProgress runat="server" id="DC_OrderRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_OrderRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_OrderRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_OrderRecordControl runat="server" id="DC_OrderRecordControl">
														
<!-- Begin Record Panel.html -->

<table class="dialog_view" cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dh">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <td class="dhel"><img src="../Images/space.gif" alt=""/></td>
    <td class="dhb">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_OrderDialogTitle" Text="Print Order">
														</asp:Literal></td>
    </tr>
    </table>
    </td>	
    <td class="dher"><img src="../Images/space.gif" alt=""/></td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
    <td>
    <table id="CollapsibleRegion" style="display:block;" cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
    <td class="dialog_body">
    <table cellpadding="0" cellspacing="3" border="0">
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="VesselIdLabel" Text="Vessel">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:DropDownList runat="server" id="VesselId" CssClass="field_input" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="VesselIdFvLlsHyperLink" ControlToUpdate="VesselId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" Table="DC_Vessel" Field="DC_Vessel_.VesselId" DisplayField="DC_Vessel_.VesselName">														</Selectors:FvLlsHyperLink>
    
    </td><td class="field_label_on_side"><asp:Literal runat="server" id="OrderNumLabel" Text="Order Number">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="OrderNum">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CommodityCodeLabel" Text="Commodity Code">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="CommodityCode">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="OrderStatusIdLabel" Text="Order Status">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="OrderStatusId">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="CustomerId">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="PickUpDateLabel" Text="Pick Up Date">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="PickUpDate">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ConsigneeIdLabel" Text="Consignee">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="ConsigneeId">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="DeliveryDateLabel" Text="Delivery Date">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="DeliveryDate">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side">&nbsp;</td>
    <td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td>
    </tr>
    <tr>
    <td class="dialog_field_value"><asp:checkbox id="Form1CheckBox" runat="server" Text="Form1 - Order Entry"/>&nbsp;</td>
    <td class="dialog_field_value"><asp:checkbox id="Form2CheckBox" runat="server" Text="Form 2 - Delivery Order"/>&nbsp;</td><td class="dialog_field_value"><asp:checkbox id="Form3CheckBox" runat="server" Text="Form 3 - Bill Of Lading"/>&nbsp;</td><td class="dialog_field_value"><asp:checkbox id="Form4CheckBox" runat="server" Text="Form 4 - Tally Sheet"/>&nbsp;</td>
    </tr>
    <tr>
    <td class="dialog_field_value"><asp:checkbox id="Form5CheckBox" runat="server" Text="Form 5 - Confirmation Of Sale"/>&nbsp;</td>
    <td class="dialog_field_value"><asp:checkbox id="Form6CheckBox" runat="server" Text="Form 6 - PARS Form"/>&nbsp;</td><td class="dialog_field_value"><asp:checkbox id="Form7CheckBox" runat="server" Text="Form 7 - Transportation Entry"/>&nbsp;</td><td class="dialog_field_value">&nbsp;</td>
    </tr>
    <tr>
    <td class="field_label_on_side">&nbsp;</td>
    <td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td>
    </tr>
    <tr>
    <td class="field_label_on_side">&nbsp;</td>
    <td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td><td class="dialog_field_value">&nbsp;</td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
 
											</ePortDC:DC_OrderRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/>
<br/><table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
    <td width="100%"><table><tr><td><ePortDC:ThemeButton runat="server" id="PrintManifestButton" Button-CausesValidation="False" Button-CommandName="Custom" Button-Text="Print Selected Files">
									</ePortDC:ThemeButton>
</td><td><ePortDC:ThemeButton runat="server" id="DoneButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="Back" Button-Text="Done">
									</ePortDC:ThemeButton>
</td></tr></table>&nbsp;</td>
    </tr>
</table><br>


							<div id="detailPopup" style="z-index:2; visibility:visible; position:absolute;"></div>
							<div id="detailPopupDropShadow" class="detailPopupDropShadow" style="z-index:1; visibility:visible; position:absolute;"></div>
						</td>
						</tr>
						</table>
					</td>
					</tr>
					</table>
				</td>
				<td class="pcR"></td>
			</tr>
			<tr>
				<td class="pcBL"></td>
				<td class="pcB">
				<ePortDC:Footer runat="server" id="PageFooter">
									</ePortDC:Footer>
				</td>
				<td class="pcBR"></td>
			</tr>
			</table>
			</td>
			<td class="pbR"><img src="../Images/space.gif" alt=""/></td>
		</tr>
		<tr>
			<td class="pbBL"><img src="../Images/space.gif" alt=""/></td>
			<td class="pbB"><img src="../Images/space.gif" alt=""/></td>
			<td class="pbBR"><img src="../Images/space.gif" alt=""/></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<asp:ValidationSummary id="ValidationSummary1" ShowMessageBox="true" ShowSummary="false" runat="server"></asp:ValidationSummary>
	</form>
	</body>
</html>



