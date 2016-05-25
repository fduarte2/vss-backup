using System;

namespace ePortDC.Business
{

/// <summary>
/// Contains embedded schema and configuration data that is used by the 
/// <see cref="DC_OrderStatusTable">ePortDC.DC_OrderStatusTable</see> class
/// to initialize the class's TableDefinition.
/// </summary>
/// <seealso cref="DC_OrderStatusTable"></seealso>
public class DC_OrderStatusDefinition
{
#region "Definition (XML) for DC_OrderStatusDefinition table"
	//Next 61 lines contain Table Definition (XML) for table "DC_OrderStatusDefinition"
	private static string _DefinitionString = 
@"<XMLDefinition Generator=""Iron Speed Designer"" Version=""5.0"" Type=""GENERIC"">" +
  @"<ColumnDefinition>" +
    @"<Column InternalName=""0"" Priority=""1"" ColumnNum=""0"">" +
      @"<columnName>OrderStatusId</columnName>" +
      @"<columnUIName>Order Status</columnUIName>" +
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
    "</Column>" +
    @"<Column InternalName=""1"" Priority=""2"" ColumnNum=""1"">" +
      @"<columnName>Descr</columnName>" +
      @"<columnUIName>Descr</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>30</columnLengthSet>" +
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
  @"<TableName>DC_OrderStatus</TableName>" +
  @"<Version></Version>" +
  @"<Owner>dbo</Owner>" +
  @"<TableCodeName>DC_OrderStatus</TableCodeName>" +
  @"<TableAliasName>DC_OrderStatus_</TableAliasName>" +
  @"<ConnectionName>DatabaseePort1</ConnectionName>" +
  @"<canCreateRecords Source=""Database"">Y</canCreateRecords>" +
  @"<canEditRecords Source=""Database"">Y</canEditRecords>" +
  @"<canDeleteRecords Source=""Database"">Y</canDeleteRecords>" +
  @"<canViewRecords Source=""Database"">Y</canViewRecords>" +
  @"<ConcurrencyMethod>BinaryChecksum</ConcurrencyMethod>" +
  @"<AppShortName>ePortDC</AppShortName>" +
  @"<TableStoredProcPrefix>pePortDCDC_OrderStatus</TableStoredProcPrefix>" +
"</XMLDefinition>";
#endregion

	/// <summary>
	/// Gets the embedded schema and configuration data for the  
	/// <see cref="DC_OrderStatusTable"></see>
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
