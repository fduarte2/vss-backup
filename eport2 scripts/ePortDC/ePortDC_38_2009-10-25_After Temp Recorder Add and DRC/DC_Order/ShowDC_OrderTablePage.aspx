<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_OrderTablePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_OrderTablePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_OrderTablePage" %>
<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_OrderTablePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_OrderMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent"><a name="StartOfPageContent"></a><asp:UpdateProgress runat="server" id="DC_OrderTableControlUpdateProgress" AssociatedUpdatePanelID="DC_OrderTableControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_OrderTableControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_OrderTableControl runat="server" id="DC_OrderTableControl">
														
<!-- Begin Table Panel.html -->
<table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_OrderTableTitle" Text="Order">
														</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_OrderTotalItems">
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
    <td><table id="FilterRegion" cellpadding="0" cellspacing="3" border="0">
    
    <!-- Search & Filter Area -->
    <tr>
        <td class="fila"><%# GetResourceValue("Txt:SearchFor", "ePortDC") %></td>
        <td>
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_OrderTableControl$DC_OrderSearchButton")) %>
        <asp:TextBox runat="server" id="DC_OrderSearchArea" CaseSensitive="False" Columns="50" CssClass="Search_Input" Fields="OrderNum,CustomerId,ConsigneeId,DriverName,TransporterId,VesselId">
															</asp:TextBox>
		<asp:AutoCompleteExtender id="DC_OrderSearchAreaAutoCompleteExtender" runat="server" TargetControlID="DC_OrderSearchArea" ServiceMethod="GetAutoCompletionList_DC_OrderSearchArea" MinimumPrefixLength="2" CompletionInterval="700" CompletionSetCount="10" CompletionListCssClass="autotypeahead_completionListElement" CompletionListItemCssClass="autotypeahead_listItem " CompletionListHighlightedItemCssClass="autotypeahead_highlightedListItem">
		</asp:AutoCompleteExtender>
		
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_OrderTableControl$DC_OrderSearchButton")) %>
        </td>
        <td class="filbc"><ePortDC:ThemeButton runat="server" id="DC_OrderSearchButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
		</ePortDC:ThemeButton></td><td class="filbc">&nbsp;</td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"><ePortDC:ThemeButton runat="server" id="DC_OrderFilterButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
		</ePortDC:ThemeButton></td><td rowspan="100" class="filbc">&nbsp;&nbsp;
<Br>
<ePortDC:ThemeButton runat="server" id="CrystalReportButton" Button-CausesValidation="False" Button-CommandName="CustomPostback" Button-Text="&lt;%# GetResourceValue(&quot;Show Report&quot;, &quot;ePortDC&quot;) %>" Postback="true">
			</ePortDC:ThemeButton>
      <br/><br/>
      &nbsp;</td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_OrderTableControl$DC_OrderFilterButton")) %>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="CommodityCodeLabel" Text="Commodity Code">
		</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="CommodityCodeFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
		</asp:DropDownList></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
		</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="CustomerIdFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
		</asp:DropDownList></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="OrderStatusId1Label" Text="Order Status">
		</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="OrderStatusIdFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
		</asp:DropDownList></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="TEStatus1Label" Text="TE Status">
		</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="TEStatusFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
		</asp:DropDownList></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="PickUpDate1Label" Text="Pick Up Date">
		</asp:Literal></td>
        <td><asp:TextBox runat="server" id="PickUpDateFromFilter" Columns="30" CssClass="Filter_Input" OnKeyUp="DateFormat(this, this.value, event.keyCode, 'm/d/yyyy')" TimeString="&quot;00:00:00&quot;" AutoPostback="False" style="vertical-align:middle">
		</asp:TextBox>
		<Selectors:FvDsHyperLink runat="server" id="PickUpDateFromFilterFvDsHyperLink" ControlToUpdate="PickUpDateFromFilter" Text="&lt;%# GetResourceValue(&quot;DS:Text&quot;, &quot;ePortDC&quot;) %>">		</Selectors:FvDsHyperLink><span class="rft"><asp:Label runat="server" id="PickUpDateFilterText" Text="to">
		</asp:Label></span><asp:TextBox runat="server" id="PickUpDateToFilter" Columns="30" CssClass="Filter_Input" OnKeyUp="DateFormat(this, this.value, event.keyCode, 'm/d/yyyy')" TimeString="&quot;23:59:59&quot;" AutoPostback="False" style="vertical-align:middle">
		</asp:TextBox>
		<Selectors:FvDsHyperLink runat="server" id="PickUpDateToFilterFvDsHyperLink" ControlToUpdate="PickUpDateToFilter" Text="&lt;%# GetResourceValue(&quot;DS:Text&quot;, &quot;ePortDC&quot;) %>">		</Selectors:FvDsHyperLink></td>
    </tr>
    
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_OrderTableControl$DC_OrderFilterButton")) %>
    
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
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderNewButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" RedirectURL="../DC_Order/AddDC_OrderPage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderEditButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarEdit.gif" onmouseout="this.src='../Images/ButtonBarEdit.gif'" onmouseover="this.src='../Images/ButtonBarEditOver.gif'" RedirectURL="../DC_Order/EditDC_OrderPage.aspx?DC_Order={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" RequiredRoles="ADMIN;PORTADMIN" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderPDFButton" CausesValidation="False" CommandName="ReportData" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarPDFExport.gif" onmouseout="this.src='../Images/ButtonBarPDFExport.gif'" onmouseover="this.src='../Images/ButtonBarPDFExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PDF&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderExportExcelButton" CausesValidation="False" CommandName="ExportDataExcel" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarExcelExport.gif" onmouseout="this.src='../Images/ButtonBarExcelExport.gif'" onmouseover="this.src='../Images/ButtonBarExcelExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:ExportExcel&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderExportButton" CausesValidation="False" CommandName="ExportData" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarCSVExport.gif" onmouseout="this.src='../Images/ButtonBarCSVExport.gif'" onmouseover="this.src='../Images/ButtonBarCSVExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Export&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_OrderResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_OrderTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

		</asp:ImageButton></td>
                
                <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_OrderPagination">
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
            <th class="thc" >&nbsp;</th><th class="thc" COLSPAN="3"><img src="../Images/space.gif" height="1" width="1" alt=""/></th>
            <th class="thc" style="padding:0px;vertical-align:middle;">
                    <asp:CheckBox runat="server" id="DC_OrderToggleAll" onclick="toggleAllCheckboxes(this);">

		</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderNumLabel" Text="Order Number" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderStatusIdLabel" Text="Order Status" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerIdLabel1" Text="Customer" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="ConsigneeIdLabel" Text="Consignee" CausesValidation="False">
		</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PickUpDateLabel" Text="Pick Up Date" CausesValidation="False">
		</asp:LinkButton>
                &nbsp;</th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="DeliveryDateLabel" Text="Delivery Date" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TEStatusLabel" Text="TE Status" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="VesselIdLabel" Text="Vessel" CausesValidation="False">
		</asp:LinkButton>
                &nbsp;</th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CommodityCodeLabel1" Text="Commodity Code" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerPOLabel" Text="Customer PO" CausesValidation="False">
		</asp:LinkButton>
                &nbsp;</th>
            
            
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="DirectOrderLabel" Text="Direct Order" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="LoadTypeLabel" Text="Load Type" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SNMGNumLabel" Text="SNMG Number" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TransporterIdLabel" Text="Transporter" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="DriverNameLabel" Text="Driver Name" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TruckTagLabel" Text="Truck Tag" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TrailerNumLabel" Text="Trailer Number" CausesValidation="False">
		</asp:LinkButton>
                &nbsp;</th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TotalCountLabel" Text="Total Carton Count" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TotalPalletCountLabel" Text="Total Pallet Count" CausesValidation="False">
		</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TotalBoxDamagedLabel" Text="Total Box Damaged" CausesValidation="False">
		</asp:LinkButton>
                </th><th class="thc" scope="col"><asp:LinkButton runat="server" id="TotalQuantityKGLabel" Text="Total Weight (KG)" CausesValidation="False">
		</asp:LinkButton>
                </th><th class="thc" scope="col"><asp:LinkButton runat="server" id="TotalPriceLabel" Text="Total Price" CausesValidation="False">
		</asp:LinkButton></th><th class="thc" scope="col"><asp:LinkButton runat="server" id="TransportChargesLabel" Text="Transport Charges" CausesValidation="False">
		</asp:LinkButton></th><th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel" Text="Comments" CausesValidation="False">
		</asp:LinkButton></th>
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_OrderTableControlRepeater">
			<ITEMTEMPLATE>
					<ePortDC:DC_OrderTableControlRow runat="server" id="DC_OrderTableControlRow">
							
            <div id="AJAXUpdateRecordRow">
            <tr><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_OrderRecordRowPicklistButton" CausesValidation="False" CommandName="Redirect" Consumers="page" ImageURL="../Images/icon_picklist.gif" RedirectURL="PickListDC_OrderPage.aspx?DC_Order={DC_OrderTableControlRow:FV:OrderNum}" RequiredRoles="ADMIN;EX;PORTADMIN" AlternateText="">

							</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_OrderRecordRowViewButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_view.gif" RedirectURL="../DC_Order/ShowDC_OrderPage.aspx?DC_Order={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:ViewRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

							</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_OrderRecordRowEditButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_edit.gif" RedirectURL="../DC_Order/EditDC_OrderPage.aspx?DC_Order={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:EditRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

							</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_OrderRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_OrderTableControlRow" ImageURL="../Images/icon_delete.gif" RequiredRoles="ADMIN;PORTADMIN" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

							</asp:ImageButton></td><td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_OrderRecordRowSelection">

							</asp:CheckBox></td>
            <td class="ttc" ><asp:Literal runat="server" id="OrderNum">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="OrderStatusId">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:LinkButton runat="server" id="CustomerId" CausesValidation="False" CommandName="Redirect" RedirectURL="../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_OrderTableControlRow:FK:FK_Order_Customer}">
							</asp:LinkButton>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="ConsigneeId">
							</asp:Literal></td>
            <td class="ttc" ><asp:Literal runat="server" id="PickUpDate">
							</asp:Literal>
            &nbsp;</td>
            <td class="ttc" ><asp:Literal runat="server" id="DeliveryDate">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="TEStatus">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="VesselId">
							</asp:Literal>
            &nbsp;</td>
            <td class="ttc" ><asp:Literal runat="server" id="CommodityCode">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="CustomerPO">
							</asp:Literal>
            &nbsp;</td>
            
            <td class="ttc" ><asp:Literal runat="server" id="DirectOrder">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="LoadType">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="SNMGNum">
							</asp:Literal>
            </td>
            <td class="ttc" ><asp:LinkButton runat="server" id="TransporterId" CausesValidation="False" CommandName="Redirect" RedirectURL="../DC_Transporter/ShowDC_TransporterPage.aspx?DC_Transporter={DC_OrderTableControlRow:FK:FK_Order_Transporter}">
							</asp:LinkButton>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="DriverName">
							</asp:Literal>
            </td>
            <td class="ttc"  ><asp:Literal runat="server" id="TruckTag">
							</asp:Literal>
            </td>
            <td class="ttc"  ><asp:Literal runat="server" id="TrailerNum">
							</asp:Literal>
            &nbsp;</td>
            <td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="TotalCount">
							</asp:Literal>
            </td>
            <td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="TotalPalletCount">
							</asp:Literal>
            </td>
            <td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="TotalBoxDamaged">
							</asp:Literal>
            </td><td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="TotalQuantityKG">
							</asp:Literal>
            </td><td class="ttc"  ><asp:Literal runat="server" id="TotalPrice">
							</asp:Literal></td><td class="ttc"  ><asp:Literal runat="server" id="TransportCharges">
							</asp:Literal></td><td class="ttc"  ><asp:Literal runat="server" id="Comments">
							</asp:Literal></td>
            </tr>
            </div>
            
					</ePortDC:DC_OrderTableControlRow>
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
    </table></td>
    </tr>
    </table>
    </td>
 </tr>
</table><!-- End Table Panel.html -->
</ePortDC:DC_OrderTableControl>

</ContentTemplate>
<Triggers>
	<asp:PostBackTrigger ControlID="CrystalReportButton"/>
	<asp:PostBackTrigger ControlID="DC_OrderPDFButton"/>
	<asp:PostBackTrigger ControlID="DC_OrderExportExcelButton"/>
	<asp:PostBackTrigger ControlID="DC_OrderExportButton"/>
</Triggers>
</asp:UpdatePanel>

<br/><div id="detailPopup" style="z-index:2; visibility:visible; position:absolute;"></div><div id="detailPopupDropShadow" class="detailPopupDropShadow" style="z-index:1; visibility:visible; position:absolute;"></div></td>
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
