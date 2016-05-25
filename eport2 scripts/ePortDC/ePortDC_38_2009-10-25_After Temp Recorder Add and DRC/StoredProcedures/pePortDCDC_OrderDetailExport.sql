if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderDetailExport') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderDetailExport 
GO

-- Returns the query result set in a CSV format
-- so that the data can be exported to a CSV file
CREATE PROCEDURE pePortDCDC_OrderDetailExport
        @p_separator_str nvarchar(15),
        @p_title_str nvarchar(4000),
        @p_select_str nvarchar(4000),
        @p_join_str nvarchar(4000),
        @p_where_str nvarchar(4000),
        @p_num_exported int output
    AS
    DECLARE
        @l_title_str nvarchar(4000),
        @l_select_str nvarchar(4000),
        @l_from_str nvarchar(4000),
        @l_join_str nvarchar(4000),
        @l_where_str nvarchar(4000),
        @l_query_select nvarchar(4000),
        @l_query_union nvarchar(4000),
        @l_query_from nvarchar(4000)
    BEGIN
        -- Set up the title string from the column names.  Excel 
        -- will complain if the first column value is ID. So wrap
        -- the value with "".
        SET @l_title_str = @p_title_str + char(13)
        IF @p_title_str IS NULL
            BEGIN
            SET @l_title_str = 
                N'"OrderNum"' + @p_separator_str +
                N'"""OrderNum"" DriverName"' + @p_separator_str +
                N'"OrderDetailId"' + @p_separator_str +
                N'"Comments"' + @p_separator_str +
                N'"DeliveredQty"' + @p_separator_str +
                N'"OrderQty"' + @p_separator_str +
                N'"OrderSizeId"' + @p_separator_str +
                N'"""OrderSizeId"" Descr"' + @p_separator_str +
                N'"Price"' + @p_separator_str +
                N'"SizeHigh"' + @p_separator_str +
                N'"SizeLow"' + @p_separator_str +
                N'"WeightKG"' + @p_separator_str +
                N'"ZUser1"' + @p_separator_str +
                N'"ZUser2"' + @p_separator_str +
                N'"ZUser3"' + @p_separator_str +
                N'"ZUser4"' + ' ';
            END
        ELSE IF SUBSTRING(@l_title_str, 1, 2) = 'ID'
            SET @l_title_str = 
                '"' + 
                SUBSTRING(@l_title_str, 1, PATINDEX('%,%', @l_title_str)-1) + 
                '"' + 
                SUBSTRING(@l_title_str, PATINDEX('%,%', @l_title_str), LEN(@l_title_str)); 

        -- Set up the select string
        SET @l_select_str = @p_select_str
        IF @p_select_str IS NULL
            BEGIN
            SET @l_select_str = 
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[OrderNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t0.[DriverName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[OrderDetailId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[Comments], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[DeliveredQty]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[OrderQty]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[OrderSizeId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t1.[Descr], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[Price]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[SizeHigh]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[SizeLow]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_OrderDetail_.[WeightKG]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[ZUser1], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[ZUser2], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[ZUser3], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_OrderDetail_.[ZUser4], ''''), N''"'', N''""'') + N''"''  + '' ''';
            END

        -- Set up the from string (with table alias) and the join string
        SET @l_from_str = '[dbo].[DC_OrderDetail] DC_OrderDetail_ LEFT OUTER JOIN [dbo].[DC_Order] t0 ON (DC_OrderDetail_.[OrderNum] =  t0.[OrderNum]) LEFT OUTER JOIN [dbo].[DC_CommoditySize] t1 ON (DC_OrderDetail_.[OrderSizeId] =  t1.[SizeId])';

        SET @l_join_str = @p_join_str
        if @p_join_str is null
            SET @l_join_str = ' ';

        -- Set up the where string
        SET @l_where_str = ' ';
        IF @p_where_str IS NOT NULL
            SET @l_where_str = @l_where_str + 'WHERE ' + @p_where_str;

        -- Construct the query string.  Append the result set with the title.
        SET @l_query_select = 
                'SELECT '''
        SET @l_query_union = 
                ''' UNION ALL ' +
                'SELECT '
        SET @l_query_from = 
                ' FROM ' + @l_from_str + ' ' + @l_join_str + ' ' +
                @l_where_str;

        -- Run the query
        EXECUTE (@l_query_select + @l_title_str + @l_query_union + @l_select_str+ @l_query_from)

        -- Return the total number of rows of the query
        SELECT @p_num_exported = @@rowcount
    END

