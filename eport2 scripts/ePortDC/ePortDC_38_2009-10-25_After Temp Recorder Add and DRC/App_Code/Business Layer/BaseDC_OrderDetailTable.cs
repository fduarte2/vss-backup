// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_OrderDetailTable.cs


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
/// The generated superclass for the <see cref="DC_OrderDetailTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_OrderDetail.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_OrderDetailTable.Instance">DC_OrderDetailTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_OrderDetailTable"></seealso>
[SerializableAttribute()]
public class BaseDC_OrderDetailTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_OrderDetailDefinition.GetXMLString();







    protected BaseDC_OrderDetailTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_OrderDetailTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_OrderDetailRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_OrderDetailSqlTable();
        ((DC_OrderDetailSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_OrderDetailSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn OrderNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn OrderNum
    {
        get
        {
            return DC_OrderDetailTable.Instance.OrderNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderDetailId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn OrderDetailIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderDetailId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn OrderDetailId
    {
        get
        {
            return DC_OrderDetailTable.Instance.OrderDetailIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.Comments column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.Comments column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Comments
    {
        get
        {
            return DC_OrderDetailTable.Instance.CommentsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.DeliveredQty column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn DeliveredQtyColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.DeliveredQty column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn DeliveredQty
    {
        get
        {
            return DC_OrderDetailTable.Instance.DeliveredQtyColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderQty column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn OrderQtyColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderQty column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn OrderQty
    {
        get
        {
            return DC_OrderDetailTable.Instance.OrderQtyColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderSizeId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn OrderSizeIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.OrderSizeId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn OrderSizeId
    {
        get
        {
            return DC_OrderDetailTable.Instance.OrderSizeIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.Price column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn PriceColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.Price column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn Price
    {
        get
        {
            return DC_OrderDetailTable.Instance.PriceColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.SizeHigh column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn SizeHighColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.SizeHigh column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn SizeHigh
    {
        get
        {
            return DC_OrderDetailTable.Instance.SizeHighColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.SizeLow column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn SizeLowColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[8];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.SizeLow column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn SizeLow
    {
        get
        {
            return DC_OrderDetailTable.Instance.SizeLowColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.WeightKG column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn WeightKGColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[9];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.WeightKG column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn WeightKG
    {
        get
        {
            return DC_OrderDetailTable.Instance.WeightKGColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser1 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser1Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[10];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser1 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser1
    {
        get
        {
            return DC_OrderDetailTable.Instance.ZUser1Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser2 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser2Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[11];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser2 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser2
    {
        get
        {
            return DC_OrderDetailTable.Instance.ZUser2Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser3 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser3Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[12];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser3 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser3
    {
        get
        {
            return DC_OrderDetailTable.Instance.ZUser3Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser4 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser4Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[13];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_OrderDetail_.ZUser4 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser4
    {
        get
        {
            return DC_OrderDetailTable.Instance.ZUser4Column;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderDetailRecord records using a where clause.
    /// </summary>
    public static DC_OrderDetailRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderDetailRecord records using a where and order by clause.
    /// </summary>
    public static DC_OrderDetailRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderDetailRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_OrderDetailRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_OrderDetailTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_OrderDetailRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord"));
    }   
    
    public static DC_OrderDetailRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_OrderDetailTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_OrderDetailRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_OrderDetailRecord"));
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

        return (int)DC_OrderDetailTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_OrderDetailTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_OrderDetailRecord record using a where clause.
    /// </summary>
    public static DC_OrderDetailRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_OrderDetailRecord record using a where and order by clause.
    /// </summary>
    public static DC_OrderDetailRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_OrderDetailTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_OrderDetailRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_OrderDetailRecord)recList[0];
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

        return DC_OrderDetailTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_OrderDetailRecord[] recs = GetRecords(where);
        return  DC_OrderDetailTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_OrderDetailRecord[] recs = GetRecords(where, orderBy);
        return  DC_OrderDetailTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_OrderDetailRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_OrderDetailTable.Instance.CreateDataTable(recs, null);
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
        DC_OrderDetailTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_OrderDetailTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_OrderDetailTable.Instance.ExportRecordData(whereFilter);
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

        return DC_OrderDetailTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_OrderDetailTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_OrderDetailTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_OrderDetailTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_OrderDetailTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_OrderDetailTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_OrderDetailTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_OrderDetailRecord GetRecord(string id, bool bMutable)
        {
            return (DC_OrderDetailRecord)DC_OrderDetailTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_OrderDetailRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_OrderDetailRecord)DC_OrderDetailTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string OrderNumValue, 
        string CommentsValue, 
        string DeliveredQtyValue, 
        string OrderQtyValue, 
        string OrderSizeIdValue, 
        string PriceValue, 
        string SizeHighValue, 
        string SizeLowValue, 
        string WeightKGValue, 
        string ZUser1Value, 
        string ZUser2Value, 
        string ZUser3Value, 
        string ZUser4Value
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(OrderNumValue, OrderNumColumn);
        rec.SetString(CommentsValue, CommentsColumn);
        rec.SetString(DeliveredQtyValue, DeliveredQtyColumn);
        rec.SetString(OrderQtyValue, OrderQtyColumn);
        rec.SetString(OrderSizeIdValue, OrderSizeIdColumn);
        rec.SetString(PriceValue, PriceColumn);
        rec.SetString(SizeHighValue, SizeHighColumn);
        rec.SetString(SizeLowValue, SizeLowColumn);
        rec.SetString(WeightKGValue, WeightKGColumn);
        rec.SetString(ZUser1Value, ZUser1Column);
        rec.SetString(ZUser2Value, ZUser2Column);
        rec.SetString(ZUser3Value, ZUser3Column);
        rec.SetString(ZUser4Value, ZUser4Column);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_OrderDetailTable.Instance.DeleteOneRecord(kv);
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
				DC_OrderDetailTable.GetRecord(kv, false);
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
            if (!(DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_OrderDetailTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_OrderDetailTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
