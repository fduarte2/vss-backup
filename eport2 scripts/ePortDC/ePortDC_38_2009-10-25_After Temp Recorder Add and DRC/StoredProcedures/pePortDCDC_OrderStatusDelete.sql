if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderStatusDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderStatusDelete 
GO

-- Deletes a record from the [dbo].[DC_OrderStatus] table.
CREATE PROCEDURE pePortDCDC_OrderStatusDelete
        @pk_OrderStatusId smallint
AS
BEGIN
    DELETE [dbo].[DC_OrderStatus]
    WHERE [OrderStatusId] = @pk_OrderStatusId
END

