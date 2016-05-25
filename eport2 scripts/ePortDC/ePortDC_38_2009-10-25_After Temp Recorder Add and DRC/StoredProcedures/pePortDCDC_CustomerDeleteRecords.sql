if exists (select * from sysobjects where id = object_id(N'pePortDCDC_CustomerDeleteRecords') and sysstat & 0xf = 4) drop procedure pePortDCDC_CustomerDeleteRecords 
GO

-- Deletes a set of rows from the [dbo].[DC_Customer] table
-- that match the specified search criteria.
-- Returns the number of rows deleted as an output parameter.
CREATE PROCEDURE pePortDCDC_CustomerDeleteRecords
        @p_where_str nvarchar(4000),
        @p_num_deleted int OUTPUT
AS
DECLARE
    @l_where_str nvarchar(4000),
    @l_query_str nvarchar(4000)
BEGIN

    -- Initialize the where string
    SET @l_where_str = ' '
    IF @p_where_str IS NOT NULL
        SET @l_where_str = ' WHERE ' + @p_where_str;

    SET @p_num_deleted = 0;

    -- Set up the query string
    SET @l_query_str =
        'DELETE [dbo].[DC_Customer] ' +
        'FROM [dbo].[DC_Customer] DC_Customer_' +
        @l_where_str + ' ';

    -- Run the query
    EXECUTE (@l_query_str)

    -- Return the number of rows affected to the output parameter
    SELECT @p_num_deleted = @@ROWCOUNT

END

