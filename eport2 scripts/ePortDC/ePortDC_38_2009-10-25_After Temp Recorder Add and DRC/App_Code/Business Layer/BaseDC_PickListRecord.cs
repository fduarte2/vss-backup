// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_PickListRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_PickListRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_PickListTable"></see> class.
/// </remarks>
/// <seealso cref="DC_PickListTable"></seealso>
/// <seealso cref="DC_PickListRecord"></seealso>
public class BaseDC_PickListRecord : PrimaryKeyRecord
{

	public readonly static DC_PickListTable TableUtils = DC_PickListTable.Instance;

	// Constructors
 
	protected BaseDC_PickListRecord() : base(TableUtils)
	{
	}

	protected BaseDC_PickListRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.OrderNum field.
	/// </summary>
	public ColumnValue GetOrderNumValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.OrderNum field.
	/// </summary>
	public string GetOrderNumFieldValue()
	{
		return this.GetValue(TableUtils.OrderNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderNum field.
	/// </summary>
	public void SetOrderNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public ColumnValue GetOrderDetailIdValue()
	{
		return this.GetValue(TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public Int32 GetOrderDetailIdFieldValue()
	{
		return this.GetValue(TableUtils.OrderDetailIdColumn).ToInt32();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public void SetOrderDetailIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public void SetOrderDetailIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public void SetOrderDetailIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public void SetOrderDetailIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderDetailIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public void SetOrderDetailIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderDetailIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PickListId field.
	/// </summary>
	public ColumnValue GetPickListIdValue()
	{
		return this.GetValue(TableUtils.PickListIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PickListId field.
	/// </summary>
	public Int32 GetPickListIdFieldValue()
	{
		return this.GetValue(TableUtils.PickListIdColumn).ToInt32();
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public ColumnValue GetPackHouseIdValue()
	{
		return this.GetValue(TableUtils.PackHouseIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public string GetPackHouseIdFieldValue()
	{
		return this.GetValue(TableUtils.PackHouseIdColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public void SetPackHouseIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PackHouseIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public void SetPackHouseIdFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PackHouseIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public ColumnValue GetPalletLocationValue()
	{
		return this.GetValue(TableUtils.PalletLocationColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public string GetPalletLocationFieldValue()
	{
		return this.GetValue(TableUtils.PalletLocationColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public void SetPalletLocationFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PalletLocationColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public void SetPalletLocationFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PalletLocationColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public ColumnValue GetPalletQtyValue()
	{
		return this.GetValue(TableUtils.PalletQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public Int16 GetPalletQtyFieldValue()
	{
		return this.GetValue(TableUtils.PalletQtyColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public void SetPalletQtyFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PalletQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public void SetPalletQtyFieldValue(string val)
	{
		this.SetString(val, TableUtils.PalletQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public void SetPalletQtyFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PalletQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public void SetPalletQtyFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PalletQtyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public void SetPalletQtyFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PalletQtyColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public ColumnValue GetPickListSizeValue()
	{
		return this.GetValue(TableUtils.PickListSizeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public Int16 GetPickListSizeFieldValue()
	{
		return this.GetValue(TableUtils.PickListSizeColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public void SetPickListSizeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PickListSizeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public void SetPickListSizeFieldValue(string val)
	{
		this.SetString(val, TableUtils.PickListSizeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public void SetPickListSizeFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PickListSizeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public void SetPickListSizeFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PickListSizeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public void SetPickListSizeFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PickListSizeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser1 field.
	/// </summary>
	public ColumnValue GetZUser1Value()
	{
		return this.GetValue(TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser1 field.
	/// </summary>
	public string GetZUser1FieldValue()
	{
		return this.GetValue(TableUtils.ZUser1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser1 field.
	/// </summary>
	public void SetZUser1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser2 field.
	/// </summary>
	public ColumnValue GetZUser2Value()
	{
		return this.GetValue(TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser2 field.
	/// </summary>
	public string GetZUser2FieldValue()
	{
		return this.GetValue(TableUtils.ZUser2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser2 field.
	/// </summary>
	public void SetZUser2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser3 field.
	/// </summary>
	public ColumnValue GetZUser3Value()
	{
		return this.GetValue(TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser3 field.
	/// </summary>
	public string GetZUser3FieldValue()
	{
		return this.GetValue(TableUtils.ZUser3Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser3Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser3 field.
	/// </summary>
	public void SetZUser3FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser3Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser4 field.
	/// </summary>
	public ColumnValue GetZUser4Value()
	{
		return this.GetValue(TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PickList_.ZUser4 field.
	/// </summary>
	public string GetZUser4FieldValue()
	{
		return this.GetValue(TableUtils.ZUser4Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ZUser4Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser4 field.
	/// </summary>
	public void SetZUser4FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ZUser4Column);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.OrderNum field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderNum field.
	/// </summary>
	public string OrderNumDefault
	{
		get
		{
			return TableUtils.OrderNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.OrderDetailId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.OrderDetailId field.
	/// </summary>
	public string OrderDetailIdDefault
	{
		get
		{
			return TableUtils.OrderDetailIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.PickListId field.
	/// </summary>
	public Int32 PickListId
	{
		get
		{
			return this.GetValue(TableUtils.PickListIdColumn).ToInt32();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PickListIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PickListIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PickListIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListId field.
	/// </summary>
	public string PickListIdDefault
	{
		get
		{
			return TableUtils.PickListIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public string PackHouseId
	{
		get
		{
			return this.GetValue(TableUtils.PackHouseIdColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PackHouseIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PackHouseIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PackHouseIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PackHouseId field.
	/// </summary>
	public string PackHouseIdDefault
	{
		get
		{
			return TableUtils.PackHouseIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public string PalletLocation
	{
		get
		{
			return this.GetValue(TableUtils.PalletLocationColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PalletLocationColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PalletLocationSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PalletLocationColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletLocation field.
	/// </summary>
	public string PalletLocationDefault
	{
		get
		{
			return TableUtils.PalletLocationColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public Int16 PalletQty
	{
		get
		{
			return this.GetValue(TableUtils.PalletQtyColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PalletQtyColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PalletQtySpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PalletQtyColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PalletQty field.
	/// </summary>
	public string PalletQtyDefault
	{
		get
		{
			return TableUtils.PalletQtyColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public Int16 PickListSize
	{
		get
		{
			return this.GetValue(TableUtils.PickListSizeColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PickListSizeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PickListSizeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PickListSizeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.PickListSize field.
	/// </summary>
	public string PickListSizeDefault
	{
		get
		{
			return TableUtils.PickListSizeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.ZUser1 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser1 field.
	/// </summary>
	public string ZUser1Default
	{
		get
		{
			return TableUtils.ZUser1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.ZUser2 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser2 field.
	/// </summary>
	public string ZUser2Default
	{
		get
		{
			return TableUtils.ZUser2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.ZUser3 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser3 field.
	/// </summary>
	public string ZUser3Default
	{
		get
		{
			return TableUtils.ZUser3Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PickList_.ZUser4 field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PickList_.ZUser4 field.
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
