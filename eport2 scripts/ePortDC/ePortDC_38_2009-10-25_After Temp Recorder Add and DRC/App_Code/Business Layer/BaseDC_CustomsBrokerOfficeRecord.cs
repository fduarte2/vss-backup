// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CustomsBrokerOfficeRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_CustomsBrokerOfficeRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_CustomsBrokerOfficeTable"></see> class.
/// </remarks>
/// <seealso cref="DC_CustomsBrokerOfficeTable"></seealso>
/// <seealso cref="DC_CustomsBrokerOfficeRecord"></seealso>
public class BaseDC_CustomsBrokerOfficeRecord : PrimaryKeyRecord
{

	public readonly static DC_CustomsBrokerOfficeTable TableUtils = DC_CustomsBrokerOfficeTable.Instance;

	// Constructors
 
	protected BaseDC_CustomsBrokerOfficeRecord() : base(TableUtils)
	{
	}

	protected BaseDC_CustomsBrokerOfficeRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public ColumnValue GetCustomsBrokerOfficeIdValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public Int16 GetCustomsBrokerOfficeIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public ColumnValue GetBorderCrossingValue()
	{
		return this.GetValue(TableUtils.BorderCrossingColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public string GetBorderCrossingFieldValue()
	{
		return this.GetValue(TableUtils.BorderCrossingColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public void SetBorderCrossingFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.BorderCrossingColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public void SetBorderCrossingFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.BorderCrossingColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public ColumnValue GetClientValue()
	{
		return this.GetValue(TableUtils.ClientColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public string GetClientFieldValue()
	{
		return this.GetValue(TableUtils.ClientColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public void SetClientFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ClientColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public void SetClientFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ClientColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public ColumnValue GetContactNameValue()
	{
		return this.GetValue(TableUtils.ContactNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public string GetContactNameFieldValue()
	{
		return this.GetValue(TableUtils.ContactNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public void SetContactNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ContactNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public void SetContactNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ContactNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public ColumnValue GetCustomsBrokerValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public string GetCustomsBrokerFieldValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public void SetCustomsBrokerFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomsBrokerColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public void SetCustomsBrokerFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public ColumnValue GetDestinationsValue()
	{
		return this.GetValue(TableUtils.DestinationsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public string GetDestinationsFieldValue()
	{
		return this.GetValue(TableUtils.DestinationsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public void SetDestinationsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DestinationsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public void SetDestinationsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DestinationsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public ColumnValue GetEmail1Value()
	{
		return this.GetValue(TableUtils.Email1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public string GetEmail1FieldValue()
	{
		return this.GetValue(TableUtils.Email1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public void SetEmail1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Email1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public void SetEmail1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Email1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public ColumnValue GetEmail2Value()
	{
		return this.GetValue(TableUtils.Email2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public string GetEmail2FieldValue()
	{
		return this.GetValue(TableUtils.Email2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public void SetEmail2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Email2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public void SetEmail2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Email2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public ColumnValue GetEmail3Value()
	{
		return this.GetValue(TableUtils.Email3Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public string GetEmail3FieldValue()
	{
		return this.GetValue(TableUtils.Email3Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public void SetEmail3FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Email3Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public void SetEmail3FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Email3Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public ColumnValue GetEmail4Value()
	{
		return this.GetValue(TableUtils.Email4Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public string GetEmail4FieldValue()
	{
		return this.GetValue(TableUtils.Email4Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public void SetEmail4FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Email4Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public void SetEmail4FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Email4Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public ColumnValue GetEmail5Value()
	{
		return this.GetValue(TableUtils.Email5Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public string GetEmail5FieldValue()
	{
		return this.GetValue(TableUtils.Email5Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public void SetEmail5FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Email5Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public void SetEmail5FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Email5Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public ColumnValue GetFaxValue()
	{
		return this.GetValue(TableUtils.FaxColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public string GetFaxFieldValue()
	{
		return this.GetValue(TableUtils.FaxColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public void SetFaxFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.FaxColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public void SetFaxFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.FaxColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public ColumnValue GetPhoneValue()
	{
		return this.GetValue(TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public string GetPhoneFieldValue()
	{
		return this.GetValue(TableUtils.PhoneColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public Int16 CustomsBrokerOfficeId
	{
		get
		{
			return this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomsBrokerOfficeIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId field.
	/// </summary>
	public string CustomsBrokerOfficeIdDefault
	{
		get
		{
			return TableUtils.CustomsBrokerOfficeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public string BorderCrossing
	{
		get
		{
			return this.GetValue(TableUtils.BorderCrossingColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.BorderCrossingColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool BorderCrossingSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.BorderCrossingColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.BorderCrossing field.
	/// </summary>
	public string BorderCrossingDefault
	{
		get
		{
			return TableUtils.BorderCrossingColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public string Client
	{
		get
		{
			return this.GetValue(TableUtils.ClientColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ClientColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ClientSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ClientColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Client field.
	/// </summary>
	public string ClientDefault
	{
		get
		{
			return TableUtils.ClientColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public string ContactName
	{
		get
		{
			return this.GetValue(TableUtils.ContactNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ContactNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ContactNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ContactNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.ContactName field.
	/// </summary>
	public string ContactNameDefault
	{
		get
		{
			return TableUtils.ContactNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public string CustomsBroker
	{
		get
		{
			return this.GetValue(TableUtils.CustomsBrokerColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomsBrokerColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomsBrokerSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomsBrokerColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.CustomsBroker field.
	/// </summary>
	public string CustomsBrokerDefault
	{
		get
		{
			return TableUtils.CustomsBrokerColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public string Destinations
	{
		get
		{
			return this.GetValue(TableUtils.DestinationsColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DestinationsColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DestinationsSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DestinationsColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Destinations field.
	/// </summary>
	public string DestinationsDefault
	{
		get
		{
			return TableUtils.DestinationsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public string Email1
	{
		get
		{
			return this.GetValue(TableUtils.Email1Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Email1Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Email1Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Email1Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email1 field.
	/// </summary>
	public string Email1Default
	{
		get
		{
			return TableUtils.Email1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public string Email2
	{
		get
		{
			return this.GetValue(TableUtils.Email2Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Email2Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Email2Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Email2Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email2 field.
	/// </summary>
	public string Email2Default
	{
		get
		{
			return TableUtils.Email2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public string Email3
	{
		get
		{
			return this.GetValue(TableUtils.Email3Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Email3Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Email3Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Email3Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email3 field.
	/// </summary>
	public string Email3Default
	{
		get
		{
			return TableUtils.Email3Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public string Email4
	{
		get
		{
			return this.GetValue(TableUtils.Email4Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Email4Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Email4Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Email4Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email4 field.
	/// </summary>
	public string Email4Default
	{
		get
		{
			return TableUtils.Email4Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public string Email5
	{
		get
		{
			return this.GetValue(TableUtils.Email5Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Email5Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Email5Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Email5Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Email5 field.
	/// </summary>
	public string Email5Default
	{
		get
		{
			return TableUtils.Email5Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public string Fax
	{
		get
		{
			return this.GetValue(TableUtils.FaxColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.FaxColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool FaxSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.FaxColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Fax field.
	/// </summary>
	public string FaxDefault
	{
		get
		{
			return TableUtils.FaxColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public string Phone
	{
		get
		{
			return this.GetValue(TableUtils.PhoneColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PhoneColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PhoneSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PhoneColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_CustomsBrokerOffice_.Phone field.
	/// </summary>
	public string PhoneDefault
	{
		get
		{
			return TableUtils.PhoneColumn.DefaultValue;
		}
	}


#endregion
}

}
