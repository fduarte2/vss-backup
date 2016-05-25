<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="AddDC_CommodityPage.aspx.cs" Inherits="ePortDC.UI.AddDC_CommodityPage" %>
<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.AddDC_CommodityPage" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>AddDC_CommodityPage</title>
    <link rel="stylesheet" rev="stylesheet" type="text/css" href="../Styles/Style.css"/>
    </head>
    <body id="Body1" runat="server" class="pBack">
    <form id="Form1" method="post" runat="server">
        <BaseClasses:ScrollCoordinates id="ScrollCoordinates" runat="server"></BaseClasses:ScrollCoordinates>
        <BaseClasses:BasePageSettings id="PageSettings" runat="server" LoginRequired="ADMIN" ></BaseClasses:BasePageSettings>
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
                    <ePortDC:Header runat="server" id="PageHeader">
		</ePortDC:Header>
                </td>
                </tr>
                </table></td>
                <td class="pcTR"></td>
            </tr>
            <tr>
                <td class="pcL">
                    
                </td>
                <td class="pcC">			
                    <table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%">
                    <tr>
                    <td>
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_CommodityMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                <asp:UpdateProgress runat="server" id="DC_CommodityRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_CommodityRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_CommodityRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_CommodityRecordControl runat="server" id="DC_CommodityRecordControl">
														
<!-- Begin Record Panel.html -->

<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("CancelButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("OKButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveNNewButton$_Button")) %>

 <table class="dialog_view" cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dh">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <td class="dhel"><img src="../Images/space.gif" alt=""/></td>
    <td class="dheci" valign="middle"><a onclick="toggleExpandCollapse(this);"><img id="ExpandCollapseIcon" src="../Images/DialogHeaderIconCollapse.gif" border="0" alt="Collapse panel"/></a></td>
    <td class="dhb">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_CommodityDialogTitle" Text="Add Commodity">
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
        <asp:Literal runat="server" id="CommodityCodeLabel" Text="Commodity Code">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="CommodityCode" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("CommodityCode"),"NumberTextBox","","","") %>
														&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CommodityCodeRequiredFieldValidator" ControlToValidate="CommodityCode" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Code&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CommodityCodeTextBoxMaxLengthValidator" ControlToValidate="CommodityCode" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Code&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="CommodityNameLabel" Text="Commodity Name">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="CommodityName" Columns="30" MaxLength="30" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CommodityNameRequiredFieldValidator" ControlToValidate="CommodityName" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Name&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CommodityNameTextBoxMaxLengthValidator" ControlToValidate="CommodityName" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Name&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="HarmonizedSystemTariffLabel" Text="Harmonized System Tariff">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="HarmonizedSystemTariff" Columns="10" MaxLength="10" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="HarmonizedSystemTariffRequiredFieldValidator" ControlToValidate="HarmonizedSystemTariff" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Harmonized System Tariff&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="HarmonizedSystemTariffTextBoxMaxLengthValidator" ControlToValidate="HarmonizedSystemTariff" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Harmonized System Tariff&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
                </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
 </table>
<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("OKButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("CancelButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("SaveButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("SaveNNewButton$_Button")) %>
<!-- End Record Panel.html -->

											</ePortDC:DC_CommodityRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/>

    <table cellpadding="0" cellspacing="0" border="0" id="Table1">
    <tr>
    <td class="recordPanelButtonsAlignment">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td><ePortDC:ThemeButton runat="server" id="SaveButton" Button-CausesValidation="True" Button-CommandName="UpdateData" Button-RedirectURL="Back" Button-Text="&lt;%# GetResourceValue(&quot;Btn:Save&quot;, &quot;ePortDC&quot;) %>">
										</ePortDC:ThemeButton></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
	<td><ePortDC:ThemeButton runat="server" id="CancelButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="Back" Button-Text="&lt;%# GetResourceValue(&quot;Btn:Cancel&quot;, &quot;ePortDC&quot;) %>">
										</ePortDC:ThemeButton></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    </tr>
    </table>
    </td>
    </tr>
</table>

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
