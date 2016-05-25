Attribute VB_Name = "Module1"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsPreInv As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsBILLING As Object
Global dsLEASE As Object
Global dsLEASE_HISTORY As Object
Global dsCOMMODITY_PROFILE As Object
Global dsBILLING_MAX As Object

Global StartInvNum As Long


Dim SqlStmt As String


Public Sub Main()
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

    If OraDatabase.LastServerErr = 0 Then
        frmLeaseData.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
    End If
End Sub
Public Sub SubPreInv()
        
    SqlStmt = "SELECT * FROM PreInvoice"
    
    Set dsPreInv = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    If dsPreInv.recordcount <> 0 Then
        OraDatabase.DbExecuteSQL ("DELETE FROM PreInvoice")
    End If

End Sub

Public Function fnCountryName(Country_Code As String) As String
'Get from Customer table based on Customer Code
    
    Dim dsCountry As Object
        
    SqlStmt = "SELECT * FROM COUNTRY WHERE COUNTRY_CODE =" _
                & "'" & Country_Code & "'"
    Set dsCountry = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsCountry.recordcount > 0 Then
        If Not IsNull(dsCountry.fields("Country_Name").Value) Then
            fnCountryName = dsCountry.fields("Country_Name").Value
        Else
            fnCountryName = ""
        End If
    Else
        fnCountryName = ""
    End If

    
End Function

Public Function fnSerName(SerCode As Integer) As String
    Dim dsService_Category As Object
        
    SqlStmt = "SELECT * FROM SERVICE_CATEGORY WHERE SERVICE_CODE = " _
             & "'" & SerCode & "'"
    Set dsService_Category = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsService_Category.recordcount > 0 Then
        If Not IsNull(dsService_Category.fields("SERVICE_NAME").Value) Then
            fnSerName = dsService_Category.fields("SERVICE_NAME").Value
        Else
            fnSerName = ""
        End If
    Else
        fnSerName = ""
    End If
End Function
Public Function fnMaxInvNum() As Long
    
    SqlStmt = "SELECT MAX(INVOICE_NUM) MAX_NUM FROM BILLING"
    
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsBILLING_MAX.recordcount > 0 Then
        fnMaxInvNum = dsBILLING_MAX.fields("MAX_NUM").Value + 1
        
        If StartInvNum = 0 Then
            StartInvNum = dsBILLING_MAX.fields("MAX_NUM").Value + 1
        End If
    Else
        fnMaxInvNum = 1
    End If
End Function
Public Function fnVesselName(LrNum As Integer) As String
        
    SqlStmt = "SELECT Vessel_Name FROM Vessel_Profile where lr_num=" & LrNum
    
    Set dsVESSEL_PROFILE = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsVESSEL_PROFILE.recordcount > 0 Then
        If Not IsNull(dsVESSEL_PROFILE.fields("VESSEL_NAME").Value) Then
            fnVesselName = dsVESSEL_PROFILE.fields("VESSEL_NAME").Value
        Else
            fnVesselName = ""
        End If
    Else
        fnVesselName = ""
    End If
End Function
Public Function fnMaxBillNum() As Long
    
    SqlStmt = "SELECT MAX(BILLING_NUM) MAX_NUM FROM BILLING"
    
    Set dsBILLING_MAX = OraDatabase.dbcreatedynaset(SqlStmt, 0&)
    
    If dsBILLING_MAX.recordcount > 0 Then
        fnMaxBillNum = dsBILLING_MAX.fields("MAX_NUM").Value + 1
    Else
        fnMaxBillNum = 1
    End If
End Function


