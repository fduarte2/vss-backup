if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomBrokerAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomBrokerAdd 
GO

-- Creates a new record in the [dbo].[DC_CustomBroker] table.
CREATE PROCEDURE pePortDCDC_CustomBrokerAdd
    @p_CustomBrokerId nvarchar(2),
    @p_BorderCrossing nvarchar(50),
    @p_Client nvarchar(50),
    @p_Comments nvarchar(50),
    @p_ContactName nvarchar(50),
    @p_CustomsBroker nvarchar(50),
    @p_Destinations nvarchar(50),
    @p_Email1 nvarchar(50),
    @p_Email2 nvarchar(50),
    @p_Email3 nvarchar(50),
    @p_Email4 nvarchar(50),
    @p_Email5 nvarchar(50),
    @p_Fax nvarchar(25),
    @p_Phone nvarchar(25)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_CustomBroker]
        (
            [CustomBrokerId],
            [BorderCrossing],
            [Client],
            [Comments],
            [ContactName],
            [CustomsBroker],
            [Destinations],
            [Email1],
            [Email2],
            [Email3],
            [Email4],
            [Email5],
            [Fax],
            [Phone]
        )
    VALUES
        (
             @p_CustomBrokerId,
             @p_BorderCrossing,
             @p_Client,
             @p_Comments,
             @p_ContactName,
             @p_CustomsBroker,
             @p_Destinations,
             @p_Email1,
             @p_Email2,
             @p_Email3,
             @p_Email4,
             @p_Email5,
             @p_Fax,
             @p_Phone
        )

END

