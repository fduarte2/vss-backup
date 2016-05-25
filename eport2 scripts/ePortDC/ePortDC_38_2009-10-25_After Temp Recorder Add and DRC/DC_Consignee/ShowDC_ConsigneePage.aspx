<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_ConsigneePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_ConsigneePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_ConsigneePage" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_ConsigneePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_ConsigneeMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a><asp:UpdateProgress runat="server" id="DC_ConsigneeRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_ConsigneeRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_ConsigneeRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_ConsigneeRecordControl runat="server" id="DC_ConsigneeRecordControl">
														
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_ConsigneeDialogTitle" Text="Show Consignee">
														</asp:Literal></td>
    <td class="dhir"><asp:ImageButton runat="server" id="DC_ConsigneeDialogEditButton" CausesValidation="False" CommandName="Redirect" Consumers="page" ImageURL="../Images/iconEdit.gif" RedirectURL="../DC_Consignee/EditDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

														</asp:ImageButton></td>
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
        <asp:Literal runat="server" id="ConsigneeIdLabel" Text="Consignee">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="ConsigneeId">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:LinkButton runat="server" id="CustomerId" CausesValidation="False" CommandName="Redirect" RedirectURL="../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_ConsigneeRecordControl:FK:FK_DC_Consignee_DC_Customer1}">
														</asp:LinkButton></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ConsigneeNameLabel" Text="Consignee Name">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="ConsigneeName">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomsBrokerOfficeIdLabel" Text="Customs Broker Office">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:LinkButton runat="server" id="CustomsBrokerOfficeId" CausesValidation="False" CommandName="Redirect" RedirectURL="../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={DC_ConsigneeRecordControl:FK:FK_DC_Consignee_DC_CustomsBrokerOffice}">
														</asp:LinkButton></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="AddressLabel" Text="Address">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Address">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CityLabel" Text="City">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="City">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="StateProvinceLabel" Text="State Province">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="StateProvince">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="PostalCodeLabel" Text="Postal Code">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="PostalCode">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="PhoneLabel" Text="Phone">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Phone">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="PhoneMobileLabel" Text="Phone Mobile">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="PhoneMobile">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CommentsLabel" Text="Comments">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Comments">
														</asp:Literal></td>
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

											</ePortDC:DC_ConsigneeRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/>
                            
<br/>

    <table cellpadding="0" cellspacing="0" border="0" id="Table1">
    <tr>
    <td class="recordPanelButtonsAlignment">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td><ePortDC:ThemeButton runat="server" id="OKButton" Button-CausesValidation="False" Button-RedirectURL="Back" Button-Text="&lt;%# GetResourceValue(&quot;Btn:OK&quot;, &quot;ePortDC&quot;) %>">
										</ePortDC:ThemeButton></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
	<td></td>
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
