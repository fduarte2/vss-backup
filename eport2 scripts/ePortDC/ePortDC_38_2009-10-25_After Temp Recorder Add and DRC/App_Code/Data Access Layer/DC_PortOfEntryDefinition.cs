using System;

namespace ePortDC.Business
{

/// <summary>
/// Contains embedded schema and configuration data that is used by the 
/// <see cref="DC_PortOfEntryTable">ePortDC.DC_PortOfEntryTable</see> class
/// to initialize the class's TableDefinition.
/// </summary>
/// <seealso cref="DC_PortOfEntryTable"></seealso>
public class DC_PortOfEntryDefinition
{
#region "Definition (XML) for DC_PortOfEntryDefinition table"
	//Next 61 lines contain Table Definition (XML) for table "DC_PortOfEntryDefinition"
	private static string _DefinitionString = 
@"<XMLDefinition Generator=""Iron Speed Designer"" Version=""5.0"" Type=""GENERIC"">" +
  @"<ColumnDefinition>" +
    @"<Column InternalName=""0"" Priority=""1"" ColumnNum=""0"">" +
      @"<columnName>PortCode</columnName>" +
      @"<columnUIName>Port Code</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nchar</columnDBType>" +
      @"<columnLengthSet>3</columnLengthSet>" +
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
      @"<columnName>PortName</columnName>" +
      @"<columnUIName>Port Name</columnUIName>" +
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
  "</ColumnDefinition>" +
  @"<TableName>DC_PortOfEntry</TableName>" +
  @"<Version>1</Version>" +
  @"<Owner>dbo</Owner>" +
  @"<TableCodeName>DC_PortOfEntry</TableCodeName>" +
  @"<TableAliasName>DC_PortOfEntry_</TableAliasName>" +
  @"<ConnectionName>DatabaseePort1</ConnectionName>" +
  @"<canCreateRecords Source=""Database"">Y</canCreateRecords>" +
  @"<canEditRecords Source=""Database"">Y</canEditRecords>" +
  @"<canDeleteRecords Source=""Database"">Y</canDeleteRecords>" +
  @"<canViewRecords Source=""Database"">Y</canViewRecords>" +
  @"<ConcurrencyMethod>BinaryChecksum</ConcurrencyMethod>" +
  @"<AppShortName>ePortDC</AppShortName>" +
  @"<TableStoredProcPrefix>pePortDCDC_PortOfEntry</TableStoredProcPrefix>" +
"</XMLDefinition>";
#endregion

	/// <summary>
	/// Gets the embedded schema and configuration data for the  
	/// <see cref="DC_PortOfEntryTable"></see>
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
