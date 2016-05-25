Attribute VB_Name = "Module1"
Option Explicit
Public Const sMsg As String = "EQUIPMENT BILL"

Global OraSession As Object
Global OraDatabase As Object
Global dsCARGO_TRACKING As Object
Global dsCARGO_ACTIVITY As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsCARGO_ACTIVITY_SUM As Object
Global dsCARGO_ACTIVITY_RETURN As Object
Global dsCARGO_ACTIVITY_ORDERS As Object
Global dsCARGO_ACTIVITY_PALLETS As Object
Global dsCARGO_ACTIVITY_PALLETS_S As Object
Global dsCARGO_ACTIVITY_SUM_S As Object
Global dsPERSONNEL_CHECK As Object
Global dsPERSONNEL  As Object
Global dsSERVICE_CATEGORY As Object
Global dsCARGO_DELIVERY As Object
Global TableName As String
Global CA_TableName As String
Global dsSHORT_TERM_DATA As Object
Global bIsQC As Boolean




Global iRec As Integer
Global iOrderNum As String
Global gsSqlStmt  As String

Global gsPVItem As String

Sub Main()
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        frmTagAudit.Show
    Else
        MsgBox OraDatabase.LastServerErrText, vbCritical, sMsg
        End
    End If
    

End Sub
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
    If Val(sHour) < 0 Or Val(sHour) > 24 Then
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
    
    If Val(sHour) * 60 + Val(sMinute) > 24 * 60 Then
        IsValidTime = False
        Exit Function
    End If
    
    IsValidTime = True
End Function


Sub PopulateSeasons()

Dim FutureDate As Date
Dim LastYear As Integer
Dim i As Integer


    FutureDate = DateAdd("m", 4, Now())
    LastYear = Format(FutureDate, "yyyy")
    

    For i = LastYear To 2005 Step -1
        frmTagAudit.Season.AddItem i
    Next i
    
    frmTagAudit.Season.Text = LastYear

End Sub

Sub PopulateCommodity()
    gsSqlStmt = "SELECT DISTINCT COMMODITY_TYPE FROM COMMODITY_PROFILE WHERE COMMODITY_TYPE IS NOT NULL ORDER BY COMMODITY_TYPE"
    Set dsSHORT_TERM_DATA = OraDatabase.dbcreatedynaset(gsSqlStmt, 0&)
    While Not dsSHORT_TERM_DATA.EOF
        frmTagAudit.cmbCommodity.AddItem dsSHORT_TERM_DATA.Fields("COMMODITY_TYPE").Value
        dsSHORT_TERM_DATA.MoveNext
    Wend
End Sub

