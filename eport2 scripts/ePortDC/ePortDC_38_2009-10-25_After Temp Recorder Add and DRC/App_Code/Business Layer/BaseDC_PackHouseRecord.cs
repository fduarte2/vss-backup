// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_PackHouseRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_PackHouseRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_PackHouseTable"></see> class.
/// </remarks>
/// <seealso cref="DC_PackHouseTable"></seealso>
/// <seealso cref="DC_PackHouseRecord"></seealso>
public class BaseDC_PackHouseRecord : PrimaryKeyRecord
{

	public readonly static DC_PackHouseTable TableUtils = DC_PackHouseTable.Instance;

	// Constructors
 
	protected BaseDC_PackHouseRecord() : base(TableUtils)
	{
	}

	protected BaseDC_PackHouseRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.PackHouseId field.
	/// </summary>
	public ColumnValue GetPackHouseIdValue()
	{
		return this.GetValue(TableUtils.PackHouseIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.PackHouseId field.
	/// </summary>
	public string GetPackHouseIdFieldValue()
	{
		return this.GetValue(TableUtils.PackHouseIdColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseId field.
	/// </summary>
	public void SetPackHouseIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PackHouseIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseId field.
	/// </summary>
	public void SetPackHouseIdFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PackHouseIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public ColumnValue GetGroupNameValue()
	{
		return this.GetValue(TableUtils.GroupNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public string GetGroupNameFieldValue()
	{
		return this.GetValue(TableUtils.GroupNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public void SetGroupNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.GroupNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public void SetGroupNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.GroupNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public ColumnValue GetPackHouseNameValue()
	{
		return this.GetValue(TableUtils.PackHouseNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public string GetPackHouseNameFieldValue()
	{
		return this.GetValue(TableUtils.PackHouseNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public void SetPackHouseNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PackHouseNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public void SetPackHouseNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PackHouseNameColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PackHouse_.PackHouseId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseId field.
	/// </summary>
	public string PackHouseIdDefault
	{
		get
		{
			return TableUtils.PackHouseIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public string GroupName
	{
		get
		{
			return this.GetValue(TableUtils.GroupNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.GroupNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool GroupNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.GroupNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.GroupName field.
	/// </summary>
	public string GroupNameDefault
	{
		get
		{
			return TableUtils.GroupNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public string PackHouseName
	{
		get
		{
			return this.GetValue(TableUtils.PackHouseNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PackHouseNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PackHouseNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PackHouseNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_PackHouse_.PackHouseName field.
	/// </summary>
	public string PackHouseNameDefault
	{
		get
		{
			return TableUtils.PackHouseNameColumn.DefaultValue;
		}
	}


#endregion
}

}
