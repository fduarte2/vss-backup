<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="EditDC_CustomerPricePage.aspx.cs" Inherits="ePortDC.UI.EditDC_CustomerPricePage" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.EditDC_CustomerPricePage" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head id="Head1" runat="server">
    <title>EditDC_CustomerPricePage</title>
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
                        <ePortDC:Menu runat="server" id="Menu" HiliteSettings="DC_CustomerPriceMenuItem">
		</ePortDC:Menu>
                    </td>
                    </tr>
                    <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tr>
                        <td class="pContent">
                            <a name="StartOfPageContent"></a>
                <asp:UpdateProgress runat="server" id="DC_CustomerPriceRecordControlUpdateProgress" AssociatedUpdatePanelID="DC_CustomerPriceRecordControlUpdatePanel">
										<ProgressTemplate>
											<div style="position:absolute;   width:100%;height:1000px;background-color:#000000;filter:alpha(opacity=10);opacity:0.20;-moz-opacity:0.20;padding:20px;">
											</div>
											<div style=" position:absolute; padding:30px;">
												<img src="../Images/updating.gif">
											</div>
										</ProgressTemplate>
									</asp:UpdateProgress>
									<asp:UpdatePanel runat="server" id="DC_CustomerPriceRecordControlUpdatePanel" UpdateMode="Conditional">

										<ContentTemplate>
											<ePortDC:DC_CustomerPriceRecordControl runat="server" id="DC_CustomerPriceRecordControl">
														
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
    <td class="dhtr" valign="middle"><asp:Literal runat="server" id="DC_CustomerPriceDialogTitle" Text="Edit Customer Price">
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
        <asp:Literal runat="server" id="CustomerIdLabel" Text="Customer">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:DropDownList runat="server" id="CustomerId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="CustomerIdFvLlsHyperLink" ControlToUpdate="CustomerId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Customer" Field="DC_Customer_.CustomerId" DisplayField="DC_Customer_.CustomerName">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CustomerIdRequiredFieldValidator" ControlToValidate="CustomerId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Customer&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
    
    <asp:ImageButton runat="server" id="CustomerIdAddRecordLink" CausesValidation="False" CommandName="Redirect" Consumers="page" ControlToUpdate="CustomerId" FieldValue="CustomerId" ImageURL="../Images/iconNewFlat.gif" RedirectURL="../DC_Customer/AddDC_CustomerPage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:New&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

														</asp:ImageButton>
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="CommodityCodeLabel" Text="Commodity Code">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:DropDownList runat="server" id="CommodityCode" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="CommodityCodeFvLlsHyperLink" ControlToUpdate="CommodityCode" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_Commodity" Field="DC_Commodity_.CommodityCode" DisplayField="DC_Commodity_.CommodityName">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="CommodityCodeRequiredFieldValidator" ControlToValidate="CommodityCode" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Commodity Code&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="SizeIdLabel" Text="Size">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <asp:DropDownList runat="server" id="SizeId" CssClass="field_input" EnableIncrementDecrementButtons="True" onkeypress="dropDownListTypeAhead(this,false)">
														</asp:DropDownList>
														<Selectors:FvLlsHyperLink runat="server" id="SizeIdFvLlsHyperLink" ControlToUpdate="SizeId" Text="&lt;%# GetResourceValue(&quot;LLS:Text&quot;, &quot;ePortDC&quot;) %>" MinListItems="100" Table="DC_CommoditySize" Field="DC_CommoditySize_.SizeId" DisplayField="DC_CommoditySize_.Descr">														</Selectors:FvLlsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="SizeIdRequiredFieldValidator" ControlToValidate="SizeId" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Size&quot;) %>" Enabled="True" InitialValue="--PLEASE_SELECT--" Text="*">														</asp:RequiredFieldValidator>
    
    <asp:ImageButton runat="server" id="SizeIdAddRecordLink" CausesValidation="False" CommandName="Redirect" Consumers="page" ControlToUpdate="SizeId" FieldValue="SizeId" ImageURL="../Images/iconNewFlat.gif" RedirectURL="../DC_CommoditySize/AddDC_CommoditySizePage.aspx" ToolTip="&lt;%# GetResourceValue(&quot;Btn:New&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

														</asp:ImageButton>
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side">
        <asp:Literal runat="server" id="EffectiveDateLabel" Text="Effective Date">
														</asp:Literal>
    </td>
    <td class="dialog_field_value">
        <table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="EffectiveDate" Columns="20" MaxLength="20" onkeyup="DateFormat(this, this.value, event.keyCode, 'mm/dd/yyyy')" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("EffectiveDate"),"DateTextBox","mm/dd/yyyy","","") %>
														</td>
														<td style="padding-right: 5px">
														<Selectors:FvDsHyperLink runat="server" id="EffectiveDateFvDsHyperLink" ControlToUpdate="EffectiveDate" Text="&lt;%# GetResourceValue(&quot;DS:Text&quot;, &quot;ePortDC&quot;) %>" Format="d">														</Selectors:FvDsHyperLink>&nbsp;
														<asp:RequiredFieldValidator runat="server" id="EffectiveDateRequiredFieldValidator" ControlToValidate="EffectiveDate" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Effective Date&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="EffectiveDateTextBoxMaxLengthValidator" ControlToValidate="EffectiveDate" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Effective Date&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														
    
    </td>
    </tr>
    <tr>
    <td class="field_label_on_side"><asp:Literal runat="server" id="PriceLabel" Text="Price">
														</asp:Literal></td>
    <td class="dialog_field_value"><table border="0" cellpadding="0" cellspacing="0">
														<tr>
														<td style="padding-right: 5px; vertical-align:top">
														<asp:TextBox runat="server" id="Price" Columns="20" MaxLength="20" onkeyup="adjustCurrency(this, event.keyCode,'$', '.', 'False');" CssClass="field_input" EnableIncrementDecrementButtons="True">
														</asp:TextBox></td>
														<td style="padding-right: 5px; white-space:nowrap;">
														<%# SystemUtils.GenerateIncrementDecrementButtons(true, Container.FindControl("Price"),"CurrencyTextBox","$",".","False") %>
														&nbsp;
														<asp:RequiredFieldValidator runat="server" id="PriceRequiredFieldValidator" ControlToValidate="Price" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueIsRequired&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Price&quot;) %>" Enabled="True" Text="*">														</asp:RequiredFieldValidator>&nbsp;
														<BaseClasses:TextBoxMaxLengthValidator runat="server" id="PriceTextBoxMaxLengthValidator" ControlToValidate="Price" ErrorMessage="&lt;%# GetResourceValue(&quot;Val:ValueTooLong&quot;, &quot;ePortDC&quot;).Replace(&quot;{FieldName}&quot;, &quot;Price&quot;) %>">														</BaseClasses:TextBoxMaxLengthValidator></td>
														</tr>
														</table>
														</td>
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

											</ePortDC:DC_CustomerPriceRecordControl>
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
