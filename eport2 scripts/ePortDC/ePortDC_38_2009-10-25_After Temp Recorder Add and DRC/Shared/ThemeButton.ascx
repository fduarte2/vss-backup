﻿<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Control Language="C#" AutoEventWireup="false" CodeFile="ThemeButton.ascx.cs" Inherits="ePortDC.UI.ThemeButton" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<table cellpadding="0" cellspacing="0" border="0" onmouseover="this.style.cursor='pointer'; return true;" onclick="clickLinkButtonText(this, event);">
 <tr>
	<td class="bTL"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
	<td class="bT"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
	<td class="bTR"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
 </tr>
 <tr>
	<td class="bL"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
	<td class="bC"><asp:LinkButton CommandName="Redirect" runat="server" id="_Button" CssClass="button_link">

</asp:LinkButton></td>
	<td class="bR"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
 </tr>
 <tr>
	<td class="bBL"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
	<td class="bB"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
	<td class="bBR"><img src="../Images/space.gif" height="0" width="0" alt=""/></td>
 </tr>
</table>