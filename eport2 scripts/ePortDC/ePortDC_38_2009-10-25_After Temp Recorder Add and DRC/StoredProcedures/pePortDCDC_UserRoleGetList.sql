if exists (select * from sysobjects where id = object_id(N'pePortDCDC_UserRoleGetList') and sysstat & 0xf = 4) drop procedure pePortDCDC_UserRoleGetList 
GO

-- Returns a query resultset from table [dbo].[DC_UserRole]
-- given the search criteria and sorting condition.
-- It will return a subset of the data based
-- on the current page number and batch size.  Table joins can
-- be performed if the join clause is specified.
-- 
-- If the resultset is not empty, it will return:
--    1) The total number of rows which match the condition;
--    2) The resultset in the current page
-- If nothing matches the search condition, it will return:
--    1) count is 0 ;
--    2) empty resultset.
CREATE PROCEDURE pePortDCDC_UserRoleGetList
        @p_join_str nvarchar(4000),
        @p_where_str nvarchar(4000),
        @p_sort_str nvarchar(4000),
        @p_page_number int,
        @p_batch_size int
AS
DECLARE
    @l_temp_insert nvarchar(4000),
    @l_temp_select nvarchar(4000),
    @l_temp_from nvarchar(4000),
    @l_final_sort nvarchar(4000),
    @l_temp_cols nvarchar(4000),
    @l_temp_colsWithAlias nvarchar(4000),
    @l_query_select nvarchar(4000),
    @l_query_from nvarchar(4000),
    @l_query_where nvarchar(4000),
    @l_query_cols nvarchar(4000),
    @l_from_str nvarchar(4000),
    @l_join_str nvarchar(4000),
    @l_sort_str nvarchar(4000),
    @l_where_str nvarchar(4000),
    @l_count_query nvarchar(4000),
    @l_end_gen_row_num integer,
    @l_start_gen_row_num integer
BEGIN
    -- Set up the from string as the base table.
    SET @l_from_str = '[dbo].[DC_UserRole] DC_UserRole_'

    -- Set up the join string.
    SET @l_join_str = @p_join_str
    IF @p_join_str is null
        SET @l_join_str = ' '

    -- Set up the where string.
    SET @l_where_str = ' '
        IF @p_where_str is not null
        SET @l_where_str = 'WHERE ' + @p_where_str

    -- Get the total count of rows the query will return
    IF @p_page_number > 0 and @p_batch_size >= 0
    BEGIN
        SET @l_count_query = 
            'SELECT count(*) ' +
            'FROM ' + @l_from_str + ' ' + @l_join_str + ' ' +
            @l_where_str + ' '

        -- Run the count query
        EXECUTE (@l_count_query)
    END

    -- Get the list.
    IF @p_page_number > 0 AND @p_batch_size > 0
    BEGIN
        -- If the caller did not pass a sort string, use a default value
        IF @p_sort_str IS NOT NULL
            SET @l_sort_str = 'ORDER BY ' + @p_sort_str
        ELSE
            SET @l_sort_str = N'ORDER BY DC_UserRole_.[UserId],DC_UserRole_.[RoleId] asc '

        -- Calculate the rows to be included in the list
        -- before geting the list.
        SET @l_end_gen_row_num = @p_page_number * @p_batch_size;
        SET @l_start_gen_row_num = @l_end_gen_row_num - (@p_batch_size-1);

        -- Create a temporary table to keep the numbering
        -- of the rows returned.  It contains the necessary colums
        -- from base table plus an identity field used for numbering

        SELECT 1 AS IS_SECONDARY_TEMP_T_GETLIST_COL INTO #IS_SECONDARY_TEMP_T_GETLIST
        SELECT Identity(int,1,1) AS IS_ROWNUM_COL,
            [UserId],[RoleId]
         INTO #IS_TEMP_T_GETLIST 
         FROM [dbo].[DC_UserRole], #IS_SECONDARY_TEMP_T_GETLIST
             WHERE 1=2 

        -- Now copy column data into temporary table.
        SET @l_temp_insert = 
            'INSERT INTO #IS_TEMP_T_GETLIST ('
        SET @l_temp_cols = 
            N'[UserId],[RoleId]'
        SET @l_temp_select = 
            ') ' + 
            'SELECT ' + 
            'TOP ' + convert(varchar, @l_end_gen_row_num) + ' '
        SET @l_temp_colsWithAlias = 
            N'DC_UserRole_.[UserId],DC_UserRole_.[RoleId]'
        SET @l_temp_from = 
            ' FROM ' + @l_from_str + ' ' + @l_join_str + ' ' + 
            @l_where_str + ' ' + 
            @l_sort_str

        EXECUTE (@l_temp_insert + @l_temp_cols + @l_temp_select + @l_temp_colsWithAlias + @l_temp_from)

        -- Construct the main query
        SET @l_query_select = 'SELECT '
        SET @l_query_cols = 
            N'DC_UserRole_.[UserId],
            DC_UserRole_.[RoleId],
            CAST(BINARY_CHECKSUM(DC_UserRole_.[UserId],DC_UserRole_.[RoleId]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345 '
        SET @l_query_from = 
            'FROM ( ' +
                N'SELECT TOP 100 PERCENT IS_ROWNUM_COL, [UserId],[RoleId] from #IS_TEMP_T_GETLIST ' +
                'WHERE IS_ROWNUM_COL >= '+ convert(varchar, @l_start_gen_row_num) + 
                ') IS_ALIAS, ' +
                @l_from_str + ' ';

        SET @l_query_where = 
            N'WHERE DC_UserRole_.[UserId] = IS_ALIAS.[UserId] AND DC_UserRole_.[RoleId] = IS_ALIAS.[RoleId] ' 

        SET @l_final_sort = 'ORDER BY IS_ROWNUM_COL Asc '

        -- Run the query
        EXECUTE (@l_query_select + @l_query_cols + @l_query_from + @l_query_where + @l_final_sort)

    END
    ELSE
    BEGIN
        -- If page number and batch size are not valid numbers
        -- return an empty result set
        SET @l_query_select = 'SELECT '
        SET @l_query_cols = 
            N'DC_UserRole_.[UserId],
            DC_UserRole_.[RoleId],
            CAST(BINARY_CHECKSUM(DC_UserRole_.[UserId],DC_UserRole_.[RoleId]) AS nvarchar(4000)) AS IS_CHECKSUM_COLUMN_12345'
        SET @l_query_from = 
            ' FROM [dbo].[DC_UserRole] DC_UserRole_ ' + 
            'WHERE 1=2;'
        EXECUTE (@l_query_select + @l_query_cols + @l_query_from);
    END

END

