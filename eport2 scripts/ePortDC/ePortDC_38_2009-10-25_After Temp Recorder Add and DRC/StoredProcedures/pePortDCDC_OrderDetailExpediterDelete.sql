if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailExpediterDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailExpediterDelete 
GO

-- Deletes a record from the [dbo].[DC_OrderDetailExpediter] table.
CREATE PROCEDURE pePortDCDC_OrderDetailExpediterDelete
        @pk_OrderNum nchar(12),
    @pk_OrderExpediterId int
AS
BEGIN
    DELETE [dbo].[DC_OrderDetailExpediter]
    WHERE [OrderNum] = @pk_OrderNum
    AND [OrderExpediterId] = @pk_OrderExpediterId
END

