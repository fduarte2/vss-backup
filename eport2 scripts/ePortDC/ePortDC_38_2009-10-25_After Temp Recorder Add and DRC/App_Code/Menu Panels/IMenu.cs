
using Microsoft.VisualBasic;
  
namespace ePortDC.UI
{

  

    public interface IMenu {

#region Interface Properties
        
        IMenu_Item DC_CommodityMenuItem {get;}
                
        IMenu_Item_Highlighted DC_CommodityMenuItemHilited {get;}
                
        IMenu_Item DC_CommoditySizeMenuItem {get;}
                
        IMenu_Item_Highlighted DC_CommoditySizeMenuItemHilited {get;}
                
        IMenu_Item DC_ConsigneeMenuItem {get;}
                
        IMenu_Item_Highlighted DC_ConsigneeMenuItemHilited {get;}
                
        IMenu_Item DC_CustomerMenuItem {get;}
                
        IMenu_Item_Highlighted DC_CustomerMenuItemHilited {get;}
                
        IMenu_Item DC_CustomerPriceMenuItem {get;}
                
        IMenu_Item_Highlighted DC_CustomerPriceMenuItemHilited {get;}
                
        IMenu_Item DC_CustomsBrokerOfficeMenuItem {get;}
                
        IMenu_Item_Highlighted DC_CustomsBrokerOfficeMenuItemHilited {get;}
                
        IMenu_Item DC_OrderMenuItem {get;}
                
        IMenu_Item_Highlighted DC_OrderMenuItemHilited {get;}
                
        IMenu_Item DC_TransporterMenuItem {get;}
                
        IMenu_Item_Highlighted DC_TransporterMenuItemHilited {get;}
                
        IMenu_Item DC_UserMenuItem {get;}
                
        IMenu_Item_Highlighted DC_UserMenuItemHilited {get;}
                
        IMenu_Item DC_VesselMenuItem {get;}
                
        IMenu_Item_Highlighted DC_VesselMenuItemHilited {get;}
                
        IMenu_Item Menu10MenuItem {get;}
                
        IMenu_Item_Highlighted Menu10MenuItemHilited {get;}
                
        IMenu_Item Menu9MenuItem {get;}
                
        IMenu_Item_Highlighted Menu9MenuItemHilited {get;}
                
        IMenu_Item Order_ListMenuItem {get;}
                
        IMenu_Item_Highlighted Order_ListMenuItemHilited {get;}
                

#endregion

    }

  
}
  