if exists (select * from sysobjects where id = object_id(N'pePortDCDC_TransporterGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_TransporterGet 
GO

-- Returns a specific record from the [dbo].[DC_Transporter] table.
CREATE PROCEDURE pePortDCDC_TransporterGet
        @pk_TransporterId smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_Transporter]
    WHERE [TransporterId] =@pk_TransporterId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [TransporterId],
        [CarrierName],
        [Comments],
        [ContactName],
        [Email],
        [Fax],
        [IRSNum],
        [Phone1],
        [Phone2],
        [PhoneCell1],
        [PhoneCell2],
        [Rate1GTAMiltonWhitby],
        [Rate2Cambridge],
        [Rate3Ottawa],
        [Rate4Montreal],
        [Rate5Quebec],
        [Rate6Moncton],
        [Rate7Debert],
        [Rate8Other],
        [USBondNum],
        CAST(BINARY_CHECKSUM([TransporterId],[CarrierName],[Comments],[ContactName],[Email],[Fax],[IRSNum],[Phone1],[Phone2],[PhoneCell1],[PhoneCell2],[Rate1GTAMiltonWhitby],[Rate2Cambridge],[Rate3Ottawa],[Rate4Montreal],[Rate5Quebec],[Rate6Moncton],[Rate7Debert],[Rate8Other],[USBondNum]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_Transporter]
    WHERE [TransporterId] =@pk_TransporterId
END

