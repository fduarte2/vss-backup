if exists (select * from sysobjects where id = object_id(N'pePortDCDC_TransporterExport') and sysstat & 0xf = 4) drop procedure pePortDCDC_TransporterExport 
GO

-- Returns the query result set in a CSV format
-- so that the data can be exported to a CSV file
CREATE PROCEDURE pePortDCDC_TransporterExport
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
                N'"TransporterId"' + @p_separator_str +
                N'"CarrierName"' + @p_separator_str +
                N'"Comments"' + @p_separator_str +
                N'"ContactName"' + @p_separator_str +
                N'"Email"' + @p_separator_str +
                N'"Fax"' + @p_separator_str +
                N'"IRSNum"' + @p_separator_str +
                N'"Phone1"' + @p_separator_str +
                N'"Phone2"' + @p_separator_str +
                N'"PhoneCell1"' + @p_separator_str +
                N'"PhoneCell2"' + @p_separator_str +
                N'"Rate1GTAMiltonWhitby"' + @p_separator_str +
                N'"Rate2Cambridge"' + @p_separator_str +
                N'"Rate3Ottawa"' + @p_separator_str +
                N'"Rate4Montreal"' + @p_separator_str +
                N'"Rate5Quebec"' + @p_separator_str +
                N'"Rate6Moncton"' + @p_separator_str +
                N'"Rate7Debert"' + @p_separator_str +
                N'"Rate8Other"' + @p_separator_str +
                N'"USBondNum"' + ' ';
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
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[TransporterId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[CarrierName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[Comments], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[ContactName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[Email], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[Fax], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[IRSNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[Phone1], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[Phone2], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[PhoneCell1], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[PhoneCell2], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate1GTAMiltonWhitby]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate2Cambridge]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate3Ottawa]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate4Montreal]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate5Quebec]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate6Moncton]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate7Debert]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Transporter_.[Rate8Other]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Transporter_.[USBondNum], ''''), N''"'', N''""'') + N''"''  + '' ''';
            END

        -- Set up the from string (with table alias) and the join string
        SET @l_from_str = '[dbo].[DC_Transporter] DC_Transporter_';

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

