if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommoditySizeDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommoditySizeDelete 
GO

-- Deletes a record from the [dbo].[DC_CommoditySize] table.
CREATE PROCEDURE pePortDCDC_CommoditySizeDelete
        @pk_SizeId smallint
AS
BEGIN
    DELETE [dbo].[DC_CommoditySize]
    WHERE [SizeId] = @pk_SizeId
END

