
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_TransporterTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_TransporterTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_TransporterTableControlRow : BaseDC_TransporterTableControlRow
{
      
        // The BaseDC_TransporterTableControlRow implements code for a ROW within the
        // the DC_TransporterTableControl table.  The BaseDC_TransporterTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_TransporterTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_TransporterTableControl : BaseDC_TransporterTableControl
{
        // The BaseDC_TransporterTableControl class implements the LoadData, DataBind, CreateWhereClause
        // and other methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
        // The DC_TransporterTableControlRow class offers another place where you can customize
        // the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_TransporterTableControlRow control on the ShowDC_TransporterTablePage page.
// Do not modify this class. Instead override any method in DC_TransporterTableControlRow.
public class BaseDC_TransporterTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_TransporterTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_TransporterTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_TransporterRecordRowCopyButton.Click += new ImageClickEventHandler(DC_TransporterRecordRowCopyButton_Click);
              this.DC_TransporterRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_TransporterRecordRowDeleteButton_Click);
              this.DC_TransporterRecordRowEditButton.Click += new ImageClickEventHandler(DC_TransporterRecordRowEditButton_Click);
              this.DC_TransporterRecordRowViewButton.Click += new ImageClickEventHandler(DC_TransporterRecordRowViewButton_Click);
        }

        // To customize, override this method in DC_TransporterTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                // Show confirmation message on Click
                this.DC_TransporterRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_TransporterTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_TransporterTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_TransporterTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_TransporterRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_TransporterTableControlRow.
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

        
            if (this.DataSource.CarrierNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.CarrierName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CarrierName.Text = formattedValue;
                        
            } else {  
                this.CarrierName.Text = DC_TransporterTable.CarrierName.Format(DC_TransporterTable.CarrierName.DefaultValue);
            }
                    
            if (this.CarrierName.Text == null ||
                this.CarrierName.Text.Trim().Length == 0) {
                this.CarrierName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Comments);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_TransporterTable.Comments.Format(DC_TransporterTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
            if (this.DataSource.ContactNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.ContactName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ContactName.Text = formattedValue;
                        
            } else {  
                this.ContactName.Text = DC_TransporterTable.ContactName.Format(DC_TransporterTable.ContactName.DefaultValue);
            }
                    
            if (this.ContactName.Text == null ||
                this.ContactName.Text.Trim().Length == 0) {
                this.ContactName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.EmailSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Email);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Email.Text = formattedValue;
                        
            } else {  
                this.Email.Text = DC_TransporterTable.Email.Format(DC_TransporterTable.Email.DefaultValue);
            }
                    
            if (this.Email.Text == null ||
                this.Email.Text.Trim().Length == 0) {
                this.Email.Text = "&nbsp;";
            }
                  
            if (this.DataSource.FaxSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Fax);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Fax.Text = formattedValue;
                        
            } else {  
                this.Fax.Text = DC_TransporterTable.Fax.Format(DC_TransporterTable.Fax.DefaultValue);
            }
                    
            if (this.Fax.Text == null ||
                this.Fax.Text.Trim().Length == 0) {
                this.Fax.Text = "&nbsp;";
            }
                  
            if (this.DataSource.IRSNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.IRSNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.IRSNum.Text = formattedValue;
                        
            } else {  
                this.IRSNum.Text = DC_TransporterTable.IRSNum.Format(DC_TransporterTable.IRSNum.DefaultValue);
            }
                    
            if (this.IRSNum.Text == null ||
                this.IRSNum.Text.Trim().Length == 0) {
                this.IRSNum.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Phone1Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Phone1);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Phone1.Text = formattedValue;
                        
            } else {  
                this.Phone1.Text = DC_TransporterTable.Phone1.Format(DC_TransporterTable.Phone1.DefaultValue);
            }
                    
            if (this.Phone1.Text == null ||
                this.Phone1.Text.Trim().Length == 0) {
                this.Phone1.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Phone2Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Phone2);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Phone2.Text = formattedValue;
                        
            } else {  
                this.Phone2.Text = DC_TransporterTable.Phone2.Format(DC_TransporterTable.Phone2.DefaultValue);
            }
                    
            if (this.Phone2.Text == null ||
                this.Phone2.Text.Trim().Length == 0) {
                this.Phone2.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneCell1Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.PhoneCell1);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PhoneCell1.Text = formattedValue;
                        
            } else {  
                this.PhoneCell1.Text = DC_TransporterTable.PhoneCell1.Format(DC_TransporterTable.PhoneCell1.DefaultValue);
            }
                    
            if (this.PhoneCell1.Text == null ||
                this.PhoneCell1.Text.Trim().Length == 0) {
                this.PhoneCell1.Text = "&nbsp;";
            }
                  
            if (this.DataSource.PhoneCell2Specified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.PhoneCell2);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PhoneCell2.Text = formattedValue;
                        
            } else {  
                this.PhoneCell2.Text = DC_TransporterTable.PhoneCell2.Format(DC_TransporterTable.PhoneCell2.DefaultValue);
            }
                    
            if (this.PhoneCell2.Text == null ||
                this.PhoneCell2.Text.Trim().Length == 0) {
                this.PhoneCell2.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate1GTAMiltonWhitbySpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate1GTAMiltonWhitby);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate1GTAMiltonWhitby.Text = formattedValue;
                        
            } else {  
                this.Rate1GTAMiltonWhitby.Text = DC_TransporterTable.Rate1GTAMiltonWhitby.Format(DC_TransporterTable.Rate1GTAMiltonWhitby.DefaultValue);
            }
                    
            if (this.Rate1GTAMiltonWhitby.Text == null ||
                this.Rate1GTAMiltonWhitby.Text.Trim().Length == 0) {
                this.Rate1GTAMiltonWhitby.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate2CambridgeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate2Cambridge);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate2Cambridge.Text = formattedValue;
                        
            } else {  
                this.Rate2Cambridge.Text = DC_TransporterTable.Rate2Cambridge.Format(DC_TransporterTable.Rate2Cambridge.DefaultValue);
            }
                    
            if (this.Rate2Cambridge.Text == null ||
                this.Rate2Cambridge.Text.Trim().Length == 0) {
                this.Rate2Cambridge.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate3OttawaSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate3Ottawa);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate3Ottawa.Text = formattedValue;
                        
            } else {  
                this.Rate3Ottawa.Text = DC_TransporterTable.Rate3Ottawa.Format(DC_TransporterTable.Rate3Ottawa.DefaultValue);
            }
                    
            if (this.Rate3Ottawa.Text == null ||
                this.Rate3Ottawa.Text.Trim().Length == 0) {
                this.Rate3Ottawa.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate4MontrealSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate4Montreal);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate4Montreal.Text = formattedValue;
                        
            } else {  
                this.Rate4Montreal.Text = DC_TransporterTable.Rate4Montreal.Format(DC_TransporterTable.Rate4Montreal.DefaultValue);
            }
                    
            if (this.Rate4Montreal.Text == null ||
                this.Rate4Montreal.Text.Trim().Length == 0) {
                this.Rate4Montreal.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate5QuebecSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate5Quebec);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate5Quebec.Text = formattedValue;
                        
            } else {  
                this.Rate5Quebec.Text = DC_TransporterTable.Rate5Quebec.Format(DC_TransporterTable.Rate5Quebec.DefaultValue);
            }
                    
            if (this.Rate5Quebec.Text == null ||
                this.Rate5Quebec.Text.Trim().Length == 0) {
                this.Rate5Quebec.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate6MonctonSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate6Moncton);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate6Moncton.Text = formattedValue;
                        
            } else {  
                this.Rate6Moncton.Text = DC_TransporterTable.Rate6Moncton.Format(DC_TransporterTable.Rate6Moncton.DefaultValue);
            }
                    
            if (this.Rate6Moncton.Text == null ||
                this.Rate6Moncton.Text.Trim().Length == 0) {
                this.Rate6Moncton.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate7DebertSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate7Debert);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate7Debert.Text = formattedValue;
                        
            } else {  
                this.Rate7Debert.Text = DC_TransporterTable.Rate7Debert.Format(DC_TransporterTable.Rate7Debert.DefaultValue);
            }
                    
            if (this.Rate7Debert.Text == null ||
                this.Rate7Debert.Text.Trim().Length == 0) {
                this.Rate7Debert.Text = "&nbsp;";
            }
                  
            if (this.DataSource.Rate8OtherSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.Rate8Other);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Rate8Other.Text = formattedValue;
                        
            } else {  
                this.Rate8Other.Text = DC_TransporterTable.Rate8Other.Format(DC_TransporterTable.Rate8Other.DefaultValue);
            }
                    
            if (this.Rate8Other.Text == null ||
                this.Rate8Other.Text.Trim().Length == 0) {
                this.Rate8Other.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TransporterIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.TransporterId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TransporterId.Text = formattedValue;
                        
            } else {  
                this.TransporterId.Text = DC_TransporterTable.TransporterId.Format(DC_TransporterTable.TransporterId.DefaultValue);
            }
                    
            if (this.TransporterId.Text == null ||
                this.TransporterId.Text.Trim().Length == 0) {
                this.TransporterId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.USBondNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_TransporterTable.USBondNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.USBondNum.Text = formattedValue;
                        
            } else {  
                this.USBondNum.Text = DC_TransporterTable.USBondNum.Format(DC_TransporterTable.USBondNum.DefaultValue);
            }
                    
            if (this.USBondNum.Text == null ||
                this.USBondNum.Text.Trim().Length == 0) {
                this.USBondNum.Text = "&nbsp;";
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

        //  To customize, override this method in DC_TransporterTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_TransporterTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_TransporterTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_TransporterTableControl)MiscUtils.GetParentControlObject(this, "DC_TransporterTableControl")).DataChanged = true;
                ((DC_TransporterTableControl)MiscUtils.GetParentControlObject(this, "DC_TransporterTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_TransporterTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_TransporterTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_TransporterTableControlRow.
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
            DC_TransporterTable.DeleteRecord(pk);

          
            ((DC_TransporterTableControl)MiscUtils.GetParentControlObject(this, "DC_TransporterTableControl")).DataChanged = true;
            ((DC_TransporterTableControl)MiscUtils.GetParentControlObject(this, "DC_TransporterTableControl")).ResetData = true;
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
              public virtual void DC_TransporterRecordRowCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/AddDC_TransporterPage.aspx?DC_Transporter={PK}";
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
              public virtual void DC_TransporterRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_TransporterRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/EditDC_TransporterPage.aspx?DC_Transporter={PK}";
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
              public virtual void DC_TransporterRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/ShowDC_TransporterPage.aspx?DC_Transporter={PK}";
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
                return (string)this.ViewState["BaseDC_TransporterTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_TransporterTableControlRow_Rec"] = value;
            }
        }
        
        private DC_TransporterRecord _DataSource;
        public DC_TransporterRecord DataSource {
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
           
        public System.Web.UI.WebControls.Literal CarrierName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CarrierName");
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
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterRecordRowCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRecordRowCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_TransporterRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email");
            }
        }
           
        public System.Web.UI.WebControls.Literal Fax {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Fax");
            }
        }
           
        public System.Web.UI.WebControls.Literal IRSNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "IRSNum");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone1");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone2 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone2");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneCell1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell1");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneCell2 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell2");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate1GTAMiltonWhitby {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate1GTAMiltonWhitby");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate2Cambridge {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate2Cambridge");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate3Ottawa {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate3Ottawa");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate4Montreal {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate4Montreal");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate5Quebec {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate5Quebec");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate6Moncton {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate6Moncton");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate7Debert {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate7Debert");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate8Other {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate8Other");
            }
        }
           
        public System.Web.UI.WebControls.Literal TransporterId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterId");
            }
        }
           
        public System.Web.UI.WebControls.Literal USBondNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "USBondNum");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_TransporterRecord rec = null;
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

        public DC_TransporterRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_TransporterTable.GetRecord(this.RecordUniqueId, true);
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

  
// Base class for the DC_TransporterTableControl control on the ShowDC_TransporterTablePage page.
// Do not modify this class. Instead override any method in DC_TransporterTableControl.
public class BaseDC_TransporterTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_TransporterTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_TransporterPagination.FirstPage.Click += new ImageClickEventHandler(DC_TransporterPagination_FirstPage_Click);
              this.DC_TransporterPagination.LastPage.Click += new ImageClickEventHandler(DC_TransporterPagination_LastPage_Click);
              this.DC_TransporterPagination.NextPage.Click += new ImageClickEventHandler(DC_TransporterPagination_NextPage_Click);
              this.DC_TransporterPagination.PageSizeButton.Click += new EventHandler(DC_TransporterPagination_PageSizeButton_Click);
            
              this.DC_TransporterPagination.PreviousPage.Click += new ImageClickEventHandler(DC_TransporterPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.CarrierNameLabel1.Click += new EventHandler(CarrierNameLabel1_Click);
            
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.ContactNameLabel.Click += new EventHandler(ContactNameLabel_Click);
            
              this.EmailLabel.Click += new EventHandler(EmailLabel_Click);
            
              this.FaxLabel.Click += new EventHandler(FaxLabel_Click);
            
              this.IRSNumLabel.Click += new EventHandler(IRSNumLabel_Click);
            
              this.Phone1Label.Click += new EventHandler(Phone1Label_Click);
            
              this.Phone2Label.Click += new EventHandler(Phone2Label_Click);
            
              this.PhoneCell1Label.Click += new EventHandler(PhoneCell1Label_Click);
            
              this.PhoneCell2Label.Click += new EventHandler(PhoneCell2Label_Click);
            
              this.Rate1GTAMiltonWhitbyLabel.Click += new EventHandler(Rate1GTAMiltonWhitbyLabel_Click);
            
              this.Rate2CambridgeLabel.Click += new EventHandler(Rate2CambridgeLabel_Click);
            
              this.Rate3OttawaLabel.Click += new EventHandler(Rate3OttawaLabel_Click);
            
              this.Rate4MontrealLabel.Click += new EventHandler(Rate4MontrealLabel_Click);
            
              this.Rate5QuebecLabel.Click += new EventHandler(Rate5QuebecLabel_Click);
            
              this.Rate6MonctonLabel.Click += new EventHandler(Rate6MonctonLabel_Click);
            
              this.Rate7DebertLabel.Click += new EventHandler(Rate7DebertLabel_Click);
            
              this.Rate8OtherLabel.Click += new EventHandler(Rate8OtherLabel_Click);
            
              this.TransporterIdLabel1.Click += new EventHandler(TransporterIdLabel1_Click);
            
              this.USBondNumLabel.Click += new EventHandler(USBondNumLabel_Click);
            

            // Setup the button events.
        
              this.DC_TransporterCopyButton.Click += new ImageClickEventHandler(DC_TransporterCopyButton_Click);
              this.DC_TransporterDeleteButton.Click += new ImageClickEventHandler(DC_TransporterDeleteButton_Click);
              this.DC_TransporterEditButton.Click += new ImageClickEventHandler(DC_TransporterEditButton_Click);
              this.DC_TransporterExportButton.Click += new ImageClickEventHandler(DC_TransporterExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_TransporterExportButton"), MiscUtils.GetParentControlObject(this,"DC_TransporterTableControlUpdatePanel"));
                    
              this.DC_TransporterExportExcelButton.Click += new ImageClickEventHandler(DC_TransporterExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_TransporterExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_TransporterTableControlUpdatePanel"));
                    
              this.DC_TransporterNewButton.Click += new ImageClickEventHandler(DC_TransporterNewButton_Click);
              this.DC_TransporterPDFButton.Click += new ImageClickEventHandler(DC_TransporterPDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_TransporterPDFButton"), MiscUtils.GetParentControlObject(this,"DC_TransporterTableControlUpdatePanel"));
                    
              this.DC_TransporterRefreshButton.Click += new ImageClickEventHandler(DC_TransporterRefreshButton_Click);
              this.DC_TransporterResetButton.Click += new ImageClickEventHandler(DC_TransporterResetButton_Click);
              this.DC_TransporterFilterButton.Button.Click += new EventHandler(DC_TransporterFilterButton_Click);

            // Setup the filter and search events.
        
            this.CarrierNameFilter.SelectedIndexChanged += new EventHandler(CarrierNameFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.CarrierNameFilter)) {
                this.CarrierNameFilter.Items.Add(new ListItem(this.GetFromSession(this.CarrierNameFilter), this.GetFromSession(this.CarrierNameFilter)));
                this.CarrierNameFilter.SelectedValue = this.GetFromSession(this.CarrierNameFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.TransporterIdFromFilter)) {
                
                this.TransporterIdFromFilter.Text = this.GetFromSession(this.TransporterIdFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.TransporterIdToFilter)) {
                
                this.TransporterIdToFilter.Text = this.GetFromSession(this.TransporterIdToFilter);
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
                this.DC_TransporterDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_TransporterRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_TransporterTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_TransporterRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_TransporterTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_TransporterRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_TransporterTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCarrierNameFilter(MiscUtils.GetSelectedValue(this.CarrierNameFilter, this.GetFromSession(this.CarrierNameFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_TransporterTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_TransporterTableControlRow recControl = (DC_TransporterTableControlRow)(repItem.FindControl("DC_TransporterTableControlRow"));
                recControl.DataSource = this.DataSource[index];
                recControl.DataBind();
                recControl.Visible = !this.InDeletedRecordIds(recControl);
                index += 1;
            }
        }

         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_TransporterTableControl pagination.
        
            this.DC_TransporterPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_TransporterPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_TransporterPagination.LastPage.Enabled = false;
            }
          
            this.DC_TransporterPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_TransporterPagination.NextPage.Enabled = false;
            }
          
            this.DC_TransporterPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_TransporterPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_TransporterPagination.CurrentPage.Text = "0";
            }
            this.DC_TransporterPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_TransporterTotalItems.Text = this.TotalRecords.ToString();
            this.DC_TransporterPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_TransporterPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_TransporterTableControlRow recCtl in this.GetRecordControls())
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
            DC_TransporterTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.CarrierNameFilter)) {
                wc.iAND(DC_TransporterTable.CarrierName, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CarrierNameFilter, this.GetFromSession(this.CarrierNameFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TransporterIdFromFilter)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.TransporterIdFromFilter, this.GetFromSession(this.TransporterIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TransporterIdToFilter)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.TransporterIdToFilter, this.GetFromSession(this.TransporterIdToFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_TransporterTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String CarrierNameFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CarrierNameFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CarrierNameFilterSelectedValue)) {
                wc.iAND(DC_TransporterTable.CarrierName, BaseFilter.ComparisonOperator.EqualsTo, CarrierNameFilterSelectedValue, false, false);
            }
                      
            String TransporterIdFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "TransporterIdFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(TransporterIdFromFilterSelectedValue)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, TransporterIdFromFilterSelectedValue, false, false);
            }
                      
            String TransporterIdToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "TransporterIdToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(TransporterIdToFilterSelectedValue)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, TransporterIdToFilterSelectedValue, false, false);
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
        
            if (this.DC_TransporterPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_TransporterPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_TransporterTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_TransporterTableControlRow recControl = (DC_TransporterTableControlRow)(repItem.FindControl("DC_TransporterTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_TransporterRecord rec = new DC_TransporterRecord();
        
                        if (recControl.CarrierName.Text != "") {
                            rec.Parse(recControl.CarrierName.Text, DC_TransporterTable.CarrierName);
                        }
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_TransporterTable.Comments);
                        }
                        if (recControl.ContactName.Text != "") {
                            rec.Parse(recControl.ContactName.Text, DC_TransporterTable.ContactName);
                        }
                        if (recControl.Email.Text != "") {
                            rec.Parse(recControl.Email.Text, DC_TransporterTable.Email);
                        }
                        if (recControl.Fax.Text != "") {
                            rec.Parse(recControl.Fax.Text, DC_TransporterTable.Fax);
                        }
                        if (recControl.IRSNum.Text != "") {
                            rec.Parse(recControl.IRSNum.Text, DC_TransporterTable.IRSNum);
                        }
                        if (recControl.Phone1.Text != "") {
                            rec.Parse(recControl.Phone1.Text, DC_TransporterTable.Phone1);
                        }
                        if (recControl.Phone2.Text != "") {
                            rec.Parse(recControl.Phone2.Text, DC_TransporterTable.Phone2);
                        }
                        if (recControl.PhoneCell1.Text != "") {
                            rec.Parse(recControl.PhoneCell1.Text, DC_TransporterTable.PhoneCell1);
                        }
                        if (recControl.PhoneCell2.Text != "") {
                            rec.Parse(recControl.PhoneCell2.Text, DC_TransporterTable.PhoneCell2);
                        }
                        if (recControl.Rate1GTAMiltonWhitby.Text != "") {
                            rec.Parse(recControl.Rate1GTAMiltonWhitby.Text, DC_TransporterTable.Rate1GTAMiltonWhitby);
                        }
                        if (recControl.Rate2Cambridge.Text != "") {
                            rec.Parse(recControl.Rate2Cambridge.Text, DC_TransporterTable.Rate2Cambridge);
                        }
                        if (recControl.Rate3Ottawa.Text != "") {
                            rec.Parse(recControl.Rate3Ottawa.Text, DC_TransporterTable.Rate3Ottawa);
                        }
                        if (recControl.Rate4Montreal.Text != "") {
                            rec.Parse(recControl.Rate4Montreal.Text, DC_TransporterTable.Rate4Montreal);
                        }
                        if (recControl.Rate5Quebec.Text != "") {
                            rec.Parse(recControl.Rate5Quebec.Text, DC_TransporterTable.Rate5Quebec);
                        }
                        if (recControl.Rate6Moncton.Text != "") {
                            rec.Parse(recControl.Rate6Moncton.Text, DC_TransporterTable.Rate6Moncton);
                        }
                        if (recControl.Rate7Debert.Text != "") {
                            rec.Parse(recControl.Rate7Debert.Text, DC_TransporterTable.Rate7Debert);
                        }
                        if (recControl.Rate8Other.Text != "") {
                            rec.Parse(recControl.Rate8Other.Text, DC_TransporterTable.Rate8Other);
                        }
                        if (recControl.TransporterId.Text != "") {
                            rec.Parse(recControl.TransporterId.Text, DC_TransporterTable.TransporterId);
                        }
                        if (recControl.USBondNum.Text != "") {
                            rec.Parse(recControl.USBondNum.Text, DC_TransporterTable.USBondNum);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_TransporterRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_TransporterRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_TransporterTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_TransporterTableControlRow rec)            
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
        
        // Get the filters' data for CarrierNameFilter.
        protected virtual void PopulateCarrierNameFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_TransporterTable.CarrierName, OrderByItem.OrderDir.Asc);

            string[] list = DC_TransporterTable.GetValues(DC_TransporterTable.CarrierName, wc, orderBy, maxItems);
            
            this.CarrierNameFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_TransporterTable.CarrierName.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.CarrierNameFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CarrierNameFilter, selectedValue);

            // Add the All item.
            this.CarrierNameFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CarrierNameFilter.
        public virtual WhereClause CreateWhereClause_CarrierNameFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.TransporterIdFromFilter)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, MiscUtils.GetSelectedValue(this.TransporterIdFromFilter, this.GetFromSession(this.TransporterIdFromFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TransporterIdToFilter)) {
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, MiscUtils.GetSelectedValue(this.TransporterIdToFilter, this.GetFromSession(this.TransporterIdToFilter)), false, false);
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
        
            this.SaveToSession(this.CarrierNameFilter, this.CarrierNameFilter.SelectedValue);
            this.SaveToSession(this.TransporterIdFromFilter, this.TransporterIdFromFilter.Text);
            this.SaveToSession(this.TransporterIdToFilter, this.TransporterIdToFilter.Text);
            
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
          
            this.SaveToSession("CarrierNameFilter_Ajax", this.CarrierNameFilter.SelectedValue);
            this.SaveToSession("TransporterIdFromFilter_Ajax", this.TransporterIdFromFilter.Text);
            this.SaveToSession("TransporterIdToFilter_Ajax", this.TransporterIdToFilter.Text);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.CarrierNameFilter);
            this.RemoveFromSession(this.TransporterIdFromFilter);
            this.RemoveFromSession(this.TransporterIdToFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_TransporterTableControl_OrderBy"];
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
                this.ViewState["DC_TransporterTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_TransporterPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_TransporterPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_TransporterPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_TransporterPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_TransporterPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_TransporterPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_TransporterPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void CarrierNameLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.CarrierName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.CarrierName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ContactNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.ContactName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.ContactName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void EmailLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Email);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Email, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void FaxLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Fax);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Fax, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void IRSNumLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.IRSNum);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.IRSNum, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Phone1Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Phone1);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Phone1, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Phone2Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Phone2);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Phone2, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneCell1Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.PhoneCell1);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.PhoneCell1, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PhoneCell2Label_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.PhoneCell2);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.PhoneCell2, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate1GTAMiltonWhitbyLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate1GTAMiltonWhitby);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate1GTAMiltonWhitby, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate2CambridgeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate2Cambridge);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate2Cambridge, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate3OttawaLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate3Ottawa);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate3Ottawa, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate4MontrealLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate4Montreal);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate4Montreal, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate5QuebecLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate5Quebec);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate5Quebec, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate6MonctonLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate6Moncton);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate6Moncton, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate7DebertLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate7Debert);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate7Debert, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void Rate8OtherLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.Rate8Other);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.Rate8Other, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TransporterIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.TransporterId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.TransporterId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void USBondNumLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_TransporterTable.USBondNum);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_TransporterTable.USBondNum, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_TransporterCopyButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/AddDC_TransporterPage.aspx?DC_Transporter={PK}";
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
              public virtual void DC_TransporterDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_TransporterEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/EditDC_TransporterPage.aspx?DC_Transporter={PK}";
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
              public virtual void DC_TransporterExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_TransporterTable.TransporterId,
             DC_TransporterTable.CarrierName,
             DC_TransporterTable.Comments,
             DC_TransporterTable.ContactName,
             DC_TransporterTable.Email,
             DC_TransporterTable.Fax,
             DC_TransporterTable.IRSNum,
             DC_TransporterTable.Phone1,
             DC_TransporterTable.Phone2,
             DC_TransporterTable.PhoneCell1,
             DC_TransporterTable.PhoneCell2,
             DC_TransporterTable.Rate1GTAMiltonWhitby,
             DC_TransporterTable.Rate2Cambridge,
             DC_TransporterTable.Rate3Ottawa,
             DC_TransporterTable.Rate4Montreal,
             DC_TransporterTable.Rate5Quebec,
             DC_TransporterTable.Rate6Moncton,
             DC_TransporterTable.Rate7Debert,
             DC_TransporterTable.Rate8Other,
             DC_TransporterTable.USBondNum,
             null};
            ExportData rep = new ExportData(DC_TransporterTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_TransporterExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_TransporterTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.TransporterId, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.CarrierName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.ContactName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Email, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Fax, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.IRSNum, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Phone1, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Phone2, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.PhoneCell1, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.PhoneCell2, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate1GTAMiltonWhitby, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate2Cambridge, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate3Ottawa, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate4Montreal, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate5Quebec, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate6Moncton, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate7Debert, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.Rate8Other, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_TransporterTable.USBondNum, "Default"));

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
              public virtual void DC_TransporterNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Transporter/AddDC_TransporterPage.aspx";
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
              public virtual void DC_TransporterPDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_TransporterTablePage.DC_TransporterPDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Transporter";
                // If ShowDC_TransporterTablePage.DC_TransporterPDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_TransporterTable.TransporterId.Name, ReportEnum.Align.Right, "${DC_TransporterTable.TransporterId.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_TransporterTable.CarrierName.Name, ReportEnum.Align.Left, "${DC_TransporterTable.CarrierName.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_TransporterTable.Comments.Name, ReportEnum.Align.Left, "${DC_TransporterTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_TransporterTable.ContactName.Name, ReportEnum.Align.Left, "${DC_TransporterTable.ContactName.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_TransporterTable.Email.Name, ReportEnum.Align.Left, "${DC_TransporterTable.Email.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_TransporterTable.Fax.Name, ReportEnum.Align.Left, "${DC_TransporterTable.Fax.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_TransporterTable.IRSNum.Name, ReportEnum.Align.Left, "${DC_TransporterTable.IRSNum.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_TransporterTable.Phone1.Name, ReportEnum.Align.Left, "${DC_TransporterTable.Phone1.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_TransporterTable.Phone2.Name, ReportEnum.Align.Left, "${DC_TransporterTable.Phone2.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_TransporterTable.PhoneCell1.Name, ReportEnum.Align.Left, "${DC_TransporterTable.PhoneCell1.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_TransporterTable.PhoneCell2.Name, ReportEnum.Align.Left, "${DC_TransporterTable.PhoneCell2.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_TransporterTable.Rate1GTAMiltonWhitby.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate1GTAMiltonWhitby.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate2Cambridge.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate2Cambridge.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate3Ottawa.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate3Ottawa.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate4Montreal.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate4Montreal.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate5Quebec.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate5Quebec.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate6Moncton.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate6Moncton.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate7Debert.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate7Debert.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.Rate8Other.Name, ReportEnum.Align.Right, "${DC_TransporterTable.Rate8Other.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_TransporterTable.USBondNum.Name, ReportEnum.Align.Left, "${DC_TransporterTable.USBondNum.Name}", ReportEnum.Align.Left, 15);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_TransporterTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_TransporterTable.GetColumnList();
                DC_TransporterRecord[] records = null;
                do
                {
                    records = DC_TransporterTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_TransporterRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_TransporterTable.TransporterId.Name}", record.Format(DC_TransporterTable.TransporterId), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.CarrierName.Name}", record.Format(DC_TransporterTable.CarrierName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Comments.Name}", record.Format(DC_TransporterTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.ContactName.Name}", record.Format(DC_TransporterTable.ContactName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Email.Name}", record.Format(DC_TransporterTable.Email), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Fax.Name}", record.Format(DC_TransporterTable.Fax), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.IRSNum.Name}", record.Format(DC_TransporterTable.IRSNum), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Phone1.Name}", record.Format(DC_TransporterTable.Phone1), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Phone2.Name}", record.Format(DC_TransporterTable.Phone2), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.PhoneCell1.Name}", record.Format(DC_TransporterTable.PhoneCell1), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.PhoneCell2.Name}", record.Format(DC_TransporterTable.PhoneCell2), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_TransporterTable.Rate1GTAMiltonWhitby.Name}", record.Format(DC_TransporterTable.Rate1GTAMiltonWhitby), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate2Cambridge.Name}", record.Format(DC_TransporterTable.Rate2Cambridge), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate3Ottawa.Name}", record.Format(DC_TransporterTable.Rate3Ottawa), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate4Montreal.Name}", record.Format(DC_TransporterTable.Rate4Montreal), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate5Quebec.Name}", record.Format(DC_TransporterTable.Rate5Quebec), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate6Moncton.Name}", record.Format(DC_TransporterTable.Rate6Moncton), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate7Debert.Name}", record.Format(DC_TransporterTable.Rate7Debert), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.Rate8Other.Name}", record.Format(DC_TransporterTable.Rate8Other), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_TransporterTable.USBondNum.Name}", record.Format(DC_TransporterTable.USBondNum), ReportEnum.Align.Left, 100);

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
              public virtual void DC_TransporterRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_TransporterTableControl)(this.Page.FindControlRecursively("DC_TransporterTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_TransporterResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CarrierNameFilter.ClearSelection();
            
              this.TransporterIdFromFilter.Text = "";
            
              this.TransporterIdToFilter.Text = "";
            
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
              public virtual void DC_TransporterFilterButton_Click(object sender, EventArgs args)
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
        protected virtual void CarrierNameFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_TransporterRecord[] _DataSource = null;
        public  DC_TransporterRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.DropDownList CarrierNameFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CarrierNameFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CarrierNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CarrierNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CarrierNameLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CarrierNameLabel1");
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
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterCopyButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterCopyButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_TransporterFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_TransporterPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterPDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterPDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterResetButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_TransporterTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_TransporterToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_TransporterTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton EmailLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EmailLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton FaxLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FaxLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton IRSNumLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "IRSNumLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Phone1Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone1Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Phone2Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone2Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PhoneCell1Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell1Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PhoneCell2Label {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell2Label");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate1GTAMiltonWhitbyLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate1GTAMiltonWhitbyLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate2CambridgeLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate2CambridgeLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate3OttawaLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate3OttawaLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate4MontrealLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate4MontrealLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate5QuebecLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate5QuebecLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate6MonctonLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate6MonctonLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate7DebertLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate7DebertLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton Rate8OtherLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate8OtherLabel");
            }
        }
        
        public System.Web.UI.WebControls.TextBox TransporterIdFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdFromFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal TransporterIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TransporterIdLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel1");
            }
        }
        
        public System.Web.UI.WebControls.TextBox TransporterIdToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdToFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton USBondNumLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "USBondNumLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_TransporterTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_TransporterRecord rec = null;
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
            foreach (DC_TransporterTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_TransporterRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_TransporterTableControlRow GetSelectedRecordControl()
        {
        DC_TransporterTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_TransporterTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_TransporterTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_TransporterRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_TransporterTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_TransporterTablePage.DC_TransporterTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_TransporterTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_TransporterTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_TransporterRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_TransporterTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_TransporterTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_TransporterTableControlRow recControl = (DC_TransporterTableControlRow)repItem.FindControl("DC_TransporterTableControlRow");
                recList.Add(recControl);
            }

            return (DC_TransporterTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_TransporterTablePage.DC_TransporterTableControlRow"));
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

  