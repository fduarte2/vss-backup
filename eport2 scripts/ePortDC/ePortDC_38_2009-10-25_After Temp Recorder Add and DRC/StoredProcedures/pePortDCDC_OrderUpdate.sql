if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderUpdate 
GO

-- Updates a record in the [dbo].[DC_Order] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_OrderUpdate
    @p_OrderNum nchar(12),
    @pk_OrderNum nchar(12),
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
    @p_ZUser4 nchar(10),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_Order] WHERE [OrderNum] = @pk_OrderNum)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_Order]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_Order]
            SET 
            [OrderNum] = @p_OrderNum,
            [Comments] = @p_Comments,
            [CommentsCancel] = @p_CommentsCancel,
            [CommodityCode] = @p_CommodityCode,
            [ConsigneeId] = @p_ConsigneeId,
            [CustomerId] = @p_CustomerId,
            [CustomerPO] = @p_CustomerPO,
            [DeliveryDate] = @p_DeliveryDate,
            [DirectOrder] = @p_DirectOrder,
            [DriverCheckInDateTime] = @p_DriverCheckInDateTime,
            [DriverCheckOutDateTime] = @p_DriverCheckOutDateTime,
            [DriverName] = @p_DriverName,
            [LastUpdateUser] = @p_LastUpdateUser,
            [LastUpdateDateTime] = @p_LastUpdateDateTime,
            [LoadType] = @p_LoadType,
            [OrderStatusId] = @p_OrderStatusId,
            [PARSBarCode] = @p_PARSBarCode,
            [PARSCarrierDispatchPhone] = @p_PARSCarrierDispatchPhone,
            [PARSDriverPhoneMobile] = @p_PARSDriverPhoneMobile,
            [PARSETABorder] = @p_PARSETABorder,
            [PARSPortOfEntryNum] = @p_PARSPortOfEntryNum,
            [PickUpDate] = @p_PickUpDate,
            [SealNum] = @p_SealNum,
            [SNMGNum] = @p_SNMGNum,
            [TEStatus] = @p_TEStatus,
            [TotalBoxDamaged] = @p_TotalBoxDamaged,
            [TotalCount] = @p_TotalCount,
            [TotalPalletCount] = @p_TotalPalletCount,
            [TotalPrice] = @p_TotalPrice,
            [TotalQuantityKG] = @p_TotalQuantityKG,
            [TransportCharges] = @p_TransportCharges,
            [TransporterId] = @p_TransporterId,
            [TrailerNum] = @p_TrailerNum,
            [TruckTag] = @p_TruckTag,
            [VesselId] = @p_VesselId,
            [ZUser1] = @p_ZUser1,
            [ZUser2] = @p_ZUser2,
            [ZUser3] = @p_ZUser3,
            [ZUser4] = @p_ZUser4
            WHERE [OrderNum] = @pk_OrderNum

            -- Make sure only one record is affected
            SET @l_rowcount = @@ROWCOUNT
            IF @l_rowcount = 0
                RAISERROR ('The record cannot be updated.', 16, 1)
            IF @l_rowcount > 1
                RAISERROR ('duplicate object instances.', 16, 1)

        END
    ELSE
        BEGIN
            -- Get the checksum value for the record 
            -- and put an update lock on the record to 
            -- ensure transactional integrity.  The lock
            -- will be release when the transaction is 
            -- later committed or rolled back.
            Select @l_newValue = CAST(BINARY_CHECKSUM([OrderNum],[Comments],[CommentsCancel],[CommodityCode],[ConsigneeId],[CustomerId],[CustomerPO],[DeliveryDate],[DirectOrder],[DriverCheckInDateTime],[DriverCheckOutDateTime],[DriverName],[LastUpdateUser],[LastUpdateDateTime],[LoadType],[OrderStatusId],[PARSBarCode],[PARSCarrierDispatchPhone],[PARSDriverPhoneMobile],[PARSETABorder],[PARSPortOfEntryNum],[PickUpDate],[SealNum],[SNMGNum],[TEStatus],[TotalBoxDamaged],[TotalCount],[TotalPalletCount],[TotalPrice],[TotalQuantityKG],[TransportCharges],[TransporterId],[TrailerNum],[TruckTag],[VesselId],[ZUser1],[ZUser2],[ZUser3],[ZUser4]) AS nvarchar(4000)) 
            FROM [dbo].[DC_Order] with (rowlock, holdlock)
            WHERE [OrderNum] = @pk_OrderNum


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_Order]
                    SET 
                    [OrderNum] = @p_OrderNum,
                    [Comments] = @p_Comments,
                    [CommentsCancel] = @p_CommentsCancel,
                    [CommodityCode] = @p_CommodityCode,
                    [ConsigneeId] = @p_ConsigneeId,
                    [CustomerId] = @p_CustomerId,
                    [CustomerPO] = @p_CustomerPO,
                    [DeliveryDate] = @p_DeliveryDate,
                    [DirectOrder] = @p_DirectOrder,
                    [DriverCheckInDateTime] = @p_DriverCheckInDateTime,
                    [DriverCheckOutDateTime] = @p_DriverCheckOutDateTime,
                    [DriverName] = @p_DriverName,
                    [LastUpdateUser] = @p_LastUpdateUser,
                    [LastUpdateDateTime] = @p_LastUpdateDateTime,
                    [LoadType] = @p_LoadType,
                    [OrderStatusId] = @p_OrderStatusId,
                    [PARSBarCode] = @p_PARSBarCode,
                    [PARSCarrierDispatchPhone] = @p_PARSCarrierDispatchPhone,
                    [PARSDriverPhoneMobile] = @p_PARSDriverPhoneMobile,
                    [PARSETABorder] = @p_PARSETABorder,
                    [PARSPortOfEntryNum] = @p_PARSPortOfEntryNum,
                    [PickUpDate] = @p_PickUpDate,
                    [SealNum] = @p_SealNum,
                    [SNMGNum] = @p_SNMGNum,
                    [TEStatus] = @p_TEStatus,
                    [TotalBoxDamaged] = @p_TotalBoxDamaged,
                    [TotalCount] = @p_TotalCount,
                    [TotalPalletCount] = @p_TotalPalletCount,
                    [TotalPrice] = @p_TotalPrice,
                    [TotalQuantityKG] = @p_TotalQuantityKG,
                    [TransportCharges] = @p_TransportCharges,
                    [TransporterId] = @p_TransporterId,
                    [TrailerNum] = @p_TrailerNum,
                    [TruckTag] = @p_TruckTag,
                    [VesselId] = @p_VesselId,
                    [ZUser1] = @p_ZUser1,
                    [ZUser2] = @p_ZUser2,
                    [ZUser3] = @p_ZUser3,
                    [ZUser4] = @p_ZUser4
                    WHERE [OrderNum] = @pk_OrderNum

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_Order]', 16, 1)

        END
END

