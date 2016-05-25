
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// PrintDC_OrderPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.PrintDC_OrderPage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_OrderRecordControl : BaseDC_OrderRecordControl
{
      
        // The BaseDC_OrderRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderRecordControl control on the PrintDC_OrderPage page.
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
        
              this.VesselId.SelectedIndexChanged += new EventHandler(VesselId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                  this.Page.Authorize((Control)VesselId, "NO_ACCESS");
            
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

        
            if (this.DataSource.CommodityCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CommodityCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CommodityCode.Text = formattedValue;
                        
            } else {  
                this.CommodityCode.Text = DC_OrderTable.CommodityCode.Format(DC_OrderTable.CommodityCode.DefaultValue);
            }
                    
            if (this.CommodityCode.Text == null ||
                this.CommodityCode.Text.Trim().Length == 0) {
                this.CommodityCode.Text = "&nbsp;";
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
                    
            if (this.ConsigneeId.Text == null ||
                this.ConsigneeId.Text.Trim().Length == 0) {
                this.ConsigneeId.Text = "&nbsp;";
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
                    
            if (this.CustomerId.Text == null ||
                this.CustomerId.Text.Trim().Length == 0) {
                this.CustomerId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.DeliveryDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DeliveryDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.DeliveryDate.Text = formattedValue;
                        
            } else {  
                this.DeliveryDate.Text = DC_OrderTable.DeliveryDate.Format(DC_OrderTable.DeliveryDate.DefaultValue);
            }
                    
            if (this.DeliveryDate.Text == null ||
                this.DeliveryDate.Text.Trim().Length == 0) {
                this.DeliveryDate.Text = "&nbsp;";
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
                    
            if (this.OrderNum.Text == null ||
                this.OrderNum.Text.Trim().Length == 0) {
                this.OrderNum.Text = "&nbsp;";
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
                    
            if (this.OrderStatusId.Text == null ||
                this.OrderStatusId.Text.Trim().Length == 0) {
                this.OrderStatusId.Text = "&nbsp;";
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
                    
            if (this.PickUpDate.Text == null ||
                this.PickUpDate.Text.Trim().Length == 0) {
                this.PickUpDate.Text = "&nbsp;";
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
        
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.VesselId), DC_OrderTable.VesselId);
                  
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
              
            string recId = this.Page.Request.QueryString["OrderNum"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "OrderNum"));
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
        
        public virtual WhereClause CreateWhereClause_VesselIdDropDownList() {
            return new WhereClause();
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
           
        public System.Web.UI.WebControls.Literal CommodityCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel");
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
           
        public System.Web.UI.WebControls.Literal DeliveryDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal DeliveryDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDateLabel");
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

  