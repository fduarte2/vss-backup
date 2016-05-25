// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_UserTable.cs


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
/// The generated superclass for the <see cref="DC_UserTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_User.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_UserTable.Instance">DC_UserTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_UserTable"></seealso>
[SerializableAttribute()]
public class BaseDC_UserTable : PrimaryKeyTable, IUserIdentityTable, IUserRoleTable
{

    private readonly string TableDefinitionString = DC_UserDefinition.GetXMLString();

#region "IUserTable Members"

	//Get the column that specifies the user's unique identifier
	public virtual BaseClasses.Data.BaseColumn UserId
	{
		get
		{
			return (BaseClasses.Data.BaseColumn)this.TableDefinition.ColumnList[0];
		}
	}

	//Use the "explicit interface member implementation" feature to make 
	//the IUserTable.UserIdColumn Interface property an alias for the virtual UserId property. 
	BaseClasses.Data.BaseColumn BaseClasses.IUserTable.UserIdColumn
	{
		get
		{
			return this.UserId;
		}
	}

	//Get a list of records that match the criteria specified in a filter
	public virtual ArrayList GetRecordList(
		string userId, 
		BaseClasses.Data.BaseFilter filter, 
		BaseClasses.Data.OrderBy orderBy, 
		int pageNumber, 
		int batchSize, 
		ref int totalRows)
	{
		if (userId != null)
		{
			filter = BaseFilter.CombineFilters(
				CompoundFilter.CompoundingOperators.And_Operator, 
				filter, 
				BaseFilter.CreateUserIdFilter(((IUserTable)this), userId));
		}
		return ((BaseClasses.ITable)this).GetRecordList(filter, orderBy, pageNumber, batchSize, ref totalRows);
	}

#endregion



#region "IUserIdentityTable Members"

	//Get the column that specifies the user's name
	public virtual BaseClasses.Data.BaseColumn UserName
	{
		get
		{
			return (BaseClasses.Data.BaseColumn)this.TableDefinition.ColumnList[0];
		}
	}

	//Use the "explicit interface member implementation" feature to make 
	//the IUserIdentityTable.UserNameColumn Interface property an alias for the virtual UserName property. 
	BaseClasses.Data.BaseColumn BaseClasses.IUserIdentityTable.UserNameColumn
	{
		get
		{
			return this.UserName;
		}
	}

	//Get the column that specifies the user's password
	public virtual BaseClasses.Data.BaseColumn UserPassword
	{
		get
		{
			return (BaseClasses.Data.BaseColumn)this.TableDefinition.ColumnList[3];
		}
	}

	//Use the "explicit interface member implementation" feature to make 
	//the IUserIdentityTable.UserPasswordColumn Interface property an alias for the virtual UserPassword property. 
	BaseClasses.Data.BaseColumn BaseClasses.IUserIdentityTable.UserPasswordColumn
	{
		get
		{
			return this.UserPassword;
		}
	}

	//Get the column that specifies the user's email address
	public virtual BaseClasses.Data.BaseColumn UserEmail
	{
		get
		{
			return (BaseClasses.Data.BaseColumn)null;
		}
	}

	//Use the "explicit interface member implementation" feature to make 
	//the IUserIdentityTable.UserEmailColumn Interface property an alias for the virtual UserEmail property. 
	BaseClasses.Data.BaseColumn BaseClasses.IUserIdentityTable.UserEmailColumn
	{
		get
		{
			return this.UserEmail;
		}
	}

	//Get a role table object
	public virtual BaseClasses.IUserRoleTable GetUserRoleTable()
	{
		return (BaseClasses.IUserRoleTable)this;
	}

	//Get a list of records that match the user's name/password
	public virtual ArrayList GetRecordList(
		string userName, 
		string userPassword, 
		BaseClasses.Data.BaseFilter filter, 
		BaseClasses.Data.OrderBy orderBy, 
		int pageNumber, 
		int batchSize, 
		ref int totalRows)
	{
		//Set up a name/password filter   
		if ((userName != null) || (userPassword != null))
		{
			filter = BaseFilter.CombineFilters(
				CompoundFilter.CompoundingOperators.And_Operator,
				filter,
				BaseFilter.CreateUserAuthenticationFilter(((IUserIdentityTable)this), userName, userPassword));
		}
		return ((BaseClasses.ITable)this).GetRecordList(filter, orderBy, pageNumber, batchSize, ref totalRows);
	}

#endregion


#region "IUserRoleTable Members"

	//Get the column that specifies role values
	public virtual BaseClasses.Data.BaseColumn UserRole
	{
		get
		{
			return (BaseClasses.Data.BaseColumn)this.TableDefinition.ColumnList[7];
		}
	}

	//Use the "explicit interface member implementation" feature to make 
	//the IUserRoleTable.UserRoleColumn Interface property an alias for the virtual UserRole property. 
	BaseClasses.Data.BaseColumn BaseClasses.IUserRoleTable.UserRoleColumn
	{
		get
		{
			return this.UserRole;
		}
	}

#endregion


    protected BaseDC_UserTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_UserTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_UserRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_UserSqlTable();
        ((DC_UserSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_UserSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.UserId column object.
    /// </summary>
    public BaseClasses.Data.StringColumn UserId0Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.UserId column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn UserId0
    {
        get
        {
            return DC_UserTable.Instance.UserId0Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Email column object.
    /// </summary>
    public BaseClasses.Data.EmailColumn EmailColumn
    {
        get
        {
            return (BaseClasses.Data.EmailColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Email column object.
    /// </summary>
    public static BaseClasses.Data.EmailColumn Email
    {
        get
        {
            return DC_UserTable.Instance.EmailColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Name column object.
    /// </summary>
    public BaseClasses.Data.StringColumn NameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Name column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Name
    {
        get
        {
            return DC_UserTable.Instance.NameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Password column object.
    /// </summary>
    public BaseClasses.Data.PasswordColumn PasswordColumn
    {
        get
        {
            return (BaseClasses.Data.PasswordColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Password column object.
    /// </summary>
    public static BaseClasses.Data.PasswordColumn Password
    {
        get
        {
            return DC_UserTable.Instance.PasswordColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Phone column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Phone column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Phone
    {
        get
        {
            return DC_UserTable.Instance.PhoneColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.PhoneMobile column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PhoneMobileColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.PhoneMobile column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PhoneMobile
    {
        get
        {
            return DC_UserTable.Instance.PhoneMobileColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Printer column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PrinterColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Printer column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Printer
    {
        get
        {
            return DC_UserTable.Instance.PrinterColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Role column object.
    /// </summary>
    public BaseClasses.Data.StringColumn RoleColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_User_.Role column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Role
    {
        get
        {
            return DC_UserTable.Instance.RoleColumn;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_UserRecord records using a where clause.
    /// </summary>
    public static DC_UserRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_UserRecord records using a where and order by clause.
    /// </summary>
    public static DC_UserRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_UserRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_UserRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_UserTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_UserRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_UserRecord"));
    }   
    
    public static DC_UserRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_UserTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_UserRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_UserRecord"));
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

        return (int)DC_UserTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_UserTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_UserRecord record using a where clause.
    /// </summary>
    public static DC_UserRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_UserRecord record using a where and order by clause.
    /// </summary>
    public static DC_UserRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_UserTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_UserRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_UserRecord)recList[0];
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

        return DC_UserTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_UserRecord[] recs = GetRecords(where);
        return  DC_UserTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_UserRecord[] recs = GetRecords(where, orderBy);
        return  DC_UserTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_UserRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_UserTable.Instance.CreateDataTable(recs, null);
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
        DC_UserTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_UserTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_UserTable.Instance.ExportRecordData(whereFilter);
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

        return DC_UserTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_UserTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_UserTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_UserTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_UserTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_UserTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_UserTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_UserRecord GetRecord(string id, bool bMutable)
        {
            return (DC_UserRecord)DC_UserTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_UserRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_UserRecord)DC_UserTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string UserId0Value, 
        string EmailValue, 
        string NameValue, 
        string PasswordValue, 
        string PhoneValue, 
        string PhoneMobileValue, 
        string PrinterValue, 
        string RoleValue
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(UserId0Value, UserId0Column);
        rec.SetString(EmailValue, EmailColumn);
        rec.SetString(NameValue, NameColumn);
        rec.SetString(PasswordValue, PasswordColumn);
        rec.SetString(PhoneValue, PhoneColumn);
        rec.SetString(PhoneMobileValue, PhoneMobileColumn);
        rec.SetString(PrinterValue, PrinterColumn);
        rec.SetString(RoleValue, RoleColumn);


            rec.Create(); //update the DB so any DB-initialized fields (like autoincrement IDs) can be initialized

            return rec.GetID();
        }
        
        /// <summary>
		///  This method deletes a specified record
		/// </summary>
		/// <param name="kv">Keyvalue of the record to be deleted.</param>
		public static void DeleteRecord(KeyValue kv)
		{
			DC_UserTable.Instance.DeleteOneRecord(kv);
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
				DC_UserTable.GetRecord(kv, false);
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
            if (!(DC_UserTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_UserTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_UserTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_UserTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_UserTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_UserTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_UserTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
