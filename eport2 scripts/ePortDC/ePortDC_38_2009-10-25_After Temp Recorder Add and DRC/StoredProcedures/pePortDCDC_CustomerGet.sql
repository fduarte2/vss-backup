if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerGet 
GO

-- Returns a specific record from the [dbo].[DC_Customer] table.
CREATE PROCEDURE pePortDCDC_CustomerGet
        @pk_CustomerId smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_Customer]
    WHERE [CustomerId] =@pk_CustomerId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [CustomerId],
        [Address],
        [City],
        [Comments],
        [CustomerName],
        [CustomerShortName],
        [DestCode],
        [NeedPARS],
        [Origin],
        [Phone],
        [PhoneMobile],
        [PostalCode],
        [StateProvince],
        CAST(BINARY_CHECKSUM([CustomerId],[Address],[City],[Comments],[CustomerName],[CustomerShortName],[DestCode],[NeedPARS],[Origin],[Phone],[PhoneMobile],[PostalCode],[StateProvince]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_Customer]
    WHERE [CustomerId] =@pk_CustomerId
END

