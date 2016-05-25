if exists (select * from sysobjects where id = object_id(N'pePortDCDC_TransporterUpdate') and sysstat & 0xf = 4) drop procedure pePortDCDC_TransporterUpdate 
GO

-- Updates a record in the [dbo].[DC_Transporter] table.
-- Concurreny is supported by using checksum method.
CREATE PROCEDURE pePortDCDC_TransporterUpdate
    @p_TransporterId smallint,
    @pk_TransporterId smallint,
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
    @p_USBondNum nvarchar(15),
    @p_prevConValue nvarchar(4000),
    @p_force_update  char(1)
AS
DECLARE
    @l_newValue nvarchar(4000),
    @return_status int,
    @l_rowcount int
BEGIN
-- Check whether the record still exists before doing update
    IF NOT EXISTS (SELECT * FROM [dbo].[DC_Transporter] WHERE [TransporterId] = @pk_TransporterId)
        RAISERROR ('Concurrency Error: The record has been deleted by another user. Table [dbo].[DC_Transporter]', 16, 1)

    -- If user wants to force update to happen even if 
    -- the record has been modified by a concurrent user,
    -- then we do this.
    IF (@p_force_update = 'Y')
        BEGIN

            -- Update the record with the passed parameters
            UPDATE [dbo].[DC_Transporter]
            SET 
            [TransporterId] = @p_TransporterId,
            [CarrierName] = @p_CarrierName,
            [Comments] = @p_Comments,
            [ContactName] = @p_ContactName,
            [Email] = @p_Email,
            [Fax] = @p_Fax,
            [IRSNum] = @p_IRSNum,
            [Phone1] = @p_Phone1,
            [Phone2] = @p_Phone2,
            [PhoneCell1] = @p_PhoneCell1,
            [PhoneCell2] = @p_PhoneCell2,
            [Rate1GTAMiltonWhitby] = @p_Rate1GTAMiltonWhitby,
            [Rate2Cambridge] = @p_Rate2Cambridge,
            [Rate3Ottawa] = @p_Rate3Ottawa,
            [Rate4Montreal] = @p_Rate4Montreal,
            [Rate5Quebec] = @p_Rate5Quebec,
            [Rate6Moncton] = @p_Rate6Moncton,
            [Rate7Debert] = @p_Rate7Debert,
            [Rate8Other] = @p_Rate8Other,
            [USBondNum] = @p_USBondNum
            WHERE [TransporterId] = @pk_TransporterId

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
            Select @l_newValue = CAST(BINARY_CHECKSUM([TransporterId],[CarrierName],[Comments],[ContactName],[Email],[Fax],[IRSNum],[Phone1],[Phone2],[PhoneCell1],[PhoneCell2],[Rate1GTAMiltonWhitby],[Rate2Cambridge],[Rate3Ottawa],[Rate4Montreal],[Rate5Quebec],[Rate6Moncton],[Rate7Debert],[Rate8Other],[USBondNum]) AS nvarchar(4000)) 
            FROM [dbo].[DC_Transporter] with (rowlock, holdlock)
            WHERE [TransporterId] = @pk_TransporterId


            -- Check concurrency by comparing the checksum values
            IF (@p_prevConValue = @l_newValue)
                SET @return_status = 0     -- pass
            ElSE
                SET @return_status = 1     -- fail

            -- Concurrency check passed.  Go ahead and 
            -- update the record
            IF (@return_status = 0)
                BEGIN

                    UPDATE [dbo].[DC_Transporter]
                    SET 
                    [TransporterId] = @p_TransporterId,
                    [CarrierName] = @p_CarrierName,
                    [Comments] = @p_Comments,
                    [ContactName] = @p_ContactName,
                    [Email] = @p_Email,
                    [Fax] = @p_Fax,
                    [IRSNum] = @p_IRSNum,
                    [Phone1] = @p_Phone1,
                    [Phone2] = @p_Phone2,
                    [PhoneCell1] = @p_PhoneCell1,
                    [PhoneCell2] = @p_PhoneCell2,
                    [Rate1GTAMiltonWhitby] = @p_Rate1GTAMiltonWhitby,
                    [Rate2Cambridge] = @p_Rate2Cambridge,
                    [Rate3Ottawa] = @p_Rate3Ottawa,
                    [Rate4Montreal] = @p_Rate4Montreal,
                    [Rate5Quebec] = @p_Rate5Quebec,
                    [Rate6Moncton] = @p_Rate6Moncton,
                    [Rate7Debert] = @p_Rate7Debert,
                    [Rate8Other] = @p_Rate8Other,
                    [USBondNum] = @p_USBondNum
                    WHERE [TransporterId] = @pk_TransporterId

                    SET @l_rowcount = @@ROWCOUNT
                    IF @l_rowcount = 0
                        RAISERROR ('The record cannot be updated.', 16, 1)
                    IF @l_rowcount > 1
                        RAISERROR ('duplicate object instances.', 16, 1)

                END
            ELSE
            -- Concurrency check failed.  Inform the user by raising the error
                RAISERROR ('Concurrency Error: The record has been updated by another user. Table [dbo].[DC_Transporter]', 16, 1)

        END
END

