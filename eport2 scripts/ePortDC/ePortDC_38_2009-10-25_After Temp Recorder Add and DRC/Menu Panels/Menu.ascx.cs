
using System;
using System.Collections;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.ComponentModel;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Web.UI;
using BaseClasses.Web.UI.WebControls;
        
using ePortDC.Business;
using ePortDC.Data;
        
namespace ePortDC.UI
{

  // Code-behind class for the Menu user control.
       
partial class Menu : BaseApplicationMenuControl , IMenu
{
		

#region "Section 1: Place your customizations here."    

        public Menu()
        {
            this.Initialize();
        }

        public void LoadData()
        {
            // LoadData reads database data and assigns it to UI controls.
            // Customize by adding code before or after the call to LoadData_Base()
            // or replace the call to LoadData_Base().
            LoadData_Base();
         }

#region "Ajax Functions"

        
        [System.Web.Services.WebMethod()]
        public static Object[] GetRecordFieldValue(String tableName , 
                                                    String recordID , 
                                                    String columnName, 
                                                    String title, 
                                                    bool persist, 
                                                    int popupWindowHeight, 
                                                    int popupWindowWidth, 
                                                    bool popupWindowScrollBar)
        {
            // GetRecordFieldValue gets the pop up window content from the column specified by
            // columnName in the record specified by the recordID in data base table specified by tableName.
            // Customize by adding code before or after the call to  GetRecordFieldValue_Base()
            // or replace the call to  GetRecordFieldValue_Base().

            return GetRecordFieldValue_Base(tableName, recordID, columnName, title, persist, popupWindowHeight, popupWindowWidth, popupWindowScrollBar);
        }

    
        [System.Web.Services.WebMethod()]
        public static object[] GetImage(String tableName,
      
                                        String recordID, 
                                        String columnName, 
                                        String title, 
                                        bool persist, 
                                        int popupWindowHeight, 
                                        int popupWindowWidth, 
                                        bool popupWindowScrollBar)
        {
            // GetImage gets the Image url for the image in the column "columnName" and
            // in the record specified by recordID in data base table specified by tableName.
            // Customize by adding code before or after the call to  GetImage_Base()
            // or replace the call to  GetImage_Base().
            return GetImage_Base(tableName, recordID, columnName, title, persist, popupWindowHeight, popupWindowWidth, popupWindowScrollBar);
        }
        
    
#endregion

    // Page Event Handlers - buttons, sort, links
    
              public void DC_CommoditySizeMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CommoditySizeMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CommoditySizeMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void DC_CommoditySizeMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CommoditySizeMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CommoditySizeMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
//              public void DC_CustomBrokerMenuItem_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_CustomBrokerMenuItem.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_CustomBrokerMenuItem_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
        
//              public void DC_CustomBrokerMenuItemHilited_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_CustomBrokerMenuItemHilited.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_CustomBrokerMenuItemHilited_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
        
//              public void DC_CustomerMenuItem_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_CustomerMenuItem.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_CustomerMenuItem_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
        
//              public void DC_CustomerMenuItemHilited_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_CustomerMenuItemHilited.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_CustomerMenuItemHilited_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
        
              public void DC_OrderMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_OrderMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_OrderMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void DC_OrderMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_OrderMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_OrderMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void DC_TransporterMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_TransporterMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_TransporterMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void DC_TransporterMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_TransporterMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_TransporterMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void Order_ListMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for Order_ListMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Order_ListMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void Order_ListMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for Order_ListMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Order_ListMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        

public void DC_CustomsBrokerOfficeMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomsBrokerOfficeMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomsBrokerOfficeMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CustomsBrokerOfficeMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomsBrokerOfficeMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomsBrokerOfficeMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CustomerMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomerMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomerMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CustomerMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomerMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomerMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_ConsigneeMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_ConsigneeMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_ConsigneeMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_ConsigneeMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_ConsigneeMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_ConsigneeMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
//public void DC_OrderDetailExpediterMenuItemHilited_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_OrderDetailExpediterMenuItemHilited.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_OrderDetailExpediterMenuItemHilited_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
//public void DC_OrderDetailExpediterMenuItem_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for DC_OrderDetailExpediterMenuItem.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          DC_OrderDetailExpediterMenuItem_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
public void Menu9MenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for Menu9MenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Menu9MenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void Menu9MenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for Menu9MenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Menu9MenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void Menu10MenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for Menu10MenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Menu10MenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void Menu10MenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for Menu10MenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          Menu10MenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CustomerPriceMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomerPriceMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomerPriceMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CustomerPriceMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CustomerPriceMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CustomerPriceMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_UserMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_UserMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_UserMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_UserMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_UserMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_UserMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_VesselMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_VesselMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_VesselMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_VesselMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_VesselMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_VesselMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CommodityMenuItemHilited_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CommodityMenuItemHilited.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CommodityMenuItemHilited_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
public void DC_CommodityMenuItem_Click(object sender, EventArgs args)
              {
            
          // Click handler for DC_CommodityMenuItem.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DC_CommodityMenuItem_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
#endregion

#region "Section 2: Do not modify this section."
        

        private void Initialize()
        {
            // Called by the class constructor to initialize event handlers for Init and Load
            // You can customize by modifying the constructor in Section 1.
            this.Init += new EventHandler(Page_InitializeEventHandlers);
            this.Load += new EventHandler(Page_Load);

            
        }

        // Handles base.Init. Registers event handler for any button, sort or links.
        // You can add additional Init handlers in Section 1.
        protected virtual void Page_InitializeEventHandlers(object sender, System.EventArgs e)
        {
            // Register the Event handler for any Events.
        
              this.DC_CommodityMenuItem.Button.Click += new EventHandler(DC_CommodityMenuItem_Click);
              this.DC_CommodityMenuItemHilited.Button.Click += new EventHandler(DC_CommodityMenuItemHilited_Click);
              this.DC_CommoditySizeMenuItem.Button.Click += new EventHandler(DC_CommoditySizeMenuItem_Click);
              this.DC_CommoditySizeMenuItemHilited.Button.Click += new EventHandler(DC_CommoditySizeMenuItemHilited_Click);
              this.DC_ConsigneeMenuItem.Button.Click += new EventHandler(DC_ConsigneeMenuItem_Click);
              this.DC_ConsigneeMenuItemHilited.Button.Click += new EventHandler(DC_ConsigneeMenuItemHilited_Click);
              this.DC_CustomerMenuItem.Button.Click += new EventHandler(DC_CustomerMenuItem_Click);
              this.DC_CustomerMenuItemHilited.Button.Click += new EventHandler(DC_CustomerMenuItemHilited_Click);
              this.DC_CustomerPriceMenuItem.Button.Click += new EventHandler(DC_CustomerPriceMenuItem_Click);
              this.DC_CustomerPriceMenuItemHilited.Button.Click += new EventHandler(DC_CustomerPriceMenuItemHilited_Click);
              this.DC_CustomsBrokerOfficeMenuItem.Button.Click += new EventHandler(DC_CustomsBrokerOfficeMenuItem_Click);
              this.DC_CustomsBrokerOfficeMenuItemHilited.Button.Click += new EventHandler(DC_CustomsBrokerOfficeMenuItemHilited_Click);
              this.DC_OrderMenuItem.Button.Click += new EventHandler(DC_OrderMenuItem_Click);
              this.DC_OrderMenuItemHilited.Button.Click += new EventHandler(DC_OrderMenuItemHilited_Click);
              this.DC_TransporterMenuItem.Button.Click += new EventHandler(DC_TransporterMenuItem_Click);
              this.DC_TransporterMenuItemHilited.Button.Click += new EventHandler(DC_TransporterMenuItemHilited_Click);
              this.DC_UserMenuItem.Button.Click += new EventHandler(DC_UserMenuItem_Click);
              this.DC_UserMenuItemHilited.Button.Click += new EventHandler(DC_UserMenuItemHilited_Click);
              this.DC_VesselMenuItem.Button.Click += new EventHandler(DC_VesselMenuItem_Click);
              this.DC_VesselMenuItemHilited.Button.Click += new EventHandler(DC_VesselMenuItemHilited_Click);
              this.Menu10MenuItem.Button.Click += new EventHandler(Menu10MenuItem_Click);
              this.Menu10MenuItemHilited.Button.Click += new EventHandler(Menu10MenuItemHilited_Click);
              this.Menu9MenuItem.Button.Click += new EventHandler(Menu9MenuItem_Click);
              this.Menu9MenuItemHilited.Button.Click += new EventHandler(Menu9MenuItemHilited_Click);
              this.Order_ListMenuItem.Button.Click += new EventHandler(Order_ListMenuItem_Click);
              this.Order_ListMenuItemHilited.Button.Click += new EventHandler(Order_ListMenuItemHilited_Click);
        }

        // Handles base.Load.  Read database data and put into the UI controls.
        // You can add additional Load handlers in Section 1.
        protected virtual void Page_Load(object sender, EventArgs e)
        {
        

            // Load data only when displaying the page for the first time
            if ((!this.IsPostBack)) {   
        

        // Read the data for all controls on the page.
        // To change the behavior, override the DataBind method for the individual
        // record or table UI controls.
        this.LoadData();
    }
    }

    public static object[] GetRecordFieldValue_Base(String tableName , 
                                                    String recordID , 
                                                    String columnName, 
                                                    String title, 
                                                    bool persist, 
                                                    int popupWindowHeight, 
                                                    int popupWindowWidth, 
                                                    bool popupWindowScrollBar)
    {
        string content =  NetUtils.EncodeStringForHtmlDisplay(BaseClasses.Utils.MiscUtils.GetFieldData(tableName, recordID, columnName)) ;
        // returnValue is an array of string values.
        // returnValue(0) represents title of the pop up window.
        // returnValue(1) represents content ie, image url.
        // retrunValue(2) represents whether pop up window should be made persistant
        // or it should closes as soon as mouse moved out.
        // returnValue(3), (4) represents pop up window height and width respectivly
        // ' returnValue(5) represents whether pop up window should contain scroll bar.
        // (0),(2),(3) and (4) is initially set as pass through attribute.
        // They can be modified by going to Attribute tab of the properties window of the control in aspx page.
        object[] returnValue = new object[6];
        returnValue[0] = title;
        returnValue[1] = content;
        returnValue[2] = persist;
        returnValue[3] = popupWindowWidth;
        returnValue[4] = popupWindowHeight;
        returnValue[5] = popupWindowScrollBar;
        return returnValue;
    }

    public static object[] GetImage_Base(String tableName, 
                                          String recordID, 
                                          String columnName, 
                                          String title, 
                                          bool persist, 
                                          int popupWindowHeight, 
                                          int popupWindowWidth, 
                                          bool popupWindowScrollBar)
    {
        string  content= "<IMG src =" + "\"../Shared/ExportFieldValue.aspx?Table=" + tableName + "&Field=" + columnName + "&Record=" + recordID + "\"/>";
        // returnValue is an array of string values.
        // returnValue(0) represents title of the pop up window.
        // returnValue(1) represents content ie, image url.
        // retrunValue(2) represents whether pop up window should be made persistant
        // or it should closes as soon as mouse moved out.
        // returnValue(3), (4) represents pop up window height and width respectivly
        // returnValue(5) represents whether pop up window should contain scroll bar.
        // (0),(2),(3), (4) and (5) is initially set as pass through attribute.
        // They can be modified by going to Attribute tab of the properties window of the control in aspx page.
        object[] returnValue = new object[6];
        returnValue[0] = title;
        returnValue[1] = content;
        returnValue[2] = persist;
        returnValue[3] = popupWindowWidth;
        returnValue[4] = popupWindowHeight;
        returnValue[5] = popupWindowScrollBar;
        return returnValue;
    }
    

    // Load data from database into UI controls.
    // Modify LoadData in Section 1 above to customize.  Or override DataBind() in
    // the individual table and record controls to customize.
    public void LoadData_Base()
    {
    
        }

        // Write out event methods for the page events
        
              // event handler for Button with Layout
              public void DC_CommodityMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Commodity/ShowDC_CommodityTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CommodityMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Commodity/ShowDC_CommodityTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CommoditySizeMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CommoditySize/ShowDC_CommoditySizeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CommoditySizeMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CommoditySize/ShowDC_CommoditySizeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_ConsigneeMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Consignee/ShowDC_ConsigneeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_ConsigneeMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Consignee/ShowDC_ConsigneeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomerMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Customer/ShowDC_CustomerTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomerMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Customer/ShowDC_CustomerTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomerPriceMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/ShowDC_CustomerPriceTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomerPriceMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/ShowDC_CustomerPriceTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomsBrokerOfficeMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_CustomsBrokerOfficeMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficeTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_OrderMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Order/AddDC_OrderPage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_OrderMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Order/AddDC_OrderPage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_TransporterMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Transporter/ShowDC_TransporterTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_TransporterMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Transporter/ShowDC_TransporterTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_UserMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_User/ShowDC_UserTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_UserMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_User/ShowDC_UserTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_VesselMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Vessel/ShowDC_VesselTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void DC_VesselMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Vessel/ShowDC_VesselTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Menu10MenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../Security/SignOut.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
            ((BaseApplicationPage)this.Page).LogOut();
      
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Menu10MenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../Security/SignOut.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Menu9MenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../Security/SignIn.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Menu9MenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../Security/SignIn.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Order_ListMenuItem_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Order/ShowDC_OrderTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
              // event handler for Button with Layout
              public void Order_ListMenuItemHilited_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"../DC_Order/ShowDC_OrderTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = ((BaseApplicationPage)this.Page).ModifyRedirectUrl(url, "",false);
                        ((BaseApplicationPage)this.Page).CommitTransaction(sender);
      
            } catch (Exception ex) {
                ((BaseApplicationPage)this.Page).RollBackTransaction(sender);
                shouldRedirect = false;
                ((BaseApplicationPage)this.Page).ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                ((BaseApplicationPage)this.Page).Response.Redirect(url);
            }
        
              }
            
#region Interface Properties
          
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_CommodityMenuItem {
            get {
                return this._DC_CommodityMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_CommodityMenuItemHilited {
            get {
                return this._DC_CommodityMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_CommoditySizeMenuItem {
            get {
                return this._DC_CommoditySizeMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_CommoditySizeMenuItemHilited {
            get {
                return this._DC_CommoditySizeMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_ConsigneeMenuItem {
            get {
                return this._DC_ConsigneeMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_ConsigneeMenuItemHilited {
            get {
                return this._DC_ConsigneeMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_CustomerMenuItem {
            get {
                return this._DC_CustomerMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_CustomerMenuItemHilited {
            get {
                return this._DC_CustomerMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_CustomerPriceMenuItem {
            get {
                return this._DC_CustomerPriceMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_CustomerPriceMenuItemHilited {
            get {
                return this._DC_CustomerPriceMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_CustomsBrokerOfficeMenuItem {
            get {
                return this._DC_CustomsBrokerOfficeMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_CustomsBrokerOfficeMenuItemHilited {
            get {
                return this._DC_CustomsBrokerOfficeMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_OrderMenuItem {
            get {
                return this._DC_OrderMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_OrderMenuItemHilited {
            get {
                return this._DC_OrderMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_TransporterMenuItem {
            get {
                return this._DC_TransporterMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_TransporterMenuItemHilited {
            get {
                return this._DC_TransporterMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_UserMenuItem {
            get {
                return this._DC_UserMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_UserMenuItemHilited {
            get {
                return this._DC_UserMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item DC_VesselMenuItem {
            get {
                return this._DC_VesselMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted DC_VesselMenuItemHilited {
            get {
                return this._DC_VesselMenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item Menu10MenuItem {
            get {
                return this._Menu10MenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted Menu10MenuItemHilited {
            get {
                return this._Menu10MenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item Menu9MenuItem {
            get {
                return this._Menu9MenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted Menu9MenuItemHilited {
            get {
                return this._Menu9MenuItemHilited;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item Order_ListMenuItem {
            get {
                return this._Order_ListMenuItem;
            }
        }
                
        [Bindable(true),
        Category("Behavior"),
        DefaultValue(""),
        NotifyParentProperty(true),
        DesignerSerializationVisibility(DesignerSerializationVisibility.Content)]
        public IMenu_Item_Highlighted Order_ListMenuItemHilited {
            get {
                return this._Order_ListMenuItemHilited;
            }
        }
                
#endregion
        
#endregion

  

}
  
}

  