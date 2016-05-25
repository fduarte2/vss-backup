Attribute VB_Name = "Module1"
Option Explicit
Global OraSession As Object
Global OraDatabase As Object
Global dsSERVICE_CATEGORY As Object
Sub main()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("bni", "SAG_OWNER/sag", 0&)

    If OraDatabase.LastServerErr = 0 Then
        frmServiceCat.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
    End If

End Sub
