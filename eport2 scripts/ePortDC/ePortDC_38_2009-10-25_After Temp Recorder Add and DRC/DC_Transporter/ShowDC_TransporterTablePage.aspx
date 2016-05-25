<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_TransporterTablePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_TransporterTablePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_TransporterTablePage" %>
<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_TransporterTablePage</title>
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
                            <asp:UpdateProgress runat="server" id="DC_TransporterTableControlUpdateProgress" AssociatedUpdatePanelID="DC_TransporterTableControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_TransporterTableControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_TransporterTableControl runat="server" id="DC_TransporterTableControl">
														
<!-- Begin Table Panel.html -->

<table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_TransporterTableTitle" Text="Transporter">
														</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_TransporterTotalItems">
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
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_TransporterTableControl$DC_TransporterSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_TransporterTableControl$DC_TransporterSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"><ePortDC:ThemeButton runat="server" id="DC_TransporterFilterButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
															</ePortDC:ThemeButton></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_TransporterTableControl$DC_TransporterFilterButton")) %>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="TransporterIdLabel" Text="Transporter">
															</asp:Literal></td>
        <td><asp:TextBox runat="server" id="TransporterIdFromFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox><span class="rft"><asp:Label runat="server" id="TransporterIdFilterText" Text="to">
															</asp:Label></span><asp:TextBox runat="server" id="TransporterIdToFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox></td>
    </tr>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="CarrierNameLabel" Text="Carrier Name">
															</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="CarrierNameFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
															</asp:DropDownList></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_TransporterTableControl$DC_TransporterFilterButton")) %>
    
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
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterNewButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" RedirectURL="../DC_Transporter/AddDC_TransporterPage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterEditButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarEdit.gif" onmouseout="this.src='../Images/ButtonBarEdit.gif'" onmouseover="this.src='../Images/ButtonBarEditOver.gif'" RedirectURL="../DC_Transporter/EditDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterCopyButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarCopy.gif" onmouseout="this.src='../Images/ButtonBarCopy.gif'" onmouseover="this.src='../Images/ButtonBarCopyOver.gif'" RedirectURL="../DC_Transporter/AddDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Copy&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterPDFButton" CausesValidation="False" CommandName="ReportData" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarPDFExport.gif" onmouseout="this.src='../Images/ButtonBarPDFExport.gif'" onmouseover="this.src='../Images/ButtonBarPDFExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PDF&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterExportExcelButton" CausesValidation="False" CommandName="ExportDataExcel" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarExcelExport.gif" onmouseout="this.src='../Images/ButtonBarExcelExport.gif'" onmouseover="this.src='../Images/ButtonBarExcelExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:ExportExcel&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterExportButton" CausesValidation="False" CommandName="ExportData" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarCSVExport.gif" onmouseout="this.src='../Images/ButtonBarCSVExport.gif'" onmouseover="this.src='../Images/ButtonBarCSVExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Export&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_TransporterResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_TransporterTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
                <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
                <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_TransporterPagination">
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
                    <asp:CheckBox runat="server" id="DC_TransporterToggleAll" onclick="toggleAllCheckboxes(this);">

															</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="TransporterIdLabel1" Text="Transporter" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CarrierNameLabel1" Text="Carrier Name" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="ContactNameLabel" Text="Contact Name" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="EmailLabel" Text="Email" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="FaxLabel" Text="Fax" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="IRSNumLabel" Text="IRS Number" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Phone1Label" Text="Phone 1" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Phone2Label" Text="Phone 2" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneCell1Label" Text="Phone Cell 1" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneCell2Label" Text="Phone Cell 2" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate1GTAMiltonWhitbyLabel" Text="Rate 1 GTA Milton Whitby" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate2CambridgeLabel" Text="Rate 2 Cambridge" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate3OttawaLabel" Text="Rate 3 Ottawa" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate4MontrealLabel" Text="Rate 4 Montreal" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate5QuebecLabel" Text="Rate 5 Quebec" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate6MonctonLabel" Text="Rate 6 Moncton" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate7DebertLabel" Text="Rate 7 Debert" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="Rate8OtherLabel" Text="Rate 8 Other" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="USBondNumLabel" Text="US Bond Number" CausesValidation="False">
															</asp:LinkButton>
                </th><th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel" Text="Comments" CausesValidation="False">
															</asp:LinkButton></th>
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_TransporterTableControlRepeater">
																<ITEMTEMPLATE>
																		<ePortDC:DC_TransporterTableControlRow runat="server" id="DC_TransporterTableControlRow">
																				
            <div id="AJAXUpdateRecordRow">
            <tr><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_TransporterRecordRowViewButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_view.gif" RedirectURL="../DC_Transporter/ShowDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:ViewRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_TransporterRecordRowEditButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_edit.gif" RedirectURL="../DC_Transporter/EditDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:EditRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_TransporterRecordRowCopyButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_copy.gif" RedirectURL="../DC_Transporter/AddDC_TransporterPage.aspx?DC_Transporter={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:CopyRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_TransporterRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_TransporterTableControlRow" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_TransporterRecordRowSelection">

																				</asp:CheckBox></td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="TransporterId">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="CarrierName">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="ContactName">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Email">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Fax">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="IRSNum">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Phone1">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Phone2">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="PhoneCell1">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="PhoneCell2">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate1GTAMiltonWhitby">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate2Cambridge">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate3Ottawa">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate4Montreal">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate5Quebec">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate6Moncton">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate7Debert">
																				</asp:Literal>
            </td>
            <td class="ttc" style=";;text-align:right"><asp:Literal runat="server" id="Rate8Other">
																				</asp:Literal>
            </td>
            
            <td class="ttc" ><asp:Literal runat="server" id="USBondNum">
																				</asp:Literal>
            </td><td class="ttc" ><asp:Literal runat="server" id="Comments">
																				</asp:Literal></td>
            </tr>
            </div>
            
																		</ePortDC:DC_TransporterTableControlRow>
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

											</ePortDC:DC_TransporterTableControl>

										</ContentTemplate>
										<Triggers>
											<asp:PostBackTrigger ControlID="DC_TransporterPDFButton"/>
											<asp:PostBackTrigger ControlID="DC_TransporterExportExcelButton"/>
											<asp:PostBackTrigger ControlID="DC_TransporterExportButton"/>
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
