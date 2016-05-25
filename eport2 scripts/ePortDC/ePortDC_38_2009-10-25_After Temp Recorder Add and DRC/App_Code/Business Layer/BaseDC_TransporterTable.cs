// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_TransporterTable.cs


using System;
using System.Data;
using System.Collections;
using System.Runtime;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;
using ePortDC.Data;

namespace ePortDC.Business
{

/// <summary>
/// The generated superclass for the <see cref="DC_TransporterTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_Transporter.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_TransporterTable.Instance">DC_TransporterTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_TransporterTable"></seealso>
[SerializableAttribute()]
public class BaseDC_TransporterTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_TransporterDefinition.GetXMLString();







    protected BaseDC_TransporterTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_TransporterTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_TransporterRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_TransporterSqlTable();
        ((DC_TransporterSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_TransporterSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.TransporterId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TransporterIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.TransporterId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TransporterId
    {
        get
        {
            return DC_TransporterTable.Instance.TransporterIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.CarrierName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CarrierNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.CarrierName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn CarrierName
    {
        get
        {
            return DC_TransporterTable.Instance.CarrierNameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Comments column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Comments column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Comments
    {
        get
        {
            return DC_TransporterTable.Instance.CommentsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.ContactName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ContactNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.ContactName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ContactName
    {
        get
        {
            return DC_TransporterTable.Instance.ContactNameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Email column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn EmailColumn
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Email column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email
    {
        get
        {
            return DC_TransporterTable.Instance.EmailColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Fax column object.
    /// </summary>
    public BaseClasses.Data.StringColumn FaxColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Fax column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Fax
    {
        get
        {
            return DC_TransporterTable.Instance.FaxColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.IRSNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn IRSNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.IRSNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn IRSNum
    {
        get
        {
            return DC_TransporterTable.Instance.IRSNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Phone1 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn Phone1Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Phone1 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Phone1
    {
        get
        {
            return DC_TransporterTable.Instance.Phone1Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Phone2 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn Phone2Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[8];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Phone2 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Phone2
    {
        get
        {
            return DC_TransporterTable.Instance.Phone2Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.PhoneCell1 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneCell1Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[9];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.PhoneCell1 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PhoneCell1
    {
        get
        {
            return DC_TransporterTable.Instance.PhoneCell1Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.PhoneCell2 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneCell2Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[10];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.PhoneCell2 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PhoneCell2
    {
        get
        {
            return DC_TransporterTable.Instance.PhoneCell2Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate1GTAMiltonWhitby column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate1GTAMiltonWhitbyColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[11];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate1GTAMiltonWhitby column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate1GTAMiltonWhitby
    {
        get
        {
            return DC_TransporterTable.Instance.Rate1GTAMiltonWhitbyColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate2Cambridge column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate2CambridgeColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[12];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate2Cambridge column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate2Cambridge
    {
        get
        {
            return DC_TransporterTable.Instance.Rate2CambridgeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate3Ottawa column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate3OttawaColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[13];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate3Ottawa column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate3Ottawa
    {
        get
        {
            return DC_TransporterTable.Instance.Rate3OttawaColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate4Montreal column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate4MontrealColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[14];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate4Montreal column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate4Montreal
    {
        get
        {
            return DC_TransporterTable.Instance.Rate4MontrealColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate5Quebec column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate5QuebecColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[15];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate5Quebec column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate5Quebec
    {
        get
        {
            return DC_TransporterTable.Instance.Rate5QuebecColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate6Moncton column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate6MonctonColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[16];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate6Moncton column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate6Moncton
    {
        get
        {
            return DC_TransporterTable.Instance.Rate6MonctonColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate7Debert column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate7DebertColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[17];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate7Debert column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate7Debert
    {
        get
        {
            return DC_TransporterTable.Instance.Rate7DebertColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate8Other column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn Rate8OtherColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[18];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.Rate8Other column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Rate8Other
    {
        get
        {
            return DC_TransporterTable.Instance.Rate8OtherColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.USBondNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn USBondNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[19];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Transporter_.USBondNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn USBondNum
    {
        get
        {
            return DC_TransporterTable.Instance.USBondNumColumn;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_TransporterRecord records using a where clause.
    /// </summary>
    public static DC_TransporterRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_TransporterRecord records using a where and order by clause.
    /// </summary>
    public static DC_TransporterRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_TransporterRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_TransporterRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_TransporterTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_TransporterRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord"));
    }   
    
    public static DC_TransporterRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_TransporterTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_TransporterRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_TransporterRecord"));
    }

    /// <summary>
    /// This is a shared function that can be used to get total number of records that will be returned using the where clause.
    /// </summary>
    public static int GetRecordCount(string where)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        return (int)DC_TransporterTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_TransporterTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_TransporterRecord record using a where clause.
    /// </summary>
    public static DC_TransporterRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_TransporterRecord record using a where and order by clause.
    /// </summary>
    public static DC_TransporterRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_TransporterTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_TransporterRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_TransporterRecord)recList[0];
        }

        return rec;
    }
    
    public static String[] GetValues(
		BaseColumn col,
		WhereClause where,
		OrderBy orderBy,
		int maxItems)
	{

        // Create the filter list.
        SqlBuilderColumnSelection retCol = new SqlBuilderColumnSelection(false, true);
        retCol.AddColumn(col);

        return DC_TransporterTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_TransporterRecord[] recs = GetRecords(where);
        return  DC_TransporterTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_TransporterRecord[] recs = GetRecords(where, orderBy);
        return  DC_TransporterTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_TransporterRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_TransporterTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to delete records using a where clause.
    /// </summary>
    public static void DeleteRecords(string where)
    {
        if (where == null || where.Trim() == "")
        {
           return;
        }
        
        SqlFilter whereFilter = new SqlFilter(where);
        DC_TransporterTable.Instance.DeleteRecordList(whereFilter);
    }
    
    /// <summary>
    /// This is a shared function that can be used to export records using a where clause.
    /// </summary>
    public static string Export(string where)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        return  DC_TransporterTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_TransporterTable.Instance.ExportRecordData(whereFilter);
    }
    
	public static string GetSum(
		BaseColumn col,
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{
        SqlBuilderColumnSelection colSel = new SqlBuilderColumnSelection(false, false);
        colSel.AddColumn(col, SqlBuilderColumnOperation.OperationType.Sum);

        return DC_TransporterTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }
    
    public static string GetCount(
		BaseColumn col,
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{
        SqlBuilderColumnSelection colSel = new SqlBuilderColumnSelection(false, false);
        colSel.AddColumn(col, SqlBuilderColumnOperation.OperationType.Count);

        return DC_TransporterTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_TransporterTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_TransporterTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_TransporterTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_TransporterTable.Instance.CreateRecord(tempId);
    }

    /// <summary>
    /// This method checks if column is editable.
    /// </summary>
    /// <param name="columnName">Name of the column to check.</param>
    public static bool isReadOnlyColumn(string columnName) 
    {
        BaseColumn column = GetColumn(columnName);
        if (!(column == null)) 
        {
            return column.IsValuesReadOnly;
        }
        else 
        {
            return true;
        }
    }

    /// <summary>
    /// This method gets the specified column.
    /// </summary>
    /// <param name="uniqueColumnName">Unique name of the column to fetch.</param>
    public static BaseColumn GetColumn(string uniqueColumnName) 
    {
        BaseColumn column = DC_TransporterTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_TransporterRecord GetRecord(string id, bool bMutable)
        {
            return (DC_TransporterRecord)DC_TransporterTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_TransporterRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_TransporterRecord)DC_TransporterTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string TransporterIdValue, 
        string CarrierNameValue, 
        string CommentsValue, 
        string ContactNameValue, 
        string EmailValue, 
        string FaxValue, 
        string IRSNumValue, 
        string Phone1Value, 
        string Phone2Value, 
        string PhoneCell1Value, 
        string PhoneCell2Value, 
        string Rate1GTAMiltonWhitbyValue, 
        string Rate2CambridgeValue, 
        string Rate3OttawaValue, 
        string Rate4MontrealValue, 
        string Rate5QuebecValue, 
        string Rate6MonctonValue, 
        string Rate7DebertValue, 
        string Rate8OtherValue, 
        string USBondNumValue
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(TransporterIdValue, TransporterIdColumn);
        rec.SetString(CarrierNameValue, CarrierNameColumn);
        rec.SetString(CommentsValue, CommentsColumn);
        rec.SetString(ContactNameValue, ContactNameColumn);
        rec.SetString(EmailValue, EmailColumn);
        rec.SetString(FaxValue, FaxColumn);
        rec.SetString(IRSNumValue, IRSNumColumn);
        rec.SetString(Phone1Value, Phone1Column);
        rec.SetString(Phone2Value, Phone2Column);
        rec.SetString(PhoneCell1Value, PhoneCell1Column);
        rec.SetString(PhoneCell2Value, PhoneCell2Column);
        rec.SetString(Rate1GTAMiltonWhitbyValue, Rate1GTAMiltonWhitbyColumn);
        rec.SetString(Rate2CambridgeValue, Rate2CambridgeColumn);
        rec.SetString(Rate3OttawaValue, Rate3OttawaColumn);
        rec.SetString(Rate4MontrealValue, Rate4MontrealColumn);
        rec.SetString(Rate5QuebecValue, Rate5QuebecColumn);
        rec.SetString(Rate6MonctonValue, Rate6MonctonColumn);
        rec.SetString(Rate7DebertValue, Rate7DebertColumn);
        rec.SetString(Rate8OtherValue, Rate8OtherColumn);
        rec.SetString(USBondNumValue, USBondNumColumn);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_TransporterTable.Instance.DeleteOneRecord(kv);
		}

		/// <summary>
		/// This method checks if record exist in the database using the keyvalue provided.
		/// </summary>
		/// <param name="kv">Key value of the record.</param>
		public static bool DoesRecordExist(KeyValue kv)
		{
			bool recordExist = true;
			try
			{
				DC_TransporterTable.GetRecord(kv, false);
			}
			catch (Exception ex)
			{
				recordExist = false;
			}
			return recordExist;
		}

        /// <summary>
        ///  This method returns all the primary columns in the table.
        /// </summary>
        public static ColumnList GetPrimaryKeyColumns() 
        {
            if (!(DC_TransporterTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_TransporterTable.Instance.TableDefinition.PrimaryKey.Columns;
            }
            else 
            {
                return null;
            }
        }

        /// <summary>
        /// This method takes a key and returns a keyvalue.
        /// </summary>
        /// <param name="key">key could be array of primary key values in case of composite primary key or a string containing single primary key value in case of non-composite primary key.</param>
        public static KeyValue GetKeyValue(object key) 
        {
            KeyValue kv = null;
            if (!(DC_TransporterTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_TransporterTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_TransporterTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_TransporterTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
                            {
                                kv.AddElement(pkColumn.UniqueName, keyString);
                            }
                            else 
                            {
                                kv.AddElement(pkColumn.InternalName, keyString);
                            }

                            index = (index + 1);
                        }
                    }
                }
                else 
                {
                    //  If the key is not composite, then get the key value.
                    kv = DC_TransporterTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
