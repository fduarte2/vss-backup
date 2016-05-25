// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_ConsigneeTable.cs


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
/// The generated superclass for the <see cref="DC_ConsigneeTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_Consignee.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_ConsigneeTable.Instance">DC_ConsigneeTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_ConsigneeTable"></seealso>
[SerializableAttribute()]
public class BaseDC_ConsigneeTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_ConsigneeDefinition.GetXMLString();







    protected BaseDC_ConsigneeTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_ConsigneeTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_ConsigneeRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_ConsigneeSqlTable();
        ((DC_ConsigneeSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_ConsigneeSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.ConsigneeId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn ConsigneeIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.ConsigneeId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn ConsigneeId
    {
        get
        {
            return DC_ConsigneeTable.Instance.ConsigneeIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Address column object.
    /// </summary>
    public BaseClasses.Data.StringColumn AddressColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Address column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Address
    {
        get
        {
            return DC_ConsigneeTable.Instance.AddressColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.CustomsBrokerOfficeId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn CustomsBrokerOfficeIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.CustomsBrokerOfficeId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn CustomsBrokerOfficeId
    {
        get
        {
            return DC_ConsigneeTable.Instance.CustomsBrokerOfficeIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.City column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CityColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.City column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn City
    {
        get
        {
            return DC_ConsigneeTable.Instance.CityColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Comments column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Comments column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Comments
    {
        get
        {
            return DC_ConsigneeTable.Instance.CommentsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.ConsigneeName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ConsigneeNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.ConsigneeName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ConsigneeName
    {
        get
        {
            return DC_ConsigneeTable.Instance.ConsigneeNameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.CustomerId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn CustomerIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.CustomerId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn CustomerId
    {
        get
        {
            return DC_ConsigneeTable.Instance.CustomerIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Phone column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.Phone column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Phone
    {
        get
        {
            return DC_ConsigneeTable.Instance.PhoneColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.PhoneMobile column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneMobileColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[8];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.PhoneMobile column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PhoneMobile
    {
        get
        {
            return DC_ConsigneeTable.Instance.PhoneMobileColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.PostalCode column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PostalCodeColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[9];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.PostalCode column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PostalCode
    {
        get
        {
            return DC_ConsigneeTable.Instance.PostalCodeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.StateProvince column object.
    /// </summary>
    public BaseClasses.Data.StringColumn StateProvinceColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[10];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Consignee_.StateProvince column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn StateProvince
    {
        get
        {
            return DC_ConsigneeTable.Instance.StateProvinceColumn;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_ConsigneeRecord records using a where clause.
    /// </summary>
    public static DC_ConsigneeRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_ConsigneeRecord records using a where and order by clause.
    /// </summary>
    public static DC_ConsigneeRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_ConsigneeRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_ConsigneeRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_ConsigneeTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_ConsigneeRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord"));
    }   
    
    public static DC_ConsigneeRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_ConsigneeTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_ConsigneeRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_ConsigneeRecord"));
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

        return (int)DC_ConsigneeTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_ConsigneeTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_ConsigneeRecord record using a where clause.
    /// </summary>
    public static DC_ConsigneeRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_ConsigneeRecord record using a where and order by clause.
    /// </summary>
    public static DC_ConsigneeRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_ConsigneeTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_ConsigneeRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_ConsigneeRecord)recList[0];
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

        return DC_ConsigneeTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_ConsigneeRecord[] recs = GetRecords(where);
        return  DC_ConsigneeTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_ConsigneeRecord[] recs = GetRecords(where, orderBy);
        return  DC_ConsigneeTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_ConsigneeRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_ConsigneeTable.Instance.CreateDataTable(recs, null);
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
        DC_ConsigneeTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_ConsigneeTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_ConsigneeTable.Instance.ExportRecordData(whereFilter);
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

        return DC_ConsigneeTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_ConsigneeTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_ConsigneeTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_ConsigneeTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_ConsigneeTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_ConsigneeTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_ConsigneeTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_ConsigneeRecord GetRecord(string id, bool bMutable)
        {
            return (DC_ConsigneeRecord)DC_ConsigneeTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_ConsigneeRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_ConsigneeRecord)DC_ConsigneeTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string ConsigneeIdValue, 
        string AddressValue, 
        string CustomsBrokerOfficeIdValue, 
        string CityValue, 
        string CommentsValue, 
        string ConsigneeNameValue, 
        string CustomerIdValue, 
        string PhoneValue, 
        string PhoneMobileValue, 
        string PostalCodeValue, 
        string StateProvinceValue
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(ConsigneeIdValue, ConsigneeIdColumn);
        rec.SetString(AddressValue, AddressColumn);
        rec.SetString(CustomsBrokerOfficeIdValue, CustomsBrokerOfficeIdColumn);
        rec.SetString(CityValue, CityColumn);
        rec.SetString(CommentsValue, CommentsColumn);
        rec.SetString(ConsigneeNameValue, ConsigneeNameColumn);
        rec.SetString(CustomerIdValue, CustomerIdColumn);
        rec.SetString(PhoneValue, PhoneColumn);
        rec.SetString(PhoneMobileValue, PhoneMobileColumn);
        rec.SetString(PostalCodeValue, PostalCodeColumn);
        rec.SetString(StateProvinceValue, StateProvinceColumn);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_ConsigneeTable.Instance.DeleteOneRecord(kv);
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
				DC_ConsigneeTable.GetRecord(kv, false);
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
            if (!(DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_ConsigneeTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_ConsigneeTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
