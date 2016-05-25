if exists (select * from sysobjects where id = object_id(N'pePortDCDC_RoleDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_RoleDelete 
GO

-- Deletes a record from the [dbo].[DC_Role] table.
CREATE PROCEDURE pePortDCDC_RoleDelete
        @pk_RoleId nchar(12)
AS
BEGIN
    DELETE [dbo].[DC_Role]
    WHERE [RoleId] = @pk_RoleId
END

