if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomBrokerUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomBrokerUpdate 
GO

-- Updates a record in the [dbo].[DC_CustomBroker] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_CustomBrokerUpdate
    @p_CustomBrokerId nvarchar(2),
    @pk_CustomBrokerId nvarchar(2),
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
    @p_Phone nvarchar(25),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_CustomBroker] WHERE [CustomBrokerId] = @pk_CustomBrokerId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_CustomBroker]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_CustomBroker]
            SET 
            [CustomBrokerId] = @p_CustomBrokerId,
            [BorderCrossing] = @p_BorderCrossing,
            [Client] = @p_Client,
            [Comments] = @p_Comments,
            [ContactName] = @p_ContactName,
            [CustomsBroker] = @p_CustomsBroker,
            [Destinations] = @p_Destinations,
            [Email1] = @p_Email1,
            [Email2] = @p_Email2,
            [Email3] = @p_Email3,
            [Email4] = @p_Email4,
            [Email5] = @p_Email5,
            [Fax] = @p_Fax,
            [Phone] = @p_Phone
            WHERE [CustomBrokerId] = @pk_CustomBrokerId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([CustomBrokerId],[BorderCrossing],[Client],[Comments],[ContactName],[CustomsBroker],[Destinations],[Email1],[Email2],[Email3],[Email4],[Email5],[Fax],[Phone]) AS nvarchar(4000)) 
            FROM [dbo].[DC_CustomBroker] with (rowlock, holdlock)
            WHERE [CustomBrokerId] = @pk_CustomBrokerId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_CustomBroker]
                    SET 
                    [CustomBrokerId] = @p_CustomBrokerId,
                    [BorderCrossing] = @p_BorderCrossing,
                    [Client] = @p_Client,
                    [Comments] = @p_Comments,
                    [ContactName] = @p_ContactName,
                    [CustomsBroker] = @p_CustomsBroker,
                    [Destinations] = @p_Destinations,
                    [Email1] = @p_Email1,
                    [Email2] = @p_Email2,
                    [Email3] = @p_Email3,
                    [Email4] = @p_Email4,
                    [Email5] = @p_Email5,
                    [Fax] = @p_Fax,
                    [Phone] = @p_Phone
                    WHERE [CustomBrokerId] = @pk_CustomBrokerId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_CustomBroker]', 16, 1)

        END
END

