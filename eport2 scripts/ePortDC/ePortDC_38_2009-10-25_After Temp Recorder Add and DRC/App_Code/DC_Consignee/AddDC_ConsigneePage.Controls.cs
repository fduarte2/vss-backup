
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// AddDC_ConsigneePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.AddDC_ConsigneePage
{
  

#region "Section 1: Place your customizations here."

    
//public class DC_OrderTableControlRow : BaseDC_OrderTableControlRow
//{
//      
//        // The BaseDC_OrderTableControlRow implements code for a ROW within the
//        // the DC_OrderTableControl table.  The BaseDC_OrderTableControlRow implements the DataBind and SaveData methods.
//        // The loading of data is actually performed by the LoadData method in the base class of DC_OrderTableControl.
//
//        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
//        // SaveData, GetUIData, and Validate methods.
//        
//
//}
//

  

//public class DC_OrderTableControl : BaseDC_OrderTableControl
//{
//        // The BaseDC_OrderTableControl class implements the LoadData, DataBind, CreateWhereClause
//        // and other methods to load and display the data in a table control.
//
//        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
//        // The DC_OrderTableControlRow class offers another place where you can customize
//        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.
//
//}
//

  
public class DC_ConsigneeRecordControl : BaseDC_ConsigneeRecordControl
{
      
    // The BaseDC_ConsigneeRecordControl implements the LoadData, DataBind and other
    // methods to load and display the data in a table control.

    // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
    // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.

    protected override void PopulateCustomsBrokerOfficeIdDropDownList(string selectedValue, int maxItems) 
    {
	    // Call the base.Populate${Dropdown List Control}DropDownList to populate the dropdown list
	    base.PopulateCustomsBrokerOfficeIdDropDownList(selectedValue, maxItems);
	
	    // Add value in front of the description of all items
	    foreach (ListItem li1 in this.CustomsBrokerOfficeId.Items)
	    {
	    	if (li1.Text != "** Please Select **")
	    		li1.Text = li1.Text + " (" + li1.Value + ")";
	    }
	}

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_ConsigneeRecordControl control on the AddDC_ConsigneePage page.
// Do not modify this class. Instead override any method in DC_ConsigneeRecordControl.
public class BaseDC_ConsigneeRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_ConsigneeRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_ConsigneeRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
                this.CustomerIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.CustomerId.UniqueID + "&DFKA=CustomerShortName";
                this.CustomerIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.CustomerIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.CustomerIdAddRecordLink.Click += new ImageClickEventHandler(CustomerIdAddRecordLink_Click);
                this.CustomsBrokerOfficeIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.CustomsBrokerOfficeId.UniqueID + "&DFKA=CustomsBroker";
                this.CustomsBrokerOfficeIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.CustomsBrokerOfficeIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.CustomsBrokerOfficeIdAddRecordLink.Click += new ImageClickEventHandler(CustomsBrokerOfficeIdAddRecordLink_Click);
              this.CustomerId.SelectedIndexChanged += new EventHandler(CustomerId_SelectedIndexChanged);
            
              this.CustomsBrokerOfficeId.SelectedIndexChanged += new EventHandler(CustomsBrokerOfficeId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_ConsigneeRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_ConsigneeRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_ConsigneeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_ConsigneeRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_ConsigneeRecord[] recList = DC_ConsigneeTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = (DC_ConsigneeRecord)DC_ConsigneeRecord.Copy(recList[0], false);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_ConsigneeRecordControl.
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

        
            if (this.DataSource.AddressSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Address);
                this.Address.Text = formattedValue;
                        
            } else {  
                this.Address.Text = DC_ConsigneeTable.Address.Format(DC_ConsigneeTable.Address.DefaultValue);
            }
                    
            if (this.DataSource.CitySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.City);
                this.City.Text = formattedValue;
                        
            } else {  
                this.City.Text = DC_ConsigneeTable.City.Format(DC_ConsigneeTable.City.DefaultValue);
            }
                    
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Comments);
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_ConsigneeTable.Comments.Format(DC_ConsigneeTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.ConsigneeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.ConsigneeId);
                this.ConsigneeId.Text = formattedValue;
                        
            } else {  
                this.ConsigneeId.Text = DC_ConsigneeTable.ConsigneeId.Format(DC_ConsigneeTable.ConsigneeId.DefaultValue);
            }
                    
            if (this.DataSource.ConsigneeNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.ConsigneeName);
                this.ConsigneeName.Text = formattedValue;
                        
            } else {  
                this.ConsigneeName.Text = DC_ConsigneeTable.ConsigneeName.Format(DC_ConsigneeTable.ConsigneeName.DefaultValue);
            }
                    
            if (this.DataSource.CustomerIdSpecified) {
                this.PopulateCustomerIdDropDownList(this.DataSource.CustomerId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCustomerIdDropDownList(DC_ConsigneeTable.CustomerId.DefaultValue, 100);
                } else {
                this.PopulateCustomerIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.CustomsBrokerOfficeIdSpecified) {
                this.PopulateCustomsBrokerOfficeIdDropDownList(this.DataSource.CustomsBrokerOfficeId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCustomsBrokerOfficeIdDropDownList(DC_ConsigneeTable.CustomsBrokerOfficeId.DefaultValue, 100);
                } else {
                this.PopulateCustomsBrokerOfficeIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Phone);
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_ConsigneeTable.Phone.Format(DC_ConsigneeTable.Phone.DefaultValue);
            }
                    
            if (this.DataSource.PhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.PhoneMobile);
                this.PhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PhoneMobile.Text = DC_ConsigneeTable.PhoneMobile.Format(DC_ConsigneeTable.PhoneMobile.DefaultValue);
            }
                    
            if (this.DataSource.PostalCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.PostalCode);
                this.PostalCode.Text = formattedValue;
                        
            } else {  
                this.PostalCode.Text = DC_ConsigneeTable.PostalCode.Format(DC_ConsigneeTable.PostalCode.DefaultValue);
            }
                    
            if (this.DataSource.StateProvinceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.StateProvince);
                this.StateProvince.Text = formattedValue;
                        
            } else {  
                this.StateProvince.Text = DC_ConsigneeTable.StateProvince.Format(DC_ConsigneeTable.StateProvince.DefaultValue);
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

        //  To customize, override this method in DC_ConsigneeRecordControl.
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
        
            // 2. Validate the data.  Override in DC_ConsigneeRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_ConsigneeRecordControl to set additional fields.
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

        //  To customize, override this method in DC_ConsigneeRecordControl.
        public virtual void GetUIData()
        {
        
            bool clearDataSource = false;
            foreach(BaseColumn col in DC_ConsigneeRecord.TableUtils.TableDefinition.Columns){
                if ((col.ColumnType == BaseColumn.ColumnTypes.Unique_Identifier)){
                    clearDataSource = true;
                }
            }

            if (clearDataSource){
                this.DataSource = new DC_ConsigneeRecord();
            }
        
            this.DataSource.Parse(this.Address.Text, DC_ConsigneeTable.Address);
                          
            this.DataSource.Parse(this.City.Text, DC_ConsigneeTable.City);
                          
            this.DataSource.Parse(this.Comments.Text, DC_ConsigneeTable.Comments);
                          
            this.DataSource.Parse(this.ConsigneeId.Text, DC_ConsigneeTable.ConsigneeId);
                          
            this.DataSource.Parse(this.ConsigneeName.Text, DC_ConsigneeTable.ConsigneeName);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CustomerId), DC_ConsigneeTable.CustomerId);
                  
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CustomsBrokerOfficeId), DC_ConsigneeTable.CustomsBrokerOfficeId);
                  
            this.DataSource.Parse(this.Phone.Text, DC_ConsigneeTable.Phone);
                          
            this.DataSource.Parse(this.PhoneMobile.Text, DC_ConsigneeTable.PhoneMobile);
                          
            this.DataSource.Parse(this.PostalCode.Text, DC_ConsigneeTable.PostalCode);
                          
            this.DataSource.Parse(this.StateProvince.Text, DC_ConsigneeTable.StateProvince);
                          
        }

        //  To customize, override this method in DC_ConsigneeRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_ConsigneeTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
            string recId = this.Page.Request.QueryString["DC_Consignee"];
            if (recId == null || recId.Length == 0) {
                return null;
            }
                       
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_ConsigneeTable.ConsigneeId).ToString());
            } else {
                
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;  
          
        }
        

        //  To customize, override this method in DC_ConsigneeRecordControl.
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
            DC_ConsigneeTable.DeleteRecord(pk);

          
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
        
        public virtual WhereClause CreateWhereClause_CustomerIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_CustomsBrokerOfficeIdDropDownList() {
            return new WhereClause();
        }
                
        // Fill the CustomerId list.
        protected virtual void PopulateCustomerIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_CustomerIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerShortName, OrderByItem.OrderDir.Asc);

                      this.CustomerId.Items.Clear();
            foreach (DC_CustomerRecord itemValue in DC_CustomerTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.CustomerIdSpecified) {
                    cvalue = itemValue.CustomerId.ToString();
                    fvalue = itemValue.Format(DC_CustomerTable.CustomerShortName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.CustomerId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.CustomerId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.CustomerId, DC_ConsigneeTable.CustomerId.Format(selectedValue))) {
                string fvalue = DC_ConsigneeTable.CustomerId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CustomerId.Items.Insert(0, item);
            }

                  
            this.CustomerId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the CustomsBrokerOfficeId list.
        protected virtual void PopulateCustomsBrokerOfficeIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_CustomsBrokerOfficeIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomsBrokerOfficeTable.CustomsBroker, OrderByItem.OrderDir.Asc);

                      this.CustomsBrokerOfficeId.Items.Clear();
            foreach (DC_CustomsBrokerOfficeRecord itemValue in DC_CustomsBrokerOfficeTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.CustomsBrokerOfficeIdSpecified) {
                    cvalue = itemValue.CustomsBrokerOfficeId.ToString();
                    fvalue = itemValue.Format(DC_CustomsBrokerOfficeTable.CustomsBroker);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.CustomsBrokerOfficeId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.CustomsBrokerOfficeId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.CustomsBrokerOfficeId, DC_ConsigneeTable.CustomsBrokerOfficeId.Format(selectedValue))) {
                string fvalue = DC_ConsigneeTable.CustomsBrokerOfficeId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CustomsBrokerOfficeId.Items.Insert(0, item);
            }

                  
            this.CustomsBrokerOfficeId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              // event handler for ImageButton
              public virtual void CustomerIdAddRecordLink_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/AddDC_CustomerPage.aspx";
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
              public virtual void CustomsBrokerOfficeIdAddRecordLink_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/AddDC_CustomsBrokerOfficePage.aspx";
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
            
              protected virtual void CustomerId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.CustomerId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.CustomerId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.CustomerId, DC_ConsigneeTable.CustomerId.Format(selectedValue))) {
              string fvalue = DC_ConsigneeTable.CustomerId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CustomerId.Items.Insert(0, item);
              }
              }
            
              protected virtual void CustomsBrokerOfficeId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.CustomsBrokerOfficeId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.CustomsBrokerOfficeId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.CustomsBrokerOfficeId, DC_ConsigneeTable.CustomsBrokerOfficeId.Format(selectedValue))) {
              string fvalue = DC_ConsigneeTable.CustomsBrokerOfficeId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CustomsBrokerOfficeId.Items.Insert(0, item);
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
                return (string)this.ViewState["BaseDC_ConsigneeRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_ConsigneeRecordControl_Rec"] = value;
            }
        }
        
        private DC_ConsigneeRecord _DataSource;
        public DC_ConsigneeRecord DataSource {
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
           
        public System.Web.UI.WebControls.TextBox Address {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Address");
            }
        }
        
        public System.Web.UI.WebControls.Literal AddressLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "AddressLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox City {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "City");
            }
        }
        
        public System.Web.UI.WebControls.Literal CityLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CityLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Comments {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommentsLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox ConsigneeId {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox ConsigneeName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeName");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeNameLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList CustomerId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton CustomerIdAddRecordLink {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdAddRecordLink");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList CustomsBrokerOfficeId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton CustomsBrokerOfficeIdAddRecordLink {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdAddRecordLink");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBrokerOfficeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_ConsigneeDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Phone {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PhoneMobile {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobile");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneMobileLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobileLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PostalCode {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal PostalCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox StateProvince {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvince");
            }
        }
        
        public System.Web.UI.WebControls.Literal StateProvinceLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvinceLabel");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_ConsigneeRecord rec = null;
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

        public DC_ConsigneeRecord GetRecord()
        {
        
            if (this.DataSource != null) {
              return this.DataSource;
            }
            
            return new DC_ConsigneeRecord();
          
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

  