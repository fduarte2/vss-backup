<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Control Language="C#" AutoEventWireup="true" CodeFile="SignIn_Control.ascx.cs" Inherits="ePortDC.UI.SignIn_Control" %>
<%@ Register Tagprefix="ePortDC" Namespace="ePortDC.UI.Controls.SignIn_Control" %>
<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>

<ePortDC:SignInControl id="SignInControl" runat="server">
<table class="edit_label" cellspacing="1" cellpadding="1" border="0" align="center">
	<tbody>
		<tr>
			<td colspan="2" class="edit_label">
				<br />
				<asp:Label id="LoginMsg" runat="server" />
			</td>
		</tr>
		<tr>
			<td colspan="2" height="5"></td>
		</tr>
		<tr>
			<td class="field_label"><asp:Label id="UsernameLbl" runat="server" Text='<%# GetResourceValue("Txt:UserName", "ePortDC")%>'/>&nbsp;</td>
			<td class="field_value">
				<asp:TextBox id="UserName" class="field_input" runat="server" Text="">
				</asp:TextBox>
				<asp:RequiredFieldValidator id="RequiredFieldValidator1" runat="server" ErrorMessage="Please enter your user name." ControlToValidate="UserName" Display="None"></asp:RequiredFieldValidator>
			</td>
		</tr>
		<tr>
			<td class="field_label"><asp:Label id="PasswordLbl" runat="server" Text='<%# GetResourceValue("Txt:Password", "ePortDC")%>'/>&nbsp;</td>
			<td class="field_value">
				<asp:TextBox id="Password" class="field_input" runat="server" TextMode="Password"></asp:TextBox>
				<asp:RequiredFieldValidator id="RequiredFieldValidator2" runat="server" ErrorMessage="Please enter your password." ControlToValidate="Password" Display="None"></asp:RequiredFieldValidator>
			</td>
		</tr>
	</tbody>
</table>
</ePortDC:SignInControl>