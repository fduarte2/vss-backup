if exists (select * from sysobjects where id = object_id(N'pePortDCDC_OrderExport') and sysstat & 0xf = 4) drop procedure pePortDCDC_OrderExport 
GO

-- Returns the query result set in a CSV format
-- so that the data can be exported to a CSV file
CREATE PROCEDURE pePortDCDC_OrderExport
        @p_separator_str nvarchar(15),
        @p_title_str nvarchar(4000),
        @p_select_str nvarchar(4000),
        @p_join_str nvarchar(4000),
        @p_where_str nvarchar(4000),
        @p_num_exported int output
    AS
    DECLARE
        @l_title_str nvarchar(4000),
        @l_select_str1 nvarchar(4000),
        @l_select_str2 nvarchar(4000),
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
                N'"Comments"' + @p_separator_str +
                N'"CommentsCancel"' + @p_separator_str +
                N'"CommodityCode"' + @p_separator_str +
                N'"""CommodityCode"" CommodityName"' + @p_separator_str +
                N'"ConsigneeId"' + @p_separator_str +
                N'"""ConsigneeId"" ConsigneeName"' + @p_separator_str +
                N'"CustomerId"' + @p_separator_str +
                N'"""CustomerId"" CustomerName"' + @p_separator_str +
                N'"CustomerPO"' + @p_separator_str +
                N'"DeliveryDate"' + @p_separator_str +
                N'"DirectOrder"' + @p_separator_str +
                N'"DriverCheckInDateTime"' + @p_separator_str +
                N'"DriverCheckOutDateTime"' + @p_separator_str +
                N'"DriverName"' + @p_separator_str +
                N'"LastUpdateUser"' + @p_separator_str +
                N'"LastUpdateDateTime"' + @p_separator_str +
                N'"LoadType"' + @p_separator_str +
                N'"OrderStatusId"' + @p_separator_str +
                N'"""OrderStatusId"" Descr"' + @p_separator_str +
                N'"PARSBarCode"' + @p_separator_str +
                N'"PARSCarrierDispatchPhone"' + @p_separator_str +
                N'"PARSDriverPhoneMobile"' + @p_separator_str +
                N'"PARSETABorder"' + @p_separator_str +
                N'"PARSPortOfEntryNum"' + @p_separator_str +
                N'"""PARSPortOfEntryNum"" PortName"' + @p_separator_str +
                N'"PickUpDate"' + @p_separator_str +
                N'"SealNum"' + @p_separator_str +
                N'"SNMGNum"' + @p_separator_str +
                N'"TEStatus"' + @p_separator_str +
                N'"TotalBoxDamaged"' + @p_separator_str +
                N'"TotalCount"' + @p_separator_str +
                N'"TotalPalletCount"' + @p_separator_str +
                N'"TotalPrice"' + @p_separator_str +
                N'"TotalQuantityKG"' + @p_separator_str +
                N'"TransportCharges"' + @p_separator_str +
                N'"TransporterId"' + @p_separator_str +
                N'"""TransporterId"" CarrierName"' + @p_separator_str +
                N'"TrailerNum"' + @p_separator_str +
                N'"TruckTag"' + @p_separator_str +
                N'"VesselId"' + @p_separator_str +
                N'"""VesselId"" VesselName"' + @p_separator_str +
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
        SET @l_select_str1 = @p_select_str
        SET @l_select_str2 = @p_select_str
        IF @p_select_str IS NULL
            BEGIN
            SET @l_select_str1 = 
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[OrderNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[Comments], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[CommentsCancel], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[CommodityCode]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t0.[CommodityName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[ConsigneeId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t1.[ConsigneeName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[CustomerId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t2.[CustomerName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[CustomerPO], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[DeliveryDate], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[DirectOrder], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[DriverCheckInDateTime], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[DriverCheckOutDateTime], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[DriverName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[LastUpdateUser], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[LastUpdateDateTime], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[LoadType], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[OrderStatusId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t3.[Descr], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[PARSBarCode], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[PARSCarrierDispatchPhone], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[PARSDriverPhoneMobile], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[PARSETABorder], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[PARSPortOfEntryNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t4.[PortName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[PickUpDate], 21), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[SealNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[SNMGNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[TEStatus], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' 
            SET @l_select_str2 = 
                N'IsNULL(Convert(nvarchar, DC_Order_.[TotalBoxDamaged]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TotalCount]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TotalPalletCount]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TotalPrice]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TotalQuantityKG]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TransportCharges]), '''') + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[TransporterId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t5.[CarrierName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[TrailerNum], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[TruckTag], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'IsNULL(Convert(nvarchar, DC_Order_.[VesselId]), '''') + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL( t6.[VesselName], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[ZUser1], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[ZUser2], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[ZUser3], ''''), N''"'', N''""'') + N''"''  + ''' + @p_separator_str + ''' +' +
                N'N''"'' + REPLACE(IsNULL(DC_Order_.[ZUser4], ''''), N''"'', N''""'') + N''"''  + '' ''';
            END

        -- Set up the from string (with table alias) and the join string
        SET @l_from_str = '[dbo].[DC_Order] DC_Order_ LEFT OUTER JOIN [dbo].[DC_Commodity] t0 ON (DC_Order_.[CommodityCode] =  t0.[CommodityCode]) LEFT OUTER JOIN [dbo].[DC_Consignee] t1 ON (DC_Order_.[ConsigneeId] =  t1.[ConsigneeId]) LEFT OUTER JOIN [dbo].[DC_Customer] t2 ON (DC_Order_.[CustomerId] =  t2.[CustomerId]) LEFT OUTER JOIN [dbo].[DC_OrderStatus] t3 ON (DC_Order_.[OrderStatusId] =  t3.[OrderStatusId]) LEFT OUTER JOIN [dbo].[DC_PortOfEntry] t4 ON (DC_Order_.[PARSPortOfEntryNum] =  t4.[PortCode]) LEFT OUTER JOIN [dbo].[DC_Transporter] t5 ON (DC_Order_.[TransporterId] =  t5.[TransporterId]) LEFT OUTER JOIN [dbo].[DC_Vessel] t6 ON (DC_Order_.[VesselId] =  t6.[VesselId])';

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
        EXECUTE (@l_query_select + @l_title_str + @l_query_union + @l_select_str1 + @l_select_str2+ @l_query_from)

        -- Return the total number of rows of the query
        SELECT @p_num_exported = @@rowcount
    END

