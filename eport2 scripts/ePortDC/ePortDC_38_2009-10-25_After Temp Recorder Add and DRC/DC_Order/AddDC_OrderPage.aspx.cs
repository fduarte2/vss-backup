
// This file implements the code-behind class for AddDC_OrderPage.aspx.
// App_Code\AddDC_OrderPage.Controls.vb contains the Table, Row and Record control classes
// for the page.  Best practices calls for overriding methods in the Row or Record control classes.

#region "Using statements"    

using System;
using System.Data;
using System.Collections;
using System.ComponentModel;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.IO;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;
using BaseClasses.Web.UI.WebControls;
using Microsoft.Office.Interop.Excel;
        
using ePortDC.Business;
using ePortDC.Data;
        

#endregion

  
namespace ePortDC.UI
{
  
partial class AddDC_OrderPage
        : BaseApplicationPage
// Code-behind class for the AddDC_OrderPage page.
// Place your customizations in Section 1. Do not modify Section 2.
{
        

#region "Section 1: Place your customizations here."    

        public AddDC_OrderPage()
        {
		    this.Load += new System.EventHandler(RetrieveCookie_MyPageLoad);  
            this.Initialize();
        }

        public void LoadData()
        {
            // LoadData reads database data and assigns it to UI controls.
            // Customize by adding code before or after the call to LoadData_Base()
            // or replace the call to LoadData_Base().
            LoadData_Base();

			// Get Logged in User's Role
			string sRole = SystemUtils.GetUserRole();
			
			// Make all the buttons invisible, except cancel order button
			SaveButton.Visible = false;
			SubmitButton.Visible = false;
			MessageLabel.Text = "";
			
            if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
			{
				SaveButton.Visible = true;
				SubmitButton.Visible = true;
			}
			else
			{
				MessageLabel.Text = "You are not authorized to add orders.";
			}
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
    
              public void CancelButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for CancelButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          CancelButton_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        
              public void SaveButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for SaveButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          SaveButton_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }
        

			public void SubmitButton_Click(object sender, EventArgs args)
              {
              
              // BN - Save the Submit flag to be used in RecordControl class to update the Status
              OrderStatusSubmitLabel.Text = "SubmitButton";

			// BN - Save the record, no code will be executed after this because of redirect to another page
			SaveButton_Click_Base(sender, args);
			
          // Click handler for SubmitButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          //SubmitButton_Click_Base(sender, args);
          // NOTE: If the Base function redirects to another page, any code here will not be executed.
          }

		/// BN - Save last used Vessel in a cookie to fill next time automatically
		public override void CommitTransaction(object sender)
		{
		    // Call base.CommitTransaction()which will save the changes made by user
		    base.CommitTransaction(sender);

		    // Initiate port custom object
			PortCustom myPortCustom = new PortCustom();
			string sUserId = SystemUtils.GetUserID();

	        string sOrderNum = this.OrderNum.Text.ToString();
			string sToListDC = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListDC");
			string sToListPort = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListPort");
			string sToListOrderAlert = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListOrderAlert");
		    
		    // Do the following only if coming from Send or Submit button, you get here even by imagebutton
		    if (sender.GetType().ToString() == "System.Web.UI.WebControls.LinkButton")
		    {
			    System.Web.UI.WebControls.LinkButton myButton = ((System.Web.UI.WebControls.LinkButton)(sender));
			    
			    // Check to see if the button that got clicked is the save or submit button
			    if ((myButton.Parent.ID == "SaveButton")||(myButton.Parent.ID == "SubmitButton"))
			    {
			        // Save information in a cookie.
			        HttpCookie myCookie = new HttpCookie("ePortVessel");
			        myCookie.Expires = DateTime.Now.AddDays(30);
			
			        // Save the VesselId in a cookie.
			        myCookie.Value = this.DC_OrderRecordControl.GetRecord().VesselId.ToString();
			        this.Page.Response.Cookies.Add(myCookie);
			        
			    	// BN - Create Form1 from template
					myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form1_OrderEntry");
			    	
					// BN - Send an email
					//myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERENTRY");
			    }
			}
		}                    

		// Read the cookie
		private void RetrieveCookie_MyPageLoad(object sender, System.EventArgs e) 
		{ 
		    System.Web.HttpCookie myCookie; 
		
		    // Get information from a cookie
		    myCookie = Request.Cookies["ePortVessel"]; 
		    if (myCookie != null) 
		    { 
		    
		        // If cookie is not empty, then get its value and store it for later use
		        ePortVesselCookie.Text = myCookie.Value; 
		    } 
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
        
              this.CancelButton.Button.Click += new EventHandler(CancelButton_Click);
              this.SaveButton.Button.Click += new EventHandler(SaveButton_Click);
              this.SubmitButton.Button.Click += new EventHandler(SubmitButton_Click);
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
          
                    this.DC_OrderDetailTableControl.LoadData();
                    this.DC_OrderDetailTableControl.DataBind();           
          
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
              public void CancelButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"ShowDC_OrderTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = this.ModifyRedirectUrl(url, "",false);
                        this.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.RollBackTransaction(sender);
                shouldRedirect = false;
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.ShouldSaveControlsToSession = true;
                this.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public void SaveButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"ShowDC_OrderTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderRecordControl.SaveData();
              
                }
        
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderDetailTableControl.SaveData();
              
                }
        
                url = this.ModifyRedirectUrl(url, "",false);
                        this.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.RollBackTransaction(sender);
                shouldRedirect = false;
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.ShouldSaveControlsToSession = true;
                this.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public void SubmitButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"ShowDC_OrderTablePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderRecordControl.SaveData();
              
                }
        
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderDetailTableControl.SaveData();
              
                }
        
                url = this.ModifyRedirectUrl(url, "",false);
                        this.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.RollBackTransaction(sender);
                shouldRedirect = false;
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.ShouldSaveControlsToSession = true;
                this.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
#endregion

  
}
  
}
  