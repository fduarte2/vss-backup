
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// EditDC_OrderPage.aspx page.  The Row or RecordControl classes are the 
// ideal place to add code customizations. For example, you can override the LoadData, 
// CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.

#region "Using statements"    

using Microsoft.VisualBasic;
using BaseClasses.Web.UI.WebControls;
using System;
using System.Collections;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Utils;

using ReportTools.ReportCreator;
using ReportTools.Shared;

using ePortDC.Business;
using ePortDC.Data;
        

#endregion

  
namespace ePortDC.UI.Controls.EditDC_OrderPage
{
  

#region "Section 1: Place your customizations here."

  
public class DC_OrderDetailTableControl : BaseDC_OrderDetailTableControl
{
    // The BaseDC_OrderDetailTableControl class implements the LoadData, DataBind, CreateWhereClause
    // and other methods to load and display the data in a table control.

    // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
    // The DC_OrderDetailTableControlRow class offers another place where you can customize
    // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

	public DC_OrderDetailTableControl()
	{
	}

    public override void SaveData()
    {
        base.SaveData();
        
		string sToListOrderAlert = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListOrderAlert");

		// Send alert to Expediter if anything changed on the detail lines after pick list complete
		DataAccessSettings dataAccessSettingsObj = DataAccessSettings.Current;
		if(dataAccessSettingsObj.ContainsKey("SendExAlert"))
		{
			string sSendExAlert = (string)dataAccessSettingsObj["SendExAlert"];
			if (sSendExAlert != "")
			{
				string sOrderNum = sSendExAlert;
				if(dataAccessSettingsObj.ContainsKey("OrderDetailChanged"))
				{
					string sOrderDetailChanged = (string)dataAccessSettingsObj["OrderDetailChanged"];
					if(sOrderDetailChanged == "Y")
					{

						this.Page.SystemUtils.SetDataAccessSettingsParameterValue("SendExAlert", "");
						this.Page.SystemUtils.SetDataAccessSettingsParameterValue("OrderDetailChanged", "N");

						// BN - Send an email text to expediter
						PortCustom myPortCustom = new PortCustom();
						string sUserId = this.Page.SystemUtils.GetUserID();
						myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERALERT");
					}
				}
			}
		}
	}

}

public class DC_OrderDetailTableControlRow : BaseDC_OrderDetailTableControlRow
{
	// The BaseDC_OrderDetailTableControlRow implements code for a ROW within the
	// the DC_OrderDetailTableControl table.  The BaseDC_OrderDetailTableControlRow implements the DataBind and SaveData methods.
	// The loading of data is actually performed by the LoadData method in the base class of DC_OrderDetailTableControl.
	
	// This is the ideal place to add your code customizations. For example, you can override the DataBind, 
	// SaveData, GetUIData, and Validate methods.
	
    public DC_OrderDetailTableControlRow()
	{          
		this.Init += new EventHandler(RecordControl_Init);
    	this.Load += new EventHandler(RecordControl_Load);
  	          
	}

    // Occurs when the server control is initialized, which is the first step in the its lifecycle.
    private void RecordControl_Init(object sender, EventArgs e)
    {
        
    } 

    // Occurs when the server control is loaded into the Page object.
    private void RecordControl_Load(object sender, EventArgs e)
    {
		// BN - Catch the OrderSizeId selection change event
	    this.OrderSizeId.AutoPostBack = true;
	    this.OrderSizeId.SelectedIndexChanged += new System.EventHandler(OrderSizeId_SelectedIndexChanged);     
    }

	// BN - Handle the OrderSizeId selected index changed event
	public void OrderSizeId_SelectedIndexChanged(object sender, System.EventArgs e) 
	{ 
	    // Fill the Detail fields drop down list with filtered records, call a function to protect
	    this.FillDetailFields();
	}         

	// BN - Fill the detail fields based on SizeId 
	protected void FillDetailFields()
	{
		// Get data from control because table may still not have the data OR user may have changed it
		string sCustomerId = ((DC_OrderRecordControl)this.Page.FindControl("DC_OrderRecordControl")).CustomerId.SelectedItem.Value.ToString();
		string sCommodityCode = ((DC_OrderRecordControl)this.Page.FindControl("DC_OrderRecordControl")).CommodityCode.SelectedItem.Value.ToString();
	    string sSizeId = OrderSizeId.SelectedItem.Value;  
		string sEffectiveDate = DateTime.Now.ToString();
		string sPrice = "";
		
		//***********************************************************************************
		// Get price from Customer Price and fill here
		//***********************************************************************************

	    // Create the WHERE clause to filter the DC_CommoditySize based on the SizeId
	    WhereClause wc1 = new WhereClause();
	    wc1.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, sSizeId);
	
	    // Get the record using the created where clause.
	    foreach (DC_CommoditySizeRecord itemValue in DC_CommoditySizeTable.GetRecords(wc1, null, 0, 1))
	    {
	        if (itemValue.SizeIdSpecified)
	        {
			    // Create the WHERE clause to get the Price based on CustomerId/CommodityCode/SizeId/EffectiveDate
			    WhereClause wc2 = new WhereClause();
			    wc2.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, sCustomerId);
			    wc2.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, sCommodityCode);
			    wc2.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, sSizeId);
			    wc2.iAND(DC_CustomerPriceTable.EffectiveDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, sEffectiveDate);
			    OrderBy ob2 = new OrderBy(false, false);
			    ob2.AddColumn(DC_CustomerPriceTable.EffectiveDate, BaseClasses.Data.OrderByItem.OrderDir.Desc);
			
			    // Get the record using the created where clause.
			    foreach (DC_CustomerPriceRecord itemValue2 in DC_CustomerPriceTable.GetRecords(wc2, ob2, 0, 1))
			    {
			    	sPrice = itemValue2.Format(DC_CustomerPriceTable.Price);
			    }
		
	            // Fill the details
	            if (this.Price.Text.Trim() == "")
	            	if (sPrice == "")
	            	{
	            		// No price was found in the CustomerPrice table, so default it
	            		this.Price.Text = itemValue.Format(DC_CommoditySizeTable.Price);
	            	}
	            	else
	            	{
	            		// Get price from CustomerPrice table
	            		this.Price.Text = sPrice;
	            	}
	            if (this.SizeLow.Text.Trim() == "")
	            	this.SizeLow.Text = itemValue.Format(DC_CommoditySizeTable.SizeLow);
	            if (this.SizeHigh.Text.Trim() == "")
	            	this.SizeHigh.Text = itemValue.Format(DC_CommoditySizeTable.SizeHigh);
	            if (this.WeightKG.Text.Trim() == "")
	            	this.WeightKG.Text = itemValue.Format(DC_CommoditySizeTable.WeightKG);
	        }
		}
	}


    public override void GetUIData() 
    {
    	// Get before value
		DC_OrderDetailRecord myRecord = this.GetRecord();
		int iOrderQtyOld = myRecord.OrderQty;
		int iOrderSizeIdOld = myRecord.OrderSizeId;

        base.GetUIData();
        
        // Get after value
		int iOrderQtyNew = myRecord.OrderQty;
		int iOrderSizeIdNew = myRecord.OrderSizeId;

		// Compare the old and new values. Available DataChanged function does not work
    	if (iOrderQtyOld != iOrderQtyNew)
    	{
			this.Page.SystemUtils.SetDataAccessSettingsParameterValue("OrderDetailChanged", "Y");
		}

    	if (iOrderSizeIdOld != iOrderSizeIdNew)
    	{
			this.Page.SystemUtils.SetDataAccessSettingsParameterValue("OrderDetailChanged", "Y");
		}
    }
}    

public class DC_OrderRecordControl : BaseDC_OrderRecordControl
{
	// The BaseDC_OrderRecordControl implements the LoadData, DataBind and other
	// methods to load and display the data in a table control.
	
	// This is the ideal place to add your code customizations. For example, you can override the LoadData, 
	// CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
	
	public DC_OrderRecordControl()
	{          
		this.Init += new EventHandler(RecordControl_Init);
		this.Load += new EventHandler(RecordControl_Load);
	}

    // Occurs when the server control is initialized, which is the first step in the its lifecycle.
    private void RecordControl_Init(object sender, EventArgs e)
    {
        
    } 

	// Occurs when the server control is loaded into the Page object.
	private void RecordControl_Load(object sender, EventArgs e)
	{
		// BN - Catch the CustomerId selection change event
		this.CustomerId.AutoPostBack = true;
		this.CustomerId.SelectedIndexChanged += new System.EventHandler(CustomerId_SelectedIndexChanged);
		this.ConsigneeId.AutoPostBack = true;
		this.ConsigneeId.SelectedIndexChanged += new System.EventHandler(ConsigneeId_SelectedIndexChanged);
	    this.VesselId.AutoPostBack = true;
	    this.VesselId.SelectedIndexChanged += new System.EventHandler(VesselId_SelectedIndexChanged);

		// Raise the event so info about Customs Broker and Border Crossing are filled properly
		if (CustomerId.SelectedItem != null)
		{
			CustomerInfoLabel.Text = CustomerId.SelectedItem.Value;
			ConsigneeId_SelectedIndexChanged(sender, e);
		}
	}
	
	public override void GetUIData()
	{
		base.GetUIData();

		DC_OrderRecord myRecord = this.GetRecord();

		// Update date time
		myRecord.LastUpdateDateTime = DateTime.Now;
		
		// Update last user id with logged in user
		myRecord.LastUpdateUser = this.Page.SystemUtils.GetUserID();
		
		// Using a label to pass the button clicked because modifications can't be done in Order class
		switch (this.OrderStatusSubmitLabel.Text)
		{
			case "SubmitButton":
				this.Page.SystemUtils.SetDataAccessSettingsParameterValue("SendExAlert", "");
				if (myRecord.OrderStatusId == 3)
				{
					//Don't change status if pick list already submitted
			    	//myRecord.OrderStatusId = 2;

					// Don't send alert if domestic customer
					if (myRecord.CustomerId == 440)
					{
						this.Page.SystemUtils.SetDataAccessSettingsParameterValue("SendExAlert", "");
					}
					else
					{
						this.Page.SystemUtils.SetDataAccessSettingsParameterValue("SendExAlert", myRecord.OrderNum.Trim());
					}
				}
				else
				{
					if (myRecord.CustomerId == 440)
					{
						// Complete the picklist automaticaly when domestic customer
						myRecord.OrderStatusId = 3;
						
						// Default the TE Received
						myRecord.TEStatus = "RECEIVED";
					}
					else
					{
						myRecord.OrderStatusId = 2;
					}
				}
				break;
			
			// Status 3 - Picklist complete is handedled in Order List page

			case "CheckInDriverButton":
				myRecord.OrderStatusId = 4;
				myRecord.DriverCheckInDateTime = DateTime.Now;
				break;

			case "TEReceivedButton":
				myRecord.TEStatus = "RECEIVED";
				break;
			
			case "StopLoadingButton":
				myRecord.OrderStatusId = 5;
				break;
			
			case "SubmitRevisionButton":
				myRecord.OrderStatusId = 6;
				break;
			
			// Status 7 - Revised Picklist complete is handedled in Order List page

			case "ConfirmOrderButton":
				myRecord.OrderStatusId = 8;
				myRecord.DriverCheckOutDateTime = DateTime.Now;

				if (myRecord.CustomerId != 440)
				{
					string sOrderNum = myRecord.OrderNum.Trim();
					string sToListCustoms = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListCustoms");
					
					// BN - Send an email text to customs
					PortCustom myPortCustom = new PortCustom();
					string sUserId = this.Page.SystemUtils.GetUserID();
					myPortCustom.EmailToQueue(sOrderNum, sUserId, "ORDERCUSTOMS");
				}

				break;
			
			case "SignatureCompleteButton":
				myRecord.OrderStatusId = 9;
				break;
			
			case "CancelOrderButton":
				// The new Cancel page should take care of this, control would never come here 
				myRecord.OrderStatusId = 10;
				break;
	    }
	    
	    //Reset the label
	    this.OrderStatusSubmitLabel.Text = " ";
	} 

	// BN - Handle the CustomerId selected index changed event
	public void CustomerId_SelectedIndexChanged(object sender, System.EventArgs e) 
	{ 
		// Reset info boxes
		CustomerInfoLabel.Text = "**";
		ConsigneeInfoLabel.Text = "**";
		CustomsBrokerInfoLabel.Text = "**";
		BorderCrossingInfoLabel.Text = "**";
		
		// Display Customer Id in label
		if (CustomerId.SelectedItem.Text != "** Please Select **")
		{
			CustomerInfoLabel.Text = CustomerId.SelectedItem.Value;
		}
		
		// Fill the ConsigneeId drop down list with first 500 filtered records, call a function to protect
		this.FillConsigneeIdDropDownOnCustomerChange(500);
	}
	
	// BN - Fill Consignees based on CustomerId 
	protected void FillConsigneeIdDropDownOnCustomerChange(int maxItems)
	{
		// Create the WHERE clause to filter the consignee list based on the CustomerId
		WhereClause wc = new WhereClause();
		string selectedValue = CustomerId.SelectedValue;  
		string selectedText = CustomerId.SelectedItem.Text;   
		wc.iAND(DC_ConsigneeTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, selectedValue);
		
		// Clear the contents of second dropdown list
		this.ConsigneeId.Items.Clear();    
		
		// Add "Please Select" string to second dropdown list.   
		this.ConsigneeId.Items.Insert(0, new ListItem(MiscUtils.GetValueFromResourceFile("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));                      
		
		if(BaseClasses.Utils.StringUtils.InvariantUCase(selectedText).Equals(BaseClasses.Utils.StringUtils.InvariantUCase(MiscUtils.GetValueFromResourceFile("Txt:PleaseSelect", "ePortDC"))))
		{
			// If "Please Select" string is selected for first dropdown list,
			// then do not continue populating the second dropdown list.
			return;
		}    
		
		// Get the records using the created where clause.
		foreach ( DC_ConsigneeRecord itemValue in DC_ConsigneeTable.GetRecords(wc, null, 0, maxItems) )
		{
			if(itemValue.ConsigneeIdSpecified)
			{
				// In each record, obtain the value of second dropdown field if value exists and add to list
				string cvalue = itemValue.ConsigneeId.ToString();
				string fvalue  = itemValue.Format(DC_ConsigneeTable.ConsigneeName);
				ListItem item  = new ListItem(fvalue, cvalue);
				if (! this.ConsigneeId.Items.Contains(item))
				{
					this.ConsigneeId.Items.Add(item);        
				}
		    }        
		}                    
		
		// Select "Please Select" string in the second dropdown list 
		this.ConsigneeId.SelectedIndex = 0;
	}
	
	// BN - Handle the ConsigneeId selected index changed event
	public void ConsigneeId_SelectedIndexChanged(object sender, System.EventArgs e) 
	{ 
		// Reset info boxes
		ConsigneeInfoLabel.Text = "**";
		CustomsBrokerInfoLabel.Text = "**";
		BorderCrossingInfoLabel.Text = "**";
	
		// Display Consignee Id in label
		if (ConsigneeId.SelectedItem.Text != "** Please Select **")
		{
			ConsigneeInfoLabel.Text = ConsigneeId.SelectedItem.Value;

			// Create the WHERE clause to get CustomsBrokerId for the consignee
			string strCustomsBrokerOfficeId = "";
		    WhereClause wc = new WhereClause();
		    string selectedValue = ConsigneeId.SelectedValue;  
		    wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.EqualsTo, selectedValue);
	
			// Get the record using the created where clause.
			foreach (DC_ConsigneeRecord itemValue in DC_ConsigneeTable.GetRecords(wc, null, 0, 1))
			{
				if (itemValue.ConsigneeIdSpecified)
				{
					// Fill the details
					strCustomsBrokerOfficeId = itemValue.CustomsBrokerOfficeId.ToString();
				}
			}

			if (strCustomsBrokerOfficeId != "")
			{
				// Create the WHERE clause to filter the DC_CustomsBrokerOffices based on the ConsigneeId
				WhereClause wc1 = new WhereClause();
				wc1.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, strCustomsBrokerOfficeId);
			
				// Get the record using the created where clause.
				foreach (DC_CustomsBrokerOfficeRecord itemValue in DC_CustomsBrokerOfficeTable.GetRecords(wc1, null, 0, 1))
				{
					if (itemValue.CustomsBrokerOfficeIdSpecified)
					{
						// Fill the details
						CustomsBrokerInfoLabel.Text = strCustomsBrokerOfficeId + " - " + itemValue.CustomsBroker;
						BorderCrossingInfoLabel.Text = itemValue.BorderCrossing;
					}
				}
			}
		}
	}  

	// BN - Handle the VesselId selected index changed event
	public void VesselId_SelectedIndexChanged(object sender, System.EventArgs e) 
	{
		// Show Vessel Id
		VesselInfoLabel.Text = VesselId.SelectedItem.Value;
		
		// Reset info boxes
		FixedFreightInfoLabel.Text = "**";

	    // Fill the Vessel drop down list with first 500 filtered records, call a function to protect
	    this.PopulateVesselIdDropDownList(VesselId.SelectedItem.Value, 500);
	}  	
	
	// Call the stored procedure to update the totals
	public override void RefreshTotalsButton_Click(object sender, EventArgs args)
	{
		PortCustom myPortCustom = new PortCustom();
		myPortCustom.RefreshTotals(this.OrderNum.Text.Trim());

		base.RefreshTotalsButton_Click(sender, args);
	} 
	
	// BN - Remove "** Select Item **" OrderStatus
	protected override void PopulateOrderStatusIdDropDownList(string selectedValue, int maxItems)
	{
		// Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
		base.PopulateOrderStatusIdDropDownList(selectedValue, maxItems);

		// Get the listItem with text "** Please Select **"
		ListItem li = this.OrderStatusId.Items.FindByText("** Please Select **");

		if (!(li == null))
		{
			// If item found the remove it from the list
			this.OrderStatusId.Items.Remove(li);
		}
		this.OrderStatusInfoLabel.Text = OrderStatusId.SelectedItem.Text;
	}
	
	// BN - Remove "** Select Item **" Commodity
	protected override void PopulateCommodityCodeDropDownList(string selectedValue, int maxItems)
	{
		// Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
		base.PopulateCommodityCodeDropDownList(selectedValue, maxItems);

		// Get the listItem with text "** Please Select **"
		ListItem li = this.CommodityCode.Items.FindByText("** Please Select **");

		if (!(li == null))
		{
			// If item found the remove it from the list
			this.CommodityCode.Items.Remove(li);
		}
	}

	// BN - Remove "** Select Item **" Transport Entry status
	protected override void PopulateTEStatusDropDownList(string selectedValue, int maxItems)
	{
		// Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
		base.PopulateTEStatusDropDownList(selectedValue, maxItems);
		
		// Get the listItem with text "** Please Select **"
		ListItem li = this.TEStatus.Items.FindByText("** Please Select **");
		
		if (!(li == null))
		{
			// If item found the remove it from the list
			this.TEStatus.Items.Remove(li);
		}
	}

	// BN - Remove "** Select Item **" Direct Order
	protected override void PopulateDirectOrderDropDownList(string selectedValue, int maxItems)
	{
		// Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
		base.PopulateDirectOrderDropDownList(selectedValue, maxItems);

		// Get the listItem with text "** Please Select **"
		ListItem li = this.DirectOrder.Items.FindByText("** Please Select **");

		if (!(li == null))
		{
			// If item found the remove it from the list
			this.DirectOrder.Items.Remove(li);
		}
	}

	// BN - Remove "** Select Item **" Load Type
	protected override void PopulateLoadTypeDropDownList(string selectedValue, int maxItems)
	{
		// Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
		base.PopulateLoadTypeDropDownList(selectedValue, maxItems);

		// Get the listItem with text "** Please Select **"
		ListItem li = this.LoadType.Items.FindByText("** Please Select **");

		if (!(li == null))
		{
			// If item found the remove it from the list
			this.LoadType.Items.Remove(li);
		}
	}

	// Select the previous vessel select from the cookie 
	protected override void PopulateVesselIdDropDownList(string selectedValue, int maxItems)
	{
	
	    // Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
	    base.PopulateVesselIdDropDownList(selectedValue, maxItems);
	
	    // Get the listItem with text "** Please Select **"
	    ListItem li = this.VesselId.Items.FindByText("** Please Select **");
	
	    if (!(li == null))
	    {
	        // If item found the remove it from the list
	        this.VesselId.Items.Remove(li);
	    }

	    // Get the listItem with value of the vessel id
	    li = this.VesselId.Items.FindByValue(selectedValue);
	    VesselInfoLabel.Text = selectedValue;
	
	    if (!(li == null))
	    {
	        // If item found then select it from the list
	        li.Selected = true;;

			// Get fixed freight for vessel
			WhereClause wc1 = new WhereClause();
			wc1.iAND(DC_VesselTable.VesselId, BaseFilter.ComparisonOperator.EqualsTo, li.Value);
			
			// Get the record using the created where clause.
			foreach (DC_VesselRecord itemValue in DC_VesselTable.GetRecords(wc1, null, 0, 1))
			{
				if (itemValue.VesselIdSpecified)
				{
					this.FixedFreightInfoLabel.Text = itemValue.Format(DC_VesselTable.FixedFreight);
				}
            }
	    }
	    
	}

	protected override void PopulateTransporterIdDropDownList(string selectedValue, int maxItems) 
    {
	    // Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
    	base.PopulateTransporterIdDropDownList(selectedValue, maxItems);
	
	    // Add value in front of the description of all items
	    foreach (ListItem li1 in this.TransporterId.Items)
	    {
	    	if (li1.Text != "** Please Select **")
	    		li1.Text = li1.Text + " (" + li1.Value + ")";
	    }
	}

}

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderDetailTableControlRow control on the EditDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_OrderDetailTableControlRow.
public class BaseDC_OrderDetailTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_OrderDetailTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_OrderDetailTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_OrderDetailRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_OrderDetailRecordRowDeleteButton_Click);
              this.OrderSizeId.SelectedIndexChanged += new EventHandler(OrderSizeId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_OrderDetailTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                  this.Page.Authorize((Control)DC_OrderDetailRecordRowDeleteButton, "ADMIN;DC;PORTADMIN");
            
                // Show confirmation message on Click
                this.DC_OrderDetailRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_OrderDetailTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_OrderDetailTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_OrderDetailTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_OrderDetailRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_OrderDetailTableControlRow.
        public override void DataBind()
        {
            base.DataBind();

            // Make sure that the DataSource is initialized.
            if (this.DataSource == null) {
                return;
            }
        
            // Store the checksum. The checksum is used to
            // ensure the record was not changed by another user.
            if ((this.DataSource.GetCheckSumValue() != null) &&
                (this.CheckSum == null || this.CheckSum.Trim().Length == 0)) {
                this.CheckSum = this.DataSource.GetCheckSumValue().Value;
            }

            // For each field, check to see if a value is specified.  If a value is specified,
            // then format the value for display.  If no value is specified, use the default value (formatted).

        
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.Comments);
                this.Comments1.Text = formattedValue;
                        
            } else {  
                this.Comments1.Text = DC_OrderDetailTable.Comments.Format(DC_OrderDetailTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.OrderQtySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.OrderQty);
                this.OrderQty.Text = formattedValue;
                        
            } else {  
                this.OrderQty.Text = DC_OrderDetailTable.OrderQty.Format(DC_OrderDetailTable.OrderQty.DefaultValue);
            }
                    
            if (this.DataSource.OrderSizeIdSpecified) {
                this.PopulateOrderSizeIdDropDownList(this.DataSource.OrderSizeId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateOrderSizeIdDropDownList(DC_OrderDetailTable.OrderSizeId.DefaultValue, 100);
                } else {
                this.PopulateOrderSizeIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.PriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.Price);
                this.Price.Text = formattedValue;
                        
            } else {  
                this.Price.Text = DC_OrderDetailTable.Price.Format(DC_OrderDetailTable.Price.DefaultValue);
            }
                    
            if (this.DataSource.SizeHighSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.SizeHigh);
                this.SizeHigh.Text = formattedValue;
                        
            } else {  
                this.SizeHigh.Text = DC_OrderDetailTable.SizeHigh.Format(DC_OrderDetailTable.SizeHigh.DefaultValue);
            }
                    
            if (this.DataSource.SizeLowSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.SizeLow);
                this.SizeLow.Text = formattedValue;
                        
            } else {  
                this.SizeLow.Text = DC_OrderDetailTable.SizeLow.Format(DC_OrderDetailTable.SizeLow.DefaultValue);
            }
                    
            if (this.DataSource.WeightKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.WeightKG);
                this.WeightKG.Text = formattedValue;
                        
            } else {  
                this.WeightKG.Text = DC_OrderDetailTable.WeightKG.Format(DC_OrderDetailTable.WeightKG.DefaultValue);
            }
                    
            this.IsNewRecord = true;
            if (this.DataSource.IsCreated) {
                this.IsNewRecord = false;
        
                this.RecordUniqueId = this.DataSource.GetID().ToXmlString();
            }

            

            // Load data for each record and table UI control.
            // Ordering is important because child controls get 
            // their parent ids from their parent UI controls.
            
        }

        //  To customize, override this method in DC_OrderDetailTableControlRow.
        public virtual void SaveData()
        {
            // 1. Load the existing record from the database. Since we save the entire reocrd, this ensures 
            // that fields that are not displayed also properly initialized.
            this.LoadData();
        
            // The checksum is used to ensure the record was not changed by another user.
            if (this.DataSource.GetCheckSumValue() != null) {
                if (this.CheckSum != null && this.CheckSum != this.DataSource.GetCheckSumValue().Value) {
                    throw new Exception(Page.GetResourceValue("Err:RecChangedByOtherUser", "ePortDC"));
                }
            }
        
            // DC_Order in DC_OrderRecordControl is One To Many to DC_OrderDetailTableControl.
                    
            // Setup the parent id in the record.
            DC_OrderRecordControl recDC_OrderRecordControl = (DC_OrderRecordControl)this.Page.FindControlRecursively("DC_OrderRecordControl");
            if (recDC_OrderRecordControl != null && recDC_OrderRecordControl.DataSource == null) {
                // Load the record if it is not loaded yet.
                recDC_OrderRecordControl.LoadData();
            }
            if (recDC_OrderRecordControl == null || recDC_OrderRecordControl.DataSource == null) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:NoParentRecId", "ePortDC"));
            }
                    
            this.DataSource.OrderNum = recDC_OrderRecordControl.DataSource.OrderNum;
            
            // 2. Validate the data.  Override in DC_OrderDetailTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_OrderDetailTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_OrderDetailTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderDetailTableControl")).DataChanged = true;
                ((DC_OrderDetailTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderDetailTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_OrderDetailTableControlRow.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Comments1.Text, DC_OrderDetailTable.Comments);
                          
            this.DataSource.Parse(this.OrderQty.Text, DC_OrderDetailTable.OrderQty);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.OrderSizeId), DC_OrderDetailTable.OrderSizeId);
                  
            this.DataSource.Parse(this.Price.Text, DC_OrderDetailTable.Price);
                          
            this.DataSource.Parse(this.SizeHigh.Text, DC_OrderDetailTable.SizeHigh);
                          
            this.DataSource.Parse(this.SizeLow.Text, DC_OrderDetailTable.SizeLow);
                          
            this.DataSource.Parse(this.WeightKG.Text, DC_OrderDetailTable.WeightKG);
                          
        }

        //  To customize, override this method in DC_OrderDetailTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_OrderDetailTableControlRow.
        public virtual void Validate()
        {
            // Initially empty.  Override to add custom validation.
        }

        public virtual void Delete()
        {
        
            if (this.IsNewRecord) {
                return;
            }

            KeyValue pk = KeyValue.XmlToKey(this.RecordUniqueId);
            DC_OrderDetailTable.DeleteRecord(pk);

          
            ((DC_OrderDetailTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderDetailTableControl")).DataChanged = true;
            ((DC_OrderDetailTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderDetailTableControl")).ResetData = true;
        }

        private void Control_PreRender(object sender, System.EventArgs e)
        {
            try {
                DbUtils.StartTransaction();

                if (!this.Page.ErrorOnPage && (this.Page.IsPageRefresh || this.DataChanged || this.ResetData)) {
                    this.LoadData();
                    this.DataBind();
                }

            } catch (Exception ex) {
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
        }
        
        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);
            string isNewRecord = (string)ViewState["IsNewRecord"];
            if (isNewRecord != null && isNewRecord.Length > 0) {
                this.IsNewRecord = Boolean.Parse(isNewRecord);
            }
            string myCheckSum = (string)ViewState["CheckSum"];
            if (myCheckSum != null && myCheckSum.Length > 0) {
                this.CheckSum = myCheckSum;
            }
        }

        protected override object SaveViewState()
        {
            ViewState["IsNewRecord"] = this.IsNewRecord.ToString();
            ViewState["CheckSum"] = this.CheckSum;
            return base.SaveViewState();
        }
        
        public virtual WhereClause CreateWhereClause_OrderSizeIdDropDownList() {
            return new WhereClause();
        }
                
        // Fill the OrderSizeId list.
        protected virtual void PopulateOrderSizeIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_OrderSizeIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommoditySizeTable.Descr, OrderByItem.OrderDir.Asc);

                      this.OrderSizeId.Items.Clear();
            foreach (DC_CommoditySizeRecord itemValue in DC_CommoditySizeTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.SizeIdSpecified) {
                    cvalue = itemValue.SizeId.ToString();
                    fvalue = itemValue.Format(DC_CommoditySizeTable.Descr);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.OrderSizeId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.OrderSizeId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.OrderSizeId, DC_OrderDetailTable.OrderSizeId.Format(selectedValue))) {
                string fvalue = DC_OrderDetailTable.OrderSizeId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.OrderSizeId.Items.Insert(0, item);
            }

                  
            this.OrderSizeId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              // event handler for ImageButton
              public virtual void DC_OrderDetailRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            if (!this.Page.IsPageRefresh) {
        
                this.Delete();
              
            }
                this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              protected virtual void OrderSizeId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.OrderSizeId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.OrderSizeId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.OrderSizeId, DC_OrderDetailTable.OrderSizeId.Format(selectedValue))) {
              string fvalue = DC_OrderDetailTable.OrderSizeId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.OrderSizeId.Items.Insert(0, item);
              }
              }
            
        private bool _IsNewRecord = true;
        public virtual bool IsNewRecord {
            get {
                return this._IsNewRecord;
            }
            set {
                this._IsNewRecord = value;
            }
        }

        private bool _DataChanged = false;
        public virtual bool DataChanged {
            get {
                return this._DataChanged;
            }
            set {
                this._DataChanged = value;
            }
        }

        private bool _ResetData = false;
        public virtual bool ResetData {
            get {
                return (this._ResetData);
            }
            set {
                this._ResetData = value;
            }
        }
        
        public String RecordUniqueId {
            get {
                return (string)this.ViewState["BaseDC_OrderDetailTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_OrderDetailTableControlRow_Rec"] = value;
            }
        }
        
        private DC_OrderDetailRecord _DataSource;
        public DC_OrderDetailRecord DataSource {
            get {
                return (this._DataSource);
            }
            set {
                this._DataSource = value;
            }
        }

        private string _checkSum;
        public virtual string CheckSum {
            get {
                return (this._checkSum);
            }
            set {
                this._checkSum = value;
            }
        }

#region "Helper Properties"
           
        public System.Web.UI.WebControls.TextBox Comments1 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments1");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDetailRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_OrderDetailRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailRecordRowSelection");
            }
        }
           
        public System.Web.UI.WebControls.TextBox OrderQty {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderQty");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList OrderSizeId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderSizeId");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Price {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Price");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SizeHigh {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHigh");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SizeLow {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeLow");
            }
        }
           
        public System.Web.UI.WebControls.TextBox WeightKG {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "WeightKG");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_OrderDetailRecord rec = null;
            try {
                rec = this.GetRecord();
            }
            catch (Exception ex) {
                // Do Nothing
            }
            
      if (rec == null && url.IndexOf("{") >= 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:RecDataSrcNotInitialized", "ePortDC"));
            }
    
        return ModifyRedirectUrl(url, arg, rec, bEncrypt);
      
        }

        public DC_OrderDetailRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_OrderDetailTable.GetRecord(this.RecordUniqueId, true);
            }
            
            // Localization.
            throw new Exception(Page.GetResourceValue("Err:RetrieveRec", "ePortDC"));
          
        }

        public BaseApplicationPage Page
        {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

#endregion

}

  
// Base class for the DC_OrderDetailTableControl control on the EditDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_OrderDetailTableControl.
public class BaseDC_OrderDetailTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_OrderDetailTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_OrderDetailPagination.FirstPage.Click += new ImageClickEventHandler(DC_OrderDetailPagination_FirstPage_Click);
              this.DC_OrderDetailPagination.LastPage.Click += new ImageClickEventHandler(DC_OrderDetailPagination_LastPage_Click);
              this.DC_OrderDetailPagination.NextPage.Click += new ImageClickEventHandler(DC_OrderDetailPagination_NextPage_Click);
              this.DC_OrderDetailPagination.PageSizeButton.Click += new EventHandler(DC_OrderDetailPagination_PageSizeButton_Click);
            
              this.DC_OrderDetailPagination.PreviousPage.Click += new ImageClickEventHandler(DC_OrderDetailPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.CommentsLabel2.Click += new EventHandler(CommentsLabel2_Click);
            
              this.OrderQtyLabel.Click += new EventHandler(OrderQtyLabel_Click);
            
              this.OrderSizeIdLabel1.Click += new EventHandler(OrderSizeIdLabel1_Click);
            
              this.PriceLabel.Click += new EventHandler(PriceLabel_Click);
            
              this.SizeHighLabel.Click += new EventHandler(SizeHighLabel_Click);
            
              this.SizeLowLabel.Click += new EventHandler(SizeLowLabel_Click);
            
              this.WeightKGLabel.Click += new EventHandler(WeightKGLabel_Click);
            

            // Setup the button events.
        
              this.DC_OrderDetailAddButton.Click += new ImageClickEventHandler(DC_OrderDetailAddButton_Click);
              this.DC_OrderDetailDeleteButton.Click += new ImageClickEventHandler(DC_OrderDetailDeleteButton_Click);
              this.DC_OrderDetailRefreshButton.Click += new ImageClickEventHandler(DC_OrderDetailRefreshButton_Click);
              this.DC_OrderDetailResetButton.Click += new ImageClickEventHandler(DC_OrderDetailResetButton_Click);

            // Setup the filter and search events.
        

            // Control Initializations.
            // Initialize the table's current sort order.
            if (this.InSession(this, "Order_By")) {
                this.CurrentSortOrder = OrderBy.FromXmlString(this.GetFromSession(this, "Order_By", null));
            } else {
                this.CurrentSortOrder = new OrderBy(true, true);
        
                this.CurrentSortOrder.Add(DC_OrderDetailTable.OrderDetailId, OrderByItem.OrderDir.Asc);
        
            }

    // Setup default pagination settings.
    
            this.PageSize = Convert.ToInt32(this.GetFromSession(this, "Page_Size", "10"));
            this.PageIndex = Convert.ToInt32(this.GetFromSession(this, "Page_Index", "0"));
            this.ClearControlsFromSession();
        }

        protected virtual void Control_Load(object sender, EventArgs e)
        {
        
            SaveControlsToSession_Ajax();
        
                  this.Page.Authorize((Control)DC_OrderDetailAddButton, "ADMIN;DC;PORTADMIN");
            
                  this.Page.Authorize((Control)DC_OrderDetailDeleteButton, "ADMIN;DC;PORTADMIN");
            
                // Show confirmation message on Click
                this.DC_OrderDetailDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
        }

        // Read data from database. Returns an array of records that can be assigned
        // to the DataSource table control property.
        public virtual void LoadData()
        {
            try {
            
                // The WHERE clause will be empty when displaying all records in table.
                WhereClause wc = CreateWhereClause();
                if (wc != null && !wc.RunQuery) {
                    // Initialize an empty array of records
                    ArrayList alist = new ArrayList(0);
                    this.DataSource = (DC_OrderDetailRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_OrderDetailTable.GetRecordCount(wc);

                // Go to the last page.
                if (this.TotalPages <= 0 || this.PageIndex < 0) {
                    this.PageIndex = 0;
                } else if (this.DisplayLastPage || this.PageIndex >= this.TotalPages) {
                    this.PageIndex = this.TotalPages - 1;
                }

                // Retrieve the records and set the table DataSource.
                // Only PageSize records are fetched starting at PageIndex (zero based).
                if (this.TotalRecords <= 0) {
                    // Initialize an empty array of records
                    ArrayList alist = new ArrayList(0);
                    this.DataSource = (DC_OrderDetailRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_OrderDetailTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_OrderDetailRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_OrderDetailTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
                }

                // Initialize the page and grand totals. now
            
            } catch (Exception ex) {
                throw ex;
            } finally {
                // Add records to the list.
                this.AddNewRecords();
            }
        }

        // Populate the UI controls.
        public override void DataBind()
        {
            base.DataBind();

            // Make sure that the DataSource is initialized.
            if (this.DataSource == null) {
                return;
            }
        
            // Improve performance by prefetching display as records.
            this.PreFetchForeignKeyValues();

            // Setup the pagination controls.
            BindPaginationControls();

            // Populate all filters data.
        

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_OrderDetailTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_OrderDetailTableControlRow recControl = (DC_OrderDetailTableControlRow)(repItem.FindControl("DC_OrderDetailTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

        
        public void PreFetchForeignKeyValues() {
            if (this.DataSource == null) {
                return;
            }
          
            this.Page.PregetDfkaRecords(DC_OrderDetailTable.OrderSizeId, this.DataSource);
        }
         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_OrderDetailTableControl pagination.
        
            this.DC_OrderDetailPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_OrderDetailPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_OrderDetailPagination.LastPage.Enabled = false;
            }
          
            this.DC_OrderDetailPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_OrderDetailPagination.NextPage.Enabled = false;
            }
          
            this.DC_OrderDetailPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_OrderDetailPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_OrderDetailPagination.CurrentPage.Text = "0";
            }
            this.DC_OrderDetailPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_OrderDetailTotalItems.Text = this.TotalRecords.ToString();
            this.DC_OrderDetailPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_OrderDetailPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_OrderDetailTableControlRow recCtl in this.GetRecordControls())
            {
        
                if (this.InDeletedRecordIds(recCtl)) {
                    recCtl.Delete();
                } else {
                    if (recCtl.Visible) {
                        recCtl.SaveData();
                    }
                }
          
            }
            
            this.DataChanged = true;
            this.ResetData = true;
        }

        protected virtual OrderBy CreateOrderBy()
        {
            return this.CurrentSortOrder;
        }

        // This CreateWhereClause is used for loading the data.
        public virtual WhereClause CreateWhereClause()
        {
            DC_OrderDetailTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        DC_OrderRecordControl parentRecordControl = (DC_OrderRecordControl)(this.Page.FindControlRecursively("DC_OrderRecordControl"));
            DC_OrderRecord parentRec = parentRecordControl.GetRecord();
            if (parentRec == null) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:ParentNotInitialized", "ePortDC"));
            }
           
            if (parentRec.OrderNumSpecified) {
                wc.iAND(DC_OrderDetailTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, parentRec.OrderNum.ToString());
            } else {
                wc.RunQuery = false;
                return wc;
            }
            
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_OrderDetailTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            String recId  = (String)HttpContext.Current.Session["SelectedID"];
            if (recId != null) {
                if (KeyValue.IsXmlKey(recId)) {
                    KeyValue pkValue = KeyValue.XmlToKey(recId);
              
            wc.iAND(DC_OrderDetailTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_OrderTable.OrderNum).ToString());
                
                } else {
              
            wc.iAND(DC_OrderDetailTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, recId);
              
                }
            }
            
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            return wc;
        }
          
        // Formats the result Item and adds it to the list of suggestions.
        public virtual bool FormatSuggestions(String prefixText, String resultItem,
                                              int columnLength, String AutoTypeAheadDisplayFoundText,
                                              String autoTypeAheadSearch, String AutoTypeAheadWordSeparators,
                                              ArrayList resultList)
        {
              int index  = StringUtils.InvariantLCase(resultItem).IndexOf(StringUtils.InvariantLCase(prefixText));
            String itemToAdd = null;
            bool isFound = false;
            bool isAdded = false;
            if (StringUtils.InvariantLCase(autoTypeAheadSearch).Equals("wordsstartingwithsearchstring") && !(index == 0)) {
                 // Expression to find word which contains AutoTypeAheadWordSeparators followed by prefixText
                System.Text.RegularExpressions.Regex regex1 = new System.Text.RegularExpressions.Regex( AutoTypeAheadWordSeparators + prefixText, System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                if (regex1.IsMatch(resultItem)) {
                    index = regex1.Match(resultItem).Index;
                    isFound = true;
                }
                //If the prefixText is found immediatly after white space then starting of the word is found so don not search any further
                if (resultItem[index].ToString() != " ") {
                    // Expression to find beginning of the word which contains AutoTypeAheadWordSeparators followed by prefixText
                    System.Text.RegularExpressions.Regex regex = new System.Text.RegularExpressions.Regex("\\S*" + AutoTypeAheadWordSeparators + prefixText, System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                    if (regex.IsMatch(resultItem)) {
                        index = regex.Match(resultItem).Index;
                        isFound = true;
                    }
                }
            }
            // If autoTypeAheadSearch value is wordsstartingwithsearchstring then, extract the substring only if the prefixText is found at the 
            // beginning of the resultItem (index = 0) or a word in resultItem is found starts with prefixText. 
            if (index == 0 || isFound || StringUtils.InvariantLCase(autoTypeAheadSearch).Equals("anywhereinstring")) {
                if (StringUtils.InvariantLCase(AutoTypeAheadDisplayFoundText).Equals("atbeginningofmatchedstring")) {
                    // Expression to find beginning of the word which contains prefixText
                    System.Text.RegularExpressions.Regex regex1 = new System.Text.RegularExpressions.Regex("\\S*" + prefixText, System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                    //  Find the beginning of the word which contains prefexText
                    if (StringUtils.InvariantLCase(autoTypeAheadSearch).Equals("anywhereinstring") && regex1.IsMatch(resultItem)) {
                        index = regex1.Match(resultItem).Index;
                        isFound = true;
                    }
                    // Display string from the index till end of the string if, sub string from index till end of string is less than columnLength value.
                    if ((resultItem.Length - index) <= columnLength) {
                        if (index == 0) {
                            itemToAdd = resultItem;
                        } else {
                            itemToAdd = "..." + resultItem.Substring(index, resultItem.Length - index);
                        }
                    } else {
                        if (index == 0) {
                          itemToAdd = resultItem.Substring(index, (columnLength - 3)) + "...";
                        } else {
                            // Truncate the string to show only columnLength - 6 characters as begining and trailing "..." has to be appended.
                            itemToAdd = "..." + resultItem.Substring(index, (columnLength - 6)) + "...";
                        }
                    }
                } else if (StringUtils.InvariantLCase(AutoTypeAheadDisplayFoundText).Equals("inmiddleofmatchedstring")) {
                    int subStringBeginIndex = (int)(columnLength / 2);
                    if (resultItem.Length <= columnLength) {
                        itemToAdd = resultItem;
                    } else {
                        // Sanity check at end of the string
                        if ((index + prefixText.Length) == columnLength) {
                            itemToAdd = "..." + resultItem.Substring((index - columnLength), index);
                        } else if ((resultItem.Length - index) < subStringBeginIndex) {
                            //  Display string from the end till columnLength value if, index is closer to the end of the string.
                            itemToAdd = "..." + resultItem.Substring(resultItem.Length - columnLength, resultItem.Length);
                        } else if (index <= subStringBeginIndex) {
                            // Sanity chet at beginning of the string
                            itemToAdd = resultItem.Substring(0, columnLength) + "...";
                        } else {
                            // Display string containing text before the prefixText occures and text after the prefixText
                            itemToAdd = "..." + resultItem.Substring(index - subStringBeginIndex, columnLength) + "...";
                        }
                    }
                } else if (StringUtils.InvariantLCase(AutoTypeAheadDisplayFoundText).Equals("atendofmatchedstring")) {
                     // Expression to find ending of the word which contains prefexText
                    System.Text.RegularExpressions.Regex regex1 = new System.Text.RegularExpressions.Regex("\\s", System.Text.RegularExpressions.RegexOptions.IgnoreCase); 
                    // Find the ending of the word which contains prefexText
                    if (regex1.IsMatch(resultItem, index + 1)) {
                        index = regex1.Match(resultItem, index + 1).Index;
                    }else{
                        // If the word which contains prefexText is the last word in string, regex1.IsMatch returns false.
                        index = resultItem.Length;
                    }
                    
                    if (index > resultItem.Length) {
                        index = resultItem.Length;
                    }
                    // If text from beginning of the string till index is less than columnLength value then, display string from the beginning till index.
                    if (index <= columnLength) {
                        if (index == resultItem.Length) {   //Make decision to append "..."
                            itemToAdd = resultItem.Substring(0, index);
                        } else {
                            itemToAdd = resultItem.Substring(0, index) + "...";
                        }
                    } else if (index == resultItem.Length) {
                        itemToAdd = "..." + resultItem.Substring(index - (columnLength - 3), columnLength - 3);
                    } else {
                        // Truncate the string to show only columnLength - 6 characters as begining and trailing "..." has to be appended.
                        itemToAdd = "..." + resultItem.Substring(index - (columnLength - 6), (columnLength - 6)) + "...";
                    }
                }
                
                // Remove newline character from itemToAdd
                int prefixTextIndex = itemToAdd.IndexOf(prefixText, StringComparison.InvariantCultureIgnoreCase);
                // If itemToAdd contains any newline after the search text then show text only till newline
                System.Text.RegularExpressions.Regex regex2 = new System.Text.RegularExpressions.Regex("(\r\n|\n)", System.Text.RegularExpressions.RegexOptions.IgnoreCase);
                int newLineIndexAfterPrefix = -1;
                if (regex2.IsMatch(itemToAdd, prefixTextIndex)){
                    newLineIndexAfterPrefix = regex2.Match(itemToAdd, prefixTextIndex).Index;
                }
                if ((newLineIndexAfterPrefix > -1)) {
                    if (itemToAdd.EndsWith("...")) {
                        itemToAdd = itemToAdd.Substring(0, newLineIndexAfterPrefix) + "...";
                    }
                    else {
                        itemToAdd = itemToAdd.Substring(0, newLineIndexAfterPrefix);
                    }
                }
                // If itemToAdd contains any newline before search text then show text which comes after newline
                System.Text.RegularExpressions.Regex regex3 = new System.Text.RegularExpressions.Regex("(\r\n|\n)", System.Text.RegularExpressions.RegexOptions.IgnoreCase | System.Text.RegularExpressions.RegexOptions.RightToLeft );
                int newLineIndexBeforePrefix = -1;
                if (regex3.IsMatch(itemToAdd, prefixTextIndex)){
                    newLineIndexBeforePrefix = regex3.Match(itemToAdd, prefixTextIndex).Index;
                }
                if ((newLineIndexBeforePrefix > -1)) {
                    if (itemToAdd.StartsWith("...")) {
                        itemToAdd = "..." + itemToAdd.Substring(newLineIndexBeforePrefix +regex3.Match(itemToAdd, prefixTextIndex).Length);
                    }
                    else {
                        itemToAdd = itemToAdd.Substring(newLineIndexBeforePrefix +regex3.Match(itemToAdd, prefixTextIndex).Length);
                    }
                }

                if (itemToAdd!= null && !resultList.Contains(itemToAdd)) {
                    resultList.Add(itemToAdd);
                    isAdded = true;
                }
            }
            return isAdded;
        }
        
        
    
        protected virtual void GetPageSize()
        {
        
            if (this.DC_OrderDetailPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_OrderDetailPagination.PageSize.Text);
                } catch (Exception ex) {
                }
            }
        }

        protected virtual void AddNewRecords()
        {
            ArrayList newRecordList = new ArrayList();

            // Loop though all the record controls and if the record control
            // does not have a unique record id set, then create a record
            // and add to the list.
            if (!this.ResetData)
            {
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_OrderDetailTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_OrderDetailTableControlRow recControl = (DC_OrderDetailTableControlRow)(repItem.FindControl("DC_OrderDetailTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_OrderDetailRecord rec = new DC_OrderDetailRecord();
        
                        if (recControl.Comments1.Text != "") {
                            rec.Parse(recControl.Comments1.Text, DC_OrderDetailTable.Comments);
                        }
                        if (recControl.OrderQty.Text != "") {
                            rec.Parse(recControl.OrderQty.Text, DC_OrderDetailTable.OrderQty);
                        }
                        if (MiscUtils.IsValueSelected(recControl.OrderSizeId)) {
                            rec.Parse(recControl.OrderSizeId.SelectedItem.Value, DC_OrderDetailTable.OrderSizeId);
                        }
                        if (recControl.Price.Text != "") {
                            rec.Parse(recControl.Price.Text, DC_OrderDetailTable.Price);
                        }
                        if (recControl.SizeHigh.Text != "") {
                            rec.Parse(recControl.SizeHigh.Text, DC_OrderDetailTable.SizeHigh);
                        }
                        if (recControl.SizeLow.Text != "") {
                            rec.Parse(recControl.SizeLow.Text, DC_OrderDetailTable.SizeLow);
                        }
                        if (recControl.WeightKG.Text != "") {
                            rec.Parse(recControl.WeightKG.Text, DC_OrderDetailTable.WeightKG);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_OrderDetailRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_OrderDetailRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_OrderDetailTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_OrderDetailTableControlRow rec)            
        {
            if (this.DeletedRecordIds == null || this.DeletedRecordIds.Length == 0) {
                return (false);
            }

            return (this.DeletedRecordIds.IndexOf("[" + rec.RecordUniqueId + "]") >= 0);
        }

        private String _DeletedRecordIds;
        public String DeletedRecordIds {
            get {
                return (this._DeletedRecordIds);
            }
            set {
                this._DeletedRecordIds = value;
            }
        }
        
        private void Control_PreRender(object sender, System.EventArgs e)
        {
            try {
                DbUtils.StartTransaction();
                
                if (!this.Page.ErrorOnPage && (this.Page.IsPageRefresh || this.DataChanged || this.ResetData)) {
                    this.LoadData();
                    this.DataBind();
                }
                
            } catch (Exception ex) {
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
        }
        
        protected override void SaveControlsToSession()
        {
            base.SaveControlsToSession();

            // Save filter controls to values to session.
        
            
            // Save table control properties to the session.
            if (this.CurrentSortOrder != null) {
                this.SaveToSession(this, "Order_By", this.CurrentSortOrder.ToXmlString());
            }
            this.SaveToSession(this, "Page_Index", this.PageIndex.ToString());
            this.SaveToSession(this, "Page_Size", this.PageSize.ToString());
        
            this.SaveToSession(this, "DeletedRecordIds", this.DeletedRecordIds);
        
        }
        
        protected  void SaveControlsToSession_Ajax()
        {
            // Save filter controls to values to session.
          
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_OrderDetailTableControl_OrderBy"];
            if (orderByStr != null && orderByStr.Length > 0) {
                this.CurrentSortOrder = BaseClasses.Data.OrderBy.FromXmlString(orderByStr);
            } else {
                this.CurrentSortOrder = new OrderBy(true, true);
            }

            if (ViewState["Page_Index"] != null) {
                this.PageIndex = (int)ViewState["Page_Index"];
            }

            if (ViewState["Page_Size"] != null) {
                this.PageSize = (int)ViewState["Page_Size"];
            }
        
            this.DeletedRecordIds = (string)this.ViewState["DeletedRecordIds"];
        
        }

        protected override object SaveViewState()
        {            
            if (this.CurrentSortOrder != null) {
                this.ViewState["DC_OrderDetailTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_OrderDetailPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            this.PageIndex = 0;
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailPagination_LastPage_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            this.DisplayLastPage = true;
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailPagination_NextPage_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            this.PageIndex += 1;
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for LinkButton
              public virtual void DC_OrderDetailPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_OrderDetailPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_OrderDetailPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            if (this.PageIndex > 0) {
                this.PageIndex -= 1;
                this.DataChanged = true;
            }
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            

        // Generate the event handling functions for sorting events.
        
              // event handler for FieldSort
              public virtual void CommentsLabel2_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OrderQtyLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.OrderQty);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.OrderQty, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OrderSizeIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.OrderSizeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.OrderSizeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PriceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.Price);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.Price, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeHighLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.SizeHigh);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.SizeHigh, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeLowLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.SizeLow);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.SizeLow, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void WeightKGLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderDetailTable.WeightKG);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderDetailTable.WeightKG, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_OrderDetailAddButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            this.AddNewRecord = 1;
            this.DataChanged = true;
                this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailDeleteButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            if (!this.Page.IsPageRefresh) {
        
                this.DeleteSelectedRecords(true);
          
            }
                this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_OrderDetailTableControl)(this.Page.FindControlRecursively("DC_OrderDetailTableControl"))).ResetData = true;
                
            ((DC_OrderRecordControl)(this.Page.FindControlRecursively("DC_OrderRecordControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderDetailResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CurrentSortOrder.Reset();
              if (this.InSession(this, "Order_By")) {
              this.CurrentSortOrder = OrderBy.FromXmlString(this.GetFromSession(this, "Order_By", null));
              } else {
              this.CurrentSortOrder = new OrderBy(true, true);
            
              this.CurrentSortOrder.Add(DC_OrderDetailTable.OrderDetailId, OrderByItem.OrderDir.Asc);
            
            }

            this.DataChanged = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            

        // Generate the event handling functions for filter and search events.
        

        // verify the processing details for these properties
        private int _PageSize;
        public int PageSize {
            get {
                return this._PageSize;
            }
            set {
                this._PageSize = value;
            }
        }

        private int _PageIndex;
        public int PageIndex {
            get {
                // _PageSize return (the PageIndex);
                return this._PageIndex;
            }
            set {
                this._PageIndex = value;
            }
        }

        private int _TotalRecords;
        public int TotalRecords {
            get {
                return (this._TotalRecords);
            }
            set {
                if (this.PageSize > 0) {
                    this.TotalPages = Convert.ToInt32(Math.Ceiling(Convert.ToDouble(value) / Convert.ToDouble(this.PageSize)));
                }
                this._TotalRecords = value;
            }
        }

        private int _TotalPages;
        public int TotalPages {
            get {
                return this._TotalPages;
            }
            set {
                this._TotalPages = value;
            }
        }

        private bool _DisplayLastPage;
        public bool DisplayLastPage {
            get {
                return this._DisplayLastPage;
            }
            set {
                this._DisplayLastPage = value;
            }
        }

        private bool _DataChanged = false;
        public bool DataChanged {
            get {
                return this._DataChanged;
            }
            set {
                this._DataChanged = value;
            }
        }

        private bool _ResetData = false;
        public bool ResetData {
            get {
                return this._ResetData;
            }
            set {
                this._ResetData = value;
            }
        }

        private int _AddNewRecord = 0;
        public int AddNewRecord {
            get {
                return this._AddNewRecord;
            }
            set {
                this._AddNewRecord = value;
            }
        }

        private OrderBy _CurrentSortOrder = null;
        public OrderBy CurrentSortOrder {
            get {
                return this._CurrentSortOrder;
            }
            set {
                this._CurrentSortOrder = value;
            }
        }

        private DC_OrderDetailRecord[] _DataSource = null;
        public  DC_OrderDetailRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton CommentsLabel2 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel2");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDetailAddButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailAddButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDetailDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailDeleteButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_OrderDetailPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDetailRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDetailResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDetailTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_OrderDetailToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_OrderDetailTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OrderQtyLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderQtyLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OrderSizeIdLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderSizeIdLabel1");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PriceLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PriceLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton SizeHighLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHighLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton SizeLowLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeLowLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton WeightKGLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "WeightKGLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_OrderDetailTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_OrderDetailRecord rec = null;
                if (recCtl != null) {
                    rec = recCtl.GetRecord();
                }
            
                return ModifyRedirectUrl(url, arg, rec, bEncrypt);
              
            }
            return url;
        }
          
        public int GetSelectedRecordIndex()
        {
            int counter = 0;
            foreach (DC_OrderDetailTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_OrderDetailRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_OrderDetailTableControlRow GetSelectedRecordControl()
        {
        DC_OrderDetailTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_OrderDetailTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_OrderDetailTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_OrderDetailRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_OrderDetailTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.EditDC_OrderPage.DC_OrderDetailTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_OrderDetailTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_OrderDetailTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_OrderDetailRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_OrderDetailTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_OrderDetailTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_OrderDetailTableControlRow recControl = (DC_OrderDetailTableControlRow)repItem.FindControl("DC_OrderDetailTableControlRow");
                recList.Add(recControl);
            }

            return (DC_OrderDetailTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.EditDC_OrderPage.DC_OrderDetailTableControlRow"));
        }

        public BaseApplicationPage Page {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

    #endregion

    

    }
  
// Base class for the DC_OrderRecordControl control on the EditDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_OrderRecordControl.
public class BaseDC_OrderRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_OrderRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
                this.ConsigneeIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.CustomerId.UniqueID + "&DFKA=CustomerName";
                this.ConsigneeIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.ConsigneeIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.ConsigneeIdAddRecordLink.Click += new ImageClickEventHandler(ConsigneeIdAddRecordLink_Click);
                this.TransporterIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.TransporterId.UniqueID + "&DFKA=CarrierName";
                this.TransporterIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.TransporterIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.TransporterIdAddRecordLink.Click += new ImageClickEventHandler(TransporterIdAddRecordLink_Click);
              this.RefreshTotalsButton.Button.Click += new EventHandler(RefreshTotalsButton_Click);
              this.CommodityCode.SelectedIndexChanged += new EventHandler(CommodityCode_SelectedIndexChanged);
            
              this.ConsigneeId.SelectedIndexChanged += new EventHandler(ConsigneeId_SelectedIndexChanged);
            
              this.CustomerId.SelectedIndexChanged += new EventHandler(CustomerId_SelectedIndexChanged);
            
              this.DirectOrder.SelectedIndexChanged += new EventHandler(DirectOrder_SelectedIndexChanged);
            
              this.LoadType.SelectedIndexChanged += new EventHandler(LoadType_SelectedIndexChanged);
            
              this.OrderStatusId.SelectedIndexChanged += new EventHandler(OrderStatusId_SelectedIndexChanged);
            
              this.TEStatus.SelectedIndexChanged += new EventHandler(TEStatus_SelectedIndexChanged);
            
              this.TransporterId.SelectedIndexChanged += new EventHandler(TransporterId_SelectedIndexChanged);
            
              this.VesselId.SelectedIndexChanged += new EventHandler(VesselId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                  this.Page.Authorize((Control)OrderStatusId, "ADMIN");
            
        }

        // Read data from database. To customize, override this method in DC_OrderRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_OrderTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_OrderRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_OrderRecord[] recList = DC_OrderTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_OrderTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_OrderRecordControl.
        public override void DataBind()
        {
            base.DataBind();

            // Make sure that the DataSource is initialized.
            if (this.DataSource == null) {
                return;
            }
        
            // Store the checksum. The checksum is used to
            // ensure the record was not changed by another user.
            if ((this.DataSource.GetCheckSumValue() != null) &&
                (this.CheckSum == null || this.CheckSum.Trim().Length == 0)) {
                this.CheckSum = this.DataSource.GetCheckSumValue().Value;
            }

            // For each field, check to see if a value is specified.  If a value is specified,
            // then format the value for display.  If no value is specified, use the default value (formatted).

        
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.Comments);
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_OrderTable.Comments.Format(DC_OrderTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.CommentsCancelSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CommentsCancel);
                this.CommentsCancel.Text = formattedValue;
                        
            } else {  
                this.CommentsCancel.Text = DC_OrderTable.CommentsCancel.Format(DC_OrderTable.CommentsCancel.DefaultValue);
            }
                    
            if (this.DataSource.CommodityCodeSpecified) {
                this.PopulateCommodityCodeDropDownList(this.DataSource.CommodityCode.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCommodityCodeDropDownList(DC_OrderTable.CommodityCode.DefaultValue, 100);
                } else {
                this.PopulateCommodityCodeDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.ConsigneeIdSpecified) {
                this.PopulateConsigneeIdDropDownList(this.DataSource.ConsigneeId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateConsigneeIdDropDownList(DC_OrderTable.ConsigneeId.DefaultValue, 100);
                } else {
                this.PopulateConsigneeIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.CustomerIdSpecified) {
                this.PopulateCustomerIdDropDownList(this.DataSource.CustomerId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCustomerIdDropDownList(DC_OrderTable.CustomerId.DefaultValue, 100);
                } else {
                this.PopulateCustomerIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.CustomerPOSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerPO);
                this.CustomerPO.Text = formattedValue;
                        
            } else {  
                this.CustomerPO.Text = DC_OrderTable.CustomerPO.Format(DC_OrderTable.CustomerPO.DefaultValue);
            }
                    
            this.DeliveryDate.Attributes.Add("onfocus", "toggleEnableDisableDateFormatter(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
            this.DeliveryDate.Attributes.Add("onblur", "presubmitDateValidation(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
                    
            if (this.DataSource.DeliveryDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DeliveryDate);
                this.DeliveryDate.Text = formattedValue;
                        
            } else {  
                this.DeliveryDate.Text = DC_OrderTable.DeliveryDate.Format(DC_OrderTable.DeliveryDate.DefaultValue);
            }
                    
            if (this.DataSource.DirectOrderSpecified) {
                this.PopulateDirectOrderDropDownList(this.DataSource.DirectOrder, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateDirectOrderDropDownList(DC_OrderTable.DirectOrder.DefaultValue, 100);
                } else {
                this.PopulateDirectOrderDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.DriverNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DriverName);
                this.DriverName.Text = formattedValue;
                        
            } else {  
                this.DriverName.Text = DC_OrderTable.DriverName.Format(DC_OrderTable.DriverName.DefaultValue);
            }
                    
            if (this.DataSource.LoadTypeSpecified) {
                this.PopulateLoadTypeDropDownList(this.DataSource.LoadType, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateLoadTypeDropDownList(DC_OrderTable.LoadType.DefaultValue, 100);
                } else {
                this.PopulateLoadTypeDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.OrderNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderNum);
                this.OrderNum.Text = formattedValue;
                        
            } else {  
                this.OrderNum.Text = DC_OrderTable.OrderNum.Format(DC_OrderTable.OrderNum.DefaultValue);
            }
                    
            if (this.DataSource.OrderStatusIdSpecified) {
                this.PopulateOrderStatusIdDropDownList(this.DataSource.OrderStatusId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateOrderStatusIdDropDownList(DC_OrderTable.OrderStatusId.DefaultValue, 100);
                } else {
                this.PopulateOrderStatusIdDropDownList(null, 100);
                }
            }
                
            this.PickUpDate.Attributes.Add("onfocus", "toggleEnableDisableDateFormatter(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
            this.PickUpDate.Attributes.Add("onblur", "presubmitDateValidation(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
                    
            if (this.DataSource.PickUpDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PickUpDate);
                this.PickUpDate.Text = formattedValue;
                        
            } else {  
                this.PickUpDate.Text = DC_OrderTable.PickUpDate.Format(DC_OrderTable.PickUpDate.DefaultValue);
            }
                    
            if (this.DataSource.SealNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.SealNum);
                this.SealNum.Text = formattedValue;
                        
            } else {  
                this.SealNum.Text = DC_OrderTable.SealNum.Format(DC_OrderTable.SealNum.DefaultValue);
            }
                    
            if (this.DataSource.SNMGNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.SNMGNum);
                this.SNMGNum.Text = formattedValue;
                        
            } else {  
                this.SNMGNum.Text = DC_OrderTable.SNMGNum.Format(DC_OrderTable.SNMGNum.DefaultValue);
            }
                    
            if (this.DataSource.TEStatusSpecified) {
                this.PopulateTEStatusDropDownList(this.DataSource.TEStatus, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateTEStatusDropDownList(DC_OrderTable.TEStatus.DefaultValue, 100);
                } else {
                this.PopulateTEStatusDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.TotalBoxDamagedSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalBoxDamaged);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalBoxDamaged.Text = formattedValue;
                        
            } else {  
                this.TotalBoxDamaged.Text = DC_OrderTable.TotalBoxDamaged.Format(DC_OrderTable.TotalBoxDamaged.DefaultValue);
            }
                    
            if (this.DataSource.TotalCountSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalCount);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalCount.Text = formattedValue;
                        
            } else {  
                this.TotalCount.Text = DC_OrderTable.TotalCount.Format(DC_OrderTable.TotalCount.DefaultValue);
            }
                    
            if (this.DataSource.TotalPalletCountSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalPalletCount);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalPalletCount.Text = formattedValue;
                        
            } else {  
                this.TotalPalletCount.Text = DC_OrderTable.TotalPalletCount.Format(DC_OrderTable.TotalPalletCount.DefaultValue);
            }
                    
            if (this.DataSource.TotalPriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalPrice);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalPrice.Text = formattedValue;
                        
            } else {  
                this.TotalPrice.Text = DC_OrderTable.TotalPrice.Format(DC_OrderTable.TotalPrice.DefaultValue);
            }
                    
            if (this.DataSource.TotalQuantityKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalQuantityKG);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalQuantityKG.Text = formattedValue;
                        
            } else {  
                this.TotalQuantityKG.Text = DC_OrderTable.TotalQuantityKG.Format(DC_OrderTable.TotalQuantityKG.DefaultValue);
            }
                    
            if (this.DataSource.TrailerNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TrailerNum);
                this.TrailerNum.Text = formattedValue;
                        
            } else {  
                this.TrailerNum.Text = DC_OrderTable.TrailerNum.Format(DC_OrderTable.TrailerNum.DefaultValue);
            }
                    
            if (this.DataSource.TransportChargesSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TransportCharges);
                this.TransportCharges.Text = formattedValue;
                        
            } else {  
                this.TransportCharges.Text = DC_OrderTable.TransportCharges.Format(DC_OrderTable.TransportCharges.DefaultValue);
            }
                    
            if (this.DataSource.TransporterIdSpecified) {
                this.PopulateTransporterIdDropDownList(this.DataSource.TransporterId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateTransporterIdDropDownList(DC_OrderTable.TransporterId.DefaultValue, 100);
                } else {
                this.PopulateTransporterIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.TruckTagSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TruckTag);
                this.TruckTag.Text = formattedValue;
                        
            } else {  
                this.TruckTag.Text = DC_OrderTable.TruckTag.Format(DC_OrderTable.TruckTag.DefaultValue);
            }
                    
            if (this.DataSource.VesselIdSpecified) {
                this.PopulateVesselIdDropDownList(this.DataSource.VesselId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateVesselIdDropDownList(DC_OrderTable.VesselId.DefaultValue, 100);
                } else {
                this.PopulateVesselIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.ZUser1Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.ZUser1);
                this.ZUser1.Text = formattedValue;
                        
            } else {  
                this.ZUser1.Text = DC_OrderTable.ZUser1.Format(DC_OrderTable.ZUser1.DefaultValue);
            }
                    
            if (this.DataSource.ZUser2Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.ZUser2);
                this.ZUser2.Text = formattedValue;
                        
            } else {  
                this.ZUser2.Text = DC_OrderTable.ZUser2.Format(DC_OrderTable.ZUser2.DefaultValue);
            }
                    
            this.IsNewRecord = true;
            if (this.DataSource.IsCreated) {
                this.IsNewRecord = false;
        
                this.RecordUniqueId = this.DataSource.GetID().ToXmlString();
            }

            

            // Load data for each record and table UI control.
            // Ordering is important because child controls get 
            // their parent ids from their parent UI controls.
            
        }

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual void SaveData()
        {
            // 1. Load the existing record from the database. Since we save the entire reocrd, this ensures 
            // that fields that are not displayed also properly initialized.
            this.LoadData();
        
            // The checksum is used to ensure the record was not changed by another user.
            if (this.DataSource.GetCheckSumValue() != null) {
                if (this.CheckSum != null && this.CheckSum != this.DataSource.GetCheckSumValue().Value) {
                    throw new Exception(Page.GetResourceValue("Err:RecChangedByOtherUser", "ePortDC"));
                }
            }
        
            // 2. Validate the data.  Override in DC_OrderRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_OrderRecordControl to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Comments.Text, DC_OrderTable.Comments);
                          
            this.DataSource.Parse(this.CommentsCancel.Text, DC_OrderTable.CommentsCancel);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CommodityCode), DC_OrderTable.CommodityCode);
                  
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.ConsigneeId), DC_OrderTable.ConsigneeId);
                  
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CustomerId), DC_OrderTable.CustomerId);
                  
            this.DataSource.Parse(this.CustomerPO.Text, DC_OrderTable.CustomerPO);
                          
            this.DataSource.Parse(this.DeliveryDate.Text, DC_OrderTable.DeliveryDate);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.DirectOrder), DC_OrderTable.DirectOrder);
                  
            this.DataSource.Parse(this.DriverName.Text, DC_OrderTable.DriverName);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.LoadType), DC_OrderTable.LoadType);
                  
            this.DataSource.Parse(this.OrderNum.Text, DC_OrderTable.OrderNum);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.OrderStatusId), DC_OrderTable.OrderStatusId);
                  
            this.DataSource.Parse(this.PickUpDate.Text, DC_OrderTable.PickUpDate);
                          
            this.DataSource.Parse(this.SealNum.Text, DC_OrderTable.SealNum);
                          
            this.DataSource.Parse(this.SNMGNum.Text, DC_OrderTable.SNMGNum);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.TEStatus), DC_OrderTable.TEStatus);
                  
            this.DataSource.Parse(this.TrailerNum.Text, DC_OrderTable.TrailerNum);
                          
            this.DataSource.Parse(this.TransportCharges.Text, DC_OrderTable.TransportCharges);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.TransporterId), DC_OrderTable.TransporterId);
                  
            this.DataSource.Parse(this.TruckTag.Text, DC_OrderTable.TruckTag);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.VesselId), DC_OrderTable.VesselId);
                  
            this.DataSource.Parse(this.ZUser1.Text, DC_OrderTable.ZUser1);
                          
            this.DataSource.Parse(this.ZUser2.Text, DC_OrderTable.ZUser2);
                          
        }

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_OrderTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_Order"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_Order"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_OrderTable.OrderNum).ToString());
            } else {
                
                wc.iAND(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual void Validate()
        {
            // Initially empty.  Override to add custom validation.
        }

        public virtual void Delete()
        {
        
            if (this.IsNewRecord) {
                return;
            }

            KeyValue pk = KeyValue.XmlToKey(this.RecordUniqueId);
            DC_OrderTable.DeleteRecord(pk);

          
        }

        private void Control_PreRender(object sender, System.EventArgs e)
        {
            try {
                DbUtils.StartTransaction();

                if (!this.Page.ErrorOnPage && (this.Page.IsPageRefresh || this.DataChanged || this.ResetData)) {
                    this.LoadData();
                    this.DataBind();
                }

            } catch (Exception ex) {
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
        }
        
        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);
            string isNewRecord = (string)ViewState["IsNewRecord"];
            if (isNewRecord != null && isNewRecord.Length > 0) {
                this.IsNewRecord = Boolean.Parse(isNewRecord);
            }
            string myCheckSum = (string)ViewState["CheckSum"];
            if (myCheckSum != null && myCheckSum.Length > 0) {
                this.CheckSum = myCheckSum;
            }
        }

        protected override object SaveViewState()
        {
            ViewState["IsNewRecord"] = this.IsNewRecord.ToString();
            ViewState["CheckSum"] = this.CheckSum;
            return base.SaveViewState();
        }
        
        public virtual WhereClause CreateWhereClause_CommodityCodeDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_ConsigneeIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_CustomerIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_DirectOrderDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_LoadTypeDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_OrderStatusIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_TEStatusDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_TransporterIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_VesselIdDropDownList() {
            return new WhereClause();
        }
                
        // Fill the CommodityCode list.
        protected virtual void PopulateCommodityCodeDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_CommodityCodeDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommodityTable.CommodityName, OrderByItem.OrderDir.Asc);

                      this.CommodityCode.Items.Clear();
            foreach (DC_CommodityRecord itemValue in DC_CommodityTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.CommodityCodeSpecified) {
                    cvalue = itemValue.CommodityCode.ToString();
                    fvalue = itemValue.Format(DC_CommodityTable.CommodityName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.CommodityCode.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.CommodityCode, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.CommodityCode, DC_OrderTable.CommodityCode.Format(selectedValue))) {
                string fvalue = DC_OrderTable.CommodityCode.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CommodityCode.Items.Insert(0, item);
            }

                  
            this.CommodityCode.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the ConsigneeId list.
        protected virtual void PopulateConsigneeIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_ConsigneeIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_ConsigneeTable.ConsigneeName, OrderByItem.OrderDir.Asc);

                      this.ConsigneeId.Items.Clear();
            foreach (DC_ConsigneeRecord itemValue in DC_ConsigneeTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.ConsigneeIdSpecified) {
                    cvalue = itemValue.ConsigneeId.ToString();
                    fvalue = itemValue.Format(DC_ConsigneeTable.ConsigneeName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.ConsigneeId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.ConsigneeId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.ConsigneeId, DC_OrderTable.ConsigneeId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.ConsigneeId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.ConsigneeId.Items.Insert(0, item);
            }

                  
            this.ConsigneeId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the CustomerId list.
        protected virtual void PopulateCustomerIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_CustomerIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerName, OrderByItem.OrderDir.Asc);

                      this.CustomerId.Items.Clear();
            foreach (DC_CustomerRecord itemValue in DC_CustomerTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.CustomerIdSpecified) {
                    cvalue = itemValue.CustomerId.ToString();
                    fvalue = itemValue.Format(DC_CustomerTable.CustomerName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.CustomerId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.CustomerId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.CustomerId, DC_OrderTable.CustomerId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.CustomerId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CustomerId.Items.Insert(0, item);
            }

                  
            this.CustomerId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the DirectOrder list.
        protected virtual void PopulateDirectOrderDropDownList
                (string selectedValue, int maxItems) {
                  
            this.DirectOrder.Items.Clear();
                      
            this.DirectOrder.Items.Add(new ListItem("DIRECT", "DIRECT"));
            this.DirectOrder.Items.Add(new ListItem("OTHER", "OTHER"));
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.DirectOrder, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.DirectOrder, DC_OrderTable.DirectOrder.Format(selectedValue))) {
                string fvalue = DC_OrderTable.DirectOrder.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.DirectOrder.Items.Insert(0, item);
            }

                  
            this.DirectOrder.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the LoadType list.
        protected virtual void PopulateLoadTypeDropDownList
                (string selectedValue, int maxItems) {
                  
            this.LoadType.Items.Clear();
                      
            this.LoadType.Items.Add(new ListItem("CUSTOMER LOAD", "CUSTOMER LOAD"));
            this.LoadType.Items.Add(new ListItem("REGRADE LOAD", "REGRADE LOAD"));
            this.LoadType.Items.Add(new ListItem("HOSPITAL LOAD", "HOSPITAL LOAD"));
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.LoadType, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.LoadType, DC_OrderTable.LoadType.Format(selectedValue))) {
                string fvalue = DC_OrderTable.LoadType.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.LoadType.Items.Insert(0, item);
            }

                  
            this.LoadType.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the OrderStatusId list.
        protected virtual void PopulateOrderStatusIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_OrderStatusIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_OrderStatusTable.Descr, OrderByItem.OrderDir.Asc);

                      this.OrderStatusId.Items.Clear();
            foreach (DC_OrderStatusRecord itemValue in DC_OrderStatusTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.OrderStatusIdSpecified) {
                    cvalue = itemValue.OrderStatusId.ToString();
                    fvalue = itemValue.Format(DC_OrderStatusTable.Descr);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.OrderStatusId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.OrderStatusId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.OrderStatusId, DC_OrderTable.OrderStatusId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.OrderStatusId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.OrderStatusId.Items.Insert(0, item);
            }

                  
            this.OrderStatusId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the TEStatus list.
        protected virtual void PopulateTEStatusDropDownList
                (string selectedValue, int maxItems) {
                  
            this.TEStatus.Items.Clear();
                      
            this.TEStatus.Items.Add(new ListItem("PENDING", "PENDING"));
            this.TEStatus.Items.Add(new ListItem("RECEIVED", "RECEIVED"));
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.TEStatus, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.TEStatus, DC_OrderTable.TEStatus.Format(selectedValue))) {
                string fvalue = DC_OrderTable.TEStatus.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.TEStatus.Items.Insert(0, item);
            }

                  
            this.TEStatus.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the TransporterId list.
        protected virtual void PopulateTransporterIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_TransporterIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_TransporterTable.CarrierName, OrderByItem.OrderDir.Asc);

                      this.TransporterId.Items.Clear();
            foreach (DC_TransporterRecord itemValue in DC_TransporterTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.TransporterIdSpecified) {
                    cvalue = itemValue.TransporterId.ToString();
                    fvalue = itemValue.Format(DC_TransporterTable.CarrierName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.TransporterId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.TransporterId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.TransporterId, DC_OrderTable.TransporterId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.TransporterId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.TransporterId.Items.Insert(0, item);
            }

                  
            this.TransporterId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the VesselId list.
        protected virtual void PopulateVesselIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_VesselIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_VesselTable.VesselName, OrderByItem.OrderDir.Asc);

                      this.VesselId.Items.Clear();
            foreach (DC_VesselRecord itemValue in DC_VesselTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.VesselIdSpecified) {
                    cvalue = itemValue.VesselId.ToString();
                    fvalue = itemValue.Format(DC_VesselTable.VesselName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.VesselId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.VesselId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.VesselId, DC_OrderTable.VesselId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.VesselId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.VesselId.Items.Insert(0, item);
            }

                  
            this.VesselId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              // event handler for ImageButton
              public virtual void ConsigneeIdAddRecordLink_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/AddDC_ConsigneePage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = this.ModifyRedirectUrl(url, "",false);
                url = this.Page.ModifyRedirectUrl(url, "",false);
                        this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                shouldRedirect = false;
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.Page.ShouldSaveControlsToSession = true;
                this.Page.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.Page.ShouldSaveControlsToSession = true ; 
            this.Page.CloseWindow(true);
            }
        
              }
            
              // event handler for ImageButton
              public virtual void TransporterIdAddRecordLink_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/AddDC_TransporterPage.aspx";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = this.ModifyRedirectUrl(url, "",false);
                url = this.Page.ModifyRedirectUrl(url, "",false);
                        this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                shouldRedirect = false;
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.Page.ShouldSaveControlsToSession = true;
                this.Page.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.Page.ShouldSaveControlsToSession = true ; 
            this.Page.CloseWindow(true);
            }
        
              }
            
              // event handler for Button with Layout
              public virtual void RefreshTotalsButton_Click(object sender, EventArgs args)
              {
              
            string url = @"EditDC_OrderPage.aspx?DC_Order={DC_OrderRecordControl:PK}";
            bool shouldRedirect = true;
            string TargetKey = null;
            string DFKA = null;
            string id = null;
            string value = null;
            try {
                DbUtils.StartTransaction();
                
                url = this.ModifyRedirectUrl(url, "",false);
                url = this.Page.ModifyRedirectUrl(url, "",false);
                        this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                shouldRedirect = false;
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
                DbUtils.EndTransaction();
            }
            if (shouldRedirect) {
                this.Page.ShouldSaveControlsToSession = true;
                this.Page.Response.Redirect(url);
            }
        
            else if (TargetKey != null && !shouldRedirect){
            this.Page.ShouldSaveControlsToSession = true ; 
            this.Page.CloseWindow(true);
            }
        
              }
            
              protected virtual void CommodityCode_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.CommodityCode);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.CommodityCode, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.CommodityCode, DC_OrderTable.CommodityCode.Format(selectedValue))) {
              string fvalue = DC_OrderTable.CommodityCode.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CommodityCode.Items.Insert(0, item);
              }
              }
            
              protected virtual void ConsigneeId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.ConsigneeId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.ConsigneeId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.ConsigneeId, DC_OrderTable.ConsigneeId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.ConsigneeId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.ConsigneeId.Items.Insert(0, item);
              }
              }
            
              protected virtual void CustomerId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.CustomerId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.CustomerId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.CustomerId, DC_OrderTable.CustomerId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.CustomerId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CustomerId.Items.Insert(0, item);
              }
              }
            
              protected virtual void DirectOrder_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.DirectOrder);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.DirectOrder, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.DirectOrder, DC_OrderTable.DirectOrder.Format(selectedValue))) {
              string fvalue = DC_OrderTable.DirectOrder.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.DirectOrder.Items.Insert(0, item);
              }
              }
            
              protected virtual void LoadType_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.LoadType);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.LoadType, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.LoadType, DC_OrderTable.LoadType.Format(selectedValue))) {
              string fvalue = DC_OrderTable.LoadType.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.LoadType.Items.Insert(0, item);
              }
              }
            
              protected virtual void OrderStatusId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.OrderStatusId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.OrderStatusId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.OrderStatusId, DC_OrderTable.OrderStatusId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.OrderStatusId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.OrderStatusId.Items.Insert(0, item);
              }
              }
            
              protected virtual void TEStatus_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.TEStatus);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.TEStatus, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.TEStatus, DC_OrderTable.TEStatus.Format(selectedValue))) {
              string fvalue = DC_OrderTable.TEStatus.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.TEStatus.Items.Insert(0, item);
              }
              }
            
              protected virtual void TransporterId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.TransporterId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.TransporterId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.TransporterId, DC_OrderTable.TransporterId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.TransporterId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.TransporterId.Items.Insert(0, item);
              }
              }
            
              protected virtual void VesselId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.VesselId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.VesselId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.VesselId, DC_OrderTable.VesselId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.VesselId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.VesselId.Items.Insert(0, item);
              }
              }
            
        private bool _IsNewRecord = true;
        public virtual bool IsNewRecord {
            get {
                return this._IsNewRecord;
            }
            set {
                this._IsNewRecord = value;
            }
        }

        private bool _DataChanged = false;
        public virtual bool DataChanged {
            get {
                return this._DataChanged;
            }
            set {
                this._DataChanged = value;
            }
        }

        private bool _ResetData = false;
        public virtual bool ResetData {
            get {
                return (this._ResetData);
            }
            set {
                this._ResetData = value;
            }
        }
        
        public String RecordUniqueId {
            get {
                return (string)this.ViewState["BaseDC_OrderRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_OrderRecordControl_Rec"] = value;
            }
        }
        
        private DC_OrderRecord _DataSource;
        public DC_OrderRecord DataSource {
            get {
                return (this._DataSource);
            }
            set {
                this._DataSource = value;
            }
        }

        private string _checkSum;
        public virtual string CheckSum {
            get {
                return (this._checkSum);
            }
            set {
                this._checkSum = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.Label BorderCrossingInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingInfoLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label BorderCrossingLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Comments {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CommentsCancel {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsCancel");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommentsCancelLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsCancelLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommentsLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList CommodityCode {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList ConsigneeId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton ConsigneeIdAddRecordLink {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdAddRecordLink");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label ConsigneeInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeInfoLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList CustomerId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label CustomerInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerInfoLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomerPO {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPO");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerPOLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPOLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label CustomsBrokerInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerInfoLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label CustomsBrokerLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox DeliveryDate {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal DeliveryDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList DirectOrder {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DirectOrder");
            }
        }
        
        public System.Web.UI.WebControls.Literal DirectOrderLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DirectOrderLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox DriverName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverName");
            }
        }
        
        public System.Web.UI.WebControls.Literal DriverNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label FixedFreightInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FixedFreightInfoLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label FixedFreightLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FixedFreightLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList LoadType {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadType");
            }
        }
        
        public System.Web.UI.WebControls.Literal LoadTypeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadTypeLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox OrderNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList OrderStatusId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusId");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderStatusIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label OrderStatusInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusInfoLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label OrderStatusSubmitLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusSubmitLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PickUpDate {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal PickUpDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateLabel");
            }
        }
        
        public ePortDC.UI.IThemeButton RefreshTotalsButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "RefreshTotalsButton");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SealNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SealNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal SealNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SealNumLabel");
            }
        }
        
        public System.Web.UI.WebControls.Image SignatureImage {
            get {
                return (System.Web.UI.WebControls.Image)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SignatureImage");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SNMGNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SNMGNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal SNMGNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SNMGNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList TEStatus {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatus");
            }
        }
        
        public System.Web.UI.WebControls.Literal TEStatusLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatusLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalBoxDamaged {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalBoxDamaged");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalBoxDamagedLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalBoxDamagedLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalCount {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCount");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalCountLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCountLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalPalletCount {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCount");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalPalletCountLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCountLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalPrice {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPrice");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalPriceLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPriceLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalQuantityKG {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalQuantityKG");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalQuantityKGLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalQuantityKGLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox TrailerNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal TrailerNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox TransportCharges {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransportCharges");
            }
        }
        
        public System.Web.UI.WebControls.Literal TransportChargesLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransportChargesLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList TransporterId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton TransporterIdAddRecordLink {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdAddRecordLink");
            }
        }
        
        public System.Web.UI.WebControls.Literal TransporterIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox TruckTag {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTag");
            }
        }
        
        public System.Web.UI.WebControls.Literal TruckTagLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTagLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList VesselId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselId");
            }
        }
        
        public System.Web.UI.WebControls.Literal VesselIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Label VesselInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselInfoLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox ZUser1 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ZUser1");
            }
        }
        
        public System.Web.UI.WebControls.Literal ZUser1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ZUser1Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox ZUser2 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ZUser2");
            }
        }
        
        public System.Web.UI.WebControls.Literal ZUser2Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ZUser2Label");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_OrderRecord rec = null;
            try {
                rec = this.GetRecord();
            }
            catch (Exception ex) {
                // Do Nothing
            }
            
      if (rec == null && url.IndexOf("{") >= 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:RecDataSrcNotInitialized", "ePortDC"));
            }
    
        return ModifyRedirectUrl(url, arg, rec, bEncrypt);
      
        }

        public DC_OrderRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_OrderTable.GetRecord(this.RecordUniqueId, true);
            }
            
            // Localization.
            throw new Exception(Page.GetResourceValue("Err:RetrieveRec", "ePortDC"));
          
        }

        public BaseApplicationPage Page
        {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

#endregion

}

  

#endregion
    
  
}

  