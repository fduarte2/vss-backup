if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommodityGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommodityGet 
GO

-- Returns a specific record from the [dbo].[DC_Commodity] table.
CREATE PROCEDURE pePortDCDC_CommodityGet
        @pk_CommodityCode smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_Commodity]
    WHERE [CommodityCode] =@pk_CommodityCode

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [CommodityCode],
        [CommodityName],
        [HarmonizedSystemTariff],
        CAST(BINARY_CHECKSUM([CommodityCode],[CommodityName],[HarmonizedSystemTariff]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_Commodity]
    WHERE [CommodityCode] =@pk_CommodityCode
END

