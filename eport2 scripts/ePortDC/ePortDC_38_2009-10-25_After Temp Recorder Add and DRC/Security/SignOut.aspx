<%@ Register Tagprefix="ePortDC" TagName="Menu" Src="../Menu Panels/Menu.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="ThemeButton" Src="../Shared/ThemeButton.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Page Language="C#" EnableEventValidation="false" AutoEventWireup="false" CodeFile="SignOut.aspx.cs" Inherits="ePortDC.UI.SignOut" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<%@ Register Tagprefix="ePortDC" TagName="Header" Src="../Header and Footer/Header.ascx" %>

<%@ Register Tagprefix="ePortDC" TagName="Footer" Src="../Header and Footer/Footer.ascx" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head id="Head1" runat="server">
	<title>SignOut</title>
	<link rel="stylesheet" rev="stylesheet" type="text/css" href="../Styles/Style.css"/>
	</head>
	<body id="Body1" runat="server" class="pBack">
	<form id="Form1" method="post" runat="server">
		<BaseClasses:ScrollCoordinates id="ScrollCoordinates" runat="server"></BaseClasses:ScrollCoordinates>
		<BaseClasses:BasePageSettings id="PageSettings" runat="server"></BaseClasses:BasePageSettings>
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
						<ePortDC:Menu runat="server" id="Menu" HiliteSettings="Menu10MenuItem">
		</ePortDC:Menu>
					</td>
					</tr>
					<tr>
					<td>
						<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tr>
						<td class="pContent">
							<a name="StartOfPageContent"></a>
							
<!-- Begin SignOut.html -->
<table cellspacing="0" cellpadding="0" border="0" class="dv">
 <tr>
	<td class="dh">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="dhel"><img src="../Images/space.gif" alt=""/></td>
		<td class="dhi"><img src="../Images/icon_edit.gif" alt=""/></td>
	<td class="dht" valign="middle"><asp:Literal runat="server" id="DialogTitle" Text="&lt;%# GetResourceValue(&quot;Txt:SignOut&quot;, &quot;ePortDC&quot;) %>">
		</asp:Literal></td>
		<td class="dher"><img src="../Images/space.gif" alt=""/></td>
	</tr>
	</table>
	</td>
 	</tr>	
	<tr>
	<td align="left" class="dBody" colspan="4">
	<table cellpadding="0" cellspacing="0" border="0" width="325">
	<tr>
	<td>
	<%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("OKButton$_Button")) %>
	<asp:Label runat="server" id="SignOutMessage" Text="You have successfully signed out.">
		</asp:Label>
	<%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("OKButton$_Button")) %>
	
	
	
	<table cellpadding="0" cellspacing="0" border="0" style="padding-top:10px; padding-bottom:5px;" id="Table1" align="center">
		<tr>
		<td><ePortDC:ThemeButton runat="server" id="OKButton" Button-CausesValidation="False" Button-RedirectURL="../Default.aspx" Button-Text="&lt;%# GetResourceValue(&quot;Btn:OK&quot;, &quot;ePortDC&quot;) %>">
			</ePortDC:ThemeButton></td>
		<td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
		<td></td>
		<td><img src="../Images/space.gif" height="6" width="3" alt=""/></td>
		<td></td>
		</tr>
	</table>
	
	Please close your browser window to completely log out.
	</td>
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



