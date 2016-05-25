Attribute VB_Name = "Module1"
' Captain's Log, December 2007.
' It has been shown that the Miscbill system is inadequate for translation to Clementine Miscbills.
' as such, a change has been made to exclude the clementine customers (439, 440) from this code.
' In the future, if the "miscbill" concept will continue to be automated, a change needs to be made
' to accomodate multiple customers, and possibly commodities.
' current, this program, dspc-s16/finance/bni/maintain/rfbnidef.php, and database table
' RFBNI_MISC_BILL_RATE would need attention and modification, but I make no promises it will stay that way



Option Explicit

Global OraSession As Object
Global OraDatabaseBNI As Object
Global OraDatabaseRF As Object
'Global OraDatabase2 As Object
Global dsRETURN As Object
Global dsDOCK_RETURN As Object
Global dsTRANSFER As Object
Global dsINBOUNDS As Object
Global dsRetEnza As Object
Global dsCOMMODITY As Object
Global dsTRANSFER_TO As Object
Global dsCUSTOMER As Object
Global dsRFBNI As Object
Global dsSavRFBNI As Object
Global dsSHORT_TERM_DATA As Object
Sub Main()
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    Set OraDatabaseBNI = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 1&)
    Set OraDatabaseRF = OraSession.OpenDatabase("RF", "SAG_OWNER/OWNER", 0&)
'    Set OraDatabaseBNI = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 1&)
'    Set OraDatabaseRF = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    
    
'    Set OraDatabase2 = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
    If OraDatabaseBNI.LastServerErr = 0 And OraDatabaseRF.LastServerErr = 0 Then
        frmRFServ.Show
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

