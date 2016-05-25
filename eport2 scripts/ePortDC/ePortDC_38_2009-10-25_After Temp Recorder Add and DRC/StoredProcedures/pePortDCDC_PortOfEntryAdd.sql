if exists (select * from sysobjects where id = object_id(N'pePortDCDC_PortOfEntryAdd') and sysstat & 0xf = 4) drop procedure pePortDCDC_PortOfEntryAdd 
GO

-- Creates a new record in the [dbo].[DC_PortOfEntry] table.
CREATE PROCEDURE pePortDCDC_PortOfEntryAdd
    @p_PortCode nchar(3),
    @p_PortName nvarchar(50)
AS
BEGIN
    INSERT
    INTO [dbo].[DC_PortOfEntry]
        (
            [PortCode],
            [PortName]
        )
    VALUES
        (
             @p_PortCode,
             @p_PortName
        )

END

