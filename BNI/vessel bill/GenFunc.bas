Attribute VB_Name = "modGenFunc"

Public Function IsValidTime(asTime As String) As Integer

    Dim sHour As String
    Dim sMinute As String
    
    If Mid$(asTime, 3, 1) <> ":" Then
        IsValidTime = False
        Exit Function
    End If
    
    sHour = Left$(asTime, 2)
    If Trim$(sHour) = "" Then
        IsValidTime = False
        Exit Function
    End If
    If Not IsNumeric(sHour) Then
        IsValidTime = False
        Exit Function
    End If
    If Len(Trim$(sHour)) <> 2 Then
        IsValidTime = False
        Exit Function
    End If
    If Val(sHour) < 0 Or Val(sHour) >= 24 Then
        IsValidTime = False
        Exit Function
    End If
    
    sMinute = Right$(asTime, 2)
    If Trim$(sMinute) = "" Then
        IsValidTime = False
        Exit Function
    End If
    If Not IsNumeric(sMinute) Then
        IsValidTime = False
        Exit Function
    End If
    If Len(Trim$(sMinute)) <> 2 Then
        IsValidTime = False
        Exit Function
    End If
    If sMinute < 0 Or sMinute >= 60 Then
        IsValidTime = False
        Exit Function
    End If
    
    If Val(sHour) * 60 + Val(sMinute) >= 24 * 60 Then
        IsValidTime = False
        Exit Function
    End If
    
    IsValidTime = True
    
End Function
Function fnMaxRowNum() As Long

    Dim dsMaxRow As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT MAX(ROW_NUM) MaxNum FROM BILLING_CORRECTION "
    Set dsMaxRow = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And dsMaxRow.RecordCount > 0 Then
        If dsMaxRow.fields("MaxNum").Value > 0 Then
            fnMaxRowNum = dsMaxRow.fields("MAXNUM").Value + 1
        Else
            fnMaxRowNum = 1
        End If
    End If

End Function
Public Function fnSerName(SerCode As Integer) As String

    Dim dsSERVICE_CATEGORY As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " _
             & "'" & SerCode & "'"
    Set dsSERVICE_CATEGORY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsSERVICE_CATEGORY.RecordCount > 0 Then
        If Not IsNull(dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value) Then
            fnSerName = dsSERVICE_CATEGORY.fields("SERVICE_NAME").Value
        Else
            fnSerName = ""
        End If
    Else
        fnSerName = ""
    End If
    
End Function
Public Function fnMaxInvNum() As Long

    Dim SqlStmt As String
    Dim dsBILLING_MAX As Object
    
    SqlStmt = "SELECT MAX(INVOICE_NUM) MAX_NUM FROM BILLING"
    
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsBILLING_MAX.RecordCount > 0 Then
        fnMaxInvNum = dsBILLING_MAX.fields("MAX_NUM").Value + 1
    Else
        fnMaxInvNum = 1
    End If
    
End Function
Public Function fnVesselName(LrNum As Integer) As String

    Dim SqlStmt As String
    Dim dsVESSEL_PROFILE As Object
    
    SqlStmt = "SELECT Vessel_Name FROM Vessel_Profile where lr_num=" & LrNum
    
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsVESSEL_PROFILE.RecordCount > 0 Then
        If Not IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) Then
            fnVesselName = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            fnVesselName = ""
        End If
    Else
        fnVesselName = ""
    End If
    
End Function
Public Sub SubPreInv()
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM PreInvoice"
    
    Set dsPreInv = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If dsPreInv.RecordCount <> 0 Then
'        raSession.begintrans
        OraDatabase.DbExecuteSQL ("DELETE FROM PreInvoice")
'        OraSession.committrans
    End If

End Sub
Public Sub AddNewInvDt(sType As String, sStBillNo As String, sEdBillNo As String)
    Dim SqlStmt As String
    Dim DtID As Long
    
    SqlStmt = "SELECT MAX(ID) MAXID FROM INVOICEDATE"
    Set dsID = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If dsID.RecordCount > 0 Then
        If Not IsNull(dsID.fields("MAXID").Value) Then
            DtID = dsID.fields("MAXID").Value + 1
        Else
            DtID = 0
        End If
    Else
        DtID = 0
    End If
    
    SqlStmt = "SELECT * FROM INVOICEDATE"
    Set dsINVDATE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        With dsINVDATE
            .AddNew
            .fields("ID").Value = DtID
            .fields("RUN_DATE").Value = Format(Now, "MM/DD/YYYY HH:MM:SS")
            .fields("BILL_TYPE").Value = "B"
            .fields("TYPE").Value = sType
            .fields("START_INV_NO").Value = sStBillNo
            .fields("END_INV_NO").Value = sEdBillNo
            .Update
        End With
    Else
        MsgBox OraSession.LastServerErrText, vbInformation, "BILLING"
    End If
End Sub

Public Function fnCountryName(Country_Code As String) As String
'Get from Customer table based on Customer Code
    
    Dim dsCOUNTRY As Object
    Dim SqlStmt As String
    
    SqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE =" _
                & "'" & Country_Code & "'"
    Set dsCOUNTRY = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsCOUNTRY.RecordCount > 0 Then
        If Not IsNull(dsCOUNTRY.fields("Country_Name").Value) Then
            fnCountryName = dsCOUNTRY.fields("Country_Name").Value
        Else
            fnCountryName = ""
        End If
    Else
        fnCountryName = ""
    End If

End Function
