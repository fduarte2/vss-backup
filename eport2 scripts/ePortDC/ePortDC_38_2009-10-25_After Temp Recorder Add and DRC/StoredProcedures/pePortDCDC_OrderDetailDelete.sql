if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailDelete 
GO

-- Deletes a record from the [dbo].[DC_OrderDetail] table.
CREATE PROCEDURE pePortDCDC_OrderDetailDelete
        @pk_OrderNum nchar(12),
    @pk_OrderDetailId int
AS
BEGIN
    DELETE [dbo].[DC_OrderDetail]
    WHERE [OrderNum] = @pk_OrderNum
    AND [OrderDetailId] = @pk_OrderDetailId
END

