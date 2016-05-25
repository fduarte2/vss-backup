<%@ Register Tagprefix="ePortDC" TagName="Pagination" Src="../Shared/Pagination.ascx" %>

<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.AddDC_OrderPage" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="AddDC_OrderPage.aspx.cs" Inherits="ePortDC.UI.AddDC_OrderPage" %>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>AddDC_OrderPage</title>
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
                        <td class="pContent"><span style='font-size:10.0pt;font-family:Arial;color:blue'>&nbsp;<asp:Label runat="server" id="MessageLabel">
		</asp:Label></span><a name="StartOfPageContent"></a>
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
<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("CancelButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("OKButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("SaveNNewButton$_Button")) %><table class="dialog_view" cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dh">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <tr>
    <td class="dhel"><img src="../Images/space.gif" alt=""/></td>
    <td class="dheci" valign="middle"><a onclick="toggleExpandCollapse(this);"><img id="ExpandCollapseIcon" src="../Images/DialogHeaderIconCollapse.gif" border="0" alt="Collapse panel"/></a></td>
    <td class="dhb">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_OrderDialogTitle" Text="Add Order">
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
    <td class="field_label_on_side"><asp:Literal runat="server" id="VesselIdLabel" Text="Vessel">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:DropDownList runat="server" id="VesselId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="VesselIdFvLlsHyperLink" ControlToUpdate="VesselId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Vessel" Field="DC_Vessel_.VesselId" DisplayField="DC_Vessel_.VesselName">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="VesselIdRequiredFieldValidator" ControlToValidate="VesselId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Vessel&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
<asp:Label runat="server" id="ePortVesselCookie" Text="VesselCookieNotSet">
														</asp:Label></td><td class="field_label_on_side"><asp:Literal runat="server" id="OrderNumLabel" Text="Order Number">
														</asp:Literal></td><td class="dialog_field_value"><asp:TextBox runat="server" id="OrderNum" Columns="12" MaxLength="12" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="OrderNumRequiredFieldValidator" ControlToValidate="OrderNum" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Number&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="OrderNumTextBoxMaxLengthValidator" ControlToValidate="OrderNum" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Number&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CommodityCodeLabel" Text="Commodity Code">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:DropDownList runat="server" id="CommodityCode" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="CommodityCodeFvLlsHyperLink" ControlToUpdate="CommodityCode" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Commodity" Field="DC_Commodity_.CommodityCode" DisplayField="DC_Commodity_.CommodityName">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CommodityCodeRequiredFieldValidator" ControlToValidate="CommodityCode" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Code&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator></td><td class="field_label_on_side"><asp:Literal runat="server" id="OrderStatusIdLabel" Text="Order Status">
														</asp:Literal></td><td class="dialog_field_value"><b><asp:Label runat="server" id="OrderStatusInfoLabel" Text="**">
														</asp:Label>
<asp:Label runat="server" id="OrderStatusSubmitLabel">
														</asp:Label></b>
<asp:DropDownList runat="server" id="OrderStatusId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="OrderStatusIdFvLlsHyperLink" ControlToUpdate="OrderStatusId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_OrderStatus" Field="DC_OrderStatus_.OrderStatusId" DisplayField="DC_OrderStatus_.Descr">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="OrderStatusIdRequiredFieldValidator" ControlToValidate="OrderStatusId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Status&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:DropDownList runat="server" id="CustomerId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="CustomerIdFvLlsHyperLink" ControlToUpdate="CustomerId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Customer" Field="DC_Customer_.CustomerId" DisplayField="DC_Customer_.CustomerName">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CustomerIdRequiredFieldValidator" ControlToValidate="CustomerId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customer&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
<asp:Label runat="server" id="CustomerInfoLabel" Text="**">
														</asp:Label></td><td class="field_label_on_side"><asp:Literal runat="server" id="DirectOrderLabel" Text="Direct Order">
														</asp:Literal></td><td class="dialog_field_value"><asp:DropDownList runat="server" id="DirectOrder" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
																<asp:ListItem Value="DIRECT">
																</asp:ListItem><asp:ListItem Value="OTHER">
																</asp:ListItem>
														</asp:DropDownList></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="ConsigneeIdLabel" Text="Consignee">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:DropDownList runat="server" id="ConsigneeId" CssClass="field_input" onkeypress="dropDownListTypeAhead(this,false)">

														</asp:DropDownList>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="ConsigneeIdRequiredFieldValidator" ControlToValidate="ConsigneeId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Consignee&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
<asp:Label runat="server" id="ConsigneeInfoLabel" Text="**">
														</asp:Label><asp:ImageButton runat="server" id="ConsigneeIdAddRecordLink" CausesValidation="False" CommandName="Redirect" Consumers="page" ControlToUpdate="CustomerId" FieldValue="CustomerId" ImageURL="../Images/iconNewFlat.gif" RedirectURL="../DC_Consignee/AddDC_ConsigneePage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:New&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

														</asp:ImageButton></td><td class="field_label_on_side"><asp:Literal runat="server" id="TEStatusLabel" Text="TE Status">
														</asp:Literal></td><td class="dialog_field_value"><asp:DropDownList runat="server" id="TEStatus" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
																<asp:ListItem Value="PENDING">
																</asp:ListItem><asp:ListItem Value="RECEIVED">
																</asp:ListItem>
														</asp:DropDownList></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="CustomerPOLabel" Text="Customer PO">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:TextBox runat="server" id="CustomerPO" Columns="15" MaxLength="15" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CustomerPOTextBoxMaxLengthValidator" ControlToValidate="CustomerPO" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customer PO&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td><td class="field_label_on_side"><asp:Literal runat="server" id="SNMGNumLabel" Text="SNMG Number">
														</asp:Literal></td><td class="dialog_field_value"><asp:TextBox runat="server" id="SNMGNum" Columns="50" MaxLength="50" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="SNMGNumTextBoxMaxLengthValidator" ControlToValidate="SNMGNum" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;SNMG Number&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="PickUpDateLabel" Text="Pick Up Date">
														</asp:Literal></td>
    <td class="dialog_field_value"><table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="PickUpDate" Columns="20" MaxLength="20" onkeyup="DateFormat(this, this.value, event.keyCode, 'mm/dd/yyyy')" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("PickUpDate"),"DateTextBox","mm/dd/yyyy","","") %>
														</td>
														<td style="padding-right: 5px">
														<Selectors:FvDsHyperLink runat="server" id="PickUpDateFvDsHyperLink" ControlToUpdate="PickUpDate" Text="&lt;%# GetResourceValue(&quot;DS:Text&quot;, &quot;ePortDC&quot;) %>" Format="d">														</Selectors:FvDsHyperLink>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PickUpDateTextBoxMaxLengthValidator" ControlToValidate="PickUpDate" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Pick Up Date&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														</td><td class="field_label_on_side"><asp:Literal runat="server" id="LoadTypeLabel" Text="Load Type">
														</asp:Literal></td><td class="dialog_field_value"><asp:DropDownList runat="server" id="LoadType" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
																<asp:ListItem Value="CUSTOMER LOAD">
																</asp:ListItem><asp:ListItem Value="REGRADE LOAD">
																</asp:ListItem><asp:ListItem Value="HOSPITAL LOAD">
																</asp:ListItem>
														</asp:DropDownList></td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="DeliveryDateLabel" Text="Delivery Date">
														</asp:Literal></td>
    <td class="dialog_field_value"><table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="DeliveryDate" Columns="20" MaxLength="20" onkeyup="DateFormat(this, this.value, event.keyCode, 'mm/dd/yyyy')" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("DeliveryDate"),"DateTextBox","mm/dd/yyyy","","") %>
														</td>
														<td style="padding-right: 5px">
														<Selectors:FvDsHyperLink runat="server" id="DeliveryDateFvDsHyperLink" ControlToUpdate="DeliveryDate" Text="&lt;%# GetResourceValue(&quot;DS:Text&quot;, &quot;ePortDC&quot;) %>" Format="d">														</Selectors:FvDsHyperLink>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="DeliveryDateTextBoxMaxLengthValidator" ControlToValidate="DeliveryDate" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Delivery Date&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														</td><td class="field_label_on_side"><asp:Literal runat="server" id="CommentsLabel" Text="Comments">
														</asp:Literal></td><td class="dialog_field_value"><asp:TextBox runat="server" id="Comments" Columns="50" MaxLength="50" CssClass="field_input">
														</asp:TextBox>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="CommentsTextBoxMaxLengthValidator" ControlToValidate="Comments" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Comments&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="TransporterIdLabel" Text="Transporter">
														</asp:Literal></td>
    <td class="dialog_field_value"><asp:DropDownList runat="server" id="TransporterId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="TransporterIdFvLlsHyperLink" ControlToUpdate="TransporterId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Transporter" Field="DC_Transporter_.TransporterId" DisplayField="DC_Transporter_.CarrierName">														</Selectors:FvLlsHyperLink><asp:ImageButton runat="server" id="TransporterIdAddRecordLink" CausesValidation="False" CommandName="Redirect" Consumers="page" ControlToUpdate="TransporterId" FieldValue="TransporterId" ImageURL="../Images/iconNewFlat.gif" RedirectURL="../DC_Transporter/AddDC_TransporterPage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:New&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

														</asp:ImageButton></td><td class="field_label_on_side"><asp:Label runat="server" id="BorderCrossingLabel" Text="Border Crossing">
														</asp:Label></td><td class="dialog_field_value"><asp:Label runat="server" id="BorderCrossingInfoLabel" Text="**">
														</asp:Label></td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="TransportChargesLabel" Text="Transport Charges">
														</asp:Literal></td>
    <td class="dialog_field_value"><table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="TransportCharges" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("TransportCharges"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="TransportChargesTextBoxMaxLengthValidator" ControlToValidate="TransportCharges" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Transport Charges&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														</td><td class="field_label_on_side"><asp:Label runat="server" id="CustomerBrokerLabel" Text="Customs Broker">
														</asp:Label></td><td class="dialog_field_value"><asp:Label runat="server" id="CustomsBrokerInfoLabel" Text="**">
														</asp:Label></td>
    </tr><tr>
    <td class="field_label_on_side"><asp:Label runat="server" id="FixedFreightLabel" Text="Fixed Freight (per Load)">
														</asp:Label></td>
    <td class="dialog_field_value"><asp:Label runat="server" id="FixedFreightInfoLabel" Text="**">
														</asp:Label></td><td class="field_label_on_side">&nbsp;</td><td class="dialog_field_value">&nbsp;</td>
    </tr>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
                </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
 </table><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("OKButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("CancelButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("SaveButton$_Button")) %><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("SaveNNewButton$_Button")) %><!-- End Record Panel.html -->
											</ePortDC:DC_OrderRecordControl>
											</ContentTemplate>
										</asp:UpdatePanel>
									
<br/><asp:UpdateProgress runat="server" id="DC_OrderDetailTableControlUpdateProgress" AssociatedUpdatePanelID="DC_OrderDetailTableControlUpdatePanel">
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
            
            <td class="prbbc"><img src="../Images/ButtonBarEdgeL.gif"></td>
            <td class="prbbc"><img src="../Images/ButtonBarDividerL.gif"></td>
            
            <td class="prbbc">
            <asp:ImageButton runat="server" id="DC_OrderDetailAddButton" CausesValidation="False" CommandName="AddRecord" Consumers="DC_OrderDetailTableControl" ImageURL="../Images/ButtonBarNew.gif" onmouseout="this.src='../Images/ButtonBarNew.gif'" onmouseover="this.src='../Images/ButtonBarNewOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Add&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton>
            </td>
            
            <td class="prbbc">
            
            </td>
            
            <td class="prbbc">
            <asp:ImageButton runat="server" id="DC_OrderDetailDeleteButton" CausesValidation="False" CommandArgument="DeleteOnUpdate" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_OrderDetailTableControl" ImageURL="../Images/ButtonBarDelete.gif" onmouseout="this.src='../Images/ButtonBarDelete.gif'" onmouseover="this.src='../Images/ButtonBarDeleteOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Delete&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton>
            </td>
            
            <td class="prbbc">
            <asp:ImageButton runat="server" id="DC_OrderDetailRefreshButton" CausesValidation="False" CommandName="ResetData" Consumers="DC_OrderDetailTableControl" ImageURL="../Images/ButtonBarRefresh.gif" onmouseout="this.src='../Images/ButtonBarRefresh.gif'" onmouseover="this.src='../Images/ButtonBarRefreshOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Refresh&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton>
            </td>
            
            <td class="prbbc">
            <asp:ImageButton runat="server" id="DC_OrderDetailResetButton" CausesValidation="False" CommandName="ResetFilters" Consumers="DC_OrderDetailTableControl" ImageURL="../Images/ButtonBarReset.gif" onmouseout="this.src='../Images/ButtonBarReset.gif'" onmouseover="this.src='../Images/ButtonBarResetOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:Reset&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																						</asp:ImageButton>
            </td>
            
            <td class="prbbc"><img src="../Images/ButtonBarDividerR.gif"></td>
            <td class="prbbc"><img src="../Images/ButtonBarEdgeR.gif"></td>
            
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
            <th class="thc" COLSPAN="1"><img src="../Images/space.gif" height="1" width="1" alt=""/></th>
            <th class="thc" style="padding:0px;vertical-align:middle;"><asp:CheckBox runat="server" id="DC_OrderDetailToggleAll" onclick="toggleAllCheckboxes(this);">

																						</asp:CheckBox></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderSizeIdLabel1" Text="Order Size" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="OrderQtyLabel" Text="Order Quantity (Cartons)" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="PriceLabel" Text="Price" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeLowLabel" Text="Size Low" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="SizeHighLabel" Text="Size High" CausesValidation="False">
																						</asp:LinkButton></th>
            
            <th class="thc" scope="col"><asp:LinkButton runat="server" id="WeightKGLabel" Text="Weight (KG/Carton)" CausesValidation="False">
																						</asp:LinkButton></th><th class="thc" scope="col"><asp:LinkButton runat="server" id="CommentsLabel2" Text="Comments" CausesValidation="False">
																						</asp:LinkButton>&nbsp;</th>
            
            </tr>
            </div>
            
            <!-- Table Rows -->
            <asp:Repeater runat="server" id="DC_OrderDetailTableControlRepeater">
																							<ITEMTEMPLATE>
																									<ePortDC:DC_OrderDetailTableControlRow runat="server" id="DC_OrderDetailTableControlRow">
																											
            <div id="AJAXUpdateRecordRow">
            <tr>
            
            
            <td class="tic"><asp:ImageButton runat="server" id="DC_OrderDetailRecordRowDeleteButton" CausesValidation="False" CommandName="DeleteRecord" ConfirmMessage="&lt;%# GetResourceValue(&quot;DeleteRecordConfirm&quot;, &quot;ePortDC&quot;) %>" Consumers="DC_OrderDetailTableControl" ImageURL="../Images/icon_delete.gif" ToolTip="&lt;%# GetResourceValue(&quot;Txt:DeleteRecord&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

																											</asp:ImageButton></td>
            <td class="tic" onclick="moveToThisTableRow(this);"><asp:CheckBox runat="server" id="DC_OrderDetailRecordRowSelection">

																											</asp:CheckBox></td>
            
            <td class="ttc" ><asp:DropDownList runat="server" id="OrderSizeId" CssClass="field_input" onkeypress="dropDownListTypeAhead(this,false)">
																											</asp:DropDownList>
																											<Selectors:FvLlsHyperLink runat="server" id="OrderSizeIdFvLlsHyperLink" ControlToUpdate="OrderSizeId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" Table="DC_CommoditySize" Field="DC_CommoditySize_.SizeId" DisplayField="DC_CommoditySize_.Descr">																											</Selectors:FvLlsHyperLink>&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="OrderSizeIdRequiredFieldValidator" ControlToValidate="OrderSizeId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Size&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">																											</asp:RequiredFieldValidator></td>
            
            <td class="ttc" style=";;;;text-align:right"><table border="0" cellpadding="0" cellspacing="0">
																											<tr>
																											<td style="padding-right: 5px; vertical-align:top">
																											<asp:TextBox runat="server" id="OrderQty" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input">
																											</asp:TextBox></td>
																											<td style="padding-right: 5px; white-space:nowrap;">
																											<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("OrderQty"),"NumberTextBox","","","") %>
																											&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="OrderQtyRequiredFieldValidator" ControlToValidate="OrderQty" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Quantity&quot;) %>" Enabled="True" Text="*">																											</asp:RequiredFieldValidator>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="OrderQtyTextBoxMaxLengthValidator" ControlToValidate="OrderQty" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Order Quantity&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator></td>
																											</tr>
																											</table>
																											</td>
            
            <td class="ttc"  style="text-align:right"><table border="0" cellpadding="0" cellspacing="0">
																											<tr>
																											<td style="padding-right: 5px; vertical-align:top">
																											<asp:TextBox runat="server" id="Price" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input">
																											</asp:TextBox></td>
																											<td style="padding-right: 5px; white-space:nowrap;">
																											<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Price"),"CurrencyTextBox","$",".","False") %>
																											&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="PriceRequiredFieldValidator" ControlToValidate="Price" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Price&quot;) %>" Enabled="True" Text="*">																											</asp:RequiredFieldValidator>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PriceTextBoxMaxLengthValidator" ControlToValidate="Price" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Price&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator></td>
																											</tr>
																											</table>
																											</td>
            
            <td class="ttc" style=";;;;text-align:right"><table border="0" cellpadding="0" cellspacing="0">
																											<tr>
																											<td style="padding-right: 5px; vertical-align:top">
																											<asp:TextBox runat="server" id="SizeLow" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input">
																											</asp:TextBox></td>
																											<td style="padding-right: 5px; white-space:nowrap;">
																											<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("SizeLow"),"NumberTextBox","","","") %>
																											&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="SizeLowRequiredFieldValidator" ControlToValidate="SizeLow" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Size Low&quot;) %>" Enabled="True" Text="*">																											</asp:RequiredFieldValidator>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="SizeLowTextBoxMaxLengthValidator" ControlToValidate="SizeLow" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Size Low&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator></td>
																											</tr>
																											</table>
																											</td>
            
            <td class="ttc" style=";;;;text-align:right"><table border="0" cellpadding="0" cellspacing="0">
																											<tr>
																											<td style="padding-right: 5px; vertical-align:top">
																											<asp:TextBox runat="server" id="SizeHigh" Columns="7" MaxLength="7" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input">
																											</asp:TextBox></td>
																											<td style="padding-right: 5px; white-space:nowrap;">
																											<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("SizeHigh"),"NumberTextBox","","","") %>
																											&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="SizeHighRequiredFieldValidator" ControlToValidate="SizeHigh" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Size High&quot;) %>" Enabled="True" Text="*">																											</asp:RequiredFieldValidator>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="SizeHighTextBoxMaxLengthValidator" ControlToValidate="SizeHigh" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Size High&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator></td>
																											</tr>
																											</table>
																											</td>
            
            <td class="ttc" ><table border="0" cellpadding="0" cellspacing="0">
																											<tr>
																											<td style="padding-right: 5px; vertical-align:top">
																											<asp:TextBox runat="server" id="WeightKG" Columns="16" MaxLength="16" onkeyup="adjustInteger(this, event.keyCode)" CssClass="field_input">
																											</asp:TextBox></td>
																											<td style="padding-right: 5px; white-space:nowrap;">
																											<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("WeightKG"),"NumberTextBox","","","") %>
																											&nbsp;
																											<asp:RequiredFieldValidator runat="server" id="WeightKGRequiredFieldValidator" ControlToValidate="WeightKG" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Weight KG&quot;) %>" Enabled="True" Text="*">																											</asp:RequiredFieldValidator>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="WeightKGTextBoxMaxLengthValidator" ControlToValidate="WeightKG" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Weight KG&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator></td>
																											</tr>
																											</table>
																											</td><td class="ttc"  ><asp:TextBox runat="server" id="Comments1" Columns="50" MaxLength="50" CssClass="field_input">
																											</asp:TextBox>&nbsp;
																											<BaseClasses:TextBoxMaxLengthValidator runat="server" id="Comments1TextBoxMaxLengthValidator" ControlToValidate="Comments1" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Comments&quot;) %>">																											</BaseClasses:TextBoxMaxLengthValidator>&nbsp;</td>
            
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
</table><!-- End Table Panel.html -->
																		</ePortDC:DC_OrderDetailTableControl>

																	</ContentTemplate>
																</asp:UpdatePanel>
															
<br/>
        
        
        
    <table cellpadding="0" cellspacing="0" border="0" id="Table1">
    <tr>
    <td class="recordPanelButtonsAlignment">
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td></td>
    
    <td></td>
    
    <td><ePortDC:ThemeButton runat="server" id="CancelButton" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="ShowDC_OrderTablePage.aspx" Button-Text="&lt;%# GetResourceValue(&quot;Btn:Cancel&quot;, &quot;ePortDC&quot;) %>">
																</ePortDC:ThemeButton></td><td>&nbsp;</td><td><ePortDC:ThemeButton runat="server" id="SaveButton" Button-CausesValidation="True" Button-CommandName="UpdateData" Button-RedirectURL="ShowDC_OrderTablePage.aspx" Button-Text="&lt;%# GetResourceValue(&quot;Btn:Save&quot;, &quot;ePortDC&quot;) %>">
																</ePortDC:ThemeButton></td><td>&nbsp;</td>
    
    <td></td>
    
	<td><ePortDC:ThemeButton runat="server" id="SubmitButton" Button-CausesValidation="True" Button-CommandName="UpdateData" Button-RedirectURL="ShowDC_OrderTablePage.aspx" Button-Text="Submit">
																</ePortDC:ThemeButton></td>
    
    </tr>
    </table>
    </td>
    </tr>
</table><div id="detailPopup" style="z-index:2; visibility:visible; position:absolute;"></div><div id="detailPopupDropShadow" class="detailPopupDropShadow" style="z-index:1; visibility:visible; position:absolute;"></div></td>
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
