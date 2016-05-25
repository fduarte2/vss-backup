
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// EditDC_UserPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.EditDC_UserPage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_UserRecordControl : BaseDC_UserRecordControl
{
      
        // The BaseDC_UserRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_UserRecordControl control on the EditDC_UserPage page.
// Do not modify this class. Instead override any method in DC_UserRecordControl.
public class BaseDC_UserRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_UserRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_UserRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.Role.SelectedIndexChanged += new EventHandler(Role_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_UserRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_UserRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_UserTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_UserRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_UserRecord[] recList = DC_UserTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_UserTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_UserRecordControl.
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

        
            if (this.DataSource.EmailSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.Email);
                this.Email.Text = formattedValue;
                        
            } else {  
                this.Email.Text = DC_UserTable.Email.Format(DC_UserTable.Email.DefaultValue);
            }
                    
            if (this.DataSource.NameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.Name);
                this.Name.Text = formattedValue;
                        
            } else {  
                this.Name.Text = DC_UserTable.Name.Format(DC_UserTable.Name.DefaultValue);
            }
                    
            if (this.DataSource.PasswordSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.Password);
                this.Password.Text = formattedValue;
                        
            } else {  
                this.Password.Text = DC_UserTable.Password.Format(DC_UserTable.Password.DefaultValue);
            }
                    
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.Phone);
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_UserTable.Phone.Format(DC_UserTable.Phone.DefaultValue);
            }
                    
            if (this.DataSource.PhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.PhoneMobile);
                this.PhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PhoneMobile.Text = DC_UserTable.PhoneMobile.Format(DC_UserTable.PhoneMobile.DefaultValue);
            }
                    
            if (this.DataSource.PrinterSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.Printer);
                this.Printer.Text = formattedValue;
                        
            } else {  
                this.Printer.Text = DC_UserTable.Printer.Format(DC_UserTable.Printer.DefaultValue);
            }
                    
            if (this.DataSource.RoleSpecified) {
                this.PopulateRoleDropDownList(this.DataSource.Role, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateRoleDropDownList(DC_UserTable.Role.DefaultValue, 100);
                } else {
                this.PopulateRoleDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.UserId0Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_UserTable.UserId0);
                this.UserId1.Text = formattedValue;
                        
            } else {  
                this.UserId1.Text = DC_UserTable.UserId0.Format(DC_UserTable.UserId0.DefaultValue);
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

        //  To customize, override this method in DC_UserRecordControl.
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
        
            // 2. Validate the data.  Override in DC_UserRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_UserRecordControl to set additional fields.
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

        //  To customize, override this method in DC_UserRecordControl.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Email.Text, DC_UserTable.Email);
                          
            this.DataSource.Parse(this.Name.Text, DC_UserTable.Name);
                          
            if (!(this.Password.TextMode == TextBoxMode.Password) || !(this.Password.Text.Trim() == "")) {
                        
                string PasswordformattedValue = this.DataSource.Format(DC_UserTable.Password);
                          
                if (this.Password.Text.Trim() != PasswordformattedValue) {
                        
                    this.DataSource.Parse(this.Password.Text, DC_UserTable.Password);
                          
                }
            }
                      
            this.DataSource.Parse(this.Phone.Text, DC_UserTable.Phone);
                          
            this.DataSource.Parse(this.PhoneMobile.Text, DC_UserTable.PhoneMobile);
                          
            this.DataSource.Parse(this.Printer.Text, DC_UserTable.Printer);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.Role), DC_UserTable.Role);
                  
            this.DataSource.Parse(this.UserId1.Text, DC_UserTable.UserId0);
                          
        }

        //  To customize, override this method in DC_UserRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_UserTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_User"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_User"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_UserTable.UserId0, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_UserTable.UserId0).ToString());
            } else {
                
                wc.iAND(DC_UserTable.UserId0, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_UserRecordControl.
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
            DC_UserTable.DeleteRecord(pk);

          
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
        
        public virtual WhereClause CreateWhereClause_RoleDropDownList() {
            return new WhereClause();
        }
                
        // Fill the Role list.
        protected virtual void PopulateRoleDropDownList
                (string selectedValue, int maxItems) {
                  
            this.Role.Items.Clear();
                      
            this.Role.Items.Add(new ListItem("ADMIN", "ADMIN"));
            this.Role.Items.Add(new ListItem("PORTADMIN", "PORTADMIN"));
            this.Role.Items.Add(new ListItem("PORTUSER", "PORTUSER"));
            this.Role.Items.Add(new ListItem("DC", "DC"));
            this.Role.Items.Add(new ListItem("EX", "EX"));
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.Role, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.Role, DC_UserTable.Role.Format(selectedValue))) {
                string fvalue = DC_UserTable.Role.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.Role.Items.Insert(0, item);
            }

                  
            this.Role.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              protected virtual void Role_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.Role);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.Role, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.Role, DC_UserTable.Role.Format(selectedValue))) {
              string fvalue = DC_UserTable.Role.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.Role.Items.Insert(0, item);
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
                return (string)this.ViewState["BaseDC_UserRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_UserRecordControl_Rec"] = value;
            }
        }
        
        private DC_UserRecord _DataSource;
        public DC_UserRecord DataSource {
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
        
        public System.Web.UI.WebControls.Literal DC_UserDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_UserDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Email {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email");
            }
        }
        
        public System.Web.UI.WebControls.Literal EmailLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EmailLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Name {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Name");
            }
        }
        
        public System.Web.UI.WebControls.Literal NameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "NameLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox Password {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Password");
            }
        }
        
        public System.Web.UI.WebControls.Literal PasswordLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PasswordLabel");
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
           
        public System.Web.UI.WebControls.TextBox Printer {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Printer");
            }
        }
        
        public System.Web.UI.WebControls.Literal PrinterLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PrinterLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList Role {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Role");
            }
        }
        
        public System.Web.UI.WebControls.Literal RoleLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "RoleLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox UserId1 {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "UserId1");
            }
        }
        
        public System.Web.UI.WebControls.Literal UserIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "UserIdLabel");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_UserRecord rec = null;
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

        public DC_UserRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_UserTable.GetRecord(this.RecordUniqueId, true);
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

  