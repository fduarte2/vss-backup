Attribute VB_Name = "Module1"
Option Explicit
Public Const sMsg As String = "LABOR BILLING"
Global OraSession As Object
Global OraDatabase As Object
Global OraDatabase2 As Object
Global dsBILLING As Object
Global dsLABOR_CATEGORY As Object
Global dsLABOR_RATE_TYPE As Object
Global dsVESSEL_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsCOMMODITY_PROFILE As Object
Global dsHOURLY_DETAIL_DISTINCT As Object
Global dsHOURLY_DETAIL As Object
Global dsHOURLY_DETAIL_TIME As Object
Global dsSERVICE_LABOR_RATE_TYPE As Object
Global dsCHECK_SUPER As Object
Global dsLABOR_RATE As Object
Global dsBILLING_MAX As Object
Global gsPVItem As String
Global OraFinApp As Object
Global rsPROD As Object
Global dsBILLING_RETRIEVE_DATA As Object
Global dsSHORT_TERM_DATA As Object
Global SaveCheckForPrint As Boolean


Sub Main()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    Set OraDatabase2 = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
'    Set OraFinApp = OraSession.opendatabase("PROD", "APPS/APPS", 0&)
    
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
'    Set OraDatabase2 = OraSession.OpenDatabase("BNITEST", "LABOR/LABOR_DEV", 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        frmLaborBill.Show
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


