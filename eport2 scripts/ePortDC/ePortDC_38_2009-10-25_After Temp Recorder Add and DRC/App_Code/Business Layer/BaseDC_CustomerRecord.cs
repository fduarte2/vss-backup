// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CustomerRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_CustomerRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_CustomerTable"></see> class.
/// </remarks>
/// <seealso cref="DC_CustomerTable"></seealso>
/// <seealso cref="DC_CustomerRecord"></seealso>
public class BaseDC_CustomerRecord : PrimaryKeyRecord
{

	public readonly static DC_CustomerTable TableUtils = DC_CustomerTable.Instance;

	// Constructors
 
	protected BaseDC_CustomerRecord() : base(TableUtils)
	{
	}

	protected BaseDC_CustomerRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public ColumnValue GetCustomerIdValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public Int16 GetCustomerIdFieldValue()
	{
		return this.GetValue(TableUtils.CustomerIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public void SetCustomerIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Address field.
	/// </summary>
	public ColumnValue GetAddressValue()
	{
		return this.GetValue(TableUtils.AddressColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Address field.
	/// </summary>
	public string GetAddressFieldValue()
	{
		return this.GetValue(TableUtils.AddressColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Address field.
	/// </summary>
	public void SetAddressFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.AddressColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Address field.
	/// </summary>
	public void SetAddressFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.AddressColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.City field.
	/// </summary>
	public ColumnValue GetCityValue()
	{
		return this.GetValue(TableUtils.CityColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.City field.
	/// </summary>
	public string GetCityFieldValue()
	{
		return this.GetValue(TableUtils.CityColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.City field.
	/// </summary>
	public void SetCityFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CityColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.City field.
	/// </summary>
	public void SetCityFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CityColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public ColumnValue GetCustomerNameValue()
	{
		return this.GetValue(TableUtils.CustomerNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public string GetCustomerNameFieldValue()
	{
		return this.GetValue(TableUtils.CustomerNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public void SetCustomerNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public void SetCustomerNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public ColumnValue GetCustomerShortNameValue()
	{
		return this.GetValue(TableUtils.CustomerShortNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public string GetCustomerShortNameFieldValue()
	{
		return this.GetValue(TableUtils.CustomerShortNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public void SetCustomerShortNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CustomerShortNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public void SetCustomerShortNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CustomerShortNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public ColumnValue GetDestCodeValue()
	{
		return this.GetValue(TableUtils.DestCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public string GetDestCodeFieldValue()
	{
		return this.GetValue(TableUtils.DestCodeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public void SetDestCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.DestCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public void SetDestCodeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.DestCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public ColumnValue GetNeedPARSValue()
	{
		return this.GetValue(TableUtils.NeedPARSColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public bool GetNeedPARSFieldValue()
	{
		return this.GetValue(TableUtils.NeedPARSColumn).ToBoolean();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public void SetNeedPARSFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.NeedPARSColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public void SetNeedPARSFieldValue(string val)
	{
		this.SetString(val, TableUtils.NeedPARSColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public void SetNeedPARSFieldValue(bool val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.NeedPARSColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public ColumnValue GetOriginValue()
	{
		return this.GetValue(TableUtils.OriginColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public string GetOriginFieldValue()
	{
		return this.GetValue(TableUtils.OriginColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public void SetOriginFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.OriginColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public void SetOriginFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.OriginColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Phone field.
	/// </summary>
	public ColumnValue GetPhoneValue()
	{
		return this.GetValue(TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.Phone field.
	/// </summary>
	public string GetPhoneFieldValue()
	{
		return this.GetValue(TableUtils.PhoneColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.PhoneMobile field.
	/// </summary>
	public ColumnValue GetPhoneMobileValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.PhoneMobile field.
	/// </summary>
	public string GetPhoneMobileFieldValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneMobileColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.PostalCode field.
	/// </summary>
	public ColumnValue GetPostalCodeValue()
	{
		return this.GetValue(TableUtils.PostalCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.PostalCode field.
	/// </summary>
	public string GetPostalCodeFieldValue()
	{
		return this.GetValue(TableUtils.PostalCodeColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PostalCode field.
	/// </summary>
	public void SetPostalCodeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PostalCodeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PostalCode field.
	/// </summary>
	public void SetPostalCodeFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PostalCodeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.StateProvince field.
	/// </summary>
	public ColumnValue GetStateProvinceValue()
	{
		return this.GetValue(TableUtils.StateProvinceColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Customer_.StateProvince field.
	/// </summary>
	public string GetStateProvinceFieldValue()
	{
		return this.GetValue(TableUtils.StateProvinceColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.StateProvince field.
	/// </summary>
	public void SetStateProvinceFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.StateProvinceColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.StateProvince field.
	/// </summary>
	public void SetStateProvinceFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.StateProvinceColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.CustomerId field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerId field.
	/// </summary>
	public string CustomerIdDefault
	{
		get
		{
			return TableUtils.CustomerIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.Address field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Address field.
	/// </summary>
	public string AddressDefault
	{
		get
		{
			return TableUtils.AddressColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.City field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.City field.
	/// </summary>
	public string CityDefault
	{
		get
		{
			return TableUtils.CityColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public string CustomerName
	{
		get
		{
			return this.GetValue(TableUtils.CustomerNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomerNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomerNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomerNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerName field.
	/// </summary>
	public string CustomerNameDefault
	{
		get
		{
			return TableUtils.CustomerNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public string CustomerShortName
	{
		get
		{
			return this.GetValue(TableUtils.CustomerShortNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CustomerShortNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CustomerShortNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CustomerShortNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.CustomerShortName field.
	/// </summary>
	public string CustomerShortNameDefault
	{
		get
		{
			return TableUtils.CustomerShortNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public string DestCode
	{
		get
		{
			return this.GetValue(TableUtils.DestCodeColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.DestCodeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool DestCodeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.DestCodeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.DestCode field.
	/// </summary>
	public string DestCodeDefault
	{
		get
		{
			return TableUtils.DestCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public bool NeedPARS
	{
		get
		{
			return this.GetValue(TableUtils.NeedPARSColumn).ToBoolean();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
	   		this.SetValue(cv, TableUtils.NeedPARSColumn);
		}
	}
	
	

	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool NeedPARSSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.NeedPARSColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.NeedPARS field.
	/// </summary>
	public string NeedPARSDefault
	{
		get
		{
			return TableUtils.NeedPARSColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public string Origin
	{
		get
		{
			return this.GetValue(TableUtils.OriginColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.OriginColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool OriginSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.OriginColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Origin field.
	/// </summary>
	public string OriginDefault
	{
		get
		{
			return TableUtils.OriginColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.Phone field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.Phone field.
	/// </summary>
	public string PhoneDefault
	{
		get
		{
			return TableUtils.PhoneColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.PhoneMobile field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PhoneMobile field.
	/// </summary>
	public string PhoneMobileDefault
	{
		get
		{
			return TableUtils.PhoneMobileColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.PostalCode field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.PostalCode field.
	/// </summary>
	public string PostalCodeDefault
	{
		get
		{
			return TableUtils.PostalCodeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Customer_.StateProvince field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Customer_.StateProvince field.
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
