if exists (select * from sysobjects where id = object_id(N'pePortDCDC_VesselGet') and sysstat & 0xf = 4) drop procedure pePortDCDC_VesselGet 
GO

-- Returns a specific record from the [dbo].[DC_Vessel] table.
CREATE PROCEDURE pePortDCDC_VesselGet
        @pk_VesselId smallint
AS
DECLARE
    @l_count int
BEGIN

    -- Get the rowcount first and make sure 
    -- only one row is returned
    SELECT @l_count = count(*) 
    FROM [dbo].[DC_Vessel]
    WHERE [VesselId] =@pk_VesselId

    IF @l_count = 0
        RAISERROR ('The record no longer exists.', 16, 1)
    IF @l_count > 1
        RAISERROR ('duplicate object instances.', 16, 1)

    -- Get the row from the query.  Checksum value will be
    -- returned along the row data to support concurrency.
    SELECT 
        [VesselId],
        [ArrivalDate],
        [FixedFreight],
        [VesselName],
        CAST(BINARY_CHECKSUM([VesselId],[ArrivalDate],[FixedFreight],[VesselName]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345
    FROM [dbo].[DC_Vessel]
    WHERE [VesselId] =@pk_VesselId
END

