// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_VesselTable.cs


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
/// The generated superclass for the <see cref="DC_VesselTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_Vessel.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_VesselTable.Instance">DC_VesselTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_VesselTable"></seealso>
[SerializableAttribute()]
public class BaseDC_VesselTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_VesselDefinition.GetXMLString();







    protected BaseDC_VesselTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_VesselTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_VesselRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_VesselSqlTable();
        ((DC_VesselSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_VesselSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.VesselId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn VesselIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.VesselId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn VesselId
    {
        get
        {
            return DC_VesselTable.Instance.VesselIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.ArrivalDate column object.
    /// </summary>
    public BaseClasses.Data.DateColumn ArrivalDateColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.ArrivalDate column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn ArrivalDate
    {
        get
        {
            return DC_VesselTable.Instance.ArrivalDateColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.FixedFreight column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn FixedFreightColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.FixedFreight column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn FixedFreight
    {
        get
        {
            return DC_VesselTable.Instance.FixedFreightColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.VesselName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn VesselNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Vessel_.VesselName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn VesselName
    {
        get
        {
            return DC_VesselTable.Instance.VesselNameColumn;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_VesselRecord records using a where clause.
    /// </summary>
    public static DC_VesselRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_VesselRecord records using a where and order by clause.
    /// </summary>
    public static DC_VesselRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_VesselRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_VesselRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_VesselTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_VesselRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord"));
    }   
    
    public static DC_VesselRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_VesselTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_VesselRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_VesselRecord"));
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

        return (int)DC_VesselTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_VesselTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_VesselRecord record using a where clause.
    /// </summary>
    public static DC_VesselRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_VesselRecord record using a where and order by clause.
    /// </summary>
    public static DC_VesselRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_VesselTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_VesselRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_VesselRecord)recList[0];
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

        return DC_VesselTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_VesselRecord[] recs = GetRecords(where);
        return  DC_VesselTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_VesselRecord[] recs = GetRecords(where, orderBy);
        return  DC_VesselTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_VesselRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_VesselTable.Instance.CreateDataTable(recs, null);
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
        DC_VesselTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_VesselTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_VesselTable.Instance.ExportRecordData(whereFilter);
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

        return DC_VesselTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_VesselTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_VesselTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_VesselTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_VesselTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_VesselTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_VesselTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_VesselRecord GetRecord(string id, bool bMutable)
        {
            return (DC_VesselRecord)DC_VesselTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_VesselRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_VesselRecord)DC_VesselTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string VesselIdValue, 
        string ArrivalDateValue, 
        string FixedFreightValue, 
        string VesselNameValue
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(VesselIdValue, VesselIdColumn);
        rec.SetString(ArrivalDateValue, ArrivalDateColumn);
        rec.SetString(FixedFreightValue, FixedFreightColumn);
        rec.SetString(VesselNameValue, VesselNameColumn);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_VesselTable.Instance.DeleteOneRecord(kv);
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
				DC_VesselTable.GetRecord(kv, false);
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
            if (!(DC_VesselTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_VesselTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_VesselTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_VesselTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_VesselTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_VesselTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_VesselTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
