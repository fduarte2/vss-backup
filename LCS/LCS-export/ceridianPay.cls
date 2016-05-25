VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "ceridianPay"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Private mvarEmployeeID As String
Private mvarServShiftEquipCode As String
Private mvarPayCode As String
Private mvarPayHour As String
Private mvarRateCode As String
Private mvarPayRate As String

Public Property Let EmployeeID(s As String)

  mvarEmployeeID = Trim(s)
  
End Property

Public Property Let ServShiftEquipCode(s As String)

  mvarServShiftEquipCode = Trim(s)
  
End Property
Public Property Get ServShiftEquipCode() As String

  ServShiftEquipCode = mvarServShiftEquipCode
  
End Property

Public Property Let payCode(s As String)

  mvarPayCode = Trim(s)
  
End Property

Public Property Get payCode() As String
    payCode = mvarPayCode
End Property

Public Property Let rateCode(s As String)

  mvarRateCode = Trim(s)
  
End Property

Public Property Get rateCode() As String

  rateCode = mvarRateCode
  
End Property

Public Property Let payRate(s As String)

  mvarPayRate = Trim(s)
  
End Property

Public Property Get payRate() As String

  payRate = mvarPayRate
  
End Property

Public Property Let payHour(s As String)

  mvarPayHour = Trim(s)
  
End Property

Public Property Get payHour() As String

  payHour = mvarPayHour
  
End Property

Public Function getCeridianString() As String
    Call formatPay
    
    getCeridianString = "HEG " & _
                  Left("LCS PAYROLL" & "                                ", 32) & _
                  Left(mvarEmployeeID & "          ", 10) & _
                  Left(mvarPayCode & "  ", 2) & _
                  Left(mvarPayHour & "               ", 15) & _
                  mvarRateCode & _
                  Left(mvarPayRate & "               ", 15) & _
                  Left("                      ", 18) & _
                  Left(mvarServShiftEquipCode & "                  ", 19)
End Function

Public Sub formatPay()
    Dim iPosition As Integer
    Dim iFirstPart As String, iSecondPart As String
    
    If mvarPayHour <> "" Then
      iPosition = InStr(1, mvarPayHour, ".")
      If iPosition <> 0 Then
          iSecondPart = Mid(mvarPayHour, iPosition + 1, Len(mvarPayHour) - iPosition)
          iSecondPart = Left(iSecondPart & "00", 2)
          iFirstPart = Left$(mvarPayHour, iPosition - 1)
      Else
          iSecondPart = "00"
          iFirstPart = mvarPayHour
      End If
      
      mvarPayHour = iFirstPart & "." & iSecondPart
    End If
    
    iPosition = 0
    iFirstPart = ""
    iSecondPart = ""
    If mvarPayRate <> "" Then
        iPosition = InStr(1, mvarPayRate, ".")
        If iPosition <> 0 Then
            iSecondPart = Mid(mvarPayRate, iPosition + 1, Len(mvarPayRate) - iPosition)
            iSecondPart = Left(iSecondPart & "00", 2)
            iFirstPart = Left(mvarPayRate, iPosition - 1)
        Else
            iSecondPart = "00"
            iFirstPart = mvarPayRate
        End If
        mvarPayRate = iFirstPart & "." & iSecondPart
    End If
End Sub

Public Function writeCeridianPay(EmployeeID As String, hireDate As String) As Boolean
    On Error GoTo ErrHandler
    
    Dim SqlStmt As String
    Dim iUpdRecCnt As Long
    Dim dsDetail As Object
    
    SqlStmt = " select * from CERIDIAN_PAY_DETAIL "
    
    Set dsDetail = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
        
    dsDetail.AddNew
    dsDetail.Fields("ROW_NUMBER").Value = RowNumber + 1
    dsDetail.Fields("HIRE_DATE").Value = Format(hireDate, "MM/DD/YYYY")
    dsDetail.Fields("EMPLOYEE_ID").Value = EmployeeID
    dsDetail.Fields("CERIDIAN_PAY_CODE").Value = mvarPayCode
    dsDetail.Fields("CERIDIAN_PAY_HOUR").Value = mvarPayHour
    dsDetail.Fields("CERIDIAN_RATE_CODE").Value = mvarRateCode
    dsDetail.Fields("CERIDIAN_PAY_RATE").Value = mvarPayRate
    dsDetail.Fields("CERIDIAN_SERVICE_CODE").Value = mvarServShiftEquipCode
    dsDetail.Fields("PROCESS_DATE").Value = Now
    dsDetail.Update
    
    RowNumber = RowNumber + 1
    
    If OraDatabase.LastServerErr = 0 Then
        writeCeridianPay = True
    Else
        writeCeridianPay = False
    End If
    
    dsDetail.Close
    Set dsDetail = Nothing
    
    Exit Function
ErrHandler:
    writeCeridianPay = False
    MsgBox ("Problem: " & OraDatabase.LastServerErrText)
End Function



