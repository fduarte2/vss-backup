<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_TransporterPage" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_TransporterPage.aspx.cs" Inherits="ePortDC.UI.ShowDC_TransporterPage" %>
<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_TransporterPage</title>
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
                            <a name="StartOfPageContent"></a><asp:UpdateProgress runat="server" id="DC_TransporterRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_TransporterRecordControlUpdatePanel">
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_TransporterDialogTitle" Text="Show Transporter">
														</asp:Literal></td>
    <td class="dhir"><asp:ImageButton runat="server" id="DC_TransporterDialogEditButton" CausesValidation="False" CommandName="Redirect" Consumers="page" ImageURL="../Images/iconEdit.gif" RedirectURL="../DC_Transporter/EditDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

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
        <asp:Literal runat="server" id="TransporterIdLabel" Text="Transporter">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="TransporterId">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="CarrierNameLabel" Text="Carrier Name">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="CarrierName">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="ContactNameLabel" Text="Contact Name">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="ContactName">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="EmailLabel" Text="Email">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="FaxLabel" Text="Fax">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Fax">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="IRSNumLabel" Text="IRS Number">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="IRSNum">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Phone1Label" Text="Phone 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Phone1">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Phone2Label" Text="Phone 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Phone2">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="PhoneCell1Label" Text="Phone Cell 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="PhoneCell1">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="PhoneCell2Label" Text="Phone Cell 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="PhoneCell2">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate1GTAMiltonWhitbyLabel" Text="Rate 1 GTA Milton Whitby">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate1GTAMiltonWhitby">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate2CambridgeLabel" Text="Rate 2 Cambridge">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate2Cambridge">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate3OttawaLabel" Text="Rate 3 Ottawa">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate3Ottawa">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate4MontrealLabel" Text="Rate 4 Montreal">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate4Montreal">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate5QuebecLabel" Text="Rate 5 Quebec">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate5Quebec">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate6MonctonLabel" Text="Rate 6 Moncton">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate6Moncton">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate7DebertLabel" Text="Rate 7 Debert">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate7Debert">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Rate8OtherLabel" Text="Rate 8 Other">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Rate8Other">
														</asp:Literal>
    
    </td>
    </tr>
    
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="USBondNumLabel" Text="US Bond Number">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="USBondNum">
														</asp:Literal>
    
    </td>
    </tr><tr>
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
