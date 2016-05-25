
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_TransporterPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_TransporterPage
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

  
public class DC_TransporterRecordControl : BaseDC_TransporterRecordControl
{
      
        // The BaseDC_TransporterRecordControl implements the LoadData, DataBind and other
        // methods to load and display the data in a table control.

        // This is the ideal place to add your code customizations. For example, you can override the LoadData, 
        // CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.
        

}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_TransporterRecordControl control on the ShowDC_TransporterPage page.
// Do not modify this class. Instead override any method in DC_TransporterRecordControl.
public class BaseDC_TransporterRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_TransporterRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_TransporterRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_TransporterDialogEditButton.Click += new ImageClickEventHandler(DC_TransporterDialogEditButton_Click);
        }

        // To customize, override this method in DC_TransporterRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
        }

        // Read data from database. To customize, override this method in DC_TransporterRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_TransporterTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_TransporterRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_TransporterRecord[] recList = DC_TransporterTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_TransporterTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_TransporterRecordControl.
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

        //  To customize, override this method in DC_TransporterRecordControl.
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
        
            // 2. Validate the data.  Override in DC_TransporterRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_TransporterRecordControl to set additional fields.
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

        //  To customize, override this method in DC_TransporterRecordControl.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_TransporterRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_TransporterTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["DC_Transporter"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "DC_Transporter"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_TransporterTable.TransporterId).ToString());
            } else {
                
                wc.iAND(DC_TransporterTable.TransporterId, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_TransporterRecordControl.
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
              public virtual void DC_TransporterDialogEditButton_Click(object sender, ImageClickEventArgs args)
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
                return (string)this.ViewState["BaseDC_TransporterRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_TransporterRecordControl_Rec"] = value;
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
        
        public System.Web.UI.WebControls.Literal CarrierNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CarrierNameLabel");
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
           
        public System.Web.UI.WebControls.Literal ContactName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactName");
            }
        }
        
        public System.Web.UI.WebControls.Literal ContactNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ContactNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_TransporterDialogEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterDialogEditButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_TransporterDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_TransporterDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.Literal Email {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Email");
            }
        }
        
        public System.Web.UI.WebControls.Literal EmailLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "EmailLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Fax {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Fax");
            }
        }
        
        public System.Web.UI.WebControls.Literal FaxLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "FaxLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal IRSNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "IRSNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal IRSNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "IRSNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone1");
            }
        }
        
        public System.Web.UI.WebControls.Literal Phone1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone1Label");
            }
        }
           
        public System.Web.UI.WebControls.Literal Phone2 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone2");
            }
        }
        
        public System.Web.UI.WebControls.Literal Phone2Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Phone2Label");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneCell1 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell1");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneCell1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell1Label");
            }
        }
           
        public System.Web.UI.WebControls.Literal PhoneCell2 {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell2");
            }
        }
        
        public System.Web.UI.WebControls.Literal PhoneCell2Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PhoneCell2Label");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate1GTAMiltonWhitby {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate1GTAMiltonWhitby");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate1GTAMiltonWhitbyLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate1GTAMiltonWhitbyLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate2Cambridge {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate2Cambridge");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate2CambridgeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate2CambridgeLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate3Ottawa {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate3Ottawa");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate3OttawaLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate3OttawaLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate4Montreal {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate4Montreal");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate4MontrealLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate4MontrealLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate5Quebec {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate5Quebec");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate5QuebecLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate5QuebecLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate6Moncton {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate6Moncton");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate6MonctonLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate6MonctonLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate7Debert {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate7Debert");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate7DebertLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate7DebertLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal Rate8Other {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate8Other");
            }
        }
        
        public System.Web.UI.WebControls.Literal Rate8OtherLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Rate8OtherLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal TransporterId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterId");
            }
        }
        
        public System.Web.UI.WebControls.Literal TransporterIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.Literal USBondNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "USBondNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal USBondNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "USBondNumLabel");
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

  

#endregion
    
  
}

  