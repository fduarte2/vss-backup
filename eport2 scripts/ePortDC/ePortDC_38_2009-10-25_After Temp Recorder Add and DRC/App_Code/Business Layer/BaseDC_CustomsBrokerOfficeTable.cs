// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_CustomsBrokerOfficeTable.cs


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
/// The generated superclass for the <see cref="DC_CustomsBrokerOfficeTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_CustomsBrokerOffice.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_CustomsBrokerOfficeTable.Instance">DC_CustomsBrokerOfficeTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_CustomsBrokerOfficeTable"></seealso>
[SerializableAttribute()]
public class BaseDC_CustomsBrokerOfficeTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_CustomsBrokerOfficeDefinition.GetXMLString();







    protected BaseDC_CustomsBrokerOfficeTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_CustomsBrokerOfficeTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_CustomsBrokerOfficeRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_CustomsBrokerOfficeSqlTable();
        ((DC_CustomsBrokerOfficeSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_CustomsBrokerOfficeSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn CustomsBrokerOfficeIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.CustomsBrokerOfficeId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn CustomsBrokerOfficeId
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.CustomsBrokerOfficeIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.BorderCrossing column object.
    /// </summary>
    public BaseClasses.Data.StringColumn BorderCrossingColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.BorderCrossing column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn BorderCrossing
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.BorderCrossingColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Client column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ClientColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Client column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Client
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.ClientColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Comments column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Comments column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Comments
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.CommentsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.ContactName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ContactNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.ContactName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ContactName
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.ContactNameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.CustomsBroker column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CustomsBrokerColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.CustomsBroker column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn CustomsBroker
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.CustomsBrokerColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Destinations column object.
    /// </summary>
    public BaseClasses.Data.StringColumn DestinationsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Destinations column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Destinations
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.DestinationsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email1 column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn Email1Column
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email1 column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email1
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.Email1Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email2 column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn Email2Column
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[8];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email2 column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email2
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.Email2Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email3 column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn Email3Column
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[9];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email3 column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email3
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.Email3Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email4 column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn Email4Column
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[10];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email4 column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email4
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.Email4Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email5 column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn Email5Column
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[11];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Email5 column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email5
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.Email5Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Fax column object.
    /// </summary>
    public BaseClasses.Data.StringColumn FaxColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[12];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Fax column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Fax
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.FaxColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Phone column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[13];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_CustomsBrokerOffice_.Phone column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Phone
    {
        get
        {
            return DC_CustomsBrokerOfficeTable.Instance.PhoneColumn;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_CustomsBrokerOfficeRecord records using a where clause.
    /// </summary>
    public static DC_CustomsBrokerOfficeRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_CustomsBrokerOfficeRecord records using a where and order by clause.
    /// </summary>
    public static DC_CustomsBrokerOfficeRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_CustomsBrokerOfficeRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_CustomsBrokerOfficeRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_CustomsBrokerOfficeTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_CustomsBrokerOfficeRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord"));
    }   
    
    public static DC_CustomsBrokerOfficeRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_CustomsBrokerOfficeTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_CustomsBrokerOfficeRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_CustomsBrokerOfficeRecord"));
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

        return (int)DC_CustomsBrokerOfficeTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_CustomsBrokerOfficeTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_CustomsBrokerOfficeRecord record using a where clause.
    /// </summary>
    public static DC_CustomsBrokerOfficeRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_CustomsBrokerOfficeRecord record using a where and order by clause.
    /// </summary>
    public static DC_CustomsBrokerOfficeRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_CustomsBrokerOfficeTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_CustomsBrokerOfficeRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_CustomsBrokerOfficeRecord)recList[0];
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

        return DC_CustomsBrokerOfficeTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_CustomsBrokerOfficeRecord[] recs = GetRecords(where);
        return  DC_CustomsBrokerOfficeTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_CustomsBrokerOfficeRecord[] recs = GetRecords(where, orderBy);
        return  DC_CustomsBrokerOfficeTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_CustomsBrokerOfficeRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_CustomsBrokerOfficeTable.Instance.CreateDataTable(recs, null);
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
        DC_CustomsBrokerOfficeTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_CustomsBrokerOfficeTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_CustomsBrokerOfficeTable.Instance.ExportRecordData(whereFilter);
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

        return DC_CustomsBrokerOfficeTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_CustomsBrokerOfficeTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_CustomsBrokerOfficeTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_CustomsBrokerOfficeTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_CustomsBrokerOfficeTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_CustomsBrokerOfficeTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_CustomsBrokerOfficeTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_CustomsBrokerOfficeRecord GetRecord(string id, bool bMutable)
        {
            return (DC_CustomsBrokerOfficeRecord)DC_CustomsBrokerOfficeTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_CustomsBrokerOfficeRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_CustomsBrokerOfficeRecord)DC_CustomsBrokerOfficeTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string CustomsBrokerOfficeIdValue, 
        string BorderCrossingValue, 
        string ClientValue, 
        string CommentsValue, 
        string ContactNameValue, 
        string CustomsBrokerValue, 
        string DestinationsValue, 
        string Email1Value, 
        string Email2Value, 
        string Email3Value, 
        string Email4Value, 
        string Email5Value, 
        string FaxValue, 
        string PhoneValue
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(CustomsBrokerOfficeIdValue, CustomsBrokerOfficeIdColumn);
        rec.SetString(BorderCrossingValue, BorderCrossingColumn);
        rec.SetString(ClientValue, ClientColumn);
        rec.SetString(CommentsValue, CommentsColumn);
        rec.SetString(ContactNameValue, ContactNameColumn);
        rec.SetString(CustomsBrokerValue, CustomsBrokerColumn);
        rec.SetString(DestinationsValue, DestinationsColumn);
        rec.SetString(Email1Value, Email1Column);
        rec.SetString(Email2Value, Email2Column);
        rec.SetString(Email3Value, Email3Column);
        rec.SetString(Email4Value, Email4Column);
        rec.SetString(Email5Value, Email5Column);
        rec.SetString(FaxValue, FaxColumn);
        rec.SetString(PhoneValue, PhoneColumn);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_CustomsBrokerOfficeTable.Instance.DeleteOneRecord(kv);
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
				DC_CustomsBrokerOfficeTable.GetRecord(kv, false);
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
            if (!(DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_CustomsBrokerOfficeTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_CustomsBrokerOfficeTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
