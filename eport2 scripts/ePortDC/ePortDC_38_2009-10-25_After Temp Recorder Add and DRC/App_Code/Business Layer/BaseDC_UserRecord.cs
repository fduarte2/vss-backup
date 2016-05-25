// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_UserRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_UserRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_UserTable"></see> class.
/// </remarks>
/// <seealso cref="DC_UserTable"></seealso>
/// <seealso cref="DC_UserRecord"></seealso>
public class BaseDC_UserRecord : PrimaryKeyRecord, IUserIdentityRecord, IUserRoleRecord
{

	public readonly static DC_UserTable TableUtils = DC_UserTable.Instance;

	// Constructors
 
	protected BaseDC_UserRecord() : base(TableUtils)
	{
	}

	protected BaseDC_UserRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}

#region "IUserRecord Members"

	//Get the user's unique identifier
	public string GetUserId()
	{
		return this.GetString(((BaseClasses.IUserTable)this.TableAccess).UserIdColumn);
	}

#endregion


#region "IUserIdentityRecord Members"

	//Get the user's name
	public string GetUserName()
	{
		return this.GetString(((BaseClasses.IUserIdentityTable)this.TableAccess).UserNameColumn);
	}

	//Get the user's password
	public string GetUserPassword()
	{
		return this.GetString(((BaseClasses.IUserIdentityTable)this.TableAccess).UserPasswordColumn);
	}

	//Get the user's email address
	public string GetUserEmail()
	{
		return this.GetString(((BaseClasses.IUserIdentityTable)this.TableAccess).UserEmailColumn);
	}

	//Get a list of roles to which the user belongs
	public string[] GetUserRoles()
	{
		string[] roles;
		if ((this as BaseClasses.IUserRoleRecord) != null)
		{
			string role = ((BaseClasses.IUserRoleRecord)this).GetUserRole();
			roles = new string[]{role};
		}
		else
		{
			BaseClasses.IUserRoleTable roleTable = 
				((BaseClasses.IUserIdentityTable)this.TableAccess).GetUserRoleTable();
			if (roleTable == null)
			{
				return null;
			}
			else
			{
				ColumnValueFilter filter = BaseFilter.CreateUserIdFilter(roleTable, this.GetUserId());
				BaseClasses.Data.OrderBy order = new BaseClasses.Data.OrderBy(false, false);
				ArrayList roleRecords = roleTable.GetRecordList(
					filter, 
					order, 
					BaseClasses.Data.BaseTable.MIN_PAGE_NUMBER, 
					BaseClasses.Data.BaseTable.MAX_BATCH_SIZE);
				ArrayList roleList = new ArrayList(roleRecords.Count);
				foreach (BaseClasses.IUserRoleRecord roleRecord in roleRecords)
				{
					roleList.Add(roleRecord.GetUserRole());
				}
				roles = (string[])roleList.ToArray(typeof(string));
			}
		}
		return roles;
	}

#endregion


#region "IUserRoleRecord Members"

	//Get the role to which this user belongs
	public string GetUserRole()
	{
		return this.GetString(((BaseClasses.IUserRoleTable)this.TableAccess).UserRoleColumn);
	}

#endregion


#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.UserId field.
	/// </summary>
	public ColumnValue GetUserId0Value()
	{
		return this.GetValue(TableUtils.UserId0Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.UserId field.
	/// </summary>
	public string GetUserId0FieldValue()
	{
		return this.GetValue(TableUtils.UserId0Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.UserId field.
	/// </summary>
	public void SetUserId0FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.UserId0Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.UserId field.
	/// </summary>
	public void SetUserId0FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.UserId0Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Email field.
	/// </summary>
	public ColumnValue GetEmailValue()
	{
		return this.GetValue(TableUtils.EmailColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Email field.
	/// </summary>
	public string GetEmailFieldValue()
	{
		return this.GetValue(TableUtils.EmailColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Email field.
	/// </summary>
	public void SetEmailFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.EmailColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Email field.
	/// </summary>
	public void SetEmailFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.EmailColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Name field.
	/// </summary>
	public ColumnValue GetNameValue()
	{
		return this.GetValue(TableUtils.NameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Name field.
	/// </summary>
	public string GetNameFieldValue()
	{
		return this.GetValue(TableUtils.NameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Name field.
	/// </summary>
	public void SetNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.NameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Name field.
	/// </summary>
	public void SetNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.NameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Password field.
	/// </summary>
	public ColumnValue GetPasswordValue()
	{
		return this.GetValue(TableUtils.PasswordColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Password field.
	/// </summary>
	public string GetPasswordFieldValue()
	{
		return this.GetValue(TableUtils.PasswordColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Password field.
	/// </summary>
	public void SetPasswordFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PasswordColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Password field.
	/// </summary>
	public void SetPasswordFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PasswordColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Phone field.
	/// </summary>
	public ColumnValue GetPhoneValue()
	{
		return this.GetValue(TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Phone field.
	/// </summary>
	public string GetPhoneFieldValue()
	{
		return this.GetValue(TableUtils.PhoneColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Phone field.
	/// </summary>
	public void SetPhoneFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.PhoneMobile field.
	/// </summary>
	public ColumnValue GetPhoneMobileValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.PhoneMobile field.
	/// </summary>
	public string GetPhoneMobileFieldValue()
	{
		return this.GetValue(TableUtils.PhoneMobileColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneMobileColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.PhoneMobile field.
	/// </summary>
	public void SetPhoneMobileFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneMobileColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Printer field.
	/// </summary>
	public ColumnValue GetPrinterValue()
	{
		return this.GetValue(TableUtils.PrinterColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Printer field.
	/// </summary>
	public string GetPrinterFieldValue()
	{
		return this.GetValue(TableUtils.PrinterColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Printer field.
	/// </summary>
	public void SetPrinterFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PrinterColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Printer field.
	/// </summary>
	public void SetPrinterFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PrinterColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Role field.
	/// </summary>
	public ColumnValue GetRoleValue()
	{
		return this.GetValue(TableUtils.RoleColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_User_.Role field.
	/// </summary>
	public string GetRoleFieldValue()
	{
		return this.GetValue(TableUtils.RoleColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Role field.
	/// </summary>
	public void SetRoleFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.RoleColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Role field.
	/// </summary>
	public void SetRoleFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.RoleColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.UserId field.
	/// </summary>
	public string UserId0
	{
		get
		{
			return this.GetValue(TableUtils.UserId0Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.UserId0Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool UserId0Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.UserId0Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.UserId field.
	/// </summary>
	public string UserId0Default
	{
		get
		{
			return TableUtils.UserId0Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Email field.
	/// </summary>
	public string Email
	{
		get
		{
			return this.GetValue(TableUtils.EmailColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.EmailColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool EmailSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.EmailColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Email field.
	/// </summary>
	public string EmailDefault
	{
		get
		{
			return TableUtils.EmailColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Name field.
	/// </summary>
	public string Name
	{
		get
		{
			return this.GetValue(TableUtils.NameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.NameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool NameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.NameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Name field.
	/// </summary>
	public string NameDefault
	{
		get
		{
			return TableUtils.NameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Password field.
	/// </summary>
	public string Password
	{
		get
		{
			return this.GetValue(TableUtils.PasswordColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PasswordColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PasswordSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PasswordColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Password field.
	/// </summary>
	public string PasswordDefault
	{
		get
		{
			return TableUtils.PasswordColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Phone field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Phone field.
	/// </summary>
	public string PhoneDefault
	{
		get
		{
			return TableUtils.PhoneColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.PhoneMobile field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.PhoneMobile field.
	/// </summary>
	public string PhoneMobileDefault
	{
		get
		{
			return TableUtils.PhoneMobileColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Printer field.
	/// </summary>
	public string Printer
	{
		get
		{
			return this.GetValue(TableUtils.PrinterColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PrinterColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PrinterSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PrinterColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Printer field.
	/// </summary>
	public string PrinterDefault
	{
		get
		{
			return TableUtils.PrinterColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_User_.Role field.
	/// </summary>
	public string Role
	{
		get
		{
			return this.GetValue(TableUtils.RoleColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.RoleColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool RoleSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.RoleColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_User_.Role field.
	/// </summary>
	public string RoleDefault
	{
		get
		{
			return TableUtils.RoleColumn.DefaultValue;
		}
	}


#endregion
}

}
