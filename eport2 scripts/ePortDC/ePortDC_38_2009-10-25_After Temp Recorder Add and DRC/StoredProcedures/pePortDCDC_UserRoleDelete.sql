if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserRoleDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserRoleDelete 
GO

-- Deletes a record from the [dbo].[DC_UserRole] table.
CREATE PROCEDURE pePortDCDC_UserRoleDelete
        @pk_UserId nchar(12),
    @pk_RoleId nchar(12)
AS
BEGIN
    DELETE [dbo].[DC_UserRole]
    WHERE [UserId] = @pk_UserId
    AND [RoleId] = @pk_RoleId
END

