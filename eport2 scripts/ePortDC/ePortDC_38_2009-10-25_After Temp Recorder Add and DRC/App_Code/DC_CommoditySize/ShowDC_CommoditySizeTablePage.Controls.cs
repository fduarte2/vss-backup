
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CommoditySizeTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CommoditySizeTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CommoditySizeTableControlRow : BaseDC_CommoditySizeTableControlRow
{
      
        // The BaseDC_CommoditySizeTableControlRow implements code for a ROW within the
        // the DC_CommoditySizeTableControl table.  The BaseDC_CommoditySizeTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_CommoditySizeTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_CommoditySizeTableControl : BaseDC_CommoditySizeTableControl
{
        // The BaseDC_CommoditySizeTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_CommoditySizeTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CommoditySizeTableControlRow control on the ShowDC_CommoditySizeTablePage page.
// Do not modify this class. Instead override any method in DC_CommoditySizeTableControlRow.
public class BaseDC_CommoditySizeTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CommoditySizeTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CommoditySizeTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_CommoditySizeRecordRowCopyButton.Click += new ImageClickEventHandler(DC_CommoditySizeRecordRowCopyButton_Click);
              this.DC_CommoditySizeRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_CommoditySizeRecordRowDeleteButton_Click);
              this.DC_CommoditySizeRecordRowEditButton.Click += new ImageClickEventHandler(DC_CommoditySizeRecordRowEditButton_Click);
              this.DC_CommoditySizeRecordRowViewButton.Click += new ImageClickEventHandler(DC_CommoditySizeRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_CommoditySizeTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_CommoditySizeRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_CommoditySizeTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CommoditySizeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_CommoditySizeTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_CommoditySizeRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CommoditySizeTableControlRow.
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

        
            if (this.DataSource.DescrSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.Descr);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Descr.Text = formattedValue;
                        
            } else {  
                this.Descr.Text = DC_CommoditySizeTable.Descr.Format(DC_CommoditySizeTable.Descr.DefaultValue);
            }
                    
            if (this.Descr.Text == null ||
                this.Descr.Text.Trim().Length == 0) {
                this.Descr.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.Price);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Price.Text = formattedValue;
                        
            } else {  
                this.Price.Text = DC_CommoditySizeTable.Price.Format(DC_CommoditySizeTable.Price.DefaultValue);
            }
                    
            if (this.Price.Text == null ||
                this.Price.Text.Trim().Length == 0) {
                this.Price.Text = "&nbsp;";
            }
                  
            if (this.DataSource.SizeHighSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeHigh);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SizeHigh.Text = formattedValue;
                        
            } else {  
                this.SizeHigh.Text = DC_CommoditySizeTable.SizeHigh.Format(DC_CommoditySizeTable.SizeHigh.DefaultValue);
            }
                    
            if (this.SizeHigh.Text == null ||
                this.SizeHigh.Text.Trim().Length == 0) {
                this.SizeHigh.Text = "&nbsp;";
            }
                  
            if (this.DataSource.SizeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SizeId.Text = formattedValue;
                        
            } else {  
                this.SizeId.Text = DC_CommoditySizeTable.SizeId.Format(DC_CommoditySizeTable.SizeId.DefaultValue);
            }
                    
            if (this.SizeId.Text == null ||
                this.SizeId.Text.Trim().Length == 0) {
                this.SizeId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.SizeLowSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeLow);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SizeLow.Text = formattedValue;
                        
            } else {  
                this.SizeLow.Text = DC_CommoditySizeTable.SizeLow.Format(DC_CommoditySizeTable.SizeLow.DefaultValue);
            }
                    
            if (this.SizeLow.Text == null ||
                this.SizeLow.Text.Trim().Length == 0) {
                this.SizeLow.Text = "&nbsp;";
            }
                  
            if (this.DataSource.WeightKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.WeightKG);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.WeightKG.Text = formattedValue;
                        
            } else {  
                this.WeightKG.Text = DC_CommoditySizeTable.WeightKG.Format(DC_CommoditySizeTable.WeightKG.DefaultValue);
            }
                    
            if (this.WeightKG.Text == null ||
                this.WeightKG.Text.Trim().Length == 0) {
                this.WeightKG.Text = "&nbsp;";
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

        //  To customize, override this method in DC_CommoditySizeTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_CommoditySizeTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CommoditySizeTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_CommoditySizeTableControl)MiscUtils.GetParentControlObject(this, "DC_CommoditySizeTableControl")).DataChanged = true;
                ((DC_CommoditySizeTableControl)MiscUtils.GetParentControlObject(this, "DC_CommoditySizeTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_CommoditySizeTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_CommoditySizeTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_CommoditySizeTableControlRow.
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
            DC_CommoditySizeTable.DeleteRecord(pk);

          
            ((DC_CommoditySizeTableControl)MiscUtils.GetParentControlObject(this, "DC_CommoditySizeTableControl")).DataChanged = true;
            ((DC_CommoditySizeTableControl)MiscUtils.GetParentControlObject(this, "DC_CommoditySizeTableControl")).ResetData = true;
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
              public virtual void DC_CommoditySizeRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/AddDC_CommoditySizePage.aspx?DC_CommoditySize={PK}";
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
              public virtual void DC_CommoditySizeRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommoditySizeRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/EditDC_CommoditySizePage.aspx?DC_CommoditySize={PK}";
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
              public virtual void DC_CommoditySizeRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/ShowDC_CommoditySizePage.aspx?DC_CommoditySize={PK}";
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
                return (string)this.ViewState["BaseDC_CommoditySizeTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_CommoditySizeTableControlRow_Rec"] = value;
            }
        }
        
        private DC_CommoditySizeRecord _DataSource;
        public DC_CommoditySizeRecord DataSource {
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
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CommoditySizeRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal Descr {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Descr");
            }
        }
           
        public System.Web.UI.WebControls.Literal Price {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Price");
            }
        }
           
        public System.Web.UI.WebControls.Literal SizeHigh {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHigh");
            }
        }
           
        public System.Web.UI.WebControls.Literal SizeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId");
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
            DC_CommoditySizeRecord rec = null;
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

        public DC_CommoditySizeRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_CommoditySizeTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_CommoditySizeTableControl control on the ShowDC_CommoditySizeTablePage page.
// Do not modify this class. Instead override any method in DC_CommoditySizeTableControl.
public class BaseDC_CommoditySizeTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_CommoditySizeTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_CommoditySizePagination.FirstPage.Click += new ImageClickEventHandler(DC_CommoditySizePagination_FirstPage_Click);
              this.DC_CommoditySizePagination.LastPage.Click += new ImageClickEventHandler(DC_CommoditySizePagination_LastPage_Click);
              this.DC_CommoditySizePagination.NextPage.Click += new ImageClickEventHandler(DC_CommoditySizePagination_NextPage_Click);
              this.DC_CommoditySizePagination.PageSizeButton.Click += new EventHandler(DC_CommoditySizePagination_PageSizeButton_Click);
            
              this.DC_CommoditySizePagination.PreviousPage.Click += new ImageClickEventHandler(DC_CommoditySizePagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.DescrLabel1.Click += new EventHandler(DescrLabel1_Click);
            
              this.PriceLabel.Click += new EventHandler(PriceLabel_Click);
            
              this.SizeHighLabel.Click += new EventHandler(SizeHighLabel_Click);
            
              this.SizeIdLabel1.Click += new EventHandler(SizeIdLabel1_Click);
            
              this.SizeLowLabel.Click += new EventHandler(SizeLowLabel_Click);
            
              this.WeightKGLabel.Click += new EventHandler(WeightKGLabel_Click);
            

            // Setup the button events.
        
              this.DC_CommoditySizeCopyButton.Click += new ImageClickEventHandler(DC_CommoditySizeCopyButton_Click);
              this.DC_CommoditySizeDeleteButton.Click += new ImageClickEventHandler(DC_CommoditySizeDeleteButton_Click);
              this.DC_CommoditySizeEditButton.Click += new ImageClickEventHandler(DC_CommoditySizeEditButton_Click);
              this.DC_CommoditySizeExportButton.Click += new ImageClickEventHandler(DC_CommoditySizeExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommoditySizeExportButton"), MiscUtils.GetParentControlObject(this,"DC_CommoditySizeTableControlUpdatePanel"));
                    
              this.DC_CommoditySizeExportExcelButton.Click += new ImageClickEventHandler(DC_CommoditySizeExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommoditySizeExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_CommoditySizeTableControlUpdatePanel"));
                    
              this.DC_CommoditySizeNewButton.Click += new ImageClickEventHandler(DC_CommoditySizeNewButton_Click);
              this.DC_CommoditySizePDFButton.Click += new ImageClickEventHandler(DC_CommoditySizePDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommoditySizePDFButton"), MiscUtils.GetParentControlObject(this,"DC_CommoditySizeTableControlUpdatePanel"));
                    
              this.DC_CommoditySizeRefreshButton.Click += new ImageClickEventHandler(DC_CommoditySizeRefreshButton_Click);
              this.DC_CommoditySizeResetButton.Click += new ImageClickEventHandler(DC_CommoditySizeResetButton_Click);
              this.DC_CommoditySizeFilterButton.Button.Click += new EventHandler(DC_CommoditySizeFilterButton_Click);

            // Setup the filter and search events.
        
            this.DescrFilter.SelectedIndexChanged += new EventHandler(DescrFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.DescrFilter)) {
                this.DescrFilter.Items.Add(new ListItem(this.GetFromSession(this.DescrFilter), this.GetFromSession(this.DescrFilter)));
                this.DescrFilter.SelectedValue = this.GetFromSession(this.DescrFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.SizeIdFromFilter)) {
                
                this.SizeIdFromFilter.Text = this.GetFromSession(this.SizeIdFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.SizeIdToFilter)) {
                
                this.SizeIdToFilter.Text = this.GetFromSession(this.SizeIdToFilter);
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
                this.DC_CommoditySizeDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_CommoditySizeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CommoditySizeRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_CommoditySizeTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_CommoditySizeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CommoditySizeRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_CommoditySizeTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_CommoditySizeRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_CommoditySizeRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_CommoditySizeTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        

            // Setup the pagination controls.
            BindPaginationControls();

            // Populate all filters data.
        
            this.PopulateDescrFilter(MiscUtils.GetSelectedValue(this.DescrFilter, this.GetFromSession(this.DescrFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CommoditySizeTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_CommoditySizeTableControlRow recControl = (DC_CommoditySizeTableControlRow)(repItem.FindControl("DC_CommoditySizeTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_CommoditySizeTableControl pagination.
        
            this.DC_CommoditySizePagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_CommoditySizePagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CommoditySizePagination.LastPage.Enabled = false;
            }
          
            this.DC_CommoditySizePagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CommoditySizePagination.NextPage.Enabled = false;
            }
          
            this.DC_CommoditySizePagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_CommoditySizePagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_CommoditySizePagination.CurrentPage.Text = "0";
            }
            this.DC_CommoditySizePagination.PageSize.Text = this.PageSize.ToString();
            this.DC_CommoditySizeTotalItems.Text = this.TotalRecords.ToString();
            this.DC_CommoditySizePagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_CommoditySizePagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_CommoditySizeTableControlRow recCtl in this.GetRecordControls())
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
            DC_CommoditySizeTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.DescrFilter)) {
                wc.iAND(DC_CommoditySizeTable.Descr, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.DescrFilter, this.GetFromSession(this.DescrFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdFromFilter)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.SizeIdFromFilter, this.GetFromSession(this.SizeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdToFilter)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.SizeIdToFilter, this.GetFromSession(this.SizeIdToFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_CommoditySizeTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String DescrFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "DescrFilter_Ajax"];
            if (MiscUtils.IsValueSelected(DescrFilterSelectedValue)) {
                wc.iAND(DC_CommoditySizeTable.Descr, BaseFilter.ComparisonOperator.EqualsTo, DescrFilterSelectedValue, false, false);
            }
                      
            String SizeIdFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "SizeIdFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(SizeIdFromFilterSelectedValue)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, SizeIdFromFilterSelectedValue, false, false);
            }
                      
            String SizeIdToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "SizeIdToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(SizeIdToFilterSelectedValue)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, SizeIdToFilterSelectedValue, false, false);
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
        
            if (this.DC_CommoditySizePagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_CommoditySizePagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CommoditySizeTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_CommoditySizeTableControlRow recControl = (DC_CommoditySizeTableControlRow)(repItem.FindControl("DC_CommoditySizeTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_CommoditySizeRecord rec = new DC_CommoditySizeRecord();
        
                        if (recControl.Descr.Text != "") {
                            rec.Parse(recControl.Descr.Text, DC_CommoditySizeTable.Descr);
                        }
                        if (recControl.Price.Text != "") {
                            rec.Parse(recControl.Price.Text, DC_CommoditySizeTable.Price);
                        }
                        if (recControl.SizeHigh.Text != "") {
                            rec.Parse(recControl.SizeHigh.Text, DC_CommoditySizeTable.SizeHigh);
                        }
                        if (recControl.SizeId.Text != "") {
                            rec.Parse(recControl.SizeId.Text, DC_CommoditySizeTable.SizeId);
                        }
                        if (recControl.SizeLow.Text != "") {
                            rec.Parse(recControl.SizeLow.Text, DC_CommoditySizeTable.SizeLow);
                        }
                        if (recControl.WeightKG.Text != "") {
                            rec.Parse(recControl.WeightKG.Text, DC_CommoditySizeTable.WeightKG);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_CommoditySizeRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_CommoditySizeRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_CommoditySizeRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_CommoditySizeTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_CommoditySizeTableControlRow rec)            
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
        
        // Get the filters' data for DescrFilter.
        protected virtual void PopulateDescrFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommoditySizeTable.Descr, OrderByItem.OrderDir.Asc);

            string[] list = DC_CommoditySizeTable.GetValues(DC_CommoditySizeTable.Descr, wc, orderBy, maxItems);
            
            this.DescrFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CommoditySizeTable.Descr.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.DescrFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.DescrFilter, selectedValue);

            // Add the All item.
            this.DescrFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter DescrFilter.
        public virtual WhereClause CreateWhereClause_DescrFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.SizeIdFromFilter)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.SizeIdFromFilter, this.GetFromSession(this.SizeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.SizeIdToFilter)) {
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.SizeIdToFilter, this.GetFromSession(this.SizeIdToFilter)), false, false);
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
        
            this.SaveToSession(this.DescrFilter, this.DescrFilter.SelectedValue);
            this.SaveToSession(this.SizeIdFromFilter, this.SizeIdFromFilter.Text);
            this.SaveToSession(this.SizeIdToFilter, this.SizeIdToFilter.Text);
            
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
          
            this.SaveToSession("DescrFilter_Ajax", this.DescrFilter.SelectedValue);
            this.SaveToSession("SizeIdFromFilter_Ajax", this.SizeIdFromFilter.Text);
            this.SaveToSession("SizeIdToFilter_Ajax", this.SizeIdToFilter.Text);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.DescrFilter);
            this.RemoveFromSession(this.SizeIdFromFilter);
            this.RemoveFromSession(this.SizeIdToFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_CommoditySizeTableControl_OrderBy"];
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
                this.ViewState["DC_CommoditySizeTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_CommoditySizePagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommoditySizePagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommoditySizePagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommoditySizePagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_CommoditySizePagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_CommoditySizePagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CommoditySizePagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DescrLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.Descr);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.Descr, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PriceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.Price);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.Price, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeHighLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.SizeHigh);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.SizeHigh, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.SizeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.SizeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SizeLowLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.SizeLow);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.SizeLow, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void WeightKGLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommoditySizeTable.WeightKG);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommoditySizeTable.WeightKG, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_CommoditySizeCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/AddDC_CommoditySizePage.aspx?DC_CommoditySize={PK}";
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
              public virtual void DC_CommoditySizeDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommoditySizeEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CommoditySize/EditDC_CommoditySizePage.aspx?DC_CommoditySize={PK}";
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
              public virtual void DC_CommoditySizeExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_CommoditySizeTable.SizeId,
             DC_CommoditySizeTable.Descr,
             DC_CommoditySizeTable.Price,
             DC_CommoditySizeTable.SizeHigh,
             DC_CommoditySizeTable.SizeLow,
             DC_CommoditySizeTable.WeightKG,
             null};
            ExportData rep = new ExportData(DC_CommoditySizeTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_CommoditySizeExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_CommoditySizeTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.SizeId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.Descr, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.Price, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.SizeHigh, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.SizeLow, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CommoditySizeTable.WeightKG, "Standard"));

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
              public virtual void DC_CommoditySizeNewButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_CommoditySizePDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_CommoditySizeTablePage.DC_CommoditySizePDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_CommoditySize";
                // If ShowDC_CommoditySizeTablePage.DC_CommoditySizePDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_CommoditySizeTable.SizeId.Name, ReportEnum.Align.Right, "${DC_CommoditySizeTable.SizeId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CommoditySizeTable.Descr.Name, ReportEnum.Align.Left, "${DC_CommoditySizeTable.Descr.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CommoditySizeTable.Price.Name, ReportEnum.Align.Right, "${DC_CommoditySizeTable.Price.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_CommoditySizeTable.SizeHigh.Name, ReportEnum.Align.Right, "${DC_CommoditySizeTable.SizeHigh.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CommoditySizeTable.SizeLow.Name, ReportEnum.Align.Right, "${DC_CommoditySizeTable.SizeLow.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CommoditySizeTable.WeightKG.Name, ReportEnum.Align.Right, "${DC_CommoditySizeTable.WeightKG.Name}", ReportEnum.Align.Right, 16);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_CommoditySizeTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_CommoditySizeTable.GetColumnList();
                DC_CommoditySizeRecord[] records = null;
                do
                {
                    records = DC_CommoditySizeTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_CommoditySizeRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_CommoditySizeTable.SizeId.Name}", record.Format(DC_CommoditySizeTable.SizeId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CommoditySizeTable.Descr.Name}", record.Format(DC_CommoditySizeTable.Descr), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CommoditySizeTable.Price.Name}", record.Format(DC_CommoditySizeTable.Price), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CommoditySizeTable.SizeHigh.Name}", record.Format(DC_CommoditySizeTable.SizeHigh), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CommoditySizeTable.SizeLow.Name}", record.Format(DC_CommoditySizeTable.SizeLow), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CommoditySizeTable.WeightKG.Name}", record.Format(DC_CommoditySizeTable.WeightKG), ReportEnum.Align.Right, 100);

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
              public virtual void DC_CommoditySizeRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_CommoditySizeTableControl)(this.Page.FindControlRecursively("DC_CommoditySizeTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CommoditySizeResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.DescrFilter.ClearSelection();
            
              this.SizeIdFromFilter.Text = "";
            
              this.SizeIdToFilter.Text = "";
            
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
            
              // event handler for Button with Layout
              public virtual void DC_CommoditySizeFilterButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            

        // Generate the event handling functions for filter and search events.
        
        // event handler for FieldFilter
        protected virtual void DescrFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_CommoditySizeRecord[] _DataSource = null;
        public  DC_CommoditySizeRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_CommoditySizeFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_CommoditySizePagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizePagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizePDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizePDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommoditySizeResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CommoditySizeTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CommoditySizeToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_CommoditySizeTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList DescrFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DescrFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal DescrLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DescrLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DescrLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DescrLabel1");
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
        
        public System.Web.UI.WebControls.TextBox SizeIdFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdFromFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton SizeIdLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdLabel1");
            }
        }
        
        public System.Web.UI.WebControls.TextBox SizeIdToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdToFilter");
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
                DC_CommoditySizeTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_CommoditySizeRecord rec = null;
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
            foreach (DC_CommoditySizeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CommoditySizeRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_CommoditySizeTableControlRow GetSelectedRecordControl()
        {
        DC_CommoditySizeTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_CommoditySizeTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_CommoditySizeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CommoditySizeRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_CommoditySizeTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CommoditySizeTablePage.DC_CommoditySizeTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_CommoditySizeTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_CommoditySizeTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_CommoditySizeRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_CommoditySizeTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_CommoditySizeTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_CommoditySizeTableControlRow recControl = (DC_CommoditySizeTableControlRow)repItem.FindControl("DC_CommoditySizeTableControlRow");
                recList.Add(recControl);
            }

            return (DC_CommoditySizeTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CommoditySizeTablePage.DC_CommoditySizeTableControlRow"));
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

  