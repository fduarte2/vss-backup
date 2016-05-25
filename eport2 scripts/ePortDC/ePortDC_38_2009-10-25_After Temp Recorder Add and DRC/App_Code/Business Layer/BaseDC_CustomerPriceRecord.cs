// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CustomerPriceRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_CustomerPriceRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_CustomerPriceTable"></see> class.
/// </remarks>
/// <seealso cref="DC_CustomerPriceTable"></seealso>
/// <seealso cref="DC_CustomerPriceRecord"></seealso>
public class BaseDC_CustomerPriceRecord : PrimaryKeyRecord
{

	public readonly static DC_CustomerPriceTable TableUtils = DC_CustomerPriceTable.Instance;

	// Constructors
 
	protected BaseDC_CustomerPriceRecord() : base(TableUtils)
	{
	}

	protected BaseDC_CustomerPriceRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public ColumnValue GetCustomerIdValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public Int16 GetCustomerIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public ColumnValue GetCommodityCodeValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public Int16 GetCommodityCodeFieldValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(string val)
	{
		this.SetString(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public ColumnValue GetSizeIdValue()
	{
		return this.GetValue(TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public Int16 GetSizeIdFieldValue()
	{
		return this.GetValue(TableUtils.SizeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public ColumnValue GetEffectiveDateValue()
	{
		return this.GetValue(TableUtils.EffectiveDateColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public DateTime GetEffectiveDateFieldValue()
	{
		return this.GetValue(TableUtils.EffectiveDateColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public void SetEffectiveDateFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.EffectiveDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public void SetEffectiveDateFieldValue(string val)
	{
		this.SetString(val, TableUtils.EffectiveDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public void SetEffectiveDateFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.EffectiveDateColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public ColumnValue GetPriceValue()
	{
		return this.GetValue(TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public Decimal GetPriceFieldValue()
	{
		return this.GetValue(TableUtils.PriceColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public void SetPriceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public void SetPriceFieldValue(string val)
	{
		this.SetString(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public void SetPriceFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public void SetPriceFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public void SetPriceFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.CustomerId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CustomerId field.
	/// </summary>
	public string CustomerIdDefault
	{
		get
		{
			return TableUtils.CustomerIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.CommodityCode field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.CommodityCode field.
	/// </summary>
	public string CommodityCodeDefault
	{
		get
		{
			return TableUtils.CommodityCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public Int16 SizeId
	{
		get
		{
			return this.GetValue(TableUtils.SizeIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.SizeIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool SizeIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.SizeIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.SizeId field.
	/// </summary>
	public string SizeIdDefault
	{
		get
		{
			return TableUtils.SizeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public DateTime EffectiveDate
	{
		get
		{
			return this.GetValue(TableUtils.EffectiveDateColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.EffectiveDateColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool EffectiveDateSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.EffectiveDateColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.EffectiveDate field.
	/// </summary>
	public string EffectiveDateDefault
	{
		get
		{
			return TableUtils.EffectiveDateColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomerPrice_.Price field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomerPrice_.Price field.
	/// </summary>
	public string PriceDefault
	{
		get
		{
			return TableUtils.PriceColumn.DefaultValue;
		}
	}


#endregion
}

}
