Attribute VB_Name = "Module1"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsAT_EMPLOYEE As Object  ' Primary dataset from AT_EMPLOYEE table
Global dsTIME_SUBMISSION As Object  ' Primary dataset from TIME_SUBMISSION table
Global dsSHORT_TERM_DATA As Object
Global dsEMPLOYEE As Object ' used mostly for editing of values in AT_EMPLOYEE table
Global dsPRINTINFODATA As Object ' used for lineitems in the printout
Global dsPRINTINFOHEADER As Object ' used for header in the printout
Global strCurrentWeekOracle As String
Global strCurrentWeekHuman As String
Global strCurrentWeekOracleAPP As String
Global strCurrentWeekHumanAPP As String
Global strPastWeek As String    ' USed to view past weeks
Global strSql As String         ' String used for all SQL statements
Global strUser As String        ' Person logged into Windows running program (I.E. ithomas)
Global strComputer As String    ' Computer currently running Timesheet program
Global strUserID As String      ' EmployeeID using timesheet app (I.E. E444000)

Global strSuperModID As String  ' Used for when a super force-modifies an employee sheet








Sub Initialize()
' Sets database and initializes some global variables, or errors
' these variables defined here should NEVER BE REDEFINED throughout the rest of the program.


'strUser = LCase$(Environ$("USERNAME"))
'strUser = "ddonofrio"
'strUser = "bbarker"
'strUser = "cfoster"
'strUser = "abrizolaki"
'strUser = "wcollins"
'strUser = "fduarte"
'strUser = "saithoma"
'strUser = "pshukla"
'strUser = "fvignuli"
'strUser = "ddouglas"
'strUser = "gbailey"
'strUser = "ltreut"
'strUser = "martym"
'strUser = "vfarkas"
'strUser = "tkeefer"
'strUser = "sannone"
strUser = "skennard"
'strUser = "ephilhower"
'strUser = "lhorne"
'strUser = "aoutlaw"
'strUser = "kskinner"
'strUser = "jharoldson"
'strUser = "jcustis"
'strUser = "dthomp"
'strUser = "draczkowski"
'strUser = "twiggins"
'strUser = "ithomas"
'strUser = "cmarttinen"
strComputer = Environ$("COMPUTERNAME")

Set OraSession = CreateObject("OracleInProcServer.XOraSession")
'Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
If OraDatabase.LastServerErr <> 0 Then
    MsgBox "Database connection could not be made.  Please Contact TS."
    End
End If

strSql = "SELECT EMPLOYEE_ID FROM AT_EMPLOYEE WHERE WINDOWS_LOGIN_ID = '" & strUser & "' AND EMPLOYMENT_STATUS = 'ACTIVE'"
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
If dsSHORT_TERM_DATA.Recordcount = 0 Then
    MsgBox "Current User has no entry in the Time Recording System.  Please contact TS if this is in error"
    Exit Sub
Else
    strUserID = dsSHORT_TERM_DATA.Fields("EMPLOYEE_ID").Value
End If

strSql = "ALTER SESSION SET nls_date_format = 'DD-MON-YYYY'"
OraDatabase.ExecuteSQL (strSql)

' employees can only submit until 10AM.
' Supers can approve until 12Noon.
strSql = "SELECT to_char(NEXT_DAY((SYSDATE - 7) - (10/24), 'MONDAY'), 'FMMM/DD/YYYY') THE_PRI, to_char(NEXT_DAY((SYSDATE - 7) - (10/24), 'MONDAY'), 'dd-MON-yyyy') THE_ORA, to_char(NEXT_DAY((SYSDATE - 7) - (12/24), 'MONDAY'), 'FMMM/DD/YYYY') THE_PRI_APP, to_char(NEXT_DAY((SYSDATE - 7) - (12/24), 'MONDAY'), 'dd-MON-yyyy') THE_ORA_APP FROM DUAL"
' (SYSDATE - 7) - (10 / 24) will give the current monday if after Monday 10:00AM, or previous monday if run before Monday at 10:00AM
' if these times need to be adjusted, simply edit the numerator of the fraction to the desired time.
Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
strCurrentWeekOracle = dsSHORT_TERM_DATA.Fields("THE_ORA").Value
strCurrentWeekHuman = dsSHORT_TERM_DATA.Fields("THE_PRI").Value
strCurrentWeekOracleAPP = dsSHORT_TERM_DATA.Fields("THE_ORA_APP").Value
strCurrentWeekHumanAPP = dsSHORT_TERM_DATA.Fields("THE_PRI_APP").Value

End Sub


