<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="asp" %>
<%@ Control Language="C#" AutoEventWireup="false" CodeFile="Menu.ascx.cs" Inherits="ePortDC.UI.Menu" %>
<%@ Register Tagprefix="ePortDC" TagName="Menu_Item" Src="../Shared/Menu_Item.ascx" %>

<%@ Register Tagprefix="Selectors" Namespace="ePortDC" %>

<%@ Register Tagprefix="ePortDC" TagName="Menu_Item_Highlighted" Src="../Shared/Menu_Item_Highlighted.ascx" %>

<%@ Register Tagprefix="BaseClasses" Namespace="BaseClasses.Web.UI.WebControls" Assembly="BaseClasses" %>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
 <tr>
	<td class="menus">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
	<td><img src="../Images/menuEdgeL.gif" alt=""/></td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_OrderMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Order/AddDC_OrderPage.aspx" Button-Text="Order">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_OrderMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Order/AddDC_OrderPage.aspx" Button-Text="Order" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_Order_ListMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Order/ShowDC_OrderTablePage.aspx" Button-Text="Order List">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_Order_ListMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Order/ShowDC_OrderTablePage.aspx" Button-Text="Order List" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_CommoditySizeMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CommoditySize/ShowDC_CommoditySizeTablePage.aspx" Button-Text="Commodity Size">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_CommoditySizeMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CommoditySize/ShowDC_CommoditySizeTablePage.aspx" Button-Text="Commodity Size" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_CustomsBrokerOfficeMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficeTablePage.aspx" Button-Text="Customs Broker Office">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_CustomsBrokerOfficeMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficeTablePage.aspx" Button-Text="Customs Broker Office" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_CustomerMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Customer/ShowDC_CustomerTablePage.aspx" Button-Text="Customer">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_CustomerMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Customer/ShowDC_CustomerTablePage.aspx" Button-Text="Customer" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_CustomerPriceMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CustomerPrice/ShowDC_CustomerPriceTablePage.aspx" Button-Text="Customer Price">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_CustomerPriceMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_CustomerPrice/ShowDC_CustomerPriceTablePage.aspx" Button-Text="Customer Price" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_ConsigneeMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Consignee/ShowDC_ConsigneeTablePage.aspx" Button-Text="Consignee">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_ConsigneeMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Consignee/ShowDC_ConsigneeTablePage.aspx" Button-Text="Consignee" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_TransporterMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Transporter/ShowDC_TransporterTablePage.aspx" Button-Text="Transporter">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_TransporterMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Transporter/ShowDC_TransporterTablePage.aspx" Button-Text="Transporter" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_CommodityMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Commodity/ShowDC_CommodityTablePage.aspx" Button-Text="Commodity">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_CommodityMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Commodity/ShowDC_CommodityTablePage.aspx" Button-Text="Commodity" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_VesselMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Vessel/ShowDC_VesselTablePage.aspx" Button-Text="Vessel">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_VesselMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_Vessel/ShowDC_VesselTablePage.aspx" Button-Text="Vessel" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_DC_UserMenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_User/ShowDC_UserTablePage.aspx" Button-Text="User">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_DC_UserMenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../DC_User/ShowDC_UserTablePage.aspx" Button-Text="User" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_Menu9MenuItem" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../Security/SignIn.aspx" Button-Text="Sign In">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_Menu9MenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../Security/SignIn.aspx" Button-Text="Sign In" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td>
	<ePortDC:Menu_Item runat="server" id="_Menu10MenuItem" Button-CausesValidation="False" Button-CommandName="LogOut" Button-RedirectURL="../Security/SignOut.aspx" Button-Text="Sign Out">
</ePortDC:Menu_Item>
	<ePortDC:Menu_Item_Highlighted runat="server" id="_Menu10MenuItemHilited" Button-CausesValidation="False" Button-CommandName="Redirect" Button-RedirectURL="../Security/SignOut.aspx" Button-Text="Sign Out" Visible="False">
</ePortDC:Menu_Item_Highlighted>
	</td>
	
	<td><img src="../Images/menuEdgeR.gif" alt=""/></td>
	</tr>
	</table>
	</td>
 </tr>
 <tr>
	<td class="mbbg"><img src="../Images/space.gif" height="1" width="1" alt=""/></td>
 </tr>
</table>
