
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// PARSDC_OrderPage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.PARSDC_OrderPage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_OrderRecordControl : BaseDC_OrderRecordControl
{
	// The BaseDC_OrderRecordControl implements the LoadData, DataBind and other
	// methods to load and display the data in a table control.
	
	// This is the ideal place to add your code customizations. For example, you can override the LoadData, 
	// CreateWhereClause, DataBind, SaveData, GetUIData, and Validate methods.

	public DC_OrderRecordControl()
	{          
		this.Load += new EventHandler(RecordControl_Load);
	}

	// Occurs when the server control is loaded into the Page object.
	private void RecordControl_Load(object sender, EventArgs e)
	{
        if (this.PARSETABorder.Text.Trim() == "")
        {
            this.PARSETABorder.Text = DateTime.Now.AddHours(8).ToString("yyyy-MM-dd HH:MM");
        }
	}

	protected override void PopulatePARSPortOfEntryNumDropDownList(string selectedValue, int maxItems)
	{
	
	    // Call the base.PopulatePARSPortOfEntryNumDropDownList to populate the dropdown list
	    base.PopulatePARSPortOfEntryNumDropDownList(selectedValue, maxItems);
	
		// Add value in front of the description of all items
		foreach (ListItem li in this.PARSPortOfEntryNum.Items)
		{
			if (li.Text != "** Please Select **")
		    li.Text = li.Text + " (" + li.Value + ")";
		}
	}   
}

  

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderRecordControl control on the PARSDC_OrderPage page.
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
        
              this.PARSPortOfEntryNum.SelectedIndexChanged += new EventHandler(PARSPortOfEntryNum_SelectedIndexChanged);
            
              this.TransporterId.SelectedIndexChanged += new EventHandler(TransporterId_SelectedIndexChanged);
            
        }

        // To customize, override this method in DC_OrderRecordControl.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
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

        
            if (this.DataSource.CommodityCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CommodityCode);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CommodityCode.Text = formattedValue;
                        
            } else {  
                this.CommodityCode.Text = DC_OrderTable.CommodityCode.Format(DC_OrderTable.CommodityCode.DefaultValue);
            }
                    
            if (this.DataSource.ConsigneeIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.ConsigneeId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.ConsigneeId.Text = formattedValue;
                        
            } else {  
                this.ConsigneeId.Text = DC_OrderTable.ConsigneeId.Format(DC_OrderTable.ConsigneeId.DefaultValue);
            }
                    
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_OrderTable.CustomerId.Format(DC_OrderTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.CustomerPOSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerPO);
                this.CustomerPO.Text = formattedValue;
                        
            } else {  
                this.CustomerPO.Text = DC_OrderTable.CustomerPO.Format(DC_OrderTable.CustomerPO.DefaultValue);
            }
                    
            if (this.DataSource.DeliveryDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DeliveryDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.DeliveryDate.Text = formattedValue;
                        
            } else {  
                this.DeliveryDate.Text = DC_OrderTable.DeliveryDate.Format(DC_OrderTable.DeliveryDate.DefaultValue);
            }
                    
            if (this.DataSource.DriverNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DriverName);
                this.DriverName.Text = formattedValue;
                        
            } else {  
                this.DriverName.Text = DC_OrderTable.DriverName.Format(DC_OrderTable.DriverName.DefaultValue);
            }
                    
            if (this.DataSource.OrderNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderNum.Text = formattedValue;
                        
            } else {  
                this.OrderNum.Text = DC_OrderTable.OrderNum.Format(DC_OrderTable.OrderNum.DefaultValue);
            }
                    
            if (this.DataSource.OrderStatusIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.OrderStatusId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.OrderStatusId.Text = formattedValue;
                        
            } else {  
                this.OrderStatusId.Text = DC_OrderTable.OrderStatusId.Format(DC_OrderTable.OrderStatusId.DefaultValue);
            }
                    
            if (this.DataSource.PARSBarCodeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PARSBarCode);
                this.PARSBarCode.Text = formattedValue;
                        
            } else {  
                this.PARSBarCode.Text = DC_OrderTable.PARSBarCode.Format(DC_OrderTable.PARSBarCode.DefaultValue);
            }
                    
            if (this.DataSource.PARSCarrierDispatchPhoneSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PARSCarrierDispatchPhone);
                this.PARSCarrierDispatchPhone.Text = formattedValue;
                        
            } else {  
                this.PARSCarrierDispatchPhone.Text = DC_OrderTable.PARSCarrierDispatchPhone.Format(DC_OrderTable.PARSCarrierDispatchPhone.DefaultValue);
            }
                    
            if (this.DataSource.PARSDriverPhoneMobileSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PARSDriverPhoneMobile);
                this.PARSDriverPhoneMobile.Text = formattedValue;
                        
            } else {  
                this.PARSDriverPhoneMobile.Text = DC_OrderTable.PARSDriverPhoneMobile.Format(DC_OrderTable.PARSDriverPhoneMobile.DefaultValue);
            }
                    
            if (this.DataSource.PARSETABorderSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PARSETABorder, @"yyyy-MM-dd HH:MM");
                this.PARSETABorder.Text = formattedValue;
                        
            } else {  
                this.PARSETABorder.Text = DC_OrderTable.PARSETABorder.Format(DC_OrderTable.PARSETABorder.DefaultValue, @"yyyy-MM-dd HH:MM");
            }
                    
            if (this.DataSource.PARSPortOfEntryNumSpecified) {
                this.PopulatePARSPortOfEntryNumDropDownList(this.DataSource.PARSPortOfEntryNum, 200);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulatePARSPortOfEntryNumDropDownList(DC_OrderTable.PARSPortOfEntryNum.DefaultValue, 200);
                } else {
                this.PopulatePARSPortOfEntryNumDropDownList(null, 200);
                }
            }
                
            if (this.DataSource.PickUpDateSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.PickUpDate);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.PickUpDate.Text = formattedValue;
                        
            } else {  
                this.PickUpDate.Text = DC_OrderTable.PickUpDate.Format(DC_OrderTable.PickUpDate.DefaultValue);
            }
                    
            if (this.DataSource.TotalCountSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalCount);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalCount.Text = formattedValue;
                        
            } else {  
                this.TotalCount.Text = DC_OrderTable.TotalCount.Format(DC_OrderTable.TotalCount.DefaultValue);
            }
                    
            if (this.DataSource.TotalPalletCountSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalPalletCount);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalPalletCount.Text = formattedValue;
                        
            } else {  
                this.TotalPalletCount.Text = DC_OrderTable.TotalPalletCount.Format(DC_OrderTable.TotalPalletCount.DefaultValue);
            }
                    
            if (this.DataSource.TrailerNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TrailerNum);
                this.TrailerNum.Text = formattedValue;
                        
            } else {  
                this.TrailerNum.Text = DC_OrderTable.TrailerNum.Format(DC_OrderTable.TrailerNum.DefaultValue);
            }
                    
            if (this.DataSource.TransporterIdSpecified) {
                this.PopulateTransporterIdDropDownList(this.DataSource.TransporterId.ToString(), 100);
            } else {
                if (!this.DataSource.IsCreated) {
                    this.PopulateTransporterIdDropDownList(DC_OrderTable.TransporterId.DefaultValue, 100);
                } else {
                this.PopulateTransporterIdDropDownList(null, 100);
                }
            }
                
            if (this.DataSource.TruckTagSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TruckTag);
                this.TruckTag.Text = formattedValue;
                        
            } else {  
                this.TruckTag.Text = DC_OrderTable.TruckTag.Format(DC_OrderTable.TruckTag.DefaultValue);
            }
                    
            if (this.DataSource.VesselIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.VesselId);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.VesselId.Text = formattedValue;
                        
            } else {  
                this.VesselId.Text = DC_OrderTable.VesselId.Format(DC_OrderTable.VesselId.DefaultValue);
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
        
            this.DataSource.Parse(this.CustomerPO.Text, DC_OrderTable.CustomerPO);
                          
            this.DataSource.Parse(this.DriverName.Text, DC_OrderTable.DriverName);
                          
            this.DataSource.Parse(this.PARSBarCode.Text, DC_OrderTable.PARSBarCode);
                          
            this.DataSource.Parse(this.PARSCarrierDispatchPhone.Text, DC_OrderTable.PARSCarrierDispatchPhone);
                          
            this.DataSource.Parse(this.PARSDriverPhoneMobile.Text, DC_OrderTable.PARSDriverPhoneMobile);
                          
            this.DataSource.Parse(this.PARSETABorder.Text, DC_OrderTable.PARSETABorder);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.PARSPortOfEntryNum), DC_OrderTable.PARSPortOfEntryNum);
                  
            this.DataSource.Parse(this.TrailerNum.Text, DC_OrderTable.TrailerNum);
                          
            this.DataSource.Parse(MiscUtils.GetValueSelectedPageRequest(this.TransporterId), DC_OrderTable.TransporterId);
                  
            this.DataSource.Parse(this.TruckTag.Text, DC_OrderTable.TruckTag);
                          
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
        
        public virtual WhereClause CreateWhereClause_PARSPortOfEntryNumDropDownList() {
            return new WhereClause();
        }
                
        public virtual WhereClause CreateWhereClause_TransporterIdDropDownList() {
            return new WhereClause();
        }
                
        // Fill the PARSPortOfEntryNum list.
        protected virtual void PopulatePARSPortOfEntryNumDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_PARSPortOfEntryNumDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_PortOfEntryTable.PortName, OrderByItem.OrderDir.Asc);

                      this.PARSPortOfEntryNum.Items.Clear();
            foreach (DC_PortOfEntryRecord itemValue in DC_PortOfEntryTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.PortCodeSpecified) {
                    cvalue = itemValue.PortCode;
                    fvalue = itemValue.Format(DC_PortOfEntryTable.PortName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.PARSPortOfEntryNum.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.PARSPortOfEntryNum, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.PARSPortOfEntryNum, DC_OrderTable.PARSPortOfEntryNum.Format(selectedValue))) {
                string fvalue = DC_OrderTable.PARSPortOfEntryNum.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.PARSPortOfEntryNum.Items.Insert(0, item);
            }

                  
            this.PARSPortOfEntryNum.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
        // Fill the TransporterId list.
        protected virtual void PopulateTransporterIdDropDownList
                (string selectedValue, int maxItems) {
                  
            //Setup the WHERE clause.
            WhereClause wc = CreateWhereClause_TransporterIdDropDownList();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_TransporterTable.CarrierName, OrderByItem.OrderDir.Asc);

                      this.TransporterId.Items.Clear();
            foreach (DC_TransporterRecord itemValue in DC_TransporterTable.GetRecords(wc, orderBy, 0, maxItems)) {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = null;
                if (itemValue.TransporterIdSpecified) {
                    cvalue = itemValue.TransporterId.ToString();
                    fvalue = itemValue.Format(DC_TransporterTable.CarrierName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                this.TransporterId.Items.Add(item);
            }
                    
            // Setup the selected item.
            if (selectedValue != null &&
                selectedValue.Length > 0 &&
                !MiscUtils.SetSelectedValue(this.TransporterId, selectedValue) &&
                !MiscUtils.SetSelectedValue(this.TransporterId, DC_OrderTable.TransporterId.Format(selectedValue))) {
                string fvalue = DC_OrderTable.TransporterId.Format(selectedValue);
                ListItem item = new ListItem(fvalue, selectedValue);
                item.Selected = true;
                this.TransporterId.Items.Insert(0, item);
            }

                  
            this.TransporterId.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:PleaseSelect", "ePortDC"), "--PLEASE_SELECT--"));
                  
        }
                
              protected virtual void PARSPortOfEntryNum_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.PARSPortOfEntryNum);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.PARSPortOfEntryNum, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.PARSPortOfEntryNum, DC_OrderTable.PARSPortOfEntryNum.Format(selectedValue))) {
              string fvalue = DC_OrderTable.PARSPortOfEntryNum.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.PARSPortOfEntryNum.Items.Insert(0, item);
              }
              }
            
              protected virtual void TransporterId_SelectedIndexChanged(object sender, EventArgs args)
              {
              string selectedValue = MiscUtils.GetValueSelectedPageRequest(this.TransporterId);
              if (selectedValue != null &&
              selectedValue.Length > 0 &&
              !MiscUtils.SetSelectedValue(this.TransporterId, selectedValue) &&
              !MiscUtils.SetSelectedValue(this.TransporterId, DC_OrderTable.TransporterId.Format(selectedValue))) {
              string fvalue = DC_OrderTable.TransporterId.Format(selectedValue);
              ListItem item = new ListItem(fvalue, selectedValue);
              item.Selected = true;
              this.TransporterId.Items.Insert(0, item);
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
           
        public System.Web.UI.WebControls.Label CommodityCode {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label ConsigneeId {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
        
        public System.Web.UI.WebControls.Literal ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label CustomerId {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox CustomerPO {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPO");
            }
        }
        
        public System.Web.UI.WebControls.Literal CustomerPOLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPOLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderDialogTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDialogTitle");
            }
        }
           
        public System.Web.UI.WebControls.Label DeliveryDate {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal DeliveryDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox DriverName {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverName");
            }
        }
        
        public System.Web.UI.WebControls.Literal DriverNameLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverNameLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label OrderNum {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label OrderStatusId {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusId");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderStatusIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PARSBarCode {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSBarCode");
            }
        }
        
        public System.Web.UI.WebControls.Literal PARSBarCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSBarCodeLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PARSCarrierDispatchPhone {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSCarrierDispatchPhone");
            }
        }
        
        public System.Web.UI.WebControls.Literal PARSCarrierDispatchPhoneLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSCarrierDispatchPhoneLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PARSDriverPhoneMobile {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSDriverPhoneMobile");
            }
        }
        
        public System.Web.UI.WebControls.Literal PARSDriverPhoneMobileLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSDriverPhoneMobileLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox PARSETABorder {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSETABorder");
            }
        }
        
        public System.Web.UI.WebControls.Literal PARSETABorderLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSETABorderLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList PARSPortOfEntryNum {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSPortOfEntryNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal PARSPortOfEntryNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PARSPortOfEntryNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label PickUpDate {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDate");
            }
        }
        
        public System.Web.UI.WebControls.Literal PickUpDateLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalCount {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCount");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalCountLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCountLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label TotalPalletCount {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCount");
            }
        }
        
        public System.Web.UI.WebControls.Literal TotalPalletCountLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCountLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox TrailerNum {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNum");
            }
        }
        
        public System.Web.UI.WebControls.Literal TrailerNumLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNumLabel");
            }
        }
           
        public System.Web.UI.WebControls.DropDownList TransporterId {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterId");
            }
        }
        
        public System.Web.UI.WebControls.Literal TransporterIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel");
            }
        }
           
        public System.Web.UI.WebControls.TextBox TruckTag {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTag");
            }
        }
        
        public System.Web.UI.WebControls.Literal TruckTagLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTagLabel");
            }
        }
           
        public System.Web.UI.WebControls.Label VesselId {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselId");
            }
        }
        
        public System.Web.UI.WebControls.Literal VesselIdLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselIdLabel");
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

  