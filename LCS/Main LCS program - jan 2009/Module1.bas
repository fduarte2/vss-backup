Attribute VB_Name = "Module1"
Option Explicit

Global UserID As String
Global HireRole As String

' used in SFHourlyDetail cmdReport_Click() function
Global TimeSheetSelection As String
Global TimeSheetOrderBy As String

Global TotalSupRec As Integer
Global MaintFlag As String
Global EmpEditRS As Object
Global dsHIRE_SUPERVISOR As Object
Global dsSUPERVISOR As Object
Global arrFirstName() As String
Global arrEmployeeID() As String
Global arrTypeID() As String
Global maxarr As Integer
Global maxarrrec As Integer
Global arrEmplID() As String
Global ShowMeNot As Boolean
Global MasterName As String
Global ReportTitle As String
Global AlreadyInSolomon As Object
Global OraSession As Object
Global OraDatabase As Object

Global HideDblEntry As Boolean, OverlapEntry As Boolean
Global GroupID As String
Global ClosedDate As Date
Global LineRun As Boolean
Global OraSessionBNI As Object
Global OraDatabaseBNI As Object
'***********************************
Global dsBILLING As Object
Global dsBILLING_MAX As Object
Global dsHOURLY_DETAIL As Object
Global dsCUSTOMER_COUNT As Object
Global dsHOURLY_DETAIL_TOTAL As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsEQUIPMENT_CATEGORY As Object
Global dsEQUIPMENT_RATE As Object
Global dsEQUIPMENT_RATE_TYPE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_PROFILE As Object
Global dsLCS_USER As Object
Global dsHOURLY_DETAIL_TIME As Object
Global dsHOURLY_DETAIL_SERVICE As Object
Global dsHOURLY_DETAIL_SUPERVISOR As Object
Global dsHOURLY_DETAIL_SUPER_TIME As Object
Global dsSHORT_TERM_DATA As Object
Global gsPVItem As String
Global gsReason As String

Global dsService As Object
    
'5/2/2007 HD2759 Rudy: added these 4
Global DB As String
Global DBLCS As String
Global Login As String
Global LoginLCS As String


Function GetValue(DataValue As Variant, Default As Variant) As Variant
  If IsNull(DataValue) Then
    GetValue = Default
  Else
    GetValue = DataValue
  End If
End Function

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

Sub MouseHourlyGlass()
  Screen.MousePointer = vbHourglass
End Sub

Sub MouseNormal()
  Screen.MousePointer = vbDefault
End Sub

'called from 1 place Form Load
Function isGuardSupervisor(supervisorID As String) As Boolean
    ' HD 8266 2/15/13
    ' HD not to be closed until this fuction is table driven
    Select Case supervisorID
        Case "E408070"      'Gerardo Hernandez
            isGuardSupervisor = True
        Case "E408880"      'Jerry Custis
            isGuardSupervisor = True
        Case Else
            isGuardSupervisor = False
    End Select
End Function

'called from 8 places
Function timeKeeperPrivilege() As Boolean
    '           TIMEKEEPER S1         Tasmania Haskins S6
    If UserID = "E407612" Or UserID = "E006814" Or UserID = "E405833" Or UserID = "E405484" Or UserID = "E408488" Then
        timeKeeperPrivilege = True
    Else
        timeKeeperPrivilege = False
    End If
End Function

Function findLastSunday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findLastSunday = day
    Else
        findLastSunday = day - (weekofday - 1)
    End If
End Function

'5/2/2007 HD2759 Rudy: added sub
Sub Init()
    
    DB = "BNI"
    Login = "SAG_OWNER/SAG"
    DBLCS = "LCS"
    LoginLCS = "LABOR/LABOR"

'    DB = "BNITEST"
'    Login = "SAG_OWNER/BNITEST238"
'    DBLCS = "BNITEST"
'    LoginLCS = "LABOR/LABOR_DEV"

'    LoginLCS = "LABOR/LABOR"

End Sub

'5/2/2007 HD2759 Rudy: added func
Public Function CheckSvcCommAllowZero(SvcCode As String) As Boolean
Dim dsService As Object
Dim strSQL As String
Dim OraDatabaseSvc As Object

'Dim OraDatabase2 As Object
    CheckSvcCommAllowZero = False   'init
    
    Set OraDatabaseSvc = OraSession.OpenDatabase(DBLCS, LoginLCS, 0&)  '5/2/2007 HD2759 Rudy:  one init  TESTED /

    strSQL = "SELECT * FROM LABOR.SERVICE WHERE SERVICE_CODE='" & SvcCode & "'"
    'Set dsService = OraDatabase2.CreateDynaset(strSQL, 0&)
    Set dsService = OraDatabaseSvc.CreateDynaset(strSQL, 0&)
    If dsService.RecordCount > 0 Then
      If dsService.Fields.count > 5 Then
        If dsService.Fields("COMMODITY_REQUIRED").Value = "N" Then
          CheckSvcCommAllowZero = True
        End If
      End If
    End If

    dsService.Close
    Set dsService = Nothing
    OraDatabaseSvc.Close
    Set OraDatabaseSvc = Nothing
End Function

