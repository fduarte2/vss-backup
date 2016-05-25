if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommoditySizeGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommoditySizeGet 
GO

-- Returns a specific record from the [dbo].[DC_CommoditySize] table.
CREATE PROCEDURE pePortDCDC_CommoditySizeGet
        @pk_SizeId smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_CommoditySize]
    WHERE [SizeId] =@pk_SizeId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [SizeId],
        [Descr],
        [Price],
        [SizeHigh],
        [SizeLow],
        [WeightKG],
        CAST(BINARY_CHECKSUM([SizeId],[Descr],[Price],[SizeHigh],[SizeLow],[WeightKG]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_CommoditySize]
    WHERE [SizeId] =@pk_SizeId
END

