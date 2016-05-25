if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomsBrokerOfficeGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomsBrokerOfficeGet 
GO

-- Returns a specific record from the [dbo].[DC_CustomsBrokerOffice] table.
CREATE PROCEDURE pePortDCDC_CustomsBrokerOfficeGet
        @pk_CustomsBrokerOfficeId smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_CustomsBrokerOffice]
    WHERE [CustomsBrokerOfficeId] =@pk_CustomsBrokerOfficeId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [CustomsBrokerOfficeId],
        [BorderCrossing],
        [Client],
        [Comments],
        [ContactName],
        [CustomsBroker],
        [Destinations],
        [Email1],
        [Email2],
        [Email3],
        [Email4],
        [Email5],
        [Fax],
        [Phone],
        CAST(BINARY_CHECKSUM([CustomsBrokerOfficeId],[BorderCrossing],[Client],[Comments],[ContactName],[CustomsBroker],[Destinations],[Email1],[Email2],[Email3],[Email4],[Email5],[Fax],[Phone]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_CustomsBrokerOffice]
    WHERE [CustomsBrokerOfficeId] =@pk_CustomsBrokerOfficeId
END

