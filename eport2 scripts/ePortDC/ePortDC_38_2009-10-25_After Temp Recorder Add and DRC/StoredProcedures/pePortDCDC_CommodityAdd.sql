if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommodityAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommodityAdd 
GO

-- Creates a new record in the [dbo].[DC_Commodity] table.
CREATE PROCEDURE pePortDCDC_CommodityAdd
    @p_CommodityCode smallint,
    @p_CommodityName nchar(30),
    @p_HarmonizedSystemTariff nchar(10)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Commodity]
        (
            [CommodityCode],
            [CommodityName],
            [HarmonizedSystemTariff]
        )
    VALUES
        (
             @p_CommodityCode,
             @p_CommodityName,
             @p_HarmonizedSystemTariff
        )

END

