if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailAdd 
GO

-- Creates a new record in the [dbo].[DC_OrderDetail] table.
CREATE PROCEDURE pePortDCDC_OrderDetailAdd
    @p_OrderNum nchar(12),
    @p_Comments nvarchar(50),
    @p_DeliveredQty smallint,
    @p_OrderQty smallint,
    @p_OrderSizeId smallint,
    @p_Price money,
    @p_SizeHigh smallint,
    @p_SizeLow smallint,
    @p_WeightKG numeric(10,2),
    @p_ZUser1 nvarchar(50),
    @p_ZUser2 nvarchar(50),
    @p_ZUser3 nchar(10),
    @p_ZUser4 nchar(10),
    @p_OrderDetailId_out int output
AS
BEGIN
    INSERT
    INTO [dbo].[DC_OrderDetail]
        (
            [OrderNum],
            [Comments],
            [DeliveredQty],
            [OrderQty],
            [OrderSizeId],
            [Price],
            [SizeHigh],
            [SizeLow],
            [WeightKG],
            [ZUser1],
            [ZUser2],
            [ZUser3],
            [ZUser4]
        )
    VALUES
        (
             @p_OrderNum,
             @p_Comments,
             @p_DeliveredQty,
             @p_OrderQty,
             @p_OrderSizeId,
             @p_Price,
             @p_SizeHigh,
             @p_SizeLow,
             @p_WeightKG,
             @p_ZUser1,
             @p_ZUser2,
             @p_ZUser3,
             @p_ZUser4
        )

    SET @p_OrderDetailId_out = SCOPE_IDENTITY()

END

