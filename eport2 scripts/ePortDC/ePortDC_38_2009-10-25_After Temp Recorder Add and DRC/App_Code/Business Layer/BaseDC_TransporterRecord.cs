// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_TransporterRecord.cs

using System;
using System.Collections;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_TransporterRecord"></see> class.
/// </summary>
/// <remarks>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, 
/// use the methods of the <see cref="DC_TransporterTable"></see> class.
/// </remarks>
/// <seealso cref="DC_TransporterTable"></seealso>
/// <seealso cref="DC_TransporterRecord"></seealso>
public class BaseDC_TransporterRecord : PrimaryKeyRecord
{

	public readonly static DC_TransporterTable TableUtils = DC_TransporterTable.Instance;

	// Constructors
 
	protected BaseDC_TransporterRecord() : base(TableUtils)
	{
	}

	protected BaseDC_TransporterRecord(PrimaryKeyRecord record) : base(record, TableUtils)
	{
	}







#region "Convenience methods to get/set values of fields"

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public ColumnValue GetTransporterIdValue()
	{
		return this.GetValue(TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public Int16 GetTransporterIdFieldValue()
	{
		return this.GetValue(TableUtils.TransporterIdColumn).ToInt16();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(string val)
	{
		this.SetString(val, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public void SetTransporterIdFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.TransporterIdColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public ColumnValue GetCarrierNameValue()
	{
		return this.GetValue(TableUtils.CarrierNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public string GetCarrierNameFieldValue()
	{
		return this.GetValue(TableUtils.CarrierNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public void SetCarrierNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CarrierNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public void SetCarrierNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CarrierNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Comments field.
	/// </summary>
	public ColumnValue GetCommentsValue()
	{
		return this.GetValue(TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Comments field.
	/// </summary>
	public string GetCommentsFieldValue()
	{
		return this.GetValue(TableUtils.CommentsColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.CommentsColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Comments field.
	/// </summary>
	public void SetCommentsFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.CommentsColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.ContactName field.
	/// </summary>
	public ColumnValue GetContactNameValue()
	{
		return this.GetValue(TableUtils.ContactNameColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.ContactName field.
	/// </summary>
	public string GetContactNameFieldValue()
	{
		return this.GetValue(TableUtils.ContactNameColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.ContactName field.
	/// </summary>
	public void SetContactNameFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.ContactNameColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.ContactName field.
	/// </summary>
	public void SetContactNameFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.ContactNameColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Email field.
	/// </summary>
	public ColumnValue GetEmailValue()
	{
		return this.GetValue(TableUtils.EmailColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Email field.
	/// </summary>
	public string GetEmailFieldValue()
	{
		return this.GetValue(TableUtils.EmailColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Email field.
	/// </summary>
	public void SetEmailFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.EmailColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Email field.
	/// </summary>
	public void SetEmailFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.EmailColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Fax field.
	/// </summary>
	public ColumnValue GetFaxValue()
	{
		return this.GetValue(TableUtils.FaxColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Fax field.
	/// </summary>
	public string GetFaxFieldValue()
	{
		return this.GetValue(TableUtils.FaxColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Fax field.
	/// </summary>
	public void SetFaxFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.FaxColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Fax field.
	/// </summary>
	public void SetFaxFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.FaxColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public ColumnValue GetIRSNumValue()
	{
		return this.GetValue(TableUtils.IRSNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public string GetIRSNumFieldValue()
	{
		return this.GetValue(TableUtils.IRSNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public void SetIRSNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.IRSNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public void SetIRSNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.IRSNumColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public ColumnValue GetPhone1Value()
	{
		return this.GetValue(TableUtils.Phone1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public string GetPhone1FieldValue()
	{
		return this.GetValue(TableUtils.Phone1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public void SetPhone1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Phone1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public void SetPhone1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Phone1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public ColumnValue GetPhone2Value()
	{
		return this.GetValue(TableUtils.Phone2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public string GetPhone2FieldValue()
	{
		return this.GetValue(TableUtils.Phone2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public void SetPhone2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Phone2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public void SetPhone2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Phone2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public ColumnValue GetPhoneCell1Value()
	{
		return this.GetValue(TableUtils.PhoneCell1Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public string GetPhoneCell1FieldValue()
	{
		return this.GetValue(TableUtils.PhoneCell1Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public void SetPhoneCell1FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneCell1Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public void SetPhoneCell1FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneCell1Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public ColumnValue GetPhoneCell2Value()
	{
		return this.GetValue(TableUtils.PhoneCell2Column);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public string GetPhoneCell2FieldValue()
	{
		return this.GetValue(TableUtils.PhoneCell2Column).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public void SetPhoneCell2FieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.PhoneCell2Column);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public void SetPhoneCell2FieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.PhoneCell2Column);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public ColumnValue GetRate1GTAMiltonWhitbyValue()
	{
		return this.GetValue(TableUtils.Rate1GTAMiltonWhitbyColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public Decimal GetRate1GTAMiltonWhitbyFieldValue()
	{
		return this.GetValue(TableUtils.Rate1GTAMiltonWhitbyColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public void SetRate1GTAMiltonWhitbyFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate1GTAMiltonWhitbyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public void SetRate1GTAMiltonWhitbyFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate1GTAMiltonWhitbyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public void SetRate1GTAMiltonWhitbyFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate1GTAMiltonWhitbyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public void SetRate1GTAMiltonWhitbyFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate1GTAMiltonWhitbyColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public void SetRate1GTAMiltonWhitbyFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate1GTAMiltonWhitbyColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public ColumnValue GetRate2CambridgeValue()
	{
		return this.GetValue(TableUtils.Rate2CambridgeColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public Decimal GetRate2CambridgeFieldValue()
	{
		return this.GetValue(TableUtils.Rate2CambridgeColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public void SetRate2CambridgeFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate2CambridgeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public void SetRate2CambridgeFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate2CambridgeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public void SetRate2CambridgeFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate2CambridgeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public void SetRate2CambridgeFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate2CambridgeColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public void SetRate2CambridgeFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate2CambridgeColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public ColumnValue GetRate3OttawaValue()
	{
		return this.GetValue(TableUtils.Rate3OttawaColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public Decimal GetRate3OttawaFieldValue()
	{
		return this.GetValue(TableUtils.Rate3OttawaColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public void SetRate3OttawaFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate3OttawaColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public void SetRate3OttawaFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate3OttawaColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public void SetRate3OttawaFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate3OttawaColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public void SetRate3OttawaFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate3OttawaColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public void SetRate3OttawaFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate3OttawaColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public ColumnValue GetRate4MontrealValue()
	{
		return this.GetValue(TableUtils.Rate4MontrealColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public Decimal GetRate4MontrealFieldValue()
	{
		return this.GetValue(TableUtils.Rate4MontrealColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public void SetRate4MontrealFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate4MontrealColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public void SetRate4MontrealFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate4MontrealColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public void SetRate4MontrealFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate4MontrealColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public void SetRate4MontrealFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate4MontrealColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public void SetRate4MontrealFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate4MontrealColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public ColumnValue GetRate5QuebecValue()
	{
		return this.GetValue(TableUtils.Rate5QuebecColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public Decimal GetRate5QuebecFieldValue()
	{
		return this.GetValue(TableUtils.Rate5QuebecColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public void SetRate5QuebecFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate5QuebecColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public void SetRate5QuebecFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate5QuebecColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public void SetRate5QuebecFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate5QuebecColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public void SetRate5QuebecFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate5QuebecColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public void SetRate5QuebecFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate5QuebecColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public ColumnValue GetRate6MonctonValue()
	{
		return this.GetValue(TableUtils.Rate6MonctonColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public Decimal GetRate6MonctonFieldValue()
	{
		return this.GetValue(TableUtils.Rate6MonctonColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public void SetRate6MonctonFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate6MonctonColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public void SetRate6MonctonFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate6MonctonColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public void SetRate6MonctonFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate6MonctonColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public void SetRate6MonctonFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate6MonctonColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public void SetRate6MonctonFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate6MonctonColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public ColumnValue GetRate7DebertValue()
	{
		return this.GetValue(TableUtils.Rate7DebertColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public Decimal GetRate7DebertFieldValue()
	{
		return this.GetValue(TableUtils.Rate7DebertColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public void SetRate7DebertFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate7DebertColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public void SetRate7DebertFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate7DebertColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public void SetRate7DebertFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate7DebertColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public void SetRate7DebertFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate7DebertColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public void SetRate7DebertFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate7DebertColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public ColumnValue GetRate8OtherValue()
	{
		return this.GetValue(TableUtils.Rate8OtherColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public Decimal GetRate8OtherFieldValue()
	{
		return this.GetValue(TableUtils.Rate8OtherColumn).ToDecimal();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public void SetRate8OtherFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.Rate8OtherColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public void SetRate8OtherFieldValue(string val)
	{
		this.SetString(val, TableUtils.Rate8OtherColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public void SetRate8OtherFieldValue(double val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate8OtherColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public void SetRate8OtherFieldValue(decimal val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate8OtherColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public void SetRate8OtherFieldValue(long val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.Rate8OtherColumn);
	}
	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public ColumnValue GetUSBondNumValue()
	{
		return this.GetValue(TableUtils.USBondNumColumn);
	}

	/// <summary>
	/// This is a convenience method that provides direct access to the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public string GetUSBondNumFieldValue()
	{
		return this.GetValue(TableUtils.USBondNumColumn).ToString();
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public void SetUSBondNumFieldValue(ColumnValue val)
	{
		this.SetValue(val, TableUtils.USBondNumColumn);
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public void SetUSBondNumFieldValue(string val)
	{
		ColumnValue cv = new ColumnValue(val);
		this.SetValue(cv, TableUtils.USBondNumColumn);
	}


#endregion

#region "Convenience methods to get field names"

	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public Int16 TransporterId
	{
		get
		{
			return this.GetValue(TableUtils.TransporterIdColumn).ToInt16();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.TransporterIdColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool TransporterIdSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.TransporterIdColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.TransporterId field.
	/// </summary>
	public string TransporterIdDefault
	{
		get
		{
			return TableUtils.TransporterIdColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public string CarrierName
	{
		get
		{
			return this.GetValue(TableUtils.CarrierNameColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.CarrierNameColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool CarrierNameSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.CarrierNameColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.CarrierName field.
	/// </summary>
	public string CarrierNameDefault
	{
		get
		{
			return TableUtils.CarrierNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Comments field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Comments field.
	/// </summary>
	public string CommentsDefault
	{
		get
		{
			return TableUtils.CommentsColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.ContactName field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.ContactName field.
	/// </summary>
	public string ContactNameDefault
	{
		get
		{
			return TableUtils.ContactNameColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Email field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Email field.
	/// </summary>
	public string EmailDefault
	{
		get
		{
			return TableUtils.EmailColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Fax field.
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
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Fax field.
	/// </summary>
	public string FaxDefault
	{
		get
		{
			return TableUtils.FaxColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public string IRSNum
	{
		get
		{
			return this.GetValue(TableUtils.IRSNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.IRSNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool IRSNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.IRSNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.IRSNum field.
	/// </summary>
	public string IRSNumDefault
	{
		get
		{
			return TableUtils.IRSNumColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public string Phone1
	{
		get
		{
			return this.GetValue(TableUtils.Phone1Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Phone1Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Phone1Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Phone1Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone1 field.
	/// </summary>
	public string Phone1Default
	{
		get
		{
			return TableUtils.Phone1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public string Phone2
	{
		get
		{
			return this.GetValue(TableUtils.Phone2Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Phone2Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Phone2Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Phone2Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Phone2 field.
	/// </summary>
	public string Phone2Default
	{
		get
		{
			return TableUtils.Phone2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public string PhoneCell1
	{
		get
		{
			return this.GetValue(TableUtils.PhoneCell1Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PhoneCell1Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PhoneCell1Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PhoneCell1Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell1 field.
	/// </summary>
	public string PhoneCell1Default
	{
		get
		{
			return TableUtils.PhoneCell1Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public string PhoneCell2
	{
		get
		{
			return this.GetValue(TableUtils.PhoneCell2Column).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.PhoneCell2Column);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool PhoneCell2Specified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.PhoneCell2Column);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.PhoneCell2 field.
	/// </summary>
	public string PhoneCell2Default
	{
		get
		{
			return TableUtils.PhoneCell2Column.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public Decimal Rate1GTAMiltonWhitby
	{
		get
		{
			return this.GetValue(TableUtils.Rate1GTAMiltonWhitbyColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate1GTAMiltonWhitbyColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate1GTAMiltonWhitbySpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate1GTAMiltonWhitbyColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate1GTAMiltonWhitby field.
	/// </summary>
	public string Rate1GTAMiltonWhitbyDefault
	{
		get
		{
			return TableUtils.Rate1GTAMiltonWhitbyColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public Decimal Rate2Cambridge
	{
		get
		{
			return this.GetValue(TableUtils.Rate2CambridgeColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate2CambridgeColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate2CambridgeSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate2CambridgeColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate2Cambridge field.
	/// </summary>
	public string Rate2CambridgeDefault
	{
		get
		{
			return TableUtils.Rate2CambridgeColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public Decimal Rate3Ottawa
	{
		get
		{
			return this.GetValue(TableUtils.Rate3OttawaColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate3OttawaColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate3OttawaSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate3OttawaColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate3Ottawa field.
	/// </summary>
	public string Rate3OttawaDefault
	{
		get
		{
			return TableUtils.Rate3OttawaColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public Decimal Rate4Montreal
	{
		get
		{
			return this.GetValue(TableUtils.Rate4MontrealColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate4MontrealColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate4MontrealSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate4MontrealColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate4Montreal field.
	/// </summary>
	public string Rate4MontrealDefault
	{
		get
		{
			return TableUtils.Rate4MontrealColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public Decimal Rate5Quebec
	{
		get
		{
			return this.GetValue(TableUtils.Rate5QuebecColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate5QuebecColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate5QuebecSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate5QuebecColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate5Quebec field.
	/// </summary>
	public string Rate5QuebecDefault
	{
		get
		{
			return TableUtils.Rate5QuebecColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public Decimal Rate6Moncton
	{
		get
		{
			return this.GetValue(TableUtils.Rate6MonctonColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate6MonctonColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate6MonctonSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate6MonctonColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate6Moncton field.
	/// </summary>
	public string Rate6MonctonDefault
	{
		get
		{
			return TableUtils.Rate6MonctonColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public Decimal Rate7Debert
	{
		get
		{
			return this.GetValue(TableUtils.Rate7DebertColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate7DebertColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate7DebertSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate7DebertColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate7Debert field.
	/// </summary>
	public string Rate7DebertDefault
	{
		get
		{
			return TableUtils.Rate7DebertColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public Decimal Rate8Other
	{
		get
		{
			return this.GetValue(TableUtils.Rate8OtherColumn).ToDecimal();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.Rate8OtherColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool Rate8OtherSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.Rate8OtherColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.Rate8Other field.
	/// </summary>
	public string Rate8OtherDefault
	{
		get
		{
			return TableUtils.Rate8OtherColumn.DefaultValue;
		}
	}
	/// <summary>
	/// This is a property that provides direct access to the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public string USBondNum
	{
		get
		{
			return this.GetValue(TableUtils.USBondNumColumn).ToString();
		}
		set
		{
			ColumnValue cv = new ColumnValue(value);
			this.SetValue(cv, TableUtils.USBondNumColumn);
		}
	}


	/// <summary>
	/// This is a convenience method that can be used to determine that the column is set.
	/// </summary>
	public bool USBondNumSpecified
	{
		get
		{
			ColumnValue val = this.GetValue(TableUtils.USBondNumColumn);
            if (val == null || val.IsNull)
            {
                return false;
            }
            return true;
		}
	}

	/// <summary>
	/// This is a convenience method that allows direct modification of the value of the record's DC_Transporter_.USBondNum field.
	/// </summary>
	public string USBondNumDefault
	{
		get
		{
			return TableUtils.USBondNumColumn.DefaultValue;
		}
	}


#endregion
}

}
