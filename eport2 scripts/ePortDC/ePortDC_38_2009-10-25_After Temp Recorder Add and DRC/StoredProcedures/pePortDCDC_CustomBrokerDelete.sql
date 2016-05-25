if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomBrokerDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomBrokerDelete 
GO

-- Deletes a record from the [dbo].[DC_CustomBroker] table.
CREATE PROCEDURE pePortDCDC_CustomBrokerDelete
        @pk_CustomBrokerId nvarchar(2)
AS
BEGIN
    DELETE [dbo].[DC_CustomBroker]
    WHERE [CustomBrokerId] = @pk_CustomBrokerId
END

