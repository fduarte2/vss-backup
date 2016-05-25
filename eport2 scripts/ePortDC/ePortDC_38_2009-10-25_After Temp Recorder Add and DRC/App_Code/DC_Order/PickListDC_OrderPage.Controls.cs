
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// PickListDC_OrderPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.PickListDC_OrderPage
{
  

#region "Section 1: Place your customizations here."

public class DC_PickListTableControl : BaseDC_PickListTableControl
{

    // The BaseDC_PickListTableControl class implements the LoadData, DataBind, CreateWhereClause
    // and other methods to load and display the data in a table control.

    // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
    // The DC_PickListTableControlRow class offers another place where you can customize
    // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.
	public DC_PickListTableControl()
	{

	}
 
    public override void SaveData()
    {
        base.SaveData();
        
		DC_PickListTableControlRow[] oTable = this.GetRecordControls();
		if (oTable.Length > 0)
		{
			// Get number of deleted records from the records
			int iDeletedRecords = 0;
            if (this.DeletedRecordIds != null)
            {
                string[] sKeys = this.DeletedRecordIds.Split(new string[] { "," }, System.StringSplitOptions.RemoveEmptyEntries);
                iDeletedRecords = sKeys.Length;
            }

            // Pick list exists if there are any savable records
			if ((oTable.Length - iDeletedRecords) <= 0)
			{
				this.Page.SystemUtils.SetDataAccessSettingsParameterValue("PickListExists", "N");        
			}
            else
            {
                this.Page.SystemUtils.SetDataAccessSettingsParameterValue("PickListExists", "Y");
            }
        }
		else
		{
			this.Page.SystemUtils.SetDataAccessSettingsParameterValue("PickListExists", "N");        
		}
    }

}

public class DC_PickListTableControlRow : BaseDC_PickListTableControlRow
{

    // The BaseDC_PickListTableControlRow implements code for a ROW within the
    // the DC_PickListTableControl table.  The BaseDC_PickListTableControlRow implements the DataBind and SaveData methods.
    // The loading of data is actually performed by the LoadData method in the base class of DC_PickListTableControl.

    // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
    // SaveData, GetUIData, and Validate methods.

	public DC_PickListTableControlRow()
	{          
	    this.Load += new System.EventHandler(RecordControl_Load);
	}

	private void RecordControl_Load(object sender , System.EventArgs e)
	{
	    this.OrderDetailId.AutoPostBack = true;
	    this.OrderDetailId.SelectedIndexChanged += new System.EventHandler(OrderDetailId_SelectedIndexChanged);
	}

	public void OrderDetailId_SelectedIndexChanged(object sender, System.EventArgs e) 
	{ 
		// Default SizeLow value
		if (OrderDetailId.SelectedItem.Text != "** Please Select **")
		{
			string sSizeLow = "";

			// Create the WHERE clause to get CustomsBrokerId for the consignee
		    WhereClause wc = new WhereClause();
		    string sOrderNum = this.Page.Request.QueryString.Get("DC_Order").Trim();
		    string sOrderDetailId = OrderDetailId.SelectedValue;  
		    wc.iAND(DC_OrderDetailTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, sOrderNum);
		    wc.iAND(DC_OrderDetailTable.OrderDetailId, BaseFilter.ComparisonOperator.EqualsTo, sOrderDetailId);
	
			// Get the record using the created where clause.
			foreach (DC_OrderDetailRecord itemValue in DC_OrderDetailTable.GetRecords(wc, null, 0, 1))
			{
				if (itemValue.OrderDetailIdSpecified)
				{
					sSizeLow = itemValue.SizeLow.ToString();
				}
			}

			if (sSizeLow != "")
			{
				PickListSize.Text = sSizeLow;
			}
		}
	}

    // This method allows adding of additional logic after data is retrieved from the 
    // User Interface - but before it is saved into the database.    
    public override void GetUIData() 
    {
        base.GetUIData();

		// Save the order number with the record as no automatic master/detail with pick list table
		DC_PickListRecord myRecord = this.GetRecord();
		myRecord.OrderNum = this.Page.Request.QueryString.Get("DC_Order").Trim();
    }

	protected override void PopulateOrderDetailIdDropDownList(string selectedValue, int maxItems)
	{
	    // Call the base.PopulatePackHouseIdDropDownList to populate the dropdown list
	    base.PopulateOrderDetailIdDropDownList(selectedValue, maxItems);
	    
		string sOrderNum = this.Page.Request.QueryString.Get("DC_Order").Trim();

	    // Remove all items
	    this.OrderDetailId.Items.Clear();

		// Add "Please Select" string to second dropdown list.   
		this.OrderDetailId.Items.Insert(0, new ListItem(MiscUtils.GetValueFromResourceFile("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));                      
		
		// Get detail items for the order
		WhereClause wc = new WhereClause();
		wc.iAND(DC_OrderDetailTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, sOrderNum);
		
		// Get the record using the created where clause.
		foreach (DC_OrderDetailRecord itemValue in DC_OrderDetailTable.GetRecords(wc, null, 0, 100))
		{
			if (itemValue.OrderNumSpecified)
			{
                ListItem li = new ListItem();
                li.Value = itemValue.OrderDetailId.ToString().Trim();
                li.Text = itemValue.SizeLow + " - " + itemValue.SizeHigh + ", " + itemValue.WeightKG + " KG";
                if (selectedValue == itemValue.OrderDetailId.ToString())
                {
                	li.Selected = true;
                }
				this.OrderDetailId.Items.Add(li);
			}
        }

	}

	protected override void PopulatePackHouseIdDropDownList(string selectedValue, int maxItems)
	{
	    // Call the base.PopulatePackHouseIdDropDownList to populate the dropdown list
	    base.PopulatePackHouseIdDropDownList(selectedValue, maxItems);
	
	    // Add value in front of the description of all items
	    foreach (ListItem li in this.PackHouseId.Items)
	    {
	    	if (li.Text != "** Please Select **")
	    		li.Text = li.Value + " (" + li.Text + ")";
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
	          
	}

    public override void GetUIData() 
    {
        base.GetUIData();

		// Get which button got clicked from settings
		DC_OrderRecord myOrderRecord = this.GetRecord();
		DataAccessSettings dataAccessSettingsObj = DataAccessSettings.Current;
		if(dataAccessSettingsObj.ContainsKey("ButtonClicked"))
		{
			string sButtonClicked = (string)dataAccessSettingsObj["ButtonClicked"];
			if (sButtonClicked == "PicklistCompleteButton")
			{
				myOrderRecord.OrderStatusId = 3; 
			}

			// Clear settings
			this.Page.SystemUtils.SetDataAccessSettingsParameterValue("ButtonClicked", "");        
		}

		// Check if there are any picklist record, if none, set the status back to Submitted
		if(dataAccessSettingsObj.ContainsKey("PickListExists"))
		{
			string sPickListExists = (string)dataAccessSettingsObj["PickListExists"];
			if (sPickListExists == "N")
			{
				// Since Order save is fired first, will need to update the status using stored proc
		    	//myOrderRecord.OrderStatusId = 2;
		    }
		}
		
		// Set status back to default state
		this.Page.SystemUtils.SetDataAccessSettingsParameterValue("PickListExists", "N");        
    }

}

public class DC_OrderDetailTableControl : BaseDC_OrderDetailTableControl
{

    // The BaseDC_OrderDetailTableControl class implements the LoadData, DataBind, CreateWhereClause
    // and other methods to load and display the data in a table control.

    // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
    // The DC_OrderDetailTableControlRow class offers another place where you can customize
    // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

public class DC_OrderDetailTableControlRow : BaseDC_OrderDetailTableControlRow
{
      
    // The BaseDC_OrderDetailTableControlRow implements code for a ROW within the
    // the DC_OrderDetailTableControl table.  The BaseDC_OrderDetailTableControlRow implements the DataBind and SaveData methods.
    // The loading of data is actually performed by the LoadData method in the base class of DC_OrderDetailTableControl.

    // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
    // SaveData, GetUIData, and Validate methods.

}
#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderDetailTableControlRow control on the PickListDC_OrderPage page.
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
        
        }

        // To customize, override this method in DC_OrderDetailTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
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
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments1.Text = formattedValue;
                        
            } else {  
                this.Comments1.Text = DC_OrderDetailTable.Comments.Format(DC_OrderDetailTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.OrderQtySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.OrderQty);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderQty.Text = formattedValue;
                        
            } else {  
                this.OrderQty.Text = DC_OrderDetailTable.OrderQty.Format(DC_OrderDetailTable.OrderQty.DefaultValue);
            }
                    
            if (this.DataSource.OrderSizeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.OrderSizeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderSizeId.Text = formattedValue;
                        
            } else {  
                this.OrderSizeId.Text = DC_OrderDetailTable.OrderSizeId.Format(DC_OrderDetailTable.OrderSizeId.DefaultValue);
            }
                    
            if (this.DataSource.SizeHighSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.SizeHigh);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SizeHigh.Text = formattedValue;
                        
            } else {  
                this.SizeHigh.Text = DC_OrderDetailTable.SizeHigh.Format(DC_OrderDetailTable.SizeHigh.DefaultValue);
            }
                    
            if (this.DataSource.SizeLowSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.SizeLow);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SizeLow.Text = formattedValue;
                        
            } else {  
                this.SizeLow.Text = DC_OrderDetailTable.SizeLow.Format(DC_OrderDetailTable.SizeLow.DefaultValue);
            }
                    
            if (this.DataSource.WeightKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderDetailTable.WeightKG);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
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
           
        public System.Web.UI.WebControls.Literal Comments1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments1");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderQty {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderQty");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderSizeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderSizeId");
            }
        }
           
        public System.Web.UI.WebControls.Literal SizeHigh {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHigh");
            }
        }
           
        public System.Web.UI.WebControls.Literal SizeLow {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeLow");
            }
        }
           
        public System.Web.UI.WebControls.Literal WeightKG {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "WeightKG");
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

  
// Base class for the DC_OrderDetailTableControl control on the PickListDC_OrderPage page.
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
            
              this.SizeHighLabel.Click += new EventHandler(SizeHighLabel_Click);
            
              this.SizeLowLabel.Click += new EventHandler(SizeLowLabel_Click);
            
              this.WeightKGLabel.Click += new EventHandler(WeightKGLabel_Click);
            

            // Setup the button events.
        

            // Setup the filter and search events.
        

            // Control Initializations.
            // Initialize the table's current sort order.
            if (this.InSession(this, "Order_By")) {
                this.CurrentSortOrder = OrderBy.FromXmlString(this.GetFromSession(this, "Order_By", null));
            } else {
                this.CurrentSortOrder = new OrderBy(true, true);
        
                this.CurrentSortOrder.Add(DC_OrderDetailTable.OrderSizeId, OrderByItem.OrderDir.Asc);
        
            }

    // Setup default pagination settings.
    
            this.PageSize = Convert.ToInt32(this.GetFromSession(this, "Page_Size", "10"));
            this.PageIndex = Convert.ToInt32(this.GetFromSession(this, "Page_Index", "0"));
            this.ClearControlsFromSession();
        }

        protected virtual void Control_Load(object sender, EventArgs e)
        {
        
            SaveControlsToSession_Ajax();
        
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
                        if (recControl.OrderSizeId.Text != "") {
                            rec.Parse(recControl.OrderSizeId.Text, DC_OrderDetailTable.OrderSizeId);
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
        
        public ePortDC.UI.IPagination DC_OrderDetailPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailPagination");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDetailTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDetailTableTitle");
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
          
        public DC_OrderDetailTableControlRow GetSelectedRecordControl()
        {
        
            return null;
          
        }

        public DC_OrderDetailTableControlRow[] GetSelectedRecordControls()
        {
        
            return (DC_OrderDetailTableControlRow[])((new ArrayList()).ToArray(Type.GetType("ePortDC.UI.Controls.PickListDC_OrderPage.DC_OrderDetailTableControlRow")));
          
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

            return (DC_OrderDetailTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.PickListDC_OrderPage.DC_OrderDetailTableControlRow"));
        }

        public BaseApplicationPage Page {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

    #endregion

    

    }
  
// Base class for the DC_PickListTableControlRow control on the PickListDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_PickListTableControlRow.
public class BaseDC_PickListTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_PickListTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_PickListTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_PickListRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_PickListRecordRowDeleteButton_Click);
              this.OrderDetailId.SelectedIndexChanged += new EventHandler(OrderDetailId_SelectedIndexChanged);
            
              this.PackHouseId.SelectedIndexChanged += new EventHandler(PackHouseId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_PickListTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_PickListRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_PickListTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_PickListTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_PickListTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_PickListRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_PickListTableControlRow.
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
                      
                string formattedValue = this.DataSource.Format(DC_PickListTable.Comments);
                this.Comments2.Text = formattedValue;
                        
            } else {  
                this.Comments2.Text = DC_PickListTable.Comments.Format(DC_PickListTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.OrderDetailIdSpecified) {
                this.PopulateOrderDetailIdDropDownList(this.DataSource.OrderDetailId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateOrderDetailIdDropDownList(DC_PickListTable.OrderDetailId.DefaultValue, 100);
                } else {
                this.PopulateOrderDetailIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.PackHouseIdSpecified) {
                this.PopulatePackHouseIdDropDownList(this.DataSource.PackHouseId, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulatePackHouseIdDropDownList(DC_PickListTable.PackHouseId.DefaultValue, 100);
                } else {
                this.PopulatePackHouseIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.PalletQtySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_PickListTable.PalletQty);
                this.PalletQty.Text = formattedValue;
                        
            } else {  
                this.PalletQty.Text = DC_PickListTable.PalletQty.Format(DC_PickListTable.PalletQty.DefaultValue);
            }
                    
            if (this.DataSource.PickListSizeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_PickListTable.PickListSize);
                this.PickListSize.Text = formattedValue;
                        
            } else {  
                this.PickListSize.Text = DC_PickListTable.PickListSize.Format(DC_PickListTable.PickListSize.DefaultValue);
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

        //  To customize, override this method in DC_PickListTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_PickListTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_PickListTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_PickListTableControl)MiscUtils.GetParentControlObject(this, "DC_PickListTableControl")).DataChanged = true;
                ((DC_PickListTableControl)MiscUtils.GetParentControlObject(this, "DC_PickListTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_PickListTableControlRow.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Comments2.Text, DC_PickListTable.Comments);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.OrderDetailId), DC_PickListTable.OrderDetailId);
                  
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.PackHouseId), DC_PickListTable.PackHouseId);
                  
            this.DataSource.Parse(this.PalletQty.Text, DC_PickListTable.PalletQty);
                          
            this.DataSource.Parse(this.PickListSize.Text, DC_PickListTable.PickListSize);
                          
        }

        //  To customize, override this method in DC_PickListTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_PickListTableControlRow.
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
            DC_PickListTable.DeleteRecord(pk);

          
            ((DC_PickListTableControl)MiscUtils.GetParentControlObject(this, "DC_PickListTableControl")).DataChanged = true;
            ((DC_PickListTableControl)MiscUtils.GetParentControlObject(this, "DC_PickListTableControl")).ResetData = true;
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
        
        public virtual WhereClause CreateWhereClause_OrderDetailIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_PackHouseIdDropDownList() {
            return new WhereClause();
        }
                
        // Fill the OrderDetailId list.
        protected virtual void PopulateOrderDetailIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_OrderDetailIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_OrderDetailTable.OrderDetailId, OrderByItem.OrderDir.Asc);

                      this.OrderDetailId.Items.Clear();
            foreach (DC_OrderDetailRecord itemValue in DC_OrderDetailTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.OrderDetailIdSpecified) {
                    cvalue = itemValue.OrderDetailId.ToString();
                    fvalue = itemValue.Format(DC_OrderDetailTable.OrderDetailId);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.OrderDetailId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.OrderDetailId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.OrderDetailId, DC_PickListTable.OrderDetailId.Format(selectedValue))) {
                string fvalue = DC_PickListTable.OrderDetailId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.OrderDetailId.Items.Insert(0, item);
            }

                  
            this.OrderDetailId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the PackHouseId list.
        protected virtual void PopulatePackHouseIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_PackHouseIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_PackHouseTable.PackHouseName, OrderByItem.OrderDir.Asc);

                      this.PackHouseId.Items.Clear();
            foreach (DC_PackHouseRecord itemValue in DC_PackHouseTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.PackHouseIdSpecified) {
                    cvalue = itemValue.PackHouseId;
                    fvalue = itemValue.Format(DC_PackHouseTable.PackHouseName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.PackHouseId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.PackHouseId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.PackHouseId, DC_PickListTable.PackHouseId.Format(selectedValue))) {
                string fvalue = DC_PickListTable.PackHouseId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.PackHouseId.Items.Insert(0, item);
            }

                  
            this.PackHouseId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              // event handler for ImageButton
              public virtual void DC_PickListRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            if (!this.Page.IsPageRefresh) {
        DC_PickListTableControl tc= ((DC_PickListTableControl)MiscUtils.GetParentControlObject(this, "DC_PickListTableControl"));
                if (tc != null){
                    if (!this.IsNewRecord){
                        tc.AddToDeletedRecordIds((DC_PickListTableControlRow)this);
                    }
                    this.Visible = false;
                }
              
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
            
              protected virtual void OrderDetailId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.OrderDetailId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.OrderDetailId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.OrderDetailId, DC_PickListTable.OrderDetailId.Format(selectedValue))) {
              string fvalue = DC_PickListTable.OrderDetailId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.OrderDetailId.Items.Insert(0, item);
              }
              }
            
              protected virtual void PackHouseId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.PackHouseId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.PackHouseId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.PackHouseId, DC_PickListTable.PackHouseId.Format(selectedValue))) {
              string fvalue = DC_PickListTable.PackHouseId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.PackHouseId.Items.Insert(0, item);
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
                return (string)this.ViewState["BaseDC_PickListTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_PickListTableControlRow_Rec"] = value;
            }
        }
        
        private DC_PickListRecord _DataSource;
        public DC_PickListRecord DataSource {
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
           
        public System.Web.UI.WebControls.TextBox Comments2 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments2");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_PickListRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_PickListRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListRecordRowSelection");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList OrderDetailId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderDetailId");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList PackHouseId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PackHouseId");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PalletQty {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PalletQty");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PickListSize {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickListSize");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_PickListRecord rec = null;
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

        public DC_PickListRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_PickListTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_PickListTableControl control on the PickListDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_PickListTableControl.
public class BaseDC_PickListTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_PickListTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_PickListPagination.FirstPage.Click += new ImageClickEventHandler(DC_PickListPagination_FirstPage_Click);
              this.DC_PickListPagination.LastPage.Click += new ImageClickEventHandler(DC_PickListPagination_LastPage_Click);
              this.DC_PickListPagination.NextPage.Click += new ImageClickEventHandler(DC_PickListPagination_NextPage_Click);
              this.DC_PickListPagination.PageSizeButton.Click += new EventHandler(DC_PickListPagination_PageSizeButton_Click);
            
              this.DC_PickListPagination.PreviousPage.Click += new ImageClickEventHandler(DC_PickListPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.Comments1Label.Click += new EventHandler(Comments1Label_Click);
            
              this.OrderDetailIdLabel.Click += new EventHandler(OrderDetailIdLabel_Click);
            
              this.PackHouseIdLabel.Click += new EventHandler(PackHouseIdLabel_Click);
            
              this.PalletQtyLabel.Click += new EventHandler(PalletQtyLabel_Click);
            
              this.PickListSizeLabel.Click += new EventHandler(PickListSizeLabel_Click);
            

            // Setup the button events.
        
              this.DC_PickListAddButton.Click += new ImageClickEventHandler(DC_PickListAddButton_Click);
              this.DC_PickListDeleteButton.Click += new ImageClickEventHandler(DC_PickListDeleteButton_Click);

            // Setup the filter and search events.
        

            // Control Initializations.
            // Initialize the table's current sort order.
            if (this.InSession(this, "Order_By")) {
                this.CurrentSortOrder = OrderBy.FromXmlString(this.GetFromSession(this, "Order_By", null));
            } else {
                this.CurrentSortOrder = new OrderBy(true, true);
        
            }

    // Setup default pagination settings.
    
            this.PageSize = Convert.ToInt32(this.GetFromSession(this, "Page_Size", "10"));
            this.PageIndex = Convert.ToInt32(this.GetFromSession(this, "Page_Index", "0"));
            this.ClearControlsFromSession();
        }

        protected virtual void Control_Load(object sender, EventArgs e)
        {
        
            SaveControlsToSession_Ajax();
        
                // Show confirmation message on Click
                this.DC_PickListDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_PickListRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_PickListRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_PickListTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_PickListRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_PickListRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_PickListTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_PickListRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_PickListRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_PickListTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_PickListTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_PickListTableControlRow recControl = (DC_PickListTableControlRow)(repItem.FindControl("DC_PickListTableControlRow"));
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
          
            this.Page.PregetDfkaRecords(DC_PickListTable.OrderDetailId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_PickListTable.PackHouseId, this.DataSource);
        }
         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_PickListTableControl pagination.
        
            this.DC_PickListPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_PickListPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_PickListPagination.LastPage.Enabled = false;
            }
          
            this.DC_PickListPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_PickListPagination.NextPage.Enabled = false;
            }
          
            this.DC_PickListPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_PickListPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_PickListPagination.CurrentPage.Text = "0";
            }
            this.DC_PickListPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_PickListTotalItems.Text = this.TotalRecords.ToString();
            this.DC_PickListPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_PickListPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_PickListTableControlRow recCtl in this.GetRecordControls())
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
            DC_PickListTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            // Get the static clause defined at design time on the Table Panel Wizard
            WhereClause qc = this.CreateQueryClause();
            if (qc != null) {
                wc.iAND(qc);
            }
        
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_PickListTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Get the static clause defined at design time on the Table Panel Wizard
            WhereClause qc = this.CreateQueryClause();
            if (qc != null) {
                wc.iAND(qc);
            }
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            return wc;
        }
          
        // Create a where clause for the Static clause defined at design time.
        protected virtual WhereClause CreateQueryClause()
        {
            CompoundFilter filter = new CompoundFilter(CompoundFilter.CompoundingOperators.And_Operator, null);

            filter.AddFilter(new BaseClasses.Data.ParameterValueFilter(BaseClasses.Data.BaseTable.CreateInstance(@"ePortDC.Business.DC_PickListTable, App_Code").TableDefinition.ColumnList.GetByUniqueName(@"DC_PickList_.OrderNum"), @"DC_Order", BaseClasses.Data.BaseFilter.ComparisonOperator.EqualsTo, false, new BaseClasses.Data.IdentifierAliasInfo(@"", null)));

            WhereClause whereClause = new WhereClause();
            whereClause.AddFilter(filter, CompoundFilter.CompoundingOperators.And_Operator);

            return whereClause;

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
        
            if (this.DC_PickListPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_PickListPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_PickListTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_PickListTableControlRow recControl = (DC_PickListTableControlRow)(repItem.FindControl("DC_PickListTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_PickListRecord rec = new DC_PickListRecord();
        
                        if (recControl.Comments2.Text != "") {
                            rec.Parse(recControl.Comments2.Text, DC_PickListTable.Comments);
                        }
                        if (MiscUtils.IsValueSelected(recControl.OrderDetailId)) {
                            rec.Parse(recControl.OrderDetailId.SelectedItem.Value, DC_PickListTable.OrderDetailId);
                        }
                        if (MiscUtils.IsValueSelected(recControl.PackHouseId)) {
                            rec.Parse(recControl.PackHouseId.SelectedItem.Value, DC_PickListTable.PackHouseId);
                        }
                        if (recControl.PalletQty.Text != "") {
                            rec.Parse(recControl.PalletQty.Text, DC_PickListTable.PalletQty);
                        }
                        if (recControl.PickListSize.Text != "") {
                            rec.Parse(recControl.PickListSize.Text, DC_PickListTable.PickListSize);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_PickListRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_PickListRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_PickListRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_PickListTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_PickListTableControlRow rec)            
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

            string orderByStr = (string)ViewState["DC_PickListTableControl_OrderBy"];
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
                this.ViewState["DC_PickListTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_PickListPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_PickListPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_PickListPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_PickListPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_PickListPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_PickListPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_PickListPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void Comments1Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_PickListTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_PickListTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OrderDetailIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_PickListTable.OrderDetailId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_PickListTable.OrderDetailId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PackHouseIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_PickListTable.PackHouseId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_PickListTable.PackHouseId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PalletQtyLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_PickListTable.PalletQty);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_PickListTable.PalletQty, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PickListSizeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_PickListTable.PickListSize);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_PickListTable.PickListSize, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_PickListAddButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_PickListDeleteButton_Click(object sender, ImageClickEventArgs args)
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

        private DC_PickListRecord[] _DataSource = null;
        public  DC_PickListRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton Comments1Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments1Label");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_PickListAddButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListAddButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_PickListDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListDeleteButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_PickListPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListPagination");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_PickListTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_PickListToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_PickListTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_PickListTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OrderDetailIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderDetailIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PackHouseIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PackHouseIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PalletQtyLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PalletQtyLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PickListSizeLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickListSizeLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_PickListTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_PickListRecord rec = null;
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
            foreach (DC_PickListTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_PickListRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_PickListTableControlRow GetSelectedRecordControl()
        {
        DC_PickListTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_PickListTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_PickListTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_PickListRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_PickListTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.PickListDC_OrderPage.DC_PickListTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_PickListTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_PickListTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_PickListRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_PickListTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_PickListTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_PickListTableControlRow recControl = (DC_PickListTableControlRow)repItem.FindControl("DC_PickListTableControlRow");
                recList.Add(recControl);
            }

            return (DC_PickListTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.PickListDC_OrderPage.DC_PickListTableControlRow"));
        }

        public BaseApplicationPage Page {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

    #endregion

    

    }
  
// Base class for the DC_OrderRecordControl control on the PickListDC_OrderPage page.
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
        
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
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
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_OrderTable.Comments.Format(DC_OrderTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.ConsigneeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.ConsigneeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ConsigneeId.Text = formattedValue;
                        
            } else {  
                this.ConsigneeId.Text = DC_OrderTable.ConsigneeId.Format(DC_OrderTable.ConsigneeId.DefaultValue);
            }
                    
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_OrderTable.CustomerId.Format(DC_OrderTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.LoadTypeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.LoadType);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.LoadType.Text = formattedValue;
                        
            } else {  
                this.LoadType.Text = DC_OrderTable.LoadType.Format(DC_OrderTable.LoadType.DefaultValue);
            }
                    
            if (this.DataSource.OrderNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderNum.Text = formattedValue;
                        
            } else {  
                this.OrderNum.Text = DC_OrderTable.OrderNum.Format(DC_OrderTable.OrderNum.DefaultValue);
            }
                    
            if (this.DataSource.OrderStatusIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderStatusId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderStatusId.Text = formattedValue;
                        
            } else {  
                this.OrderStatusId.Text = DC_OrderTable.OrderStatusId.Format(DC_OrderTable.OrderStatusId.DefaultValue);
            }
                    
            if (this.DataSource.PickUpDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PickUpDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PickUpDate.Text = formattedValue;
                        
            } else {  
                this.PickUpDate.Text = DC_OrderTable.PickUpDate.Format(DC_OrderTable.PickUpDate.DefaultValue);
            }
                    
            if (this.DataSource.VesselIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.VesselId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.VesselId.Text = formattedValue;
                        
            } else {  
                this.VesselId.Text = DC_OrderTable.VesselId.Format(DC_OrderTable.VesselId.DefaultValue);
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
           
        public System.Web.UI.WebControls.Literal Comments {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommentsLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal ConsigneeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomerId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.Literal LoadType {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadType");
            }
        }
        
        public System.Web.UI.WebControls.Literal LoadTypeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadTypeLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderStatusId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusId");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderStatusIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal PickUpDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal PickUpDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal VesselId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselId");
            }
        }
        
        public System.Web.UI.WebControls.Literal VesselIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselIdLabel");
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

  