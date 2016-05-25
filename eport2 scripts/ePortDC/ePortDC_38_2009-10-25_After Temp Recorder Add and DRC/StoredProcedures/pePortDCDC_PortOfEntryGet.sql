if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PortOfEntryGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_PortOfEntryGet 
GO

-- Returns a specific record from the [dbo].[DC_PortOfEntry] table.
CREATE PROCEDURE pePortDCDC_PortOfEntryGet
        @pk_PortCode nchar(3)
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_PortOfEntry]
    WHERE [PortCode] =@pk_PortCode

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [PortCode],
        [PortName],
        CAST(BINARY_CHECKSUM([PortCode],[PortName]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_PortOfEntry]
    WHERE [PortCode] =@pk_PortCode
END

