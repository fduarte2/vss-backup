// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CommoditySizeRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_CommoditySizeRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_CommoditySizeTable"></see> class.
/// </remarks>
/// <seealso cref="DC_CommoditySizeTable"></seealso>
/// <seealso cref="DC_CommoditySizeRecord"></seealso>
public class BaseDC_CommoditySizeRecord : PrimaryKeyRecord
{

	public readonly static DC_CommoditySizeTable TableUtils = DC_CommoditySizeTable.Instance;

	// Constructors
 
	protected BaseDC_CommoditySizeRecord() : base(TableUtils)
	{
	}

	protected BaseDC_CommoditySizeRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public ColumnValue GetSizeIdValue()
	{
		return this.GetValue(TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public Int16 GetSizeIdFieldValue()
	{
		return this.GetValue(TableUtils.SizeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public void SetSizeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.Descr field.
	/// </summary>
	public ColumnValue GetDescrValue()
	{
		return this.GetValue(TableUtils.DescrColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.Descr field.
	/// </summary>
	public string GetDescrFieldValue()
	{
		return this.GetValue(TableUtils.DescrColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Descr field.
	/// </summary>
	public void SetDescrFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DescrColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Descr field.
	/// </summary>
	public void SetDescrFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DescrColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public ColumnValue GetPriceValue()
	{
		return this.GetValue(TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public Decimal GetPriceFieldValue()
	{
		return this.GetValue(TableUtils.PriceColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public void SetPriceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public void SetPriceFieldValue(string val)
	{
		this.SetString(val, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public void SetPriceFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public void SetPriceFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public void SetPriceFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PriceColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public ColumnValue GetSizeHighValue()
	{
		return this.GetValue(TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public Int16 GetSizeHighFieldValue()
	{
		return this.GetValue(TableUtils.SizeHighColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public void SetSizeHighFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeHighColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public ColumnValue GetSizeLowValue()
	{
		return this.GetValue(TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public Int16 GetSizeLowFieldValue()
	{
		return this.GetValue(TableUtils.SizeLowColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(string val)
	{
		this.SetString(val, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public void SetSizeLowFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.SizeLowColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public ColumnValue GetWeightKGValue()
	{
		return this.GetValue(TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public Decimal GetWeightKGFieldValue()
	{
		return this.GetValue(TableUtils.WeightKGColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(string val)
	{
		this.SetString(val, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public void SetWeightKGFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.WeightKGColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.SizeId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeId field.
	/// </summary>
	public string SizeIdDefault
	{
		get
		{
			return TableUtils.SizeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.Descr field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Descr field.
	/// </summary>
	public string DescrDefault
	{
		get
		{
			return TableUtils.DescrColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.Price field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.Price field.
	/// </summary>
	public string PriceDefault
	{
		get
		{
			return TableUtils.PriceColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.SizeHigh field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeHigh field.
	/// </summary>
	public string SizeHighDefault
	{
		get
		{
			return TableUtils.SizeHighColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.SizeLow field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.SizeLow field.
	/// </summary>
	public string SizeLowDefault
	{
		get
		{
			return TableUtils.SizeLowColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CommoditySize_.WeightKG field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CommoditySize_.WeightKG field.
	/// </summary>
	public string WeightKGDefault
	{
		get
		{
			return TableUtils.WeightKGColumn.DefaultValue;
		}
	}


#endregion
}

}
