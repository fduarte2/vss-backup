
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_ConsigneeTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_ConsigneeTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_ConsigneeTableControlRow : BaseDC_ConsigneeTableControlRow
{
      
        // The BaseDC_ConsigneeTableControlRow implements code for a ROW within the
        // the DC_ConsigneeTableControl table.  The BaseDC_ConsigneeTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_ConsigneeTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_ConsigneeTableControl : BaseDC_ConsigneeTableControl
{
        // The BaseDC_ConsigneeTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_ConsigneeTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_ConsigneeTableControlRow control on the ShowDC_ConsigneeTablePage page.
// Do not modify this class. Instead override any method in DC_ConsigneeTableControlRow.
public class BaseDC_ConsigneeTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_ConsigneeTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_ConsigneeTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_ConsigneeRecordRowCopyButton.Click += new ImageClickEventHandler(DC_ConsigneeRecordRowCopyButton_Click);
              this.DC_ConsigneeRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_ConsigneeRecordRowDeleteButton_Click);
              this.DC_ConsigneeRecordRowEditButton.Click += new ImageClickEventHandler(DC_ConsigneeRecordRowEditButton_Click);
              this.DC_ConsigneeRecordRowViewButton.Click += new ImageClickEventHandler(DC_ConsigneeRecordRowViewButton_Click);
              this.CustomerId.Click += new EventHandler(CustomerId_Click);
            
              this.CustomsBrokerOfficeId.Click += new EventHandler(CustomsBrokerOfficeId_Click);
            
        }

        // To customize, override this method in DC_ConsigneeTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_ConsigneeRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_ConsigneeTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_ConsigneeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_ConsigneeTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_ConsigneeRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_ConsigneeTableControlRow.
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

        //  To customize, override this method in DC_ConsigneeTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_ConsigneeTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_ConsigneeTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_ConsigneeTableControl)MiscUtils.GetParentControlObject(this, "DC_ConsigneeTableControl")).DataChanged = true;
                ((DC_ConsigneeTableControl)MiscUtils.GetParentControlObject(this, "DC_ConsigneeTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_ConsigneeTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_ConsigneeTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_ConsigneeTableControlRow.
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

          
            ((DC_ConsigneeTableControl)MiscUtils.GetParentControlObject(this, "DC_ConsigneeTableControl")).DataChanged = true;
            ((DC_ConsigneeTableControl)MiscUtils.GetParentControlObject(this, "DC_ConsigneeTableControl")).ResetData = true;
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
              public virtual void DC_ConsigneeRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/AddDC_ConsigneePage.aspx?DC_Consignee={PK}";
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
              public virtual void DC_ConsigneeRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_ConsigneeRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_ConsigneeRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/ShowDC_ConsigneePage.aspx?DC_Consignee={PK}";
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
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_ConsigneeTableControlRow:FK:FK_DC_Consignee_DC_Customer1}";
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
              
            string url = @"../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={DC_ConsigneeTableControlRow:FK:FK_DC_Consignee_DC_CustomsBrokerOffice}";
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
                return (string)this.ViewState["BaseDC_ConsigneeTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_ConsigneeTableControlRow_Rec"] = value;
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
           
        public System.Web.UI.WebControls.Literal City {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "City");
            }
        }
           
        public System.Web.UI.WebControls.Literal Comments {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
           
        public System.Web.UI.WebControls.Literal ConsigneeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
           
        public System.Web.UI.WebControls.Literal ConsigneeName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeName");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton CustomerId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton CustomsBrokerOfficeId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_ConsigneeRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneMobile {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobile");
            }
        }
           
        public System.Web.UI.WebControls.Literal PostalCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCode");
            }
        }
           
        public System.Web.UI.WebControls.Literal StateProvince {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvince");
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

  
// Base class for the DC_ConsigneeTableControl control on the ShowDC_ConsigneeTablePage page.
// Do not modify this class. Instead override any method in DC_ConsigneeTableControl.
public class BaseDC_ConsigneeTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_ConsigneeTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_ConsigneePagination.FirstPage.Click += new ImageClickEventHandler(DC_ConsigneePagination_FirstPage_Click);
              this.DC_ConsigneePagination.LastPage.Click += new ImageClickEventHandler(DC_ConsigneePagination_LastPage_Click);
              this.DC_ConsigneePagination.NextPage.Click += new ImageClickEventHandler(DC_ConsigneePagination_NextPage_Click);
              this.DC_ConsigneePagination.PageSizeButton.Click += new EventHandler(DC_ConsigneePagination_PageSizeButton_Click);
            
              this.DC_ConsigneePagination.PreviousPage.Click += new ImageClickEventHandler(DC_ConsigneePagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.AddressLabel.Click += new EventHandler(AddressLabel_Click);
            
              this.CityLabel.Click += new EventHandler(CityLabel_Click);
            
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.ConsigneeIdLabel.Click += new EventHandler(ConsigneeIdLabel_Click);
            
              this.ConsigneeNameLabel.Click += new EventHandler(ConsigneeNameLabel_Click);
            
              this.CustomerIdLabel1.Click += new EventHandler(CustomerIdLabel1_Click);
            
              this.CustomsBrokerOfficeIdLabel1.Click += new EventHandler(CustomsBrokerOfficeIdLabel1_Click);
            
              this.PhoneLabel.Click += new EventHandler(PhoneLabel_Click);
            
              this.PhoneMobileLabel.Click += new EventHandler(PhoneMobileLabel_Click);
            
              this.PostalCodeLabel.Click += new EventHandler(PostalCodeLabel_Click);
            
              this.StateProvinceLabel.Click += new EventHandler(StateProvinceLabel_Click);
            

            // Setup the button events.
        
              this.DC_ConsigneeCopyButton.Click += new ImageClickEventHandler(DC_ConsigneeCopyButton_Click);
              this.DC_ConsigneeDeleteButton.Click += new ImageClickEventHandler(DC_ConsigneeDeleteButton_Click);
              this.DC_ConsigneeEditButton.Click += new ImageClickEventHandler(DC_ConsigneeEditButton_Click);
              this.DC_ConsigneeExportButton.Click += new ImageClickEventHandler(DC_ConsigneeExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_ConsigneeExportButton"), MiscUtils.GetParentControlObject(this,"DC_ConsigneeTableControlUpdatePanel"));
                    
              this.DC_ConsigneeExportExcelButton.Click += new ImageClickEventHandler(DC_ConsigneeExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_ConsigneeExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_ConsigneeTableControlUpdatePanel"));
                    
              this.DC_ConsigneeNewButton.Click += new ImageClickEventHandler(DC_ConsigneeNewButton_Click);
              this.DC_ConsigneePDFButton.Click += new ImageClickEventHandler(DC_ConsigneePDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_ConsigneePDFButton"), MiscUtils.GetParentControlObject(this,"DC_ConsigneeTableControlUpdatePanel"));
                    
              this.DC_ConsigneeRefreshButton.Click += new ImageClickEventHandler(DC_ConsigneeRefreshButton_Click);
              this.DC_ConsigneeResetButton.Click += new ImageClickEventHandler(DC_ConsigneeResetButton_Click);
              this.DC_ConsigneeFilterButton.Button.Click += new EventHandler(DC_ConsigneeFilterButton_Click);

            // Setup the filter and search events.
        
            this.CustomerIdFilter.SelectedIndexChanged += new EventHandler(CustomerIdFilter_SelectedIndexChanged);
            this.CustomsBrokerOfficeIdFilter.SelectedIndexChanged += new EventHandler(CustomsBrokerOfficeIdFilter_SelectedIndexChanged);
            this.StateProvinceFilter.SelectedIndexChanged += new EventHandler(StateProvinceFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.ConsigneeIdFromFilter)) {
                
                this.ConsigneeIdFromFilter.Text = this.GetFromSession(this.ConsigneeIdFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.ConsigneeIdToFilter)) {
                
                this.ConsigneeIdToFilter.Text = this.GetFromSession(this.ConsigneeIdToFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomerIdFilter)) {
                this.CustomerIdFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomerIdFilter), this.GetFromSession(this.CustomerIdFilter)));
                this.CustomerIdFilter.SelectedValue = this.GetFromSession(this.CustomerIdFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomsBrokerOfficeIdFilter)) {
                this.CustomsBrokerOfficeIdFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomsBrokerOfficeIdFilter), this.GetFromSession(this.CustomsBrokerOfficeIdFilter)));
                this.CustomsBrokerOfficeIdFilter.SelectedValue = this.GetFromSession(this.CustomsBrokerOfficeIdFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.StateProvinceFilter)) {
                this.StateProvinceFilter.Items.Add(new ListItem(this.GetFromSession(this.StateProvinceFilter), this.GetFromSession(this.StateProvinceFilter)));
                this.StateProvinceFilter.SelectedValue = this.GetFromSession(this.StateProvinceFilter);
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
                this.DC_ConsigneeDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_ConsigneeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_ConsigneeTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_ConsigneeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_ConsigneeTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_ConsigneeRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_ConsigneeTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCustomerIdFilter(MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), 500);
            this.PopulateCustomsBrokerOfficeIdFilter(MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFilter)), 500);
            this.PopulateStateProvinceFilter(MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_ConsigneeTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_ConsigneeTableControlRow recControl = (DC_ConsigneeTableControlRow)(repItem.FindControl("DC_ConsigneeTableControlRow"));
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
          
            this.Page.PregetDfkaRecords(DC_ConsigneeTable.CustomerId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_ConsigneeTable.CustomsBrokerOfficeId, this.DataSource);
        }
         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_ConsigneeTableControl pagination.
        
            this.DC_ConsigneePagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_ConsigneePagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_ConsigneePagination.LastPage.Enabled = false;
            }
          
            this.DC_ConsigneePagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_ConsigneePagination.NextPage.Enabled = false;
            }
          
            this.DC_ConsigneePagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_ConsigneePagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_ConsigneePagination.CurrentPage.Text = "0";
            }
            this.DC_ConsigneePagination.PageSize.Text = this.PageSize.ToString();
            this.DC_ConsigneeTotalItems.Text = this.TotalRecords.ToString();
            this.DC_ConsigneePagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_ConsigneePagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_ConsigneeTableControlRow recCtl in this.GetRecordControls())
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
            DC_ConsigneeTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.ConsigneeIdFromFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdFromFilter, this.GetFromSession(this.ConsigneeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.ConsigneeIdToFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdToFilter, this.GetFromSession(this.ConsigneeIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.StateProvinceFilter)) {
                wc.iAND(DC_ConsigneeTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_ConsigneeTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String ConsigneeIdFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "ConsigneeIdFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(ConsigneeIdFromFilterSelectedValue)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, ConsigneeIdFromFilterSelectedValue, false, false);
            }
                      
            String ConsigneeIdToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "ConsigneeIdToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(ConsigneeIdToFilterSelectedValue)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, ConsigneeIdToFilterSelectedValue, false, false);
            }
                      
            String CustomerIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerIdFilterSelectedValue)) {
                wc.iAND(DC_ConsigneeTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, CustomerIdFilterSelectedValue, false, false);
            }
                      
            String CustomsBrokerOfficeIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomsBrokerOfficeIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomsBrokerOfficeIdFilterSelectedValue)) {
                wc.iAND(DC_ConsigneeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, CustomsBrokerOfficeIdFilterSelectedValue, false, false);
            }
                      
            String StateProvinceFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "StateProvinceFilter_Ajax"];
            if (MiscUtils.IsValueSelected(StateProvinceFilterSelectedValue)) {
                wc.iAND(DC_ConsigneeTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, StateProvinceFilterSelectedValue, false, false);
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
        
            if (this.DC_ConsigneePagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_ConsigneePagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_ConsigneeTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_ConsigneeTableControlRow recControl = (DC_ConsigneeTableControlRow)(repItem.FindControl("DC_ConsigneeTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_ConsigneeRecord rec = new DC_ConsigneeRecord();
        
                        if (recControl.Address.Text != "") {
                            rec.Parse(recControl.Address.Text, DC_ConsigneeTable.Address);
                        }
                        if (recControl.City.Text != "") {
                            rec.Parse(recControl.City.Text, DC_ConsigneeTable.City);
                        }
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_ConsigneeTable.Comments);
                        }
                        if (recControl.ConsigneeId.Text != "") {
                            rec.Parse(recControl.ConsigneeId.Text, DC_ConsigneeTable.ConsigneeId);
                        }
                        if (recControl.ConsigneeName.Text != "") {
                            rec.Parse(recControl.ConsigneeName.Text, DC_ConsigneeTable.ConsigneeName);
                        }
                        if (recControl.CustomerId.Text != "") {
                            rec.Parse(recControl.CustomerId.Text, DC_ConsigneeTable.CustomerId);
                        }
                        if (recControl.CustomsBrokerOfficeId.Text != "") {
                            rec.Parse(recControl.CustomsBrokerOfficeId.Text, DC_ConsigneeTable.CustomsBrokerOfficeId);
                        }
                        if (recControl.Phone.Text != "") {
                            rec.Parse(recControl.Phone.Text, DC_ConsigneeTable.Phone);
                        }
                        if (recControl.PhoneMobile.Text != "") {
                            rec.Parse(recControl.PhoneMobile.Text, DC_ConsigneeTable.PhoneMobile);
                        }
                        if (recControl.PostalCode.Text != "") {
                            rec.Parse(recControl.PostalCode.Text, DC_ConsigneeTable.PostalCode);
                        }
                        if (recControl.StateProvince.Text != "") {
                            rec.Parse(recControl.StateProvince.Text, DC_ConsigneeTable.StateProvince);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_ConsigneeRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_ConsigneeRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_ConsigneeTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_ConsigneeTableControlRow rec)            
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
        
        // Get the filters' data for CustomerIdFilter.
        protected virtual void PopulateCustomerIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerShortName, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CustomerIdFilter.Items.Clear();
            foreach (DC_CustomerRecord itemValue in DC_CustomerTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CustomerIdSpecified) {
                    cvalue = itemValue.CustomerId.ToString();
                    fvalue = itemValue.Format(DC_CustomerTable.CustomerShortName);
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
            
        // Get the filters' data for CustomsBrokerOfficeIdFilter.
        protected virtual void PopulateCustomsBrokerOfficeIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomsBrokerOfficeTable.CustomsBroker, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CustomsBrokerOfficeIdFilter.Items.Clear();
            foreach (DC_CustomsBrokerOfficeRecord itemValue in DC_CustomsBrokerOfficeTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CustomsBrokerOfficeIdSpecified) {
                    cvalue = itemValue.CustomsBrokerOfficeId.ToString();
                    fvalue = itemValue.Format(DC_CustomsBrokerOfficeTable.CustomsBroker);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.CustomsBrokerOfficeIdFilter.Items.IndexOf(item) < 0) {
                    this.CustomsBrokerOfficeIdFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CustomsBrokerOfficeIdFilter, selectedValue);

            // Add the All item.
            this.CustomsBrokerOfficeIdFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for StateProvinceFilter.
        protected virtual void PopulateStateProvinceFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_ConsigneeTable.StateProvince, OrderByItem.OrderDir.Asc);

            string[] list = DC_ConsigneeTable.GetValues(DC_ConsigneeTable.StateProvince, wc, orderBy, maxItems);
            
            this.StateProvinceFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_ConsigneeTable.StateProvince.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.StateProvinceFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.StateProvinceFilter, selectedValue);

            // Add the All item.
            this.StateProvinceFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CustomerIdFilter.
        public virtual WhereClause CreateWhereClause_CustomerIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.ConsigneeIdFromFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdFromFilter, this.GetFromSession(this.ConsigneeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.ConsigneeIdToFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdToFilter, this.GetFromSession(this.ConsigneeIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.StateProvinceFilter)) {
                wc.iAND(DC_ConsigneeTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter CustomsBrokerOfficeIdFilter.
        public virtual WhereClause CreateWhereClause_CustomsBrokerOfficeIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.ConsigneeIdFromFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdFromFilter, this.GetFromSession(this.ConsigneeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.ConsigneeIdToFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdToFilter, this.GetFromSession(this.ConsigneeIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.StateProvinceFilter)) {
                wc.iAND(DC_ConsigneeTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter StateProvinceFilter.
        public virtual WhereClause CreateWhereClause_StateProvinceFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.ConsigneeIdFromFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdFromFilter, this.GetFromSession(this.ConsigneeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.ConsigneeIdToFilter)) {
                wc.iAND(DC_ConsigneeTable.ConsigneeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.ConsigneeIdToFilter, this.GetFromSession(this.ConsigneeIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFilter)) {
                wc.iAND(DC_ConsigneeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFilter)), false, false);
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
        
            this.SaveToSession(this.ConsigneeIdFromFilter, this.ConsigneeIdFromFilter.Text);
            this.SaveToSession(this.ConsigneeIdToFilter, this.ConsigneeIdToFilter.Text);
            this.SaveToSession(this.CustomerIdFilter, this.CustomerIdFilter.SelectedValue);
            this.SaveToSession(this.CustomsBrokerOfficeIdFilter, this.CustomsBrokerOfficeIdFilter.SelectedValue);
            this.SaveToSession(this.StateProvinceFilter, this.StateProvinceFilter.SelectedValue);
            
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
          
            this.SaveToSession("ConsigneeIdFromFilter_Ajax", this.ConsigneeIdFromFilter.Text);
            this.SaveToSession("ConsigneeIdToFilter_Ajax", this.ConsigneeIdToFilter.Text);
            this.SaveToSession("CustomerIdFilter_Ajax", this.CustomerIdFilter.SelectedValue);
            this.SaveToSession("CustomsBrokerOfficeIdFilter_Ajax", this.CustomsBrokerOfficeIdFilter.SelectedValue);
            this.SaveToSession("StateProvinceFilter_Ajax", this.StateProvinceFilter.SelectedValue);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.ConsigneeIdFromFilter);
            this.RemoveFromSession(this.ConsigneeIdToFilter);
            this.RemoveFromSession(this.CustomerIdFilter);
            this.RemoveFromSession(this.CustomsBrokerOfficeIdFilter);
            this.RemoveFromSession(this.StateProvinceFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_ConsigneeTableControl_OrderBy"];
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
                this.ViewState["DC_ConsigneeTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_ConsigneePagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_ConsigneePagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_ConsigneePagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_ConsigneePagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_ConsigneePagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_ConsigneePagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_ConsigneePagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void AddressLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.Address);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.Address, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CityLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.City);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.City, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ConsigneeIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.ConsigneeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.ConsigneeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ConsigneeNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.ConsigneeName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.ConsigneeName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.CustomerId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.CustomerId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomsBrokerOfficeIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.CustomsBrokerOfficeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.CustomsBrokerOfficeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.Phone);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.Phone, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneMobileLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.PhoneMobile);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.PhoneMobile, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PostalCodeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.PostalCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.PostalCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void StateProvinceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_ConsigneeTable.StateProvince);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_ConsigneeTable.StateProvince, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_ConsigneeCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/AddDC_ConsigneePage.aspx?DC_Consignee={PK}";
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
              public virtual void DC_ConsigneeDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_ConsigneeEditButton_Click(object sender, ImageClickEventArgs args)
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
            
              // event handler for ImageButton
              public virtual void DC_ConsigneeExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_ConsigneeTable.ConsigneeId,
             DC_ConsigneeTable.Address,
             DC_ConsigneeTable.CustomsBrokerOfficeId,
             DC_ConsigneeTable.City,
             DC_ConsigneeTable.Comments,
             DC_ConsigneeTable.ConsigneeName,
             DC_ConsigneeTable.CustomerId,
             DC_ConsigneeTable.Phone,
             DC_ConsigneeTable.PhoneMobile,
             DC_ConsigneeTable.PostalCode,
             DC_ConsigneeTable.StateProvince,
             null};
            ExportData rep = new ExportData(DC_ConsigneeTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_ConsigneeExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_ConsigneeTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.ConsigneeId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.Address, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.CustomsBrokerOfficeId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.City, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.ConsigneeName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.CustomerId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.Phone, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.PhoneMobile, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.PostalCode, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_ConsigneeTable.StateProvince, "Default"));

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
              public virtual void DC_ConsigneeNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Consignee/AddDC_ConsigneePage.aspx";
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
              public virtual void DC_ConsigneePDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_ConsigneeTablePage.DC_ConsigneePDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Consignee";
                // If ShowDC_ConsigneeTablePage.DC_ConsigneePDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_ConsigneeTable.ConsigneeId.Name, ReportEnum.Align.Right, "${DC_ConsigneeTable.ConsigneeId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_ConsigneeTable.Address.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.Address.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_ConsigneeTable.CustomsBrokerOfficeId.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.CustomsBrokerOfficeId.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_ConsigneeTable.City.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.City.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_ConsigneeTable.Comments.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_ConsigneeTable.ConsigneeName.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.ConsigneeName.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_ConsigneeTable.CustomerId.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.CustomerId.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_ConsigneeTable.Phone.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.Phone.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_ConsigneeTable.PhoneMobile.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.PhoneMobile.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_ConsigneeTable.PostalCode.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.PostalCode.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_ConsigneeTable.StateProvince.Name, ReportEnum.Align.Left, "${DC_ConsigneeTable.StateProvince.Name}", ReportEnum.Align.Left, 24);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_ConsigneeTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_ConsigneeTable.GetColumnList();
                DC_ConsigneeRecord[] records = null;
                do
                {
                    records = DC_ConsigneeTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_ConsigneeRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_ConsigneeTable.ConsigneeId.Name}", record.Format(DC_ConsigneeTable.ConsigneeId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_ConsigneeTable.Address.Name}", record.Format(DC_ConsigneeTable.Address), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.CustomsBrokerOfficeId.Name}", record.Format(DC_ConsigneeTable.CustomsBrokerOfficeId), ReportEnum.Align.Left);
                             report.AddData("${DC_ConsigneeTable.City.Name}", record.Format(DC_ConsigneeTable.City), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.Comments.Name}", record.Format(DC_ConsigneeTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.ConsigneeName.Name}", record.Format(DC_ConsigneeTable.ConsigneeName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.CustomerId.Name}", record.Format(DC_ConsigneeTable.CustomerId), ReportEnum.Align.Left);
                             report.AddData("${DC_ConsigneeTable.Phone.Name}", record.Format(DC_ConsigneeTable.Phone), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.PhoneMobile.Name}", record.Format(DC_ConsigneeTable.PhoneMobile), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.PostalCode.Name}", record.Format(DC_ConsigneeTable.PostalCode), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_ConsigneeTable.StateProvince.Name}", record.Format(DC_ConsigneeTable.StateProvince), ReportEnum.Align.Left, 100);

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
              public virtual void DC_ConsigneeRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_ConsigneeTableControl)(this.Page.FindControlRecursively("DC_ConsigneeTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_ConsigneeResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CustomerIdFilter.ClearSelection();
            
              this.CustomsBrokerOfficeIdFilter.ClearSelection();
            
              this.StateProvinceFilter.ClearSelection();
            
              this.ConsigneeIdFromFilter.Text = "";
            
              this.ConsigneeIdToFilter.Text = "";
            
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
              public virtual void DC_ConsigneeFilterButton_Click(object sender, EventArgs args)
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
        protected virtual void CustomerIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void CustomsBrokerOfficeIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void StateProvinceFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_ConsigneeRecord[] _DataSource = null;
        public  DC_ConsigneeRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton AddressLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "AddressLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CityLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CityLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CommentsLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeId1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId1Label");
            }
        }
        
        public System.Web.UI.WebControls.TextBox ConsigneeIdFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdFromFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.TextBox ConsigneeIdToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdToFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ConsigneeNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeNameLabel");
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
        
        public System.Web.UI.WebControls.DropDownList CustomsBrokerOfficeIdFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBrokerOfficeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CustomsBrokerOfficeIdLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdLabel1");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_ConsigneeFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_ConsigneePagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneePagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneePDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneePDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_ConsigneeResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_ConsigneeTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_ConsigneeToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_ConsigneeTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_ConsigneeTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PhoneLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PhoneMobileLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneMobileLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PostalCodeLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PostalCodeLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal StateProvince1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvince1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList StateProvinceFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvinceFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton StateProvinceLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "StateProvinceLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_ConsigneeTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_ConsigneeRecord rec = null;
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
            foreach (DC_ConsigneeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_ConsigneeRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_ConsigneeTableControlRow GetSelectedRecordControl()
        {
        DC_ConsigneeTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_ConsigneeTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_ConsigneeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_ConsigneeRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_ConsigneeTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_ConsigneeTablePage.DC_ConsigneeTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_ConsigneeTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_ConsigneeTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_ConsigneeRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_ConsigneeTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_ConsigneeTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_ConsigneeTableControlRow recControl = (DC_ConsigneeTableControlRow)repItem.FindControl("DC_ConsigneeTableControlRow");
                recList.Add(recControl);
            }

            return (DC_ConsigneeTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_ConsigneeTablePage.DC_ConsigneeTableControlRow"));
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

  