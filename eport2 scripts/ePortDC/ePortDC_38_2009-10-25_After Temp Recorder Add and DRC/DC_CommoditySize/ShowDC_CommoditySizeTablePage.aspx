<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_CommoditySizeTablePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_CommoditySizeTablePage" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_CommoditySizeTablePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_CommoditySizeTablePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_CommoditySizeMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                            <asp:UpdateProgress runat="server" id="DC_CommoditySizeTableControlUpdateProgress" AssociatedUpdatePanelID="DC_CommoditySizeTableControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_CommoditySizeTableControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_CommoditySizeTableControl runat="server" id="DC_CommoditySizeTableControl">
														
<!-- Begin Table Panel.html -->

<table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_CommoditySizeTableTitle" Text="Commodity Size">
														</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_CommoditySizeTotalItems">
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
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_CommoditySizeTableControl$DC_CommoditySizeSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_CommoditySizeTableControl$DC_CommoditySizeSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"><ePortDC:ThemeButton runat="server" id="DC_CommoditySizeFilterButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
															</ePortDC:ThemeButton></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_CommoditySizeTableControl$DC_CommoditySizeFilterButton")) %>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="SizeIdLabel" Text="Size">
															</asp:Literal></td>
        <td><asp:TextBox runat="server" id="SizeIdFromFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox><span class="rft"><asp:Label runat="server" id="SizeIdFilterText" Text="to">
															</asp:Label></span><asp:TextBox runat="server" id="SizeIdToFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox></td>
    </tr>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="DescrLabel" Text="Descr">
															</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="DescrFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
															</asp:DropDownList></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_CommoditySizeTableControl$DC_CommoditySizeFilterButton")) %>
    
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
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeNewButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" RedirectURL="../DC_CommoditySize/AddDC_CommoditySizePage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeEditButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarEdit.gif" onmouseout="this.src='../Images/ButtonBarEdit.gif'" onmouseover="this.src='../Images/ButtonBarEditOver.gif'" RedirectURL="../DC_CommoditySize/EditDC_CommoditySizePage.aspx?DC_CommoditySize={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeCopyButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarCopy.gif" onmouseout="this.src='../Images/ButtonBarCopy.gif'" onmouseover="this.src='../Images/ButtonBarCopyOver.gif'" RedirectURL="../DC_CommoditySize/AddDC_CommoditySizePage.aspx?DC_CommoditySize={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Copy&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizePDFButton" CausesValidation="False" CommandName="ReportData" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarPDFExport.gif" onmouseout="this.src='../Images/ButtonBarPDFExport.gif'" onmouseover="this.src='../Images/ButtonBarPDFExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PDF&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeExportExcelButton" CausesValidation="False" CommandName="ExportDataExcel" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarExcelExport.gif" onmouseout="this.src='../Images/ButtonBarExcelExport.gif'" onmouseover="this.src='../Images/ButtonBarExcelExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:ExportExcel&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeExportButton" CausesValidation="False" CommandName="ExportData" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarCSVExport.gif" onmouseout="this.src='../Images/ButtonBarCSVExport.gif'" onmouseover="this.src='../Images/ButtonBarCSVExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Export&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CommoditySizeResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_CommoditySizeTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
                <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
                <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_CommoditySizePagination">
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
                    <asp:CheckBox runat="server" id="DC_CommoditySizeToggleAll" onclick="toggleAllCheckboxes(this);">

															</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeIdLabel1" Text="Size" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="DescrLabel1" Text="Descr" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PriceLabel" Text="Price" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeLowLabel" Text="Size Low" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeHighLabel" Text="Size High" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="WeightKGLabel" Text="Weight KG" CausesValidation="False">
															</asp:LinkButton>
                </th>
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_CommoditySizeTableControlRepeater">
																<ITEMTEMPLATE>
																		<ePortDC:DC_CommoditySizeTableControlRow runat="server" id="DC_CommoditySizeTableControlRow">
																				
            <div id="AJAXUpdateRecordRow">
            <tr><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CommoditySizeRecordRowViewButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_view.gif" RedirectURL="../DC_CommoditySize/ShowDC_CommoditySizePage.aspx?DC_CommoditySize={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:ViewRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CommoditySizeRecordRowEditButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_edit.gif" RedirectURL="../DC_CommoditySize/EditDC_CommoditySizePage.aspx?DC_CommoditySize={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:EditRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CommoditySizeRecordRowCopyButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_copy.gif" RedirectURL="../DC_CommoditySize/AddDC_CommoditySizePage.aspx?DC_CommoditySize={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:CopyRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CommoditySizeRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_CommoditySizeTableControlRow" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_CommoditySizeRecordRowSelection">

																				</asp:CheckBox></td>
            <td class="ttc" style="text-align: right;"><asp:Literal runat="server" id="SizeId">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Descr">
																				</asp:Literal>
            </td>
            <td class="ttc" style="text-align: right;"><asp:Literal runat="server" id="Price">
																				</asp:Literal>
            </td>
            <td class="ttc" style="text-align: right;"><asp:Literal runat="server" id="SizeLow">
																				</asp:Literal>
            </td>
            <td class="ttc" style="text-align: right;"><asp:Literal runat="server" id="SizeHigh">
																				</asp:Literal>
            </td>
            <td class="ttc" style="text-align: right;"><asp:Literal runat="server" id="WeightKG">
																				</asp:Literal>
            </td>
            </tr>
            </div>
            
																		</ePortDC:DC_CommoditySizeTableControlRow>
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

											</ePortDC:DC_CommoditySizeTableControl>

										</ContentTemplate>
										<Triggers>
											<asp:PostBackTrigger ControlID="DC_CommoditySizePDFButton"/>
											<asp:PostBackTrigger ControlID="DC_CommoditySizeExportExcelButton"/>
											<asp:PostBackTrigger ControlID="DC_CommoditySizeExportButton"/>
										</Triggers>
									</asp:UpdatePanel>
								
<br/>

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
