Attribute VB_Name = "ModBniRates"

Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsSERVICE_CATEGORY As Object
Global dsCOMMODITY_PROFILE As Object
Global dsDockRcptHandlingRate As Object
Global dsUnit As Object
Global dsEQUIP_RATE As Object
Global dsEQUIP_RATE_TYPE As Object
Global dsFREE_TIME As Object
Global dsHANDLING_RATE As Object
Global dsLABOR_RATE As Object
Global dsLABOR_RATE_TYPE As Object
Global dsSTORAGE_RATE As Object
Global dsTRK_HDLING_RATE As Object
Global dsVESSEL_RATE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsTRM_RATE  As Object
Global dsLIST As Object
Global dsSave As Object
Global aService As String
Global aRate As String
Global aComm As String
Global aUnit As String
Global SqlStmt As String
Global lineList As String
Global itemSelected As Boolean

Sub Main()

    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)

    If OraDatabase.LastServerErr = 0 Then
        frmBniRates.Show
    Else
        MsgBox "Error " & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
    End If
    
End Sub



