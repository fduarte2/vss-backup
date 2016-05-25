// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CommodityRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_CommodityRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_CommodityTable"></see> class.
/// </remarks>
/// <seealso cref="DC_CommodityTable"></seealso>
/// <seealso cref="DC_CommodityRecord"></seealso>
public class BaseDC_CommodityRecord : PrimaryKeyRecord
{

	public readonly static DC_CommodityTable TableUtils = DC_CommodityTable.Instance;

	// Constructors
 
	protected BaseDC_CommodityRecord() : base(TableUtils)
	{
	}

	protected BaseDC_CommodityRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public ColumnValue GetCommodityCodeValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public Int16 GetCommodityCodeFieldValue()
	{
		return this.GetValue(TableUtils.CommodityCodeColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(string val)
	{
		this.SetString(val, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public void SetCommodityCodeFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public ColumnValue GetCommodityNameValue()
	{
		return this.GetValue(TableUtils.CommodityNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public string GetCommodityNameFieldValue()
	{
		return this.GetValue(TableUtils.CommodityNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public void SetCommodityNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommodityNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public void SetCommodityNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommodityNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public ColumnValue GetHarmonizedSystemTariffValue()
	{
		return this.GetValue(TableUtils.HarmonizedSystemTariffColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public string GetHarmonizedSystemTariffFieldValue()
	{
		return this.GetValue(TableUtils.HarmonizedSystemTariffColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public void SetHarmonizedSystemTariffFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.HarmonizedSystemTariffColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public void SetHarmonizedSystemTariffFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.HarmonizedSystemTariffColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Commodity_.CommodityCode field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityCode field.
	/// </summary>
	public string CommodityCodeDefault
	{
		get
		{
			return TableUtils.CommodityCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public string CommodityName
	{
		get
		{
			return this.GetValue(TableUtils.CommodityNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CommodityNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CommodityNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CommodityNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.CommodityName field.
	/// </summary>
	public string CommodityNameDefault
	{
		get
		{
			return TableUtils.CommodityNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public string HarmonizedSystemTariff
	{
		get
		{
			return this.GetValue(TableUtils.HarmonizedSystemTariffColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.HarmonizedSystemTariffColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool HarmonizedSystemTariffSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.HarmonizedSystemTariffColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Commodity_.HarmonizedSystemTariff field.
	/// </summary>
	public string HarmonizedSystemTariffDefault
	{
		get
		{
			return TableUtils.HarmonizedSystemTariffColumn.DefaultValue;
		}
	}


#endregion
}

}
