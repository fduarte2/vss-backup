using System;

namespace ePortDC.Business
{

/// <summary>
/// Contains embedded schema and configuration data that is used by the 
/// <see cref="DC_CustomerPriceTable">ePortDC.DC_CustomerPriceTable</see> class
/// to initialize the class's TableDefinition.
/// </summary>
/// <seealso cref="DC_CustomerPriceTable"></seealso>
public class DC_CustomerPriceDefinition
{
#region "Definition (XML) for DC_CustomerPriceDefinition table"
	//Next 173 lines contain Table Definition (XML) for table "DC_CustomerPriceDefinition"
	private static string _DefinitionString = 
@"<XMLDefinition Generator=""Iron Speed Designer"" Version=""5.0"" Type=""GENERIC"">" +
  @"<ColumnDefinition>" +
    @"<Column InternalName=""0"" Priority=""1"" ColumnNum=""0"">" +
      @"<columnName>CustomerId</columnName>" +
      @"<columnUIName>Customer</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>smallint</columnDBType>" +
      @"<columnLengthSet>5.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction>notrim</columnFunction>" +
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
        @"<columnFKName>FK_DC_CustomerPrice_DC_Customer</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_CustomerTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>CustomerId</columnFKColumn>" +
        @"<columnFKColumnDisplay>CustomerName</columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""1"" Priority=""2"" ColumnNum=""1"">" +
      @"<columnName>CommodityCode</columnName>" +
      @"<columnUIName>Commodity Code</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>smallint</columnDBType>" +
      @"<columnLengthSet>5.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction>notrim</columnFunction>" +
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
        @"<columnFKName>FK_DC_CustomerPrice_DC_Commodity</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_CommodityTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>CommodityCode</columnFKColumn>" +
        @"<columnFKColumnDisplay>CommodityName</columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""2"" Priority=""3"" ColumnNum=""2"">" +
      @"<columnName>SizeId</columnName>" +
      @"<columnUIName>Size</columnUIName>" +
      @"<columnType>Number</columnType>" +
      @"<columnDBType>smallint</columnDBType>" +
      @"<columnLengthSet>5.0</columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction>notrim</columnFunction>" +
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
        @"<columnFKName>FK_DC_CustomerPrice_DC_CommoditySize</columnFKName>" +
        @"<columnFKTable>ePortDC.Business.DC_CommoditySizeTable, App_Code</columnFKTable>" +
        @"<columnFKOwner>dbo</columnFKOwner>" +
        @"<columnFKColumn>SizeId</columnFKColumn>" +
        @"<columnFKColumnDisplay>Descr</columnFKColumnDisplay>" +
        @"<foreignKeyType>Explicit</foreignKeyType>" +
      "</foreignKey>" +
    "</Column>" +
    @"<Column InternalName=""3"" Priority=""4"" ColumnNum=""3"">" +
      @"<columnName>EffectiveDate</columnName>" +
      @"<columnUIName>Effective Date</columnUIName>" +
      @"<columnType>Date</columnType>" +
      @"<columnDBType>smalldatetime</columnDBType>" +
      @"<columnLengthSet></columnLengthSet>" +
      @"<columnDefault></columnDefault>" +
      @"<columnDBDefault></columnDBDefault>" +
      @"<columnIndex>Y</columnIndex>" +
      @"<columnUnique>Y</columnUnique>" +
      @"<columnFunction>notrim</columnFunction>" +
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
    "</Column>" +
    @"<Column InternalName=""4"" Priority=""5"" ColumnNum=""4"">" +
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
    @"<Column InternalName=""5"" Priority=""6"" ColumnNum=""5"">" +
      @"<columnName>Price</columnName>" +
      @"<columnUIName>Price</columnUIName>" +
      @"<columnType>Currency</columnType>" +
      @"<columnDBType>money</columnDBType>" +
      @"<columnLengthSet>19.4</columnLengthSet>" +
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
  "</ColumnDefinition>" +
  @"<TableName>DC_CustomerPrice</TableName>" +
  @"<Version></Version>" +
  @"<Owner>dbo</Owner>" +
  @"<TableCodeName>DC_CustomerPrice</TableCodeName>" +
  @"<TableAliasName>DC_CustomerPrice_</TableAliasName>" +
  @"<ConnectionName>DatabaseePort1</ConnectionName>" +
  @"<canCreateRecords Source=""Database"">Y</canCreateRecords>" +
  @"<canEditRecords Source=""Database"">Y</canEditRecords>" +
  @"<canDeleteRecords Source=""Database"">Y</canDeleteRecords>" +
  @"<canViewRecords Source=""Database"">Y</canViewRecords>" +
  @"<ConcurrencyMethod>BinaryChecksum</ConcurrencyMethod>" +
  @"<AppShortName>ePortDC</AppShortName>" +
  @"<TableStoredProcPrefix>pePortDCDC_CustomerPrice</TableStoredProcPrefix>" +
"</XMLDefinition>";
#endregion

	/// <summary>
	/// Gets the embedded schema and configuration data for the  
	/// <see cref="DC_CustomerPriceTable"></see>
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
