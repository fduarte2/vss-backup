if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PickListUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_PickListUpdate 
GO

-- Updates a record in the [dbo].[DC_PickList] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_PickListUpdate
    @p_OrderNum nchar(12),
    @pk_OrderNum nchar(12),
    @p_OrderDetailId int,
    @pk_OrderDetailId int,
    @pk_PickListId int,
    @p_Comments nvarchar(50),
    @p_PackHouseId nchar(10),
    @p_PalletLocation nvarchar(50),
    @p_PalletQty smallint,
    @p_PickListSize smallint,
    @p_ZUser1 nvarchar(50),
    @p_ZUser2 nvarchar(50),
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
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_PickList] WHERE [OrderNum] = @pk_OrderNum and [OrderDetailId] = @pk_OrderDetailId and [PickListId] = @pk_PickListId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_PickList]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_PickList]
            SET 
            [OrderNum] = @p_OrderNum,
            [OrderDetailId] = @p_OrderDetailId,
            [Comments] = @p_Comments,
            [PackHouseId] = @p_PackHouseId,
            [PalletLocation] = @p_PalletLocation,
            [PalletQty] = @p_PalletQty,
            [PickListSize] = @p_PickListSize,
            [ZUser1] = @p_ZUser1,
            [ZUser2] = @p_ZUser2,
            [ZUser3] = @p_ZUser3,
            [ZUser4] = @p_ZUser4
            WHERE [OrderNum] = @pk_OrderNum and [OrderDetailId] = @pk_OrderDetailId and [PickListId] = @pk_PickListId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([OrderNum],[OrderDetailId],[PickListId],[Comments],[PackHouseId],[PalletLocation],[PalletQty],[PickListSize],[ZUser1],[ZUser2],[ZUser3],[ZUser4]) AS nvarchar(4000)) 
            FROM [dbo].[DC_PickList] with (rowlock, holdlock)
            WHERE [OrderNum] = @pk_OrderNum and [OrderDetailId] = @pk_OrderDetailId and [PickListId] = @pk_PickListId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_PickList]
                    SET 
                    [OrderNum] = @p_OrderNum,
                    [OrderDetailId] = @p_OrderDetailId,
                    [Comments] = @p_Comments,
                    [PackHouseId] = @p_PackHouseId,
                    [PalletLocation] = @p_PalletLocation,
                    [PalletQty] = @p_PalletQty,
                    [PickListSize] = @p_PickListSize,
                    [ZUser1] = @p_ZUser1,
                    [ZUser2] = @p_ZUser2,
                    [ZUser3] = @p_ZUser3,
                    [ZUser4] = @p_ZUser4
                    WHERE [OrderNum] = @pk_OrderNum and [OrderDetailId] = @pk_OrderDetailId and [PickListId] = @pk_PickListId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_PickList]', 16, 1)

        END
END

