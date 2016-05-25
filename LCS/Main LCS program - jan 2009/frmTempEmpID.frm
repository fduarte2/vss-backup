VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Begin VB.Form frmTempEmpID 
   BorderStyle     =   1  'Fixed Single
   Caption         =   "Modify Employee ID "
   ClientHeight    =   4605
   ClientLeft      =   45
   ClientTop       =   285
   ClientWidth     =   8430
   Icon            =   "frmTempEmpID.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   4605
   ScaleWidth      =   8430
   StartUpPosition =   3  'Windows Default
   Begin VB.ListBox lstTempID 
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   2760
      Left            =   120
      TabIndex        =   5
      ToolTipText     =   "Select Temporary Employee to change to Permanent"
      Top             =   1440
      Width           =   3135
   End
   Begin VB.TextBox txtPermID 
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   420
      Left            =   5640
      TabIndex        =   0
      Top             =   1440
      Width           =   2655
   End
   Begin VB.CommandButton cmdUpdate 
      Caption         =   "&UPDATE"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   3960
      TabIndex        =   1
      ToolTipText     =   "Update Changes"
      Top             =   3720
      Width           =   1655
   End
   Begin VB.CommandButton cmdCancel 
      Caption         =   "&CANCEL"
      Enabled         =   0   'False
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   6000
      TabIndex        =   2
      ToolTipText     =   "Cancel Changes"
      Top             =   3720
      Width           =   1655
   End
   Begin VB.Label Label1 
      Caption         =   "Employee Details"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   255
      Left            =   120
      TabIndex        =   6
      Top             =   1080
      Width           =   2535
   End
   Begin MSForms.Image Image1 
      Height          =   735
      Left            =   0
      Top             =   0
      Width           =   855
      BorderStyle     =   0
      SizeMode        =   1
      SpecialEffect   =   2
      Size            =   "1508;1296"
      Picture         =   "frmTempEmpID.frx":0442
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   18
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   960
      TabIndex        =   4
      Top             =   0
      Width           =   7455
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   8400
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label Label8 
      Alignment       =   1  'Right Justify
      Caption         =   "Permanent ID"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   375
      Left            =   3240
      TabIndex        =   3
      Top             =   1440
      Width           =   2250
   End
End
Attribute VB_Name = "frmTempEmpID"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim EmplRS As Object

'****************************************
'To Cancel the Changes
'****************************************
Private Sub cmdCancel_Click()
  On Error Resume Next
  EmplRS.CancelUpdate
  OraSession.Rollback       'Roll Back the Transaction
  CloseRec
  Unload Me
End Sub

'****************************************
'To Update the Changes - take care of Referential Integrity
'****************************************
Private Sub cmdUpdate_Click()
  If Trim(txtPermID) = vbNullString Then
    MsgBox "Please Enter Permanent ID", vbInformation, "Validation Failure"
    txtPermID = ""
    txtPermID.SetFocus
    Exit Sub
  End If

  'Check for existence of Permanent ID - if so, don't allow to Update
  EmplRS.MoveFirst
  EmplRS.FindFirst "Upper(Employee_ID) = '" + UCase(Trim(txtPermID)) + "'"
  If EmplRS.NoMatch Then
  'Do Nothing
  Else
    MsgBox "Employee ID Already Exists. Please enter another Employee ID.", vbInformation, "Duplicate Employee ID"
    txtPermID = ""
    txtPermID.SetFocus
    Exit Sub
  End If
  
  frmEmpMaint.MousePointer = vbHourglass
  
  'Check for Entries in Daily Hire for this Te mporary ID
  Dim myDHRS As Object, mySQL As String
  Dim myUpdateSQL As String, mypos As Integer, tempid As String
  mypos = InStr(lstTempID.List(lstTempID.ListIndex), ")")
  tempid = Mid(lstTempID.List(lstTempID.ListIndex), 2, mypos - 2)
  mySQL = "Select * from Daily_Hire_List where Upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
  Set myDHRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
  If myDHRS.EOF And myDHRS.BOF Then
    'No records in Daily Hire - Proceed with Updating Employee
    OraSession.BeginTrans       'Begin the Transaction
    'Call LockTbl("Employee")  'Lock the Table
    EmplRS.Edit
    EmplRS.Fields("Employee_ID").Value = Trim(txtPermID)
    EmplRS.Update
    'Commit or Rollback the Transaction
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
    Else
      OraSession.Rollback
    End If
  
  Else
    'There are records in Daily Hire for this Employee ID - update those also
      'Check for Entries in Hourly Detail for this Te mporary ID
      Dim myHDRS As Object
      mySQL = "Select * from Hourly_Detail where upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
      Set myHDRS = OraDatabase.DBCreateDynaset(mySQL, 0&)
      If myHDRS.EOF And myHDRS.BOF Then
        'No records in Hourly Detail - Proceed with Updating Daily Hire
        OraSession.BeginTrans       'Begin the Transaction
        'Call LockTbl("Daily_Hire_List")  'Lock the Table
        myUpdateSQL = "Update Daily_Hire_List Set Employee_ID = '" + Trim(txtPermID) + "' Where Upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
        OraDatabase.ExecuteSQL myUpdateSQL
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
      
      Else
        'There are records in Hourly Detail for this Employee ID - update those also
        'Store the data from Hourly Detail in a text file - Start with DH + Current date
        Dim myFile As String, myHrs As String, myComm As String, mySvc As String
        Dim myEqp As String, myShip As String, myLoc As String, myStr As String
        Dim myReg As String, myEmp As String, myCust As String
        myFile = App.Path + "\TE" + Format(Date, "mm") + Format(Date, "dd") + ".txt"
        Open myFile For Append As #1
        Print #1, Space(20) + "DIAMOND STATE PORT CORPORATION"
        Print #1, Space(19) + "Time Sheet for Temporary Employee" + Space(15) + Str(Date)
        Print #1, "-----------------------------------------------------------------------------------"
        Print #1, "EMPID   START   END     HRS   REG BILL SVC  EQP  COM  CATE       SHIP  CUST  USER"
        Print #1, "-----------------------------------------------------------------------------------"
        myHDRS.MoveFirst
        Do While Not myHDRS.EOF
          'Stuff Hours with Blank Spaces
          If Trim(Len(myHDRS.Fields("Duration").Value)) < 5 Then
            myHrs = Trim(myHDRS.Fields("Duration").Value) + Space(5 - Trim(Len(myHDRS.Fields("Duration").Value)))
          Else
            myHrs = myHDRS.Fields("Duration").Value
          End If
          
          'Stuff Commodity with Blank Spaces
          If Trim(Len(myHDRS.Fields("Commodity_Code").Value)) < 4 Then
            myComm = Trim(myHDRS.Fields("Commodity_Code").Value) + Space(4 - Trim(Len(myHDRS.Fields("Commodity_Code").Value)))
          Else
            myComm = myHDRS.Fields("Commodity_Code").Value
          End If
          
          'stuff Service with Blank Spaces
          If Trim(Len(myHDRS.Fields("Service_Code").Value)) < 4 Then
            mySvc = Trim(myHDRS.Fields("Service_Code").Value) + Space(4 - Trim(Len(myHDRS.Fields("Service_Code").Value)))
          Else
            mySvc = myHDRS.Fields("Service_Code").Value
          End If
          
          'Stuff Equipment with Blank Spaces
          If Trim(Len(myHDRS.Fields("Equipment_ID").Value)) < 4 Then
            myEqp = Trim(myHDRS.Fields("Equipment_ID").Value) + Space(4 - Trim(Len(myHDRS.Fields("Equipment_ID").Value)))
          Else
            myEqp = myHDRS.Fields("Equipment_ID").Value
          End If
          
          'Stuff Vessel with Blank Spaces
          If Trim(Len(myHDRS.Fields("Vessel_ID").Value)) < 4 Then
            myShip = Trim(myHDRS.Fields("Vessel_ID").Value) + Space(4 - Trim(Len(myHDRS.Fields("Vessel_ID").Value)))
          Else
            myShip = myHDRS.Fields("Vessel_ID").Value
          End If
          
          'Stuff Location with Blank Spaces
          If Trim(myHDRS.Fields("Location_ID").Value) = vbNullString Or IsNull(myHDRS.Fields("Location_ID").Value) Then
            myLoc = Space(10)
          Else
            If Trim(Len(myHDRS.Fields("Location_ID").Value)) < 10 Then
              myLoc = Trim(myHDRS.Fields("Location_ID").Value) + Space(10 - Trim(Len(myHDRS.Fields("Location_ID").Value)))
            Else
              myLoc = myHDRS.Fields("Location_ID").Value
            End If
          End If
          
          'Stuff Earning Type with Blank Spaces
          If Trim(myHDRS.Fields("Earning_Type_ID").Value) = vbNullString Or IsNull(myHDRS.Fields("Earning_Type_ID").Value) Then
            myReg = Space(3)
          Else
            If Trim(Len(myHDRS.Fields("Earning_Type_ID").Value)) < 3 Then
              myReg = Trim(myHDRS.Fields("Earning_Type_ID").Value) + Space(3 - Trim(Len(myHDRS.Fields("Earning_Type_ID").Value)))
            Else
              myReg = myHDRS.Fields("Earning_Type_ID").Value
            End If
          End If
          
          'Stuff Employee ID with Blank Spaces
          If Trim(Len(myHDRS.Fields("Employee_ID").Value)) < 7 Then
            myEmp = Trim(myHDRS.Fields("Employee_ID").Value) + Space(7 - Trim(Len(myHDRS.Fields("Employee_ID").Value)))
          Else
            myEmp = myHDRS.Fields("Employee_ID").Value
          End If
          
          'Stuff Customer with Blank Spaces
          If Trim(Len(myHDRS.Fields("Customer_ID").Value)) < 4 Then
            myCust = Trim(myHDRS.Fields("Customer_ID").Value) + Space(4 - Trim(Len(myHDRS.Fields("Customer_ID").Value)))
          Else
            myCust = myHDRS.Fields("Customer_ID").Value
          End If

          myStr = myEmp + " " + Format(myHDRS.Fields("Start_Time").Value, "hh:nnAM/PM") + " " + Format(myHDRS.Fields("End_Time").Value, "hh:nnAM/PM") + " " + myHrs + " " + myReg
          myStr = myStr + " " + myHDRS.Fields("Billing_Flag").Value + "    " + mySvc + " " + myEqp + " " + myComm
          myStr = myStr + " " + myLoc + " " + myShip + "  " + myCust + " " + myHDRS.Fields("User_ID").Value
          Print #1, myStr
          myHDRS.MoveNext
        Loop
        Print #1, "-----------------------------------------------------------------------------------"
        Print #1, " "
        Close #1
        MsgBox "TimeSheet for the Employee " + tempid + " has been stored in the file " + myFile, vbInformation, "TimeSheet"
        
        ''To Print the Time Sheet for this Te mporary Employee
        'Dim myDE As New DE_LCS
        'Dim myDR As New DR_TimeSheet
        'myDE.rsHourlyDetail_Grouping.Source = "SHAPE {SELECT Employee_Name, Employee_Type_ID, Employee_Sub_Type_Id, c.User_Name , b.* From Employee a, Hourly_Detail b, LCS_User c Where upper(a.Employee_Id) = '" + UCase(Trim(te mpid)) + "' and upper(b.Employee_Id) = '" + UCase(Trim(te mpid)) + "' and b.User_ID = c.User_ID and Upper(b.EARNING_TYPE_ID) <> 'LU'}  AS HourlyDetail COMPUTE HourlyDetail BY 'Employee_Type_ID','Employee_Sub_Type_Id','Employee_Id','Employee_Name'"
        'myDE.rsHourlyDetail_Grouping.Open
        'myDR.Refresh
        'myDR.Show
        'myDR.PrintReport
        'myDR.Hide
        'myDE.rsHourlyDetail_Grouping.Close
        OraSession.BeginTrans       'Begin the Transaction
        'Call LockTbl("Hourly_Detail")  'Lock the Table
        myUpdateSQL = "Update Hourly_Detail Set Employee_ID = '" + Trim(txtPermID) + "' Where Upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
        OraDatabase.ExecuteSQL myUpdateSQL
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
        
        'Now Update Daily Hire Also
        OraSession.BeginTrans       'Begin the Transaction
        'Call LockTbl("Daily_Hire_List")  'Lock the Table
        myUpdateSQL = "Update Daily_Hire_List Set Employee_ID = '" + Trim(txtPermID) + "' Where Upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
        OraDatabase.ExecuteSQL myUpdateSQL
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
        
        'Update Employee Table
        OraSession.BeginTrans       'Begin the Transaction
        'Call LockTbl("Employee")  'Lock the Table
        EmplRS.Edit
        EmplRS.Fields("Employee_ID").Value = Trim(txtPermID)
        EmplRS.Update
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
        
        'Update CheckOutException Table
        OraSession.BeginTrans       'Begin the Transaction
        myUpdateSQL = "Update CheckOutException Set Employee_ID = '" + Trim(txtPermID) + "' Where Upper(Employee_ID) = '" + UCase(Trim(tempid)) + "'"
        OraDatabase.ExecuteSQL myUpdateSQL
        'Commit or Rollback the Transaction
        If OraDatabase.LastServerErr = 0 Then
          OraSession.CommitTrans
        Else
          OraSession.Rollback
        End If
      End If
  End If
  frmEmpMaint.MousePointer = vbDefault
  frmHiring.Show
  Unload Me
End Sub

Private Sub Form_Load()
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  
  OpenRec       'To Open the Employee Recordset
  PopulateTempID
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  frmHiring.Show
End Sub

Private Sub Form_Terminate()
  Set EmplRS = Nothing
End Sub

Private Sub Form_Unload(Cancel As Integer)
  On Error Resume Next
  EmplRS.CancelUpdate
  OraSession.Rollback       'Rollback the Transaction
  CloseRec
  Unload Me
End Sub

'****************************************
'To close the Recordset object
'****************************************
Private Sub CloseRec()
  EmplRS.Close
End Sub

'****************************************
'To open the Employee Recordset
'****************************************
Private Sub OpenRec()
  Dim arrCnt As Integer
  Set EmplRS = OraDatabase.DBCreateDynaset("Select * from employee where Upper(Employee_ID) like 'T%'", 0&)
End Sub

Private Sub PopulateTempID()
  If EmplRS.EOF And EmplRS.BOF Then
    MsgBox "No Temporary Employees are Available", vbInformation, "Data Unavailable"
    cmdCancel.Enabled = True
    Exit Sub
  End If
  cmdUpdate.Enabled = True
  cmdCancel.Enabled = True
  EmplRS.MoveFirst
  Do While Not EmplRS.EOF
    lstTempID.AddItem "(" + EmplRS.Fields("Employee_ID").Value + ") " + EmplRS.Fields("Employee_Name").Value
    EmplRS.MoveNext
  Loop
End Sub

Private Sub lstTempID_Click()
  txtPermID.Enabled = True
  txtPermID.SetFocus
End Sub

'****************************************
'To Lock the Table before updating
'****************************************
Private Sub LockTbl(TblName As String)
  'Lock the table in Exclusive mode before updating
  Dim myLockSQL As String, i As Integer
  For i = 1 To 5
    OraDatabase.LastServerErrReset
    myLockSQL = "LOCK TABLE " + TblName + " IN EXCLUSIVE MODE NOWAIT"
    OraDatabase.ExecuteSQL myLockSQL
    If OraDatabase.LastServerErr = 0 Then Exit For
  Next
  If OraDatabase.LastServerErr <> 0 Then
    OraDatabase.LastServerErr
    MsgBox "Table could not be locked. Please Try Again", vbInformation, "Locking Failure"
    Exit Sub
  End If
End Sub
