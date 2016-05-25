if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PackHouseDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_PackHouseDelete 
GO

-- Deletes a record from the [dbo].[DC_PackHouse] table.
CREATE PROCEDURE pePortDCDC_PackHouseDelete
        @pk_PackHouseId nchar(10)
AS
BEGIN
    DELETE [dbo].[DC_PackHouse]
    WHERE [PackHouseId] = @pk_PackHouseId
END

