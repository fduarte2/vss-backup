
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CommodityTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CommodityTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CommodityTableControlRow : BaseDC_CommodityTableControlRow
{
      
        // The BaseDC_CommodityTableControlRow implements code for a ROW within the
        // the DC_CommodityTableControl table.  The BaseDC_CommodityTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_CommodityTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_CommodityTableControl : BaseDC_CommodityTableControl
{
        // The BaseDC_CommodityTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_CommodityTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CommodityTableControlRow control on the ShowDC_CommodityTablePage page.
// Do not modify this class. Instead override any method in DC_CommodityTableControlRow.
public class BaseDC_CommodityTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CommodityTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CommodityTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_CommodityRecordRowCopyButton.Click += new ImageClickEventHandler(DC_CommodityRecordRowCopyButton_Click);
              this.DC_CommodityRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_CommodityRecordRowDeleteButton_Click);
              this.DC_CommodityRecordRowEditButton.Click += new ImageClickEventHandler(DC_CommodityRecordRowEditButton_Click);
              this.DC_CommodityRecordRowViewButton.Click += new ImageClickEventHandler(DC_CommodityRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_CommodityTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_CommodityRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_CommodityTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CommodityTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_CommodityTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_CommodityRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CommodityTableControlRow.
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
                      
                string formattedValue = this.DataSource.Format(DC_CommodityTable.CommodityCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CommodityCode.Text = formattedValue;
                        
            } else {  
                this.CommodityCode.Text = DC_CommodityTable.CommodityCode.Format(DC_CommodityTable.CommodityCode.DefaultValue);
            }
                    
            if (this.CommodityCode.Text == null ||
                this.CommodityCode.Text.Trim().Length == 0) {
                this.CommodityCode.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommodityNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommodityTable.CommodityName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CommodityName.Text = formattedValue;
                        
            } else {  
                this.CommodityName.Text = DC_CommodityTable.CommodityName.Format(DC_CommodityTable.CommodityName.DefaultValue);
            }
                    
            if (this.CommodityName.Text == null ||
                this.CommodityName.Text.Trim().Length == 0) {
                this.CommodityName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.HarmonizedSystemTariffSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommodityTable.HarmonizedSystemTariff);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.HarmonizedSystemTariff.Text = formattedValue;
                        
            } else {  
                this.HarmonizedSystemTariff.Text = DC_CommodityTable.HarmonizedSystemTariff.Format(DC_CommodityTable.HarmonizedSystemTariff.DefaultValue);
            }
                    
            if (this.HarmonizedSystemTariff.Text == null ||
                this.HarmonizedSystemTariff.Text.Trim().Length == 0) {
                this.HarmonizedSystemTariff.Text = "&nbsp;";
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

        //  To customize, override this method in DC_CommodityTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_CommodityTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CommodityTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_CommodityTableControl)MiscUtils.GetParentControlObject(this, "DC_CommodityTableControl")).DataChanged = true;
                ((DC_CommodityTableControl)MiscUtils.GetParentControlObject(this, "DC_CommodityTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_CommodityTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_CommodityTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_CommodityTableControlRow.
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
            DC_CommodityTable.DeleteRecord(pk);

          
            ((DC_CommodityTableControl)MiscUtils.GetParentControlObject(this, "DC_CommodityTableControl")).DataChanged = true;
            ((DC_CommodityTableControl)MiscUtils.GetParentControlObject(this, "DC_CommodityTableControl")).ResetData = true;
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
              public virtual void DC_CommodityRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/AddDC_CommodityPage.aspx?DC_Commodity={PK}";
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
              public virtual void DC_CommodityRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommodityRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/EditDC_CommodityPage.aspx?DC_Commodity={PK}";
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
              public virtual void DC_CommodityRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/ShowDC_CommodityPage.aspx?DC_Commodity={PK}";
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
                return (string)this.ViewState["BaseDC_CommodityTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_CommodityTableControlRow_Rec"] = value;
            }
        }
        
        private DC_CommodityRecord _DataSource;
        public DC_CommodityRecord DataSource {
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
           
        public System.Web.UI.WebControls.Literal CommodityName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityName");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CommodityRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal HarmonizedSystemTariff {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "HarmonizedSystemTariff");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_CommodityRecord rec = null;
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

        public DC_CommodityRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_CommodityTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_CommodityTableControl control on the ShowDC_CommodityTablePage page.
// Do not modify this class. Instead override any method in DC_CommodityTableControl.
public class BaseDC_CommodityTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_CommodityTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_CommodityPagination.FirstPage.Click += new ImageClickEventHandler(DC_CommodityPagination_FirstPage_Click);
              this.DC_CommodityPagination.LastPage.Click += new ImageClickEventHandler(DC_CommodityPagination_LastPage_Click);
              this.DC_CommodityPagination.NextPage.Click += new ImageClickEventHandler(DC_CommodityPagination_NextPage_Click);
              this.DC_CommodityPagination.PageSizeButton.Click += new EventHandler(DC_CommodityPagination_PageSizeButton_Click);
            
              this.DC_CommodityPagination.PreviousPage.Click += new ImageClickEventHandler(DC_CommodityPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.CommodityCodeLabel1.Click += new EventHandler(CommodityCodeLabel1_Click);
            
              this.CommodityNameLabel1.Click += new EventHandler(CommodityNameLabel1_Click);
            
              this.HarmonizedSystemTariffLabel.Click += new EventHandler(HarmonizedSystemTariffLabel_Click);
            

            // Setup the button events.
        
              this.DC_CommodityCopyButton.Click += new ImageClickEventHandler(DC_CommodityCopyButton_Click);
              this.DC_CommodityDeleteButton.Click += new ImageClickEventHandler(DC_CommodityDeleteButton_Click);
              this.DC_CommodityEditButton.Click += new ImageClickEventHandler(DC_CommodityEditButton_Click);
              this.DC_CommodityExportButton.Click += new ImageClickEventHandler(DC_CommodityExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommodityExportButton"), MiscUtils.GetParentControlObject(this,"DC_CommodityTableControlUpdatePanel"));
                    
              this.DC_CommodityExportExcelButton.Click += new ImageClickEventHandler(DC_CommodityExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommodityExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_CommodityTableControlUpdatePanel"));
                    
              this.DC_CommodityNewButton.Click += new ImageClickEventHandler(DC_CommodityNewButton_Click);
              this.DC_CommodityPDFButton.Click += new ImageClickEventHandler(DC_CommodityPDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CommodityPDFButton"), MiscUtils.GetParentControlObject(this,"DC_CommodityTableControlUpdatePanel"));
                    
              this.DC_CommodityRefreshButton.Click += new ImageClickEventHandler(DC_CommodityRefreshButton_Click);
              this.DC_CommodityResetButton.Click += new ImageClickEventHandler(DC_CommodityResetButton_Click);
              this.DC_CommodityFilterButton.Button.Click += new EventHandler(DC_CommodityFilterButton_Click);

            // Setup the filter and search events.
        
            this.CommodityNameFilter.SelectedIndexChanged += new EventHandler(CommodityNameFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.CommodityCodeFromFilter)) {
                
                this.CommodityCodeFromFilter.Text = this.GetFromSession(this.CommodityCodeFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CommodityCodeToFilter)) {
                
                this.CommodityCodeToFilter.Text = this.GetFromSession(this.CommodityCodeToFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CommodityNameFilter)) {
                this.CommodityNameFilter.Items.Add(new ListItem(this.GetFromSession(this.CommodityNameFilter), this.GetFromSession(this.CommodityNameFilter)));
                this.CommodityNameFilter.SelectedValue = this.GetFromSession(this.CommodityNameFilter);
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
                this.DC_CommodityDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_CommodityRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CommodityRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_CommodityTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_CommodityRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CommodityRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_CommodityTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_CommodityRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_CommodityRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_CommodityTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCommodityNameFilter(MiscUtils.GetSelectedValue(this.CommodityNameFilter, this.GetFromSession(this.CommodityNameFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CommodityTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_CommodityTableControlRow recControl = (DC_CommodityTableControlRow)(repItem.FindControl("DC_CommodityTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_CommodityTableControl pagination.
        
            this.DC_CommodityPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_CommodityPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CommodityPagination.LastPage.Enabled = false;
            }
          
            this.DC_CommodityPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CommodityPagination.NextPage.Enabled = false;
            }
          
            this.DC_CommodityPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_CommodityPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_CommodityPagination.CurrentPage.Text = "0";
            }
            this.DC_CommodityPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_CommodityTotalItems.Text = this.TotalRecords.ToString();
            this.DC_CommodityPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_CommodityPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_CommodityTableControlRow recCtl in this.GetRecordControls())
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
            DC_CommodityTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.CommodityCodeFromFilter)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CommodityCodeFromFilter, this.GetFromSession(this.CommodityCodeFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CommodityCodeToFilter)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CommodityCodeToFilter, this.GetFromSession(this.CommodityCodeToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CommodityNameFilter)) {
                wc.iAND(DC_CommodityTable.CommodityName, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityNameFilter, this.GetFromSession(this.CommodityNameFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_CommodityTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String CommodityCodeFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CommodityCodeFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CommodityCodeFromFilterSelectedValue)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, CommodityCodeFromFilterSelectedValue, false, false);
            }
                      
            String CommodityCodeToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CommodityCodeToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CommodityCodeToFilterSelectedValue)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, CommodityCodeToFilterSelectedValue, false, false);
            }
                      
            String CommodityNameFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CommodityNameFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CommodityNameFilterSelectedValue)) {
                wc.iAND(DC_CommodityTable.CommodityName, BaseFilter.ComparisonOperator.EqualsTo, CommodityNameFilterSelectedValue, false, false);
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
        
            if (this.DC_CommodityPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_CommodityPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CommodityTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_CommodityTableControlRow recControl = (DC_CommodityTableControlRow)(repItem.FindControl("DC_CommodityTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_CommodityRecord rec = new DC_CommodityRecord();
        
                        if (recControl.CommodityCode.Text != "") {
                            rec.Parse(recControl.CommodityCode.Text, DC_CommodityTable.CommodityCode);
                        }
                        if (recControl.CommodityName.Text != "") {
                            rec.Parse(recControl.CommodityName.Text, DC_CommodityTable.CommodityName);
                        }
                        if (recControl.HarmonizedSystemTariff.Text != "") {
                            rec.Parse(recControl.HarmonizedSystemTariff.Text, DC_CommodityTable.HarmonizedSystemTariff);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_CommodityRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_CommodityRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_CommodityRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_CommodityTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_CommodityTableControlRow rec)            
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
        
        // Get the filters' data for CommodityNameFilter.
        protected virtual void PopulateCommodityNameFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommodityTable.CommodityName, OrderByItem.OrderDir.Asc);

            string[] list = DC_CommodityTable.GetValues(DC_CommodityTable.CommodityName, wc, orderBy, maxItems);
            
            this.CommodityNameFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CommodityTable.CommodityName.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.CommodityNameFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CommodityNameFilter, selectedValue);

            // Add the All item.
            this.CommodityNameFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CommodityNameFilter.
        public virtual WhereClause CreateWhereClause_CommodityNameFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFromFilter)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CommodityCodeFromFilter, this.GetFromSession(this.CommodityCodeFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CommodityCodeToFilter)) {
                wc.iAND(DC_CommodityTable.CommodityCode, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CommodityCodeToFilter, this.GetFromSession(this.CommodityCodeToFilter)), false, false);
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
        
            this.SaveToSession(this.CommodityCodeFromFilter, this.CommodityCodeFromFilter.Text);
            this.SaveToSession(this.CommodityCodeToFilter, this.CommodityCodeToFilter.Text);
            this.SaveToSession(this.CommodityNameFilter, this.CommodityNameFilter.SelectedValue);
            
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
          
            this.SaveToSession("CommodityCodeFromFilter_Ajax", this.CommodityCodeFromFilter.Text);
            this.SaveToSession("CommodityCodeToFilter_Ajax", this.CommodityCodeToFilter.Text);
            this.SaveToSession("CommodityNameFilter_Ajax", this.CommodityNameFilter.SelectedValue);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.CommodityCodeFromFilter);
            this.RemoveFromSession(this.CommodityCodeToFilter);
            this.RemoveFromSession(this.CommodityNameFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_CommodityTableControl_OrderBy"];
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
                this.ViewState["DC_CommodityTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_CommodityPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommodityPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommodityPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommodityPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_CommodityPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_CommodityPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CommodityPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void CommodityCodeLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommodityTable.CommodityCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommodityTable.CommodityCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommodityNameLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommodityTable.CommodityName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommodityTable.CommodityName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void HarmonizedSystemTariffLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CommodityTable.HarmonizedSystemTariff);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CommodityTable.HarmonizedSystemTariff, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_CommodityCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/AddDC_CommodityPage.aspx?DC_Commodity={PK}";
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
              public virtual void DC_CommodityDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CommodityEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/EditDC_CommodityPage.aspx?DC_Commodity={PK}";
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
              public virtual void DC_CommodityExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_CommodityTable.CommodityCode,
             DC_CommodityTable.CommodityName,
             DC_CommodityTable.HarmonizedSystemTariff,
             null};
            ExportData rep = new ExportData(DC_CommodityTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_CommodityExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_CommodityTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_CommodityTable.CommodityCode, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CommodityTable.CommodityName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CommodityTable.HarmonizedSystemTariff, "Default"));

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
              public virtual void DC_CommodityNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Commodity/AddDC_CommodityPage.aspx";
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
              public virtual void DC_CommodityPDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_CommodityTablePage.DC_CommodityPDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Commodity";
                // If ShowDC_CommodityTablePage.DC_CommodityPDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_CommodityTable.CommodityCode.Name, ReportEnum.Align.Right, "${DC_CommodityTable.CommodityCode.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CommodityTable.CommodityName.Name, ReportEnum.Align.Left, "${DC_CommodityTable.CommodityName.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_CommodityTable.HarmonizedSystemTariff.Name, ReportEnum.Align.Left, "${DC_CommodityTable.HarmonizedSystemTariff.Name}", ReportEnum.Align.Left, 15);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_CommodityTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_CommodityTable.GetColumnList();
                DC_CommodityRecord[] records = null;
                do
                {
                    records = DC_CommodityTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_CommodityRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_CommodityTable.CommodityCode.Name}", record.Format(DC_CommodityTable.CommodityCode), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CommodityTable.CommodityName.Name}", record.Format(DC_CommodityTable.CommodityName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CommodityTable.HarmonizedSystemTariff.Name}", record.Format(DC_CommodityTable.HarmonizedSystemTariff), ReportEnum.Align.Left, 100);

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
              public virtual void DC_CommodityRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_CommodityTableControl)(this.Page.FindControlRecursively("DC_CommodityTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CommodityResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CommodityNameFilter.ClearSelection();
            
              this.CommodityCodeFromFilter.Text = "";
            
              this.CommodityCodeToFilter.Text = "";
            
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
              public virtual void DC_CommodityFilterButton_Click(object sender, EventArgs args)
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
        protected virtual void CommodityNameFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_CommodityRecord[] _DataSource = null;
        public  DC_CommodityRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.TextBox CommodityCodeFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeFromFilter");
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
        
        public System.Web.UI.WebControls.TextBox CommodityCodeToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeToFilter");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CommodityNameFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityNameFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CommodityNameLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityNameLabel1");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_CommodityFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_CommodityPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityPDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityPDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CommodityResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CommodityTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CommodityToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_CommodityTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommodityTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton HarmonizedSystemTariffLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "HarmonizedSystemTariffLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_CommodityTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_CommodityRecord rec = null;
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
            foreach (DC_CommodityTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CommodityRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_CommodityTableControlRow GetSelectedRecordControl()
        {
        DC_CommodityTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_CommodityTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_CommodityTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CommodityRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_CommodityTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CommodityTablePage.DC_CommodityTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_CommodityTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_CommodityTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_CommodityRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_CommodityTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_CommodityTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_CommodityTableControlRow recControl = (DC_CommodityTableControlRow)repItem.FindControl("DC_CommodityTableControlRow");
                recList.Add(recControl);
            }

            return (DC_CommodityTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CommodityTablePage.DC_CommodityTableControlRow"));
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

  