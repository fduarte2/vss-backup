Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsBILLING As Object
Global dsBILLING_MAX As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_PROFILE As Object
Global dsBILLING_NO As Object
Global dsRECORDCHECK As Object
Global dsSHORT_TERM_DATA As Object

Global dsMISC As Object
Global gsPVItem As String
Global gsSqlStmt As String

'Billing number
Global glBillingNum As Long

Global itemSelected As Boolean
Sub Main()


    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
'    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    If OraDatabase.lastServerErr = 0 Then
        frmMiscBilling.Show
    Else
        MsgBox "Error " & OraDatabase.lastServerErrText & " occured.", vbExclamation, "Logon"
        End
    End If
    
    
End Sub

