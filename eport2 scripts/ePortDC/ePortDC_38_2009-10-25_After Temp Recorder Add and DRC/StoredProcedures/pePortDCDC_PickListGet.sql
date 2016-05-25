if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PickListGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_PickListGet 
GO

-- Returns a specific record from the [dbo].[DC_PickList] table.
CREATE PROCEDURE pePortDCDC_PickListGet
        @pk_OrderNum nchar(12),    @pk_OrderDetailId int,    @pk_PickListId int
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_PickList]
    WHERE [OrderNum] =@pk_OrderNum and [OrderDetailId] =@pk_OrderDetailId and [PickListId] =@pk_PickListId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [OrderNum],
        [OrderDetailId],
        [PickListId],
        [Comments],
        [PackHouseId],
        [PalletLocation],
        [PalletQty],
        [PickListSize],
        [ZUser1],
        [ZUser2],
        [ZUser3],
        [ZUser4],
        CAST(BINARY_CHECKSUM([OrderNum],[OrderDetailId],[PickListId],[Comments],[PackHouseId],[PalletLocation],[PalletQty],[PickListSize],[ZUser1],[ZUser2],[ZUser3],[ZUser4]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_PickList]
    WHERE [OrderNum] =@pk_OrderNum and [OrderDetailId] =@pk_OrderDetailId and [PickListId] =@pk_PickListId
END

