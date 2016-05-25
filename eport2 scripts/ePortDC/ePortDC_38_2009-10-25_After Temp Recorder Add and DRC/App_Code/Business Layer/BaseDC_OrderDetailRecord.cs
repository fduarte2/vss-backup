// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_OrderDetailRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_OrderDetailRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_OrderDetailTable"></see> class.
/// </remarks>
/// <seealso cref="DC_OrderDetailTable"></seealso>
/// <seealso cref="DC_OrderDetailRecord"></seealso>
public class BaseDC_OrderDetailRecord : PrimaryKeyRecord
{

	public readonly static DC_OrderDetailTable TableUtils = DC_OrderDetailTable.Instance;

	// Constructors
 
	protected BaseDC_OrderDetailRecord() : base(TableUtils)
	{
	}

	protected BaseDC_OrderDetailRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderNum field.
	/// </summary>
	public ColumnValue GetOrderNumValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderNum field.
	/// </summary>
	public string GetOrderNumFieldValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderDetailId field.
	/// </summary>
	public ColumnValue GetOrderDetailIdValue()
	{
		return this.GetValue(TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderDetailId field.
	/// </summary>
	public Int32 GetOrderDetailIdFieldValue()
	{
		return this.GetValue(TableUtils.OrderDetailIdColumn).ToInt32();
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public ColumnValue GetDeliveredQtyValue()
	{
		return this.GetValue(TableUtils.DeliveredQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public Int16 GetDeliveredQtyFieldValue()
	{
		return this.GetValue(TableUtils.DeliveredQtyColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public void SetDeliveredQtyFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DeliveredQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public void SetDeliveredQtyFieldValue(string val)
	{
		this.SetString(val, TableUtils.DeliveredQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public void SetDeliveredQtyFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DeliveredQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public void SetDeliveredQtyFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DeliveredQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public void SetDeliveredQtyFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DeliveredQtyColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public ColumnValue GetOrderQtyValue()
	{
		return this.GetValue(TableUtils.OrderQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public Int16 GetOrderQtyFieldValue()
	{
		return this.GetValue(TableUtils.OrderQtyColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public void SetOrderQtyFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public void SetOrderQtyFieldValue(string val)
	{
		this.SetString(val, TableUtils.OrderQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public void SetOrderQtyFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public void SetOrderQtyFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public void SetOrderQtyFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderQtyColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public ColumnValue GetOrderSizeIdValue()
	{
		return this.GetValue(TableUtils.OrderSizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public Int16 GetOrderSizeIdFieldValue()
	{
		return this.GetValue(TableUtils.OrderSizeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public void SetOrderSizeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderSizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public void SetOrderSizeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.OrderSizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public void SetOrderSizeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderSizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public void SetOrderSizeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderSizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public void SetOrderSizeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderSizeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public ColumnValue GetPriceValue()
	{
		return this.GetValue(TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public Decimal GetPriceFieldValue()
	{
		return this.GetValue(TableUtils.PriceColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public void SetPriceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public void SetPriceFieldValue(string val)
	{
		this.SetString(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public void SetPriceFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public void SetPriceFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public void SetPriceFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public ColumnValue GetSizeHighValue()
	{
		return this.GetValue(TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public Int16 GetSizeHighFieldValue()
	{
		return this.GetValue(TableUtils.SizeHighColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public ColumnValue GetSizeLowValue()
	{
		return this.GetValue(TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public Int16 GetSizeLowFieldValue()
	{
		return this.GetValue(TableUtils.SizeLowColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public ColumnValue GetWeightKGValue()
	{
		return this.GetValue(TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public Decimal GetWeightKGFieldValue()
	{
		return this.GetValue(TableUtils.WeightKGColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(string val)
	{
		this.SetString(val, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser1 field.
	/// </summary>
	public ColumnValue GetZUser1Value()
	{
		return this.GetValue(TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser1 field.
	/// </summary>
	public string GetZUser1FieldValue()
	{
		return this.GetValue(TableUtils.ZUser1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser2 field.
	/// </summary>
	public ColumnValue GetZUser2Value()
	{
		return this.GetValue(TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser2 field.
	/// </summary>
	public string GetZUser2FieldValue()
	{
		return this.GetValue(TableUtils.ZUser2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser3 field.
	/// </summary>
	public ColumnValue GetZUser3Value()
	{
		return this.GetValue(TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser3 field.
	/// </summary>
	public string GetZUser3FieldValue()
	{
		return this.GetValue(TableUtils.ZUser3Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser3Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser4 field.
	/// </summary>
	public ColumnValue GetZUser4Value()
	{
		return this.GetValue(TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderDetail_.ZUser4 field.
	/// </summary>
	public string GetZUser4FieldValue()
	{
		return this.GetValue(TableUtils.ZUser4Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser4Column);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.OrderNum field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderNum field.
	/// </summary>
	public string OrderNumDefault
	{
		get
		{
			return TableUtils.OrderNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.OrderDetailId field.
	/// </summary>
	public Int32 OrderDetailId
	{
		get
		{
			return this.GetValue(TableUtils.OrderDetailIdColumn).ToInt32();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OrderDetailIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OrderDetailIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OrderDetailIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderDetailId field.
	/// </summary>
	public string OrderDetailIdDefault
	{
		get
		{
			return TableUtils.OrderDetailIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public Int16 DeliveredQty
	{
		get
		{
			return this.GetValue(TableUtils.DeliveredQtyColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DeliveredQtyColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DeliveredQtySpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DeliveredQtyColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.DeliveredQty field.
	/// </summary>
	public string DeliveredQtyDefault
	{
		get
		{
			return TableUtils.DeliveredQtyColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public Int16 OrderQty
	{
		get
		{
			return this.GetValue(TableUtils.OrderQtyColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OrderQtyColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OrderQtySpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OrderQtyColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderQty field.
	/// </summary>
	public string OrderQtyDefault
	{
		get
		{
			return TableUtils.OrderQtyColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public Int16 OrderSizeId
	{
		get
		{
			return this.GetValue(TableUtils.OrderSizeIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OrderSizeIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OrderSizeIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OrderSizeIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.OrderSizeId field.
	/// </summary>
	public string OrderSizeIdDefault
	{
		get
		{
			return TableUtils.OrderSizeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public Decimal Price
	{
		get
		{
			return this.GetValue(TableUtils.PriceColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PriceColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PriceSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PriceColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.Price field.
	/// </summary>
	public string PriceDefault
	{
		get
		{
			return TableUtils.PriceColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public Int16 SizeHigh
	{
		get
		{
			return this.GetValue(TableUtils.SizeHighColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.SizeHighColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool SizeHighSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.SizeHighColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeHigh field.
	/// </summary>
	public string SizeHighDefault
	{
		get
		{
			return TableUtils.SizeHighColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public Int16 SizeLow
	{
		get
		{
			return this.GetValue(TableUtils.SizeLowColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.SizeLowColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool SizeLowSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.SizeLowColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.SizeLow field.
	/// </summary>
	public string SizeLowDefault
	{
		get
		{
			return TableUtils.SizeLowColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public Decimal WeightKG
	{
		get
		{
			return this.GetValue(TableUtils.WeightKGColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.WeightKGColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool WeightKGSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.WeightKGColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.WeightKG field.
	/// </summary>
	public string WeightKGDefault
	{
		get
		{
			return TableUtils.WeightKGColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.ZUser1 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser1 field.
	/// </summary>
	public string ZUser1Default
	{
		get
		{
			return TableUtils.ZUser1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.ZUser2 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser2 field.
	/// </summary>
	public string ZUser2Default
	{
		get
		{
			return TableUtils.ZUser2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.ZUser3 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser3 field.
	/// </summary>
	public string ZUser3Default
	{
		get
		{
			return TableUtils.ZUser3Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderDetail_.ZUser4 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderDetail_.ZUser4 field.
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
