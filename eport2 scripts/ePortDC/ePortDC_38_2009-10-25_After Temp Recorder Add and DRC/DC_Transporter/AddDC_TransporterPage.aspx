<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.AddDC_TransporterPage" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="AddDC_TransporterPage.aspx.cs" Inherits="ePortDC.UI.AddDC_TransporterPage" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>AddDC_TransporterPage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_TransporterMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                <asp:UpdateProgress runat="server" id="DC_TransporterRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_TransporterRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_TransporterRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_TransporterRecordControl runat="server" id="DC_TransporterRecordControl">
														
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_TransporterDialogTitle" Text="Add Transporter">
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
        <asp:Literal runat="server" id="TransporterIdLabel" Text="Transporter">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="TransporterId" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("TransporterId"),"NumberTextBox","","","") %>
														&nbsp;
														<asp:RequiredFieldValidator runat="server" id="TransporterIdRequiredFieldValidator" ControlToValidate="TransporterId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Transporter&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="TransporterIdTextBoxMaxLengthValidator" ControlToValidate="TransporterId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Transporter&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="CarrierNameLabel" Text="Carrier Name">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="CarrierName" Columns="30" MaxLength="30" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CarrierNameRequiredFieldValidator" ControlToValidate="CarrierName" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Carrier Name&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CarrierNameTextBoxMaxLengthValidator" ControlToValidate="CarrierName" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Carrier Name&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="ContactNameLabel" Text="Contact Name">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="ContactName" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="ContactNameTextBoxMaxLengthValidator" ControlToValidate="ContactName" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Contact Name&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="EmailLabel" Text="Email">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="EmailTextBoxMaxLengthValidator" ControlToValidate="Email" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="FaxLabel" Text="Fax">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Fax" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="FaxTextBoxMaxLengthValidator" ControlToValidate="Fax" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Fax&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="IRSNumLabel" Text="IRS Number">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="IRSNum" Columns="15" MaxLength="15" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="IRSNumTextBoxMaxLengthValidator" ControlToValidate="IRSNum" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;IRS Number&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Phone1Label" Text="Phone 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Phone1" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Phone1TextBoxMaxLengthValidator" ControlToValidate="Phone1" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Phone 1&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Phone2Label" Text="Phone 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Phone2" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Phone2TextBoxMaxLengthValidator" ControlToValidate="Phone2" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Phone 2&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="PhoneCell1Label" Text="Phone Cell 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="PhoneCell1" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PhoneCell1TextBoxMaxLengthValidator" ControlToValidate="PhoneCell1" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Phone Cell 1&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="PhoneCell2Label" Text="Phone Cell 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="PhoneCell2" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PhoneCell2TextBoxMaxLengthValidator" ControlToValidate="PhoneCell2" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Phone Cell 2&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate1GTAMiltonWhitbyLabel" Text="Rate 1 GTA Milton Whitby">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate1GTAMiltonWhitby" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate1GTAMiltonWhitby"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate1GTAMiltonWhitbyTextBoxMaxLengthValidator" ControlToValidate="Rate1GTAMiltonWhitby" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 1 GTA Milton Whitby&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate2CambridgeLabel" Text="Rate 2 Cambridge">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate2Cambridge" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate2Cambridge"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate2CambridgeTextBoxMaxLengthValidator" ControlToValidate="Rate2Cambridge" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 2 Cambridge&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate3OttawaLabel" Text="Rate 3 Ottawa">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate3Ottawa" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate3Ottawa"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate3OttawaTextBoxMaxLengthValidator" ControlToValidate="Rate3Ottawa" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 3 Ottawa&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate4MontrealLabel" Text="Rate 4 Montreal">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate4Montreal" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate4Montreal"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate4MontrealTextBoxMaxLengthValidator" ControlToValidate="Rate4Montreal" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 4 Montreal&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate5QuebecLabel" Text="Rate 5 Quebec">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate5Quebec" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate5Quebec"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate5QuebecTextBoxMaxLengthValidator" ControlToValidate="Rate5Quebec" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 5 Quebec&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate6MonctonLabel" Text="Rate 6 Moncton">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate6Moncton" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate6Moncton"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate6MonctonTextBoxMaxLengthValidator" ControlToValidate="Rate6Moncton" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 6 Moncton&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate7DebertLabel" Text="Rate 7 Debert">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate7Debert" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate7Debert"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate7DebertTextBoxMaxLengthValidator" ControlToValidate="Rate7Debert" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 7 Debert&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate8OtherLabel" Text="Rate 8 Other">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Rate8Other" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Rate8Other"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Rate8OtherTextBoxMaxLengthValidator" ControlToValidate="Rate8Other" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Rate 8 Other&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="USBondNumLabel" Text="US Bond Number">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="USBondNum" Columns="15" MaxLength="15" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="USBondNumTextBoxMaxLengthValidator" ControlToValidate="USBondNum" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;US Bond Number&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CommentsLabel" Text="Comments">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Comments" Columns="50" MaxLength="50" CssClass="field_input">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CommentsTextBoxMaxLengthValidator" ControlToValidate="Comments" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Comments&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
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

											</ePortDC:DC_TransporterRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/>
                            
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
