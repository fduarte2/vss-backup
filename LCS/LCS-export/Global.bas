Attribute VB_Name = "modGlobal"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global OraDatabaseLCS As Object
Global OraDatabaseBNI As Object

Global RowNumber As Long

Sub MB()
  Screen.MousePointer = vbHourglass
End Sub

Sub MN()
  Screen.MousePointer = vbDefault
End Sub

Function GetValue(DataValue As Variant, Default As Variant) As Variant
  If IsNull(DataValue) Then
    GetValue = Default
  Else
    GetValue = DataValue
  End If
End Function

Public Sub CreateAfile(sFilename As String)

    ' This routine will create an empty log file
    
    Dim fs As New FileSystemObject, f As Variant
    
    Set fs = CreateObject("Scripting.FileSystemObject")
    Set f = fs.CreateTextFile(sFilename, True)
    f.Close
    
    
End Sub

Public Function WriteLogFile(sFilename As String, sText As String)
    
    ' This function will open a log file for appending text messages
    
    Dim fs As New FileSystemObject, f
    If Not fs.FileExists(sFilename) Then   ' This file does not exist, create it
       CreateAfile (sFilename)
    End If

    Set fs = CreateObject("Scripting.FileSystemObject")
    Set f = fs.OpenTextFile(sFilename, ForAppending, TristateFalse)
    f.WriteLine sText
    f.Close
    
End Function

Public Function errorLog(sText As String)
    Dim fileName As String
    fileName = App.Path & "\PROBLEM.LOG"
    
    Dim fs As New FileSystemObject, f
    If Not fs.FileExists(fileName) Then    ' This file does not exist, create it
       CreateAfile (fileName)
    End If

    Set fs = CreateObject("Scripting.FileSystemObject")
    Set f = fs.OpenTextFile(fileName, ForAppending, TristateFalse)
    f.WriteLine sText
    f.Close
End Function

Function SingleToDouble(s As String) As String
  Dim RetVal As String, _
      Ch As String
  Dim Index As Long
  
  RetVal = ""
  For Index = 1 To Len(s)
    Ch = Mid(s, Index, 1)
    If Ch = "'" Then
      RetVal = RetVal & Chr(34)
    Else
      RetVal = RetVal & Ch
    End If
  Next Index
  SingleToDouble = RetVal

End Function

Function getValueInFormat(ByVal myVal As String, ByVal fillWith As String, ByVal size As Integer) As String
    Dim fillStr As String
    
    fillWith = Trim(fillWith)
    fillStr = fillWith
    Do While Len(fillStr) < size
        fillStr = fillStr + fillWith
    Loop
    
    getValueInFormat = Left(myVal & fillStr, size)
End Function

Function findLastSunday(day As Date) As Date
    Dim weekofday As Integer
    weekofday = Weekday(day)
    
    If weekofday = 1 Then
        findLastSunday = day
    Else
        findLastSunday = day - (weekofday - 1)
    End If
End Function

Sub Main()
     'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")

    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase("LCS", "LABOR/LABOR", 0&)
    Set OraDatabaseBNI = OraSession.OpenDatabase("BNI", "SAG_OWNER/SAG", 0&)
'    Set OraDatabase = OraSession.OpenDatabase("BNITEST", "LABOR/LABOR", 0&)
'    Set OraDatabaseBNI = OraSession.OpenDatabase("BNITEST", "SAG_OWNER/BNITEST238", 0&)
    
    'Call frmSimplified.Show
    Call frmSelectDate.Show
    'Call frmCeridianEntry.Show
End Sub

Function getMaxRowNumber() As Long
    Dim SqlStmt As String
    Dim iRS As Object
    
    SqlStmt = "Select Max(ROW_NUMBER)MAX_NUM FROM CERIDIAN_PAY_DETAIL "
    Set iRS = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
    
    If Not iRS.EOF And Not IsNull(iRS.Fields("MAX_NUM")) Then
        getMaxRowNumber = iRS.Fields("MAX_NUM").Value
    Else
        getMaxRowNumber = 0
    End If
    
    iRS.Close
    Set iRS = Nothing
End Function

Sub printResult(count As Long, file As String, startDate As Date, endDate As Date)
    Printer.Print
    Printer.Print
    Printer.Print Space(30) & "Ceridian Export Process For " & Format(startDate, "mm/dd/yyyy") & " to " & Format(endDate, "mm/dd/yyyy") & " Complete!"
    Printer.Print
    Printer.Print Space(30) & "No. of records processed = " & count
    Printer.Print
    Printer.Print Space(30) & "Ceridian Import Filename = " & file
    Printer.Print
    Printer.Print Space(30) & "Created on " & Now
    Printer.EndDoc
End Sub
