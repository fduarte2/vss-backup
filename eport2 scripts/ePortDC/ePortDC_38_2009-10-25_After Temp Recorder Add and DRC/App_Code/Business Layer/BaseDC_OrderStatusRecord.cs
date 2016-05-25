// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_OrderStatusRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_OrderStatusRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_OrderStatusTable"></see> class.
/// </remarks>
/// <seealso cref="DC_OrderStatusTable"></seealso>
/// <seealso cref="DC_OrderStatusRecord"></seealso>
public class BaseDC_OrderStatusRecord : PrimaryKeyRecord
{

	public readonly static DC_OrderStatusTable TableUtils = DC_OrderStatusTable.Instance;

	// Constructors
 
	protected BaseDC_OrderStatusRecord() : base(TableUtils)
	{
	}

	protected BaseDC_OrderStatusRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public ColumnValue GetOrderStatusIdValue()
	{
		return this.GetValue(TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public Int16 GetOrderStatusIdFieldValue()
	{
		return this.GetValue(TableUtils.OrderStatusIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public void SetOrderStatusIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OrderStatusIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public ColumnValue GetDescrValue()
	{
		return this.GetValue(TableUtils.DescrColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public string GetDescrFieldValue()
	{
		return this.GetValue(TableUtils.DescrColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public void SetDescrFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DescrColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public void SetDescrFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DescrColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderStatus_.OrderStatusId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.OrderStatusId field.
	/// </summary>
	public string OrderStatusIdDefault
	{
		get
		{
			return TableUtils.OrderStatusIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public string Descr
	{
		get
		{
			return this.GetValue(TableUtils.DescrColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DescrColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DescrSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DescrColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_OrderStatus_.Descr field.
	/// </summary>
	public string DescrDefault
	{
		get
		{
			return TableUtils.DescrColumn.DefaultValue;
		}
	}


#endregion
}

}
