if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommoditySizeAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommoditySizeAdd 
GO

-- Creates a new record in the [dbo].[DC_CommoditySize] table.
CREATE PROCEDURE pePortDCDC_CommoditySizeAdd
    @p_SizeId smallint,
    @p_Descr nvarchar(50),
    @p_Price money,
    @p_SizeHigh smallint,
    @p_SizeLow smallint,
    @p_WeightKG numeric(10,2)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_CommoditySize]
        (
            [SizeId],
            [Descr],
            [Price],
            [SizeHigh],
            [SizeLow],
            [WeightKG]
        )
    VALUES
        (
             @p_SizeId,
             @p_Descr,
             @p_Price,
             @p_SizeHigh,
             @p_SizeLow,
             @p_WeightKG
        )

END

