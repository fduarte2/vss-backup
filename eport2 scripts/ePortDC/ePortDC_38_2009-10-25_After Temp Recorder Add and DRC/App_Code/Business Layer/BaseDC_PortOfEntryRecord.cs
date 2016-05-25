// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_PortOfEntryRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_PortOfEntryRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_PortOfEntryTable"></see> class.
/// </remarks>
/// <seealso cref="DC_PortOfEntryTable"></seealso>
/// <seealso cref="DC_PortOfEntryRecord"></seealso>
public class BaseDC_PortOfEntryRecord : PrimaryKeyRecord
{

	public readonly static DC_PortOfEntryTable TableUtils = DC_PortOfEntryTable.Instance;

	// Constructors
 
	protected BaseDC_PortOfEntryRecord() : base(TableUtils)
	{
	}

	protected BaseDC_PortOfEntryRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public ColumnValue GetPortCodeValue()
	{
		return this.GetValue(TableUtils.PortCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public string GetPortCodeFieldValue()
	{
		return this.GetValue(TableUtils.PortCodeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public void SetPortCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PortCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public void SetPortCodeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PortCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public ColumnValue GetPortNameValue()
	{
		return this.GetValue(TableUtils.PortNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public string GetPortNameFieldValue()
	{
		return this.GetValue(TableUtils.PortNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public void SetPortNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PortNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public void SetPortNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PortNameColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public string PortCode
	{
		get
		{
			return this.GetValue(TableUtils.PortCodeColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PortCodeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PortCodeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PortCodeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortCode field.
	/// </summary>
	public string PortCodeDefault
	{
		get
		{
			return TableUtils.PortCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public string PortName
	{
		get
		{
			return this.GetValue(TableUtils.PortNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PortNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PortNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PortNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PortOfEntry_.PortName field.
	/// </summary>
	public string PortNameDefault
	{
		get
		{
			return TableUtils.PortNameColumn.DefaultValue;
		}
	}


#endregion
}

}
