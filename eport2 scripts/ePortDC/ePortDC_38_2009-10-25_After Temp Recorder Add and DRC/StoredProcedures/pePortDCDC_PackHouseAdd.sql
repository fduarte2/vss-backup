if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PackHouseAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_PackHouseAdd 
GO

-- Creates a new record in the [dbo].[DC_PackHouse] table.
CREATE PROCEDURE pePortDCDC_PackHouseAdd
    @p_PackHouseId nchar(10),
    @p_GroupName nvarchar(30),
    @p_PackHouseName nvarchar(30)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_PackHouse]
        (
            [PackHouseId],
            [GroupName],
            [PackHouseName]
        )
    VALUES
        (
             @p_PackHouseId,
             @p_GroupName,
             @p_PackHouseName
        )

END

