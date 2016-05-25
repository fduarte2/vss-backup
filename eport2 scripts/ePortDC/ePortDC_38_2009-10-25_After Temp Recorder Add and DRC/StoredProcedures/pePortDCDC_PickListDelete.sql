if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PickListDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_PickListDelete 
GO

-- Deletes a record from the [dbo].[DC_PickList] table.
CREATE PROCEDURE pePortDCDC_PickListDelete
        @pk_OrderNum nchar(12),
    @pk_OrderDetailId int,
    @pk_PickListId int
AS
BEGIN
    DELETE [dbo].[DC_PickList]
    WHERE [OrderNum] = @pk_OrderNum
    AND [OrderDetailId] = @pk_OrderDetailId
    AND [PickListId] = @pk_PickListId
END

