Attribute VB_Name = "Module1"
Option Explicit
Global OraSession As Object
Global OraDatabase As Object
Global dsRETURN As Object
Global dsDOCK_RETURN As Object
Global dsTRANSFER As Object
Global dsINBOUNDS As Object
Global dsCOMMODITY As Object
Global dsTRANSFER_TO As Object
Global dsCUSTOMER As Object
Global dsMISCBILLS As Object
Sub Main()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    If OraDatabase.LastServerErr = 0 Then
        frmBNIMiscBills.Show
    Else
        MsgBox "ERROR WHILE CONNECTING WITH DATABASE", vbCritical
        End
    End If
    
End Sub

