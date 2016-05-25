if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailExpediterGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailExpediterGet 
GO

-- Returns a specific record from the [dbo].[DC_OrderDetailExpediter] table.
CREATE PROCEDURE pePortDCDC_OrderDetailExpediterGet
        @pk_OrderNum nchar(12),    @pk_OrderExpediterId int
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_OrderDetailExpediter]
    WHERE [OrderNum] =@pk_OrderNum and [OrderExpediterId] =@pk_OrderExpediterId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [OrderNum],
        [OrderExpediterId],
        [PackHouseId],
        [Qty],
        CAST(BINARY_CHECKSUM([OrderNum],[OrderExpediterId],[PackHouseId],[Qty]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_OrderDetailExpediter]
    WHERE [OrderNum] =@pk_OrderNum and [OrderExpediterId] =@pk_OrderExpediterId
END

