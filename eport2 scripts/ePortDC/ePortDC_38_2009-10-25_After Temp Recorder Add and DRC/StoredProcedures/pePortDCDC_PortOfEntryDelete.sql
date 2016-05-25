if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PortOfEntryDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_PortOfEntryDelete 
GO

-- Deletes a record from the [dbo].[DC_PortOfEntry] table.
CREATE PROCEDURE pePortDCDC_PortOfEntryDelete
        @pk_PortCode nchar(3)
AS
BEGIN
    DELETE [dbo].[DC_PortOfEntry]
    WHERE [PortCode] = @pk_PortCode
END

