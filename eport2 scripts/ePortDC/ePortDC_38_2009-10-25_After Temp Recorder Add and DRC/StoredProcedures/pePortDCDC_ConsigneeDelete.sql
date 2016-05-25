if exists (select * from sysobjects where id = object_id(N'pePortDCDC_ConsigneeDelete') and sysstat & 0xf = 4) drop procedure pePortDCDC_ConsigneeDelete 
GO

-- Deletes a record from the [dbo].[DC_Consignee] table.
CREATE PROCEDURE pePortDCDC_ConsigneeDelete
        @pk_ConsigneeId smallint
AS
BEGIN
    DELETE [dbo].[DC_Consignee]
    WHERE [ConsigneeId] = @pk_ConsigneeId
END

