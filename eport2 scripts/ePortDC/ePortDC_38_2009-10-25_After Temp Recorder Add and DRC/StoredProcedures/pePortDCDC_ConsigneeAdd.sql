if exists (select * from sysobjects where id = object_id(N'pePortDCDC_ConsigneeAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_ConsigneeAdd 
GO

-- Creates a new record in the [dbo].[DC_Consignee] table.
CREATE PROCEDURE pePortDCDC_ConsigneeAdd
    @p_ConsigneeId smallint,
    @p_Address nvarchar(50),
    @p_CustomsBrokerOfficeId smallint,
    @p_City nvarchar(30),
    @p_Comments nvarchar(50),
    @p_ConsigneeName nvarchar(30),
    @p_CustomerId smallint,
    @p_Phone nvarchar(25),
    @p_PhoneMobile nvarchar(25),
    @p_PostalCode nvarchar(15),
    @p_StateProvince nvarchar(30)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Consignee]
        (
            [ConsigneeId],
            [Address],
            [CustomsBrokerOfficeId],
            [City],
            [Comments],
            [ConsigneeName],
            [CustomerId],
            [Phone],
            [PhoneMobile],
            [PostalCode],
            [StateProvince]
        )
    VALUES
        (
             @p_ConsigneeId,
             @p_Address,
             @p_CustomsBrokerOfficeId,
             @p_City,
             @p_Comments,
             @p_ConsigneeName,
             @p_CustomerId,
             @p_Phone,
             @p_PhoneMobile,
             @p_PostalCode,
             @p_StateProvince
        )

END

