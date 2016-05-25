if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerUpdate 
GO

-- Updates a record in the [dbo].[DC_Customer] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_CustomerUpdate
    @p_CustomerId smallint,
    @pk_CustomerId smallint,
    @p_Address nvarchar(50),
    @p_City nvarchar(30),
    @p_Comments nvarchar(50),
    @p_CustomerName nvarchar(50),
    @p_CustomerShortName nvarchar(30),
    @p_DestCode nvarchar(25),
    @p_NeedPARS bit,
    @p_Origin nvarchar(30),
    @p_Phone nvarchar(25),
    @p_PhoneMobile nvarchar(25),
    @p_PostalCode nvarchar(15),
    @p_StateProvince nvarchar(30),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_Customer] WHERE [CustomerId] = @pk_CustomerId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_Customer]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_Customer]
            SET 
            [CustomerId] = @p_CustomerId,
            [Address] = @p_Address,
            [City] = @p_City,
            [Comments] = @p_Comments,
            [CustomerName] = @p_CustomerName,
            [CustomerShortName] = @p_CustomerShortName,
            [DestCode] = @p_DestCode,
            [NeedPARS] = @p_NeedPARS,
            [Origin] = @p_Origin,
            [Phone] = @p_Phone,
            [PhoneMobile] = @p_PhoneMobile,
            [PostalCode] = @p_PostalCode,
            [StateProvince] = @p_StateProvince
            WHERE [CustomerId] = @pk_CustomerId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([CustomerId],[Address],[City],[Comments],[CustomerName],[CustomerShortName],[DestCode],[NeedPARS],[Origin],[Phone],[PhoneMobile],[PostalCode],[StateProvince]) AS nvarchar(4000)) 
            FROM [dbo].[DC_Customer] with (rowlock, holdlock)
            WHERE [CustomerId] = @pk_CustomerId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_Customer]
                    SET 
                    [CustomerId] = @p_CustomerId,
                    [Address] = @p_Address,
                    [City] = @p_City,
                    [Comments] = @p_Comments,
                    [CustomerName] = @p_CustomerName,
                    [CustomerShortName] = @p_CustomerShortName,
                    [DestCode] = @p_DestCode,
                    [NeedPARS] = @p_NeedPARS,
                    [Origin] = @p_Origin,
                    [Phone] = @p_Phone,
                    [PhoneMobile] = @p_PhoneMobile,
                    [PostalCode] = @p_PostalCode,
                    [StateProvince] = @p_StateProvince
                    WHERE [CustomerId] = @pk_CustomerId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_Customer]', 16, 1)

        END
END

