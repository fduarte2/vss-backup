if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CommoditySizeUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_CommoditySizeUpdate 
GO

-- Updates a record in the [dbo].[DC_CommoditySize] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_CommoditySizeUpdate
    @p_SizeId smallint,
    @pk_SizeId smallint,
    @p_Descr nvarchar(50),
    @p_Price money,
    @p_SizeHigh smallint,
    @p_SizeLow smallint,
    @p_WeightKG numeric(10,2),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_CommoditySize] WHERE [SizeId] = @pk_SizeId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_CommoditySize]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_CommoditySize]
            SET 
            [SizeId] = @p_SizeId,
            [Descr] = @p_Descr,
            [Price] = @p_Price,
            [SizeHigh] = @p_SizeHigh,
            [SizeLow] = @p_SizeLow,
            [WeightKG] = @p_WeightKG
            WHERE [SizeId] = @pk_SizeId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([SizeId],[Descr],[Price],[SizeHigh],[SizeLow],[WeightKG]) AS nvarchar(4000)) 
            FROM [dbo].[DC_CommoditySize] with (rowlock, holdlock)
            WHERE [SizeId] = @pk_SizeId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_CommoditySize]
                    SET 
                    [SizeId] = @p_SizeId,
                    [Descr] = @p_Descr,
                    [Price] = @p_Price,
                    [SizeHigh] = @p_SizeHigh,
                    [SizeLow] = @p_SizeLow,
                    [WeightKG] = @p_WeightKG
                    WHERE [SizeId] = @pk_SizeId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_CommoditySize]', 16, 1)

        END
END

