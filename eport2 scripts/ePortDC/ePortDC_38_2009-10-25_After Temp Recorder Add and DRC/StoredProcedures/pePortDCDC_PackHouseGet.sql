if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PackHouseGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_PackHouseGet 
GO

-- Returns a specific record from the [dbo].[DC_PackHouse] table.
CREATE PROCEDURE pePortDCDC_PackHouseGet
        @pk_PackHouseId nchar(10)
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_PackHouse]
    WHERE [PackHouseId] =@pk_PackHouseId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [PackHouseId],
        [GroupName],
        [PackHouseName],
        CAST(BINARY_CHECKSUM([PackHouseId],[GroupName],[PackHouseName]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_PackHouse]
    WHERE [PackHouseId] =@pk_PackHouseId
END

