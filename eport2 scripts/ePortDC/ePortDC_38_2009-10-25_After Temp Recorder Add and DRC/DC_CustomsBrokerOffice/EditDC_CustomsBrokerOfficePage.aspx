<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.EditDC_CustomsBrokerOfficePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="EditDC_CustomsBrokerOfficePage.aspx.cs" Inherits="ePortDC.UI.EditDC_CustomsBrokerOfficePage" %>
<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>EditDC_CustomsBrokerOfficePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_CustomsBrokerOfficeMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                <asp:UpdateProgress runat="server" id="DC_CustomsBrokerOfficeRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_CustomsBrokerOfficeRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_CustomsBrokerOfficeRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_CustomsBrokerOfficeRecordControl runat="server" id="DC_CustomsBrokerOfficeRecordControl">
														
<!-- Begin Record Panel.html -->

<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("CancelButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("OKButton$_Button")) %>
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("EditButton$_Button")) %>
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_CustomsBrokerOfficeDialogTitle" Text="Edit Customs Broker Office">
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
        <asp:Literal runat="server" id="CustomsBrokerOfficeIdLabel" Text="Customs Broker Office">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="CustomsBrokerOfficeId" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("CustomsBrokerOfficeId"),"NumberTextBox","","","") %>
														&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CustomsBrokerOfficeIdRequiredFieldValidator" ControlToValidate="CustomsBrokerOfficeId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customs Broker Office&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CustomsBrokerOfficeIdTextBoxMaxLengthValidator" ControlToValidate="CustomsBrokerOfficeId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customs Broker Office&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ClientLabel" Text="Client">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Client" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="ClientRequiredFieldValidator" ControlToValidate="Client" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Client&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="ClientTextBoxMaxLengthValidator" ControlToValidate="Client" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Client&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="BorderCrossingLabel" Text="Border Crossing">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="BorderCrossing" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="BorderCrossingRequiredFieldValidator" ControlToValidate="BorderCrossing" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Border Crossing&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="BorderCrossingTextBoxMaxLengthValidator" ControlToValidate="BorderCrossing" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Border Crossing&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomsBrokerLabel" Text="Customs Broker">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="CustomsBroker" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CustomsBrokerTextBoxMaxLengthValidator" ControlToValidate="CustomsBroker" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customs Broker&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
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
    <td class="field_label_on_side"><asp:Literal runat="server" id="PhoneLabel" Text="Phone">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Phone" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PhoneTextBoxMaxLengthValidator" ControlToValidate="Phone" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Phone&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="FaxLabel" Text="Fax">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Fax" Columns="25" MaxLength="25" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="FaxTextBoxMaxLengthValidator" ControlToValidate="Fax" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Fax&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email1Label" Text="Email 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email1" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Email1TextBoxMaxLengthValidator" ControlToValidate="Email1" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email 1&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email2Label" Text="Email 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email2" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Email2TextBoxMaxLengthValidator" ControlToValidate="Email2" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email 2&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email3Label" Text="Email 3">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email3" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Email3TextBoxMaxLengthValidator" ControlToValidate="Email3" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email 3&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email4Label" Text="Email 4">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email4" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Email4TextBoxMaxLengthValidator" ControlToValidate="Email4" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email 4&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email5Label" Text="Email 5">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:TextBox runat="server" id="Email5" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Email5TextBoxMaxLengthValidator" ControlToValidate="Email5" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Email 5&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="DestinationsLabel" Text="Destinations">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Destinations" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="DestinationsTextBoxMaxLengthValidator" ControlToValidate="Destinations" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Destinations&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CommentsLabel" Text="Comments">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="Comments" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
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
<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("EditButton$_Button")) %>
<!-- End Record Panel.html -->

											</ePortDC:DC_CustomsBrokerOfficeRecordControl>
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
