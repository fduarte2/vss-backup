// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_OrderRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_OrderRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_OrderTable"></see> class.
/// </remarks>
/// <seealso cref="DC_OrderTable"></seealso>
/// <seealso cref="DC_OrderRecord"></seealso>
public class BaseDC_OrderRecord : PrimaryKeyRecord
{

	public readonly static DC_OrderTable TableUtils = DC_OrderTable.Instance;

	// Constructors
 
	protected BaseDC_OrderRecord() : base(TableUtils)
	{
	}

	protected BaseDC_OrderRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public ColumnValue GetOrderNumValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public string GetOrderNumFieldValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public ColumnValue GetCommentsCancelValue()
	{
		return this.GetValue(TableUtils.CommentsCancelColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public string GetCommentsCancelFieldValue()
	{
		return this.GetValue(TableUtils.CommentsCancelColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public void SetCommentsCancelFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsCancelColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public void SetCommentsCancelFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsCancelColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public ColumnValue GetCommodityCodeValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public Int16 GetCommodityCodeFieldValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(string val)
	{
		this.SetString(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public ColumnValue GetConsigneeIdValue()
	{
		return this.GetValue(TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public Int16 GetConsigneeIdFieldValue()
	{
		return this.GetValue(TableUtils.ConsigneeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public ColumnValue GetCustomerIdValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public Int16 GetCustomerIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public ColumnValue GetCustomerPOValue()
	{
		return this.GetValue(TableUtils.CustomerPOColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public string GetCustomerPOFieldValue()
	{
		return this.GetValue(TableUtils.CustomerPOColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public void SetCustomerPOFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerPOColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public void SetCustomerPOFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerPOColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public ColumnValue GetDeliveryDateValue()
	{
		return this.GetValue(TableUtils.DeliveryDateColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public DateTime GetDeliveryDateFieldValue()
	{
		return this.GetValue(TableUtils.DeliveryDateColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public void SetDeliveryDateFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DeliveryDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public void SetDeliveryDateFieldValue(string val)
	{
		this.SetString(val, TableUtils.DeliveryDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public void SetDeliveryDateFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DeliveryDateColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public ColumnValue GetDirectOrderValue()
	{
		return this.GetValue(TableUtils.DirectOrderColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public string GetDirectOrderFieldValue()
	{
		return this.GetValue(TableUtils.DirectOrderColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public void SetDirectOrderFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DirectOrderColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public void SetDirectOrderFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DirectOrderColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public ColumnValue GetDriverCheckInDateTimeValue()
	{
		return this.GetValue(TableUtils.DriverCheckInDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public DateTime GetDriverCheckInDateTimeFieldValue()
	{
		return this.GetValue(TableUtils.DriverCheckInDateTimeColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public void SetDriverCheckInDateTimeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DriverCheckInDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public void SetDriverCheckInDateTimeFieldValue(string val)
	{
		this.SetString(val, TableUtils.DriverCheckInDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public void SetDriverCheckInDateTimeFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DriverCheckInDateTimeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public ColumnValue GetDriverCheckOutDateTimeValue()
	{
		return this.GetValue(TableUtils.DriverCheckOutDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public DateTime GetDriverCheckOutDateTimeFieldValue()
	{
		return this.GetValue(TableUtils.DriverCheckOutDateTimeColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public void SetDriverCheckOutDateTimeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DriverCheckOutDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public void SetDriverCheckOutDateTimeFieldValue(string val)
	{
		this.SetString(val, TableUtils.DriverCheckOutDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public void SetDriverCheckOutDateTimeFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DriverCheckOutDateTimeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public ColumnValue GetDriverNameValue()
	{
		return this.GetValue(TableUtils.DriverNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public string GetDriverNameFieldValue()
	{
		return this.GetValue(TableUtils.DriverNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public void SetDriverNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DriverNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public void SetDriverNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DriverNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public ColumnValue GetLastUpdateUserValue()
	{
		return this.GetValue(TableUtils.LastUpdateUserColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public string GetLastUpdateUserFieldValue()
	{
		return this.GetValue(TableUtils.LastUpdateUserColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public void SetLastUpdateUserFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.LastUpdateUserColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public void SetLastUpdateUserFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.LastUpdateUserColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public ColumnValue GetLastUpdateDateTimeValue()
	{
		return this.GetValue(TableUtils.LastUpdateDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public DateTime GetLastUpdateDateTimeFieldValue()
	{
		return this.GetValue(TableUtils.LastUpdateDateTimeColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public void SetLastUpdateDateTimeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.LastUpdateDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public void SetLastUpdateDateTimeFieldValue(string val)
	{
		this.SetString(val, TableUtils.LastUpdateDateTimeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public void SetLastUpdateDateTimeFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.LastUpdateDateTimeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public ColumnValue GetLoadTypeValue()
	{
		return this.GetValue(TableUtils.LoadTypeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public string GetLoadTypeFieldValue()
	{
		return this.GetValue(TableUtils.LoadTypeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public void SetLoadTypeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.LoadTypeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public void SetLoadTypeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.LoadTypeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public ColumnValue GetOrderStatusIdValue()
	{
		return this.GetValue(TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public Int16 GetOrderStatusIdFieldValue()
	{
		return this.GetValue(TableUtils.OrderStatusIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public ColumnValue GetPARSBarCodeValue()
	{
		return this.GetValue(TableUtils.PARSBarCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public string GetPARSBarCodeFieldValue()
	{
		return this.GetValue(TableUtils.PARSBarCodeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public void SetPARSBarCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PARSBarCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public void SetPARSBarCodeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PARSBarCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public ColumnValue GetPARSCarrierDispatchPhoneValue()
	{
		return this.GetValue(TableUtils.PARSCarrierDispatchPhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public string GetPARSCarrierDispatchPhoneFieldValue()
	{
		return this.GetValue(TableUtils.PARSCarrierDispatchPhoneColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public void SetPARSCarrierDispatchPhoneFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PARSCarrierDispatchPhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public void SetPARSCarrierDispatchPhoneFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PARSCarrierDispatchPhoneColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public ColumnValue GetPARSDriverPhoneMobileValue()
	{
		return this.GetValue(TableUtils.PARSDriverPhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public string GetPARSDriverPhoneMobileFieldValue()
	{
		return this.GetValue(TableUtils.PARSDriverPhoneMobileColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public void SetPARSDriverPhoneMobileFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PARSDriverPhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public void SetPARSDriverPhoneMobileFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PARSDriverPhoneMobileColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public ColumnValue GetPARSETABorderValue()
	{
		return this.GetValue(TableUtils.PARSETABorderColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public DateTime GetPARSETABorderFieldValue()
	{
		return this.GetValue(TableUtils.PARSETABorderColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public void SetPARSETABorderFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PARSETABorderColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public void SetPARSETABorderFieldValue(string val)
	{
		this.SetString(val, TableUtils.PARSETABorderColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public void SetPARSETABorderFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PARSETABorderColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public ColumnValue GetPARSPortOfEntryNumValue()
	{
		return this.GetValue(TableUtils.PARSPortOfEntryNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public string GetPARSPortOfEntryNumFieldValue()
	{
		return this.GetValue(TableUtils.PARSPortOfEntryNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public void SetPARSPortOfEntryNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PARSPortOfEntryNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public void SetPARSPortOfEntryNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PARSPortOfEntryNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public ColumnValue GetPickUpDateValue()
	{
		return this.GetValue(TableUtils.PickUpDateColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public DateTime GetPickUpDateFieldValue()
	{
		return this.GetValue(TableUtils.PickUpDateColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public void SetPickUpDateFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PickUpDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public void SetPickUpDateFieldValue(string val)
	{
		this.SetString(val, TableUtils.PickUpDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public void SetPickUpDateFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PickUpDateColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public ColumnValue GetSealNumValue()
	{
		return this.GetValue(TableUtils.SealNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public string GetSealNumFieldValue()
	{
		return this.GetValue(TableUtils.SealNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public void SetSealNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SealNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public void SetSealNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SealNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public ColumnValue GetSNMGNumValue()
	{
		return this.GetValue(TableUtils.SNMGNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public string GetSNMGNumFieldValue()
	{
		return this.GetValue(TableUtils.SNMGNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public void SetSNMGNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SNMGNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public void SetSNMGNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SNMGNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public ColumnValue GetTEStatusValue()
	{
		return this.GetValue(TableUtils.TEStatusColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public string GetTEStatusFieldValue()
	{
		return this.GetValue(TableUtils.TEStatusColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public void SetTEStatusFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TEStatusColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public void SetTEStatusFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TEStatusColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public ColumnValue GetTotalBoxDamagedValue()
	{
		return this.GetValue(TableUtils.TotalBoxDamagedColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public Int16 GetTotalBoxDamagedFieldValue()
	{
		return this.GetValue(TableUtils.TotalBoxDamagedColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public void SetTotalBoxDamagedFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TotalBoxDamagedColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public void SetTotalBoxDamagedFieldValue(string val)
	{
		this.SetString(val, TableUtils.TotalBoxDamagedColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public void SetTotalBoxDamagedFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalBoxDamagedColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public void SetTotalBoxDamagedFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalBoxDamagedColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public void SetTotalBoxDamagedFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalBoxDamagedColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public ColumnValue GetTotalCountValue()
	{
		return this.GetValue(TableUtils.TotalCountColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public Int16 GetTotalCountFieldValue()
	{
		return this.GetValue(TableUtils.TotalCountColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public void SetTotalCountFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TotalCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public void SetTotalCountFieldValue(string val)
	{
		this.SetString(val, TableUtils.TotalCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public void SetTotalCountFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public void SetTotalCountFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public void SetTotalCountFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalCountColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public ColumnValue GetTotalPalletCountValue()
	{
		return this.GetValue(TableUtils.TotalPalletCountColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public Int16 GetTotalPalletCountFieldValue()
	{
		return this.GetValue(TableUtils.TotalPalletCountColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public void SetTotalPalletCountFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TotalPalletCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public void SetTotalPalletCountFieldValue(string val)
	{
		this.SetString(val, TableUtils.TotalPalletCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public void SetTotalPalletCountFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPalletCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public void SetTotalPalletCountFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPalletCountColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public void SetTotalPalletCountFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPalletCountColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public ColumnValue GetTotalPriceValue()
	{
		return this.GetValue(TableUtils.TotalPriceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public Decimal GetTotalPriceFieldValue()
	{
		return this.GetValue(TableUtils.TotalPriceColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public void SetTotalPriceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TotalPriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public void SetTotalPriceFieldValue(string val)
	{
		this.SetString(val, TableUtils.TotalPriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public void SetTotalPriceFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public void SetTotalPriceFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public void SetTotalPriceFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalPriceColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public ColumnValue GetTotalQuantityKGValue()
	{
		return this.GetValue(TableUtils.TotalQuantityKGColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public Int32 GetTotalQuantityKGFieldValue()
	{
		return this.GetValue(TableUtils.TotalQuantityKGColumn).ToInt32();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public void SetTotalQuantityKGFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TotalQuantityKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public void SetTotalQuantityKGFieldValue(string val)
	{
		this.SetString(val, TableUtils.TotalQuantityKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public void SetTotalQuantityKGFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalQuantityKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public void SetTotalQuantityKGFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalQuantityKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public void SetTotalQuantityKGFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TotalQuantityKGColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public ColumnValue GetTransportChargesValue()
	{
		return this.GetValue(TableUtils.TransportChargesColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public Decimal GetTransportChargesFieldValue()
	{
		return this.GetValue(TableUtils.TransportChargesColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public void SetTransportChargesFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TransportChargesColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public void SetTransportChargesFieldValue(string val)
	{
		this.SetString(val, TableUtils.TransportChargesColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public void SetTransportChargesFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransportChargesColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public void SetTransportChargesFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransportChargesColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public void SetTransportChargesFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransportChargesColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public ColumnValue GetTransporterIdValue()
	{
		return this.GetValue(TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public Int16 GetTransporterIdFieldValue()
	{
		return this.GetValue(TableUtils.TransporterIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public ColumnValue GetTrailerNumValue()
	{
		return this.GetValue(TableUtils.TrailerNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public string GetTrailerNumFieldValue()
	{
		return this.GetValue(TableUtils.TrailerNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public void SetTrailerNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TrailerNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public void SetTrailerNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TrailerNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public ColumnValue GetTruckTagValue()
	{
		return this.GetValue(TableUtils.TruckTagColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public string GetTruckTagFieldValue()
	{
		return this.GetValue(TableUtils.TruckTagColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public void SetTruckTagFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TruckTagColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public void SetTruckTagFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TruckTagColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public ColumnValue GetVesselIdValue()
	{
		return this.GetValue(TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public Int16 GetVesselIdFieldValue()
	{
		return this.GetValue(TableUtils.VesselIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public ColumnValue GetZUser1Value()
	{
		return this.GetValue(TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public string GetZUser1FieldValue()
	{
		return this.GetValue(TableUtils.ZUser1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public ColumnValue GetZUser2Value()
	{
		return this.GetValue(TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public string GetZUser2FieldValue()
	{
		return this.GetValue(TableUtils.ZUser2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public ColumnValue GetZUser3Value()
	{
		return this.GetValue(TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public string GetZUser3FieldValue()
	{
		return this.GetValue(TableUtils.ZUser3Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser3Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public ColumnValue GetZUser4Value()
	{
		return this.GetValue(TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public string GetZUser4FieldValue()
	{
		return this.GetValue(TableUtils.ZUser4Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser4Column);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public string OrderNum
	{
		get
		{
			return this.GetValue(TableUtils.OrderNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OrderNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OrderNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OrderNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderNum field.
	/// </summary>
	public string OrderNumDefault
	{
		get
		{
			return TableUtils.OrderNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.Comments field.
	/// </summary>
	public string Comments
	{
		get
		{
			return this.GetValue(TableUtils.CommentsColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CommentsColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CommentsSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CommentsColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public string CommentsCancel
	{
		get
		{
			return this.GetValue(TableUtils.CommentsCancelColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CommentsCancelColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CommentsCancelSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CommentsCancelColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommentsCancel field.
	/// </summary>
	public string CommentsCancelDefault
	{
		get
		{
			return TableUtils.CommentsCancelColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public Int16 CommodityCode
	{
		get
		{
			return this.GetValue(TableUtils.CommodityCodeColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CommodityCodeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CommodityCodeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CommodityCodeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CommodityCode field.
	/// </summary>
	public string CommodityCodeDefault
	{
		get
		{
			return TableUtils.CommodityCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public Int16 ConsigneeId
	{
		get
		{
			return this.GetValue(TableUtils.ConsigneeIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ConsigneeIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ConsigneeIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ConsigneeIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ConsigneeId field.
	/// </summary>
	public string ConsigneeIdDefault
	{
		get
		{
			return TableUtils.ConsigneeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public Int16 CustomerId
	{
		get
		{
			return this.GetValue(TableUtils.CustomerIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomerIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomerIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomerIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerId field.
	/// </summary>
	public string CustomerIdDefault
	{
		get
		{
			return TableUtils.CustomerIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public string CustomerPO
	{
		get
		{
			return this.GetValue(TableUtils.CustomerPOColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomerPOColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomerPOSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomerPOColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.CustomerPO field.
	/// </summary>
	public string CustomerPODefault
	{
		get
		{
			return TableUtils.CustomerPOColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public DateTime DeliveryDate
	{
		get
		{
			return this.GetValue(TableUtils.DeliveryDateColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DeliveryDateColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DeliveryDateSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DeliveryDateColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DeliveryDate field.
	/// </summary>
	public string DeliveryDateDefault
	{
		get
		{
			return TableUtils.DeliveryDateColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public string DirectOrder
	{
		get
		{
			return this.GetValue(TableUtils.DirectOrderColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DirectOrderColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DirectOrderSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DirectOrderColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DirectOrder field.
	/// </summary>
	public string DirectOrderDefault
	{
		get
		{
			return TableUtils.DirectOrderColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public DateTime DriverCheckInDateTime
	{
		get
		{
			return this.GetValue(TableUtils.DriverCheckInDateTimeColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DriverCheckInDateTimeColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DriverCheckInDateTimeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DriverCheckInDateTimeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckInDateTime field.
	/// </summary>
	public string DriverCheckInDateTimeDefault
	{
		get
		{
			return TableUtils.DriverCheckInDateTimeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public DateTime DriverCheckOutDateTime
	{
		get
		{
			return this.GetValue(TableUtils.DriverCheckOutDateTimeColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DriverCheckOutDateTimeColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DriverCheckOutDateTimeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DriverCheckOutDateTimeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverCheckOutDateTime field.
	/// </summary>
	public string DriverCheckOutDateTimeDefault
	{
		get
		{
			return TableUtils.DriverCheckOutDateTimeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public string DriverName
	{
		get
		{
			return this.GetValue(TableUtils.DriverNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DriverNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DriverNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DriverNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.DriverName field.
	/// </summary>
	public string DriverNameDefault
	{
		get
		{
			return TableUtils.DriverNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public string LastUpdateUser
	{
		get
		{
			return this.GetValue(TableUtils.LastUpdateUserColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.LastUpdateUserColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool LastUpdateUserSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.LastUpdateUserColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateUser field.
	/// </summary>
	public string LastUpdateUserDefault
	{
		get
		{
			return TableUtils.LastUpdateUserColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public DateTime LastUpdateDateTime
	{
		get
		{
			return this.GetValue(TableUtils.LastUpdateDateTimeColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.LastUpdateDateTimeColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool LastUpdateDateTimeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.LastUpdateDateTimeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LastUpdateDateTime field.
	/// </summary>
	public string LastUpdateDateTimeDefault
	{
		get
		{
			return TableUtils.LastUpdateDateTimeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public string LoadType
	{
		get
		{
			return this.GetValue(TableUtils.LoadTypeColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.LoadTypeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool LoadTypeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.LoadTypeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.LoadType field.
	/// </summary>
	public string LoadTypeDefault
	{
		get
		{
			return TableUtils.LoadTypeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public Int16 OrderStatusId
	{
		get
		{
			return this.GetValue(TableUtils.OrderStatusIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OrderStatusIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OrderStatusIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OrderStatusIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.OrderStatusId field.
	/// </summary>
	public string OrderStatusIdDefault
	{
		get
		{
			return TableUtils.OrderStatusIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public string PARSBarCode
	{
		get
		{
			return this.GetValue(TableUtils.PARSBarCodeColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PARSBarCodeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PARSBarCodeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PARSBarCodeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSBarCode field.
	/// </summary>
	public string PARSBarCodeDefault
	{
		get
		{
			return TableUtils.PARSBarCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public string PARSCarrierDispatchPhone
	{
		get
		{
			return this.GetValue(TableUtils.PARSCarrierDispatchPhoneColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PARSCarrierDispatchPhoneColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PARSCarrierDispatchPhoneSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PARSCarrierDispatchPhoneColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSCarrierDispatchPhone field.
	/// </summary>
	public string PARSCarrierDispatchPhoneDefault
	{
		get
		{
			return TableUtils.PARSCarrierDispatchPhoneColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public string PARSDriverPhoneMobile
	{
		get
		{
			return this.GetValue(TableUtils.PARSDriverPhoneMobileColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PARSDriverPhoneMobileColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PARSDriverPhoneMobileSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PARSDriverPhoneMobileColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSDriverPhoneMobile field.
	/// </summary>
	public string PARSDriverPhoneMobileDefault
	{
		get
		{
			return TableUtils.PARSDriverPhoneMobileColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public DateTime PARSETABorder
	{
		get
		{
			return this.GetValue(TableUtils.PARSETABorderColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PARSETABorderColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PARSETABorderSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PARSETABorderColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSETABorder field.
	/// </summary>
	public string PARSETABorderDefault
	{
		get
		{
			return TableUtils.PARSETABorderColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public string PARSPortOfEntryNum
	{
		get
		{
			return this.GetValue(TableUtils.PARSPortOfEntryNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PARSPortOfEntryNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PARSPortOfEntryNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PARSPortOfEntryNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PARSPortOfEntryNum field.
	/// </summary>
	public string PARSPortOfEntryNumDefault
	{
		get
		{
			return TableUtils.PARSPortOfEntryNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public DateTime PickUpDate
	{
		get
		{
			return this.GetValue(TableUtils.PickUpDateColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PickUpDateColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PickUpDateSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PickUpDateColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.PickUpDate field.
	/// </summary>
	public string PickUpDateDefault
	{
		get
		{
			return TableUtils.PickUpDateColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public string SealNum
	{
		get
		{
			return this.GetValue(TableUtils.SealNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.SealNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool SealNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.SealNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SealNum field.
	/// </summary>
	public string SealNumDefault
	{
		get
		{
			return TableUtils.SealNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public string SNMGNum
	{
		get
		{
			return this.GetValue(TableUtils.SNMGNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.SNMGNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool SNMGNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.SNMGNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.SNMGNum field.
	/// </summary>
	public string SNMGNumDefault
	{
		get
		{
			return TableUtils.SNMGNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public string TEStatus
	{
		get
		{
			return this.GetValue(TableUtils.TEStatusColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TEStatusColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TEStatusSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TEStatusColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TEStatus field.
	/// </summary>
	public string TEStatusDefault
	{
		get
		{
			return TableUtils.TEStatusColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public Int16 TotalBoxDamaged
	{
		get
		{
			return this.GetValue(TableUtils.TotalBoxDamagedColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TotalBoxDamagedColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TotalBoxDamagedSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TotalBoxDamagedColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalBoxDamaged field.
	/// </summary>
	public string TotalBoxDamagedDefault
	{
		get
		{
			return TableUtils.TotalBoxDamagedColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public Int16 TotalCount
	{
		get
		{
			return this.GetValue(TableUtils.TotalCountColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TotalCountColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TotalCountSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TotalCountColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalCount field.
	/// </summary>
	public string TotalCountDefault
	{
		get
		{
			return TableUtils.TotalCountColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public Int16 TotalPalletCount
	{
		get
		{
			return this.GetValue(TableUtils.TotalPalletCountColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TotalPalletCountColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TotalPalletCountSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TotalPalletCountColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPalletCount field.
	/// </summary>
	public string TotalPalletCountDefault
	{
		get
		{
			return TableUtils.TotalPalletCountColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public Decimal TotalPrice
	{
		get
		{
			return this.GetValue(TableUtils.TotalPriceColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TotalPriceColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TotalPriceSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TotalPriceColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalPrice field.
	/// </summary>
	public string TotalPriceDefault
	{
		get
		{
			return TableUtils.TotalPriceColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public Int32 TotalQuantityKG
	{
		get
		{
			return this.GetValue(TableUtils.TotalQuantityKGColumn).ToInt32();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TotalQuantityKGColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TotalQuantityKGSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TotalQuantityKGColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TotalQuantityKG field.
	/// </summary>
	public string TotalQuantityKGDefault
	{
		get
		{
			return TableUtils.TotalQuantityKGColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public Decimal TransportCharges
	{
		get
		{
			return this.GetValue(TableUtils.TransportChargesColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TransportChargesColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TransportChargesSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TransportChargesColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransportCharges field.
	/// </summary>
	public string TransportChargesDefault
	{
		get
		{
			return TableUtils.TransportChargesColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public Int16 TransporterId
	{
		get
		{
			return this.GetValue(TableUtils.TransporterIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TransporterIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TransporterIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TransporterIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TransporterId field.
	/// </summary>
	public string TransporterIdDefault
	{
		get
		{
			return TableUtils.TransporterIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public string TrailerNum
	{
		get
		{
			return this.GetValue(TableUtils.TrailerNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TrailerNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TrailerNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TrailerNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TrailerNum field.
	/// </summary>
	public string TrailerNumDefault
	{
		get
		{
			return TableUtils.TrailerNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public string TruckTag
	{
		get
		{
			return this.GetValue(TableUtils.TruckTagColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TruckTagColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TruckTagSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TruckTagColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.TruckTag field.
	/// </summary>
	public string TruckTagDefault
	{
		get
		{
			return TableUtils.TruckTagColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public Int16 VesselId
	{
		get
		{
			return this.GetValue(TableUtils.VesselIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.VesselIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool VesselIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.VesselIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.VesselId field.
	/// </summary>
	public string VesselIdDefault
	{
		get
		{
			return TableUtils.VesselIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public string ZUser1
	{
		get
		{
			return this.GetValue(TableUtils.ZUser1Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ZUser1Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ZUser1Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ZUser1Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser1 field.
	/// </summary>
	public string ZUser1Default
	{
		get
		{
			return TableUtils.ZUser1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public string ZUser2
	{
		get
		{
			return this.GetValue(TableUtils.ZUser2Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ZUser2Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ZUser2Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ZUser2Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser2 field.
	/// </summary>
	public string ZUser2Default
	{
		get
		{
			return TableUtils.ZUser2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public string ZUser3
	{
		get
		{
			return this.GetValue(TableUtils.ZUser3Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ZUser3Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ZUser3Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ZUser3Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser3 field.
	/// </summary>
	public string ZUser3Default
	{
		get
		{
			return TableUtils.ZUser3Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public string ZUser4
	{
		get
		{
			return this.GetValue(TableUtils.ZUser4Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ZUser4Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ZUser4Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ZUser4Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Order_.ZUser4 field.
	/// </summary>
	public string ZUser4Default
	{
		get
		{
			return TableUtils.ZUser4Column.DefaultValue;
		}
	}


#endregion
}

}
