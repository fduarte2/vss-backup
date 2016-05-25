﻿if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderAdd 
GO

-- Creates a new record in the [dbo].[DC_Order] table.
CREATE PROCEDURE pePortDCDC_OrderAdd
    @p_OrderNum nchar(12),
    @p_Comments nvarchar(50),
    @p_CommentsCancel nvarchar(50),
    @p_CommodityCode smallint,
    @p_ConsigneeId smallint,
    @p_CustomerId smallint,
    @p_CustomerPO nvarchar(15),
    @p_DeliveryDate smalldatetime,
    @p_DirectOrder nvarchar(10),
    @p_DriverCheckInDateTime datetime,
    @p_DriverCheckOutDateTime datetime,
    @p_DriverName nvarchar(30),
    @p_LastUpdateUser nchar(12),
    @p_LastUpdateDateTime datetime,
    @p_LoadType nvarchar(30),
    @p_OrderStatusId smallint,
    @p_PARSBarCode nvarchar(30),
    @p_PARSCarrierDispatchPhone nvarchar(25),
    @p_PARSDriverPhoneMobile nvarchar(25),
    @p_PARSETABorder smalldatetime,
    @p_PARSPortOfEntryNum nchar(3),
    @p_PickUpDate smalldatetime,
    @p_SealNum nchar(15),
    @p_SNMGNum nvarchar(50),
    @p_TEStatus nvarchar(15),
    @p_TotalBoxDamaged smallint,
    @p_TotalCount smallint,
    @p_TotalPalletCount smallint,
    @p_TotalPrice money,
    @p_TotalQuantityKG int,
    @p_TransportCharges money,
    @p_TransporterId smallint,
    @p_TrailerNum nvarchar(15),
    @p_TruckTag nvarchar(15),
    @p_VesselId smallint,
    @p_ZUser1 varchar(50),
    @p_ZUser2 varchar(50),
    @p_ZUser3 nchar(10),
    @p_ZUser4 nchar(10)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Order]
        (
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
            [ZUser4]
        )
    VALUES
        (
             @p_OrderNum,
             @p_Comments,
             @p_CommentsCancel,
             @p_CommodityCode,
             @p_ConsigneeId,
             @p_CustomerId,
             @p_CustomerPO,
             @p_DeliveryDate,
             @p_DirectOrder,
             @p_DriverCheckInDateTime,
             @p_DriverCheckOutDateTime,
             @p_DriverName,
             @p_LastUpdateUser,
             @p_LastUpdateDateTime,
             @p_LoadType,
             @p_OrderStatusId,
             @p_PARSBarCode,
             @p_PARSCarrierDispatchPhone,
             @p_PARSDriverPhoneMobile,
             @p_PARSETABorder,
             @p_PARSPortOfEntryNum,
             @p_PickUpDate,
             @p_SealNum,
             @p_SNMGNum,
             @p_TEStatus,
             @p_TotalBoxDamaged,
             @p_TotalCount,
             @p_TotalPalletCount,
             @p_TotalPrice,
             @p_TotalQuantityKG,
             @p_TransportCharges,
             @p_TransporterId,
             @p_TrailerNum,
             @p_TruckTag,
             @p_VesselId,
             @p_ZUser1,
             @p_ZUser2,
             @p_ZUser3,
             @p_ZUser4
        )

END
