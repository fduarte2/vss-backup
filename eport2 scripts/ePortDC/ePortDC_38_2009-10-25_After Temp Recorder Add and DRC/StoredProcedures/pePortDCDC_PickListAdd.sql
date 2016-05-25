if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PickListAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_PickListAdd 
GO

-- Creates a new record in the [dbo].[DC_PickList] table.
CREATE PROCEDURE pePortDCDC_PickListAdd
    @p_OrderNum nchar(12),
    @p_OrderDetailId int,
    @p_Comments nvarchar(50),
    @p_PackHouseId nchar(10),
    @p_PalletLocation nvarchar(50),
    @p_PalletQty smallint,
    @p_PickListSize smallint,
    @p_ZUser1 nvarchar(50),
    @p_ZUser2 nvarchar(50),
    @p_ZUser3 nchar(10),
    @p_ZUser4 nchar(10),
    @p_PickListId_out int output
AS
BEGIN
    INSERT
    INTO [dbo].[DC_PickList]
        (
            [OrderNum],
            [OrderDetailId],
            [Comments],
            [PackHouseId],
            [PalletLocation],
            [PalletQty],
            [PickListSize],
            [ZUser1],
            [ZUser2],
            [ZUser3],
            [ZUser4]
        )
    VALUES
        (
             @p_OrderNum,
             @p_OrderDetailId,
             @p_Comments,
             @p_PackHouseId,
             @p_PalletLocation,
             @p_PalletQty,
             @p_PickListSize,
             @p_ZUser1,
             @p_ZUser2,
             @p_ZUser3,
             @p_ZUser4
        )

    SET @p_PickListId_out = SCOPE_IDENTITY()

END

