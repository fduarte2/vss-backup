﻿using System;

namespace ePortDC.Business
{

/// <summary>
/// Contains embedded schema and configuration data that is used by the 
/// <see cref="DC_CustomsBrokerOfficeTable">ePortDC.DC_CustomsBrokerOfficeTable</see> class
/// to initialize the class's TableDefinition.
/// </summary>
/// <seealso cref="DC_CustomsBrokerOfficeTable"></seealso>
public class DC_CustomsBrokerOfficeDefinition
{
#region "Definition (XML) for DC_CustomsBrokerOfficeDefinition table"
	//Next 325 lines contain Table Definition (XML) for table "DC_CustomsBrokerOfficeDefinition"
	private static string _DefinitionString = 
@"<XMLDefinition Generator=""Iron Speed Designer"" Version=""5.0"" Type=""GENERIC"">" +
  @"<ColumnDefinition>" +
    @"<Column InternalName=""0"" Priority=""1"" ColumnNum=""0"">" +
      @"<columnName>CustomsBrokerOfficeId</columnName>" +
      @"<columnUIName>Customs Broker Office</columnUIName>" +
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
      @"<columnName>BorderCrossing</columnName>" +
      @"<columnUIName>Border Crossing</columnUIName>" +
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
      @"<columnRequired>Y</columnRequired>" +
      @"<columnNotNull>Y</columnNotNull>" +
      @"<columnVisibleWidth>%ISD_DEFAULT%</columnVisibleWidth>" +
      @"<columnTableAliasName></columnTableAliasName>" +
    "</Column>" +
    @"<Column InternalName=""2"" Priority=""3"" ColumnNum=""2"">" +
      @"<columnName>Client</columnName>" +
      @"<columnUIName>Client</columnUIName>" +
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
      @"<columnName>ContactName</columnName>" +
      @"<columnUIName>Contact Name</columnUIName>" +
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
      @"<columnName>CustomsBroker</columnName>" +
      @"<columnUIName>Customs Broker</columnUIName>" +
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
      @"<columnName>Destinations</columnName>" +
      @"<columnUIName>Destinations</columnUIName>" +
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
    @"<Column InternalName=""7"" Priority=""8"" ColumnNum=""7"">" +
      @"<columnName>Email1</columnName>" +
      @"<columnUIName>Email 1</columnUIName>" +
      @"<columnType>Email</columnType>" +
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
    @"<Column InternalName=""8"" Priority=""9"" ColumnNum=""8"">" +
      @"<columnName>Email2</columnName>" +
      @"<columnUIName>Email 2</columnUIName>" +
      @"<columnType>Email</columnType>" +
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
      @"<columnName>Email3</columnName>" +
      @"<columnUIName>Email 3</columnUIName>" +
      @"<columnType>Email</columnType>" +
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
      @"<columnName>Email4</columnName>" +
      @"<columnUIName>Email 4</columnUIName>" +
      @"<columnType>Email</columnType>" +
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
    @"<Column InternalName=""11"" Priority=""12"" ColumnNum=""11"">" +
      @"<columnName>Email5</columnName>" +
      @"<columnUIName>Email 5</columnUIName>" +
      @"<columnType>Email</columnType>" +
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
    @"<Column InternalName=""12"" Priority=""13"" ColumnNum=""12"">" +
      @"<columnName>Fax</columnName>" +
      @"<columnUIName>Fax</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>25</columnLengthSet>" +
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
    @"<Column InternalName=""13"" Priority=""14"" ColumnNum=""13"">" +
      @"<columnName>Phone</columnName>" +
      @"<columnUIName>Phone</columnUIName>" +
      @"<columnType>String</columnType>" +
      @"<columnDBType>nvarchar</columnDBType>" +
      @"<columnLengthSet>25</columnLengthSet>" +
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
  @"<TableName>DC_CustomsBrokerOffice</TableName>" +
  @"<Version></Version>" +
  @"<Owner>dbo</Owner>" +
  @"<TableCodeName>DC_CustomsBrokerOffice</TableCodeName>" +
  @"<TableAliasName>DC_CustomsBrokerOffice_</TableAliasName>" +
  @"<ConnectionName>DatabaseePort1</ConnectionName>" +
  @"<canCreateRecords Source=""Database"">Y</canCreateRecords>" +
  @"<canEditRecords Source=""Database"">Y</canEditRecords>" +
  @"<canDeleteRecords Source=""Database"">Y</canDeleteRecords>" +
  @"<canViewRecords Source=""Database"">Y</canViewRecords>" +
  @"<ConcurrencyMethod>BinaryChecksum</ConcurrencyMethod>" +
  @"<AppShortName>ePortDC</AppShortName>" +
  @"<TableStoredProcPrefix>pePortDCDC_CustomsBrokerOffice</TableStoredProcPrefix>" +
"</XMLDefinition>";
#endregion

	/// <summary>
	/// Gets the embedded schema and configuration data for the  
	/// <see cref="DC_CustomsBrokerOfficeTable"></see>
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
