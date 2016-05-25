
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// AddDC_CommoditySizePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.AddDC_CommoditySizePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CommoditySizeRecordControl : BaseDC_CommoditySizeRecordControl
{
      
        // The BaseDC_CommoditySizeRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CommoditySizeRecordControl control on the AddDC_CommoditySizePage page.
// Do not modify this class. Instead override any method in DC_CommoditySizeRecordControl.
public class BaseDC_CommoditySizeRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CommoditySizeRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CommoditySizeRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
        }

        // To customize, override this method in DC_CommoditySizeRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_CommoditySizeRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CommoditySizeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_CommoditySizeRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_CommoditySizeRecord[] recList = DC_CommoditySizeTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = (DC_CommoditySizeRecord)DC_CommoditySizeRecord.Copy(recList[0], false);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CommoditySizeRecordControl.
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
                this.Descr.Text = formattedValue;
                        
            } else {  
                this.Descr.Text = DC_CommoditySizeTable.Descr.Format(DC_CommoditySizeTable.Descr.DefaultValue);
            }
                    
            if (this.DataSource.PriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.Price);
                this.Price.Text = formattedValue;
                        
            } else {  
                this.Price.Text = DC_CommoditySizeTable.Price.Format(DC_CommoditySizeTable.Price.DefaultValue);
            }
                    
            if (this.DataSource.SizeHighSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeHigh);
                this.SizeHigh.Text = formattedValue;
                        
            } else {  
                this.SizeHigh.Text = DC_CommoditySizeTable.SizeHigh.Format(DC_CommoditySizeTable.SizeHigh.DefaultValue);
            }
                    
            if (this.DataSource.SizeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeId);
                this.SizeId.Text = formattedValue;
                        
            } else {  
                this.SizeId.Text = DC_CommoditySizeTable.SizeId.Format(DC_CommoditySizeTable.SizeId.DefaultValue);
            }
                    
            if (this.DataSource.SizeLowSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.SizeLow);
                this.SizeLow.Text = formattedValue;
                        
            } else {  
                this.SizeLow.Text = DC_CommoditySizeTable.SizeLow.Format(DC_CommoditySizeTable.SizeLow.DefaultValue);
            }
                    
            if (this.DataSource.WeightKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CommoditySizeTable.WeightKG);
                this.WeightKG.Text = formattedValue;
                        
            } else {  
                this.WeightKG.Text = DC_CommoditySizeTable.WeightKG.Format(DC_CommoditySizeTable.WeightKG.DefaultValue);
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

        //  To customize, override this method in DC_CommoditySizeRecordControl.
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
        
            // 2. Validate the data.  Override in DC_CommoditySizeRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CommoditySizeRecordControl to set additional fields.
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

        //  To customize, override this method in DC_CommoditySizeRecordControl.
        public virtual void GetUIData()
        {
        
            bool clearDataSource = false;
            foreach(BaseColumn col in DC_CommoditySizeRecord.TableUtils.TableDefinition.Columns){
                if ((col.ColumnType == BaseColumn.ColumnTypes.Unique_Identifier)){
                    clearDataSource = true;
                }
            }

            if (clearDataSource){
                this.DataSource = new DC_CommoditySizeRecord();
            }
        
            this.DataSource.Parse(this.Descr.Text, DC_CommoditySizeTable.Descr);
                          
            this.DataSource.Parse(this.Price.Text, DC_CommoditySizeTable.Price);
                          
            this.DataSource.Parse(this.SizeHigh.Text, DC_CommoditySizeTable.SizeHigh);
                          
            this.DataSource.Parse(this.SizeId.Text, DC_CommoditySizeTable.SizeId);
                          
            this.DataSource.Parse(this.SizeLow.Text, DC_CommoditySizeTable.SizeLow);
                          
            this.DataSource.Parse(this.WeightKG.Text, DC_CommoditySizeTable.WeightKG);
                          
        }

        //  To customize, override this method in DC_CommoditySizeRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_CommoditySizeTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
            string recId = this.Page.Request.QueryString["DC_CommoditySize"];
            if (recId == null || recId.Length == 0) {
                return null;
            }
                       
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CommoditySizeTable.SizeId).ToString());
            } else {
                
                wc.iAND(DC_CommoditySizeTable.SizeId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;  
          
        }
        

        //  To customize, override this method in DC_CommoditySizeRecordControl.
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
                return (string)this.ViewState["BaseDC_CommoditySizeRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_CommoditySizeRecordControl_Rec"] = value;
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
        
        public System.Web.UI.WebControls.Literal DC_CommoditySizeDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CommoditySizeDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Descr {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Descr");
            }
        }
        
        public System.Web.UI.WebControls.Literal DescrLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DescrLabel");
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
           
        public System.Web.UI.WebControls.TextBox SizeHigh {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHigh");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeHighLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeHighLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SizeId {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox SizeLow {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeLow");
            }
        }
        
        public System.Web.UI.WebControls.Literal SizeLowLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SizeLowLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox WeightKG {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "WeightKG");
            }
        }
        
        public System.Web.UI.WebControls.Literal WeightKGLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "WeightKGLabel");
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
            
            return new DC_CommoditySizeRecord();
          
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

  