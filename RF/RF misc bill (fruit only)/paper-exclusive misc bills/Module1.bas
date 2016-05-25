Attribute VB_Name = "Module1"
' this program is an off-shoot of FR-BNI miscbills, designed solely for Dole Paper
' with any luck, a large re-write of the orgiinal will be forthcoming soon, preventing
' this whole multiple version mania from becoming an issue.
' April 2009

Option Explicit

Global OraSession As Object
Global OraDatabaseBNI As Object
Global OraDatabaseRF As Object
Global dsINBOUNDS As Object
Global dsCOMMODITY As Object
Global dsCUSTOMER As Object
Global dsRFBNI As Object
Global dsSHORT_TERM_DATA As Object
Sub Main()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabaseBNI = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 1&)
    Set OraDatabaseRF = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
'    Set OraDatabaseBNI = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 1&)
'    Set OraDatabaseRF = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    If OraDatabaseBNI.LastServerErr = 0 And OraDatabaseRF.LastServerErr = 0 Then
        frmRFBNIdolepaper.Show
    Else
        MsgBox "ERROR WHILE CONNECTING WITH DATABASE", vbCritical
        End
    End If
    
End Sub

Public Function roundUp(dblValue As Double) As Integer
On Error GoTo PROC_ERR
Dim myDec As Long

myDec = InStr(1, CStr(dblValue), ".", vbTextCompare)
If myDec > 0 Then
    roundUp = CInt(Left(CStr(dblValue), myDec)) + 1
Else
    roundUp = dblValue
End If

PROC_EXIT:
    Exit Function
PROC_ERR:
    MsgBox Err.Description, vbInformation, "Round Up"
End Function

