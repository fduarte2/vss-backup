Attribute VB_Name = "modGlobal"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object

Global rsHOURLY_DETAIL As Object
Global gsSqlStmt As String
Global blSkipEvent As Boolean
Global CurrRow As Integer

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
