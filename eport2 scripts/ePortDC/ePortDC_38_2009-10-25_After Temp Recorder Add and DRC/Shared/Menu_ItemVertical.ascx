<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Control Language="C#" AutoEventWireup="false" CodeFile="Menu_ItemVertical.ascx.cs" Inherits="ePortDC.UI.Menu_ItemVertical" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<tr onmouseover="this.style.cursor='pointer'; return true;" onclick="clickLinkButtonText(this, event);">
	<td class="mvTL"><img src="../Images/space.gif" height="5" width="6" alt=""/></td>
	<td class="mvT"><img src="../Images/space.gif" height="5" width="10" alt=""/></td>
	<td class="mvTR"><img src="../Images/space.gif" height="5" width="4" alt=""/></td>
 </tr>
 <tr onmouseover="this.style.cursor='pointer'; return true;" onclick="clickLinkButtonText(this, event);">
	<td class="mvL"><img src="../Images/space.gif" height="6" width="6" alt=""/></td>
	<td class="mvC"><asp:LinkButton CommandName="Redirect" runat="server" id="_Button" CssClass="menu">

</asp:LinkButton></td>
	<td class="mvR"><img src="../Images/space.gif" height="6" width="4" alt=""/></td>
 </tr>
 <tr onmouseover="this.style.cursor='pointer'; return true;" onclick="clickLinkButtonText(this, event);">
	<td class="mvBL"><img src="../Images/space.gif" height="5" width="6" alt=""/></td>
	<td class="mvB"><img src="../Images/space.gif" height="5" width="10" alt=""/></td>
	<td class="mvBR"><img src="../Images/space.gif" height="5" width="4" alt=""/></td>
 </tr>
