// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_ConsigneeRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_ConsigneeRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_ConsigneeTable"></see> class.
/// </remarks>
/// <seealso cref="DC_ConsigneeTable"></seealso>
/// <seealso cref="DC_ConsigneeRecord"></seealso>
public class BaseDC_ConsigneeRecord : PrimaryKeyRecord
{

	public readonly static DC_ConsigneeTable TableUtils = DC_ConsigneeTable.Instance;

	// Constructors
 
	protected BaseDC_ConsigneeRecord() : base(TableUtils)
	{
	}

	protected BaseDC_ConsigneeRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public ColumnValue GetConsigneeIdValue()
	{
		return this.GetValue(TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public Int16 GetConsigneeIdFieldValue()
	{
		return this.GetValue(TableUtils.ConsigneeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public void SetConsigneeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public ColumnValue GetAddressValue()
	{
		return this.GetValue(TableUtils.AddressColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public string GetAddressFieldValue()
	{
		return this.GetValue(TableUtils.AddressColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public void SetAddressFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.AddressColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public void SetAddressFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.AddressColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public ColumnValue GetCustomsBrokerOfficeIdValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public Int16 GetCustomsBrokerOfficeIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomsBrokerOfficeIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public void SetCustomsBrokerOfficeIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomsBrokerOfficeIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.City field.
	/// </summary>
	public ColumnValue GetCityValue()
	{
		return this.GetValue(TableUtils.CityColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.City field.
	/// </summary>
	public string GetCityFieldValue()
	{
		return this.GetValue(TableUtils.CityColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.City field.
	/// </summary>
	public void SetCityFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CityColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.City field.
	/// </summary>
	public void SetCityFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CityColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public ColumnValue GetConsigneeNameValue()
	{
		return this.GetValue(TableUtils.ConsigneeNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public string GetConsigneeNameFieldValue()
	{
		return this.GetValue(TableUtils.ConsigneeNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public void SetConsigneeNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ConsigneeNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public void SetConsigneeNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ConsigneeNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public ColumnValue GetCustomerIdValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public Int16 GetCustomerIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Phone field.
	/// </summary>
	public ColumnValue GetPhoneValue()
	{
		return this.GetValue(TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.Phone field.
	/// </summary>
	public string GetPhoneFieldValue()
	{
		return this.GetValue(TableUtils.PhoneColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public ColumnValue GetPhoneMobileValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public string GetPhoneMobileFieldValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneMobileColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public ColumnValue GetPostalCodeValue()
	{
		return this.GetValue(TableUtils.PostalCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public string GetPostalCodeFieldValue()
	{
		return this.GetValue(TableUtils.PostalCodeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public void SetPostalCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PostalCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public void SetPostalCodeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PostalCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public ColumnValue GetStateProvinceValue()
	{
		return this.GetValue(TableUtils.StateProvinceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public string GetStateProvinceFieldValue()
	{
		return this.GetValue(TableUtils.StateProvinceColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public void SetStateProvinceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.StateProvinceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public void SetStateProvinceFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.StateProvinceColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public Int16 ConsigneeId
	{
		get
		{
			return this.GetValue(TableUtils.ConsigneeIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ConsigneeIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ConsigneeIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ConsigneeIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeId field.
	/// </summary>
	public string ConsigneeIdDefault
	{
		get
		{
			return TableUtils.ConsigneeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public string Address
	{
		get
		{
			return this.GetValue(TableUtils.AddressColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.AddressColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool AddressSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.AddressColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Address field.
	/// </summary>
	public string AddressDefault
	{
		get
		{
			return TableUtils.AddressColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomsBrokerOfficeId field.
	/// </summary>
	public string CustomsBrokerOfficeIdDefault
	{
		get
		{
			return TableUtils.CustomsBrokerOfficeIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.City field.
	/// </summary>
	public string City
	{
		get
		{
			return this.GetValue(TableUtils.CityColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CityColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CitySpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CityColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.City field.
	/// </summary>
	public string CityDefault
	{
		get
		{
			return TableUtils.CityColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public string ConsigneeName
	{
		get
		{
			return this.GetValue(TableUtils.ConsigneeNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.ConsigneeNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool ConsigneeNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.ConsigneeNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.ConsigneeName field.
	/// </summary>
	public string ConsigneeNameDefault
	{
		get
		{
			return TableUtils.ConsigneeNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.CustomerId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.CustomerId field.
	/// </summary>
	public string CustomerIdDefault
	{
		get
		{
			return TableUtils.CustomerIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.Phone field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.Phone field.
	/// </summary>
	public string PhoneDefault
	{
		get
		{
			return TableUtils.PhoneColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public string PhoneMobile
	{
		get
		{
			return this.GetValue(TableUtils.PhoneMobileColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PhoneMobileColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PhoneMobileSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PhoneMobileColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PhoneMobile field.
	/// </summary>
	public string PhoneMobileDefault
	{
		get
		{
			return TableUtils.PhoneMobileColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public string PostalCode
	{
		get
		{
			return this.GetValue(TableUtils.PostalCodeColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PostalCodeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PostalCodeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PostalCodeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.PostalCode field.
	/// </summary>
	public string PostalCodeDefault
	{
		get
		{
			return TableUtils.PostalCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public string StateProvince
	{
		get
		{
			return this.GetValue(TableUtils.StateProvinceColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.StateProvinceColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool StateProvinceSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.StateProvinceColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Consignee_.StateProvince field.
	/// </summary>
	public string StateProvinceDefault
	{
		get
		{
			return TableUtils.StateProvinceColumn.DefaultValue;
		}
	}


#endregion
}

}
