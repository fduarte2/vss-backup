if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerPriceDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerPriceDelete 
GO

-- Deletes a record from the [dbo].[DC_CustomerPrice] table.
CREATE PROCEDURE pePortDCDC_CustomerPriceDelete
        @pk_CustomerId smallint,
    @pk_CommodityCode smallint,
    @pk_SizeId smallint,
    @pk_EffectiveDate smalldatetime
AS
BEGIN
    DELETE [dbo].[DC_CustomerPrice]
    WHERE [CustomerId] = @pk_CustomerId
    AND [CommodityCode] = @pk_CommodityCode
    AND [SizeId] = @pk_SizeId
    AND [EffectiveDate] = @pk_EffectiveDate
END

