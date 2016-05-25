Attribute VB_Name = "Module1"
Global EmpID As String
Global OraSession As Object
Global OraDatabase As Object
Global gbPrintTimesheet As Boolean

Function GetValue(DataValue As Variant, Default As Variant) As Variant
  If IsNull(DataValue) Then
    GetValue = Default
  Else
    GetValue = DataValue
  End If
End Function

