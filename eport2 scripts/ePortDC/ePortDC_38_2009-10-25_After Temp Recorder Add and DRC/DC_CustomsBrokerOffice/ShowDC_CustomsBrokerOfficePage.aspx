<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_CustomsBrokerOfficePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_CustomsBrokerOfficePage" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_CustomsBrokerOfficePage" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_CustomsBrokerOfficePage</title>
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
                            <a name="StartOfPageContent"></a><asp:UpdateProgress runat="server" id="DC_CustomsBrokerOfficeRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_CustomsBrokerOfficeRecordControlUpdatePanel">
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_CustomsBrokerOfficeDialogTitle" Text="Show Customs Broker Office">
														</asp:Literal></td>
    <td class="dhir"><asp:ImageButton runat="server" id="DC_CustomsBrokerOfficeDialogEditButton" CausesValidation="False" CommandName="Redirect" Consumers="page" ImageURL="../Images/iconEdit.gif" RedirectURL="../DC_CustomsBrokerOffice/EditDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

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
        <asp:Literal runat="server" id="CustomsBrokerOfficeIdLabel" Text="Customs Broker Office">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="CustomsBrokerOfficeId">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ClientLabel" Text="Client">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Client">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="BorderCrossingLabel" Text="Border Crossing">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="BorderCrossing">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomsBrokerLabel" Text="Customs Broker">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="CustomsBroker">
														</asp:Literal></td>
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
    <td class="field_label_on_side"><asp:Literal runat="server" id="PhoneLabel" Text="Phone">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Phone">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="FaxLabel" Text="Fax">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Fax">
														</asp:Literal></td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email1Label" Text="Email 1">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email1">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email2Label" Text="Email 2">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email2">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email3Label" Text="Email 3">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email3">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email4Label" Text="Email 4">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email4">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="Email5Label" Text="Email 5">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:Literal runat="server" id="Email5">
														</asp:Literal>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="DestinationsLabel" Text="Destinations">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="Destinations">
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

											</ePortDC:DC_CustomsBrokerOfficeRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/>
                            <asp:UpdateProgress runat="server" id="DC_ConsigneeTableControlUpdateProgress" AssociatedUpdatePanelID="DC_ConsigneeTableControlUpdatePanel">
																	<ProgressTemplate>
																		<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
																		</div>
																		<div style=" position:absolute; padding:30px;">
																			<img src="../Images/updating.gif">
																		</div>
																	</ProgressTemplate>
																</asp:UpdateProgress>
																<asp:UpdatePanel runat="server" id="DC_ConsigneeTableControlUpdatePanel" UpdateMode="Conditional">

																	<ContentTemplate>
																		<ePortDC:DC_ConsigneeTableControl runat="server" id="DC_ConsigneeTableControl">
																					
<!-- Begin Table Panel.html -->

<table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_ConsigneeTableTitle" Text="Consignee">
																					</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_ConsigneeTotalItems">
																					</asp:Label></td>
		</tr>
	</table>
	</td>
	<td class="dher"><img src="../Images/space.gif" alt=""/></td>
	</tr>
	</table>
	</td>
	</tr>
 <tr>
    <td class="dBody">
    <table id="CollapsibleRegion" style="display:block;" cellspacing="0" cellpadding="0" border="0">
    <tr>
    <td>
    <table id="FilterRegion" cellpadding="0" cellspacing="3" border="0">
    
    <!-- Search & Filter Area -->
    <tr>
        <td class="fila"></td>
        <td>
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_ConsigneeTableControl$DC_ConsigneeSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_ConsigneeTableControl$DC_ConsigneeSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"><ePortDC:ThemeButton runat="server" id="DC_ConsigneeFilterButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
																						</ePortDC:ThemeButton></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_ConsigneeTableControl$DC_ConsigneeFilterButton")) %>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
																						</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="CustomerIdFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
																						</asp:DropDownList></td>
    </tr>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="ConsigneeIdLabel" Text="Consignee">
																						</asp:Literal></td>
        <td><asp:TextBox runat="server" id="ConsigneeIdFromFilter" Columns="30" CssClass="Filter_Input">
																						</asp:TextBox><span class="rft"><asp:Label runat="server" id="ConsigneeIdFilterText" Text="to">
																						</asp:Label></span><asp:TextBox runat="server" id="ConsigneeIdToFilter" Columns="30" CssClass="Filter_Input">
																						</asp:TextBox></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="StateProvince1Label" Text="State Province">
																						</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="StateProvinceFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
																						</asp:DropDownList></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_ConsigneeTableControl$DC_ConsigneeFilterButton")) %>
    
    </table>
    <div class="spacer"></div>
    <!-- Category Area -->
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
    <td>
        <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
        <td>
        <table class="tv" cellpadding="0" cellspacing="0" border="0">
        <tr>
        <!-- Pagination Area -->
        <td class="pr" colspan="100">
        <table id="PaginationRegion" cellspacing="0" cellpadding="0" border="0">
        <tr>
        <td><img src="../Images/paginationRowEdgeL.gif" alt=""/></td>
        
        <td class="prbbc"><img src="../Images/ButtonBarEdgeL.gif"></td><td class="prbbc"><img src="../Images/ButtonBarDividerL.gif"></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeNewButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" RedirectURL="../DC_Consignee/AddDC_ConsigneePage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeEditButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarEdit.gif" onmouseout="this.src='../Images/ButtonBarEdit.gif'" onmouseover="this.src='../Images/ButtonBarEditOver.gif'" RedirectURL="../DC_Consignee/EditDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeCopyButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarCopy.gif" onmouseout="this.src='../Images/ButtonBarCopy.gif'" onmouseover="this.src='../Images/ButtonBarCopyOver.gif'" RedirectURL="../DC_Consignee/AddDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Copy&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneePDFButton" CausesValidation="False" CommandName="ReportData" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarPDFExport.gif" onmouseout="this.src='../Images/ButtonBarPDFExport.gif'" onmouseover="this.src='../Images/ButtonBarPDFExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PDF&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeExportExcelButton" CausesValidation="False" CommandName="ExportDataExcel" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarExcelExport.gif" onmouseout="this.src='../Images/ButtonBarExcelExport.gif'" onmouseover="this.src='../Images/ButtonBarExcelExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:ExportExcel&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeExportButton" CausesValidation="False" CommandName="ExportData" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarCSVExport.gif" onmouseout="this.src='../Images/ButtonBarCSVExport.gif'" onmouseover="this.src='../Images/ButtonBarCSVExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Export&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_ConsigneeResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_ConsigneeTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton></td>
                <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
                <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_ConsigneePagination">
																					</ePortDC:Pagination>
            </td>
        <td><img src="../Images/paginationRowEdgeR.gif" alt=""/></td>
        </tr>
        </table>
        </td>
        </tr>
        <!--Table View Area -->
        <tr>
        <td class="tre">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" onkeydown="captureUpDownKey(this, event)">
            <!-- This is the table's header row -->
            
            <div id="AJAXUpdateHeaderRow">
            <tr class="tch">
            <th class="thc" colspan="4"><img src="../Images/space.gif" height="1" width="1" alt=""/></th>
            <th class="thc" style="padding:0px;vertical-align:middle;">
                    <asp:CheckBox runat="server" id="DC_ConsigneeToggleAll" onclick="toggleAllCheckboxes(this);">

																						</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="ConsigneeIdLabel1" Text="Consignee" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="ConsigneeNameLabel" Text="Consignee Name" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerIdLabel1" Text="Customer" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="AddressLabel" Text="Address" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CityLabel" Text="City" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="StateProvinceLabel" Text="State Province" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PostalCodeLabel" Text="Postal Code" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneLabel1" Text="Phone" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneMobileLabel" Text="Phone Mobile" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel1" Text="Comments" CausesValidation="False">
																						</asp:LinkButton>
                </th>
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_ConsigneeTableControlRepeater">
																							<ITEMTEMPLATE>
																									<ePortDC:DC_ConsigneeTableControlRow runat="server" id="DC_ConsigneeTableControlRow">
																											
            <div id="AJAXUpdateRecordRow">
            <tr><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_ConsigneeRecordRowViewButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_view.gif" RedirectURL="../DC_Consignee/ShowDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:ViewRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																											</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_ConsigneeRecordRowEditButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_edit.gif" RedirectURL="../DC_Consignee/EditDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:EditRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																											</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_ConsigneeRecordRowCopyButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_copy.gif" RedirectURL="../DC_Consignee/AddDC_ConsigneePage.aspx?DC_Consignee={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:CopyRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																											</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_ConsigneeRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_ConsigneeTableControlRow" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																											</asp:ImageButton></td><td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_ConsigneeRecordRowSelection">

																											</asp:CheckBox></td>
            <td class="ttc" style=";;;text-align:right"><asp:Literal runat="server" id="ConsigneeId">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="ConsigneeName">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:LinkButton runat="server" id="CustomerId" CausesValidation="False" CommandName="Redirect" RedirectURL="../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_ConsigneeTableControlRow:FK:FK_DC_Consignee_DC_Customer1}">
																											</asp:LinkButton>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Address">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="City">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="StateProvince">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="PostalCode">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Phone1">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="PhoneMobile">
																											</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Comments1">
																											</asp:Literal>
            </td>
            </tr>
            </div>
            
																									</ePortDC:DC_ConsigneeTableControlRow>
																							</ITEMTEMPLATE>
																					</asp:Repeater>
            <!-- Totals Area -->
                        
            </table>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
 </tr>
</table>
<!-- End Table Panel.html -->

																		</ePortDC:DC_ConsigneeTableControl>

																	</ContentTemplate>
																	<Triggers>
																		<asp:PostBackTrigger ControlID="DC_ConsigneePDFButton"/>
																		<asp:PostBackTrigger ControlID="DC_ConsigneeExportExcelButton"/>
																		<asp:PostBackTrigger ControlID="DC_ConsigneeExportButton"/>
																	</Triggers>
																</asp:UpdatePanel>
															
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
