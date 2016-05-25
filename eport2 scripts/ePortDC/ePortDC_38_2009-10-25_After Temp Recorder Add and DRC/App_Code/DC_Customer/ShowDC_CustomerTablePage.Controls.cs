
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CustomerTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CustomerTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CustomerTableControlRow : BaseDC_CustomerTableControlRow
{
      
        // The BaseDC_CustomerTableControlRow implements code for a ROW within the
        // the DC_CustomerTableControl table.  The BaseDC_CustomerTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_CustomerTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_CustomerTableControl : BaseDC_CustomerTableControl
{
        // The BaseDC_CustomerTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_CustomerTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomerTableControlRow control on the ShowDC_CustomerTablePage page.
// Do not modify this class. Instead override any method in DC_CustomerTableControlRow.
public class BaseDC_CustomerTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomerTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomerTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_CustomerRecordRowCopyButton.Click += new ImageClickEventHandler(DC_CustomerRecordRowCopyButton_Click);
              this.DC_CustomerRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_CustomerRecordRowDeleteButton_Click);
              this.DC_CustomerRecordRowEditButton.Click += new ImageClickEventHandler(DC_CustomerRecordRowEditButton_Click);
              this.DC_CustomerRecordRowViewButton.Click += new ImageClickEventHandler(DC_CustomerRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_CustomerTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_CustomerRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_CustomerTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomerTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_CustomerTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_CustomerRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomerTableControlRow.
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
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Address.Text = formattedValue;
                        
            } else {  
                this.Address.Text = DC_CustomerTable.Address.Format(DC_CustomerTable.Address.DefaultValue);
            }
                    
            if (this.Address.Text == null ||
                this.Address.Text.Trim().Length == 0) {
                this.Address.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CitySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.City);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.City.Text = formattedValue;
                        
            } else {  
                this.City.Text = DC_CustomerTable.City.Format(DC_CustomerTable.City.DefaultValue);
            }
                    
            if (this.City.Text == null ||
                this.City.Text.Trim().Length == 0) {
                this.City.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Comments);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomerTable.Comments.Format(DC_CustomerTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_CustomerTable.CustomerId.Format(DC_CustomerTable.CustomerId.DefaultValue);
            }
                    
            if (this.CustomerId.Text == null ||
                this.CustomerId.Text.Trim().Length == 0) {
                this.CustomerId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerName.Text = formattedValue;
                        
            } else {  
                this.CustomerName.Text = DC_CustomerTable.CustomerName.Format(DC_CustomerTable.CustomerName.DefaultValue);
            }
                    
            if (this.CustomerName.Text == null ||
                this.CustomerName.Text.Trim().Length == 0) {
                this.CustomerName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerShortNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.CustomerShortName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerShortName.Text = formattedValue;
                        
            } else {  
                this.CustomerShortName.Text = DC_CustomerTable.CustomerShortName.Format(DC_CustomerTable.CustomerShortName.DefaultValue);
            }
                    
            if (this.CustomerShortName.Text == null ||
                this.CustomerShortName.Text.Trim().Length == 0) {
                this.CustomerShortName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.DestCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.DestCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.DestCode.Text = formattedValue;
                        
            } else {  
                this.DestCode.Text = DC_CustomerTable.DestCode.Format(DC_CustomerTable.DestCode.DefaultValue);
            }
                    
            if (this.DestCode.Text == null ||
                this.DestCode.Text.Trim().Length == 0) {
                this.DestCode.Text = "&nbsp;";
            }
                  
            if (this.DataSource.NeedPARSSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.NeedPARS);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.NeedPARS.Text = formattedValue;
                        
            } else {  
                this.NeedPARS.Text = DC_CustomerTable.NeedPARS.Format(DC_CustomerTable.NeedPARS.DefaultValue);
            }
                    
            if (this.NeedPARS.Text == null ||
                this.NeedPARS.Text.Trim().Length == 0) {
                this.NeedPARS.Text = "&nbsp;";
            }
                  
            if (this.DataSource.OriginSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Origin);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Origin.Text = formattedValue;
                        
            } else {  
                this.Origin.Text = DC_CustomerTable.Origin.Format(DC_CustomerTable.Origin.DefaultValue);
            }
                    
            if (this.Origin.Text == null ||
                this.Origin.Text.Trim().Length == 0) {
                this.Origin.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.Phone);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_CustomerTable.Phone.Format(DC_CustomerTable.Phone.DefaultValue);
            }
                    
            if (this.Phone.Text == null ||
                this.Phone.Text.Trim().Length == 0) {
                this.Phone.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.PhoneMobile);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PhoneMobile.Text = DC_CustomerTable.PhoneMobile.Format(DC_CustomerTable.PhoneMobile.DefaultValue);
            }
                    
            if (this.PhoneMobile.Text == null ||
                this.PhoneMobile.Text.Trim().Length == 0) {
                this.PhoneMobile.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PostalCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.PostalCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PostalCode.Text = formattedValue;
                        
            } else {  
                this.PostalCode.Text = DC_CustomerTable.PostalCode.Format(DC_CustomerTable.PostalCode.DefaultValue);
            }
                    
            if (this.PostalCode.Text == null ||
                this.PostalCode.Text.Trim().Length == 0) {
                this.PostalCode.Text = "&nbsp;";
            }
                  
            if (this.DataSource.StateProvinceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomerTable.StateProvince);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.StateProvince.Text = formattedValue;
                        
            } else {  
                this.StateProvince.Text = DC_CustomerTable.StateProvince.Format(DC_CustomerTable.StateProvince.DefaultValue);
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

        //  To customize, override this method in DC_CustomerTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_CustomerTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomerTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_CustomerTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerTableControl")).DataChanged = true;
                ((DC_CustomerTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_CustomerTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_CustomerTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_CustomerTableControlRow.
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

          
            ((DC_CustomerTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerTableControl")).DataChanged = true;
            ((DC_CustomerTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomerTableControl")).ResetData = true;
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
              public virtual void DC_CustomerRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/AddDC_CustomerPage.aspx?DC_Customer={PK}";
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
              public virtual void DC_CustomerRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/EditDC_CustomerPage.aspx?DC_Customer={PK}";
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
              public virtual void DC_CustomerRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={PK}";
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
                return (string)this.ViewState["BaseDC_CustomerTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomerTableControlRow_Rec"] = value;
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
           
        public System.Web.UI.WebControls.Literal CustomerId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomerName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerName");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomerShortName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortName");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomerRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal DestCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestCode");
            }
        }
           
        public System.Web.UI.WebControls.Literal NeedPARS {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "NeedPARS");
            }
        }
           
        public System.Web.UI.WebControls.Literal Origin {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Origin");
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

  
// Base class for the DC_CustomerTableControl control on the ShowDC_CustomerTablePage page.
// Do not modify this class. Instead override any method in DC_CustomerTableControl.
public class BaseDC_CustomerTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_CustomerTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_CustomerPagination.FirstPage.Click += new ImageClickEventHandler(DC_CustomerPagination_FirstPage_Click);
              this.DC_CustomerPagination.LastPage.Click += new ImageClickEventHandler(DC_CustomerPagination_LastPage_Click);
              this.DC_CustomerPagination.NextPage.Click += new ImageClickEventHandler(DC_CustomerPagination_NextPage_Click);
              this.DC_CustomerPagination.PageSizeButton.Click += new EventHandler(DC_CustomerPagination_PageSizeButton_Click);
            
              this.DC_CustomerPagination.PreviousPage.Click += new ImageClickEventHandler(DC_CustomerPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.AddressLabel1.Click += new EventHandler(AddressLabel1_Click);
            
              this.CityLabel.Click += new EventHandler(CityLabel_Click);
            
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.CustomerIdLabel1.Click += new EventHandler(CustomerIdLabel1_Click);
            
              this.CustomerNameLabel.Click += new EventHandler(CustomerNameLabel_Click);
            
              this.CustomerShortNameLabel.Click += new EventHandler(CustomerShortNameLabel_Click);
            
              this.DestCodeLabel.Click += new EventHandler(DestCodeLabel_Click);
            
              this.NeedPARSLabel.Click += new EventHandler(NeedPARSLabel_Click);
            
              this.OriginLabel.Click += new EventHandler(OriginLabel_Click);
            
              this.PhoneLabel.Click += new EventHandler(PhoneLabel_Click);
            
              this.PhoneMobileLabel.Click += new EventHandler(PhoneMobileLabel_Click);
            
              this.PostalCodeLabel.Click += new EventHandler(PostalCodeLabel_Click);
            
              this.StateProvinceLabel.Click += new EventHandler(StateProvinceLabel_Click);
            

            // Setup the button events.
        
              this.DC_CustomerCopyButton.Click += new ImageClickEventHandler(DC_CustomerCopyButton_Click);
              this.DC_CustomerDeleteButton.Click += new ImageClickEventHandler(DC_CustomerDeleteButton_Click);
              this.DC_CustomerEditButton.Click += new ImageClickEventHandler(DC_CustomerEditButton_Click);
              this.DC_CustomerExportButton.Click += new ImageClickEventHandler(DC_CustomerExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerExportButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerTableControlUpdatePanel"));
                    
              this.DC_CustomerExportExcelButton.Click += new ImageClickEventHandler(DC_CustomerExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerTableControlUpdatePanel"));
                    
              this.DC_CustomerNewButton.Click += new ImageClickEventHandler(DC_CustomerNewButton_Click);
              this.DC_CustomerPDFButton.Click += new ImageClickEventHandler(DC_CustomerPDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomerPDFButton"), MiscUtils.GetParentControlObject(this,"DC_CustomerTableControlUpdatePanel"));
                    
              this.DC_CustomerRefreshButton.Click += new ImageClickEventHandler(DC_CustomerRefreshButton_Click);
              this.DC_CustomerResetButton.Click += new ImageClickEventHandler(DC_CustomerResetButton_Click);
              this.DC_CustomerFilterButton.Button.Click += new EventHandler(DC_CustomerFilterButton_Click);

            // Setup the filter and search events.
        
            this.CustomerShortNameFilter.SelectedIndexChanged += new EventHandler(CustomerShortNameFilter_SelectedIndexChanged);
            this.StateProvinceFilter.SelectedIndexChanged += new EventHandler(StateProvinceFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.CustomerIdFromFilter)) {
                
                this.CustomerIdFromFilter.Text = this.GetFromSession(this.CustomerIdFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomerIdToFilter)) {
                
                this.CustomerIdToFilter.Text = this.GetFromSession(this.CustomerIdToFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomerShortNameFilter)) {
                this.CustomerShortNameFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomerShortNameFilter), this.GetFromSession(this.CustomerShortNameFilter)));
                this.CustomerShortNameFilter.SelectedValue = this.GetFromSession(this.CustomerShortNameFilter);
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
                this.DC_CustomerDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_CustomerRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomerRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_CustomerTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_CustomerRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomerRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_CustomerTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_CustomerRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_CustomerRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_CustomerTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCustomerShortNameFilter(MiscUtils.GetSelectedValue(this.CustomerShortNameFilter, this.GetFromSession(this.CustomerShortNameFilter)), 500);
            this.PopulateStateProvinceFilter(MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomerTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_CustomerTableControlRow recControl = (DC_CustomerTableControlRow)(repItem.FindControl("DC_CustomerTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_CustomerTableControl pagination.
        
            this.DC_CustomerPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_CustomerPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomerPagination.LastPage.Enabled = false;
            }
          
            this.DC_CustomerPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomerPagination.NextPage.Enabled = false;
            }
          
            this.DC_CustomerPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_CustomerPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_CustomerPagination.CurrentPage.Text = "0";
            }
            this.DC_CustomerPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_CustomerTotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomerPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomerPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_CustomerTableControlRow recCtl in this.GetRecordControls())
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
            DC_CustomerTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.CustomerIdFromFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdFromFilter, this.GetFromSession(this.CustomerIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdToFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdToFilter, this.GetFromSession(this.CustomerIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerShortNameFilter)) {
                wc.iAND(DC_CustomerTable.CustomerShortName, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerShortNameFilter, this.GetFromSession(this.CustomerShortNameFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.StateProvinceFilter)) {
                wc.iAND(DC_CustomerTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_CustomerTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String CustomerIdFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerIdFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerIdFromFilterSelectedValue)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, CustomerIdFromFilterSelectedValue, false, false);
            }
                      
            String CustomerIdToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerIdToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerIdToFilterSelectedValue)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, CustomerIdToFilterSelectedValue, false, false);
            }
                      
            String CustomerShortNameFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerShortNameFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerShortNameFilterSelectedValue)) {
                wc.iAND(DC_CustomerTable.CustomerShortName, BaseFilter.ComparisonOperator.EqualsTo, CustomerShortNameFilterSelectedValue, false, false);
            }
                      
            String StateProvinceFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "StateProvinceFilter_Ajax"];
            if (MiscUtils.IsValueSelected(StateProvinceFilterSelectedValue)) {
                wc.iAND(DC_CustomerTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, StateProvinceFilterSelectedValue, false, false);
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
        
            if (this.DC_CustomerPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_CustomerPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomerTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_CustomerTableControlRow recControl = (DC_CustomerTableControlRow)(repItem.FindControl("DC_CustomerTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_CustomerRecord rec = new DC_CustomerRecord();
        
                        if (recControl.Address.Text != "") {
                            rec.Parse(recControl.Address.Text, DC_CustomerTable.Address);
                        }
                        if (recControl.City.Text != "") {
                            rec.Parse(recControl.City.Text, DC_CustomerTable.City);
                        }
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_CustomerTable.Comments);
                        }
                        if (recControl.CustomerId.Text != "") {
                            rec.Parse(recControl.CustomerId.Text, DC_CustomerTable.CustomerId);
                        }
                        if (recControl.CustomerName.Text != "") {
                            rec.Parse(recControl.CustomerName.Text, DC_CustomerTable.CustomerName);
                        }
                        if (recControl.CustomerShortName.Text != "") {
                            rec.Parse(recControl.CustomerShortName.Text, DC_CustomerTable.CustomerShortName);
                        }
                        if (recControl.DestCode.Text != "") {
                            rec.Parse(recControl.DestCode.Text, DC_CustomerTable.DestCode);
                        }
                        if (recControl.NeedPARS.Text != "") {
                            rec.Parse(recControl.NeedPARS.Text, DC_CustomerTable.NeedPARS);
                        }
                        if (recControl.Origin.Text != "") {
                            rec.Parse(recControl.Origin.Text, DC_CustomerTable.Origin);
                        }
                        if (recControl.Phone.Text != "") {
                            rec.Parse(recControl.Phone.Text, DC_CustomerTable.Phone);
                        }
                        if (recControl.PhoneMobile.Text != "") {
                            rec.Parse(recControl.PhoneMobile.Text, DC_CustomerTable.PhoneMobile);
                        }
                        if (recControl.PostalCode.Text != "") {
                            rec.Parse(recControl.PostalCode.Text, DC_CustomerTable.PostalCode);
                        }
                        if (recControl.StateProvince.Text != "") {
                            rec.Parse(recControl.StateProvince.Text, DC_CustomerTable.StateProvince);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_CustomerRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_CustomerRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_CustomerRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_CustomerTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_CustomerTableControlRow rec)            
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
        
        // Get the filters' data for CustomerShortNameFilter.
        protected virtual void PopulateCustomerShortNameFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerShortName, OrderByItem.OrderDir.Asc);

            string[] list = DC_CustomerTable.GetValues(DC_CustomerTable.CustomerShortName, wc, orderBy, maxItems);
            
            this.CustomerShortNameFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CustomerTable.CustomerShortName.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.CustomerShortNameFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CustomerShortNameFilter, selectedValue);

            // Add the All item.
            this.CustomerShortNameFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for StateProvinceFilter.
        protected virtual void PopulateStateProvinceFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.StateProvince, OrderByItem.OrderDir.Asc);

            string[] list = DC_CustomerTable.GetValues(DC_CustomerTable.StateProvince, wc, orderBy, maxItems);
            
            this.StateProvinceFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CustomerTable.StateProvince.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.StateProvinceFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.StateProvinceFilter, selectedValue);

            // Add the All item.
            this.StateProvinceFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CustomerShortNameFilter.
        public virtual WhereClause CreateWhereClause_CustomerShortNameFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CustomerIdFromFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdFromFilter, this.GetFromSession(this.CustomerIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdToFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdToFilter, this.GetFromSession(this.CustomerIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.StateProvinceFilter)) {
                wc.iAND(DC_CustomerTable.StateProvince, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.StateProvinceFilter, this.GetFromSession(this.StateProvinceFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter StateProvinceFilter.
        public virtual WhereClause CreateWhereClause_StateProvinceFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CustomerIdFromFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdFromFilter, this.GetFromSession(this.CustomerIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdToFilter)) {
                wc.iAND(DC_CustomerTable.CustomerId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomerIdToFilter, this.GetFromSession(this.CustomerIdToFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerShortNameFilter)) {
                wc.iAND(DC_CustomerTable.CustomerShortName, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerShortNameFilter, this.GetFromSession(this.CustomerShortNameFilter)), false, false);
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
        
            this.SaveToSession(this.CustomerIdFromFilter, this.CustomerIdFromFilter.Text);
            this.SaveToSession(this.CustomerIdToFilter, this.CustomerIdToFilter.Text);
            this.SaveToSession(this.CustomerShortNameFilter, this.CustomerShortNameFilter.SelectedValue);
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
          
            this.SaveToSession("CustomerIdFromFilter_Ajax", this.CustomerIdFromFilter.Text);
            this.SaveToSession("CustomerIdToFilter_Ajax", this.CustomerIdToFilter.Text);
            this.SaveToSession("CustomerShortNameFilter_Ajax", this.CustomerShortNameFilter.SelectedValue);
            this.SaveToSession("StateProvinceFilter_Ajax", this.StateProvinceFilter.SelectedValue);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.CustomerIdFromFilter);
            this.RemoveFromSession(this.CustomerIdToFilter);
            this.RemoveFromSession(this.CustomerShortNameFilter);
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

            string orderByStr = (string)ViewState["DC_CustomerTableControl_OrderBy"];
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
                this.ViewState["DC_CustomerTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_CustomerPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_CustomerPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_CustomerPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void AddressLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.Address);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.Address, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CityLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.City);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.City, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.CustomerId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.CustomerId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.CustomerName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.CustomerName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerShortNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.CustomerShortName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.CustomerShortName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void DestCodeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.DestCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.DestCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void NeedPARSLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.NeedPARS);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.NeedPARS, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OriginLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.Origin);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.Origin, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.Phone);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.Phone, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneMobileLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.PhoneMobile);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.PhoneMobile, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PostalCodeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.PostalCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.PostalCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void StateProvinceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomerTable.StateProvince);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomerTable.StateProvince, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_CustomerCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/AddDC_CustomerPage.aspx?DC_Customer={PK}";
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
              public virtual void DC_CustomerDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomerEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/EditDC_CustomerPage.aspx?DC_Customer={PK}";
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
              public virtual void DC_CustomerExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_CustomerTable.CustomerId,
             DC_CustomerTable.Address,
             DC_CustomerTable.City,
             DC_CustomerTable.Comments,
             DC_CustomerTable.CustomerName,
             DC_CustomerTable.CustomerShortName,
             DC_CustomerTable.DestCode,
             DC_CustomerTable.NeedPARS,
             DC_CustomerTable.Origin,
             DC_CustomerTable.Phone,
             DC_CustomerTable.PhoneMobile,
             DC_CustomerTable.PostalCode,
             DC_CustomerTable.StateProvince,
             null};
            ExportData rep = new ExportData(DC_CustomerTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_CustomerExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_CustomerTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.CustomerId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.Address, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.City, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.CustomerName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.CustomerShortName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.DestCode, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.NeedPARS, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.Origin, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.Phone, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.PhoneMobile, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.PostalCode, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomerTable.StateProvince, "Default"));

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
              public virtual void DC_CustomerNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Customer/AddDC_CustomerPage.aspx";
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
              public virtual void DC_CustomerPDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_CustomerTablePage.DC_CustomerPDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Customer";
                // If ShowDC_CustomerTablePage.DC_CustomerPDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_CustomerTable.CustomerId.Name, ReportEnum.Align.Right, "${DC_CustomerTable.CustomerId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CustomerTable.Address.Name, ReportEnum.Align.Left, "${DC_CustomerTable.Address.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerTable.City.Name, ReportEnum.Align.Left, "${DC_CustomerTable.City.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_CustomerTable.Comments.Name, ReportEnum.Align.Left, "${DC_CustomerTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerTable.CustomerName.Name, ReportEnum.Align.Left, "${DC_CustomerTable.CustomerName.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomerTable.CustomerShortName.Name, ReportEnum.Align.Left, "${DC_CustomerTable.CustomerShortName.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_CustomerTable.DestCode.Name, ReportEnum.Align.Left, "${DC_CustomerTable.DestCode.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_CustomerTable.NeedPARS.Name, ReportEnum.Align.Left, "${DC_CustomerTable.NeedPARS.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_CustomerTable.Origin.Name, ReportEnum.Align.Left, "${DC_CustomerTable.Origin.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_CustomerTable.Phone.Name, ReportEnum.Align.Left, "${DC_CustomerTable.Phone.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_CustomerTable.PhoneMobile.Name, ReportEnum.Align.Left, "${DC_CustomerTable.PhoneMobile.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_CustomerTable.PostalCode.Name, ReportEnum.Align.Left, "${DC_CustomerTable.PostalCode.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_CustomerTable.StateProvince.Name, ReportEnum.Align.Left, "${DC_CustomerTable.StateProvince.Name}", ReportEnum.Align.Left, 24);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_CustomerTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_CustomerTable.GetColumnList();
                DC_CustomerRecord[] records = null;
                do
                {
                    records = DC_CustomerTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_CustomerRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_CustomerTable.CustomerId.Name}", record.Format(DC_CustomerTable.CustomerId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CustomerTable.Address.Name}", record.Format(DC_CustomerTable.Address), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.City.Name}", record.Format(DC_CustomerTable.City), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.Comments.Name}", record.Format(DC_CustomerTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.CustomerName.Name}", record.Format(DC_CustomerTable.CustomerName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.CustomerShortName.Name}", record.Format(DC_CustomerTable.CustomerShortName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.DestCode.Name}", record.Format(DC_CustomerTable.DestCode), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.NeedPARS.Name}", record.Format(DC_CustomerTable.NeedPARS), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.Origin.Name}", record.Format(DC_CustomerTable.Origin), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.Phone.Name}", record.Format(DC_CustomerTable.Phone), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.PhoneMobile.Name}", record.Format(DC_CustomerTable.PhoneMobile), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.PostalCode.Name}", record.Format(DC_CustomerTable.PostalCode), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomerTable.StateProvince.Name}", record.Format(DC_CustomerTable.StateProvince), ReportEnum.Align.Left, 100);

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
              public virtual void DC_CustomerRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_CustomerTableControl)(this.Page.FindControlRecursively("DC_CustomerTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomerResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CustomerShortNameFilter.ClearSelection();
            
              this.StateProvinceFilter.ClearSelection();
            
              this.CustomerIdFromFilter.Text = "";
            
              this.CustomerIdToFilter.Text = "";
            
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
              public virtual void DC_CustomerFilterButton_Click(object sender, EventArgs args)
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
        protected virtual void CustomerShortNameFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_CustomerRecord[] _DataSource = null;
        public  DC_CustomerRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton AddressLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "AddressLabel1");
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
        
        public System.Web.UI.WebControls.TextBox CustomerIdFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdFromFilter");
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
        
        public System.Web.UI.WebControls.TextBox CustomerIdToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdToFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CustomerNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerShortName1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortName1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CustomerShortNameFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortNameFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CustomerShortNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerShortNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_CustomerFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_CustomerPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerPDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerPDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomerResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomerTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomerToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_CustomerTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomerTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DestCodeLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestCodeLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton NeedPARSLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "NeedPARSLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OriginLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OriginLabel");
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
                DC_CustomerTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_CustomerRecord rec = null;
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
            foreach (DC_CustomerTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomerRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_CustomerTableControlRow GetSelectedRecordControl()
        {
        DC_CustomerTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_CustomerTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_CustomerTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomerRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_CustomerTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomerTablePage.DC_CustomerTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_CustomerTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_CustomerTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_CustomerRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_CustomerTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_CustomerTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_CustomerTableControlRow recControl = (DC_CustomerTableControlRow)repItem.FindControl("DC_CustomerTableControlRow");
                recList.Add(recControl);
            }

            return (DC_CustomerTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomerTablePage.DC_CustomerTableControlRow"));
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

  