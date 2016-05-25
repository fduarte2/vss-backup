Attribute VB_Name = "Module1"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global dsSHORT_TERM_DATA As Object

Global SqlStmt As String














Public Function ValidateContract(UpdatedValue As Variant) As Boolean
    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM CONTRACT WHERE CONTRACTID = '" & UpdatedValue & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "Contract " & UpdatedValue & " does not exist in the Contract Table."
        ValidateContract = False
    Else
        ValidateContract = True
    End If
End Function
Public Function ValidateCustomer(UpdatedValue As Variant) As Boolean
    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Val(UpdatedValue) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If UpdatedValue = "" Or dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 0 Then
        ValidateCustomer = True
    Else
        MsgBox "Customer " & UpdatedValue & " is not valid."
        ValidateCustomer = False
    End If

End Function
Public Function ValidateCommodity(UpdatedValue As Variant) As Boolean
    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM COMMODITY_PROFILE WHERE COMMODITY_CODE = '" & Val(UpdatedValue) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "Commodity " & UpdatedValue & " is not valid."
        ValidateCommodity = False
    Else
        ValidateCommodity = True
    End If
End Function
Public Function ValidateRatePriority(UpdatedValue As Variant) As Boolean
    If UpdatedValue < 0 Or UpdatedValue > 999 Then
        MsgBox "Rate Priority must be between 0 and 999.  If higher numbers are needed, please contact TS."
        ValidateRatePriority = False
    Else
        ValidateRatePriority = True
    End If
End Function
Public Function ValidateArvType(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "A" And UpdatedValue <> "V" And UpdatedValue <> "T" And UpdatedValue <> "R" And UpdatedValue <> "F" Then
        MsgBox "Arrival Type must be either A, F, V, T, or R."
        ValidateArvType = False
    Else
        ValidateArvType = True
    End If
End Function
Public Function ValidateFreeDays(UpdatedValue As Variant) As Boolean
    If UpdatedValue < 0 Or Not IsNumeric(UpdatedValue) Then
        MsgBox "Free Days must be positive or zero."
        ValidateFreeDays = False
    Else
        ValidateFreeDays = True
    End If
End Function
Public Function ValidateWeekends(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "Y" And UpdatedValue <> "N" Then
        MsgBox "Weekends must be Y or N."
        ValidateWeekends = False
    Else
        ValidateWeekends = True
    End If
End Function
Public Function ValidateHolidays(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "Y" And UpdatedValue <> "N" Then
        MsgBox "Holidays must be Y or N."
        ValidateHolidays = False
    Else
        ValidateHolidays = True
    End If
End Function
Public Function ValidateDuration(UpdatedValue As Variant) As Boolean
    If UpdatedValue < 0 Or Not IsNumeric(UpdatedValue) Then
        MsgBox "Duration must be positive or zero."
        ValidateDuration = False
    Else
        ValidateDuration = True
    End If
End Function
Public Function ValidateDurationUnit(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "DAY" Then
        MsgBox "Currently, DAY is the only valid duration unit.  If new options need to be added, please contact TS"
        ValidateDurationUnit = False
    Else
        ValidateDurationUnit = True
    End If
End Function
Public Function ValidateRateStartDate(UpdatedValue As Variant) As Boolean
    If UpdatedValue < 0 Or Not IsNumeric(UpdatedValue) Then
        MsgBox "Rate Start Date must be positive or zero."
        ValidateRateStartDate = False
    Else
        ValidateRateStartDate = True
    End If
End Function
Public Function ValidateRate(UpdatedValue As Variant) As Boolean
    If UpdatedValue < 0 Or Not IsNumeric(UpdatedValue) Then
        MsgBox "Rate must be positive or zero."
        ValidateRate = False
    Else
        ValidateRate = True
    End If
End Function
Public Function ValidateUnit(UpdatedValue As Variant) As Boolean
    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM UNITS WHERE UOM = '" & UpdatedValue & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value <= 0 Then
        MsgBox "Unit " & UpdatedValue & " does not exist in the Units of Measure table.  If this is a new unit that needs to be added to the port, please contact TS."
        ValidateUnit = False
    Else
        ValidateUnit = True
    End If
End Function
Public Function ValidateXFERCredit(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "Y" And UpdatedValue <> "N" Then
        MsgBox "Transfer Day Credit must be Y or N."
        ValidateXFERCredit = False
    Else
        ValidateXFERCredit = True
    End If
End Function
Public Function ValidateServiceCode(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> 3111 And UpdatedValue <> 3121 Then
        MsgBox "Service must be 3111 or 3121."
        ValidateServiceCode = False
    Else
        ValidateServiceCode = True
    End If
End Function
Public Function ValidateShipLine(UpdatedValue As Variant) As Boolean
    SqlStmt = "SELECT COUNT(*) THE_COUNT FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & Val(UpdatedValue) & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(SqlStmt, 0&)
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 0 Or UpdatedValue = "" Then
        ValidateShipLine = True
    Else
        MsgBox "Shipping Line " & UpdatedValue & " is not valid."
        ValidateShipLine = False
    End If

End Function
Public Function ValidateWarehouse(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "A" And UpdatedValue <> "B" And UpdatedValue <> "C" And UpdatedValue <> "D" And UpdatedValue <> "E" And UpdatedValue <> "F" And UpdatedValue <> "G" And UpdatedValue <> "H" And UpdatedValue <> "O" And UpdatedValue <> "" Then
        MsgBox "Warehouse must be either A, B, C, D, E, F, G, H, or O."
        ValidateWarehouse = False
    Else
        ValidateWarehouse = True
    End If
End Function
Public Function ValidateBox(UpdatedValue As Variant) As Boolean
    If (UpdatedValue < 1 Or UpdatedValue > 8) And UpdatedValue <> "" Then
        MsgBox "Box must be between 1 and 8"
        ValidateBox = False
    Else
        ValidateBox = True
    End If
End Function
Public Function ValidateStacking(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "S" And UpdatedValue <> "" Then
        MsgBox "Stacking must either be S or empty"
        ValidateStacking = False
    Else
        ValidateStacking = True
    End If
End Function

Public Function ValidateScanned(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "SCANNED" And UpdatedValue <> "UNSCANNED" Then
        MsgBox "must specify either SCANNED or UNSCANNED"
        ValidateScanned = False
    Else
        ValidateScanned = True
    End If
End Function

Public Function ValidateSpecialReturn(UpdatedValue As Variant) As Boolean
    If UpdatedValue <> "" And UpdatedValue <> "REPACK" Then
        MsgBox "must specify either REPACK or empty"
        ValidateSpecialReturn = False
    Else
        ValidateSpecialReturn = True
    End If
End Function

