
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// EditDC_CustomerPricePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.EditDC_CustomerPricePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CustomerPriceRecordControl : BaseDC_CustomerPriceRecordControl
{
      
        // The BaseDC_CustomerPriceRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomerPriceRecordControl control on the EditDC_CustomerPricePage page.
// Do not modify this class. Instead override any method in DC_CustomerPriceRecordControl.
public class BaseDC_CustomerPriceRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomerPriceRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomerPriceRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
                this.CustomerIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.CustomerId.UniqueID + "&DFKA=CustomerName";
                this.CustomerIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.CustomerIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.CustomerIdAddRecordLink.Click += new ImageClickEventHandler(CustomerIdAddRecordLink_Click);
                this.SizeIdAddRecordLink.Attributes["RedirectUrl"] += "?Target=" + this.SizeId.UniqueID + "&DFKA=Descr";
                this.SizeIdAddRecordLink.Attributes["onClick"] = "window.open('" + this.SizeIdAddRecordLink.Attributes["RedirectUrl"] + "','_blank', 'width=900, height=700, resizable, scrollbars, modal=yes'); return false;";
              
              this.SizeIdAddRecordLink.Click += new ImageClickEventHandler(SizeIdAddRecordLink_Click);
              this.CommodityCode.SelectedIndexChanged += new EventHandler(CommodityCode_SelectedIndexChanged);
            
              this.CustomerId.SelectedIndexChanged += new EventHandler(CustomerId_SelectedIndexChanged);
            
              this.SizeId.SelectedIndexChanged += new EventHandler(SizeId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_CustomerPriceRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_CustomerPriceRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomerPriceTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_CustomerPriceRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_CustomerPriceRecord[] recList = DC_CustomerPriceTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_CustomerPriceTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomerPriceRecordControl.
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
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.Comments);
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomerPriceTable.Comments.Format(DC_CustomerPriceTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.CommodityCodeSpecified) {
                this.PopulateCommodityCodeDropDownList(this.DataSource.CommodityCode.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCommodityCodeDropDownList(DC_CustomerPriceTable.CommodityCode.DefaultValue, 100);
                } else {
                this.PopulateCommodityCodeDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.CustomerIdSpecified) {
                this.PopulateCustomerIdDropDownList(this.DataSource.CustomerId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateCustomerIdDropDownList(DC_CustomerPriceTable.CustomerId.DefaultValue, 100);
                } else {
                this.PopulateCustomerIdDropDownList(null, 100);
                }
            }
                
            this.EffectiveDate.Attributes.Add("onfocus", "toggleEnableDisableDateFormatter(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
            this.EffectiveDate.Attributes.Add("onblur", "presubmitDateValidation(this, '" + System.Globalization.CultureInfo.CurrentCulture.DateTimeFormat.ShortDatePattern.Replace("'", "").ToLower() + "');");
                    
            if (this.DataSource.EffectiveDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.EffectiveDate);
                this.EffectiveDate.Text = formattedValue;
                        
            } else {  
                this.EffectiveDate.Text = DC_CustomerPriceTable.EffectiveDate.Format(DC_CustomerPriceTable.EffectiveDate.DefaultValue);
            }
                    
            if (this.DataSource.PriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.Price);
                this.Price.Text = formattedValue;
                        
            } else {  
                this.Price.Text = DC_CustomerPriceTable.Price.Format(DC_CustomerPriceTable.Price.DefaultValue);
            }
                    
            if (this.DataSource.SizeIdSpecified) {
                this.PopulateSizeIdDropDownList(this.DataSource.SizeId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateSizeIdDropDownList(DC_CustomerPriceTable.SizeId.DefaultValue, 100);
                } else {
                this.PopulateSizeIdDropDownList(null, 100);
                }
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

        //  To customize, override this method in DC_CustomerPriceRecordControl.
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
        
            // 2. Validate the data.  Override in DC_CustomerPriceRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomerPriceRecordControl to set additional fields.
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

        //  To customize, override this method in DC_CustomerPriceRecordControl.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Comments.Text, DC_CustomerPriceTable.Comments);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CommodityCode), DC_CustomerPriceTable.CommodityCode);
                  
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.CustomerId), DC_CustomerPriceTable.CustomerId);
                  
            this.DataSource.Parse(this.EffectiveDate.Text, DC_CustomerPriceTable.EffectiveDate);
                          
            this.DataSource.Parse(this.Price.Text, DC_CustomerPriceTable.Price);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.SizeId), DC_CustomerPriceTable.SizeId);
                  
        }

        //  To customize, override this method in DC_CustomerPriceRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_CustomerPriceTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_CustomerPrice"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_CustomerPrice"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomerPriceTable.CustomerId).ToString());
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomerPriceTable.CommodityCode).ToString());
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomerPriceTable.SizeId).ToString());
                wc.iAND(DC_CustomerPriceTable.EffectiveDate, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomerPriceTable.EffectiveDate).ToString());
            } else {
                
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, recId);
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, recId);
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, recId);
                wc.iAND(DC_CustomerPriceTable.EffectiveDate, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_CustomerPriceRecordControl.
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
            DC_CustomerPriceTable.DeleteRecord(pk);

          
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
                
        public virtual WhereClause CreateWhereClause_CustomerIdDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_SizeIdDropDownList() {
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
                !MiscUtils.SetSelectedValue(this.CommodityCode, DC_CustomerPriceTable.CommodityCode.Format(selectedValue))) {
                string fvalue = DC_CustomerPriceTable.CommodityCode.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CommodityCode.Items.Insert(0, item);
            }

                  
            this.CommodityCode.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
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
                !MiscUtils.SetSelectedValue(this.CustomerId, DC_CustomerPriceTable.CustomerId.Format(selectedValue))) {
                string fvalue = DC_CustomerPriceTable.CustomerId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.CustomerId.Items.Insert(0, item);
            }

                  
            this.CustomerId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the SizeId list.
        protected virtual void PopulateSizeIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_SizeIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommoditySizeTable.Descr, OrderByItem.OrderDir.Asc);

                      this.SizeId.Items.Clear();
            foreach (DC_CommoditySizeRecord itemValue in DC_CommoditySizeTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.SizeIdSpecified) {
                    cvalue = itemValue.SizeId.ToString();
                    fvalue = itemValue.Format(DC_CommoditySizeTable.Descr);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.SizeId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.SizeId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.SizeId, DC_CustomerPriceTable.SizeId.Format(selectedValue))) {
                string fvalue = DC_CustomerPriceTable.SizeId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.SizeId.Items.Insert(0, item);
            }

                  
            this.SizeId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
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
              public virtual void SizeIdAddRecordLink_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/AddDC_CommoditySizePage.aspx";
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
              !MiscUtils.SetSelectedValue(this.CommodityCode, DC_CustomerPriceTable.CommodityCode.Format(selectedValue))) {
              string fvalue = DC_CustomerPriceTable.CommodityCode.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CommodityCode.Items.Insert(0, item);
              }
              }
            
              protected virtual void CustomerId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.CustomerId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.CustomerId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.CustomerId, DC_CustomerPriceTable.CustomerId.Format(selectedValue))) {
              string fvalue = DC_CustomerPriceTable.CustomerId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.CustomerId.Items.Insert(0, item);
              }
              }
            
              protected virtual void SizeId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.SizeId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.SizeId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.SizeId, DC_CustomerPriceTable.SizeId.Format(selectedValue))) {
              string fvalue = DC_CustomerPriceTable.SizeId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.SizeId.Items.Insert(0, item);
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
                return (string)this.ViewState["BaseDC_CustomerPriceRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomerPriceRecordControl_Rec"] = value;
            }
        }
        
        private DC_CustomerPriceRecord _DataSource;
        public DC_CustomerPriceRecord DataSource {
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
        
        public System.Web.UI.WebControls.Literal DC_CustomerPriceDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox EffectiveDate {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal EffectiveDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Price {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Price");
            }
        }
        
        public System.Web.UI.WebControls.Literal PriceLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PriceLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList SizeId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton SizeIdAddRecordLink {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdAddRecordLink");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdLabel");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_CustomerPriceRecord rec = null;
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

        public DC_CustomerPriceRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_CustomerPriceTable.GetRecord(this.RecordUniqueId, true);
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

  