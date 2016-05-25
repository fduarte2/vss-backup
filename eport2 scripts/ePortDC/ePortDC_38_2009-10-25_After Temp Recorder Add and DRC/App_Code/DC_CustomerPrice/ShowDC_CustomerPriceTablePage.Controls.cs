
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CustomerPriceTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CustomerPriceTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CustomerPriceTableControlRow : BaseDC_CustomerPriceTableControlRow
{
      
        // The BaseDC_CustomerPriceTableControlRow implements code for a ROW within the
        // the DC_CustomerPriceTableControl table.  The BaseDC_CustomerPriceTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_CustomerPriceTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_CustomerPriceTableControl : BaseDC_CustomerPriceTableControl
{
        // The BaseDC_CustomerPriceTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_CustomerPriceTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomerPriceTableControlRow control on the ShowDC_CustomerPriceTablePage page.
// Do not modify this class. Instead override any method in DC_CustomerPriceTableControlRow.
public class BaseDC_CustomerPriceTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomerPriceTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomerPriceTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_CustomerPriceRecordRowCopyButton.Click += new ImageClickEventHandler(DC_CustomerPriceRecordRowCopyButton_Click);
              this.DC_CustomerPriceRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_CustomerPriceRecordRowDeleteButton_Click);
              this.DC_CustomerPriceRecordRowEditButton.Click += new ImageClickEventHandler(DC_CustomerPriceRecordRowEditButton_Click);
              this.DC_CustomerPriceRecordRowViewButton.Click += new ImageClickEventHandler(DC_CustomerPriceRecordRowViewButton_Click);
              this.CustomerId.Click += new EventHandler(CustomerId_Click);
            
              this.SizeId.Click += new EventHandler(SizeId_Click);
            
        }

        // To customize, override this method in DC_CustomerPriceTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_CustomerPriceRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_CustomerPriceTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomerPriceTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_CustomerPriceTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_CustomerPriceRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomerPriceTableControlRow.
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

        //  To customize, override this method in DC_CustomerPriceTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_CustomerPriceTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomerPriceTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_CustomerPriceTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerPriceTableControl")).DataChanged = true;
                ((DC_CustomerPriceTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerPriceTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_CustomerPriceTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_CustomerPriceTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_CustomerPriceTableControlRow.
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

          
            ((DC_CustomerPriceTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerPriceTableControl")).DataChanged = true;
            ((DC_CustomerPriceTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerPriceTableControl")).ResetData = true;
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
              public virtual void DC_CustomerPriceRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/AddDC_CustomerPricePage.aspx?DC_CustomerPrice={PK}";
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
              public virtual void DC_CustomerPriceRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/ShowDC_CustomerPricePage.aspx?DC_CustomerPrice={PK}";
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
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_CustomerPriceTableControlRow:FK:FK_DC_CustomerPrice_DC_Customer}";
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
              
            string url = @"../DC_CommoditySize/ShowDC_CommoditySizePage.aspx?DC_CommoditySize={DC_CustomerPriceTableControlRow:FK:FK_DC_CustomerPrice_DC_CommoditySize}";
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
                return (string)this.ViewState["BaseDC_CustomerPriceTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomerPriceTableControlRow_Rec"] = value;
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
           
        public System.Web.UI.WebControls.Literal CommodityCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCode");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton CustomerId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomerPriceRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal EffectiveDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDate");
            }
        }
           
        public System.Web.UI.WebControls.Literal Price {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Price");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton SizeId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId");
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

  
// Base class for the DC_CustomerPriceTableControl control on the ShowDC_CustomerPriceTablePage page.
// Do not modify this class. Instead override any method in DC_CustomerPriceTableControl.
public class BaseDC_CustomerPriceTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_CustomerPriceTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_CustomerPricePagination.FirstPage.Click += new ImageClickEventHandler(DC_CustomerPricePagination_FirstPage_Click);
              this.DC_CustomerPricePagination.LastPage.Click += new ImageClickEventHandler(DC_CustomerPricePagination_LastPage_Click);
              this.DC_CustomerPricePagination.NextPage.Click += new ImageClickEventHandler(DC_CustomerPricePagination_NextPage_Click);
              this.DC_CustomerPricePagination.PageSizeButton.Click += new EventHandler(DC_CustomerPricePagination_PageSizeButton_Click);
            
              this.DC_CustomerPricePagination.PreviousPage.Click += new ImageClickEventHandler(DC_CustomerPricePagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.CommodityCodeLabel1.Click += new EventHandler(CommodityCodeLabel1_Click);
            
              this.CustomerIdLabel1.Click += new EventHandler(CustomerIdLabel1_Click);
            
              this.EffectiveDateLabel.Click += new EventHandler(EffectiveDateLabel_Click);
            
              this.PriceLabel.Click += new EventHandler(PriceLabel_Click);
            
              this.SizeIdLabel.Click += new EventHandler(SizeIdLabel_Click);
            

            // Setup the button events.
        
              this.DC_CustomerPriceCopyButton.Click += new ImageClickEventHandler(DC_CustomerPriceCopyButton_Click);
              this.DC_CustomerPriceDeleteButton.Click += new ImageClickEventHandler(DC_CustomerPriceDeleteButton_Click);
              this.DC_CustomerPriceEditButton.Click += new ImageClickEventHandler(DC_CustomerPriceEditButton_Click);
              this.DC_CustomerPriceExportButton.Click += new ImageClickEventHandler(DC_CustomerPriceExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerPriceExportButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerPriceTableControlUpdatePanel"));
                    
              this.DC_CustomerPriceExportExcelButton.Click += new ImageClickEventHandler(DC_CustomerPriceExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerPriceExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerPriceTableControlUpdatePanel"));
                    
              this.DC_CustomerPriceNewButton.Click += new ImageClickEventHandler(DC_CustomerPriceNewButton_Click);
              this.DC_CustomerPricePDFButton.Click += new ImageClickEventHandler(DC_CustomerPricePDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerPricePDFButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerPriceTableControlUpdatePanel"));
                    
              this.DC_CustomerPriceRefreshButton.Click += new ImageClickEventHandler(DC_CustomerPriceRefreshButton_Click);
              this.DC_CustomerPriceResetButton.Click += new ImageClickEventHandler(DC_CustomerPriceResetButton_Click);

            // Setup the filter and search events.
        
            this.CommodityCodeFilter.SelectedIndexChanged += new EventHandler(CommodityCodeFilter_SelectedIndexChanged);
            this.CustomerIdFilter.SelectedIndexChanged += new EventHandler(CustomerIdFilter_SelectedIndexChanged);
            this.SizeIdFilter.SelectedIndexChanged += new EventHandler(SizeIdFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.CommodityCodeFilter)) {
                this.CommodityCodeFilter.Items.Add(new ListItem(this.GetFromSession(this.CommodityCodeFilter), this.GetFromSession(this.CommodityCodeFilter)));
                this.CommodityCodeFilter.SelectedValue = this.GetFromSession(this.CommodityCodeFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomerIdFilter)) {
                this.CustomerIdFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomerIdFilter), this.GetFromSession(this.CustomerIdFilter)));
                this.CustomerIdFilter.SelectedValue = this.GetFromSession(this.CustomerIdFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.SizeIdFilter)) {
                this.SizeIdFilter.Items.Add(new ListItem(this.GetFromSession(this.SizeIdFilter), this.GetFromSession(this.SizeIdFilter)));
                this.SizeIdFilter.SelectedValue = this.GetFromSession(this.SizeIdFilter);
            }

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
                this.DC_CustomerPriceDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_CustomerPriceRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomerPriceRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_CustomerPriceTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_CustomerPriceRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomerPriceRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_CustomerPriceTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_CustomerPriceRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_CustomerPriceRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_CustomerPriceTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCommodityCodeFilter(MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), 500);
            this.PopulateCustomerIdFilter(MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), 500);
            this.PopulateSizeIdFilter(MiscUtils.GetSelectedValue(this.SizeIdFilter, this.GetFromSession(this.SizeIdFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomerPriceTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_CustomerPriceTableControlRow recControl = (DC_CustomerPriceTableControlRow)(repItem.FindControl("DC_CustomerPriceTableControlRow"));
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
          
            this.Page.PregetDfkaRecords(DC_CustomerPriceTable.CommodityCode, this.DataSource);
            this.Page.PregetDfkaRecords(DC_CustomerPriceTable.CustomerId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_CustomerPriceTable.SizeId, this.DataSource);
        }
         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_CustomerPriceTableControl pagination.
        
            this.DC_CustomerPricePagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_CustomerPricePagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomerPricePagination.LastPage.Enabled = false;
            }
          
            this.DC_CustomerPricePagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomerPricePagination.NextPage.Enabled = false;
            }
          
            this.DC_CustomerPricePagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_CustomerPricePagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_CustomerPricePagination.CurrentPage.Text = "0";
            }
            this.DC_CustomerPricePagination.PageSize.Text = this.PageSize.ToString();
            this.DC_CustomerPriceTotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomerPricePagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomerPricePagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_CustomerPriceTableControlRow recCtl in this.GetRecordControls())
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
            DC_CustomerPriceTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.SizeIdFilter, this.GetFromSession(this.SizeIdFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_CustomerPriceTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String CommodityCodeFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CommodityCodeFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CommodityCodeFilterSelectedValue)) {
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, CommodityCodeFilterSelectedValue, false, false);
            }
                      
            String CustomerIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerIdFilterSelectedValue)) {
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, CustomerIdFilterSelectedValue, false, false);
            }
                      
            String SizeIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "SizeIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(SizeIdFilterSelectedValue)) {
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, SizeIdFilterSelectedValue, false, false);
            }
                      
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
        
            if (this.DC_CustomerPricePagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_CustomerPricePagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomerPriceTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_CustomerPriceTableControlRow recControl = (DC_CustomerPriceTableControlRow)(repItem.FindControl("DC_CustomerPriceTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_CustomerPriceRecord rec = new DC_CustomerPriceRecord();
        
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_CustomerPriceTable.Comments);
                        }
                        if (recControl.CommodityCode.Text != "") {
                            rec.Parse(recControl.CommodityCode.Text, DC_CustomerPriceTable.CommodityCode);
                        }
                        if (recControl.CustomerId.Text != "") {
                            rec.Parse(recControl.CustomerId.Text, DC_CustomerPriceTable.CustomerId);
                        }
                        if (recControl.EffectiveDate.Text != "") {
                            rec.Parse(recControl.EffectiveDate.Text, DC_CustomerPriceTable.EffectiveDate);
                        }
                        if (recControl.Price.Text != "") {
                            rec.Parse(recControl.Price.Text, DC_CustomerPriceTable.Price);
                        }
                        if (recControl.SizeId.Text != "") {
                            rec.Parse(recControl.SizeId.Text, DC_CustomerPriceTable.SizeId);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_CustomerPriceRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_CustomerPriceRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_CustomerPriceRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_CustomerPriceTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_CustomerPriceTableControlRow rec)            
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
        
        // Get the filters' data for CommodityCodeFilter.
        protected virtual void PopulateCommodityCodeFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommodityTable.CommodityName, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CommodityCodeFilter.Items.Clear();
            foreach (DC_CommodityRecord itemValue in DC_CommodityTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CommodityCodeSpecified) {
                    cvalue = itemValue.CommodityCode.ToString();
                    fvalue = itemValue.Format(DC_CommodityTable.CommodityName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.CommodityCodeFilter.Items.IndexOf(item) < 0) {
                    this.CommodityCodeFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CommodityCodeFilter, selectedValue);

            // Add the All item.
            this.CommodityCodeFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for CustomerIdFilter.
        protected virtual void PopulateCustomerIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerName, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CustomerIdFilter.Items.Clear();
            foreach (DC_CustomerRecord itemValue in DC_CustomerTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CustomerIdSpecified) {
                    cvalue = itemValue.CustomerId.ToString();
                    fvalue = itemValue.Format(DC_CustomerTable.CustomerName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.CustomerIdFilter.Items.IndexOf(item) < 0) {
                    this.CustomerIdFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CustomerIdFilter, selectedValue);

            // Add the All item.
            this.CustomerIdFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for SizeIdFilter.
        protected virtual void PopulateSizeIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommoditySizeTable.Descr, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.SizeIdFilter.Items.Clear();
            foreach (DC_CommoditySizeRecord itemValue in DC_CommoditySizeTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.SizeIdSpecified) {
                    cvalue = itemValue.SizeId.ToString();
                    fvalue = itemValue.Format(DC_CommoditySizeTable.Descr);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.SizeIdFilter.Items.IndexOf(item) < 0) {
                    this.SizeIdFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.SizeIdFilter, selectedValue);

            // Add the All item.
            this.SizeIdFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CommodityCodeFilter.
        public virtual WhereClause CreateWhereClause_CommodityCodeFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.SizeIdFilter, this.GetFromSession(this.SizeIdFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter CustomerIdFilter.
        public virtual WhereClause CreateWhereClause_CustomerIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.SizeIdFilter, this.GetFromSession(this.SizeIdFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter SizeIdFilter.
        public virtual WhereClause CreateWhereClause_SizeIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_CustomerPriceTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_CustomerPriceTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            return wc;
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
        
            this.SaveToSession(this.CommodityCodeFilter, this.CommodityCodeFilter.SelectedValue);
            this.SaveToSession(this.CustomerIdFilter, this.CustomerIdFilter.SelectedValue);
            this.SaveToSession(this.SizeIdFilter, this.SizeIdFilter.SelectedValue);
            
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
          
            this.SaveToSession("CommodityCodeFilter_Ajax", this.CommodityCodeFilter.SelectedValue);
            this.SaveToSession("CustomerIdFilter_Ajax", this.CustomerIdFilter.SelectedValue);
            this.SaveToSession("SizeIdFilter_Ajax", this.SizeIdFilter.SelectedValue);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.CommodityCodeFilter);
            this.RemoveFromSession(this.CustomerIdFilter);
            this.RemoveFromSession(this.SizeIdFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_CustomerPriceTableControl_OrderBy"];
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
                this.ViewState["DC_CustomerPriceTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_CustomerPricePagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPricePagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPricePagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPricePagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_CustomerPricePagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_CustomerPricePagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPricePagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommodityCodeLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.CommodityCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.CommodityCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.CustomerId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.CustomerId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void EffectiveDateLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.EffectiveDate);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.EffectiveDate, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PriceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.Price);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.Price, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerPriceTable.SizeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerPriceTable.SizeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_CustomerPriceCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/AddDC_CustomerPricePage.aspx?DC_CustomerPrice={PK}";
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
              public virtual void DC_CustomerPriceDeleteButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            if (!this.Page.IsPageRefresh) {
        
                this.DeleteSelectedRecords(false);
          
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
              public virtual void DC_CustomerPriceEditButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_CustomerPriceTable.CustomerId,
             DC_CustomerPriceTable.CommodityCode,
             DC_CustomerPriceTable.SizeId,
             DC_CustomerPriceTable.EffectiveDate,
             DC_CustomerPriceTable.Comments,
             DC_CustomerPriceTable.Price,
             null};
            ExportData rep = new ExportData(DC_CustomerPriceTable.Instance,wc,orderBy,columns);
            rep.ExportToCSV(this.Page.Response);
                  this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceExportExcelButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            // To customize the columns or the format, override this function in Section 1 of the page
            // and modify it to your liking.
            // Build the where clause based on the current filter and search criteria
            // Create the Order By clause based on the user's current sorting preference.
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            // Create an instance of the Excel report class with the table class, where clause and order by.
            ExportData excelReport = new ExportData(DC_CustomerPriceTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.CustomerId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.CommodityCode, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.SizeId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.EffectiveDate, "Short Date"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerPriceTable.Price, "Standard"));

            excelReport.ExportToExcel(this.Page.Response);
                    this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomerPrice/AddDC_CustomerPricePage.aspx";
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
              public virtual void DC_CustomerPricePDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_CustomerPriceTablePage.DC_CustomerPricePDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_CustomerPrice";
                // If ShowDC_CustomerPriceTablePage.DC_CustomerPricePDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_CustomerPriceTable.CustomerId.Name, ReportEnum.Align.Left, "${DC_CustomerPriceTable.CustomerId.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerPriceTable.CommodityCode.Name, ReportEnum.Align.Left, "${DC_CustomerPriceTable.CommodityCode.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_CustomerPriceTable.SizeId.Name, ReportEnum.Align.Left, "${DC_CustomerPriceTable.SizeId.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerPriceTable.EffectiveDate.Name, ReportEnum.Align.Left, "${DC_CustomerPriceTable.EffectiveDate.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_CustomerPriceTable.Comments.Name, ReportEnum.Align.Left, "${DC_CustomerPriceTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerPriceTable.Price.Name, ReportEnum.Align.Right, "${DC_CustomerPriceTable.Price.Name}", ReportEnum.Align.Right, 20);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_CustomerPriceTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_CustomerPriceTable.GetColumnList();
                DC_CustomerPriceRecord[] records = null;
                do
                {
                    records = DC_CustomerPriceTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_CustomerPriceRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_CustomerPriceTable.CustomerId.Name}", record.Format(DC_CustomerPriceTable.CustomerId), ReportEnum.Align.Left);
                             report.AddData("${DC_CustomerPriceTable.CommodityCode.Name}", record.Format(DC_CustomerPriceTable.CommodityCode), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerPriceTable.SizeId.Name}", record.Format(DC_CustomerPriceTable.SizeId), ReportEnum.Align.Left);
                             report.AddData("${DC_CustomerPriceTable.EffectiveDate.Name}", record.Format(DC_CustomerPriceTable.EffectiveDate), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerPriceTable.Comments.Name}", record.Format(DC_CustomerPriceTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerPriceTable.Price.Name}", record.Format(DC_CustomerPriceTable.Price), ReportEnum.Align.Right, 100);

                            report.WriteRow();
                        }
                        pageNum++;
                        recordCount += records.Length;
                    }
                }
                while (records != null && recordCount < totalRecords);
                report.Close();
                BaseClasses.Utils.NetUtils.WriteResponseBinaryAttachment(this.Page.Response, report.Title + ".pdf", report.ReportInByteArray, 0, true);
                      this.Page.CommitTransaction(sender);
      
            } catch (Exception ex) {
                this.Page.RollBackTransaction(sender);
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
                DbUtils.EndTransaction();
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_CustomerPriceTableControl)(this.Page.FindControlRecursively("DC_CustomerPriceTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPriceResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CommodityCodeFilter.ClearSelection();
            
              this.CustomerIdFilter.ClearSelection();
            
              this.SizeIdFilter.ClearSelection();
            
              this.CurrentSortOrder.Reset();
              if (this.InSession(this, "Order_By")) {
              this.CurrentSortOrder = OrderBy.FromXmlString(this.GetFromSession(this, "Order_By", null));
              } else {
              this.CurrentSortOrder = new OrderBy(true, true);
            
            }

            this.DataChanged = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            

        // Generate the event handling functions for filter and search events.
        
        // event handler for FieldFilter
        protected virtual void CommodityCodeFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void CustomerIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void SizeIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            

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

        private DC_CustomerPriceRecord[] _DataSource = null;
        public  DC_CustomerPriceRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton CommentsLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CommodityCodeFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CommodityCodeLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel1");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CustomerIdFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CustomerIdLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel1");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceExportExcelButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_CustomerPricePagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPricePagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPricePDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPricePDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPriceResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomerPriceTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomerPriceToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_CustomerPriceTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPriceTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton EffectiveDateLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EffectiveDateLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PriceLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PriceLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeId1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList SizeIdFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton SizeIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_CustomerPriceTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_CustomerPriceRecord rec = null;
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
            foreach (DC_CustomerPriceTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomerPriceRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_CustomerPriceTableControlRow GetSelectedRecordControl()
        {
        DC_CustomerPriceTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_CustomerPriceTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_CustomerPriceTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomerPriceRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_CustomerPriceTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomerPriceTablePage.DC_CustomerPriceTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_CustomerPriceTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_CustomerPriceTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_CustomerPriceRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_CustomerPriceTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_CustomerPriceTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_CustomerPriceTableControlRow recControl = (DC_CustomerPriceTableControlRow)repItem.FindControl("DC_CustomerPriceTableControlRow");
                recList.Add(recControl);
            }

            return (DC_CustomerPriceTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomerPriceTablePage.DC_CustomerPriceTableControlRow"));
        }

        public BaseApplicationPage Page {
            get {
                return ((BaseApplicationPage)base.Page);
            }
        }

    #endregion

    

    }
  

#endregion
    
  
}

  