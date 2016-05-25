if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailExpediterAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailExpediterAdd 
GO

-- Creates a new record in the [dbo].[DC_OrderDetailExpediter] table.
CREATE PROCEDURE pePortDCDC_OrderDetailExpediterAdd
    @p_OrderNum nchar(12),
    @p_PackHouseId nchar(5),
    @p_Qty int,
    @p_OrderExpediterId_out int output
AS
BEGIN
    INSERT
    INTO [dbo].[DC_OrderDetailExpediter]
        (
            [OrderNum],
            [PackHouseId],
            [Qty]
        )
    VALUES
        (
             @p_OrderNum,
             @p_PackHouseId,
             @p_Qty
        )

    SET @p_OrderExpediterId_out = SCOPE_IDENTITY()

END

