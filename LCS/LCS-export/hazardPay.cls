VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "hazardPay"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Private mvarHireDate As String
Private mvarEmployeeID As String
Private mvarShiftCode As String
Private mvarPayRate As String

Private mvarCeridianPay As ceridianPay
Public Property Let HireDate(s As String)

  mvarHireDate = s
  
End Property

Public Property Let EmployeeID(s As String)

  mvarEmployeeID = s
  
End Property

Public Property Let ShiftCode(s As String)

    mvarShiftCode = s
  
End Property

Public Property Get ShiftCode() As String
    ShiftCode = mvarShiftCode
End Property

Public Property Let PayRate(s As String)
    mvarPayRate = s
End Property

Public Property Get PayRate() As String
    PayRate = mvarPayRate
End Property

Public Sub getCeridianPay()
    Dim result As New ceridianPay
    
    With result
        .EmployeeID = Right(mvarEmployeeID, 4)
        .ServShiftEquipCode = "9765000000001" & Right("0" & mvarShiftCode, 1)
        .PayCode = "7H"
        .RateCode = "E"
        .PayRate = mvarPayRate
        .PayHour = ""
    End With
    
    Set mvarCeridianPay = result
End Sub

Public Function getCeridianString() As String
    Call getCeridianPay
    getCeridianString = mvarCeridianPay.getCeridianString
End Function

Public Function writeCeridianPay() As Boolean
    writeCeridianPay = False
    
    If mvarCeridianPay.writeCeridianPay(mvarEmployeeID, mvarHireDate) = False Then
        Exit Function
    Else
       writeCeridianPay = True
    End If
End Function
