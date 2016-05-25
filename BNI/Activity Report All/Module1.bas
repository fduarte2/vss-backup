Attribute VB_Name = "Module1"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_EXT As Object
Global dsCARGO_DELIVERY As Object
Global dsCARGO_TRACKING As Object
Global dsCARGO_MANIFEST As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object

Global gsPVItem As String

Global Const sMsg As String = "ACTIVITY REPORT"

Sub main()

Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)

    If OraDatabase.LastServerErr = 0 Then
        frmActRep.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, sMsg
        End
    End If
    
End Sub
