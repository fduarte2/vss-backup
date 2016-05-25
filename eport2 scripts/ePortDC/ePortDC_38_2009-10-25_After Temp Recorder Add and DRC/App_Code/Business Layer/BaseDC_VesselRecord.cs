// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_VesselRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_VesselRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_VesselTable"></see> class.
/// </remarks>
/// <seealso cref="DC_VesselTable"></seealso>
/// <seealso cref="DC_VesselRecord"></seealso>
public class BaseDC_VesselRecord : PrimaryKeyRecord
{

	public readonly static DC_VesselTable TableUtils = DC_VesselTable.Instance;

	// Constructors
 
	protected BaseDC_VesselRecord() : base(TableUtils)
	{
	}

	protected BaseDC_VesselRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public ColumnValue GetVesselIdValue()
	{
		return this.GetValue(TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public Int16 GetVesselIdFieldValue()
	{
		return this.GetValue(TableUtils.VesselIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public void SetVesselIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public ColumnValue GetArrivalDateValue()
	{
		return this.GetValue(TableUtils.ArrivalDateColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public DateTime GetArrivalDateFieldValue()
	{
		return this.GetValue(TableUtils.ArrivalDateColumn).ToDateTime();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public void SetArrivalDateFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ArrivalDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public void SetArrivalDateFieldValue(string val)
	{
		this.SetString(val, TableUtils.ArrivalDateColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public void SetArrivalDateFieldValue(DateTime val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ArrivalDateColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public ColumnValue GetFixedFreightValue()
	{
		return this.GetValue(TableUtils.FixedFreightColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public Decimal GetFixedFreightFieldValue()
	{
		return this.GetValue(TableUtils.FixedFreightColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public void SetFixedFreightFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.FixedFreightColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public void SetFixedFreightFieldValue(string val)
	{
		this.SetString(val, TableUtils.FixedFreightColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public void SetFixedFreightFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.FixedFreightColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public void SetFixedFreightFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.FixedFreightColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public void SetFixedFreightFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.FixedFreightColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public ColumnValue GetVesselNameValue()
	{
		return this.GetValue(TableUtils.VesselNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public string GetVesselNameFieldValue()
	{
		return this.GetValue(TableUtils.VesselNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public void SetVesselNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.VesselNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public void SetVesselNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.VesselNameColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Vessel_.VesselId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselId field.
	/// </summary>
	public string VesselIdDefault
	{
		get
		{
			return TableUtils.VesselIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public DateTime ArrivalDate
	{
		get
		{
			return this.GetValue(TableUtils.ArrivalDateColumn).ToDateTime();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ArrivalDateColumn);
			
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ArrivalDateSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ArrivalDateColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.ArrivalDate field.
	/// </summary>
	public string ArrivalDateDefault
	{
		get
		{
			return TableUtils.ArrivalDateColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public Decimal FixedFreight
	{
		get
		{
			return this.GetValue(TableUtils.FixedFreightColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.FixedFreightColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool FixedFreightSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.FixedFreightColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.FixedFreight field.
	/// </summary>
	public string FixedFreightDefault
	{
		get
		{
			return TableUtils.FixedFreightColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public string VesselName
	{
		get
		{
			return this.GetValue(TableUtils.VesselNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.VesselNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool VesselNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.VesselNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Vessel_.VesselName field.
	/// </summary>
	public string VesselNameDefault
	{
		get
		{
			return TableUtils.VesselNameColumn.DefaultValue;
		}
	}


#endregion
}

}
