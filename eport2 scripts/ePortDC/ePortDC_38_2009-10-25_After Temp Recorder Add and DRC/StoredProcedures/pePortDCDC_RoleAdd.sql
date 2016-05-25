if exists (select * from sysobjects where id = object_id(N'pePortDCDC_RoleAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_RoleAdd 
GO

-- Creates a new record in the [dbo].[DC_Role] table.
CREATE PROCEDURE pePortDCDC_RoleAdd
    @p_RoleId nchar(12),
    @p_Descr nvarchar(50)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Role]
        (
            [RoleId],
            [Descr]
        )
    VALUES
        (
             @p_RoleId,
             @p_Descr
        )

END

