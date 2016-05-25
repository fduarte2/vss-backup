
// This file implements the code-behind class for PrintDC_OrderPage.aspx.
// App_Code\PrintDC_OrderPage.Controls.vb contains the Table, Row and Record control classes
// for the page.  Best practices calls for overriding methods in the Row or Record control classes.

#region "Using statements"    

using System;
using System.Data;
using System.Collections;
using System.ComponentModel;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;
using BaseClasses.Web.UI.WebControls;
using ePortDC.Business;
using ePortDC.Data;
using System.IO;
        

#endregion

  
namespace ePortDC.UI
{
  
partial class PrintDC_OrderPage
        : BaseApplicationPage
// Code-behind class for the PrintDC_OrderPage page.
// Place your customizations in Section 1. Do not modify Section 2.
{
        

#region "Section 1: Place your customizations here."    

	public PrintDC_OrderPage()
	{
		this.Initialize();
	}

	public void LoadData()
	{
		// LoadData reads database data and assigns it to UI controls.
		// Customize by adding code before or after the call to LoadData_Base()
		// or replace the call to LoadData_Base().
		LoadData_Base();

		// Build filename to print, Get the value of the setting from web.config file
	    string sOrderNum = this.OrderNum.Text.Trim();
		string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
		string sVessel = this.VesselId.SelectedItem.Value.ToString().Trim() + "-" + this.VesselId.SelectedItem.Text.ToString().Trim();
		string sFilePrefix = sDocumentFolder + sVessel + "\\" + sOrderNum;

		// Disable check box if file not present
		this.Form1CheckBox.Enabled = File.Exists(sFilePrefix + "_Form1_OrderEntry.xls");
		this.Form2CheckBox.Enabled = File.Exists(sFilePrefix + "_Form2_DeliveryOrder.xls");
		this.Form3CheckBox.Enabled = File.Exists(sFilePrefix + "_Form3_BillOfLading.xls");
		this.Form4CheckBox.Enabled = File.Exists(sFilePrefix + "_Form4_TallySheet.csv");
		this.Form5CheckBox.Enabled = File.Exists(sFilePrefix + "_Form5_ConfirmationOfSale.xls");
		this.Form6CheckBox.Enabled = File.Exists(sFilePrefix + "_Form6_PARS.xls");
		this.Form7CheckBox.Enabled = File.Exists(sFilePrefix + "_Form7_TransportationEntry.xls");
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
    
	public void PrintManifestButton_Click(object sender, EventArgs args)
	{
		// Get current record data
        string sOrderNum = this.OrderNum.Text.Trim();
		string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
		string sVessel = this.VesselId.SelectedItem.Value.ToString().Trim() + "-" + this.VesselId.SelectedItem.Text.ToString().Trim();
		string sFilePrefix = sDocumentFolder + sVessel + "\\" + sOrderNum;
		
		// Initiate Port Custom object which contains Port specific customizations
		PortCustom myPortCustom = new PortCustom();
		string sUserId = SystemUtils.GetUserID();
        
		if (this.Form1CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form1_OrderEntry.xls", 1);
		}

		if (this.Form2CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form2_DeliveryOrder.xls", 1);
		}

		if (this.Form3CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form3_BillOfLading.xls", 1);
		}

		if (this.Form4CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form4_TallySheet.csv", 1);
		}

		if (this.Form5CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form5_ConfirmationOfSale.xls", 1);
		}

		if (this.Form6CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form6_PARS.xls", 1);
		}

		if (this.Form7CheckBox.Checked)
		{
			myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form7_TransportationEntry.xls", 1);
		}

		PrintManifestButton_Click_Base(sender, args);
	}
	
//	public void PrintButton_Click(object sender, EventArgs args)
//	{
//		// Click handler for PrintButton.
//		// Customize by adding code before the call or replace the call to the Base function with your own code.
//		PrintButton_Click_Base(sender, args);
//		// NOTE: If the Base function redirects to another page, any code here will not be executed.
//	}


public void DoneButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for DoneButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          DoneButton_Click_Base(sender, args);
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
        
              this.DoneButton.Button.Click += new EventHandler(DoneButton_Click);
              this.PrintManifestButton.Button.Click += new EventHandler(PrintManifestButton_Click);
        }

        // Handles base.Load.  Read database data and put into the UI controls.
        // You can add additional Load handlers in Section 1.
        protected virtual void Page_Load(object sender, EventArgs e)
        {
        
            // Check if user has access to this page.  Redirects to either sign-in page
            // or 'no access' page if not. Does not do anything if role-based security
            // is not turned on, but you can override to add your own security.
            this.Authorize(this.GetAuthorizedRoles());

            // Load data only when displaying the page for the first time
            if ((!this.IsPostBack)) {   
        
                // Setup the header text for the validation summary control.
                this.ValidationSummary1.HeaderText = GetResourceValue("ValidationSummaryHeaderText", "ePortDC");

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
    
            try {
                // Load data only when displaying the page for the first time
                if ((!this.IsPostBack)) {

                    // Must start a transaction before performing database operations
                    DbUtils.StartTransaction();

                    // Load data for each record and table UI control.
                    // Ordering is important because child controls get 
                    // their parent ids from their parent UI controls.
        
                    this.DC_OrderRecordControl.LoadData();
                    this.DC_OrderRecordControl.DataBind();           
          
                }
            } catch (Exception ex) {
                // An error has occured so display an error message.
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "Page_Load_Error_Message", ex.Message);
            } finally {
                if (!this.IsPostBack) {
                    // End database transaction
                    DbUtils.EndTransaction();
                }
            }
        
        }

        // Write out event methods for the page events
        
              // event handler for Button with Layout
              public void DoneButton_Click_Base(object sender, EventArgs args)
              {
              
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                
          TargetKey = this.Page.Request.QueryString["Target"];

          if (TargetKey != null){
            shouldRedirect = false;
          }
      
            } catch (Exception ex) {
                shouldRedirect = false;
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
            if (shouldRedirect) {
                this.ShouldSaveControlsToSession = true;
                this.RedirectBack();
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public void PrintManifestButton_Click_Base(object sender, EventArgs args)
              {
              
            try {
                
            } catch (Exception ex) {
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
#endregion

  
}
  
}
  