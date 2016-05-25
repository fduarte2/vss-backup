if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDelete 
GO

-- Deletes a record from the [dbo].[DC_Order] table.
CREATE PROCEDURE pePortDCDC_OrderDelete
        @pk_OrderNum nchar(12)
AS
BEGIN
    DELETE [dbo].[DC_Order]
    WHERE [OrderNum] = @pk_OrderNum
END

