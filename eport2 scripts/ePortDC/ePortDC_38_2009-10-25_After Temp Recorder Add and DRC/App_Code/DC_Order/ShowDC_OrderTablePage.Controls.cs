
// This file implements the TableControl, TableControlRow, and RecordControl classes for the 
// ShowDC_OrderTablePage.aspx page.  The Row or RecordControl classes are the 
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

  
namespace ePortDC.UI.Controls.ShowDC_OrderTablePage
{
  

#region "Section 1: Place your customizations here."

    
public class DC_OrderTableControlRow : BaseDC_OrderTableControlRow
{
      
        // The BaseDC_OrderTableControlRow implements code for a ROW within the
        // the DC_OrderTableControl table.  The BaseDC_OrderTableControlRow implements the DataBind and SaveData methods.
        // The loading of data is actually performed by the LoadData method in the base class of DC_OrderTableControl.

        // This is the ideal place to add your code customizations. For example, you can override the DataBind, 
        // SaveData, GetUIData, and Validate methods.
        

}

  

public class DC_OrderTableControl : BaseDC_OrderTableControl
{
	// The BaseDC_OrderTableControl class implements the LoadData, DataBind, CreateWhereClause
	// and other methods to load and display the data in a table control.
	
	// This is the ideal place to add your code customizations. You can override the LoadData and CreateWhereClause,
	// The DC_OrderTableControlRow class offers another place where you can customize
	// the DataBind, GetUIData, SaveData and Validate methods specific to each row displayed on the table.

	public DC_OrderTableControl()
	{   
		// To limit the date filter with today's date only       
		this.Load += new EventHandler(DC_OrderTableControl_Load);
	}

	private void DC_OrderTableControl_Load(object sender, System.EventArgs e)
	{
		// Get Logged in User's Role
		string sRole = this.Page.SystemUtils.GetUserRole();
		
		if(!this.Page.IsPostBack)
		{
			if ( sRole.Contains(";EX") )
			{
                // Get the listItem with Order Status 2 - Submitted and select it
                this.OrderStatusIdFilter.SelectedItem.Selected = false;
                ListItem li = this.OrderStatusIdFilter.Items.FindByValue("2");
                if (li != null)
                {
                    li.Selected = true;
                }
			}
			else
			{
				this.PickUpDateFromFilter.Text = DateTime.Now.ToShortDateString();
				this.PickUpDateToFilter.Text = DateTime.Now.ToShortDateString();
			}
		}
	}

	// Limit the orders shown to only for today
	public override WhereClause CreateWhereClause()
	{
		// Get Logged in User's Role
		string sRole = this.Page.SystemUtils.GetUserRole();

		WhereClause wc = base.CreateWhereClause();
		if ((wc == null))
		{
			wc = new WhereClause();
		}
		if (!this.Page.IsPostBack)
		{
			if ( sRole.Contains(";EX") )
			{
				wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, "2");
			}
			else
			{
				wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, DateTime.Now.ToShortDateString() + " 00:00:00");
				wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, DateTime.Now.ToShortDateString() + " 23:59:59");
			}
		}
		return wc;
	}

}

#endregion

  

#region "Section 2: Do not modify this section."
    
    
// Base class for the DC_OrderTableControlRow control on the ShowDC_OrderTablePage page.
// Do not modify this class. Instead override any method in DC_OrderTableControlRow.
public class BaseDC_OrderTableControlRow : ePortDC.UI.BaseApplicationRecordControl
{
        public BaseDC_OrderTableControlRow()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        // To customize, override this method in DC_OrderTableControlRow.
        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Register the event handlers.
        
              this.DC_OrderRecordRowDeleteButton.Click += new ImageClickEventHandler(DC_OrderRecordRowDeleteButton_Click);
              this.DC_OrderRecordRowEditButton.Click += new ImageClickEventHandler(DC_OrderRecordRowEditButton_Click);
              this.DC_OrderRecordRowPicklistButton.Click += new ImageClickEventHandler(DC_OrderRecordRowPicklistButton_Click);
              this.DC_OrderRecordRowViewButton.Click += new ImageClickEventHandler(DC_OrderRecordRowViewButton_Click);
              this.CustomerId.Click += new EventHandler(CustomerId_Click);
            
              this.TransporterId.Click += new EventHandler(TransporterId_Click);
            
        }

        // To customize, override this method in DC_OrderTableControlRow.
        protected virtual void Control_Load(object sender, System.EventArgs e)
        {
        
                  this.Page.Authorize((Control)DC_OrderRecordRowDeleteButton, "ADMIN;PORTADMIN");
            
                  this.Page.Authorize((Control)DC_OrderRecordRowPicklistButton, "ADMIN;EX;PORTADMIN");
            
                // Show confirmation message on Click
                this.DC_OrderRecordRowDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteRecordConfirm", "ePortDC") + "'));");
        }

        // Read data from database. To customize, override this method in DC_OrderTableControlRow.
        public virtual void LoadData()  
        {
        
            if (this.RecordUniqueId != null && this.RecordUniqueId.Length > 0) {
                this.DataSource = DC_OrderTable.GetRecord(this.RecordUniqueId, true);
                return;
            }
        
            // Since this is a row in the table, the data for this row is loaded by the 
            // LoadData method of the BaseDC_OrderTableControl when the data for the entire
            // table is loaded.
            this.DataSource = new DC_OrderRecord();
          
        }

        // Populate the UI controls using the DataSource. To customize, override this method in DC_OrderTableControlRow.
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

        
            if (this.DataSource.CommentsSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.Comments);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.Comments.Text = formattedValue;
                        
            } else {  
                this.Comments.Text = DC_OrderTable.Comments.Format(DC_OrderTable.Comments.DefaultValue);
            }
                    
            if (this.Comments.Text == null ||
                this.Comments.Text.Trim().Length == 0) {
                this.Comments.Text = "&nbsp;";
            }
                  
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
                    
            if (this.CommodityCode.Text == null ||
                this.CommodityCode.Text.Trim().Length == 0) {
                this.CommodityCode.Text = "&nbsp;";
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
                    
            if (this.ConsigneeId.Text == null ||
                this.ConsigneeId.Text.Trim().Length == 0) {
                this.ConsigneeId.Text = "&nbsp;";
            }
                  
            if (this.DataSource.CustomerIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerId);
                this.CustomerId.Text = formattedValue;
                        
            } else {  
                this.CustomerId.Text = DC_OrderTable.CustomerId.Format(DC_OrderTable.CustomerId.DefaultValue);
            }
                    
            if (this.DataSource.CustomerPOSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.CustomerPO);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.CustomerPO.Text = formattedValue;
                        
            } else {  
                this.CustomerPO.Text = DC_OrderTable.CustomerPO.Format(DC_OrderTable.CustomerPO.DefaultValue);
            }
                    
            if (this.CustomerPO.Text == null ||
                this.CustomerPO.Text.Trim().Length == 0) {
                this.CustomerPO.Text = "&nbsp;";
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
                    
            if (this.DeliveryDate.Text == null ||
                this.DeliveryDate.Text.Trim().Length == 0) {
                this.DeliveryDate.Text = "&nbsp;";
            }
                  
            if (this.DataSource.DirectOrderSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DirectOrder);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.DirectOrder.Text = formattedValue;
                        
            } else {  
                this.DirectOrder.Text = DC_OrderTable.DirectOrder.Format(DC_OrderTable.DirectOrder.DefaultValue);
            }
                    
            if (this.DirectOrder.Text == null ||
                this.DirectOrder.Text.Trim().Length == 0) {
                this.DirectOrder.Text = "&nbsp;";
            }
                  
            if (this.DataSource.DriverNameSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.DriverName);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.DriverName.Text = formattedValue;
                        
            } else {  
                this.DriverName.Text = DC_OrderTable.DriverName.Format(DC_OrderTable.DriverName.DefaultValue);
            }
                    
            if (this.DriverName.Text == null ||
                this.DriverName.Text.Trim().Length == 0) {
                this.DriverName.Text = "&nbsp;";
            }
                  
            if (this.DataSource.LoadTypeSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.LoadType);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.LoadType.Text = formattedValue;
                        
            } else {  
                this.LoadType.Text = DC_OrderTable.LoadType.Format(DC_OrderTable.LoadType.DefaultValue);
            }
                    
            if (this.LoadType.Text == null ||
                this.LoadType.Text.Trim().Length == 0) {
                this.LoadType.Text = "&nbsp;";
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
                    
            if (this.OrderNum.Text == null ||
                this.OrderNum.Text.Trim().Length == 0) {
                this.OrderNum.Text = "&nbsp;";
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
                    
            if (this.OrderStatusId.Text == null ||
                this.OrderStatusId.Text.Trim().Length == 0) {
                this.OrderStatusId.Text = "&nbsp;";
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
                    
            if (this.PickUpDate.Text == null ||
                this.PickUpDate.Text.Trim().Length == 0) {
                this.PickUpDate.Text = "&nbsp;";
            }
                  
            if (this.DataSource.SNMGNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.SNMGNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.SNMGNum.Text = formattedValue;
                        
            } else {  
                this.SNMGNum.Text = DC_OrderTable.SNMGNum.Format(DC_OrderTable.SNMGNum.DefaultValue);
            }
                    
            if (this.SNMGNum.Text == null ||
                this.SNMGNum.Text.Trim().Length == 0) {
                this.SNMGNum.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TEStatusSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TEStatus);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TEStatus.Text = formattedValue;
                        
            } else {  
                this.TEStatus.Text = DC_OrderTable.TEStatus.Format(DC_OrderTable.TEStatus.DefaultValue);
            }
                    
            if (this.TEStatus.Text == null ||
                this.TEStatus.Text.Trim().Length == 0) {
                this.TEStatus.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TotalBoxDamagedSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalBoxDamaged);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalBoxDamaged.Text = formattedValue;
                        
            } else {  
                this.TotalBoxDamaged.Text = DC_OrderTable.TotalBoxDamaged.Format(DC_OrderTable.TotalBoxDamaged.DefaultValue);
            }
                    
            if (this.TotalBoxDamaged.Text == null ||
                this.TotalBoxDamaged.Text.Trim().Length == 0) {
                this.TotalBoxDamaged.Text = "&nbsp;";
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
                    
            if (this.TotalCount.Text == null ||
                this.TotalCount.Text.Trim().Length == 0) {
                this.TotalCount.Text = "&nbsp;";
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
                    
            if (this.TotalPalletCount.Text == null ||
                this.TotalPalletCount.Text.Trim().Length == 0) {
                this.TotalPalletCount.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TotalPriceSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalPrice);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalPrice.Text = formattedValue;
                        
            } else {  
                this.TotalPrice.Text = DC_OrderTable.TotalPrice.Format(DC_OrderTable.TotalPrice.DefaultValue);
            }
                    
            if (this.TotalPrice.Text == null ||
                this.TotalPrice.Text.Trim().Length == 0) {
                this.TotalPrice.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TotalQuantityKGSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TotalQuantityKG);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TotalQuantityKG.Text = formattedValue;
                        
            } else {  
                this.TotalQuantityKG.Text = DC_OrderTable.TotalQuantityKG.Format(DC_OrderTable.TotalQuantityKG.DefaultValue);
            }
                    
            if (this.TotalQuantityKG.Text == null ||
                this.TotalQuantityKG.Text.Trim().Length == 0) {
                this.TotalQuantityKG.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TrailerNumSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TrailerNum);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TrailerNum.Text = formattedValue;
                        
            } else {  
                this.TrailerNum.Text = DC_OrderTable.TrailerNum.Format(DC_OrderTable.TrailerNum.DefaultValue);
            }
                    
            if (this.TrailerNum.Text == null ||
                this.TrailerNum.Text.Trim().Length == 0) {
                this.TrailerNum.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TransportChargesSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TransportCharges);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TransportCharges.Text = formattedValue;
                        
            } else {  
                this.TransportCharges.Text = DC_OrderTable.TransportCharges.Format(DC_OrderTable.TransportCharges.DefaultValue);
            }
                    
            if (this.TransportCharges.Text == null ||
                this.TransportCharges.Text.Trim().Length == 0) {
                this.TransportCharges.Text = "&nbsp;";
            }
                  
            if (this.DataSource.TransporterIdSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TransporterId);
                this.TransporterId.Text = formattedValue;
                        
            } else {  
                this.TransporterId.Text = DC_OrderTable.TransporterId.Format(DC_OrderTable.TransporterId.DefaultValue);
            }
                    
            if (this.DataSource.TruckTagSpecified) {
                      
                string formattedValue = this.DataSource.Format(DC_OrderTable.TruckTag);
                formattedValue = HttpUtility.HtmlEncode(formattedValue);
                if (formattedValue != null) {
                    // If formattedValue's length exceeds FieldMaxLength value (100) then, display till FieldMaxLength value
                    if (formattedValue.Length > (int)(100)){
                        formattedValue = NetUtils.EncodeStringForHtmlDisplay(formattedValue.Substring(0, 100)) + "...";
                    }
                }           
                this.TruckTag.Text = formattedValue;
                        
            } else {  
                this.TruckTag.Text = DC_OrderTable.TruckTag.Format(DC_OrderTable.TruckTag.DefaultValue);
            }
                    
            if (this.TruckTag.Text == null ||
                this.TruckTag.Text.Trim().Length == 0) {
                this.TruckTag.Text = "&nbsp;";
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
                    
            if (this.VesselId.Text == null ||
                this.VesselId.Text.Trim().Length == 0) {
                this.VesselId.Text = "&nbsp;";
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

        //  To customize, override this method in DC_OrderTableControlRow.
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
        
            // 2. Validate the data.  Override in DC_OrderTableControlRow to add custom validation.
            this.Validate();

            // 3. Set the values in the record with data from UI controls.  Override in DC_OrderTableControlRow to set additional fields.
            this.GetUIData();

            // 4. Save in the database.
            // We should not save the record if the data did not change. This
            // will save a database hit and avoid triggering any database triggers.
            if (this.DataSource.IsAnyValueChanged) {
                // Save record to database but do not commit.
                // Auto generated ids are available after saving for use by child (dependent) records.
                this.DataSource.Save();
              
                ((DC_OrderTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderTableControl")).DataChanged = true;
                ((DC_OrderTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderTableControl")).ResetData = true;
            }
            // Reseting of this.IsNewRecord is moved to Save button's click even handler.
            // this.IsNewRecord = false;
            this.DataChanged = true;
            this.ResetData = true;
            
            this.CheckSum = "";
        }

        //  To customize, override this method in DC_OrderTableControlRow.
        public virtual void GetUIData()
        {
        
        }

        //  To customize, override this method in DC_OrderTableControlRow.
        public virtual WhereClause CreateWhereClause()
        {
        
            return null;
          
        }
        

        //  To customize, override this method in DC_OrderTableControlRow.
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

          
            ((DC_OrderTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderTableControl")).DataChanged = true;
            ((DC_OrderTableControl)MiscUtils.GetParentControlObject(this, "DC_OrderTableControl")).ResetData = true;
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
              public virtual void DC_OrderRecordRowDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_OrderRecordRowEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Order/EditDC_OrderPage.aspx?DC_Order={PK}";
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
              public virtual void DC_OrderRecordRowPicklistButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"PickListDC_OrderPage.aspx?DC_Order={DC_OrderTableControlRow:FV:OrderNum}";
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
              public virtual void DC_OrderRecordRowViewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Order/ShowDC_OrderPage.aspx?DC_Order={PK}";
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
              
            string url = @"../DC_Customer/ShowDC_CustomerPage.aspx?DC_Customer={DC_OrderTableControlRow:FK:FK_Order_Customer}";
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
              public virtual void TransporterId_Click(object sender, EventArgs args)
              {
              
            string url = @"../DC_Transporter/ShowDC_TransporterPage.aspx?DC_Transporter={DC_OrderTableControlRow:FK:FK_Order_Transporter}";
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
                return (string)this.ViewState["BaseDC_OrderTableControlRow_Rec"];
            }
            set {
                this.ViewState["BaseDC_OrderTableControlRow_Rec"] = value;
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
           
        public System.Web.UI.WebControls.Literal Comments {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "Comments");
            }
        }
           
        public System.Web.UI.WebControls.Literal CommodityCode {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCode");
            }
        }
           
        public System.Web.UI.WebControls.Literal ConsigneeId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeId");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton CustomerId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerId");
            }
        }
           
        public System.Web.UI.WebControls.Literal CustomerPO {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPO");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderRecordRowDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRecordRowDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderRecordRowEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRecordRowEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderRecordRowPicklistButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRecordRowPicklistButton");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_OrderRecordRowSelection {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRecordRowSelection");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderRecordRowViewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRecordRowViewButton");
            }
        }
           
        public System.Web.UI.WebControls.Literal DeliveryDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDate");
            }
        }
           
        public System.Web.UI.WebControls.Literal DirectOrder {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DirectOrder");
            }
        }
           
        public System.Web.UI.WebControls.Literal DriverName {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverName");
            }
        }
           
        public System.Web.UI.WebControls.Literal LoadType {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadType");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNum");
            }
        }
           
        public System.Web.UI.WebControls.Literal OrderStatusId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusId");
            }
        }
           
        public System.Web.UI.WebControls.Literal PickUpDate {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDate");
            }
        }
           
        public System.Web.UI.WebControls.Literal SNMGNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SNMGNum");
            }
        }
           
        public System.Web.UI.WebControls.Literal TEStatus {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatus");
            }
        }
           
        public System.Web.UI.WebControls.Literal TotalBoxDamaged {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalBoxDamaged");
            }
        }
           
        public System.Web.UI.WebControls.Literal TotalCount {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCount");
            }
        }
           
        public System.Web.UI.WebControls.Literal TotalPalletCount {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCount");
            }
        }
           
        public System.Web.UI.WebControls.Literal TotalPrice {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPrice");
            }
        }
           
        public System.Web.UI.WebControls.Literal TotalQuantityKG {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalQuantityKG");
            }
        }
           
        public System.Web.UI.WebControls.Literal TrailerNum {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNum");
            }
        }
           
        public System.Web.UI.WebControls.Literal TransportCharges {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransportCharges");
            }
        }
           
        public System.Web.UI.WebControls.LinkButton TransporterId {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterId");
            }
        }
           
        public System.Web.UI.WebControls.Literal TruckTag {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTag");
            }
        }
           
        public System.Web.UI.WebControls.Literal VesselId {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselId");
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

  
// Base class for the DC_OrderTableControl control on the ShowDC_OrderTablePage page.
// Do not modify this class. Instead override any method in DC_OrderTableControl.
public class BaseDC_OrderTableControl : ePortDC.UI.BaseApplicationTableControl
{
        public BaseDC_OrderTableControl()
        {
            this.Init += new EventHandler(Control_Init);
            this.Load += new EventHandler(Control_Load);
            this.PreRender += new EventHandler(Control_PreRender);
        }

        protected virtual void Control_Init(object sender, System.EventArgs e)
        {
            // Setup the pagination events.
        
              this.DC_OrderPagination.FirstPage.Click += new ImageClickEventHandler(DC_OrderPagination_FirstPage_Click);
              this.DC_OrderPagination.LastPage.Click += new ImageClickEventHandler(DC_OrderPagination_LastPage_Click);
              this.DC_OrderPagination.NextPage.Click += new ImageClickEventHandler(DC_OrderPagination_NextPage_Click);
              this.DC_OrderPagination.PageSizeButton.Click += new EventHandler(DC_OrderPagination_PageSizeButton_Click);
            
              this.DC_OrderPagination.PreviousPage.Click += new ImageClickEventHandler(DC_OrderPagination_PreviousPage_Click);

            // Setup the sorting events.
        
              this.CommentsLabel.Click += new EventHandler(CommentsLabel_Click);
            
              this.CommodityCodeLabel1.Click += new EventHandler(CommodityCodeLabel1_Click);
            
              this.ConsigneeIdLabel.Click += new EventHandler(ConsigneeIdLabel_Click);
            
              this.CustomerIdLabel1.Click += new EventHandler(CustomerIdLabel1_Click);
            
              this.CustomerPOLabel.Click += new EventHandler(CustomerPOLabel_Click);
            
              this.DeliveryDateLabel.Click += new EventHandler(DeliveryDateLabel_Click);
            
              this.DirectOrderLabel.Click += new EventHandler(DirectOrderLabel_Click);
            
              this.DriverNameLabel.Click += new EventHandler(DriverNameLabel_Click);
            
              this.LoadTypeLabel.Click += new EventHandler(LoadTypeLabel_Click);
            
              this.OrderNumLabel.Click += new EventHandler(OrderNumLabel_Click);
            
              this.OrderStatusIdLabel.Click += new EventHandler(OrderStatusIdLabel_Click);
            
              this.PickUpDateLabel.Click += new EventHandler(PickUpDateLabel_Click);
            
              this.SNMGNumLabel.Click += new EventHandler(SNMGNumLabel_Click);
            
              this.TEStatusLabel.Click += new EventHandler(TEStatusLabel_Click);
            
              this.TotalBoxDamagedLabel.Click += new EventHandler(TotalBoxDamagedLabel_Click);
            
              this.TotalCountLabel.Click += new EventHandler(TotalCountLabel_Click);
            
              this.TotalPalletCountLabel.Click += new EventHandler(TotalPalletCountLabel_Click);
            
              this.TotalPriceLabel.Click += new EventHandler(TotalPriceLabel_Click);
            
              this.TotalQuantityKGLabel.Click += new EventHandler(TotalQuantityKGLabel_Click);
            
              this.TrailerNumLabel.Click += new EventHandler(TrailerNumLabel_Click);
            
              this.TransportChargesLabel.Click += new EventHandler(TransportChargesLabel_Click);
            
              this.TransporterIdLabel.Click += new EventHandler(TransporterIdLabel_Click);
            
              this.TruckTagLabel.Click += new EventHandler(TruckTagLabel_Click);
            
              this.VesselIdLabel.Click += new EventHandler(VesselIdLabel_Click);
            

            // Setup the button events.
        
              this.DC_OrderDeleteButton.Click += new ImageClickEventHandler(DC_OrderDeleteButton_Click);
              this.DC_OrderEditButton.Click += new ImageClickEventHandler(DC_OrderEditButton_Click);
              this.DC_OrderExportButton.Click += new ImageClickEventHandler(DC_OrderExportButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_OrderExportButton"), MiscUtils.GetParentControlObject(this,"DC_OrderTableControlUpdatePanel"));
                    
              this.DC_OrderExportExcelButton.Click += new ImageClickEventHandler(DC_OrderExportExcelButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_OrderExportExcelButton"), MiscUtils.GetParentControlObject(this,"DC_OrderTableControlUpdatePanel"));
                    
              this.DC_OrderNewButton.Click += new ImageClickEventHandler(DC_OrderNewButton_Click);
              this.DC_OrderPDFButton.Click += new ImageClickEventHandler(DC_OrderPDFButton_Click);
                      this.Page.RegisterPostBackTrigger(MiscUtils.FindControlRecursively(this,"DC_OrderPDFButton"), MiscUtils.GetParentControlObject(this,"DC_OrderTableControlUpdatePanel"));
                    
              this.DC_OrderRefreshButton.Click += new ImageClickEventHandler(DC_OrderRefreshButton_Click);
              this.DC_OrderResetButton.Click += new ImageClickEventHandler(DC_OrderResetButton_Click);
              this.DC_OrderFilterButton.Button.Click += new EventHandler(DC_OrderFilterButton_Click);
              this.DC_OrderSearchButton.Button.Click += new EventHandler(DC_OrderSearchButton_Click);

            // Setup the filter and search events.
        
            this.CommodityCodeFilter.SelectedIndexChanged += new EventHandler(CommodityCodeFilter_SelectedIndexChanged);
            this.CustomerIdFilter.SelectedIndexChanged += new EventHandler(CustomerIdFilter_SelectedIndexChanged);
            this.OrderStatusIdFilter.SelectedIndexChanged += new EventHandler(OrderStatusIdFilter_SelectedIndexChanged);
            this.TEStatusFilter.SelectedIndexChanged += new EventHandler(TEStatusFilter_SelectedIndexChanged);
            if (!this.Page.IsPostBack && this.InSession(this.CommodityCodeFilter)) {
                this.CommodityCodeFilter.Items.Add(new ListItem(this.GetFromSession(this.CommodityCodeFilter), this.GetFromSession(this.CommodityCodeFilter)));
                this.CommodityCodeFilter.SelectedValue = this.GetFromSession(this.CommodityCodeFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.CustomerIdFilter)) {
                this.CustomerIdFilter.Items.Add(new ListItem(this.GetFromSession(this.CustomerIdFilter), this.GetFromSession(this.CustomerIdFilter)));
                this.CustomerIdFilter.SelectedValue = this.GetFromSession(this.CustomerIdFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.DC_OrderSearchArea)) {
                
                this.DC_OrderSearchArea.Text = this.GetFromSession(this.DC_OrderSearchArea);
            }
            if (!this.Page.IsPostBack && this.InSession(this.OrderStatusIdFilter)) {
                this.OrderStatusIdFilter.Items.Add(new ListItem(this.GetFromSession(this.OrderStatusIdFilter), this.GetFromSession(this.OrderStatusIdFilter)));
                this.OrderStatusIdFilter.SelectedValue = this.GetFromSession(this.OrderStatusIdFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.PickUpDateFromFilter)) {
                
                this.PickUpDateFromFilter.Text = this.GetFromSession(this.PickUpDateFromFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.PickUpDateToFilter)) {
                
                this.PickUpDateToFilter.Text = this.GetFromSession(this.PickUpDateToFilter);
            }
            if (!this.Page.IsPostBack && this.InSession(this.TEStatusFilter)) {
                this.TEStatusFilter.Items.Add(new ListItem(this.GetFromSession(this.TEStatusFilter), this.GetFromSession(this.TEStatusFilter)));
                this.TEStatusFilter.SelectedValue = this.GetFromSession(this.TEStatusFilter);
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
        
                  this.Page.Authorize((Control)DC_OrderDeleteButton, "ADMIN;PORTADMIN");
            
                // Show confirmation message on Click
                this.DC_OrderDeleteButton.Attributes.Add("onClick", "return (confirm('" + ((BaseApplicationPage)this.Page).GetResourceValue("DeleteConfirm", "ePortDC") + "'));");
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
                    this.DataSource = (DC_OrderRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord")));
                    return;
                }

                OrderBy orderBy = CreateOrderBy();

                // Get the pagesize from the pagesize control.
                this.GetPageSize();

                // Get the total number of records to be displayed.
                this.TotalRecords = DC_OrderTable.GetRecordCount(wc);

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
                    this.DataSource = (DC_OrderRecord[])(alist.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord")));
                } else if (this.AddNewRecord > 0) {
                    // Get the records from the posted data
                    ArrayList postdata = new ArrayList(0);
                    foreach (DC_OrderTableControlRow rc in this.GetRecordControls()) {
                        if (!rc.IsNewRecord) {
                            rc.DataSource = rc.GetRecord();
                            rc.GetUIData();
                            postdata.Add(rc.DataSource);
                        }
                    }
                    this.DataSource = (DC_OrderRecord[])(postdata.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord")));
                } else {
                    // Get the records from the database
                    this.DataSource = DC_OrderTable.GetRecords(wc, orderBy, this.PageIndex, this.PageSize);
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
        
            this.PopulateCommodityCodeFilter(MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), 500);
            this.PopulateCustomerIdFilter(MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), 500);
            this.PopulateOrderStatusIdFilter(MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), 500);
            this.PopulateTEStatusFilter(MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), 500);

            // Bind the repeater with the list of records to expand the UI.
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_OrderTableControlRepeater"));
            rep.DataSource = this.DataSource;
            rep.DataBind();

            int index = 0;
            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                // Loop through all rows in the table, set its DataSource and call DataBind().
                DC_OrderTableControlRow recControl = (DC_OrderTableControlRow)(repItem.FindControl("DC_OrderTableControlRow"));
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
          
            this.Page.PregetDfkaRecords(DC_OrderTable.CommodityCode, this.DataSource);
            this.Page.PregetDfkaRecords(DC_OrderTable.ConsigneeId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_OrderTable.CustomerId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_OrderTable.OrderStatusId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_OrderTable.TransporterId, this.DataSource);
            this.Page.PregetDfkaRecords(DC_OrderTable.VesselId, this.DataSource);
        }
         

        protected virtual void BindPaginationControls()
        {
            // Setup the pagination controls.

            // Bind the buttons for DC_OrderTableControl pagination.
        
            this.DC_OrderPagination.FirstPage.Enabled = !(this.PageIndex == 0);
            this.DC_OrderPagination.LastPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_OrderPagination.LastPage.Enabled = false;
            }
          
            this.DC_OrderPagination.NextPage.Enabled = !(this.PageIndex == this.TotalPages - 1);
            if (this.TotalPages == 0) {
                this.DC_OrderPagination.NextPage.Enabled = false;
            }
          
            this.DC_OrderPagination.PreviousPage.Enabled = !(this.PageIndex == 0);

            // Bind the pagination labels.
        
            if (this.TotalPages > 0) {
                this.DC_OrderPagination.CurrentPage.Text = (this.PageIndex + 1).ToString();
            } else {
                this.DC_OrderPagination.CurrentPage.Text = "0";
            }
            this.DC_OrderPagination.PageSize.Text = this.PageSize.ToString();
            this.DC_OrderTotalItems.Text = this.TotalRecords.ToString();
            this.DC_OrderPagination.TotalItems.Text = this.TotalRecords.ToString();
            this.DC_OrderPagination.TotalPages.Text = this.TotalPages.ToString();
        }

        public virtual void SaveData()
        {
            foreach (DC_OrderTableControlRow recCtl in this.GetRecordControls())
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
            DC_OrderTable.Instance.InnerFilter = null;
            WhereClause wc = new WhereClause();
            // CreateWhereClause() Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
        
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_OrderSearchArea.Text.StartsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(3,this.DC_OrderSearchArea.Text.Length-3);
                }
                if (this.DC_OrderSearchArea.Text.EndsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(0,this.DC_OrderSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            if (MiscUtils.IsValueSelected(this.OrderStatusIdFilter)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TEStatusFilter)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), false, false);
            }
                      
            return (wc);
        }
        
         
        // This CreateWhereClause is used for loading list of suggestions for Auto Type-Ahead feature.
        public virtual WhereClause CreateWhereClause(String searchText, String fromSearchControl, String AutoTypeAheadSearch, String AutoTypeAheadWordSeparators)
        {
            DC_OrderTable.Instance.InnerFilter = null;
            WhereClause wc= new WhereClause();
            // Compose the WHERE clause consiting of:
            // 1. Static clause defined at design time.
            // 2. User selected filter criteria.
            // 3. User selected search criteria.
            String appRelativeVirtualPath = (String)HttpContext.Current.Session["AppRelatvieVirtualPath"];
          
            // Adds clauses if values are selected in Filter controls which are configured in the page.
          
            String CommodityCodeFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CommodityCodeFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CommodityCodeFilterSelectedValue)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, CommodityCodeFilterSelectedValue, false, false);
            }
                      
            String CustomerIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "CustomerIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(CustomerIdFilterSelectedValue)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, CustomerIdFilterSelectedValue, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(searchText) && fromSearchControl == "DC_OrderSearchArea") {
                String formatedSearchText = searchText;
                // strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (searchText.StartsWith("...")) {
                    formatedSearchText = searchText.Substring(3,searchText.Length-3);
                }
                if (searchText.EndsWith("...")) {
                    formatedSearchText = searchText.Substring(0,searchText.Length-3);
                }
                // After stripping "...", trim any leading and trailing whitespaces 
                formatedSearchText = formatedSearchText.Trim();
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(searchText)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                    if (StringUtils.InvariantLCase(AutoTypeAheadSearch).Equals("wordsstartingwithsearchstring")) {
                
                      search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                      search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                      search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                      search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                      search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                      search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Starts_With, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, AutoTypeAheadWordSeparators + formatedSearchText, true, false);
                  
                
                    } else {
                        
                      search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                      search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, formatedSearchText, true, false);
                    } 
                    wc.iAND(search);
                }
            }
                  
            String OrderStatusIdFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "OrderStatusIdFilter_Ajax"];
            if (MiscUtils.IsValueSelected(OrderStatusIdFilterSelectedValue)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, OrderStatusIdFilterSelectedValue, false, false);
            }
                      
            String PickUpDateFromFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "PickUpDateFromFilter_Ajax"];
            if (MiscUtils.IsValueSelected(PickUpDateFromFilterSelectedValue)) {
                DateTime d = DateParser.ParseDate(PickUpDateFromFilterSelectedValue, DateColumn.DEFAULT_FORMAT);
                PickUpDateFromFilterSelectedValue = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, PickUpDateFromFilterSelectedValue, false, false);
            }         
            String PickUpDateToFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "PickUpDateToFilter_Ajax"];
            if (MiscUtils.IsValueSelected(PickUpDateToFilterSelectedValue)) {
                DateTime d = DateParser.ParseDate(PickUpDateToFilterSelectedValue, DateColumn.DEFAULT_FORMAT);
                PickUpDateToFilterSelectedValue = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, PickUpDateToFilterSelectedValue, false, false);
            }         
            String TEStatusFilterSelectedValue = (String)HttpContext.Current.Session[HttpContext.Current.Session.SessionID + appRelativeVirtualPath + "TEStatusFilter_Ajax"];
            if (MiscUtils.IsValueSelected(TEStatusFilterSelectedValue)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, TEStatusFilterSelectedValue, false, false);
            }
                      
            return wc;
        }
          
         public virtual string[] GetAutoCompletionList_DC_OrderSearchArea(String prefixText,int count)
         {
            ArrayList resultList = new ArrayList();
            ArrayList wordList= new ArrayList();
            int iteration = 0;
            
            WhereClause wc= CreateWhereClause(prefixText,"DC_OrderSearchArea", "WordsStartingWithSearchString", "[^a-zA-Z0-9]");
            while (resultList.Count < count && iteration < 5){
                // Fetch 100 records in each iteration
                ePortDC.Business.DC_OrderRecord[] records = DC_OrderTable.GetRecords(wc, null, iteration, 100);
                String resultItem = "";
                foreach (DC_OrderRecord rec in records){
                    // Exit the loop if recordList count has reached AutoTypeAheadListSize.
                    if (resultList.Count >= count) {
                        break;
                    }
                    // If the field is configured to Display as Foreign key, Format() method returns the 
                    // Display as Forien Key value instead of original field value.
                    // Since search had to be done in multiple fields (selected in Control's page property, binding tab) in a record,
                    // We need to find relevent field to display which matches the prefixText and is not already present in the result list.
            
                    resultItem = rec.Format(DC_OrderTable.OrderNum);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                    resultItem = rec.Format(DC_OrderTable.CustomerId);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                    resultItem = rec.Format(DC_OrderTable.ConsigneeId);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                    resultItem = rec.Format(DC_OrderTable.DriverName);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                    resultItem = rec.Format(DC_OrderTable.TransporterId);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                    resultItem = rec.Format(DC_OrderTable.VesselId);
                    if (StringUtils.InvariantUCase(resultItem).Contains(StringUtils.InvariantUCase(prefixText))) {
                        bool isAdded = FormatSuggestions(prefixText, resultItem, 50, "AtBeginningOfMatchedString", "WordsStartingWithSearchString", "[^a-zA-Z0-9]", resultList);
                        if (isAdded) {
                            continue;
                        }
                    }
      
                }
                // Exit the loop if number of records found is less as further iteration will not return any more records
                if (records.Length < 100 ) {
                    break;
                }
                iteration++;
            }
            resultList.Sort();
            string[] result = new string[resultList.Count];
            Array.Copy(resultList.ToArray(), result, resultList.Count);
            return result;
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
        
            if (this.DC_OrderPagination.PageSize.Text.Length > 0) {
                try {
                    // this.PageSize = Convert.ToInt32(this.DC_OrderPagination.PageSize.Text);
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
                System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)(this.FindControl("DC_OrderTableControlRepeater"));
                int index = 0;

                foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
                {
                    // Loop through all rows in the table, set its DataSource and call DataBind().
                    DC_OrderTableControlRow recControl = (DC_OrderTableControlRow)(repItem.FindControl("DC_OrderTableControlRow"));

                    if (recControl.Visible && recControl.IsNewRecord) {
                        DC_OrderRecord rec = new DC_OrderRecord();
        
                        if (recControl.Comments.Text != "") {
                            rec.Parse(recControl.Comments.Text, DC_OrderTable.Comments);
                        }
                        if (recControl.CommodityCode.Text != "") {
                            rec.Parse(recControl.CommodityCode.Text, DC_OrderTable.CommodityCode);
                        }
                        if (recControl.ConsigneeId.Text != "") {
                            rec.Parse(recControl.ConsigneeId.Text, DC_OrderTable.ConsigneeId);
                        }
                        if (recControl.CustomerId.Text != "") {
                            rec.Parse(recControl.CustomerId.Text, DC_OrderTable.CustomerId);
                        }
                        if (recControl.CustomerPO.Text != "") {
                            rec.Parse(recControl.CustomerPO.Text, DC_OrderTable.CustomerPO);
                        }
                        if (recControl.DeliveryDate.Text != "") {
                            rec.Parse(recControl.DeliveryDate.Text, DC_OrderTable.DeliveryDate);
                        }
                        if (recControl.DirectOrder.Text != "") {
                            rec.Parse(recControl.DirectOrder.Text, DC_OrderTable.DirectOrder);
                        }
                        if (recControl.DriverName.Text != "") {
                            rec.Parse(recControl.DriverName.Text, DC_OrderTable.DriverName);
                        }
                        if (recControl.LoadType.Text != "") {
                            rec.Parse(recControl.LoadType.Text, DC_OrderTable.LoadType);
                        }
                        if (recControl.OrderNum.Text != "") {
                            rec.Parse(recControl.OrderNum.Text, DC_OrderTable.OrderNum);
                        }
                        if (recControl.OrderStatusId.Text != "") {
                            rec.Parse(recControl.OrderStatusId.Text, DC_OrderTable.OrderStatusId);
                        }
                        if (recControl.PickUpDate.Text != "") {
                            rec.Parse(recControl.PickUpDate.Text, DC_OrderTable.PickUpDate);
                        }
                        if (recControl.SNMGNum.Text != "") {
                            rec.Parse(recControl.SNMGNum.Text, DC_OrderTable.SNMGNum);
                        }
                        if (recControl.TEStatus.Text != "") {
                            rec.Parse(recControl.TEStatus.Text, DC_OrderTable.TEStatus);
                        }
                        if (recControl.TotalBoxDamaged.Text != "") {
                            rec.Parse(recControl.TotalBoxDamaged.Text, DC_OrderTable.TotalBoxDamaged);
                        }
                        if (recControl.TotalCount.Text != "") {
                            rec.Parse(recControl.TotalCount.Text, DC_OrderTable.TotalCount);
                        }
                        if (recControl.TotalPalletCount.Text != "") {
                            rec.Parse(recControl.TotalPalletCount.Text, DC_OrderTable.TotalPalletCount);
                        }
                        if (recControl.TotalPrice.Text != "") {
                            rec.Parse(recControl.TotalPrice.Text, DC_OrderTable.TotalPrice);
                        }
                        if (recControl.TotalQuantityKG.Text != "") {
                            rec.Parse(recControl.TotalQuantityKG.Text, DC_OrderTable.TotalQuantityKG);
                        }
                        if (recControl.TrailerNum.Text != "") {
                            rec.Parse(recControl.TrailerNum.Text, DC_OrderTable.TrailerNum);
                        }
                        if (recControl.TransportCharges.Text != "") {
                            rec.Parse(recControl.TransportCharges.Text, DC_OrderTable.TransportCharges);
                        }
                        if (recControl.TransporterId.Text != "") {
                            rec.Parse(recControl.TransporterId.Text, DC_OrderTable.TransporterId);
                        }
                        if (recControl.TruckTag.Text != "") {
                            rec.Parse(recControl.TruckTag.Text, DC_OrderTable.TruckTag);
                        }
                        if (recControl.VesselId.Text != "") {
                            rec.Parse(recControl.VesselId.Text, DC_OrderTable.VesselId);
                        }
                        newRecordList.Add(rec);
                    }
                }
            }

            // Add any new record to the list.
            for (int count = 1; count <= this.AddNewRecord; count++) {
                newRecordList.Insert(0, new DC_OrderRecord());
            }
            this.AddNewRecord = 0;

            // Finally , add any new records to the DataSource.
            if (newRecordList.Count > 0) {
                ArrayList finalList = new ArrayList(this.DataSource);
                finalList.InsertRange(0, newRecordList);

                this.DataSource = (DC_OrderRecord[])(finalList.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord")));
            }
        }

        
        public void AddToDeletedRecordIds(DC_OrderTableControlRow rec)
        {
            if (rec.IsNewRecord) {
                return;
            }

            if (this.DeletedRecordIds != null && this.DeletedRecordIds.Length > 0) {
                this.DeletedRecordIds += ",";
            }

            this.DeletedRecordIds += "[" + rec.RecordUniqueId + "]";
        }

        private bool InDeletedRecordIds(DC_OrderTableControlRow rec)            
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
        
        // Get the filters' data for CommodityCodeFilter.
        protected virtual void PopulateCommodityCodeFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CommodityTable.CommodityName, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CommodityCodeFilter.Items.Clear();
            foreach (DC_CommodityRecord itemValue in DC_CommodityTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CommodityCodeSpecified) {
                    cvalue = itemValue.CommodityCode.ToString();
                    fvalue = itemValue.Format(DC_CommodityTable.CommodityName);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.CommodityCodeFilter.Items.IndexOf(item) < 0) {
                    this.CommodityCodeFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.CommodityCodeFilter, selectedValue);

            // Add the All item.
            this.CommodityCodeFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for CustomerIdFilter.
        protected virtual void PopulateCustomerIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_CustomerTable.CustomerName, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.CustomerIdFilter.Items.Clear();
            foreach (DC_CustomerRecord itemValue in DC_CustomerTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.CustomerIdSpecified) {
                    cvalue = itemValue.CustomerId.ToString();
                    fvalue = itemValue.Format(DC_CustomerTable.CustomerName);
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
            
        // Get the filters' data for OrderStatusIdFilter.
        protected virtual void PopulateOrderStatusIdFilter(string selectedValue, int maxItems)
        {
              
            //Setup the WHERE clause.
            WhereClause wc = new WhereClause();
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_OrderStatusTable.Descr, OrderByItem.OrderDir.Asc);

            string noValueFormat = Page.GetResourceValue("Txt:Other", "ePortDC");

            this.OrderStatusIdFilter.Items.Clear();
            foreach (DC_OrderStatusRecord itemValue in DC_OrderStatusTable.GetRecords(wc, orderBy, 0, maxItems))
            {
                // Create the item and add to the list.
                string cvalue = null;
                string fvalue = noValueFormat;
                if (itemValue.OrderStatusIdSpecified) {
                    cvalue = itemValue.OrderStatusId.ToString();
                    fvalue = itemValue.Format(DC_OrderStatusTable.Descr);
                }

                ListItem item = new ListItem(fvalue, cvalue);
                if (this.OrderStatusIdFilter.Items.IndexOf(item) < 0) {
                    this.OrderStatusIdFilter.Items.Add(item);
                }
            }
                
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.OrderStatusIdFilter, selectedValue);

            // Add the All item.
            this.OrderStatusIdFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
            
        // Get the filters' data for TEStatusFilter.
        protected virtual void PopulateTEStatusFilter(string selectedValue, int maxItems)
        {
              
            // Setup the WHERE clause, including the base table if needed.
                
            WhereClause wc = new WhereClause();
                  
            OrderBy orderBy = new OrderBy(false, true);
            orderBy.Add(DC_OrderTable.TEStatus, OrderByItem.OrderDir.Asc);

            string[] list = DC_OrderTable.GetValues(DC_OrderTable.TEStatus, wc, orderBy, maxItems);
            
            this.TEStatusFilter.Items.Clear();
            
            foreach (string itemValue in list)
            {
                // Create the item and add to the list.
                string fvalue = DC_OrderTable.TEStatus.Format(itemValue);
                ListItem item = new ListItem(fvalue, itemValue);
                this.TEStatusFilter.Items.Add(item);
            }
                    
            // Set the selected value.
            MiscUtils.SetSelectedValue(this.TEStatusFilter, selectedValue);

            // Add the All item.
            this.TEStatusFilter.Items.Insert(0, new ListItem(Page.GetResourceValue("Txt:All", "ePortDC"), "--ANY--"));
        }
                          
        // Create a where clause for the filter CommodityCodeFilter.
        public virtual WhereClause CreateWhereClause_CommodityCodeFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_OrderSearchArea.Text.StartsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(3,this.DC_OrderSearchArea.Text.Length-3);
                }
                if (this.DC_OrderSearchArea.Text.EndsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(0,this.DC_OrderSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            if (MiscUtils.IsValueSelected(this.OrderStatusIdFilter)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TEStatusFilter)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter CustomerIdFilter.
        public virtual WhereClause CreateWhereClause_CustomerIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_OrderSearchArea.Text.StartsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(3,this.DC_OrderSearchArea.Text.Length-3);
                }
                if (this.DC_OrderSearchArea.Text.EndsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(0,this.DC_OrderSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            if (MiscUtils.IsValueSelected(this.OrderStatusIdFilter)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TEStatusFilter)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter DC_OrderSearchArea.
        public virtual WhereClause CreateWhereClause_DC_OrderSearchArea()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.OrderStatusIdFilter)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TEStatusFilter)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter OrderStatusIdFilter.
        public virtual WhereClause CreateWhereClause_OrderStatusIdFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_OrderSearchArea.Text.StartsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(3,this.DC_OrderSearchArea.Text.Length-3);
                }
                if (this.DC_OrderSearchArea.Text.EndsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(0,this.DC_OrderSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.TEStatusFilter)) {
                wc.iAND(DC_OrderTable.TEStatus, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.TEStatusFilter, this.GetFromSession(this.TEStatusFilter)), false, false);
            }
                      
            return wc;
        }
                          
        // Create a where clause for the filter TEStatusFilter.
        public virtual WhereClause CreateWhereClause_TEStatusFilter()
        {
              
            WhereClause wc = new WhereClause();
                  
            if (MiscUtils.IsValueSelected(this.CommodityCodeFilter)) {
                wc.iAND(DC_OrderTable.CommodityCode, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CommodityCodeFilter, this.GetFromSession(this.CommodityCodeFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.CustomerIdFilter)) {
                wc.iAND(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.CustomerIdFilter, this.GetFromSession(this.CustomerIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                // Strip "..." from begin and ending of the search text, otherwise the search will return 0 values as in database "..." is not stored.
                if (this.DC_OrderSearchArea.Text.StartsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(3,this.DC_OrderSearchArea.Text.Length-3);
                }
                if (this.DC_OrderSearchArea.Text.EndsWith("...")) {
                    this.DC_OrderSearchArea.Text = this.DC_OrderSearchArea.Text.Substring(0,this.DC_OrderSearchArea.Text.Length-3);
                }
                // After stripping "..." see if the search text is null or empty.
                if (MiscUtils.IsValueSelected(this.DC_OrderSearchArea)) {
                      
                    // These clauses are added depending on operator and fields selected in Control's property page, bindings tab.
                    WhereClause search = new WhereClause();
                    
                search.iOR(DC_OrderTable.OrderNum, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.CustomerId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.ConsigneeId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.DriverName, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.TransporterId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                search.iOR(DC_OrderTable.VesselId, BaseFilter.ComparisonOperator.Contains, MiscUtils.GetSelectedValue(this.DC_OrderSearchArea, this.GetFromSession(this.DC_OrderSearchArea)), true, false);
        
                    wc.iAND(search);
                }
            }
                  
            if (MiscUtils.IsValueSelected(this.OrderStatusIdFilter)) {
                wc.iAND(DC_OrderTable.OrderStatusId, BaseFilter.ComparisonOperator.EqualsTo, MiscUtils.GetSelectedValue(this.OrderStatusIdFilter, this.GetFromSession(this.OrderStatusIdFilter)), false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateFromFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateFromFilter, this.GetFromSession(this.PickUpDateFromFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "00:00:00";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Greater_Than_Or_Equal, val, false, false);
            }
                      
            if (MiscUtils.IsValueSelected(this.PickUpDateToFilter)) {
                string val = MiscUtils.GetSelectedValue(this.PickUpDateToFilter, this.GetFromSession(this.PickUpDateToFilter));
                DateTime d = DateParser.ParseDate(val, DateColumn.DEFAULT_FORMAT);
                val = d.ToShortDateString() + " " + "23:59:59";
                wc.iAND(DC_OrderTable.PickUpDate, BaseFilter.ComparisonOperator.Less_Than_Or_Equal, val, false, false);
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
        
            this.SaveToSession(this.CommodityCodeFilter, this.CommodityCodeFilter.SelectedValue);
            this.SaveToSession(this.CustomerIdFilter, this.CustomerIdFilter.SelectedValue);
            this.SaveToSession(this.DC_OrderSearchArea, this.DC_OrderSearchArea.Text);
            this.SaveToSession(this.OrderStatusIdFilter, this.OrderStatusIdFilter.SelectedValue);
            this.SaveToSession(this.PickUpDateFromFilter, this.PickUpDateFromFilter.Text);
            this.SaveToSession(this.PickUpDateToFilter, this.PickUpDateToFilter.Text);
            this.SaveToSession(this.TEStatusFilter, this.TEStatusFilter.SelectedValue);
            
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
          
            this.SaveToSession("CommodityCodeFilter_Ajax", this.CommodityCodeFilter.SelectedValue);
            this.SaveToSession("CustomerIdFilter_Ajax", this.CustomerIdFilter.SelectedValue);
            this.SaveToSession("DC_OrderSearchArea_Ajax", this.DC_OrderSearchArea.Text);
            this.SaveToSession("OrderStatusIdFilter_Ajax", this.OrderStatusIdFilter.SelectedValue);
            this.SaveToSession("PickUpDateFromFilter_Ajax", this.PickUpDateFromFilter.Text);
            this.SaveToSession("PickUpDateToFilter_Ajax", this.PickUpDateToFilter.Text);
            this.SaveToSession("TEStatusFilter_Ajax", this.TEStatusFilter.SelectedValue);
           HttpContext.Current.Session["AppRelatvieVirtualPath"] = this.Page.AppRelativeVirtualPath;
         
        }
        
        protected override void ClearControlsFromSession()
        {
            base.ClearControlsFromSession();

            // Clear filter controls values from the session.
        
            this.RemoveFromSession(this.CommodityCodeFilter);
            this.RemoveFromSession(this.CustomerIdFilter);
            this.RemoveFromSession(this.DC_OrderSearchArea);
            this.RemoveFromSession(this.OrderStatusIdFilter);
            this.RemoveFromSession(this.PickUpDateFromFilter);
            this.RemoveFromSession(this.PickUpDateToFilter);
            this.RemoveFromSession(this.TEStatusFilter);
            
            // Clear table properties from the session.
            this.RemoveFromSession(this, "Order_By");
            this.RemoveFromSession(this, "Page_Index");
            this.RemoveFromSession(this, "Page_Size");
            
            this.RemoveFromSession(this, "DeletedRecordIds");
            
        }

        protected override void LoadViewState(object savedState)
        {
            base.LoadViewState(savedState);

            string orderByStr = (string)ViewState["DC_OrderTableControl_OrderBy"];
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
                this.ViewState["DC_OrderTableControl_OrderBy"] = this.CurrentSortOrder.ToXmlString();
            }
            
            this.ViewState["Page_Index"] = this.PageIndex;
            this.ViewState["Page_Size"] = this.PageSize;
        
            this.ViewState["DeletedRecordIds"] = this.DeletedRecordIds;
        
            return (base.SaveViewState());
        }

        // Generate the event handling functions for pagination events.
        
              // event handler for ImageButton
              public virtual void DC_OrderPagination_FirstPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_OrderPagination_LastPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_OrderPagination_NextPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_OrderPagination_PageSizeButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
            this.PageSize = Convert.ToInt32(this.DC_OrderPagination.PageSize.Text);
            this.PageIndex = Convert.ToInt32(this.DC_OrderPagination.CurrentPage.Text) - 1;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                BaseClasses.Utils.MiscUtils.RegisterJScriptAlert(this, "BUTTON_CLICK_MESSAGE", ex.Message);
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderPagination_PreviousPage_Click(object sender, ImageClickEventArgs args)
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
              public virtual void CommentsLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.Comments);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.Comments, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CommodityCodeLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.CommodityCode);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.CommodityCode, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void ConsigneeIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.ConsigneeId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.ConsigneeId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerIdLabel1_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.CustomerId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.CustomerId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void CustomerPOLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.CustomerPO);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.CustomerPO, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void DeliveryDateLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.DeliveryDate);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.DeliveryDate, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void DirectOrderLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.DirectOrder);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.DirectOrder, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void DriverNameLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.DriverName);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.DriverName, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void LoadTypeLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.LoadType);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.LoadType, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OrderNumLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.OrderNum);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.OrderNum, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void OrderStatusIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.OrderStatusId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.OrderStatusId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void PickUpDateLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.PickUpDate);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.PickUpDate, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void SNMGNumLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.SNMGNum);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.SNMGNum, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TEStatusLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TEStatus);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TEStatus, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TotalBoxDamagedLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TotalBoxDamaged);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TotalBoxDamaged, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TotalCountLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TotalCount);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TotalCount, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TotalPalletCountLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TotalPalletCount);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TotalPalletCount, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TotalPriceLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TotalPrice);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TotalPrice, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TotalQuantityKGLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TotalQuantityKG);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TotalQuantityKG, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TrailerNumLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TrailerNum);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TrailerNum, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TransportChargesLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TransportCharges);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TransportCharges, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TransporterIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TransporterId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TransporterId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void TruckTagLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.TruckTag);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.TruckTag, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            
              // event handler for FieldSort
              public virtual void VesselIdLabel_Click(object sender, EventArgs args)
              {
              
            OrderByItem sd = this.CurrentSortOrder.Find(DC_OrderTable.VesselId);
            if (sd != null) {
                sd.Reverse();
            } else {
                this.CurrentSortOrder.Reset();
                this.CurrentSortOrder.Add(DC_OrderTable.VesselId, OrderByItem.OrderDir.Asc);
            }

            this.DataChanged = true;
              
              }
            

        // Generate the event handling functions for button events.
        
              // event handler for ImageButton
              public virtual void DC_OrderDeleteButton_Click(object sender, ImageClickEventArgs args)
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
              public virtual void DC_OrderEditButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Order/EditDC_OrderPage.aspx?DC_Order={PK}";
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
              public virtual void DC_OrderExportButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                
            WhereClause wc  = this.CreateWhereClause();
            OrderBy orderBy = CreateOrderBy();
            BaseColumn[] columns = new BaseColumn[] {
             DC_OrderTable.OrderNum,
             DC_OrderTable.Comments,
             DC_OrderTable.CommodityCode,
             DC_OrderTable.ConsigneeId,
             DC_OrderTable.CustomerId,
             DC_OrderTable.CustomerPO,
             DC_OrderTable.DeliveryDate,
             DC_OrderTable.DirectOrder,
             DC_OrderTable.DriverName,
             DC_OrderTable.LoadType,
             DC_OrderTable.OrderStatusId,
             DC_OrderTable.PickUpDate,
             DC_OrderTable.SNMGNum,
             DC_OrderTable.TEStatus,
             DC_OrderTable.TotalBoxDamaged,
             DC_OrderTable.TotalCount,
             DC_OrderTable.TotalPalletCount,
             DC_OrderTable.TotalPrice,
             DC_OrderTable.TotalQuantityKG,
             DC_OrderTable.TransportCharges,
             DC_OrderTable.TransporterId,
             DC_OrderTable.TrailerNum,
             DC_OrderTable.TruckTag,
             DC_OrderTable.VesselId,
             null};
            ExportData rep = new ExportData(DC_OrderTable.Instance,wc,orderBy,columns);
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
              public virtual void DC_OrderExportExcelButton_Click(object sender, ImageClickEventArgs args)
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
            ExportData excelReport = new ExportData(DC_OrderTable.Instance, wc, orderBy);
            // Add each of the columns in order of export.
            // To customize the data type, change the second parameter of the new ExcelColumn to be
            // a format string from Excel's Format Cell menu. For example "dddd, mmmm dd, yyyy h:mm AM/PM;@", "#,##0.00"
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.OrderNum, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.Comments, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.CommodityCode, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.ConsigneeId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.CustomerId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.CustomerPO, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.DeliveryDate, "Short Date"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.DirectOrder, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.DriverName, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.LoadType, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.OrderStatusId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.PickUpDate, "Short Date"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.SNMGNum, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TEStatus, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TotalBoxDamaged, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TotalCount, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TotalPalletCount, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TotalPrice, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TotalQuantityKG, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TransportCharges, "Standard"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TransporterId, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TrailerNum, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.TruckTag, "Default"));
             excelReport.AddColumn(new ExcelColumn(DC_OrderTable.VesselId, "Default"));

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
              public virtual void DC_OrderNewButton_Click(object sender, ImageClickEventArgs args)
              {
              
            string url = @"../DC_Order/AddDC_OrderPage.aspx";
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
              public virtual void DC_OrderPDFButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                DbUtils.StartTransaction();
                

        PDFReport report = new PDFReport();

        report.SpecificReportFileName = Page.Server.MapPath("ShowDC_OrderTablePage.DC_OrderPDFButton.report");
                // report.Title replaces the value tag of page header and footer containing ${ReportTitle}
                report.Title = "DC_Order";
                // If ShowDC_OrderTablePage.DC_OrderPDFButton.report specifies a valid report template,
                // AddColumn methods will generate a report template.   
                // Each AddColumn method-call specifies a column
                // The 1st parameter represents the text of the column header
                // The 2nd parameter represents the horizontal alignment of the column header
                // The 3rd parameter represents the text format of the column detail
                // The 4th parameter represents the horizontal alignment of the column detail
                // The 5th parameter represents the relative width of the column
                 report.AddColumn(DC_OrderTable.OrderNum.Name, ReportEnum.Align.Left, "${DC_OrderTable.OrderNum.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.Comments.Name, ReportEnum.Align.Left, "${DC_OrderTable.Comments.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_OrderTable.CommodityCode.Name, ReportEnum.Align.Left, "${DC_OrderTable.CommodityCode.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.ConsigneeId.Name, ReportEnum.Align.Left, "${DC_OrderTable.ConsigneeId.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.CustomerId.Name, ReportEnum.Align.Left, "${DC_OrderTable.CustomerId.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_OrderTable.CustomerPO.Name, ReportEnum.Align.Left, "${DC_OrderTable.CustomerPO.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.DeliveryDate.Name, ReportEnum.Align.Left, "${DC_OrderTable.DeliveryDate.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_OrderTable.DirectOrder.Name, ReportEnum.Align.Left, "${DC_OrderTable.DirectOrder.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.DriverName.Name, ReportEnum.Align.Left, "${DC_OrderTable.DriverName.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.LoadType.Name, ReportEnum.Align.Left, "${DC_OrderTable.LoadType.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.OrderStatusId.Name, ReportEnum.Align.Left, "${DC_OrderTable.OrderStatusId.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.PickUpDate.Name, ReportEnum.Align.Left, "${DC_OrderTable.PickUpDate.Name}", ReportEnum.Align.Left, 20);
                 report.AddColumn(DC_OrderTable.SNMGNum.Name, ReportEnum.Align.Left, "${DC_OrderTable.SNMGNum.Name}", ReportEnum.Align.Left, 30);
                 report.AddColumn(DC_OrderTable.TEStatus.Name, ReportEnum.Align.Left, "${DC_OrderTable.TEStatus.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.TotalBoxDamaged.Name, ReportEnum.Align.Right, "${DC_OrderTable.TotalBoxDamaged.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_OrderTable.TotalCount.Name, ReportEnum.Align.Right, "${DC_OrderTable.TotalCount.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_OrderTable.TotalPalletCount.Name, ReportEnum.Align.Right, "${DC_OrderTable.TotalPalletCount.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_OrderTable.TotalPrice.Name, ReportEnum.Align.Right, "${DC_OrderTable.TotalPrice.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_OrderTable.TotalQuantityKG.Name, ReportEnum.Align.Right, "${DC_OrderTable.TotalQuantityKG.Name}", ReportEnum.Align.Right, 15);
                 report.AddColumn(DC_OrderTable.TransportCharges.Name, ReportEnum.Align.Right, "${DC_OrderTable.TransportCharges.Name}", ReportEnum.Align.Right, 20);
                 report.AddColumn(DC_OrderTable.TransporterId.Name, ReportEnum.Align.Left, "${DC_OrderTable.TransporterId.Name}", ReportEnum.Align.Left, 24);
                 report.AddColumn(DC_OrderTable.TrailerNum.Name, ReportEnum.Align.Left, "${DC_OrderTable.TrailerNum.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.TruckTag.Name, ReportEnum.Align.Left, "${DC_OrderTable.TruckTag.Name}", ReportEnum.Align.Left, 15);
                 report.AddColumn(DC_OrderTable.VesselId.Name, ReportEnum.Align.Left, "${DC_OrderTable.VesselId.Name}", ReportEnum.Align.Left, 24);

                WhereClause whereClause = CreateWhereClause();
                OrderBy orderBy = CreateOrderBy();
                int rowsPerQuery = 1000;
                int pageNum = 0;
                int recordCount = 0;
                int totalRecords = DC_OrderTable.GetRecordCount(whereClause);
                                
                report.Page = Page.GetResourceValue("Txt:Page", "ePortDC");
                report.ApplicationPath = this.Page.MapPath(Page.Request.ApplicationPath);

                ColumnList columns = DC_OrderTable.GetColumnList();
                DC_OrderRecord[] records = null;
                do
                {
                    records = DC_OrderTable.GetRecords(whereClause, orderBy, pageNum, rowsPerQuery);
                    if (records != null && records.Length > 0)
                    {
                        foreach ( DC_OrderRecord record in records)
                        {
                            // AddData method takes four parameters   
                            // The 1st parameters represents the data format
                            // The 2nd parameters represents the data value
                            // The 3rd parameters represents the default alignment of column using the data
                            // The 4th parameters represents the maximum length of the data value being shown
                             report.AddData("${DC_OrderTable.OrderNum.Name}", record.Format(DC_OrderTable.OrderNum), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.Comments.Name}", record.Format(DC_OrderTable.Comments), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.CommodityCode.Name}", record.Format(DC_OrderTable.CommodityCode), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.ConsigneeId.Name}", record.Format(DC_OrderTable.ConsigneeId), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.CustomerId.Name}", record.Format(DC_OrderTable.CustomerId), ReportEnum.Align.Left);
                             report.AddData("${DC_OrderTable.CustomerPO.Name}", record.Format(DC_OrderTable.CustomerPO), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.DeliveryDate.Name}", record.Format(DC_OrderTable.DeliveryDate), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.DirectOrder.Name}", record.Format(DC_OrderTable.DirectOrder), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.DriverName.Name}", record.Format(DC_OrderTable.DriverName), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.LoadType.Name}", record.Format(DC_OrderTable.LoadType), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.OrderStatusId.Name}", record.Format(DC_OrderTable.OrderStatusId), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.PickUpDate.Name}", record.Format(DC_OrderTable.PickUpDate), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.SNMGNum.Name}", record.Format(DC_OrderTable.SNMGNum), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.TEStatus.Name}", record.Format(DC_OrderTable.TEStatus), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.TotalBoxDamaged.Name}", record.Format(DC_OrderTable.TotalBoxDamaged), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TotalCount.Name}", record.Format(DC_OrderTable.TotalCount), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TotalPalletCount.Name}", record.Format(DC_OrderTable.TotalPalletCount), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TotalPrice.Name}", record.Format(DC_OrderTable.TotalPrice), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TotalQuantityKG.Name}", record.Format(DC_OrderTable.TotalQuantityKG), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TransportCharges.Name}", record.Format(DC_OrderTable.TransportCharges), ReportEnum.Align.Right, 100);
                             report.AddData("${DC_OrderTable.TransporterId.Name}", record.Format(DC_OrderTable.TransporterId), ReportEnum.Align.Left);
                             report.AddData("${DC_OrderTable.TrailerNum.Name}", record.Format(DC_OrderTable.TrailerNum), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.TruckTag.Name}", record.Format(DC_OrderTable.TruckTag), ReportEnum.Align.Left, 100);
                             report.AddData("${DC_OrderTable.VesselId.Name}", record.Format(DC_OrderTable.VesselId), ReportEnum.Align.Left, 100);

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
              public virtual void DC_OrderRefreshButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
            ((DC_OrderTableControl)(this.Page.FindControlRecursively("DC_OrderTableControl"))).ResetData = true;
                
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for ImageButton
              public virtual void DC_OrderResetButton_Click(object sender, ImageClickEventArgs args)
              {
              
            try {
                
              this.CommodityCodeFilter.ClearSelection();
            
              this.CustomerIdFilter.ClearSelection();
            
              this.OrderStatusIdFilter.ClearSelection();
            
              this.TEStatusFilter.ClearSelection();
            
              this.DC_OrderSearchArea.Text = "";
            
              this.PickUpDateFromFilter.Text = "";
            
              this.PickUpDateToFilter.Text = "";
            
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
              public virtual void DC_OrderFilterButton_Click(object sender, EventArgs args)
              {
              
            try {
                
            this.DataChanged = true;
      
            } catch (Exception ex) {
                this.Page.ErrorOnPage = true;
    
                throw ex;
            } finally {
    
            }
    
              }
            
              // event handler for Button with Layout
              public virtual void DC_OrderSearchButton_Click(object sender, EventArgs args)
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
        protected virtual void CommodityCodeFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void CustomerIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void OrderStatusIdFilter_SelectedIndexChanged(object sender, EventArgs args)
        {
            this.DataChanged = true;
        }
            
        // event handler for FieldFilter
        protected virtual void TEStatusFilter_SelectedIndexChanged(object sender, EventArgs args)
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

        private DC_OrderRecord[] _DataSource = null;
        public  DC_OrderRecord[] DataSource {
            get {
                return this._DataSource;
            }
            set {
                this._DataSource = value;
            }
        }

#region "Helper Properties"
        
        public System.Web.UI.WebControls.LinkButton CommentsLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommentsLabel");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList CommodityCodeFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeFilter");
            }
        }
        
        public System.Web.UI.WebControls.Literal CommodityCodeLabel {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton CommodityCodeLabel1 {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CommodityCodeLabel1");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton ConsigneeIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "ConsigneeIdLabel");
            }
        }
        
        public ePortDC.UI.IThemeButton CrystalReportButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CrystalReportButton");
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
        
        public System.Web.UI.WebControls.LinkButton CustomerPOLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "CustomerPOLabel");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderDeleteButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderDeleteButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderEditButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderEditButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderExportButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderExportButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderExportExcelButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderExportExcelButton");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_OrderFilterButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderFilterButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderNewButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderNewButton");
            }
        }
        
        public ePortDC.UI.IPagination DC_OrderPagination {
            get {
                return (ePortDC.UI.IPagination)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderPagination");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderPDFButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderPDFButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderRefreshButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderRefreshButton");
            }
        }
        
        public System.Web.UI.WebControls.ImageButton DC_OrderResetButton {
            get {
                return (System.Web.UI.WebControls.ImageButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderResetButton");
            }
        }
        
        public System.Web.UI.WebControls.TextBox DC_OrderSearchArea {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderSearchArea");
            }
        }
        
        public ePortDC.UI.IThemeButton DC_OrderSearchButton {
            get {
                return (ePortDC.UI.IThemeButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderSearchButton");
            }
        }
        
        public System.Web.UI.WebControls.Literal DC_OrderTableTitle {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderTableTitle");
            }
        }
        
        public System.Web.UI.WebControls.CheckBox DC_OrderToggleAll {
            get {
                return (System.Web.UI.WebControls.CheckBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderToggleAll");
            }
        }
        
        public System.Web.UI.WebControls.Label DC_OrderTotalItems {
            get {
                return (System.Web.UI.WebControls.Label)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DC_OrderTotalItems");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DeliveryDateLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DeliveryDateLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DirectOrderLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DirectOrderLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton DriverNameLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "DriverNameLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton LoadTypeLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "LoadTypeLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OrderNumLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderNumLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal OrderStatusId1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusId1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList OrderStatusIdFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusIdFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton OrderStatusIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "OrderStatusIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal PickUpDate1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDate1Label");
            }
        }
        
        public System.Web.UI.WebControls.TextBox PickUpDateFromFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateFromFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton PickUpDateLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateLabel");
            }
        }
        
        public System.Web.UI.WebControls.TextBox PickUpDateToFilter {
            get {
                return (System.Web.UI.WebControls.TextBox)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "PickUpDateToFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton SNMGNumLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "SNMGNumLabel");
            }
        }
        
        public System.Web.UI.WebControls.Literal TEStatus1Label {
            get {
                return (System.Web.UI.WebControls.Literal)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatus1Label");
            }
        }
        
        public System.Web.UI.WebControls.DropDownList TEStatusFilter {
            get {
                return (System.Web.UI.WebControls.DropDownList)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatusFilter");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TEStatusLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TEStatusLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TotalBoxDamagedLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalBoxDamagedLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TotalCountLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalCountLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TotalPalletCountLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPalletCountLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TotalPriceLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalPriceLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TotalQuantityKGLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TotalQuantityKGLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TrailerNumLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TrailerNumLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TransportChargesLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransportChargesLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TransporterIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TransporterIdLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton TruckTagLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "TruckTagLabel");
            }
        }
        
        public System.Web.UI.WebControls.LinkButton VesselIdLabel {
            get {
                return (System.Web.UI.WebControls.LinkButton)BaseClasses.Utils.MiscUtils.FindControlRecursively(this, "VesselIdLabel");
            }
        }
        
#endregion

#region "Helper Functions"
        
                public override string ModifyRedirectUrl(string url, string arg, bool bEncrypt)
              
        {
            bool needToProcess = AreAnyUrlParametersForMe(url, arg);
            if (needToProcess) {
                DC_OrderTableControlRow recCtl = this.GetSelectedRecordControl();
                if (recCtl == null && url.IndexOf("{") >= 0) {
                    // Localization.
                    throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
                }

                DC_OrderRecord rec = null;
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
            foreach (DC_OrderTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_OrderRecordRowSelection.Checked) {
                    return counter;
                }
                counter += 1;
            }
            return -1;
        }
        
        public DC_OrderTableControlRow GetSelectedRecordControl()
        {
        DC_OrderTableControlRow[] selectedList = this.GetSelectedRecordControls();
            if (selectedList.Length == 0) {
            return null;
            }
            return selectedList[0];
          
        }

        public DC_OrderTableControlRow[] GetSelectedRecordControls()
        {
        
            ArrayList selectedList = new ArrayList(25);
            foreach (DC_OrderTableControlRow recControl in this.GetRecordControls())
            {
                if (recControl.DC_OrderRecordRowSelection.Checked) {
                    selectedList.Add(recControl);
                }
            }
            return (DC_OrderTableControlRow[])(selectedList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_OrderTablePage.DC_OrderTableControlRow")));
          
        }

        public virtual void DeleteSelectedRecords(bool deferDeletion)
        {
            DC_OrderTableControlRow[] recList = this.GetSelectedRecordControls();
            if (recList.Length == 0) {
                // Localization.
                throw new Exception(Page.GetResourceValue("Err:NoRecSelected", "ePortDC"));
            }
            
            foreach (DC_OrderTableControlRow recCtl in recList)
            {
                if (deferDeletion) {
                    if (!recCtl.IsNewRecord) {
                
                        this.AddToDeletedRecordIds(recCtl);
                  
                    }
                    recCtl.Visible = false;
                
                    recCtl.DC_OrderRecordRowSelection.Checked = false;
                
                } else {
                
                    recCtl.Delete();
                    this.DataChanged = true;
                    this.ResetData = true;
                  
                }
            }
        }

        public DC_OrderTableControlRow[] GetRecordControls()
        {
            ArrayList recList = new ArrayList();
            System.Web.UI.WebControls.Repeater rep = (System.Web.UI.WebControls.Repeater)this.FindControl("DC_OrderTableControlRepeater");

            foreach (System.Web.UI.WebControls.RepeaterItem repItem in rep.Items)
            {
                DC_OrderTableControlRow recControl = (DC_OrderTableControlRow)repItem.FindControl("DC_OrderTableControlRow");
                recList.Add(recControl);
            }

            return (DC_OrderTableControlRow[])recList.ToArray(Type.GetType("ePortDC.UI.Controls.ShowDC_OrderTablePage.DC_OrderTableControlRow"));
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

  