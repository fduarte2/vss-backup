
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_VesselTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_VesselTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_VesselTableControlRow : BaseDC_VesselTableControlRow
{
      
        // The BaseDC_VesselTableControlRow implements code for a ROW within the
        // the DC_VesselTableControl table.  The BaseDC_VesselTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_VesselTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_VesselTableControl : BaseDC_VesselTableControl
{
        // The BaseDC_VesselTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_VesselTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_VesselTableControlRow control on the ShowDC_VesselTablePage page.
// Do not modify this class. Instead override any method in DC_VesselTableControlRow.
public class BaseDC_VesselTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_VesselTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_VesselTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_VesselRecordRowCopyButton.Click += new ImageClickEventHandler(DC_VesselRecordRowCopyButton_Click);
              this.DC_VesselRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_VesselRecordRowDeleteButton_Click);
              this.DC_VesselRecordRowEditButton.Click += new ImageClickEventHandler(DC_VesselRecordRowEditButton_Click);
              this.DC_VesselRecordRowViewButton.Click += new ImageClickEventHandler(DC_VesselRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_VesselTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_VesselRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_VesselTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_VesselTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_VesselTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_VesselRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_VesselTableControlRow.
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

        
            if (this.DataSource.ArrivalDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_VesselTable.ArrivalDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ArrivalDate.Text = formattedValue;
                        
            } else {  
                this.ArrivalDate.Text = DC_VesselTable.ArrivalDate.Format(DC_VesselTable.ArrivalDate.DefaultValue);
            }
                    
            if (this.ArrivalDate.Text == null ||
                this.ArrivalDate.Text.Trim().Length == 0) {
                this.ArrivalDate.Text = "&nbsp;";
            }
                  
            if (this.DataSource.FixedFreightSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_VesselTable.FixedFreight);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.FixedFreight.Text = formattedValue;
                        
            } else {  
                this.FixedFreight.Text = DC_VesselTable.FixedFreight.Format(DC_VesselTable.FixedFreight.DefaultValue);
            }
                    
            if (this.FixedFreight.Text == null ||
                this.FixedFreight.Text.Trim().Length == 0) {
                this.FixedFreight.Text = "&nbsp;";
            }
                  
            if (this.DataSource.VesselIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_VesselTable.VesselId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.VesselId.Text = formattedValue;
                        
            } else {  
                this.VesselId.Text = DC_VesselTable.VesselId.Format(DC_VesselTable.VesselId.DefaultValue);
            }
                    
            if (this.VesselId.Text == null ||
                this.VesselId.Text.Trim().Length == 0) {
                this.VesselId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.VesselNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_VesselTable.VesselName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.VesselName.Text = formattedValue;
                        
            } else {  
                this.VesselName.Text = DC_VesselTable.VesselName.Format(DC_VesselTable.VesselName.DefaultValue);
            }
                    
            if (this.VesselName.Text == null ||
                this.VesselName.Text.Trim().Length == 0) {
                this.VesselName.Text = "&nbsp;";
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

        //  To customize, override this method in DC_VesselTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_VesselTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_VesselTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_VesselTableControl)MiscUtils.GetParentControlObject(this, "DC_VesselTableControl")).DataChanged = true;
                ((DC_VesselTableControl)MiscUtils.GetParentControlObject(this, "DC_VesselTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_VesselTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_VesselTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_VesselTableControlRow.
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
            DC_VesselTable.DeleteRecord(pk);

          
            ((DC_VesselTableControl)MiscUtils.GetParentControlObject(this, "DC_VesselTableControl")).DataChanged = true;
            ((DC_VesselTableControl)MiscUtils.GetParentControlObject(this, "DC_VesselTableControl")).ResetData = true;
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
              public virtual void DC_VesselRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/AddDC_VesselPage.aspx?DC_Vessel={PK}";
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
              public virtual void DC_VesselRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_VesselRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/EditDC_VesselPage.aspx?DC_Vessel={PK}";
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
              public virtual void DC_VesselRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/ShowDC_VesselPage.aspx?DC_Vessel={PK}";
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
                return (string)this.ViewState["BaseDC_VesselTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_VesselTableControlRow_Rec"] = value;
            }
        }
        
        private DC_VesselRecord _DataSource;
        public DC_VesselRecord DataSource {
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
           
        public System.Web.UI.WebControls.Literal ArrivalDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ArrivalDate");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_VesselRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal FixedFreight {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FixedFreight");
            }
        }
           
        public System.Web.UI.WebControls.Literal VesselId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselId");
            }
        }
           
        public System.Web.UI.WebControls.Literal VesselName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselName");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_VesselRecord rec = null;
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

        public DC_VesselRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_VesselTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_VesselTableControl control on the ShowDC_VesselTablePage page.
// Do not modify this class. Instead override any method in DC_VesselTableControl.
public class BaseDC_VesselTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_VesselTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_VesselPagination.FirstPage.Click += new ImageClickEventHandler(DC_VesselPagination_FirstPage_Click);
              this.DC_VesselPagination.LastPage.Click += new ImageClickEventHandler(DC_VesselPagination_LastPage_Click);
              this.DC_VesselPagination.NextPage.Click += new ImageClickEventHandler(DC_VesselPagination_NextPage_Click);
              this.DC_VesselPagination.PageSizeButton.Click += new EventHandler(DC_VesselPagination_PageSizeButton_Click);
            
              this.DC_VesselPagination.PreviousPage.Click += new ImageClickEventHandler(DC_VesselPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.ArrivalDateLabel1.Click += new EventHandler(ArrivalDateLabel1_Click);
            
              this.FixedFreightLabel.Click += new EventHandler(FixedFreightLabel_Click);
            
              this.VesselIdLabel.Click += new EventHandler(VesselIdLabel_Click);
            
              this.VesselNameLabel.Click += new EventHandler(VesselNameLabel_Click);
            

            // Setup the button events.
        
              this.DC_VesselCopyButton.Click += new ImageClickEventHandler(DC_VesselCopyButton_Click);
              this.DC_VesselDeleteButton.Click += new ImageClickEventHandler(DC_VesselDeleteButton_Click);
              this.DC_VesselEditButton.Click += new ImageClickEventHandler(DC_VesselEditButton_Click);
              this.DC_VesselExportButton.Click += new ImageClickEventHandler(DC_VesselExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_VesselExportButton"), MiscUtils.GetParentControlObject(this,"DC_VesselTableControlUpdatePanel"));
                    
              this.DC_VesselExportExcelButton.Click += new ImageClickEventHandler(DC_VesselExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_VesselExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_VesselTableControlUpdatePanel"));
                    
              this.DC_VesselNewButton.Click += new ImageClickEventHandler(DC_VesselNewButton_Click);
              this.DC_VesselPDFButton.Click += new ImageClickEventHandler(DC_VesselPDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_VesselPDFButton"), MiscUtils.GetParentControlObject(this,"DC_VesselTableControlUpdatePanel"));
                    
              this.DC_VesselRefreshButton.Click += new ImageClickEventHandler(DC_VesselRefreshButton_Click);
              this.DC_VesselResetButton.Click += new ImageClickEventHandler(DC_VesselResetButton_Click);
              this.DC_VesselFilterButton.Button.Click += new EventHandler(DC_VesselFilterButton_Click);
              this.DC_VesselSearchButton.Button.Click += new EventHandler(DC_VesselSearchButton_Click);

            // Setup the filter and search events.
        
            if (!this.Page.IsPostBack && this.InSession(this.ArrivalDateFromFilter)) {
                
                this.ArrivalDateFromFilter.Text = this.GetFromSession(this.ArrivalDateFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.ArrivalDateToFilter)) {
                
                this.ArrivalDateToFilter.Text = this.GetFromSession(this.ArrivalDateToFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.DC_VesselSearchArea)) {
                
                this.DC_VesselSearchArea.Text = this.GetFromSession(this.DC_VesselSearchArea);
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
                this.DC_VesselDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_VesselRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_VesselTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_VesselRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_VesselTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_VesselRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_VesselTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_VesselTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_VesselTableControlRow recControl = (DC_VesselTableControlRow)(repItem.FindControl("DC_VesselTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_VesselTableControl pagination.
        
            this.DC_VesselPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_VesselPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_VesselPagination.LastPage.Enabled = false;
            }
          
            this.DC_VesselPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_VesselPagination.NextPage.Enabled = false;
            }
          
            this.DC_VesselPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_VesselPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_VesselPagination.CurrentPage.Text = "0";
            }
            this.DC_VesselPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_VesselTotalItems.Text = this.TotalRecords.ToString();
            this.DC_VesselPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_VesselPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_VesselTableControlRow recCtl in this.GetRecordControls())
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
            DC_VesselTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.ArrivalDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.ArrivalDateFromFilter, this.GetFromSession(this.ArrivalDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_VesselTable.ArrivalDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.ArrivalDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.ArrivalDateToFilter, this.GetFromSession(this.ArrivalDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_VesselTable.ArrivalDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_VesselSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_VesselSearchArea.Text.StartsWith("...")) {
                    this.DC_VesselSearchArea.Text = this.DC_VesselSearchArea.Text.Substring(3,this.DC_VesselSearchArea.Text.Length-3);
                }
                if (this.DC_VesselSearchArea.Text.EndsWith("...")) {
                    this.DC_VesselSearchArea.Text = this.DC_VesselSearchArea.Text.Substring(0,this.DC_VesselSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_VesselSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_VesselTable.VesselName, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.DC_VesselSearchArea, this.GetFromSession(this.DC_VesselSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_VesselTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String ArrivalDateFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "ArrivalDateFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(ArrivalDateFromFilterSelectedValue)) {
                DateTime d = DateParser.ParseDate(ArrivalDateFromFilterSelectedValue, DateColumn.DEFAULT_FORMAT);
                ArrivalDateFromFilterSelectedValue = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_VesselTable.ArrivalDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, ArrivalDateFromFilterSelectedValue, false, false);
            }         
            String ArrivalDateToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "ArrivalDateToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(ArrivalDateToFilterSelectedValue)) {
                DateTime d = DateParser.ParseDate(ArrivalDateToFilterSelectedValue, DateColumn.DEFAULT_FORMAT);
                ArrivalDateToFilterSelectedValue = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_VesselTable.ArrivalDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, ArrivalDateToFilterSelectedValue, false, false);
            }         
            if (MiscUtils.IsValueSelected(searchText) && fromSearchControl == "DC_VesselSearchArea") {
                String formatedSearchText = searchText;
                // strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (searchText.StartsWith("...")) {
                    formatedSearchText = searchText.Substring(3,searchText.Length-3);
                }
                if (searchText.EndsWith("...")) {
                    formatedSearchText = searchText.Substring(0,searchText.Length-3);
                }
                // After stripping "...", trim any leading and trailing whitespaces 
                formatedSearchText = formatedSearchText.Trim();
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(searchText)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                    if (StringUtils.InvariantLCase(AutoTypeAheadSearch).Equals("wordsstartingwithsearchstring")) {
                
                      // Even though the field operator is equals, to provide meaningfull result starts_with operator is furcefully used,
                      search.iOR(DC_VesselTable.VesselName, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                
                    } else {
                        
                      // Even though the field operator is equals, to provide meaningfull result starts_with operator is furcefully used,
                      search.iOR(DC_VesselTable.VesselName, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                    } 
                    wc.iAND(search);
                }
            }
                  
            return wc;
        }
          
         public virtual string[] GetAutoCompletionList_DC_VesselSearchArea(String prefixText,int count)
         {
            ArrayList resultList = new ArrayList();
            ArrayList wordList= new ArrayList();
            int iteration = 0;
            
            WhereClause wc= CreateWhereClause(prefixText,"DC_VesselSearchArea", "WordsStartingWithSearchString", "[^a-zA-Z0-9]");
            while (resultList.Count < count && iteration < 5){
                // Fetch 100 records in each iteration
                ePortDC.Business.DC_VesselRecord[] records = DC_VesselTable.GetRecords(wc, null, iteration, 100);
                String resultItem = "";
                foreach (DC_VesselRecord rec in records){
                    // Exit the loop if recordList count has reached AutoTypeAheadListSize.
                    if (resultList.Count >= count) {
                        break;
                    }
                    // If the field is configured to Display as Foreign key, Format() method returns the 
                    // Display as Forien Key value instead of original field value.
                    // Since search had to be done in multiple fields (selected in Control's page property, binding tab) in a record,
                    // We need to find relevent field to display which matches the prefixText and is not already present in the result list.
            
                    resultItem = rec.Format(DC_VesselTable.VesselName);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                }
                // Exit the loop if number of records found is less as further iteration will not return any more records
                if (records.Length < 100 ) {
                    break;
                }
                iteration++;
            }
            resultList.Sort();
            string[] result = new string[resultList.Count];
            Array.Copy(resultList.ToArray(), result, resultList.Count);
            return result;
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
        
            if (this.DC_VesselPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_VesselPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_VesselTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_VesselTableControlRow recControl = (DC_VesselTableControlRow)(repItem.FindControl("DC_VesselTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_VesselRecord rec = new DC_VesselRecord();
        
                        if (recControl.ArrivalDate.Text != "") {
                            rec.Parse(recControl.ArrivalDate.Text, DC_VesselTable.ArrivalDate);
                        }
                        if (recControl.FixedFreight.Text != "") {
                            rec.Parse(recControl.FixedFreight.Text, DC_VesselTable.FixedFreight);
                        }
                        if (recControl.VesselId.Text != "") {
                            rec.Parse(recControl.VesselId.Text, DC_VesselTable.VesselId);
                        }
                        if (recControl.VesselName.Text != "") {
                            rec.Parse(recControl.VesselName.Text, DC_VesselTable.VesselName);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_VesselRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_VesselRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_VesselTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_VesselTableControlRow rec)            
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
                      
        // Create a where clause for the filter DC_VesselSearchArea.
        public virtual WhereClause CreateWhereClause_DC_VesselSearchArea()
        {
              
            return new WhereClause();
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
        
            this.SaveToSession(this.ArrivalDateFromFilter, this.ArrivalDateFromFilter.Text);
            this.SaveToSession(this.ArrivalDateToFilter, this.ArrivalDateToFilter.Text);
            this.SaveToSession(this.DC_VesselSearchArea, this.DC_VesselSearchArea.Text);
            
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
          
            this.SaveToSession("ArrivalDateFromFilter_Ajax", this.ArrivalDateFromFilter.Text);
            this.SaveToSession("ArrivalDateToFilter_Ajax", this.ArrivalDateToFilter.Text);
            this.SaveToSession("DC_VesselSearchArea_Ajax", this.DC_VesselSearchArea.Text);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.ArrivalDateFromFilter);
            this.RemoveFromSession(this.ArrivalDateToFilter);
            this.RemoveFromSession(this.DC_VesselSearchArea);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_VesselTableControl_OrderBy"];
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
                this.ViewState["DC_VesselTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_VesselPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_VesselPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_VesselPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_VesselPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_VesselPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_VesselPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_VesselPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void ArrivalDateLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_VesselTable.ArrivalDate);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_VesselTable.ArrivalDate, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void FixedFreightLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_VesselTable.FixedFreight);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_VesselTable.FixedFreight, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void VesselIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_VesselTable.VesselId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_VesselTable.VesselId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void VesselNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_VesselTable.VesselName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_VesselTable.VesselName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_VesselCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/AddDC_VesselPage.aspx?DC_Vessel={PK}";
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
              public virtual void DC_VesselDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_VesselEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/EditDC_VesselPage.aspx?DC_Vessel={PK}";
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
              public virtual void DC_VesselExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_VesselTable.VesselId,
             DC_VesselTable.ArrivalDate,
             DC_VesselTable.FixedFreight,
             DC_VesselTable.VesselName,
             null};
            ExportData rep = new ExportData(DC_VesselTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_VesselExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_VesselTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_VesselTable.VesselId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_VesselTable.ArrivalDate, "Short Date"));
             excelReport.AddColumn(new ExcelColumn(DC_VesselTable.FixedFreight, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_VesselTable.VesselName, "Default"));

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
              public virtual void DC_VesselNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Vessel/AddDC_VesselPage.aspx";
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
              public virtual void DC_VesselPDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_VesselTablePage.DC_VesselPDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Vessel";
                // If ShowDC_VesselTablePage.DC_VesselPDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_VesselTable.VesselId.Name, ReportEnum.Align.Right, "${DC_VesselTable.VesselId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_VesselTable.ArrivalDate.Name, ReportEnum.Align.Left, "${DC_VesselTable.ArrivalDate.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_VesselTable.FixedFreight.Name, ReportEnum.Align.Right, "${DC_VesselTable.FixedFreight.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_VesselTable.VesselName.Name, ReportEnum.Align.Left, "${DC_VesselTable.VesselName.Name}", ReportEnum.Align.Left, 24);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_VesselTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_VesselTable.GetColumnList();
                DC_VesselRecord[] records = null;
                do
                {
                    records = DC_VesselTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_VesselRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_VesselTable.VesselId.Name}", record.Format(DC_VesselTable.VesselId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_VesselTable.ArrivalDate.Name}", record.Format(DC_VesselTable.ArrivalDate), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_VesselTable.FixedFreight.Name}", record.Format(DC_VesselTable.FixedFreight), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_VesselTable.VesselName.Name}", record.Format(DC_VesselTable.VesselName), ReportEnum.Align.Left, 100);

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
              public virtual void DC_VesselRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_VesselTableControl)(this.Page.FindControlRecursively("DC_VesselTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_VesselResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.ArrivalDateFromFilter.Text = "";
            
              this.ArrivalDateToFilter.Text = "";
            
              this.DC_VesselSearchArea.Text = "";
            
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
              public virtual void DC_VesselFilterButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for Button with Layout
              public virtual void DC_VesselSearchButton_Click(object sender, EventArgs args)
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

        private DC_VesselRecord[] _DataSource = null;
        public  DC_VesselRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.TextBox ArrivalDateFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ArrivalDateFromFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal ArrivalDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ArrivalDateLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ArrivalDateLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ArrivalDateLabel1");
            }
        }
        
        public System.Web.UI.WebControls.TextBox ArrivalDateToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ArrivalDateToFilter");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_VesselFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_VesselPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselPDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselPDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_VesselResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselResetButton");
            }
        }
        
        public System.Web.UI.WebControls.TextBox DC_VesselSearchArea {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselSearchArea");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_VesselSearchButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselSearchButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_VesselTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_VesselToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_VesselTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_VesselTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton FixedFreightLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FixedFreightLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton VesselIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton VesselNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselNameLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_VesselTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_VesselRecord rec = null;
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
            foreach (DC_VesselTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_VesselRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_VesselTableControlRow GetSelectedRecordControl()
        {
        DC_VesselTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_VesselTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_VesselTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_VesselRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_VesselTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_VesselTablePage.DC_VesselTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_VesselTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_VesselTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_VesselRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_VesselTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_VesselTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_VesselTableControlRow recControl = (DC_VesselTableControlRow)repItem.FindControl("DC_VesselTableControlRow");
                recList.Add(recControl);
            }

            return (DC_VesselTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_VesselTablePage.DC_VesselTableControlRow"));
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

  