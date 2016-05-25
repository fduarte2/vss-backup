if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomsBrokerOfficeDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomsBrokerOfficeDelete 
GO

-- Deletes a record from the [dbo].[DC_CustomsBrokerOffice] table.
CREATE PROCEDURE pePortDCDC_CustomsBrokerOfficeDelete
        @pk_CustomsBrokerOfficeId smallint
AS
BEGIN
    DELETE [dbo].[DC_CustomsBrokerOffice]
    WHERE [CustomsBrokerOfficeId] = @pk_CustomsBrokerOfficeId
END

