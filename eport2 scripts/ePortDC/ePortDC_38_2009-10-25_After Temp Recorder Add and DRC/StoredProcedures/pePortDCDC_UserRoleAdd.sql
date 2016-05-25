if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserRoleAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserRoleAdd 
GO

-- Creates a new record in the [dbo].[DC_UserRole] table.
CREATE PROCEDURE pePortDCDC_UserRoleAdd
    @p_UserId nchar(12),
    @p_RoleId nchar(12)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_UserRole]
        (
            [UserId],
            [RoleId]
        )
    VALUES
        (
             @p_UserId,
             @p_RoleId
        )

END

