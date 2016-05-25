if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerPriceGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerPriceGet 
GO

-- Returns a specific record from the [dbo].[DC_CustomerPrice] table.
CREATE PROCEDURE pePortDCDC_CustomerPriceGet
        @pk_CustomerId smallint,    @pk_CommodityCode smallint,    @pk_SizeId smallint,    @pk_EffectiveDate smalldatetime
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_CustomerPrice]
    WHERE [CustomerId] =@pk_CustomerId and [CommodityCode] =@pk_CommodityCode and [SizeId] =@pk_SizeId and [EffectiveDate] =@pk_EffectiveDate

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [CustomerId],
        [CommodityCode],
        [SizeId],
        [EffectiveDate],
        [Comments],
        [Price],
        CAST(BINARY_CHECKSUM([CustomerId],[CommodityCode],[SizeId],[EffectiveDate],[Comments],[Price]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_CustomerPrice]
    WHERE [CustomerId] =@pk_CustomerId and [CommodityCode] =@pk_CommodityCode and [SizeId] =@pk_SizeId and [EffectiveDate] =@pk_EffectiveDate
END

