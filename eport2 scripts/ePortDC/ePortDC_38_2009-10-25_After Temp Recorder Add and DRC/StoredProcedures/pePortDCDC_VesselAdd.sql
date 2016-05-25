if exists (select * from sysobjects where id = object_id(N'pePortDCDC_VesselAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_VesselAdd 
GO

-- Creates a new record in the [dbo].[DC_Vessel] table.
CREATE PROCEDURE pePortDCDC_VesselAdd
    @p_VesselId smallint,
    @p_ArrivalDate smalldatetime,
    @p_FixedFreight money,
    @p_VesselName nchar(30)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_Vessel]
        (
            [VesselId],
            [ArrivalDate],
            [FixedFreight],
            [VesselName]
        )
    VALUES
        (
             @p_VesselId,
             @p_ArrivalDate,
             @p_FixedFreight,
             @p_VesselName
        )

END

