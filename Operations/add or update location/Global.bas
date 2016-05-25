Attribute VB_Name = "modGlobal"
Option Explicit



Global gsOraUserId As String
Global gsOraPassword As String
Global gsOraInstance As String
Global OraSession As Object
Global OraDatabase As Object
Global DSVESSEL_PROFILE As Object
Global dsCARGO_TRACKING As Object
Global dsCUSTOMER_PROFILE As Object
Global dsSHORT_TERM_DATA As Object

Global gsSqlStmt As String
Global gsMessage As String
Global gsNL As String

Global iResponse2 As String
Global Updates As Integer
Global Adds As Integer

Global BreakExecute As Boolean

'Used to open dbf file
Global dbImport As Database
Global rsImport As Recordset

'Log file while population of database
Global gsLogFileName As String
Global giLogFileNum As Integer

Public Function NVL(avIn As Variant, avOut As Variant) As Variant

    If IsNull(avIn) Then
        NVL = avOut
    Else
        If Len(Trim$(avIn)) = 0 Then
            NVL = avOut
        Else
            NVL = avIn
        End If
    End If
    
End Function



