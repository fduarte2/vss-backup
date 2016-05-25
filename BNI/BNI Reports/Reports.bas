Attribute VB_Name = "Module1"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_EXT As Object
Global dsCOMMODITY_PROFILE As Object
Global dsEXPORTER_PROFILE As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_ORIGINAL_MANIFEST As Object
Global dsVOYAGE_CARGO As Object
Global dsVESSEL_PROFILE As Object
Global dsCARGO_TRACKING As Object
Global dsCUSTOMER_PROFILE As Object
Global dsCARGO_ACTIVITY_A As Object
Global dsDUMMY_ACTIVITY As Object
Global dsCARGO_DELIVERY As Object
Global dsLATTER_ACTIVITY As Object

Global dsCARGO_MANIFEST_WT As Object

Global SqlStmt As String
Global gsSqlStmt As String
Global gsSqlStmtWt As String
Global iByCustDate As Boolean
Global iByShipDate As Boolean

Global dQty1ToQty2Ratio As Double
Global dQty1ToWeightRatio As Double
Global dQty1FromCM As Double
Global dQty2FromCM As Double
Global dWeightFromCM As Double

Sub Main()
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)

    If OraDatabase.LastServerErr = 0 Then
        frmInventory.Show
    Else
        End
    End If
    
End Sub

Function GetLumberWt(W As Double, Q As Integer, L As Double) As Double
    GetLumberWt = (W / Q) * L
End Function
