if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderStatusAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderStatusAdd 
GO

-- Creates a new record in the [dbo].[DC_OrderStatus] table.
CREATE PROCEDURE pePortDCDC_OrderStatusAdd
    @p_OrderStatusId smallint,
    @p_Descr nchar(30)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_OrderStatus]
        (
            [OrderStatusId],
            [Descr]
        )
    VALUES
        (
             @p_OrderStatusId,
             @p_Descr
        )

END

