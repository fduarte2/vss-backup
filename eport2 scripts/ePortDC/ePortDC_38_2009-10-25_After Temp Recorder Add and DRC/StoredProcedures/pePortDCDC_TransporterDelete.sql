if exists (select * from sysobjects where id = object_id(N'pePortDCDC_TransporterDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_TransporterDelete 
GO

-- Deletes a record from the [dbo].[DC_Transporter] table.
CREATE PROCEDURE pePortDCDC_TransporterDelete
        @pk_TransporterId smallint
AS
BEGIN
    DELETE [dbo].[DC_Transporter]
    WHERE [TransporterId] = @pk_TransporterId
END

