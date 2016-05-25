<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="ShowDC_CustomerTablePage.aspx.cs" Inherits="ePortDC.UI.ShowDC_CustomerTablePage" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.ShowDC_CustomerTablePage" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>ShowDC_CustomerTablePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_CustomerMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                            <asp:UpdateProgress runat="server" id="DC_CustomerTableControlUpdateProgress" AssociatedUpdatePanelID="DC_CustomerTableControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_CustomerTableControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_CustomerTableControl runat="server" id="DC_CustomerTableControl">
														
<!-- Begin Table Panel.html -->

<table class="dv" cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
	<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
	<td class="dheci" valign="middle"><a onclick="toggleRegions(this);"><img id="ToggleRegionIcon" src="../Images/ToggleHideFilters.gif" border="0" alt="Hide Filters"/></a></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DC_CustomerTableTitle" Text="Customer">
														</asp:Literal></td>
	<td class="dhtrc">
		<table id="CollapsibleRegionTotalRecords" style="display:none;" cellspacing="0" cellpadding="0" border="0">
		<tr>
		<td class="dhtrct"><%# GetResourceValue("Txt:TotalItems", "ePortDC") %>&nbsp;<asp:Label runat="server" id="DC_CustomerTotalItems">
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
        <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_CustomerTableControl$DC_CustomerSearchButton")) %>
        
        <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_CustomerTableControl$DC_CustomerSearchButton")) %>
        </td>
        <td class="filbc"></td>
    </tr>
    
    
    <tr>
    <td></td>
    <td></td>
    <td rowspan="100" class="filbc"><ePortDC:ThemeButton runat="server" id="DC_CustomerFilterButton" Button-CausesValidation="False" Button-CommandName="Search" Button-Text="&lt;%# GetResourceValue(&quot;Btn:SearchGoButtonText&quot;, &quot;ePortDC&quot;) %>">
															</ePortDC:ThemeButton></td> 
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("DC_CustomerTableControl$DC_CustomerFilterButton")) %>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
															</asp:Literal></td>
        <td><asp:TextBox runat="server" id="CustomerIdFromFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox><span class="rft"><asp:Label runat="server" id="CustomerIdFilterText" Text="to">
															</asp:Label></span><asp:TextBox runat="server" id="CustomerIdToFilter" Columns="30" CssClass="Filter_Input">
															</asp:TextBox></td>
    </tr>
    <tr>
        <td class="fila"><asp:Literal runat="server" id="CustomerShortName1Label" Text="Customer Short Name">
															</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="CustomerShortNameFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
															</asp:DropDownList></td>
    </tr><tr>
        <td class="fila"><asp:Literal runat="server" id="StateProvince1Label" Text="State Province">
															</asp:Literal></td>
        <td><asp:DropDownList runat="server" id="StateProvinceFilter" CssClass="Filter_Input" onkeypress="dropDownListTypeAhead(this,false)" AutoPostBack="True">
															</asp:DropDownList></td>
    </tr>
    <%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("DC_CustomerTableControl$DC_CustomerFilterButton")) %>
    
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
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerNewButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" RedirectURL="../DC_Customer/AddDC_CustomerPage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerEditButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarEdit.gif" onmouseout="this.src='../Images/ButtonBarEdit.gif'" onmouseover="this.src='../Images/ButtonBarEditOver.gif'" RedirectURL="../DC_Customer/EditDC_CustomerPage.aspx?DC_Customer={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Edit&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerCopyButton" CausesValidation="False" CommandName="Redirect" Consumers="Page" ImageURL="../Images/ButtonBarCopy.gif" onmouseout="this.src='../Images/ButtonBarCopy.gif'" onmouseover="this.src='../Images/ButtonBarCopyOver.gif'" RedirectURL="../DC_Customer/AddDC_CustomerPage.aspx?DC_Customer={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Copy&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerPDFButton" CausesValidation="False" CommandName="ReportData" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarPDFExport.gif" onmouseout="this.src='../Images/ButtonBarPDFExport.gif'" onmouseover="this.src='../Images/ButtonBarPDFExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PDF&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerExportExcelButton" CausesValidation="False" CommandName="ExportDataExcel" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarExcelExport.gif" onmouseout="this.src='../Images/ButtonBarExcelExport.gif'" onmouseover="this.src='../Images/ButtonBarExcelExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:ExportExcel&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerExportButton" CausesValidation="False" CommandName="ExportData" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarCSVExport.gif" onmouseout="this.src='../Images/ButtonBarCSVExport.gif'" onmouseover="this.src='../Images/ButtonBarCSVExportOver.gif'" PostBack="True" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Export&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
            <td class="prbbc"><asp:ImageButton runat="server" id="DC_CustomerResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_CustomerTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

															</asp:ImageButton></td>
                <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
                <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
            <td class="pra">
            <ePortDC:Pagination runat="server" id="DC_CustomerPagination">
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
                    <asp:CheckBox runat="server" id="DC_CustomerToggleAll" onclick="toggleAllCheckboxes(this);">

															</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerIdLabel1" Text="Customer" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerShortNameLabel" Text="Customer Short Name" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="CustomerNameLabel" Text="Customer Name" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="AddressLabel1" Text="Address" CausesValidation="False">
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
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneLabel" Text="Phone" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PhoneMobileLabel" Text="Phone Mobile" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="NeedPARSLabel" Text="Need PARS" CausesValidation="False">
															</asp:LinkButton>
                </th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OriginLabel" Text="Origin" CausesValidation="False">
															</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="DestCodeLabel" Text="Destination Code" CausesValidation="False">
															</asp:LinkButton>
                &nbsp;</th><th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel" Text="Comments" CausesValidation="False">
															</asp:LinkButton>
                </th>
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_CustomerTableControlRepeater">
																<ITEMTEMPLATE>
																		<ePortDC:DC_CustomerTableControlRow runat="server" id="DC_CustomerTableControlRow">
																				
            <div id="AJAXUpdateRecordRow">
            <tr><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CustomerRecordRowViewButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_view.gif" RedirectURL="../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:ViewRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CustomerRecordRowEditButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_edit.gif" RedirectURL="../DC_Customer/EditDC_CustomerPage.aspx?DC_Customer={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:EditRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CustomerRecordRowCopyButton" CausesValidation="False" CommandName="Redirect" CssClass="button_link" ImageURL="../Images/icon_copy.gif" RedirectURL="../DC_Customer/AddDC_CustomerPage.aspx?DC_Customer={PK}" ToolTip="&lt;%# GetResourceValue(&quot;Txt:CopyRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" scope="row"><asp:ImageButton runat="server" id="DC_CustomerRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_CustomerTableControlRow" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																				</asp:ImageButton></td><td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_CustomerRecordRowSelection">

																				</asp:CheckBox></td>
            <td class="ttc" style=";;;text-align:right"><asp:Literal runat="server" id="CustomerId">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="CustomerShortName">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="CustomerName">
																				</asp:Literal>
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
            <td class="ttc" ><asp:Literal runat="server" id="Phone">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="PhoneMobile">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="NeedPARS">
																				</asp:Literal>
            </td>
            <td class="ttc" ><asp:Literal runat="server" id="Origin">
																				</asp:Literal></td>
            <td class="ttc" ><asp:Literal runat="server" id="DestCode">
																				</asp:Literal>
            &nbsp;</td><td class="ttc" ><asp:Literal runat="server" id="Comments">
																				</asp:Literal>
            </td>
            </tr>
            </div>
            
																		</ePortDC:DC_CustomerTableControlRow>
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

											</ePortDC:DC_CustomerTableControl>

										</ContentTemplate>
										<Triggers>
											<asp:PostBackTrigger ControlID="DC_CustomerPDFButton"/>
											<asp:PostBackTrigger ControlID="DC_CustomerExportExcelButton"/>
											<asp:PostBackTrigger ControlID="DC_CustomerExportButton"/>
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
