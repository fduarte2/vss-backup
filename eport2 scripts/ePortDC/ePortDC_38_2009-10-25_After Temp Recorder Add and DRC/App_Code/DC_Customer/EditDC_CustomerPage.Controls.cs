
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// EditDC_CustomerPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.EditDC_CustomerPage
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

  
//public class DC_OrderTableControlRow : BaseDC_OrderTableControlRow
//{
//      
//        // The BaseDC_OrderTableControlRow implements code for a ROW within the
//        // the DC_OrderTableControl table.  The BaseDC_OrderTableControlRow implements the DataBind and SaveData methods.
//        // The loading of data is actually performed by the LoadData method in the base class of DC_OrderTableControl.
//
//        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
//        // SaveData, GetUIData, and Validate methods.
//        
//
//}
//

  

//public class DC_OrderTableControl : BaseDC_OrderTableControl
//{
//        // The BaseDC_OrderTableControl class implements the LoadData, DataBind, CreateWhereClause
//        // and other methods to load and display the data in a table control.
//
//        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
//        // The DC_OrderTableControlRow class offers another place where you can customize
//        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.
//
//}
//

  
public class DC_CustomerRecordControl : BaseDC_CustomerRecordControl
{
      
        // The BaseDC_CustomerRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomerRecordControl control on the EditDC_CustomerPage page.
// Do not modify this class. Instead override any method in DC_CustomerRecordControl.
public class BaseDC_CustomerRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomerRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomerRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.Origin.SelectedIndexChanged += new EventHandler(Origin_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_CustomerRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_CustomerRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomerTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_CustomerRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_CustomerRecord[] recList = DC_CustomerTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_CustomerTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomerRecordControl.
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

        
            if (this.DataSource.AddressSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Address);
                this.Address.Text = formattedValue;
                        
            } else {  
                this.Address.Text = DC_CustomerTable.Address.Format(DC_CustomerTable.Address.DefaultValue);
            }
                    
            if (this.DataSource.CitySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.City);
                this.City.Text = formattedValue;
                        
            } else {  
                this.City.Text = DC_CustomerTable.City.Format(DC_CustomerTable.City.DefaultValue);
            }
                    
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Comments);
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomerTable.Comments.Format(DC_CustomerTable.Comments.DefaultValue);
            }
                    
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerId);
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_CustomerTable.CustomerId.Format(DC_CustomerTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.CustomerNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerName);
                this.CustomerName.Text = formattedValue;
                        
            } else {  
                this.CustomerName.Text = DC_CustomerTable.CustomerName.Format(DC_CustomerTable.CustomerName.DefaultValue);
            }
                    
            if (this.DataSource.CustomerShortNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerShortName);
                this.CustomerShortName.Text = formattedValue;
                        
            } else {  
                this.CustomerShortName.Text = DC_CustomerTable.CustomerShortName.Format(DC_CustomerTable.CustomerShortName.DefaultValue);
            }
                    
            if (this.DataSource.DestCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.DestCode);
                this.DestCode.Text = formattedValue;
                        
            } else {  
                this.DestCode.Text = DC_CustomerTable.DestCode.Format(DC_CustomerTable.DestCode.DefaultValue);
            }
                    
            if (this.DataSource.NeedPARSSpecified) {
                this.NeedPARS.Checked = this.DataSource.NeedPARS;
            } else {
                if (!this.DataSource.IsCreated) {
                    this.NeedPARS.Checked = DC_CustomerTable.NeedPARS.ParseValue(DC_CustomerTable.NeedPARS.DefaultValue).ToBoolean();
                }
            }
                    
            if (this.DataSource.OriginSpecified) {
                this.PopulateOriginDropDownList(this.DataSource.Origin, 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateOriginDropDownList(DC_CustomerTable.Origin.DefaultValue, 100);
                } else {
                this.PopulateOriginDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Phone);
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_CustomerTable.Phone.Format(DC_CustomerTable.Phone.DefaultValue);
            }
                    
            if (this.DataSource.PhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.PhoneMobile);
                this.PhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PhoneMobile.Text = DC_CustomerTable.PhoneMobile.Format(DC_CustomerTable.PhoneMobile.DefaultValue);
            }
                    
            if (this.DataSource.PostalCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.PostalCode);
                this.PostalCode.Text = formattedValue;
                        
            } else {  
                this.PostalCode.Text = DC_CustomerTable.PostalCode.Format(DC_CustomerTable.PostalCode.DefaultValue);
            }
                    
            if (this.DataSource.StateProvinceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.StateProvince);
                this.StateProvince.Text = formattedValue;
                        
            } else {  
                this.StateProvince.Text = DC_CustomerTable.StateProvince.Format(DC_CustomerTable.StateProvince.DefaultValue);
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

        //  To customize, override this method in DC_CustomerRecordControl.
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
        
            // 2. Validate the data.  Override in DC_CustomerRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomerRecordControl to set additional fields.
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

        //  To customize, override this method in DC_CustomerRecordControl.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.Address.Text, DC_CustomerTable.Address);
                          
            this.DataSource.Parse(this.City.Text, DC_CustomerTable.City);
                          
            this.DataSource.Parse(this.Comments.Text, DC_CustomerTable.Comments);
                          
            this.DataSource.Parse(this.CustomerId.Text, DC_CustomerTable.CustomerId);
                          
            this.DataSource.Parse(this.CustomerName.Text, DC_CustomerTable.CustomerName);
                          
            this.DataSource.Parse(this.CustomerShortName.Text, DC_CustomerTable.CustomerShortName);
                          
            this.DataSource.Parse(this.DestCode.Text, DC_CustomerTable.DestCode);
                          
            this.DataSource.NeedPARS = this.NeedPARS.Checked;
                    
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.Origin), DC_CustomerTable.Origin);
                  
            this.DataSource.Parse(this.Phone.Text, DC_CustomerTable.Phone);
                          
            this.DataSource.Parse(this.PhoneMobile.Text, DC_CustomerTable.PhoneMobile);
                          
            this.DataSource.Parse(this.PostalCode.Text, DC_CustomerTable.PostalCode);
                          
            this.DataSource.Parse(this.StateProvince.Text, DC_CustomerTable.StateProvince);
                          
        }

        //  To customize, override this method in DC_CustomerRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_CustomerTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_Customer"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_Customer"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_CustomerTable.CustomerId).ToString());
            } else {
                
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_CustomerRecordControl.
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
            DC_CustomerTable.DeleteRecord(pk);

          
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
        
        public virtual WhereClause CreateWhereClause_OriginDropDownList() {
            return new WhereClause();
        }
                
        // Fill the Origin list.
        protected virtual void PopulateOriginDropDownList
                (string selectedValue, int maxItems) {
                  
            this.Origin.Items.Clear();
                      
            this.Origin.Items.Add(new ListItem("MOROCCO", "MOROCCO"));
            this.Origin.Items.Add(new ListItem("WILMINGTON", "WILMINGTON"));
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.Origin, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.Origin, DC_CustomerTable.Origin.Format(selectedValue))) {
                string fvalue = DC_CustomerTable.Origin.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.Origin.Items.Insert(0, item);
            }

                  
            this.Origin.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              protected virtual void Origin_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.Origin);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.Origin, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.Origin, DC_CustomerTable.Origin.Format(selectedValue))) {
              string fvalue = DC_CustomerTable.Origin.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.Origin.Items.Insert(0, item);
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
                return (string)this.ViewState["BaseDC_CustomerRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomerRecordControl_Rec"] = value;
            }
        }
        
        private DC_CustomerRecord _DataSource;
        public DC_CustomerRecord DataSource {
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
           
        public System.Web.UI.WebControls.TextBox Address {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Address");
            }
        }
        
        public System.Web.UI.WebControls.Literal AddressLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "AddressLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox City {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "City");
            }
        }
        
        public System.Web.UI.WebControls.Literal CityLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CityLabel");
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
           
        public System.Web.UI.WebControls.TextBox CustomerId {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomerName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerName");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerNameLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomerShortName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortName");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerShortNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomerDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox DestCode {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal DestCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.CheckBox NeedPARS {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "NeedPARS");
            }
        }
        
        public System.Web.UI.WebControls.Literal NeedPARSLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "NeedPARSLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList Origin {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Origin");
            }
        }
        
        public System.Web.UI.WebControls.Literal OriginLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OriginLabel");
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
           
        public System.Web.UI.WebControls.TextBox PostalCode {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal PostalCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox StateProvince {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvince");
            }
        }
        
        public System.Web.UI.WebControls.Literal StateProvinceLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvinceLabel");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_CustomerRecord rec = null;
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

        public DC_CustomerRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_CustomerTable.GetRecord(this.RecordUniqueId, true);
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

  