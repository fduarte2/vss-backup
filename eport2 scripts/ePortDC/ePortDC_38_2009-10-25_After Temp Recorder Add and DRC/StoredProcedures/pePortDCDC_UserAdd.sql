if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserAdd 
GO

-- Creates a new record in the [dbo].[DC_User] table.
CREATE PROCEDURE pePortDCDC_UserAdd
    @p_UserId nchar(12),
    @p_Email nvarchar(50),
    @p_Name nvarchar(50),
    @p_Password nvarchar(30),
    @p_Phone nvarchar(15),
    @p_PhoneMobile nvarchar(15),
    @p_Printer nvarchar(50),
    @p_Role nchar(12)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_User]
        (
            [UserId],
            [Email],
            [Name],
            [Password],
            [Phone],
            [PhoneMobile],
            [Printer],
            [Role]
        )
    VALUES
        (
             @p_UserId,
             @p_Email,
             @p_Name,
             @p_Password,
             @p_Phone,
             @p_PhoneMobile,
             @p_Printer,
             @p_Role
        )

END

