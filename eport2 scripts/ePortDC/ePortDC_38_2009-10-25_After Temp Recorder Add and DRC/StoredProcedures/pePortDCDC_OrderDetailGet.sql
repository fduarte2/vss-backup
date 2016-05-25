if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailGet 
GO

-- Returns a specific record from the [dbo].[DC_OrderDetail] table.
CREATE PROCEDURE pePortDCDC_OrderDetailGet
        @pk_OrderNum nchar(12),    @pk_OrderDetailId int
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_OrderDetail]
    WHERE [OrderNum] =@pk_OrderNum and [OrderDetailId] =@pk_OrderDetailId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [OrderNum],
        [OrderDetailId],
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
        [ZUser4],
        CAST(BINARY_CHECKSUM([OrderNum],[OrderDetailId],[Comments],[DeliveredQty],[OrderQty],[OrderSizeId],[Price],[SizeHigh],[SizeLow],[WeightKG],[ZUser1],[ZUser2],[ZUser3],[ZUser4]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_OrderDetail]
    WHERE [OrderNum] =@pk_OrderNum and [OrderDetailId] =@pk_OrderDetailId
END

