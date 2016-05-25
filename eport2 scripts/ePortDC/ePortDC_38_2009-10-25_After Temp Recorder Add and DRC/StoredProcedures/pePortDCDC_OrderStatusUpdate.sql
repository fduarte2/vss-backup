﻿if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderStatusUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderStatusUpdate 
GO

-- Updates a record in the [dbo].[DC_OrderStatus] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_OrderStatusUpdate
    @p_OrderStatusId smallint,
    @pk_OrderStatusId smallint,
    @p_Descr nchar(30),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_OrderStatus] WHERE [OrderStatusId] = @pk_OrderStatusId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_OrderStatus]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_OrderStatus]
            SET 
            [OrderStatusId] = @p_OrderStatusId,
            [Descr] = @p_Descr
            WHERE [OrderStatusId] = @pk_OrderStatusId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([OrderStatusId],[Descr]) AS nvarchar(4000)) 
            FROM [dbo].[DC_OrderStatus] with (rowlock, holdlock)
            WHERE [OrderStatusId] = @pk_OrderStatusId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_OrderStatus]
                    SET 
                    [OrderStatusId] = @p_OrderStatusId,
                    [Descr] = @p_Descr
                    WHERE [OrderStatusId] = @pk_OrderStatusId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_OrderStatus]', 16, 1)

        END
END
