
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// AddDC_CustomsBrokerOfficePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.AddDC_CustomsBrokerOfficePage
{
  

#region "Section 1: Place your customizations here."

    
//public class DC_ConsigneeTableControlRow : BaseDC_ConsigneeTableControlRow
//{
//      
//        // The BaseDC_ConsigneeTableControlRow implements code for a ROW within the
//        // the DC_ConsigneeTableControl table.  The BaseDC_ConsigneeTableControlRow implements the DataBind and SaveData methods.
//        // The loading of data is actually performed by the LoadData method in the base class of DC_ConsigneeTableControl.
//
//        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
//        // SaveData, GetUIData, and Validate methods.
//        
//
//}
//

  

//public class DC_ConsigneeTableControl : BaseDC_ConsigneeTableControl
//{
//        // The BaseDC_ConsigneeTableControl class implements the LoadData, DataBind, CreateWhereClause
//        // and other methods to load and display the data in a table control.
//
//        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
//        // The DC_ConsigneeTableControlRow class offers another place where you can customize
//        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.
//
//}
//

  
public class DC_CustomsBrokerOfficeRecordControl : BaseDC_CustomsBrokerOfficeRecordControl
{
      
        // The BaseDC_CustomsBrokerOfficeRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomsBrokerOfficeRecordControl control on the AddDC_CustomsBrokerOfficePage page.
// Do not modify this class. Instead override any method in DC_CustomsBrokerOfficeRecordControl.
public class BaseDC_CustomsBrokerOfficeRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomsBrokerOfficeRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
        }

        // To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomsBrokerOfficeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_CustomsBrokerOfficeRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_CustomsBrokerOfficeRecord[] recList = DC_CustomsBrokerOfficeTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = (DC_CustomsBrokerOfficeRecord)DC_CustomsBrokerOfficeRecord.Copy(recList[0], false);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
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

        
            if (this.DataSource.BorderCrossingSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.BorderCrossing);
                this.BorderCrossing.Text = formattedValue;
                        
            } else {  
                this.BorderCrossing.Text = DC_CustomsBrokerOfficeTable.BorderCrossing.Format(DC_CustomsBrokerOfficeTable.BorderCrossing.DefaultValue);
            }
                    
            if (this.DataSource.ClientSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Client);
                this.Client.Text = formattedValue;
                        
            } else {  
                this.Client.Text = DC_CustomsBrokerOfficeTable.Client.Format(DC_CustomsBrokerOfficeTable.Client.DefaultValue);
            }
                    
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Comments);
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomsBrokerOfficeTable.Comments.Format(DC_CustomsBrokerOfficeTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.ContactNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.ContactName);
                this.ContactName.Text = formattedValue;
                        
            } else {  
                this.ContactName.Text = DC_CustomsBrokerOfficeTable.ContactName.Format(DC_CustomsBrokerOfficeTable.ContactName.DefaultValue);
            }
                    
            if (this.DataSource.CustomsBrokerSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.CustomsBroker);
                this.CustomsBroker.Text = formattedValue;
                        
            } else {  
                this.CustomsBroker.Text = DC_CustomsBrokerOfficeTable.CustomsBroker.Format(DC_CustomsBrokerOfficeTable.CustomsBroker.DefaultValue);
            }
                    
            if (this.DataSource.CustomsBrokerOfficeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId);
                this.CustomsBrokerOfficeId.Text = formattedValue;
                        
            } else {  
                this.CustomsBrokerOfficeId.Text = DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.Format(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.DefaultValue);
            }
                    
            if (this.DataSource.DestinationsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Destinations);
                this.Destinations.Text = formattedValue;
                        
            } else {  
                this.Destinations.Text = DC_CustomsBrokerOfficeTable.Destinations.Format(DC_CustomsBrokerOfficeTable.Destinations.DefaultValue);
            }
                    
            if (this.DataSource.Email1Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email1);
                this.Email1.Text = formattedValue;
                        
            } else {  
                this.Email1.Text = DC_CustomsBrokerOfficeTable.Email1.Format(DC_CustomsBrokerOfficeTable.Email1.DefaultValue);
            }
                    
            if (this.DataSource.Email2Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email2);
                this.Email2.Text = formattedValue;
                        
            } else {  
                this.Email2.Text = DC_CustomsBrokerOfficeTable.Email2.Format(DC_CustomsBrokerOfficeTable.Email2.DefaultValue);
            }
                    
            if (this.DataSource.Email3Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email3);
                this.Email3.Text = formattedValue;
                        
            } else {  
                this.Email3.Text = DC_CustomsBrokerOfficeTable.Email3.Format(DC_CustomsBrokerOfficeTable.Email3.DefaultValue);
            }
                    
            if (this.DataSource.Email4Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email4);
                this.Email4.Text = formattedValue;
                        
            } else {  
                this.Email4.Text = DC_CustomsBrokerOfficeTable.Email4.Format(DC_CustomsBrokerOfficeTable.Email4.DefaultValue);
            }
                    
            if (this.DataSource.Email5Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email5);
                this.Email5.Text = formattedValue;
                        
            } else {  
                this.Email5.Text = DC_CustomsBrokerOfficeTable.Email5.Format(DC_CustomsBrokerOfficeTable.Email5.DefaultValue);
            }
                    
            if (this.DataSource.FaxSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Fax);
                this.Fax.Text = formattedValue;
                        
            } else {  
                this.Fax.Text = DC_CustomsBrokerOfficeTable.Fax.Format(DC_CustomsBrokerOfficeTable.Fax.DefaultValue);
            }
                    
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Phone);
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_CustomsBrokerOfficeTable.Phone.Format(DC_CustomsBrokerOfficeTable.Phone.DefaultValue);
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

        //  To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
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
        
            // 2. Validate the data.  Override in DC_CustomsBrokerOfficeRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomsBrokerOfficeRecordControl to set additional fields.
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

        //  To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
        public virtual void GetUIData()
        {
        
            bool clearDataSource = false;
            foreach(BaseColumn col in DC_CustomsBrokerOfficeRecord.TableUtils.TableDefinition.Columns){
                if ((col.ColumnType == BaseColumn.ColumnTypes.Unique_Identifier)){
                    clearDataSource = true;
                }
            }

            if (clearDataSource){
                this.DataSource = new DC_CustomsBrokerOfficeRecord();
            }
        
            this.DataSource.Parse(this.BorderCrossing.Text, DC_CustomsBrokerOfficeTable.BorderCrossing);
                          
            this.DataSource.Parse(this.Client.Text, DC_CustomsBrokerOfficeTable.Client);
                          
            this.DataSource.Parse(this.Comments.Text, DC_CustomsBrokerOfficeTable.Comments);
                          
            this.DataSource.Parse(this.ContactName.Text, DC_CustomsBrokerOfficeTable.ContactName);
                          
            this.DataSource.Parse(this.CustomsBroker.Text, DC_CustomsBrokerOfficeTable.CustomsBroker);
                          
            this.DataSource.Parse(this.CustomsBrokerOfficeId.Text, DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId);
                          
            this.DataSource.Parse(this.Destinations.Text, DC_CustomsBrokerOfficeTable.Destinations);
                          
            this.DataSource.Parse(this.Email1.Text, DC_CustomsBrokerOfficeTable.Email1);
                          
            this.DataSource.Parse(this.Email2.Text, DC_CustomsBrokerOfficeTable.Email2);
                          
            this.DataSource.Parse(this.Email3.Text, DC_CustomsBrokerOfficeTable.Email3);
                          
            this.DataSource.Parse(this.Email4.Text, DC_CustomsBrokerOfficeTable.Email4);
                          
            this.DataSource.Parse(this.Email5.Text, DC_CustomsBrokerOfficeTable.Email5);
                          
            this.DataSource.Parse(this.Fax.Text, DC_CustomsBrokerOfficeTable.Fax);
                          
            this.DataSource.Parse(this.Phone.Text, DC_CustomsBrokerOfficeTable.Phone);
                          
        }

        //  To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_CustomsBrokerOfficeTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
            string recId = this.Page.Request.QueryString["DC_CustomsBrokerOffice"];
            if (recId == null || recId.Length == 0) {
                return null;
            }
                       
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId).ToString());
            } else {
                
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;  
          
        }
        

        //  To customize, override this method in DC_CustomsBrokerOfficeRecordControl.
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
            DC_CustomsBrokerOfficeTable.DeleteRecord(pk);

          
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
                return (string)this.ViewState["BaseDC_CustomsBrokerOfficeRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomsBrokerOfficeRecordControl_Rec"] = value;
            }
        }
        
        private DC_CustomsBrokerOfficeRecord _DataSource;
        public DC_CustomsBrokerOfficeRecord DataSource {
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
           
        public System.Web.UI.WebControls.TextBox BorderCrossing {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossing");
            }
        }
        
        public System.Web.UI.WebControls.Literal BorderCrossingLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Client {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Client");
            }
        }
        
        public System.Web.UI.WebControls.Literal ClientLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ClientLabel");
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
           
        public System.Web.UI.WebControls.TextBox ContactName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactName");
            }
        }
        
        public System.Web.UI.WebControls.Literal ContactNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactNameLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomsBroker {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBroker");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBrokerLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomsBrokerOfficeId {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBrokerOfficeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomsBrokerOfficeDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Destinations {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Destinations");
            }
        }
        
        public System.Web.UI.WebControls.Literal DestinationsLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestinationsLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email1 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email1");
            }
        }
        
        public System.Web.UI.WebControls.Literal Email1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email1Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email2 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email2");
            }
        }
        
        public System.Web.UI.WebControls.Literal Email2Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email2Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email3 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email3");
            }
        }
        
        public System.Web.UI.WebControls.Literal Email3Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email3Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email4 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email4");
            }
        }
        
        public System.Web.UI.WebControls.Literal Email4Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email4Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email5 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email5");
            }
        }
        
        public System.Web.UI.WebControls.Literal Email5Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email5Label");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Fax {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Fax");
            }
        }
        
        public System.Web.UI.WebControls.Literal FaxLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FaxLabel");
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
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_CustomsBrokerOfficeRecord rec = null;
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

        public DC_CustomsBrokerOfficeRecord GetRecord()
        {
        
            if (this.DataSource != null) {
              return this.DataSource;
            }
            
            return new DC_CustomsBrokerOfficeRecord();
          
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

  