if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommodityDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommodityDelete 
GO

-- Deletes a record from the [dbo].[DC_Commodity] table.
CREATE PROCEDURE pePortDCDC_CommodityDelete
        @pk_CommodityCode smallint
AS
BEGIN
    DELETE [dbo].[DC_Commodity]
    WHERE [CommodityCode] = @pk_CommodityCode
END

