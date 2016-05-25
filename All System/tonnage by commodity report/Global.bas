Attribute VB_Name = "modGlobal"
Option Explicit
' Adam Walter, added comment, May 2008.
' Every section of this report comes from BNI, unless otherwise stated.

' Edit, Adam Walter, Feb 2012.
' I have been instructed to add a "also ssave to file" option, but was given explicit
' unstructions (typo intentional) to "not touch a single line of the printout code"
' which means I have to in-line a 2nd write statement for every "print" call.  Ugly, indeed.

Global OraSession As Object
Global OraDatabase As Object
Global OraDatabaseRF As Object

'Global accessDb As  Object
'Global cn2 As  Object
'Global RS As  Object
'Global RS1 As  Object
'Global RS2 As  Object

'Global iSqlStmt As String



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

