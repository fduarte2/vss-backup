
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CustomerPricePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CustomerPricePage
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
    
    
// Base class for the DC_CustomerPriceRecordControl control on the ShowDC_CustomerPricePage page.
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
        
              this.DC_CustomerPriceDialogEditButton.Click += new ImageClickEventHandler(DC_CustomerPriceDialogEditButton_Click);
              this.CustomerId.Click += new EventHandler(CustomerId_Click);
            
              this.SizeId.Click += new EventHandler(SizeId_Click);
            
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
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomerPriceTable.Comments.Format(DC_CustomerPriceTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommodityCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.CommodityCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CommodityCode.Text = formattedValue;
                        
            } else {  
                this.CommodityCode.Text = DC_CustomerPriceTable.CommodityCode.Format(DC_CustomerPriceTable.CommodityCode.DefaultValue);
            }
                    
            if (this.CommodityCode.Text == null ||
                this.CommodityCode.Text.Trim().Length == 0) {
                this.CommodityCode.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.CustomerId);
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_CustomerPriceTable.CustomerId.Format(DC_CustomerPriceTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.EffectiveDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.EffectiveDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.EffectiveDate.Text = formattedValue;
                        
            } else {  
                this.EffectiveDate.Text = DC_CustomerPriceTable.EffectiveDate.Format(DC_CustomerPriceTable.EffectiveDate.DefaultValue);
            }
                    
            if (this.EffectiveDate.Text == null ||
                this.EffectiveDate.Text.Trim().Length == 0) {
                this.EffectiveDate.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.Price);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Price.Text = formattedValue;
                        
            } else {  
                this.Price.Text = DC_CustomerPriceTable.Price.Format(DC_CustomerPriceTable.Price.DefaultValue);
            }
                    
            if (this.Price.Text == null ||
                this.Price.Text.Trim().Length == 0) {
                this.Price.Text = "&nbsp;";
            }
                  
            if (this.DataSource.SizeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerPriceTable.SizeId);
                this.SizeId.Text = formattedValue;
                        
            } else {  
                this.SizeId.Text = DC_CustomerPriceTable.SizeId.Format(DC_CustomerPriceTable.SizeId.DefaultValue);
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
        
              // event handler for ImageButton
              public virtual void DC_CustomerPriceDialogEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/EditDC_CustomerPricePage.aspx?DC_CustomerPrice={PK}";
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
            
              // event handler for LinkButton
              public virtual void CustomerId_Click(object sender, EventArgs args)
              {
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_CustomerPriceRecordControl:FK:FK_DC_CustomerPrice_DC_Customer}";
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
            
              // event handler for LinkButton
              public virtual void SizeId_Click(object sender, EventArgs args)
              {
              
            string url = @"../DC_CommoditySize/ShowDC_CommoditySizePage.aspx?DC_CommoditySize={DC_CustomerPriceRecordControl:FK:FK_DC_CustomerPrice_DC_CommoditySize}";
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
           
        public System.Web.UI.WebControls.LinkButton CustomerId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceDialogEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceDialogEditButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomerPriceDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.Literal EffectiveDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal EffectiveDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Price {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Price");
            }
        }
        
        public System.Web.UI.WebControls.Literal PriceLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PriceLabel");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton SizeId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId");
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

  