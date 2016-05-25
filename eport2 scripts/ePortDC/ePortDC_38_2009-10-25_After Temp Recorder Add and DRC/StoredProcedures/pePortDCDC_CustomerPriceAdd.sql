if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerPriceAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerPriceAdd 
GO

-- Creates a new record in the [dbo].[DC_CustomerPrice] table.
CREATE PROCEDURE pePortDCDC_CustomerPriceAdd
    @p_CustomerId smallint,
    @p_CommodityCode smallint,
    @p_SizeId smallint,
    @p_EffectiveDate smalldatetime,
    @p_Comments nvarchar(50),
    @p_Price money
AS
BEGIN
    INSERT
    INTO [dbo].[DC_CustomerPrice]
        (
            [CustomerId],
            [CommodityCode],
            [SizeId],
            [EffectiveDate],
            [Comments],
            [Price]
        )
    VALUES
        (
             @p_CustomerId,
             @p_CommodityCode,
             @p_SizeId,
             @p_EffectiveDate,
             @p_Comments,
             @p_Price
        )

END

