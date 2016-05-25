<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="PickListDC_OrderPage.aspx.cs" Inherits="ePortDC.UI.PickListDC_OrderPage" %>
<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.PickListDC_OrderPage" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head id="Head1" runat="server">
	<title>PickListDC_OrderPage</title>
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
						<td class="pContent"><a name="StartOfPageContent"></a>
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
 <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("CancelButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("OKButton$_Button")) %><table class="dialog_view" cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dh">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <td class="dhel"><img src="../Images/space.gif" alt=""/></td>
    <td class="dheci" valign="middle"><a onclick="toggleExpandCollapse(this);"><img id="ExpandCollapseIcon" src="../Images/DialogHeaderIconCollapse.gif" border="0" alt="Collapse panel"/></a></td>
    <td class="dhb">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_OrderDialogTitle" Text="Order">
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
    <td class="dialog_field_value"><asp:Literal runat="server" id="VesselId">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="OrderNumLabel" Text="Order Number">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="OrderNum">
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
														</asp:Literal></td><td class="field_label_on_side">&nbsp;</td><td class="field_label_on_side">&nbsp;</td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ConsigneeIdLabel" Text="Consignee">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:Literal runat="server" id="ConsigneeId">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="CommentsLabel" Text="Comments">
														</asp:Literal></td><td class="dialog_field_value"><asp:Literal runat="server" id="Comments">
														</asp:Literal></td><td class="field_label_on_side"><asp:Literal runat="server" id="LoadTypeLabel" Text="Load Type">
														</asp:Literal></td><td class="field_value_blue_bn"><asp:Literal runat="server" id="LoadType">
														</asp:Literal></td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("OKButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("CancelButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("SaveButton$_Button")) %><!-- End Record Panel.html -->
											</ePortDC:DC_OrderRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									

<asp:UpdateProgress runat="server" id="DC_OrderDetailTableControlUpdateProgress" AssociatedUpdatePanelID="DC_OrderDetailTableControlUpdatePanel">
																	<ProgressTemplate>
																		<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
																		</div>
																		<div style=" position:absolute; padding:30px;">
																			<img src="../Images/updating.gif">
																		</div>
																	</ProgressTemplate>
																</asp:UpdateProgress>
																<asp:UpdatePanel runat="server" id="DC_OrderDetailTableControlUpdatePanel" UpdateMode="Conditional">

																	<ContentTemplate>
																		<ePortDC:DC_OrderDetailTableControl runat="server" id="DC_OrderDetailTableControl">
																					
<!-- Begin Table Panel.html -->

<table class="dv" cellpadding="0" cellspacing="0" border="0">
 <tr>
    <td class="dh">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <td class="dhel"><img src="../Images/space.gif" alt=""/></td>
    <td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
    <td class="dht" valign="middle"><asp:Literal runat="server" id="DC_OrderDetailTableTitle" Text="Order Detail">
																					</asp:Literal></td>
    <td class="dhtrc">
        <table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
        <tr>
        <td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_OrderDetailTotalItems">
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
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_OrderDetailTableControl$DC_OrderDetailSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_OrderDetailTableControl$DC_OrderDetailSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_OrderDetailTableControl$DC_OrderDetailFilterButton")) %>	
    
    <tr>
        <td class="fila"></td>
        <td></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_OrderDetailTableControl$DC_OrderDetailFilterButton")) %>
    
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
            
            
            
            
            <td class="prbbc">
            
            </td>
            
            <td class="prbbc">
            
            </td>
            
            <td class="prbbc">
            
            </td>
            
            <td class="prbbc">
            
            </td>
            
            <td class="prbbc">
            
            </td>
            
            
            
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_OrderDetailPagination">
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
            
            
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderSizeIdLabel1" Text="Order Size" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderQtyLabel" Text="Order Quantity" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeLowLabel" Text="Size Low" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeHighLabel" Text="Size High" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="WeightKGLabel" Text="Weight KG" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel2" Text="Comments" CausesValidation="False">
																						</asp:LinkButton>&nbsp;</th>
            
            
            
            
            
            
            
            
            
            
            
            
            
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_OrderDetailTableControlRepeater">
																							<ITEMTEMPLATE>
																									<ePortDC:DC_OrderDetailTableControlRow runat="server" id="DC_OrderDetailTableControlRow">
																											
            <div id="AJAXUpdateRecordRow">
            <tr>
            
            
            
            
            
            <td class="ttc" ><asp:Literal runat="server" id="OrderSizeId">
																											</asp:Literal></td>
            
            <td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="OrderQty">
																											</asp:Literal></td>
            
            <td class="ttc" style=";;;text-align:right"><asp:Literal runat="server" id="SizeLow">
																											</asp:Literal></td>
            
            <td class="ttc"  style="text-align:right"><asp:Literal runat="server" id="SizeHigh">
																											</asp:Literal></td>
            
            <td class="ttc" style=";;;text-align:right"><asp:Literal runat="server" id="WeightKG">
																											</asp:Literal></td>
            
            <td class="ttc" ><asp:Literal runat="server" id="Comments1">
																											</asp:Literal>&nbsp;</td>
            
            
            
            
            
            
            
            
            
            
            
            
            
            </tr>
            </div>
            
																									</ePortDC:DC_OrderDetailTableControlRow>
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
<br/>
																		</ePortDC:DC_OrderDetailTableControl>

																	</ContentTemplate>
																</asp:UpdatePanel>
																				<asp:UpdateProgress runat="server" id="DC_PickListTableControlUpdateProgress" AssociatedUpdatePanelID="DC_PickListTableControlUpdatePanel">
																							<ProgressTemplate>
																								<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
																								</div>
																								<div style=" position:absolute; padding:30px;">
																									<img src="../Images/updating.gif">
																								</div>
																							</ProgressTemplate>
																						</asp:UpdateProgress>
																						<asp:UpdatePanel runat="server" id="DC_PickListTableControlUpdatePanel" UpdateMode="Conditional">

																							<ContentTemplate>
																								<ePortDC:DC_PickListTableControl runat="server" id="DC_PickListTableControl">
																											
    <!-- Begin Table Panel.html -->

    <table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_PickListTableTitle" Text="Pick List">
																											</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_PickListTotalItems">
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
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_PickListTableControl$DC_PickListSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_PickListTableControl$DC_PickListSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_PickListTableControl$DC_PickListFilterButton")) %>
    <tr>
        <td class="fila"></td>
        <td></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_PickListTableControl$DC_PickListFilterButton")) %>
    
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
            
                <td class="prbbc"><img src="../Images/ButtonBarEdgeL.gif"></td>
                <td class="prbbc"><img src="../Images/ButtonBarDividerL.gif"></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_PickListAddButton" CausesValidation="False" CommandName="AddRecord" Consumers="DC_PickListTableControl" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																												</asp:ImageButton></td>
            <td class="prbbc"></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_PickListDeleteButton" CausesValidation="False" CommandArgument="DeleteOnUpdate" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_PickListTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																												</asp:ImageButton></td>
            <td class="prbbc"></td>
            <td class="prbbc"></td>
            <td class="prbbc"></td>
              <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
              <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_PickListPagination">
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
            <th class="thc" COLSPAN="1"><img src="../Images/space.gif" height="1" width="1" alt=""/></th>
            <th class="thc" style="padding:0px;vertical-align:middle;">
                                <asp:CheckBox runat="server" id="DC_PickListToggleAll" onclick="toggleAllCheckboxes(this);">

																												</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderDetailIdLabel" Text="Order Detail" CausesValidation="False">
																												</asp:LinkButton>
                &nbsp;</th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PackHouseIdLabel" Text="Pack House" CausesValidation="False">
																												</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PalletQtyLabel" Text="Pallet Quantity" CausesValidation="False">
																												</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PickListSizeLabel" Text="Pick List Size" CausesValidation="False">
																												</asp:LinkButton>
                </th><th class="thc" scope="col"><asp:LinkButton runat="server" id="Comments1Label" Text="Comments" CausesValidation="False">
																												</asp:LinkButton></th>
            
            
            </tr>
            </div>
            
                <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_PickListTableControlRepeater">
																													<ITEMTEMPLATE>
																															<ePortDC:DC_PickListTableControlRow runat="server" id="DC_PickListTableControlRow">
																																	
            <div id="AJAXUpdateRecordRow">
            <tr>
                
                
                <td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_PickListRecordRowDeleteButton" CausesValidation="False" CommandArgument="DeleteOnUpdate" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_PickListTableControlRow" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																																	</asp:ImageButton></td>
                <td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_PickListRecordRowSelection">

																																	</asp:CheckBox></td>
            <td class="ttc" >
            <asp:DropDownList runat="server" id="OrderDetailId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
																																	</asp:DropDownList>&nbsp;
																																	<asp:RequiredFieldValidator runat="server" id="OrderDetailIdRequiredFieldValidator" ControlToValidate="OrderDetailId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Detail&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">																																	</asp:RequiredFieldValidator>
            &nbsp;</td>
            <td class="ttc" ><asp:DropDownList runat="server" id="PackHouseId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
																																	</asp:DropDownList>
																																	<Selectors:FvLlsHyperLink runat="server" id="PackHouseIdFvLlsHyperLink" ControlToUpdate="PackHouseId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_PackHouse" Field="DC_PackHouse_.PackHouseId" DisplayField="DC_PackHouse_.PackHouseName">																																	</Selectors:FvLlsHyperLink>&nbsp;
																																	<asp:RequiredFieldValidator runat="server" id="PackHouseIdRequiredFieldValidator" ControlToValidate="PackHouseId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pack House&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">																																	</asp:RequiredFieldValidator>
            </td>
            <td class="ttc" ><table border="0" cellpadding="0" cellspacing="0">
																																	<tr>
																																	<td style="padding-right: 5px; vertical-align:top">
																																	<asp:TextBox runat="server" id="PalletQty" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input" EnableIncrementDecrementButtons="True">
																																	</asp:TextBox></td>
																																	<td style="padding-right: 5px; white-space:nowrap;">
																																	<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("PalletQty"),"NumberTextBox","","","") %>
																																	&nbsp;
																																	<asp:RequiredFieldValidator runat="server" id="PalletQtyRequiredFieldValidator" ControlToValidate="PalletQty" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pallet Quantity&quot;) %>" Enabled="True" Text="*">																																	</asp:RequiredFieldValidator>&nbsp;
																																	<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PalletQtyTextBoxMaxLengthValidator" ControlToValidate="PalletQty" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pallet Quantity&quot;) %>">																																	</BaseClasses:TextBoxMaxLengthValidator></td>
																																	</tr>
																																	</table>
																																	
            </td>
            <td class="ttc" ><table border="0" cellpadding="0" cellspacing="0">
																																	<tr>
																																	<td style="padding-right: 5px; vertical-align:top">
																																	<asp:TextBox runat="server" id="PickListSize" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input" EnableIncrementDecrementButtons="True">
																																	</asp:TextBox></td>
																																	<td style="padding-right: 5px; white-space:nowrap;">
																																	<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("PickListSize"),"NumberTextBox","","","") %>
																																	&nbsp;
																																	<asp:RequiredFieldValidator runat="server" id="PickListSizeRequiredFieldValidator" ControlToValidate="PickListSize" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pick List Size&quot;) %>" Enabled="True" Text="*">																																	</asp:RequiredFieldValidator>&nbsp;
																																	<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PickListSizeTextBoxMaxLengthValidator" ControlToValidate="PickListSize" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pick List Size&quot;) %>">																																	</BaseClasses:TextBoxMaxLengthValidator></td>
																																	</tr>
																																	</table>
																																	
            </td><td class="ttc" ><asp:TextBox runat="server" id="Comments2" Columns="50" MaxLength="50" CssClass="field_input">
																																	</asp:TextBox>&nbsp;
																																	<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Comments2TextBoxMaxLengthValidator" ControlToValidate="Comments2" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Comments&quot;) %>">																																	</BaseClasses:TextBoxMaxLengthValidator></td>
            
            </tr>
            </div>
            
																															</ePortDC:DC_PickListTableControlRow>
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

																								</ePortDC:DC_PickListTableControl>

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
																						</ePortDC:ThemeButton></td><td>&nbsp;</td><td><ePortDC:ThemeButton runat="server" id="PicklistCompleteButton" Button-CausesValidation="False" Button-CommandName="UpdateData" Button-RedirectArgument="UpdateData" Button-RedirectURL="Back" Button-Text="Picklist Complete">
																						</ePortDC:ThemeButton>
</td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    <td></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
	<td><ePortDC:ThemeButton runat="server" id="CancelButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="Back" Button-Text="Close">
																						</ePortDC:ThemeButton></td>
    <td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
    </tr>
    </table>
    </td>
    </tr>
</table></td>
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



