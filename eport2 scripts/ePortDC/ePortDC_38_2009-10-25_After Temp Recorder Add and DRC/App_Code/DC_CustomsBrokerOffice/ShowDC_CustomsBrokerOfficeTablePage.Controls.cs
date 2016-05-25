
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_CustomsBrokerOfficeTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_CustomsBrokerOfficeTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_CustomsBrokerOfficeTableControlRow : BaseDC_CustomsBrokerOfficeTableControlRow
{
      
        // The BaseDC_CustomsBrokerOfficeTableControlRow implements code for a ROW within the
        // the DC_CustomsBrokerOfficeTableControl table.  The BaseDC_CustomsBrokerOfficeTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_CustomsBrokerOfficeTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_CustomsBrokerOfficeTableControl : BaseDC_CustomsBrokerOfficeTableControl
{
        // The BaseDC_CustomsBrokerOfficeTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_CustomsBrokerOfficeTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_CustomsBrokerOfficeTableControlRow control on the ShowDC_CustomsBrokerOfficeTablePage page.
// Do not modify this class. Instead override any method in DC_CustomsBrokerOfficeTableControlRow.
public class BaseDC_CustomsBrokerOfficeTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_CustomsBrokerOfficeTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_CustomsBrokerOfficeRecordRowCopyButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeRecordRowCopyButton_Click);
              this.DC_CustomsBrokerOfficeRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeRecordRowDeleteButton_Click);
              this.DC_CustomsBrokerOfficeRecordRowEditButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeRecordRowEditButton_Click);
              this.DC_CustomsBrokerOfficeRecordRowViewButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_CustomsBrokerOfficeRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_CustomsBrokerOfficeTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_CustomsBrokerOfficeTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_CustomsBrokerOfficeRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
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
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.BorderCrossing.Text = formattedValue;
                        
            } else {  
                this.BorderCrossing.Text = DC_CustomsBrokerOfficeTable.BorderCrossing.Format(DC_CustomsBrokerOfficeTable.BorderCrossing.DefaultValue);
            }
                    
            if (this.BorderCrossing.Text == null ||
                this.BorderCrossing.Text.Trim().Length == 0) {
                this.BorderCrossing.Text = "&nbsp;";
            }
                  
            if (this.DataSource.ClientSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Client);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Client.Text = formattedValue;
                        
            } else {  
                this.Client.Text = DC_CustomsBrokerOfficeTable.Client.Format(DC_CustomsBrokerOfficeTable.Client.DefaultValue);
            }
                    
            if (this.Client.Text == null ||
                this.Client.Text.Trim().Length == 0) {
                this.Client.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Comments);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_CustomsBrokerOfficeTable.Comments.Format(DC_CustomsBrokerOfficeTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
            if (this.DataSource.ContactNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.ContactName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ContactName.Text = formattedValue;
                        
            } else {  
                this.ContactName.Text = DC_CustomsBrokerOfficeTable.ContactName.Format(DC_CustomsBrokerOfficeTable.ContactName.DefaultValue);
            }
                    
            if (this.ContactName.Text == null ||
                this.ContactName.Text.Trim().Length == 0) {
                this.ContactName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomsBrokerSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.CustomsBroker);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomsBroker.Text = formattedValue;
                        
            } else {  
                this.CustomsBroker.Text = DC_CustomsBrokerOfficeTable.CustomsBroker.Format(DC_CustomsBrokerOfficeTable.CustomsBroker.DefaultValue);
            }
                    
            if (this.CustomsBroker.Text == null ||
                this.CustomsBroker.Text.Trim().Length == 0) {
                this.CustomsBroker.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomsBrokerOfficeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomsBrokerOfficeId.Text = formattedValue;
                        
            } else {  
                this.CustomsBrokerOfficeId.Text = DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.Format(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.DefaultValue);
            }
                    
            if (this.CustomsBrokerOfficeId.Text == null ||
                this.CustomsBrokerOfficeId.Text.Trim().Length == 0) {
                this.CustomsBrokerOfficeId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.DestinationsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Destinations);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Destinations.Text = formattedValue;
                        
            } else {  
                this.Destinations.Text = DC_CustomsBrokerOfficeTable.Destinations.Format(DC_CustomsBrokerOfficeTable.Destinations.DefaultValue);
            }
                    
            if (this.Destinations.Text == null ||
                this.Destinations.Text.Trim().Length == 0) {
                this.Destinations.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Email1Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email1);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email1.Text = formattedValue;
                        
            } else {  
                this.Email1.Text = DC_CustomsBrokerOfficeTable.Email1.Format(DC_CustomsBrokerOfficeTable.Email1.DefaultValue);
            }
                    
            if (this.Email1.Text == null ||
                this.Email1.Text.Trim().Length == 0) {
                this.Email1.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Email2Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email2);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email2.Text = formattedValue;
                        
            } else {  
                this.Email2.Text = DC_CustomsBrokerOfficeTable.Email2.Format(DC_CustomsBrokerOfficeTable.Email2.DefaultValue);
            }
                    
            if (this.Email2.Text == null ||
                this.Email2.Text.Trim().Length == 0) {
                this.Email2.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Email3Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email3);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email3.Text = formattedValue;
                        
            } else {  
                this.Email3.Text = DC_CustomsBrokerOfficeTable.Email3.Format(DC_CustomsBrokerOfficeTable.Email3.DefaultValue);
            }
                    
            if (this.Email3.Text == null ||
                this.Email3.Text.Trim().Length == 0) {
                this.Email3.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Email4Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email4);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email4.Text = formattedValue;
                        
            } else {  
                this.Email4.Text = DC_CustomsBrokerOfficeTable.Email4.Format(DC_CustomsBrokerOfficeTable.Email4.DefaultValue);
            }
                    
            if (this.Email4.Text == null ||
                this.Email4.Text.Trim().Length == 0) {
                this.Email4.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Email5Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Email5);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email5.Text = formattedValue;
                        
            } else {  
                this.Email5.Text = DC_CustomsBrokerOfficeTable.Email5.Format(DC_CustomsBrokerOfficeTable.Email5.DefaultValue);
            }
                    
            if (this.Email5.Text == null ||
                this.Email5.Text.Trim().Length == 0) {
                this.Email5.Text = "&nbsp;";
            }
                  
            if (this.DataSource.FaxSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Fax);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Fax.Text = formattedValue;
                        
            } else {  
                this.Fax.Text = DC_CustomsBrokerOfficeTable.Fax.Format(DC_CustomsBrokerOfficeTable.Fax.DefaultValue);
            }
                    
            if (this.Fax.Text == null ||
                this.Fax.Text.Trim().Length == 0) {
                this.Fax.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_CustomsBrokerOfficeTable.Phone);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Phone.Text = formattedValue;
                        
            } else {  
                this.Phone.Text = DC_CustomsBrokerOfficeTable.Phone.Format(DC_CustomsBrokerOfficeTable.Phone.DefaultValue);
            }
                    
            if (this.Phone.Text == null ||
                this.Phone.Text.Trim().Length == 0) {
                this.Phone.Text = "&nbsp;";
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

        //  To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_CustomsBrokerOfficeTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_CustomsBrokerOfficeTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_CustomsBrokerOfficeTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomsBrokerOfficeTableControl")).DataChanged = true;
                ((DC_CustomsBrokerOfficeTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomsBrokerOfficeTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_CustomsBrokerOfficeTableControlRow.
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

          
            ((DC_CustomsBrokerOfficeTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomsBrokerOfficeTableControl")).DataChanged = true;
            ((DC_CustomsBrokerOfficeTableControl)MiscUtils.GetParentControlObject(this, "DC_CustomsBrokerOfficeTableControl")).ResetData = true;
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
              public virtual void DC_CustomsBrokerOfficeRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/AddDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}";
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
              public virtual void DC_CustomsBrokerOfficeRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomsBrokerOfficeRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/EditDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}";
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
              public virtual void DC_CustomsBrokerOfficeRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/ShowDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}";
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
                return (string)this.ViewState["BaseDC_CustomsBrokerOfficeTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_CustomsBrokerOfficeTableControlRow_Rec"] = value;
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
           
        public System.Web.UI.WebControls.Literal BorderCrossing {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossing");
            }
        }
           
        public System.Web.UI.WebControls.Literal Client {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Client");
            }
        }
           
        public System.Web.UI.WebControls.Literal Comments {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
           
        public System.Web.UI.WebControls.Literal ContactName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactName");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomsBroker {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBroker");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomsBrokerOfficeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeId");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomsBrokerOfficeRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal Destinations {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Destinations");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email1");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email2 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email2");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email3 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email3");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email4 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email4");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email5 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email5");
            }
        }
           
        public System.Web.UI.WebControls.Literal Fax {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Fax");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone");
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
            
            if (this.RecordUniqueId != null) {
                return DC_CustomsBrokerOfficeTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_CustomsBrokerOfficeTableControl control on the ShowDC_CustomsBrokerOfficeTablePage page.
// Do not modify this class. Instead override any method in DC_CustomsBrokerOfficeTableControl.
public class BaseDC_CustomsBrokerOfficeTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_CustomsBrokerOfficeTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_CustomsBrokerOfficePagination.FirstPage.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficePagination_FirstPage_Click);
              this.DC_CustomsBrokerOfficePagination.LastPage.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficePagination_LastPage_Click);
              this.DC_CustomsBrokerOfficePagination.NextPage.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficePagination_NextPage_Click);
              this.DC_CustomsBrokerOfficePagination.PageSizeButton.Click += new EventHandler(DC_CustomsBrokerOfficePagination_PageSizeButton_Click);
            
              this.DC_CustomsBrokerOfficePagination.PreviousPage.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficePagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.BorderCrossingLabel1.Click += new EventHandler(BorderCrossingLabel1_Click);
            
              this.ClientLabel.Click += new EventHandler(ClientLabel_Click);
            
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.ContactNameLabel.Click += new EventHandler(ContactNameLabel_Click);
            
              this.CustomsBrokerLabel.Click += new EventHandler(CustomsBrokerLabel_Click);
            
              this.CustomsBrokerOfficeIdLabel1.Click += new EventHandler(CustomsBrokerOfficeIdLabel1_Click);
            
              this.DestinationsLabel.Click += new EventHandler(DestinationsLabel_Click);
            
              this.Email1Label.Click += new EventHandler(Email1Label_Click);
            
              this.Email2Label.Click += new EventHandler(Email2Label_Click);
            
              this.Email3Label.Click += new EventHandler(Email3Label_Click);
            
              this.Email4Label.Click += new EventHandler(Email4Label_Click);
            
              this.Email5Label.Click += new EventHandler(Email5Label_Click);
            
              this.FaxLabel.Click += new EventHandler(FaxLabel_Click);
            
              this.PhoneLabel.Click += new EventHandler(PhoneLabel_Click);
            

            // Setup the button events.
        
              this.DC_CustomsBrokerOfficeCopyButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeCopyButton_Click);
              this.DC_CustomsBrokerOfficeDeleteButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeDeleteButton_Click);
              this.DC_CustomsBrokerOfficeEditButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeEditButton_Click);
              this.DC_CustomsBrokerOfficeExportButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomsBrokerOfficeExportButton"), MiscUtils.GetParentControlObject(this,"DC_CustomsBrokerOfficeTableControlUpdatePanel"));
                    
              this.DC_CustomsBrokerOfficeExportExcelButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomsBrokerOfficeExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_CustomsBrokerOfficeTableControlUpdatePanel"));
                    
              this.DC_CustomsBrokerOfficeNewButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeNewButton_Click);
              this.DC_CustomsBrokerOfficePDFButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficePDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_CustomsBrokerOfficePDFButton"), MiscUtils.GetParentControlObject(this,"DC_CustomsBrokerOfficeTableControlUpdatePanel"));
                    
              this.DC_CustomsBrokerOfficeRefreshButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeRefreshButton_Click);
              this.DC_CustomsBrokerOfficeResetButton.Click += new ImageClickEventHandler(DC_CustomsBrokerOfficeResetButton_Click);
              this.DC_CustomsBrokerOfficeFilterButton.Button.Click += new EventHandler(DC_CustomsBrokerOfficeFilterButton_Click);

            // Setup the filter and search events.
        
            this.BorderCrossingFilter.SelectedIndexChanged += new EventHandler(BorderCrossingFilter_SelectedIndexChanged);
            this.CustomsBrokerFilter.SelectedIndexChanged += new EventHandler(CustomsBrokerFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.BorderCrossingFilter)) {
                this.BorderCrossingFilter.Items.Add(new ListItem(this.GetFromSession(this.BorderCrossingFilter), this.GetFromSession(this.BorderCrossingFilter)));
                this.BorderCrossingFilter.SelectedValue = this.GetFromSession(this.BorderCrossingFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomsBrokerFilter)) {
                this.CustomsBrokerFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomsBrokerFilter), this.GetFromSession(this.CustomsBrokerFilter)));
                this.CustomsBrokerFilter.SelectedValue = this.GetFromSession(this.CustomsBrokerFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomsBrokerOfficeIdFromFilter)) {
                
                this.CustomsBrokerOfficeIdFromFilter.Text = this.GetFromSession(this.CustomsBrokerOfficeIdFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomsBrokerOfficeIdToFilter)) {
                
                this.CustomsBrokerOfficeIdToFilter.Text = this.GetFromSession(this.CustomsBrokerOfficeIdToFilter);
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
                this.DC_CustomsBrokerOfficeDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_CustomsBrokerOfficeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_CustomsBrokerOfficeTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_CustomsBrokerOfficeRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_CustomsBrokerOfficeTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_CustomsBrokerOfficeRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_CustomsBrokerOfficeTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateBorderCrossingFilter(MiscUtils.GetSelectedValue(this.BorderCrossingFilter, this.GetFromSession(this.BorderCrossingFilter)), 500);
            this.PopulateCustomsBrokerFilter(MiscUtils.GetSelectedValue(this.CustomsBrokerFilter, this.GetFromSession(this.CustomsBrokerFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomsBrokerOfficeTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_CustomsBrokerOfficeTableControlRow recControl = (DC_CustomsBrokerOfficeTableControlRow)(repItem.FindControl("DC_CustomsBrokerOfficeTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_CustomsBrokerOfficeTableControl pagination.
        
            this.DC_CustomsBrokerOfficePagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_CustomsBrokerOfficePagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomsBrokerOfficePagination.LastPage.Enabled = false;
            }
          
            this.DC_CustomsBrokerOfficePagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_CustomsBrokerOfficePagination.NextPage.Enabled = false;
            }
          
            this.DC_CustomsBrokerOfficePagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_CustomsBrokerOfficePagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_CustomsBrokerOfficePagination.CurrentPage.Text = "0";
            }
            this.DC_CustomsBrokerOfficePagination.PageSize.Text = this.PageSize.ToString();
            this.DC_CustomsBrokerOfficeTotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomsBrokerOfficePagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_CustomsBrokerOfficePagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_CustomsBrokerOfficeTableControlRow recCtl in this.GetRecordControls())
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
            DC_CustomsBrokerOfficeTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.BorderCrossingFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.BorderCrossing, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.BorderCrossingFilter, this.GetFromSession(this.BorderCrossingFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBroker, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomsBrokerFilter, this.GetFromSession(this.CustomsBrokerFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFromFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFromFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdToFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdToFilter, this.GetFromSession(this.CustomsBrokerOfficeIdToFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_CustomsBrokerOfficeTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String BorderCrossingFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "BorderCrossingFilter_Ajax"];
            if (MiscUtils.IsValueSelected(BorderCrossingFilterSelectedValue)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.BorderCrossing, BaseFilter.ComparisonOperator.EqualsTo, BorderCrossingFilterSelectedValue, false, false);
            }
                      
            String CustomsBrokerFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomsBrokerFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomsBrokerFilterSelectedValue)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBroker, BaseFilter.ComparisonOperator.EqualsTo, CustomsBrokerFilterSelectedValue, false, false);
            }
                      
            String CustomsBrokerOfficeIdFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomsBrokerOfficeIdFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomsBrokerOfficeIdFromFilterSelectedValue)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, CustomsBrokerOfficeIdFromFilterSelectedValue, false, false);
            }
                      
            String CustomsBrokerOfficeIdToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomsBrokerOfficeIdToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomsBrokerOfficeIdToFilterSelectedValue)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, CustomsBrokerOfficeIdToFilterSelectedValue, false, false);
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
        
            if (this.DC_CustomsBrokerOfficePagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_CustomsBrokerOfficePagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_CustomsBrokerOfficeTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_CustomsBrokerOfficeTableControlRow recControl = (DC_CustomsBrokerOfficeTableControlRow)(repItem.FindControl("DC_CustomsBrokerOfficeTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_CustomsBrokerOfficeRecord rec = new DC_CustomsBrokerOfficeRecord();
        
                        if (recControl.BorderCrossing.Text != "") {
                            rec.Parse(recControl.BorderCrossing.Text, DC_CustomsBrokerOfficeTable.BorderCrossing);
                        }
                        if (recControl.Client.Text != "") {
                            rec.Parse(recControl.Client.Text, DC_CustomsBrokerOfficeTable.Client);
                        }
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_CustomsBrokerOfficeTable.Comments);
                        }
                        if (recControl.ContactName.Text != "") {
                            rec.Parse(recControl.ContactName.Text, DC_CustomsBrokerOfficeTable.ContactName);
                        }
                        if (recControl.CustomsBroker.Text != "") {
                            rec.Parse(recControl.CustomsBroker.Text, DC_CustomsBrokerOfficeTable.CustomsBroker);
                        }
                        if (recControl.CustomsBrokerOfficeId.Text != "") {
                            rec.Parse(recControl.CustomsBrokerOfficeId.Text, DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId);
                        }
                        if (recControl.Destinations.Text != "") {
                            rec.Parse(recControl.Destinations.Text, DC_CustomsBrokerOfficeTable.Destinations);
                        }
                        if (recControl.Email1.Text != "") {
                            rec.Parse(recControl.Email1.Text, DC_CustomsBrokerOfficeTable.Email1);
                        }
                        if (recControl.Email2.Text != "") {
                            rec.Parse(recControl.Email2.Text, DC_CustomsBrokerOfficeTable.Email2);
                        }
                        if (recControl.Email3.Text != "") {
                            rec.Parse(recControl.Email3.Text, DC_CustomsBrokerOfficeTable.Email3);
                        }
                        if (recControl.Email4.Text != "") {
                            rec.Parse(recControl.Email4.Text, DC_CustomsBrokerOfficeTable.Email4);
                        }
                        if (recControl.Email5.Text != "") {
                            rec.Parse(recControl.Email5.Text, DC_CustomsBrokerOfficeTable.Email5);
                        }
                        if (recControl.Fax.Text != "") {
                            rec.Parse(recControl.Fax.Text, DC_CustomsBrokerOfficeTable.Fax);
                        }
                        if (recControl.Phone.Text != "") {
                            rec.Parse(recControl.Phone.Text, DC_CustomsBrokerOfficeTable.Phone);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_CustomsBrokerOfficeRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_CustomsBrokerOfficeRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_CustomsBrokerOfficeTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_CustomsBrokerOfficeTableControlRow rec)            
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
        
        // Get the filters' data for BorderCrossingFilter.
        protected virtual void PopulateBorderCrossingFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomsBrokerOfficeTable.BorderCrossing, OrderByItem.OrderDir.Asc);

            string[] list = DC_CustomsBrokerOfficeTable.GetValues(DC_CustomsBrokerOfficeTable.BorderCrossing, wc, orderBy, maxItems);
            
            this.BorderCrossingFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CustomsBrokerOfficeTable.BorderCrossing.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.BorderCrossingFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.BorderCrossingFilter, selectedValue);

            // Add the All item.
            this.BorderCrossingFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for CustomsBrokerFilter.
        protected virtual void PopulateCustomsBrokerFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomsBrokerOfficeTable.CustomsBroker, OrderByItem.OrderDir.Asc);

            string[] list = DC_CustomsBrokerOfficeTable.GetValues(DC_CustomsBrokerOfficeTable.CustomsBroker, wc, orderBy, maxItems);
            
            this.CustomsBrokerFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_CustomsBrokerOfficeTable.CustomsBroker.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.CustomsBrokerFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CustomsBrokerFilter, selectedValue);

            // Add the All item.
            this.CustomsBrokerFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter BorderCrossingFilter.
        public virtual WhereClause CreateWhereClause_BorderCrossingFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CustomsBrokerFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBroker, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomsBrokerFilter, this.GetFromSession(this.CustomsBrokerFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFromFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFromFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdToFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdToFilter, this.GetFromSession(this.CustomsBrokerOfficeIdToFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter CustomsBrokerFilter.
        public virtual WhereClause CreateWhereClause_CustomsBrokerFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.BorderCrossingFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.BorderCrossing, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.BorderCrossingFilter, this.GetFromSession(this.BorderCrossingFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdFromFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdFromFilter, this.GetFromSession(this.CustomsBrokerOfficeIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomsBrokerOfficeIdToFilter)) {
                wc.iAND(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.CustomsBrokerOfficeIdToFilter, this.GetFromSession(this.CustomsBrokerOfficeIdToFilter)), false, false);
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
        
            this.SaveToSession(this.BorderCrossingFilter, this.BorderCrossingFilter.SelectedValue);
            this.SaveToSession(this.CustomsBrokerFilter, this.CustomsBrokerFilter.SelectedValue);
            this.SaveToSession(this.CustomsBrokerOfficeIdFromFilter, this.CustomsBrokerOfficeIdFromFilter.Text);
            this.SaveToSession(this.CustomsBrokerOfficeIdToFilter, this.CustomsBrokerOfficeIdToFilter.Text);
            
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
          
            this.SaveToSession("BorderCrossingFilter_Ajax", this.BorderCrossingFilter.SelectedValue);
            this.SaveToSession("CustomsBrokerFilter_Ajax", this.CustomsBrokerFilter.SelectedValue);
            this.SaveToSession("CustomsBrokerOfficeIdFromFilter_Ajax", this.CustomsBrokerOfficeIdFromFilter.Text);
            this.SaveToSession("CustomsBrokerOfficeIdToFilter_Ajax", this.CustomsBrokerOfficeIdToFilter.Text);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.BorderCrossingFilter);
            this.RemoveFromSession(this.CustomsBrokerFilter);
            this.RemoveFromSession(this.CustomsBrokerOfficeIdFromFilter);
            this.RemoveFromSession(this.CustomsBrokerOfficeIdToFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_CustomsBrokerOfficeTableControl_OrderBy"];
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
                this.ViewState["DC_CustomsBrokerOfficeTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_CustomsBrokerOfficePagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomsBrokerOfficePagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomsBrokerOfficePagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomsBrokerOfficePagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_CustomsBrokerOfficePagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_CustomsBrokerOfficePagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomsBrokerOfficePagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void BorderCrossingLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.BorderCrossing);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.BorderCrossing, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ClientLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Client);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Client, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ContactNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.ContactName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.ContactName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomsBrokerLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.CustomsBroker);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.CustomsBroker, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomsBrokerOfficeIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void DestinationsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Destinations);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Destinations, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Email1Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Email1);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Email1, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Email2Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Email2);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Email2, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Email3Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Email3);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Email3, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Email4Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Email4);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Email4, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Email5Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Email5);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Email5, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void FaxLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Fax);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Fax, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_CustomsBrokerOfficeTable.Phone);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_CustomsBrokerOfficeTable.Phone, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_CustomsBrokerOfficeCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/AddDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}";
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
              public virtual void DC_CustomsBrokerOfficeDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_CustomsBrokerOfficeEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/EditDC_CustomsBrokerOfficePage.aspx?DC_CustomsBrokerOffice={PK}";
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
              public virtual void DC_CustomsBrokerOfficeExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId,
             DC_CustomsBrokerOfficeTable.BorderCrossing,
             DC_CustomsBrokerOfficeTable.Client,
             DC_CustomsBrokerOfficeTable.Comments,
             DC_CustomsBrokerOfficeTable.ContactName,
             DC_CustomsBrokerOfficeTable.CustomsBroker,
             DC_CustomsBrokerOfficeTable.Destinations,
             DC_CustomsBrokerOfficeTable.Email1,
             DC_CustomsBrokerOfficeTable.Email2,
             DC_CustomsBrokerOfficeTable.Email3,
             DC_CustomsBrokerOfficeTable.Email4,
             DC_CustomsBrokerOfficeTable.Email5,
             DC_CustomsBrokerOfficeTable.Fax,
             DC_CustomsBrokerOfficeTable.Phone,
             null};
            ExportData rep = new ExportData(DC_CustomsBrokerOfficeTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_CustomsBrokerOfficeExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_CustomsBrokerOfficeTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.BorderCrossing, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Client, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.ContactName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.CustomsBroker, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Destinations, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Email1, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Email2, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Email3, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Email4, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Email5, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Fax, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_CustomsBrokerOfficeTable.Phone, "Default"));

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
              public virtual void DC_CustomsBrokerOfficeNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_CustomsBrokerOffice/AddDC_CustomsBrokerOfficePage.aspx";
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
              public virtual void DC_CustomsBrokerOfficePDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_CustomsBrokerOfficeTablePage.DC_CustomsBrokerOfficePDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_CustomsBrokerOffice";
                // If ShowDC_CustomsBrokerOfficeTablePage.DC_CustomsBrokerOfficePDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.Name, ReportEnum.Align.Right, "${DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.BorderCrossing.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.BorderCrossing.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Client.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Client.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Comments.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.ContactName.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.ContactName.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.CustomsBroker.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.CustomsBroker.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Destinations.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Destinations.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Email1.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Email1.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Email2.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Email2.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Email3.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Email3.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Email4.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Email4.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Email5.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Email5.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Fax.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Fax.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_CustomsBrokerOfficeTable.Phone.Name, ReportEnum.Align.Left, "${DC_CustomsBrokerOfficeTable.Phone.Name}", ReportEnum.Align.Left, 20);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_CustomsBrokerOfficeTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_CustomsBrokerOfficeTable.GetColumnList();
                DC_CustomsBrokerOfficeRecord[] records = null;
                do
                {
                    records = DC_CustomsBrokerOfficeTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_CustomsBrokerOfficeRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId.Name}", record.Format(DC_CustomsBrokerOfficeTable.CustomsBrokerOfficeId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.BorderCrossing.Name}", record.Format(DC_CustomsBrokerOfficeTable.BorderCrossing), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Client.Name}", record.Format(DC_CustomsBrokerOfficeTable.Client), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Comments.Name}", record.Format(DC_CustomsBrokerOfficeTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.ContactName.Name}", record.Format(DC_CustomsBrokerOfficeTable.ContactName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.CustomsBroker.Name}", record.Format(DC_CustomsBrokerOfficeTable.CustomsBroker), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Destinations.Name}", record.Format(DC_CustomsBrokerOfficeTable.Destinations), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Email1.Name}", record.Format(DC_CustomsBrokerOfficeTable.Email1), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Email2.Name}", record.Format(DC_CustomsBrokerOfficeTable.Email2), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Email3.Name}", record.Format(DC_CustomsBrokerOfficeTable.Email3), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Email4.Name}", record.Format(DC_CustomsBrokerOfficeTable.Email4), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Email5.Name}", record.Format(DC_CustomsBrokerOfficeTable.Email5), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Fax.Name}", record.Format(DC_CustomsBrokerOfficeTable.Fax), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_CustomsBrokerOfficeTable.Phone.Name}", record.Format(DC_CustomsBrokerOfficeTable.Phone), ReportEnum.Align.Left, 100);

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
              public virtual void DC_CustomsBrokerOfficeRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_CustomsBrokerOfficeTableControl)(this.Page.FindControlRecursively("DC_CustomsBrokerOfficeTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_CustomsBrokerOfficeResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.BorderCrossingFilter.ClearSelection();
            
              this.CustomsBrokerFilter.ClearSelection();
            
              this.CustomsBrokerOfficeIdFromFilter.Text = "";
            
              this.CustomsBrokerOfficeIdToFilter.Text = "";
            
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
              public virtual void DC_CustomsBrokerOfficeFilterButton_Click(object sender, EventArgs args)
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
        protected virtual void BorderCrossingFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void CustomsBrokerFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_CustomsBrokerOfficeRecord[] _DataSource = null;
        public  DC_CustomsBrokerOfficeRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.DropDownList BorderCrossingFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal BorderCrossingLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton BorderCrossingLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "BorderCrossingLabel1");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ClientLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ClientLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CommentsLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ContactNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomsBroker1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBroker1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CustomsBrokerFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CustomsBrokerLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerLabel");
            }
        }
        
        public System.Web.UI.WebControls.TextBox CustomsBrokerOfficeIdFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdFromFilter");
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
        
        public System.Web.UI.WebControls.TextBox CustomsBrokerOfficeIdToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomsBrokerOfficeIdToFilter");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_CustomsBrokerOfficeFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_CustomsBrokerOfficePagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficePagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficePDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficePDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_CustomsBrokerOfficeResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_CustomsBrokerOfficeTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_CustomsBrokerOfficeToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_CustomsBrokerOfficeTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_CustomsBrokerOfficeTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DestinationsLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DestinationsLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Email1Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email1Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Email2Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email2Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Email3Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email3Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Email4Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email4Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Email5Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email5Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton FaxLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FaxLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PhoneLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_CustomsBrokerOfficeTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_CustomsBrokerOfficeRecord rec = null;
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
            foreach (DC_CustomsBrokerOfficeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomsBrokerOfficeRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_CustomsBrokerOfficeTableControlRow GetSelectedRecordControl()
        {
        DC_CustomsBrokerOfficeTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_CustomsBrokerOfficeTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_CustomsBrokerOfficeTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_CustomsBrokerOfficeRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_CustomsBrokerOfficeTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomsBrokerOfficeTablePage.DC_CustomsBrokerOfficeTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_CustomsBrokerOfficeTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_CustomsBrokerOfficeTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_CustomsBrokerOfficeRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_CustomsBrokerOfficeTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_CustomsBrokerOfficeTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_CustomsBrokerOfficeTableControlRow recControl = (DC_CustomsBrokerOfficeTableControlRow)repItem.FindControl("DC_CustomsBrokerOfficeTableControlRow");
                recList.Add(recControl);
            }

            return (DC_CustomsBrokerOfficeTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_CustomsBrokerOfficeTablePage.DC_CustomsBrokerOfficeTableControlRow"));
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

  