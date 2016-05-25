if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerAdd 
GO

-- Creates a new record in the [dbo].[DC_Customer] table.
CREATE PROCEDURE pePortDCDC_CustomerAdd
    @p_CustomerId smallint,
    @p_Address nvarchar(50),
    @p_City nvarchar(30),
    @p_Comments nvarchar(50),
    @p_CustomerName nvarchar(50),
    @p_CustomerShortName nvarchar(30),
    @p_DestCode nvarchar(25),
    @p_NeedPARS bit,
    @p_Origin nvarchar(30),
    @p_Phone nvarchar(25),
    @p_PhoneMobile nvarchar(25),
    @p_PostalCode nvarchar(15),
    @p_StateProvince nvarchar(30)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Customer]
        (
            [CustomerId],
            [Address],
            [City],
            [Comments],
            [CustomerName],
            [CustomerShortName],
            [DestCode],
            [Origin],
            [Phone],
            [PhoneMobile],
            [PostalCode],
            [StateProvince]
        )
    VALUES
        (
             @p_CustomerId,
             @p_Address,
             @p_City,
             @p_Comments,
             @p_CustomerName,
             @p_CustomerShortName,
             @p_DestCode,
             @p_Origin,
             @p_Phone,
             @p_PhoneMobile,
             @p_PostalCode,
             @p_StateProvince
        )

    -- Call UPDATE for fields that have database defaults
    IF @p_NeedPARS IS NOT NULL
        UPDATE [dbo].[DC_Customer] SET [NeedPARS] = @p_NeedPARS WHERE [CustomerId] = @p_CustomerId

END

