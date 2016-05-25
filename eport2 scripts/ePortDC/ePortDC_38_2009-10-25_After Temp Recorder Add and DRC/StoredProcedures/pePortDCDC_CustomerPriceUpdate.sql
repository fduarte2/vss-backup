if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerPriceUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerPriceUpdate 
GO

-- Updates a record in the [dbo].[DC_CustomerPrice] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_CustomerPriceUpdate
    @p_CustomerId smallint,
    @pk_CustomerId smallint,
    @p_CommodityCode smallint,
    @pk_CommodityCode smallint,
    @p_SizeId smallint,
    @pk_SizeId smallint,
    @p_EffectiveDate smalldatetime,
    @pk_EffectiveDate smalldatetime,
    @p_Comments nvarchar(50),
    @p_Price money,
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_CustomerPrice] WHERE [CustomerId] = @pk_CustomerId and [CommodityCode] = @pk_CommodityCode and [SizeId] = @pk_SizeId and [EffectiveDate] = @pk_EffectiveDate)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_CustomerPrice]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_CustomerPrice]
            SET 
            [CustomerId] = @p_CustomerId,
            [CommodityCode] = @p_CommodityCode,
            [SizeId] = @p_SizeId,
            [EffectiveDate] = @p_EffectiveDate,
            [Comments] = @p_Comments,
            [Price] = @p_Price
            WHERE [CustomerId] = @pk_CustomerId and [CommodityCode] = @pk_CommodityCode and [SizeId] = @pk_SizeId and [EffectiveDate] = @pk_EffectiveDate

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([CustomerId],[CommodityCode],[SizeId],[EffectiveDate],[Comments],[Price]) AS nvarchar(4000)) 
            FROM [dbo].[DC_CustomerPrice] with (rowlock, holdlock)
            WHERE [CustomerId] = @pk_CustomerId and [CommodityCode] = @pk_CommodityCode and [SizeId] = @pk_SizeId and [EffectiveDate] = @pk_EffectiveDate


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_CustomerPrice]
                    SET 
                    [CustomerId] = @p_CustomerId,
                    [CommodityCode] = @p_CommodityCode,
                    [SizeId] = @p_SizeId,
                    [EffectiveDate] = @p_EffectiveDate,
                    [Comments] = @p_Comments,
                    [Price] = @p_Price
                    WHERE [CustomerId] = @pk_CustomerId and [CommodityCode] = @pk_CommodityCode and [SizeId] = @pk_SizeId and [EffectiveDate] = @pk_EffectiveDate

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_CustomerPrice]', 16, 1)

        END
END

