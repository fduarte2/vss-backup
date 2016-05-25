if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserGet 
GO

-- Returns a specific record from the [dbo].[DC_User] table.
CREATE PROCEDURE pePortDCDC_UserGet
        @pk_UserId nchar(12)
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_User]
    WHERE [UserId] =@pk_UserId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [UserId],
        [Email],
        [Name],
        [Password],
        [Phone],
        [PhoneMobile],
        [Printer],
        [Role],
        CAST(BINARY_CHECKSUM([UserId],[Email],[Name],[Password],[Phone],[PhoneMobile],[Printer],[Role]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_User]
    WHERE [UserId] =@pk_UserId
END

