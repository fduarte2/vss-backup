if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserDelete 
GO

-- Deletes a record from the [dbo].[DC_User] table.
CREATE PROCEDURE pePortDCDC_UserDelete
        @pk_UserId nchar(12)
AS
BEGIN
    DELETE [dbo].[DC_User]
    WHERE [UserId] = @pk_UserId
END

