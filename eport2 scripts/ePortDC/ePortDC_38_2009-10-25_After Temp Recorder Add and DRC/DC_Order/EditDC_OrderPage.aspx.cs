
// This file implements the code-behind class for EditDC_OrderPage.aspx.
// App_Code\EditDC_OrderPage.Controls.vb contains the Table, Row and Record control classes
// for the page.  Best practices calls for overriding methods in the Row or Record control classes.

#region "Using statements"    

using System;
using System.Data;
using System.Collections;
using System.ComponentModel;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Diagnostics;
using System.IO;
using BaseClasses;
using BaseClasses.Utils;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;
using BaseClasses.Web.UI.WebControls;
using Microsoft.Office.Interop.Excel;
using Microsoft.Office.Core;
using ePortDC.Business;
using ePortDC.Data;
using System.IO;        

#endregion

  
namespace ePortDC.UI
{
  
partial class EditDC_OrderPage
        : BaseApplicationPage
// Code-behind class for the EditDC_OrderPage page.
// Place your customizations in Section 1. Do not modify Section 2.
{
        
#region "Section 1: Place your customizations here."    
	
//	public void ScanTEButton_Click(object sender, EventArgs args)
//	{
//		// Launch Signature pad application
//		
//		ScanTEButton_Click_Base(sender, args);
//	}
	
	public EditDC_OrderPage()
	{
		this.Initialize();
	}

	public void LoadData()
	{
		// LoadData reads database data and assigns it to UI controls.
		// Customize by adding code before or after the call to LoadData_Base()
		// or replace the call to LoadData_Base().
		LoadData_Base();
		
        string sStatus = "";
		if ( this.OrderStatusId.SelectedItem == null)
		{
        	sStatus = "1"; //DRAFT
        }
        else
        {
        	sStatus = this.OrderStatusId.SelectedItem.Value;
        }

		
		// Get Logged in User's Role
		string sRole = SystemUtils.GetUserRole();
		
		// Make all the buttons invisible, except cancel order button
		SaveButton.Visible = false;
		SubmitButton.Visible = false;
		SaveButton.Visible = false;
		CheckInDriverButton.Visible = false;
		StopLoadingButton.Visible = false;
		SubmitRevisionButton.Visible = false;
		TEReceivedButton.Visible = false;
		ConfirmOrderButton.Visible = false;
		PARSFormButton.Visible = false;
		SignPadButton.Visible = false;
		SignatureCompleteButton.Visible = false;
		SyncOrderButton.Visible = false;
		RefreshTotalsButton.Visible = true;
		CancelOrderButton.Visible = false;
		CommentsCancelLabel.Visible = false;
		CommentsCancel.Visible = false;
		PrintButton.Visible = false;
		MessageLabel.Text = "";

		// Build signature file name
		string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
		string sSignatureFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortSignatureFolder");
		string sSignatureUrl = System.Configuration.ConfigurationManager.AppSettings.Get("ePortSignatureUrl");
		string sSignFileName = "";
		string sSignFileNameWeb = "";
		string sSignFileNameUrl = "";
		string sVesselTag = "";
		if ( this.VesselId.SelectedItem != null)
		{
			sVesselTag = this.VesselId.SelectedItem.Value.Trim() + "-" + this.VesselId.SelectedItem.Text.Trim();
			sSignFileName = sDocumentFolder + sVesselTag + "\\" + this.OrderNum.Text.Trim() + "_Signature.bmp";
			sSignFileNameWeb = sSignatureFolder + sVesselTag + "\\" + this.OrderNum.Text.Trim() + "_Signature.bmp";

	        if ( System.IO.File.Exists(sSignFileNameWeb) )
	        {
				sSignFileNameUrl = sSignatureUrl + sVesselTag + "/" + this.OrderNum.Text.Trim() + "_Signature.bmp";
			}
			else
			{
			    if ( System.IO.File.Exists(sSignFileName) )
	        	{
					sSignFileNameUrl = "../Images/SignatureYes.bmp";
				}
				else
	        	{
					sSignFileNameUrl = "../Images/SignatureNo.bmp";
				}
	        }
		    SignatureImage.ImageUrl = sSignFileNameUrl;
	    }

		if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
		{
			PrintButton.Visible = true;
		}

		if ( sRole.Contains(";ADMIN") )
		{
			SyncOrderButton.Visible = true;
		}

		// Make buttons visible based on order status
		switch (sStatus)
		{
			case "1": // DRAFT
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
				{
					SaveButton.Visible = true;
					SubmitButton.Visible = true;
					CancelOrderButton.Visible = true;
				}
				else
				{
					MessageLabel.Text = "You are not authorized to change the order.";
				}
				break;
			case "2": // SUBMIT
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
				{
					SaveButton.Visible = true;
					SubmitButton.Visible = true;
					CancelOrderButton.Visible = true;
					MessageLabel.Text = "Picklist Status awaited.";
				}
				else
				{
					MessageLabel.Text = "You are not authorized to change the order.";
				}
				break;
			case "3": // PICKLIST COMPLETE
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
				{
					SubmitButton.Visible = true;
					CancelOrderButton.Visible = true;
					MessageLabel.Text = "Picklist Status is Complete. Submitting would send alert to expiditor.";
				}
				else
				{
					MessageLabel.Text = "You are not authorized to change the order.";
				}
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
				{
					CheckInDriverButton.Visible = true;
				}
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") )
				{
					SaveButton.Visible = true;
				}
				break;
			case "4": // TRUCK BEING LOADED
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") )
				{
					SaveButton.Visible = true;
					CancelOrderButton.Visible = true;
				}
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
				{
					if (TEStatus.SelectedItem.Value == "RECEIVED")
					{
						ConfirmOrderButton.Visible = true;
					}
					else
					{
						TEReceivedButton.Visible = true;
					}
					StopLoadingButton.Visible = true;
				}
				else
				{
					MessageLabel.Text = "You are not authorized to change the order.";
				}
				break;
			case "5": // LOADING STOPPED
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
				{
					SaveButton.Visible = true;
					SubmitRevisionButton.Visible = true;
					CancelOrderButton.Visible = true;
				}
				break;
			case "6": // REVISION SUBMITTED
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";DC") )
				{
					SaveButton.Visible = true;
					CancelOrderButton.Visible = true;
					MessageLabel.Text = "Picklist Status awaited.";
				}
				else
				{
					MessageLabel.Text = "You are not authorized to change the order.";
				}
				break;
			case "7": // REVISED PICKLIST COMPLETE
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") )
				{
					SaveButton.Visible = true;
					CancelOrderButton.Visible = true;
				}
                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
				{
					CheckInDriverButton.Visible = true;
				}
				break;
			case "8": // CONFIRMED
                if (!Directory.Exists(sSignatureFolder + sVesselTag))
                {
                    Directory.CreateDirectory(sSignatureFolder + sVesselTag);
                }

                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") )
				{
					SaveButton.Visible = true;
				}

                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
				{
					SignPadButton.Visible = true;
					SignatureCompleteButton.Visible = true;
				}

			    // Check if PARS Information is required for this customer
			    bool bPARSRequired = false;

				// Get current record data
				DC_OrderRecord myRecord; 
				myRecord = this.DC_OrderRecordControl.GetRecord();
				    
			    WhereClause wc1 = new WhereClause();
			    string selectedValue1 = myRecord.CustomerId.ToString().Trim();  
			    wc1.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, selectedValue1);
			
			    // Get the records using the created where clause from vessel table for the vessle
			    foreach (DC_CustomerRecord itemValue1 in DC_CustomerTable.GetRecords(wc1, null, 0, 1))
			    {
			        if (itemValue1.CustomerIdSpecified)
			        {
			        	bPARSRequired = itemValue1.NeedPARS;
			        }
				}
				
				if ( bPARSRequired )
				{
	                if ( sRole.Contains(";ADMIN") || sRole.Contains(";PORTADMIN") || sRole.Contains(";PORTUSER") )
					{
						PARSFormButton.Visible = true;
					}
					if ( myRecord.PARSBarCode == null )
					{
						MessageLabel.Text = "PARS information is required for this customer.";
					}
				}
				else
				{
					MessageLabel.Text = "Request Driver Signature. Please make sure TE Sheet has been scanned.";
				}
				CancelOrderButton.Visible = false;

				break;
			case "9": // ORDER COMPLETED
                if ( sRole.Contains(";ADMIN") )
				{
					SaveButton.Visible = true;
				}
				MessageLabel.Text = "Order is Complete. No further changes are allowed.";
				RefreshTotalsButton.Visible = false;
				break;
			case "10": // CANCELLED
				MessageLabel.Text = "Order has been Cancelled. No further changes are allowed.";
				CancelOrderButton.Visible = false;
				RefreshTotalsButton.Visible = false;
				CommentsCancelLabel.Visible = true;
				CommentsCancel.Visible = true;
				break;
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
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "SaveButton";

		// Click handler for SaveButton.
		// Customize by adding code before the call or replace the call to the Base function with your own code.
		if (Validate())
		{
			SaveButton_Click_Base(sender, args);
			// NOTE: If the Base function redirects to another page, any code here will not be executed.
		}
	}

	public void SubmitButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "SubmitButton";
		
		// BN - Save the record, no code will be executed after this because of redirect to another page
		if (Validate())
		{
			SaveButton_Click_Base(sender, args);
			
			// Click handler for SubmitButton.
			// Customize by adding code before the call or replace the call to the Base function with your own code.
			//SubmitButton_Click_Base(sender, args);
			// NOTE: If the Base function redirects to another page, any code here will not be executed.
		}
	}

	public bool Validate()
	{
		// BN - Since Temp Recorder is required only for two customers, check here before saving
		// FFL = Loblaws = 1, FFP = Provigo = 2
		if ((CustomerId.Text.Trim() == "1" || CustomerId.Text.Trim() == "2") && ZUser2.Text.Trim() == "")
		{
			MessageLabel.Text = "Temp Recorder # is required for this customer.";
			return false;
		}
		else
		{
			MessageLabel.Text = "";
			return true;
		}
	}

	public void CheckInDriverButton_Click(object sender, EventArgs args)
	{
		if (DriverName.Text.Trim() != "")
		{
			// BN - Save the Submit flag to be used in RecordControl class to update the Status
			OrderStatusSubmitLabel.Text = "CheckInDriverButton";
			
			// BN - Save the record, no code will be executed after this because of redirect to another page
			CheckInDriverButton_Click_Base(sender, args);
		}
		else
		{
			MessageLabel.Text = "Please enter the Driver's Name";
		}
	}

	public void StopLoadingButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "StopLoadingButton";
		
		// BN - Save the record, no code will be executed after this because of redirect to another page
		SaveButton_Click_Base(sender, args);

		//StopLoadingButton_Click_Base(sender, args);
	}
	
	public void SubmitRevisionButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "SubmitRevisionButton";
		
		// BN - Save the record, no code will be executed after this because of redirect to another page
		SaveButton_Click_Base(sender, args);

		//SubmitRevisionButton_Click_Base(sender, args);
	}

	public void TEReceivedButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "TEReceivedButton";
		
		// BN - Save the record, no code will be executed after this because of redirect to another page
		TEReceivedButton_Click_Base(sender, args);
	}
	
	public void SignatureCompleteButton_Click(object sender, EventArgs args)
	{
		// Get current record data, since PARS information in not bound to any control
		DC_OrderRecord myRecord; 
		myRecord = DC_OrderTable.GetRecord("OrderNum = '" + this.OrderNum.Text.Trim() + "'");

		if (PARSFormButton.Visible)
		{
			if ( (myRecord.PARSBarCode != null) && (myRecord.PARSBarCode.Trim() != "") )
			{
				// BN - Save the Submit flag to be used in RecordControl class to update the Status
				OrderStatusSubmitLabel.Text = "SignatureCompleteButton";
				
				// BN - Save the record, no code will be executed after this because of redirect to another page
				SignatureCompleteButton_Click_Base(sender, args);
			}
			else
			{
				MessageLabel.Text = "Please enter PARS Details to proceed.";
			}
		}
		else 
		{
			// BN - Save the Submit flag to be used in RecordControl class to update the Status
			OrderStatusSubmitLabel.Text = "SignatureCompleteButton";
			
			// BN - Save the record, no code will be executed after this because of redirect to another page
			SignatureCompleteButton_Click_Base(sender, args);
		}
	}
	
	public void ConfirmOrderButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "ConfirmOrderButton";
		
		// BN - Save the record, no code will be executed after this because of redirect to another page
		ConfirmOrderButton_Click_Base(sender, args);
	}
	
    public void CancelOrderButton_Click(object sender, EventArgs args)
    {
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "CancelOrderButton";
		
		// Click handler for CancelOrderButton.
		// Customize by adding code before the call or replace the call to the Base function with your own code.
		CancelOrderButton_Click_Base(sender, args);
		// NOTE: If the Base function redirects to another page, any code here will not be executed.
    }

	public void SyncOrderButton_Click(object sender, EventArgs args)
	{
		PortCustom myPortCustom = new PortCustom();
		myPortCustom.SyncOrder(OrderNum.Text.Trim());
		
		// Click handler for PrintButton.
		// Customize by adding code before the call or replace the call to the Base function with your own code.
		SyncOrderButton_Click_Base(sender, args);
		// NOTE: If the Base function redirects to another page, any code here will not be executed.
	}

	public void PrintButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "PrintButton";

		// Click handler for PrintButton.
		// Customize by adding code before the call or replace the call to the Base function with your own code.
		PrintButton_Click_Base(sender, args);
		// NOTE: If the Base function redirects to another page, any code here will not be executed.
	}
	
	public void PARSFormButton_Click(object sender, EventArgs args)
	{
		// BN - Save the Submit flag to be used in RecordControl class to update the Status
		OrderStatusSubmitLabel.Text = "PARSFormButton";

		// Click handler for PARSFormButton.
		// Customize by adding code before the call or replace the call to the Base function with your own code.
		PARSFormButton_Click_Base(sender, args);
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

		// Get current record data
        string sOrderNum = this.OrderNum.Text.Trim();
		string sCustomerId = this.CustomerId.SelectedValue.ToString().Trim();
		string sDocumentFolder = System.Configuration.ConfigurationManager.AppSettings.Get("ePortFormDocumentsFolder");
		string sToListDC = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListDC");
		string sToListDC440 = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListDC440");
		string sToListPort = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListPort");
		string sToListOrderAlert = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListOrderAlert");
		string sVessel = this.VesselId.SelectedItem.Value.ToString().Trim() + "-" + this.VesselId.SelectedItem.Text.ToString().Trim();
		string sFilePrefix = sDocumentFolder + sVessel + "\\" + sOrderNum;
	    
	    // Do the following only if coming from Send or Submit button, you get here even by imagebutton
	    if (sender.GetType().ToString() == "System.Web.UI.WebControls.LinkButton")
	    {
		    System.Web.UI.WebControls.LinkButton myButton = ((System.Web.UI.WebControls.LinkButton)(sender));
		    string sButtonClicked = myButton.Parent.ID;
		    
		    // Check to see if the button that got clicked is the save or submit button
		    if ((sButtonClicked == "SaveButton") || (sButtonClicked == "SubmitButton") || (sButtonClicked == "SubmitRevisionButton"))
		    {
		        // Save information in a cookie.
		        HttpCookie myCookie = new HttpCookie("ePortVessel");
		        myCookie.Expires = DateTime.Now.AddDays(30);
		
		        // Save the VesselId in a cookie.
		        myCookie.Value = this.DC_OrderRecordControl.GetRecord().VesselId.ToString();
		        this.Page.Response.Cookies.Add(myCookie);
		        
		    	// BN - Create Form1 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form1_OrderEntry");
		    	
		    	// BN - Create Form2 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form2_DeliveryOrder");

				// BN - Print two copies of delivery order
				myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form2_DeliveryOrder.xls", 2);

				// BN - Send an email to dominion citrus
				//myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERENTRY");

				// BN - Send an email text to expediter
				//myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERALERT");
		    }

		    // Check to see if the button that got clicked is the check-in driver, create docs again
		    if ( (sButtonClicked == "CheckInDriverButton") )
		    {
		    	// BN - Create Form1 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form1_OrderEntry");
		    	
		    	// BN - Create Form2 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form2_DeliveryOrder");

				// Initiate Port Custom object to print two copies of delivery order
				myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form2_DeliveryOrder.xls", 2);
		    }

		    // Check to see if the button that got clicked is the confirm order, create docs
		    if ( (sButtonClicked == "ConfirmOrderButton") )
		    {
		    	// BN - Create Form3 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form3_BillOfLading");

		    	// BN - Create Form4 using third party tally sheet EXE
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form4_TallySheet");

		    	// BN - Create Form5 from template
		    	if (sCustomerId != "440")
		    	{
					myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form5_ConfirmationOfSale");
		    	}
		    }

		    // Check to see if the button that got clicked is the signature complete order, create docs again
		    if ( (sButtonClicked == "SignatureCompleteButton") )
		    {
		    	// BN - Create Form3 from template
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form3_BillOfLading");

		    	// BN - Create Form4 using third party tally sheet EXE
				myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form4_TallySheet");

		    	// BN - Create Form5 from template
		    	if (sCustomerId != "440")
		    	{
					myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form5_ConfirmationOfSale");
			    }

		    	// BN - Create Form6 from template, only if needed
		    	if (sCustomerId != "440")
		    	{
			    	if (this.PARSFormButton.Visible)
			    	{
						myPortCustom.CreateToQueue(sOrderNum, sUserId, "Form6_PARS");
			    	}
			    }

				// Print copies of files
				myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form3_BillOfLading.xls", 3);
		    	if (sCustomerId != "440")
		    	{
					myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form5_ConfirmationOfSale.xls", 3);
			    	if (this.PARSFormButton.Visible)
			    	{
						myPortCustom.PrintToQueue(sOrderNum, sUserId, sFilePrefix + "_Form6_PARS.xls", 3);
					}
				}

				// Send order complete email
				myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERCOMPLETE");
		    }
		}
	}                    


//public void SignButton_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for SignButton.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          SignButton_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
//public void SignPadButton_Click(object sender, EventArgs args)
//              {
//            
//          // Click handler for SignPadButton.
//          // Customize by adding code before the call or replace the call to the Base function with your own code.
//          SignPadButton_Click_Base(sender, args);
//          // NOTE: If the Base function redirects to another page, any code here will not be executed.
//          }
public void SignPadButton_Click(object sender, EventArgs args)
              {
            
          // Click handler for SignPadButton.
          // Customize by adding code before the call or replace the call to the Base function with your own code.
          SignPadButton_Click_Base(sender, args);
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
        
              this.CancelButton.Button.Click += new EventHandler(CancelButton_Click);
              this.CancelOrderButton.Button.Click += new EventHandler(CancelOrderButton_Click);
              this.CheckInDriverButton.Button.Click += new EventHandler(CheckInDriverButton_Click);
              this.ConfirmOrderButton.Button.Click += new EventHandler(ConfirmOrderButton_Click);
              this.PARSFormButton.Button.Click += new EventHandler(PARSFormButton_Click);
              this.PrintButton.Button.Click += new EventHandler(PrintButton_Click);
              this.SaveButton.Button.Click += new EventHandler(SaveButton_Click);
              this.SignatureCompleteButton.Button.Click += new EventHandler(SignatureCompleteButton_Click);
              this.SignPadButton.Button.Click += new EventHandler(SignPadButton_Click);
              this.StopLoadingButton.Button.Click += new EventHandler(StopLoadingButton_Click);
              this.SubmitButton.Button.Click += new EventHandler(SubmitButton_Click);
              this.SubmitRevisionButton.Button.Click += new EventHandler(SubmitRevisionButton_Click);
              this.SyncOrderButton.Button.Click += new EventHandler(SyncOrderButton_Click);
              this.TEReceivedButton.Button.Click += new EventHandler(TEReceivedButton_Click);
        }

        // Handles base.Load.  Read database data and put into the UI controls.
        // You can add additional Load handlers in Section 1.
        protected virtual void Page_Load(object sender, EventArgs e)
        {
        
            // Check if user has access to this page.  Redirects to either sign-in page
            // or 'no access' page if not. Does not do anything if role-based security
            // is not turned on, but you can override to add your own security.
            this.Authorize(this.GetAuthorizedRoles());
                  this.Authorize((Control)PrintButton, "ADMIN;PORTADMIN;PORTUSER");
            

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
              public void CancelOrderButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"CancelDC_OrderPage.aspx?OrderNum={DC_OrderRecordControl:PK}";
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
              public void CheckInDriverButton_Click_Base(object sender, EventArgs args)
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
              public void ConfirmOrderButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"EditDC_OrderPage.aspx?DC_Order={DC_OrderRecordControl:PK}";
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
              public void PARSFormButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"PARSDC_OrderPage.aspx?OrderNum={DC_OrderRecordControl:FV:OrderNum}";
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
              public void PrintButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"PrintDC_OrderPage.aspx?OrderNum={DC_OrderRecordControl:PK}";
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
                  this.CommitTransaction(sender);
      
            TargetKey = this.Page.Request.QueryString["Target"];

            if(TargetKey != null){
          
            DFKA = this.Page.Request.QueryString["DFKA"];
            if (this.DC_OrderRecordControl!= null && this.DC_OrderRecordControl.DataSource != null){
                id = this.DC_OrderRecordControl.DataSource.OrderNum.ToString();
                value = this.DC_OrderRecordControl.DataSource.GetValue(this.DC_OrderRecordControl.DataSource.TableAccess.TableDefinition.ColumnList.GetByAnyName(DFKA)).ToString();
                if(value == null){
                  value = id;
                }
                BaseClasses.Utils.MiscUtils.RegisterAddButtonScript(this, TargetKey, id, value);
            }
            shouldRedirect = false;
                
          }
          
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
                this.RedirectBack();
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.ShouldSaveControlsToSession = true ; 
            this.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public void SignatureCompleteButton_Click_Base(object sender, EventArgs args)
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
              public void SignPadButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"http://eport2/Signature/sign.aspx?Vessel={DC_OrderRecordControl:NoUrlEncode:FDV:VesselId}&OrderNum={DC_OrderRecordControl:NoUrlEncode:FV:OrderNum}";
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
              public void StopLoadingButton_Click_Base(object sender, EventArgs args)
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
            
              // event handler for Button with Layout
              public void SubmitRevisionButton_Click_Base(object sender, EventArgs args)
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
              public void SyncOrderButton_Click_Base(object sender, EventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderRecordControl.SaveData();
              
                }
        
                if (!this.IsPageRefresh) {
            
                    this.DC_OrderDetailTableControl.SaveData();
              
                }
                  this.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.RollBackTransaction(sender);
                this.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for Button with Layout
              public void TEReceivedButton_Click_Base(object sender, EventArgs args)
              {
              
            string url = @"EditDC_OrderPage.aspx?DC_Order={DC_OrderRecordControl:PK}";
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
  