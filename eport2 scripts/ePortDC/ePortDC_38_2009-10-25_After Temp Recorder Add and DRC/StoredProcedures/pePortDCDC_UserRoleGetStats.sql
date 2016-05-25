if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserRoleGetStats') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserRoleGetStats 
GO

-- Runs a SQL function against a column
-- and returns the result back giving the current 
-- page number and batch size.  SQL functions can include 
-- sum, avg, max, etc
CREATE PROCEDURE pePortDCDC_UserRoleGetStats
        @p_select_str nvarchar(4000),
        @p_join_str nvarchar(4000),
        @p_where_str nvarchar(4000),
        @p_sort_str nvarchar(4000),
        @p_page_number integer,
        @p_batch_size integer
    AS
    DECLARE
        @l_query nvarchar(4000),
        @l_from_str nvarchar(4000),
        @l_join_str nvarchar(4000),
        @l_sort_str nvarchar(4000),
        @l_where_str nvarchar(4000),
        @l_count_query nvarchar(4000),
        @l_end_gen_row_num integer,
        @l_start_gen_row_num integer,
        @l_select_str nvarchar(4000),
        @l_stat_col nvarchar(4000),
        @l_insert_to_temp nvarchar(4000),
        @l_create_temp nvarchar(4000),
        @l_stat_col_alias nvarchar(20)
    BEGIN

        -- Extract the col only that we need to run statistics on.
        -- First extract the content in the function call.
        SET @l_stat_col = @p_select_str
        SET @l_stat_col = SUBSTRING(@l_stat_col, 
                    PATINDEX('%(%', @l_stat_col) + 1,
                    PATINDEX('%)%', @l_stat_col) - PATINDEX('%(%', @l_stat_col) - 1)

        -- Then extract the column from the distinct clause.
        SET @l_stat_col = LTRIM(RTRIM(@l_stat_col))
        IF PATINDEX('%DISTINCT %', UPPER(@l_stat_col)) = 1
            SET @l_stat_col = SUBSTRING(@l_stat_col, PATINDEX('% %', @l_stat_col) + 1, LEN(@l_stat_col))

        -- Get the select column name without alias.
        SET @l_select_str = SUBSTRING(@l_stat_col, PATINDEX('%.%', @l_stat_col) + 1, LEN(@l_stat_col))

        -- Set up the from string.
        SET @l_from_str = '[dbo].[DC_UserRole] DC_UserRole_'

        -- Set up the join string.
        SET @l_join_str = @p_join_str
        IF @p_join_str IS NULL
            SET @l_join_str = ' '

        -- Set up the search string.
        SET @l_where_str = ' '
        IF @p_where_str IS NOT NULL
            SET @l_where_str = @l_where_str + 'WHERE ' + @p_where_str

        -- Get the list.
        IF @p_page_number > 0 AND @p_batch_size > 0
        BEGIN
            -- If the caller did not pass a sort string,
            -- use a default value.
            IF @p_sort_str IS NOT NULL
                SET @l_sort_str = 'ORDER BY ' + @p_sort_str;
            ELSE
                SET @l_sort_str = N'ORDER BY DC_UserRole_.[UserId],DC_UserRole_.[RoleId] asc '

            -- Calculate the rows to be included in the list
            -- before geting the list.
            SET @l_end_gen_row_num = @p_page_number * @p_batch_size
            SET @l_start_gen_row_num = @l_end_gen_row_num - (@p_batch_size-1)

            -- Creating a temporary table to keep the numbering
            -- of the rows returned.  It keeps the same primary
            -- key columns as the base table besides an identity
            -- field which keeps the numbering.
            Select 1 As tempCol Into #IS_TEMP_TABLE_SECONDARY;

            -- Create the temporary table from the
            SET @l_create_temp = 
                'SELECT Identity(int, 1, 1) As IS_ROWNUM_COL, ' + 
                @l_stat_col + 
                ' INTO #IS_TEMP_FROM' + 
                ' FROM ' + @l_from_str + ' ' + 
                ' CROSS JOIN #IS_TEMP_TABLE_SECONDARY' + 
                ' WHERE 1=2'
            -- Insert records into the temporary table from the
            -- base table
            SET @l_insert_to_temp = 
                'INSERT INTO #IS_TEMP_FROM ' + 
                '(' + @l_select_str + ') ' + 
                'SELECT ' + @l_stat_col + 
                ' FROM ' + @l_from_str + ' ' + @l_join_str + ' ' + 
                @l_where_str + ' ' + 
                @l_sort_str

            -- Construct the query for the current page
            SET @l_query = 
                'SELECT ' + @p_select_str + ' ' +
                'FROM ( ' +
                'SELECT ' + @l_select_str + ', IS_ROWNUM_COL ' +
                'FROM #IS_TEMP_FROM ' +
                'WHERE IS_ROWNUM_COL >= ' + convert(varchar, @l_start_gen_row_num) +
                ' AND IS_ROWNUM_COL <= ' + convert(varchar, @l_end_gen_row_num) +
                ') ' + 'DC_UserRole_'

            -- Run the query and get the result for the current page
            EXECUTE (@l_create_temp + '     ' + @l_insert_to_temp + '     ' + @l_query + ' ');

        END
        ELSE
        -- Return the empty result if page number or batch size
        -- has invalid number
        BEGIN
            SET @l_query = 
                'SELECT count(*) from ' + '[dbo].[DC_UserRole] DC_UserRole_ ' +
                'WHERE 1=2;'
            EXECUTE (@l_query)
        END
    END

