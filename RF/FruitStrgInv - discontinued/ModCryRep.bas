Attribute VB_Name = "ModCryRep"

Option Explicit


' For the date settings
Public Type SYSTEMTIME
         wYear As Integer
         wMonth As Integer
         wDayOfWeek As Integer
         wDay As Integer
         wHour As Integer
         wMinute As Integer
         wSecond As Integer
         wMilliseconds As Integer
End Type
Public Const DTM_SETRANGE = &H1004&
Public Const GDTR_MIN = 1
Public Const GDTR_MAX = 2

Public Declare Function SendMessage Lib "user32" Alias _
         "SendMessageA" (ByVal hwnd As Long, ByVal wMsg As Long, _
         ByVal wParam As Long, lParam As Any) As Long

Global OraSession As Object
Global OraDatabase As Object
Global dsPreInvoice As Object
Global dsBILLING As Object
Global dsBILLING_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsCOUNTRY As Object
Global dsSERVICE_CATEGORY As Object
Global dsSTORAGE_RATE As Object
Global sHeader(6) As String
Global dsDURATION As Object
Global dsRFBILLING_DETAIL As Object
Global dsINV_HISTORY As Object
Global dsBILLING_CORRECTION As Object

Sub Main()

 'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)

    If OraDatabase.lastservererr = 0 Then
        frmCryRep.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
       End
    End If
    
End Sub
