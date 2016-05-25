
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// CancelDC_OrderPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.CancelDC_OrderPage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_OrderRecordControl : BaseDC_OrderRecordControl
{
	public DC_OrderRecordControl()
	{          
		// The BaseDC_OrderRecordControl implements the LoadData, DataBind and other
		// methods to load and display the data in a table control.
		
		// This is the ideal place to add your code customizations. For example, you can override the LoadData, 
		// CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.

	}

	// This method allows adding of additional logic after data is retrieved from the 
	// User Interface - but before it is saved into the database.    
	public override void GetUIData() 
	{
		base.GetUIData();
		
		string sToListDC = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListDC");
		string sToListPort = System.Configuration.ConfigurationManager.AppSettings.Get("ePortToListPort");
		DC_OrderRecord myRecord = this.GetRecord();
		string sOrderNum = myRecord.OrderNum.Trim();

		// Update status to Cancelled
		myRecord.OrderStatusId = 10;

		// BN - Send the cancellation email
		try 
		{
			BaseClasses.Utils.MailSender email = new BaseClasses.Utils.MailSender();
			email.AddFrom("eport2@port.state.de.us");
			email.AddTo(sToListDC + ", " + sToListPort);
			//email.AddBCC("bnigam@port.state.de.us");
			email.SetSubject("Order: " + sOrderNum + " : Order Cancelled at: " + DateTime.Now.ToShortDateString() + " " + DateTime.Now.ToShortTimeString());
			email.SetContent("The order has been cancelled. Order No. : " + myRecord.OrderNum.ToString());
			//email.AddAttachment(strFile1);
			email.SendMessage();
		}
		catch (System.Exception ex)
		{
			BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "UNIQUE_SCRIPTKEY", ex.Message);
		}
	}

}

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderRecordControl control on the CancelDC_OrderPage page.
// Do not modify this class. Instead override any method in DC_OrderRecordControl.
public class BaseDC_OrderRecordControl : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_OrderRecordControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                  this.Page.Authorize((Control)OrderNum, "NO_ACCESS");
            
        }

        // Read data from database. To customize, override this method in DC_OrderRecordControl.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_OrderTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            WhereClause wc = this.CreateWhereClause();
            if (wc == null) {
                this.DataSource = new DC_OrderRecord();
                return;
            }

            // Retrieve the record from the database.
            DC_OrderRecord[] recList = DC_OrderTable.GetRecords(wc, null, 0, 2);
            if (recList.Length == 0) {
                throw new Exception(Page.GetResourceValue("Err:NoRecRetrieved", "ePortDC"));
            }

            
                    this.DataSource = DC_OrderTable.GetRecord(recList[0].GetID().ToXmlString(), true);
                  
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_OrderRecordControl.
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

        
            if (this.DataSource.CommentsCancelSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CommentsCancel);
                this.CommentsCancel.Text = formattedValue;
                        
            } else {  
                this.CommentsCancel.Text = DC_OrderTable.CommentsCancel.Format(DC_OrderTable.CommentsCancel.DefaultValue);
            }
                    
            if (this.DataSource.OrderNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderNum);
                this.OrderNum.Text = formattedValue;
                        
            } else {  
                this.OrderNum.Text = DC_OrderTable.OrderNum.Format(DC_OrderTable.OrderNum.DefaultValue);
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

        //  To customize, override this method in DC_OrderRecordControl.
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
        
            // 2. Validate the data.  Override in DC_OrderRecordControl to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_OrderRecordControl to set additional fields.
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

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual void GetUIData()
        {
        
            this.DataSource.Parse(this.CommentsCancel.Text, DC_OrderTable.CommentsCancel);
                          
            this.DataSource.Parse(this.OrderNum.Text, DC_OrderTable.OrderNum);
                          
        }

        //  To customize, override this method in DC_OrderRecordControl.
        public virtual WhereClause CreateWhereClause()
        {
        
            WhereClause wc;
            DC_OrderTable.Instance.InnerFilter = null;
            wc = new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            
            // Retrieve the record id from the URL parameter.
              
            string recId = this.Page.Request.QueryString["OrderNum"];
                
            if (recId == null || recId.Length == 0) {
                // Get the error message from the application resource file.
                throw new Exception(Page.GetResourceValue("Err:UrlParamMissing", "ePortDC").Replace("{URL}", "OrderNum"));
            }
            HttpContext.Current.Session["SelectedID"] = recId;
              
            if (KeyValue.IsXmlKey(recId)) {
                KeyValue pkValue = KeyValue.XmlToKey(recId);
                
                wc.iAND(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, pkValue.GetColumnValue(DC_OrderTable.OrderNum).ToString());
            } else {
                
                wc.iAND(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.EqualsTo, recId);
            }
              
            return wc;
          
        }
        

        //  To customize, override this method in DC_OrderRecordControl.
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
            DC_OrderTable.DeleteRecord(pk);

          
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
                return (string)this.ViewState["BaseDC_OrderRecordControl_Rec"];
            }
            set {
                this.ViewState["BaseDC_OrderRecordControl_Rec"] = value;
            }
        }
        
        private DC_OrderRecord _DataSource;
        public DC_OrderRecord DataSource {
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
           
        public System.Web.UI.WebControls.TextBox CommentsCancel {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsCancel");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommentsCancelLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsCancelLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.TextBox OrderNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNum");
            }
        }
        
        public System.Web.UI.WebControls.Label OrderNumInfoLabel {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumInfoLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumLabel");
            }
        }
        
#endregion

#region "Helper Functions"
    
        public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
      
        {
            DC_OrderRecord rec = null;
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

        public DC_OrderRecord GetRecord()
        {
        
            if (this.DataSource != null) {
                return this.DataSource;
            }
            
            if (this.RecordUniqueId != null) {
                return DC_OrderTable.GetRecord(this.RecordUniqueId, true);
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

  