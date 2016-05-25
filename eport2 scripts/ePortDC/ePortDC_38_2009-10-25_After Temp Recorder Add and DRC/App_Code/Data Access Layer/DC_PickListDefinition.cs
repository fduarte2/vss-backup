using System;

namespace ePortDC.Business
{

/// <summary>
/// Contains embedded schema and configuration data that is used by the 
/// <see cref="DC_PickListTable">ePortDC.DC_PickListTable</see> class
/// to initialize the class's TableDefinition.
/// </summary>
/// <seealso cref="DC_PickListTable"></seealso>
public class DC_PickListDefinition
{
#region "Definition (XML) for DC_PickListDefinition table"
	//Next 305 lines contain Table Definition (XML) for table "DC_PickListDefinition"
	private static string _DefinitionString = 
@"<XMLDefinition Generator=""Iron Speed Designer"" Version=""5.0"" Type=""GENERIC"">" +
  @"<ColumnDefinition>" +
    @"<Column InternalName=""0"" Priority=""1"" ColumnNum=""0"">" +
      @"<columnName>OrderNum</columnName>" +
      @"<columnUIName>Order Number</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>12</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>Y</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
      @"<foreignKey>" +
        @"<columnFKName>FK_DC_PickList_DC_OrderDetail</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_OrderDetailTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>OrderNum</columnFKColumn>" +
        @"<columnFKColumnDisplay></columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""1"" Priority=""2"" ColumnNum=""1"">" +
      @"<columnName>OrderDetailId</columnName>" +
      @"<columnUIName>Order Detail</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>int</columnDBType>" +
      @"<columnLengthSet>10.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>Y</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
      @"<foreignKey>" +
        @"<columnFKName>FK_DC_PickList_DC_OrderDetail</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_OrderDetailTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>OrderDetailId</columnFKColumn>" +
        @"<columnFKColumnDisplay></columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""2"" Priority=""3"" ColumnNum=""2"">" +
      @"<columnName>PickListId</columnName>" +
      @"<columnUIName>Pick List</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>int</columnDBType>" +
      @"<columnLengthSet>10.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction>notrim</columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>Y</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>Y</columnComputed>" +
      @"<columnIdentity>Y</columnIdentity>" +
      @"<columnReadOnly>Y</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""3"" Priority=""4"" ColumnNum=""3"">" +
      @"<columnName>Comments</columnName>" +
      @"<columnUIName>Comments</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>50</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""4"" Priority=""5"" ColumnNum=""4"">" +
      @"<columnName>PackHouseId</columnName>" +
      @"<columnUIName>Pack House</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>10</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction>notrim,</columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
      @"<foreignKey>" +
        @"<columnFKName>FK_DC_PickList_DC_PackHouse</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_PackHouseTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>PackHouseId</columnFKColumn>" +
        @"<columnFKColumnDisplay>PackHouseName</columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""5"" Priority=""6"" ColumnNum=""5"">" +
      @"<columnName>PalletLocation</columnName>" +
      @"<columnUIName>Pallet Location</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>50</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""6"" Priority=""7"" ColumnNum=""6"">" +
      @"<columnName>PalletQty</columnName>" +
      @"<columnUIName>Pallet Quantity</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>smallint</columnDBType>" +
      @"<columnLengthSet>5.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""7"" Priority=""8"" ColumnNum=""7"">" +
      @"<columnName>PickListSize</columnName>" +
      @"<columnUIName>Pick List Size</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>smallint</columnDBType>" +
      @"<columnLengthSet>5.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""8"" Priority=""9"" ColumnNum=""8"">" +
      @"<columnName>ZUser1</columnName>" +
      @"<columnUIName>Z User 1</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>50</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""9"" Priority=""10"" ColumnNum=""9"">" +
      @"<columnName>ZUser2</columnName>" +
      @"<columnUIName>Z User 2</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>50</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""10"" Priority=""11"" ColumnNum=""10"">" +
      @"<columnName>ZUser3</columnName>" +
      @"<columnUIName>Z User 3</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>10</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""11"" Priority=""12"" ColumnNum=""11"">" +
      @"<columnName>ZUser4</columnName>" +
      @"<columnUIName>Z User 4</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>10</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>N</columnIndex>" +
      @"<columnUnique>N</columnUnique>" +
      @"<columnFunction></columnFunction>" +
      @"<columnDBFormat></columnDBFormat>" +
      @"<columnPK>N</columnPK>" +
      @"<columnPermanent>N</columnPermanent>" +
      @"<columnComputed>N</columnComputed>" +
      @"<columnIdentity>N</columnIdentity>" +
      @"<columnReadOnly>N</columnReadOnly>" +
      @"<columnRequired>N</columnRequired>" +
      @"<columnNotNull>N</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
  "</ColumnDefinition>" +
  @"<TableName>DC_PickList</TableName>" +
  @"<Version>7</Version>" +
  @"<Owner>dbo</Owner>" +
  @"<TableCodeName>DC_PickList</TableCodeName>" +
  @"<TableAliasName>DC_PickList_</TableAliasName>" +
  @"<ConnectionName>DatabaseePort1</ConnectionName>" +
  @"<canCreateRecords Source=""Database"">Y</canCreateRecords>" +
  @"<canEditRecords Source=""Database"">Y</canEditRecords>" +
  @"<canDeleteRecords Source=""Database"">Y</canDeleteRecords>" +
  @"<canViewRecords Source=""Database"">Y</canViewRecords>" +
  @"<ConcurrencyMethod>BinaryChecksum</ConcurrencyMethod>" +
  @"<AppShortName>ePortDC</AppShortName>" +
  @"<TableStoredProcPrefix>pePortDCDC_PickList</TableStoredProcPrefix>" +
"</XMLDefinition>";
#endregion

	/// <summary>
	/// Gets the embedded schema and configuration data for the  
	/// <see cref="DC_PickListTable"></see>
	/// class's TableDefinition.
	/// </summary>
	/// <remarks>This function is only called once at runtime.</remarks>
	/// <returns>An XML string.</returns>
	public static string GetXMLString()
	{
		return _DefinitionString;
	}
}

}
