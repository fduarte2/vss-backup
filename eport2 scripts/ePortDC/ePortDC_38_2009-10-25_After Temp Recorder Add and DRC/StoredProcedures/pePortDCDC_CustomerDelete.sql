if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerDelete 
GO

-- Deletes a record from the [dbo].[DC_Customer] table.
CREATE PROCEDURE pePortDCDC_CustomerDelete
        @pk_CustomerId smallint
AS
BEGIN
    DELETE [dbo].[DC_Customer]
    WHERE [CustomerId] = @pk_CustomerId
END

