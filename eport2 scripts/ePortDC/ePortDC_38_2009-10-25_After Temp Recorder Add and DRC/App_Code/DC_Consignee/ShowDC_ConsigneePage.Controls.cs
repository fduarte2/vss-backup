
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_ConsigneePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_ConsigneePage
{
  

#region "Section 1: Place your customizations here."

    
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

  
public class DC_ConsigneeRecordControl : BaseDC_ConsigneeRecordControl
{
      
        // The BaseDC_ConsigneeRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_ConsigneeRecordControl control on the ShowDC_ConsigneePage page.
// Do not modify this class. Instead override any method in DC_ConsigneeRecordControl.
public class BaseDC_ConsigneeRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_ConsigneeRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_ConsigneeRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_ConsigneeDialogEditButton.Click += new ImageClickEventHandler(DC_ConsigneeDialogEditButton_Click);
              this.CustomerId.Click += new EventHandler(CustomerId_Click);
            
              this.CustomsBrokerOfficeId.Click += new EventHandler(CustomsBrokerOfficeId_Click);
            
        }

        // To customize, override this method in DC_ConsigneeRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_ConsigneeRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_ConsigneeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_ConsigneeRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_ConsigneeRecord[] recList = DC_ConsigneeTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_ConsigneeTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_ConsigneeRecordControl.
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
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Address);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Address.Text = formattedValue;
                        
            } else {  
                this.Address.Text = DC_ConsigneeTable.Address.Format(DC_ConsigneeTable.Address.DefaultValue);
            }
                    
            if (this.Address.Text == null ||
                this.Address.Text.Trim().Length == 0) {
                this.Address.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CitySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.City);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.City.Text = formattedValue;
                        
            } else {  
                this.City.Text = DC_ConsigneeTable.City.Format(DC_ConsigneeTable.City.DefaultValue);
            }
                    
            if (this.City.Text == null ||
                this.City.Text.Trim().Length == 0) {
                this.City.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Comments);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_ConsigneeTable.Comments.Format(DC_ConsigneeTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
            if (this.DataSource.ConsigneeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.ConsigneeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ConsigneeId.Text = formattedValue;
                        
            } else {  
                this.ConsigneeId.Text = DC_ConsigneeTable.ConsigneeId.Format(DC_ConsigneeTable.ConsigneeId.DefaultValue);
            }
                    
            if (this.ConsigneeId.Text == null ||
                this.ConsigneeId.Text.Trim().Length == 0) {
                this.ConsigneeId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.ConsigneeNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.ConsigneeName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ConsigneeName.Text = formattedValue;
                        
            } else {  
                this.ConsigneeName.Text = DC_ConsigneeTable.ConsigneeName.Format(DC_ConsigneeTable.ConsigneeName.DefaultValue);
            }
                    
            if (this.ConsigneeName.Text == null ||
                this.ConsigneeName.Text.Trim().Length == 0) {
                this.ConsigneeName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.CustomerId);
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_ConsigneeTable.CustomerId.Format(DC_ConsigneeTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.CustomsBrokerOfficeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.CustomsBrokerOfficeId);
                this.CustomsBrokerOfficeId.Text = formattedValue;
                        
            } else {  
                this.CustomsBrokerOfficeId.Text = DC_ConsigneeTable.CustomsBrokerOfficeId.Format(DC_ConsigneeTable.CustomsBrokerOfficeId.DefaultValue);
            }
                    
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.Phone);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_ConsigneeTable.Phone.Format(DC_ConsigneeTable.Phone.DefaultValue);
            }
                    
            if (this.Phone.Text == null ||
                this.Phone.Text.Trim().Length == 0) {
                this.Phone.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.PhoneMobile);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PhoneMobile.Text = DC_ConsigneeTable.PhoneMobile.Format(DC_ConsigneeTable.PhoneMobile.DefaultValue);
            }
                    
            if (this.PhoneMobile.Text == null ||
                this.PhoneMobile.Text.Trim().Length == 0) {
                this.PhoneMobile.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PostalCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.PostalCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PostalCode.Text = formattedValue;
                        
            } else {  
                this.PostalCode.Text = DC_ConsigneeTable.PostalCode.Format(DC_ConsigneeTable.PostalCode.DefaultValue);
            }
                    
            if (this.PostalCode.Text == null ||
                this.PostalCode.Text.Trim().Length == 0) {
                this.PostalCode.Text = "&nbsp;";
            }
                  
            if (this.DataSource.StateProvinceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_ConsigneeTable.StateProvince);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.StateProvince.Text = formattedValue;
                        
            } else {  
                this.StateProvince.Text = DC_ConsigneeTable.StateProvince.Format(DC_ConsigneeTable.StateProvince.DefaultValue);
            }
                    
            if (this.StateProvince.Text == null ||
                this.StateProvince.Text.Trim().Length == 0) {
                this.StateProvince.Text = "&nbsp;";
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

        //  To customize, override this method in DC_ConsigneeRecordControl.
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
        
            // 2. Validate the data.  Override in DC_ConsigneeRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_ConsigneeRecordControl to set additional fields.
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

        //  To customize, override this method in DC_ConsigneeRecordControl.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_ConsigneeRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_ConsigneeTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_Consignee"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_Consignee"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_ConsigneeTable.ConsigneeId).ToString());
            } else {
                
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_ConsigneeRecordControl.
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
            DC_ConsigneeTable.DeleteRecord(pk);

          
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
              public virtual void DC_ConsigneeDialogEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/EditDC_ConsigneePage.aspx?DC_Consignee={PK}";
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
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_ConsigneeRecordControl:FK:FK_DC_Consignee_DC_Customer1}";
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
              public virtual void CustomsBrokerOfficeId_Click(object sender, EventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={DC_ConsigneeRecordControl:FK:FK_DC_Consignee_DC_CustomsBrokerOffice}";
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
                return (string)this.ViewState["BaseDC_ConsigneeRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_ConsigneeRecordControl_Rec"] = value;
            }
        }
        
        private DC_ConsigneeRecord _DataSource;
        public DC_ConsigneeRecord DataSource {
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
           
        public System.Web.UI.WebControls.Literal Address {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Address");
            }
        }
        
        public System.Web.UI.WebControls.Literal AddressLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "AddressLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal City {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "City");
            }
        }
        
        public System.Web.UI.WebControls.Literal CityLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CityLabel");
            }
        }
           
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
           
        public System.Web.UI.WebControls.Literal ConsigneeName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeName");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeNameLabel");
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
           
        public System.Web.UI.WebControls.LinkButton CustomsBrokerOfficeId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBrokerOfficeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeDialogEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeDialogEditButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_ConsigneeDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneMobile {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobile");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneMobileLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobileLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal PostalCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal PostalCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal StateProvince {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvince");
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
            DC_ConsigneeRecord rec = null;
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

        public DC_ConsigneeRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_ConsigneeTable.GetRecord(this.RecordUniqueId, true);
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

  