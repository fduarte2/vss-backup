if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderGet 
GO

-- Returns a specific record from the [dbo].[DC_Order] table.
CREATE PROCEDURE pePortDCDC_OrderGet
        @pk_OrderNum nchar(12)
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_Order]
    WHERE [OrderNum] =@pk_OrderNum

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [OrderNum],
        [Comments],
        [CommentsCancel],
        [CommodityCode],
        [ConsigneeId],
        [CustomerId],
        [CustomerPO],
        [DeliveryDate],
        [DirectOrder],
        [DriverCheckInDateTime],
        [DriverCheckOutDateTime],
        [DriverName],
        [LastUpdateUser],
        [LastUpdateDateTime],
        [LoadType],
        [OrderStatusId],
        [PARSBarCode],
        [PARSCarrierDispatchPhone],
        [PARSDriverPhoneMobile],
        [PARSETABorder],
        [PARSPortOfEntryNum],
        [PickUpDate],
        [SealNum],
        [SNMGNum],
        [TEStatus],
        [TotalBoxDamaged],
        [TotalCount],
        [TotalPalletCount],
        [TotalPrice],
        [TotalQuantityKG],
        [TransportCharges],
        [TransporterId],
        [TrailerNum],
        [TruckTag],
        [VesselId],
        [ZUser1],
        [ZUser2],
        [ZUser3],
        [ZUser4],
        CAST(BINARY_CHECKSUM([OrderNum],[Comments],[CommentsCancel],[CommodityCode],[ConsigneeId],[CustomerId],[CustomerPO],[DeliveryDate],[DirectOrder],[DriverCheckInDateTime],[DriverCheckOutDateTime],[DriverName],[LastUpdateUser],[LastUpdateDateTime],[LoadType],[OrderStatusId],[PARSBarCode],[PARSCarrierDispatchPhone],[PARSDriverPhoneMobile],[PARSETABorder],[PARSPortOfEntryNum],[PickUpDate],[SealNum],[SNMGNum],[TEStatus],[TotalBoxDamaged],[TotalCount],[TotalPalletCount],[TotalPrice],[TotalQuantityKG],[TransportCharges],[TransporterId],[TrailerNum],[TruckTag],[VesselId],[ZUser1],[ZUser2],[ZUser3],[ZUser4]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_Order]
    WHERE [OrderNum] =@pk_OrderNum
END

