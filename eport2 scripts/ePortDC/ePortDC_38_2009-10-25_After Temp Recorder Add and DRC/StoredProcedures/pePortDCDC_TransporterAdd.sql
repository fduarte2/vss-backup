if exists (select * from sysobjects where id = object_id(N'pePortDCDC_TransporterAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_TransporterAdd 
GO

-- Creates a new record in the [dbo].[DC_Transporter] table.
CREATE PROCEDURE pePortDCDC_TransporterAdd
    @p_TransporterId smallint,
    @p_CarrierName nvarchar(30),
    @p_Comments nvarchar(50),
    @p_ContactName nvarchar(50),
    @p_Email nvarchar(50),
    @p_Fax nvarchar(25),
    @p_IRSNum nvarchar(15),
    @p_Phone1 nvarchar(25),
    @p_Phone2 nvarchar(25),
    @p_PhoneCell1 nvarchar(25),
    @p_PhoneCell2 nvarchar(25),
    @p_Rate1GTAMiltonWhitby money,
    @p_Rate2Cambridge money,
    @p_Rate3Ottawa money,
    @p_Rate4Montreal money,
    @p_Rate5Quebec money,
    @p_Rate6Moncton money,
    @p_Rate7Debert money,
    @p_Rate8Other money,
    @p_USBondNum nvarchar(15)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Transporter]
        (
            [TransporterId],
            [CarrierName],
            [Comments],
            [ContactName],
            [Email],
            [Fax],
            [IRSNum],
            [Phone1],
            [Phone2],
            [PhoneCell1],
            [PhoneCell2],
            [Rate1GTAMiltonWhitby],
            [Rate2Cambridge],
            [Rate3Ottawa],
            [Rate4Montreal],
            [Rate5Quebec],
            [Rate6Moncton],
            [Rate7Debert],
            [Rate8Other],
            [USBondNum]
        )
    VALUES
        (
             @p_TransporterId,
             @p_CarrierName,
             @p_Comments,
             @p_ContactName,
             @p_Email,
             @p_Fax,
             @p_IRSNum,
             @p_Phone1,
             @p_Phone2,
             @p_PhoneCell1,
             @p_PhoneCell2,
             @p_Rate1GTAMiltonWhitby,
             @p_Rate2Cambridge,
             @p_Rate3Ottawa,
             @p_Rate4Montreal,
             @p_Rate5Quebec,
             @p_Rate6Moncton,
             @p_Rate7Debert,
             @p_Rate8Other,
             @p_USBondNum
        )

END

