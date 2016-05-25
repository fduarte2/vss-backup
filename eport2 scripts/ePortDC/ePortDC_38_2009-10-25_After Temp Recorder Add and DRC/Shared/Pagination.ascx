<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Control Language="C#" AutoEventWireup="false" CodeFile="Pagination.ascx.cs" Inherits="ePortDC.UI.Pagination" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>
<table cellspacing="0" cellpadding="0" border="0">
 <tr>
	<td><img src="../Images/space.gif" width="10" height="1" alt=""/></td>
	<td><img src="../Images/ButtonBarEdgeL.gif"></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td><asp:ImageButton runat="server" id="_FirstPage" CausesValidation="False" CommandName="FirstPage" ImageURL="../Images/ButtonBarFirst.gif" onmouseout="this.src='../Images/ButtonBarFirst.gif'" onmouseover="this.src='../Images/ButtonBarFirstOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:FirstPage&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

</asp:ImageButton></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td><asp:ImageButton runat="server" id="_PreviousPage" CausesValidation="False" CommandName="PreviousPage" ImageURL="../Images/ButtonBarPrevious.gif" onmouseout="this.src='../Images/ButtonBarPrevious.gif'" onmouseover="this.src='../Images/ButtonBarPreviousOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:PreviousPage&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

</asp:ImageButton></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td class="prbg"><b><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("_PageSizeButton")) %><asp:TextBox runat="server" id="_CurrentPage" CssClass="Pagination_Input" EnableIncrementDecrementButtons="True" MaxLength="3" Size="3" onkeyup="adjustPageSize(this, event.keyCode, 1, 999);">
</asp:TextBox><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("_PageSizeButton")) %></b></td>
<%= SystemUtils.GenerateIncrementDecrementButtons(true, FindControl("_CurrentPage"),"PaginationTextBox1","","","") %>

	<td class="prbg"><%# GetResourceValue("Txt:Of", "ePortDC") %> <b><asp:Label runat="server" id="_TotalPages">
</asp:Label></b></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td><asp:ImageButton runat="server" id="_NextPage" CausesValidation="False" CommandName="NextPage" ImageURL="../Images/ButtonBarNext.gif" onmouseout="this.src='../Images/ButtonBarNext.gif'" onmouseover="this.src='../Images/ButtonBarNextOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:NextPage&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

</asp:ImageButton></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td><asp:ImageButton runat="server" id="_LastPage" CausesValidation="False" CommandName="LastPage" ImageURL="../Images/ButtonBarLast.gif" onmouseout="this.src='../Images/ButtonBarLast.gif'" onmouseover="this.src='../Images/ButtonBarLastOver.gif'" ToolTip="&lt;%# GetResourceValue(&quot;Btn:LastPage&quot;, &quot;ePortDC&quot;) %>" AlternateText="">

</asp:ImageButton></td>
	<td><img src="../Images/ButtonBarPageItemDivider.gif"></td>
	<td class="prbg"><b><asp:Label runat="server" id="_TotalItems">
</asp:Label></b>&nbsp;Items</td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td class="prbg"><%= SystemUtils.GenerateEnterKeyCaptureBeginTag(FindControl("_PageSizeButton")) %><asp:TextBox runat="server" id="_PageSize" CssClass="Pagination_Input" EnableIncrementDecrementButtons="True" MaxLength="3" Size="3" onkeyup="adjustPageSize(this, event.keyCode, 1, 999);">
</asp:TextBox><%= SystemUtils.GenerateEnterKeyCaptureEndTag(FindControl("_PageSizeButton")) %></td>
<%= SystemUtils.GenerateIncrementDecrementButtons(true, FindControl("_PageSize"),"PaginationTextBox2","","","") %>

	<td class="prbg">/<%# GetResourceValue("Txt:Page", "ePortDC") %></td>
	<td><img src="../Images/ButtonBarDivider.gif"></td>
	<td class="prbg"><asp:LinkButton runat="server" id="_PageSizeButton" CausesValidation="False" CommandName="PageSize" CssClass="button_link" Text="&lt;%# GetResourceValue(&quot;Btn:Go&quot;, &quot;ePortDC&quot;) %>">

</asp:LinkButton></td>
	<td><img src="../Images/ButtonBarEdgeR.gif"></td>
 </tr>
</table>
