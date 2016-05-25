if exists (select * from sysobjects where id = object_id(N'pePortDCDC_VesselDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_VesselDelete 
GO

-- Deletes a record from the [dbo].[DC_Vessel] table.
CREATE PROCEDURE pePortDCDC_VesselDelete
        @pk_VesselId smallint
AS
BEGIN
    DELETE [dbo].[DC_Vessel]
    WHERE [VesselId] = @pk_VesselId
END

