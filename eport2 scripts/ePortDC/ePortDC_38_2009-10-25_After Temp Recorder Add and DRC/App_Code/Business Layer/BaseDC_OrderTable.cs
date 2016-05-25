// This class is "generated" and will be overwritten.
// Your customizations should be made in DC_OrderTable.cs


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
/// The generated superclass for the <see cref="DC_OrderTable"></see> class.
/// Provides access to the schema information and record data of a database table or view named DC_Order.
/// </summary>
/// <remarks>
/// The connection details (name, location, etc.) of the database and table (or view) accessed by this class 
/// are resolved at runtime based on the connection string in the application's Web.Config file.
/// <para>
/// This class is not intended to be instantiated directly.  To obtain an instance of this class, use 
/// <see cref="DC_OrderTable.Instance">DC_OrderTable.Instance</see>.
/// </para>
/// </remarks>
/// <seealso cref="DC_OrderTable"></seealso>
[SerializableAttribute()]
public class BaseDC_OrderTable : PrimaryKeyTable
{

    private readonly string TableDefinitionString = DC_OrderDefinition.GetXMLString();







    protected BaseDC_OrderTable()
    {
        this.Initialize();
    }

    protected virtual void Initialize()
    {
        XmlTableDefinition def = new XmlTableDefinition(TableDefinitionString);
        this.TableDefinition = new TableDefinition();
        this.TableDefinition.TableClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_OrderTable");
        def.InitializeTableDefinition(this.TableDefinition);
        this.ConnectionName = def.GetConnectionName();
        this.RecordClassName = System.Reflection.Assembly.CreateQualifiedName("App_Code", "ePortDC.Business.DC_OrderRecord");
        this.ApplicationName = "App_Code";
        this.DataAdapter = new DC_OrderSqlTable();
        ((DC_OrderSqlTable)this.DataAdapter).ConnectionName = this.ConnectionName;
((DC_OrderSqlTable)this.DataAdapter).ApplicationName = this.ApplicationName;
        this.TableDefinition.AdapterMetaData = this.DataAdapter.AdapterMetaData;
    }

#region "Properties for columns"

    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.OrderNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn OrderNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[0];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.OrderNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn OrderNum
    {
        get
        {
            return DC_OrderTable.Instance.OrderNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.Comments column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[1];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.Comments column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn Comments
    {
        get
        {
            return DC_OrderTable.Instance.CommentsColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CommentsCancel column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CommentsCancelColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[2];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CommentsCancel column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn CommentsCancel
    {
        get
        {
            return DC_OrderTable.Instance.CommentsCancelColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CommodityCode column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn CommodityCodeColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[3];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CommodityCode column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn CommodityCode
    {
        get
        {
            return DC_OrderTable.Instance.CommodityCodeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ConsigneeId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn ConsigneeIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[4];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ConsigneeId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn ConsigneeId
    {
        get
        {
            return DC_OrderTable.Instance.ConsigneeIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CustomerId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn CustomerIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[5];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CustomerId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn CustomerId
    {
        get
        {
            return DC_OrderTable.Instance.CustomerIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CustomerPO column object.
    /// </summary>
    public BaseClasses.Data.StringColumn CustomerPOColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[6];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.CustomerPO column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn CustomerPO
    {
        get
        {
            return DC_OrderTable.Instance.CustomerPOColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DeliveryDate column object.
    /// </summary>
    public BaseClasses.Data.DateColumn DeliveryDateColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[7];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DeliveryDate column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn DeliveryDate
    {
        get
        {
            return DC_OrderTable.Instance.DeliveryDateColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DirectOrder column object.
    /// </summary>
    public BaseClasses.Data.StringColumn DirectOrderColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[8];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DirectOrder column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn DirectOrder
    {
        get
        {
            return DC_OrderTable.Instance.DirectOrderColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverCheckInDateTime column object.
    /// </summary>
    public BaseClasses.Data.DateColumn DriverCheckInDateTimeColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[9];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverCheckInDateTime column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn DriverCheckInDateTime
    {
        get
        {
            return DC_OrderTable.Instance.DriverCheckInDateTimeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverCheckOutDateTime column object.
    /// </summary>
    public BaseClasses.Data.DateColumn DriverCheckOutDateTimeColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[10];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverCheckOutDateTime column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn DriverCheckOutDateTime
    {
        get
        {
            return DC_OrderTable.Instance.DriverCheckOutDateTimeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverName column object.
    /// </summary>
    public BaseClasses.Data.StringColumn DriverNameColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[11];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.DriverName column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn DriverName
    {
        get
        {
            return DC_OrderTable.Instance.DriverNameColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LastUpdateUser column object.
    /// </summary>
    public BaseClasses.Data.StringColumn LastUpdateUserColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[12];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LastUpdateUser column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn LastUpdateUser
    {
        get
        {
            return DC_OrderTable.Instance.LastUpdateUserColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LastUpdateDateTime column object.
    /// </summary>
    public BaseClasses.Data.DateColumn LastUpdateDateTimeColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[13];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LastUpdateDateTime column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn LastUpdateDateTime
    {
        get
        {
            return DC_OrderTable.Instance.LastUpdateDateTimeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LoadType column object.
    /// </summary>
    public BaseClasses.Data.StringColumn LoadTypeColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[14];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.LoadType column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn LoadType
    {
        get
        {
            return DC_OrderTable.Instance.LoadTypeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.OrderStatusId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn OrderStatusIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[15];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.OrderStatusId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn OrderStatusId
    {
        get
        {
            return DC_OrderTable.Instance.OrderStatusIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSBarCode column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PARSBarCodeColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[16];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSBarCode column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PARSBarCode
    {
        get
        {
            return DC_OrderTable.Instance.PARSBarCodeColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSCarrierDispatchPhone column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PARSCarrierDispatchPhoneColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[17];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSCarrierDispatchPhone column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PARSCarrierDispatchPhone
    {
        get
        {
            return DC_OrderTable.Instance.PARSCarrierDispatchPhoneColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSDriverPhoneMobile column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PARSDriverPhoneMobileColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[18];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSDriverPhoneMobile column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PARSDriverPhoneMobile
    {
        get
        {
            return DC_OrderTable.Instance.PARSDriverPhoneMobileColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSETABorder column object.
    /// </summary>
    public BaseClasses.Data.DateColumn PARSETABorderColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[19];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSETABorder column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn PARSETABorder
    {
        get
        {
            return DC_OrderTable.Instance.PARSETABorderColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSPortOfEntryNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn PARSPortOfEntryNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[20];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PARSPortOfEntryNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn PARSPortOfEntryNum
    {
        get
        {
            return DC_OrderTable.Instance.PARSPortOfEntryNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PickUpDate column object.
    /// </summary>
    public BaseClasses.Data.DateColumn PickUpDateColumn
    {
        get
        {
            return (BaseClasses.Data.DateColumn)this.TableDefinition.ColumnList[21];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.PickUpDate column object.
    /// </summary>
    public static BaseClasses.Data.DateColumn PickUpDate
    {
        get
        {
            return DC_OrderTable.Instance.PickUpDateColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.SealNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn SealNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[22];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.SealNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn SealNum
    {
        get
        {
            return DC_OrderTable.Instance.SealNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.SNMGNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn SNMGNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[23];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.SNMGNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn SNMGNum
    {
        get
        {
            return DC_OrderTable.Instance.SNMGNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TEStatus column object.
    /// </summary>
    public BaseClasses.Data.StringColumn TEStatusColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[24];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TEStatus column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn TEStatus
    {
        get
        {
            return DC_OrderTable.Instance.TEStatusColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalBoxDamaged column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TotalBoxDamagedColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[25];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalBoxDamaged column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TotalBoxDamaged
    {
        get
        {
            return DC_OrderTable.Instance.TotalBoxDamagedColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalCount column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TotalCountColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[26];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalCount column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TotalCount
    {
        get
        {
            return DC_OrderTable.Instance.TotalCountColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalPalletCount column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TotalPalletCountColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[27];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalPalletCount column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TotalPalletCount
    {
        get
        {
            return DC_OrderTable.Instance.TotalPalletCountColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalPrice column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn TotalPriceColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[28];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalPrice column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn TotalPrice
    {
        get
        {
            return DC_OrderTable.Instance.TotalPriceColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalQuantityKG column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TotalQuantityKGColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[29];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TotalQuantityKG column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TotalQuantityKG
    {
        get
        {
            return DC_OrderTable.Instance.TotalQuantityKGColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TransportCharges column object.
    /// </summary>
    public BaseClasses.Data.CurrencyColumn TransportChargesColumn
    {
        get
        {
            return (BaseClasses.Data.CurrencyColumn)this.TableDefinition.ColumnList[30];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TransportCharges column object.
    /// </summary>
    public static BaseClasses.Data.CurrencyColumn TransportCharges
    {
        get
        {
            return DC_OrderTable.Instance.TransportChargesColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TransporterId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn TransporterIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[31];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TransporterId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn TransporterId
    {
        get
        {
            return DC_OrderTable.Instance.TransporterIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TrailerNum column object.
    /// </summary>
    public BaseClasses.Data.StringColumn TrailerNumColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[32];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TrailerNum column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn TrailerNum
    {
        get
        {
            return DC_OrderTable.Instance.TrailerNumColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TruckTag column object.
    /// </summary>
    public BaseClasses.Data.StringColumn TruckTagColumn
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[33];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.TruckTag column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn TruckTag
    {
        get
        {
            return DC_OrderTable.Instance.TruckTagColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.VesselId column object.
    /// </summary>
    public BaseClasses.Data.NumberColumn VesselIdColumn
    {
        get
        {
            return (BaseClasses.Data.NumberColumn)this.TableDefinition.ColumnList[34];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.VesselId column object.
    /// </summary>
    public static BaseClasses.Data.NumberColumn VesselId
    {
        get
        {
            return DC_OrderTable.Instance.VesselIdColumn;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser1 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser1Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[35];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser1 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser1
    {
        get
        {
            return DC_OrderTable.Instance.ZUser1Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser2 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser2Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[36];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser2 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser2
    {
        get
        {
            return DC_OrderTable.Instance.ZUser2Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser3 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser3Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[37];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser3 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser3
    {
        get
        {
            return DC_OrderTable.Instance.ZUser3Column;
        }
    }
    
    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser4 column object.
    /// </summary>
    public BaseClasses.Data.StringColumn ZUser4Column
    {
        get
        {
            return (BaseClasses.Data.StringColumn)this.TableDefinition.ColumnList[38];
        }
    }
    

    
    /// <summary>
    /// This is a convenience property that provides direct access to the table's DC_Order_.ZUser4 column object.
    /// </summary>
    public static BaseClasses.Data.StringColumn ZUser4
    {
        get
        {
            return DC_OrderTable.Instance.ZUser4Column;
        }
    }
    
    


#endregion

    
#region "Shared helper methods"

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderRecord records using a where clause.
    /// </summary>
    public static DC_OrderRecord[] GetRecords(string where)
    {
        return GetRecords(where, null, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }

    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderRecord records using a where and order by clause.
    /// </summary>
    public static DC_OrderRecord[] GetRecords(string where, OrderBy orderBy)
    {
        return GetRecords(where, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MAX_BATCH_SIZE);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get an array of DC_OrderRecord records using a where and order by clause clause with pagination.
    /// </summary>
    public static DC_OrderRecord[] GetRecords(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }

        ArrayList recList = DC_OrderTable.Instance.GetRecordList(whereFilter, orderBy, pageIndex, pageSize);

        return (DC_OrderRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord"));
    }   
    
    public static DC_OrderRecord[] GetRecords(
		WhereClause where,
		OrderBy orderBy,
		int pageIndex,
		int pageSize)
	{

        ArrayList recList = DC_OrderTable.Instance.GetRecordList(where.GetFilter(), orderBy, pageIndex, pageSize);

        return (DC_OrderRecord[])recList.ToArray(Type.GetType("ePortDC.Business.DC_OrderRecord"));
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

        return (int)DC_OrderTable.Instance.GetRecordListCount(whereFilter, null);
    }
    
    public static int GetRecordCount(WhereClause where)
    {
        return (int)DC_OrderTable.Instance.GetRecordListCount(where.GetFilter(), null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_OrderRecord record using a where clause.
    /// </summary>
    public static DC_OrderRecord GetRecord(string where)
    {
        OrderBy orderBy = null;
        return GetRecord(where, orderBy);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DC_OrderRecord record using a where and order by clause.
    /// </summary>
    public static DC_OrderRecord GetRecord(string where, OrderBy orderBy)
    {
        SqlFilter whereFilter = null;
        if (where != null && where.Trim() != "")
        {
           whereFilter = new SqlFilter(where);
        }
        
        ArrayList recList = DC_OrderTable.Instance.GetRecordList(whereFilter, orderBy, BaseTable.MIN_PAGE_NUMBER, BaseTable.MIN_BATCH_SIZE);

        DC_OrderRecord rec = null;
        if (recList.Count > 0)
        {
            rec = (DC_OrderRecord)recList[0];
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

        return DC_OrderTable.Instance.GetColumnValues(retCol, where.GetFilter(), orderBy, BaseTable.MIN_PAGE_NUMBER, maxItems);

    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where)
    {
        DC_OrderRecord[] recs = GetRecords(where);
        return  DC_OrderTable.Instance.CreateDataTable(recs, null);
    }

    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy)
    {
        DC_OrderRecord[] recs = GetRecords(where, orderBy);
        return  DC_OrderTable.Instance.CreateDataTable(recs, null);
    }
    
    /// <summary>
    /// This is a shared function that can be used to get a DataTable to bound with a data bound control using a where and order by clause with pagination.
    /// </summary>
    public static System.Data.DataTable GetDataTable(string where, OrderBy orderBy, int pageIndex, int pageSize)
    {
        DC_OrderRecord[] recs = GetRecords(where, orderBy, pageIndex, pageSize);
        return  DC_OrderTable.Instance.CreateDataTable(recs, null);
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
        DC_OrderTable.Instance.DeleteRecordList(whereFilter);
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
        
        return  DC_OrderTable.Instance.ExportRecordData(whereFilter);
    }
   
    public static string Export(WhereClause where)
    {
        BaseFilter whereFilter = null;
        if (where != null)
        {
            whereFilter = where.GetFilter();
        }

        return DC_OrderTable.Instance.ExportRecordData(whereFilter);
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

        return DC_OrderTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
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

        return DC_OrderTable.Instance.GetColumnStatistics(colSel, where.GetFilter(), orderBy, pageIndex, pageSize);
    }

    /// <summary>
    ///  This method returns the columns in the table.
    /// </summary>
    public static BaseColumn[] GetColumns() 
    {
        return DC_OrderTable.Instance.TableDefinition.Columns;
    }

    /// <summary>
    ///  This method returns the columnlist in the table.
    /// </summary>   
    public static ColumnList GetColumnList() 
    {
        return DC_OrderTable.Instance.TableDefinition.ColumnList;
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    public static IRecord CreateNewRecord() 
    {
        return DC_OrderTable.Instance.CreateRecord();
    }

    /// <summary>
    /// This method creates a new record and returns it to be edited.
    /// </summary>
    /// <param name="tempId">ID of the new record.</param>   
    public static IRecord CreateNewRecord(string tempId) 
    {
        return DC_OrderTable.Instance.CreateRecord(tempId);
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
        BaseColumn column = DC_OrderTable.Instance.TableDefinition.ColumnList.GetByUniqueName(uniqueColumnName);
        return column;
    }

        //Convenience method for getting a record using a string-based record identifier
        public static DC_OrderRecord GetRecord(string id, bool bMutable)
        {
            return (DC_OrderRecord)DC_OrderTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for getting a record using a KeyValue record identifier
        public static DC_OrderRecord GetRecord(KeyValue id, bool bMutable)
        {
            return (DC_OrderRecord)DC_OrderTable.Instance.GetRecordData(id, bMutable);
        }

        //Convenience method for creating a record
        public KeyValue NewRecord(
        string OrderNumValue, 
        string CommentsValue, 
        string CommentsCancelValue, 
        string CommodityCodeValue, 
        string ConsigneeIdValue, 
        string CustomerIdValue, 
        string CustomerPOValue, 
        string DeliveryDateValue, 
        string DirectOrderValue, 
        string DriverCheckInDateTimeValue, 
        string DriverCheckOutDateTimeValue, 
        string DriverNameValue, 
        string LastUpdateUserValue, 
        string LastUpdateDateTimeValue, 
        string LoadTypeValue, 
        string OrderStatusIdValue, 
        string PARSBarCodeValue, 
        string PARSCarrierDispatchPhoneValue, 
        string PARSDriverPhoneMobileValue, 
        string PARSETABorderValue, 
        string PARSPortOfEntryNumValue, 
        string PickUpDateValue, 
        string SealNumValue, 
        string SNMGNumValue, 
        string TEStatusValue, 
        string TotalBoxDamagedValue, 
        string TotalCountValue, 
        string TotalPalletCountValue, 
        string TotalPriceValue, 
        string TotalQuantityKGValue, 
        string TransportChargesValue, 
        string TransporterIdValue, 
        string TrailerNumValue, 
        string TruckTagValue, 
        string VesselIdValue, 
        string ZUser1Value, 
        string ZUser2Value, 
        string ZUser3Value, 
        string ZUser4Value
    )
        {
            IPrimaryKeyRecord rec = (IPrimaryKeyRecord)this.CreateRecord();
                    rec.SetString(OrderNumValue, OrderNumColumn);
        rec.SetString(CommentsValue, CommentsColumn);
        rec.SetString(CommentsCancelValue, CommentsCancelColumn);
        rec.SetString(CommodityCodeValue, CommodityCodeColumn);
        rec.SetString(ConsigneeIdValue, ConsigneeIdColumn);
        rec.SetString(CustomerIdValue, CustomerIdColumn);
        rec.SetString(CustomerPOValue, CustomerPOColumn);
        rec.SetString(DeliveryDateValue, DeliveryDateColumn);
        rec.SetString(DirectOrderValue, DirectOrderColumn);
        rec.SetString(DriverCheckInDateTimeValue, DriverCheckInDateTimeColumn);
        rec.SetString(DriverCheckOutDateTimeValue, DriverCheckOutDateTimeColumn);
        rec.SetString(DriverNameValue, DriverNameColumn);
        rec.SetString(LastUpdateUserValue, LastUpdateUserColumn);
        rec.SetString(LastUpdateDateTimeValue, LastUpdateDateTimeColumn);
        rec.SetString(LoadTypeValue, LoadTypeColumn);
        rec.SetString(OrderStatusIdValue, OrderStatusIdColumn);
        rec.SetString(PARSBarCodeValue, PARSBarCodeColumn);
        rec.SetString(PARSCarrierDispatchPhoneValue, PARSCarrierDispatchPhoneColumn);
        rec.SetString(PARSDriverPhoneMobileValue, PARSDriverPhoneMobileColumn);
        rec.SetString(PARSETABorderValue, PARSETABorderColumn);
        rec.SetString(PARSPortOfEntryNumValue, PARSPortOfEntryNumColumn);
        rec.SetString(PickUpDateValue, PickUpDateColumn);
        rec.SetString(SealNumValue, SealNumColumn);
        rec.SetString(SNMGNumValue, SNMGNumColumn);
        rec.SetString(TEStatusValue, TEStatusColumn);
        rec.SetString(TotalBoxDamagedValue, TotalBoxDamagedColumn);
        rec.SetString(TotalCountValue, TotalCountColumn);
        rec.SetString(TotalPalletCountValue, TotalPalletCountColumn);
        rec.SetString(TotalPriceValue, TotalPriceColumn);
        rec.SetString(TotalQuantityKGValue, TotalQuantityKGColumn);
        rec.SetString(TransportChargesValue, TransportChargesColumn);
        rec.SetString(TransporterIdValue, TransporterIdColumn);
        rec.SetString(TrailerNumValue, TrailerNumColumn);
        rec.SetString(TruckTagValue, TruckTagColumn);
        rec.SetString(VesselIdValue, VesselIdColumn);
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
			DC_OrderTable.Instance.DeleteOneRecord(kv);
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
				DC_OrderTable.GetRecord(kv, false);
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
            if (!(DC_OrderTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                return DC_OrderTable.Instance.TableDefinition.PrimaryKey.Columns;
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
            if (!(DC_OrderTable.Instance.TableDefinition.PrimaryKey == null)) 
            {
                bool isCompositePrimaryKey = false;
                isCompositePrimaryKey = DC_OrderTable.Instance.TableDefinition.PrimaryKey.IsCompositeKey;
                if ((isCompositePrimaryKey && key.GetType().IsArray)) 
                {
                    //  If the key is composite, then construct a key value.
                    kv = new KeyValue();
                    Array keyArray = ((Array)(key));
                    if (!(keyArray == null)) 
                    {
                        int length = keyArray.Length;
                        ColumnList pkColumns = DC_OrderTable.Instance.TableDefinition.PrimaryKey.Columns;
                        int index = 0;
                        foreach (BaseColumn pkColumn in pkColumns) 
                        {
                            string keyString = ((keyArray.GetValue(index)).ToString());
                            if (DC_OrderTable.Instance.TableDefinition.TableType == BaseClasses.Data.TableDefinition.TableTypes.Virtual)
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
                    kv = DC_OrderTable.Instance.TableDefinition.PrimaryKey.ParseValue(((key).ToString()));
                }
            }
            return kv;
        }

#endregion
}

}
