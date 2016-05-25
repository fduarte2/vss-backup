VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{8D650141-6025-11D1-BC40-0000C042AEC0}#3.0#0"; "ssdw3b32.ocx"
Begin VB.Form frmMasterMaint 
   BorderStyle     =   1  'Fixed Single
   ClientHeight    =   7350
   ClientLeft      =   45
   ClientTop       =   330
   ClientWidth     =   8850
   Icon            =   "frmMasterMaint.frx":0000
   LinkTopic       =   "Form1"
   MaxButton       =   0   'False
   MinButton       =   0   'False
   ScaleHeight     =   7350
   ScaleWidth      =   8850
   StartUpPosition =   3  'Windows Default
   Begin SSDataWidgets_B.SSDBGrid SSDBGrid1 
      Height          =   5655
      Left            =   120
      TabIndex        =   2
      Top             =   1080
      Width           =   8655
      _Version        =   196616
      DataMode        =   2
      Cols            =   1
      BeginProperty HeadFont {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      FieldSeparator  =   "!"
      AllowAddNew     =   -1  'True
      AllowDelete     =   -1  'True
      BackColorOdd    =   16761024
      RowHeight       =   503
      Columns(0).Width=   3200
      _ExtentX        =   15266
      _ExtentY        =   9975
      _StockProps     =   79
      Caption         =   "MASTER FILE MAINTANENCE"
      BeginProperty Font {0BE35203-8F91-11CE-9DE3-00AA004BB851} 
         Name            =   "MS Sans Serif"
         Size            =   9.75
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
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
      Left            =   7440
      TabIndex        =   1
      ToolTipText     =   "Return Back"
      Top             =   6840
      Width           =   1335
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
      Picture         =   "frmMasterMaint.frx":0442
   End
   Begin VB.Line Line2 
      X1              =   0
      X2              =   8760
      Y1              =   960
      Y2              =   960
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
      Left            =   840
      TabIndex        =   0
      Top             =   0
      Width           =   7935
   End
End
Attribute VB_Name = "frmMasterMaint"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit      '5/2/2007 HD2759 Rudy:

Dim OriginalVal As String
Dim CommRS As Object, SubTypeRS As Object, LocRS As Object, CustRS As Object
Dim VessRS As Object, EquipRS As Object, SvcRS As Object, EarnRS As Object, TypeRS As Object

'****************************************
'To Close the Current Form
'****************************************
Private Sub cmdExit_Click()
  Dim mySQL As String
  'Delete any Blank Records before Exit
  OraSession.BeginTrans
  Select Case UCase(Trim(MasterName))
  Case "COMMODITY"
    mySQL = "Delete from Commodity Where Commodity_Code = 0 and Commodity_Name is null"
  Case "SUBTYPE"
    mySQL = "Delete from Employee_Sub_Type where Employee_Sub_Type_ID is null"
  Case "LOCATION_CATEGORY"
    mySQL = "Delete From Location_Category Where Location_ID is null"
  Case "CUSTOMER"
    mySQL = "Delete from Customer where Customer_ID = 0 and Customer_Name is null"
  Case "VESSEL"
    mySQL = "Delete from Vessel where Vessel_ID = 0 and Vessel_Name is null"
  Case "EQUIPMENT_PROFILE"
    mySQL = "Delete from Equipment where Equipment_ID = 0 and Equipment_Description is null"
  Case "SERVICE"
    mySQL = "Delete from Service where Service_Code = 0 and Service_Name is null"
  Case "EARNING_TYPE"
    mySQL = "Delete from Earning_Type where Earning_Type_ID is null"
  Case "EMPLOYEE_TYPE"
    mySQL = "Delete from Employee_Type where Employee_Type_ID is null"
  End Select
  Dim myrec As Integer
  myrec = OraDatabase.ExecuteSQL(mySQL)
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    OraSession.Rollback
  End If
  
  Unload Me
End Sub

'****************************************
'To Validate Entry in the Grid
'****************************************
Private Sub SSDBGrid1_AfterColUpdate(ByVal ColIndex As Integer)
  If Trim(SSDBGrid1.Columns(ColIndex).Value) = vbNullString Then
    MsgBox "Blank value not allowed", vbInformation, "Data Required"
    SSDBGrid1.Columns(ColIndex).Value = OriginalVal
  End If
End Sub

'****************************************
'To Add / Edit a record in the Appropriate Master
'****************************************
Private Sub SSDBGrid1_AfterUpdate(RtnDispErrMsg As Integer)
  On Error Resume Next
  OraSession.BeginTrans       'Begin the Transaction
  Select Case UCase(Trim(MasterName))
    Case "COMMODITY"
      CommRS.MoveFirst
      CommRS.MoveRel SSDBGrid1.row
      If Trim(CommRS.Fields("Commodity_Code").Value) = vbNullString Or IsNull(CommRS.Fields("Commodity_Code").Value) Then
        'Check for Duplicate Data while Adding New Record
        CommRS.MoveFirst
        CommRS.DBFindFirst "Commodity_Code = " + Trim(Str(SSDBGrid1.Columns(0).Value))
        If CommRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Commodity")    'Lock the Table
          CommRS.AddNew
          CommRS.Fields("Commodity_Code").Value = SSDBGrid1.Columns(0).Value
          CommRS.Fields("Commodity_Name").Value = SSDBGrid1.Columns(1).Value
          CommRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        CommRS.DBFindFirst "Commodity_Code = " + Trim(Str(CommRS.Fields("Commodity_Code").Value))
        If CommRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            CommRS.MoveFirst
            CommRS.DBFindFirst "Commodity_Code = " + Trim(Str(SSDBGrid1.Columns(0).Value))
            If CommRS.NoMatch Then
            'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Commodity")    'Lock the Table
              CommRS.Edit
              CommRS.Fields("Commodity_Code").Value = SSDBGrid1.Columns(0).Value
              CommRS.Fields("Commodity_Name").Value = SSDBGrid1.Columns(1).Value
              CommRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Commodity")    'Lock the Table
            CommRS.Edit
            CommRS.Fields("Commodity_Code").Value = SSDBGrid1.Columns(0).Value
            CommRS.Fields("Commodity_Name").Value = SSDBGrid1.Columns(1).Value
            CommRS.Update
          End If
        End If
      End If
      CommRS.Close
      Set CommRS = Nothing
    Case "SUBTYPE"
      SubTypeRS.MoveFirst
      SubTypeRS.MoveRel SSDBGrid1.row
      If Trim(SubTypeRS.Fields("Employee_Sub_Type_ID").Value) = vbNullString Or IsNull(SubTypeRS.Fields("Employee_Sub_Type_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        SubTypeRS.MoveFirst
        SubTypeRS.DBFindFirst "Upper(Employee_Sub_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
        If SubTypeRS.NoMatch Then
          'New record = Add to the Database
          'Call LockTbl("Employee_Sub_Type")    'Lock the Table
          SubTypeRS.AddNew
          SubTypeRS.Fields("Employee_Sub_Type_ID").Value = SSDBGrid1.Columns(0).Value
          SubTypeRS.Fields("Sub_Type_Description").Value = SSDBGrid1.Columns(1).Value
          SubTypeRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
      'Find the Record in the Database and Edit it
        SubTypeRS.DBFindFirst "Upper(Employee_Sub_Type_ID) = '" + UCase(Trim(SubTypeRS.Fields("Employee_Sub_Type_ID").Value)) + "'"
        If SubTypeRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            SubTypeRS.MoveFirst
            SubTypeRS.DBFindFirst "Upper(Employee_Sub_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
            If SubTypeRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Employee_Sub_Type")    'Lock the Table
              SubTypeRS.Edit
              SubTypeRS.Fields("Employee_Sub_Type_ID").Value = SSDBGrid1.Columns(0).Value
              SubTypeRS.Fields("Sub_Type_Description").Value = SSDBGrid1.Columns(1).Value
              SubTypeRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Employee_Sub_Type")    'Lock the Table
            SubTypeRS.Edit
            SubTypeRS.Fields("Employee_Sub_Type_ID").Value = SSDBGrid1.Columns(0).Value
            SubTypeRS.Fields("Sub_Type_Description").Value = SSDBGrid1.Columns(1).Value
            SubTypeRS.Update
          End If
        End If
      End If
      SubTypeRS.Close
      Set SubTypeRS = Nothing
    Case "LOCATION_CATEGORY"
      'LocRS.MoveFirst
      'LocRS.MoveRel SSDBGrid1.Row
      If Trim(LocRS.Fields("Location_ID").Value) = vbNullString Or IsNull(LocRS.Fields("Location_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        LocRS.MoveFirst
        LocRS.DBFindFirst "Upper(Location_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
        If LocRS.NoMatch Then
        
          'New Record - Add to the Database
          'Call LockTbl("Location_Category")    'Lock the Table
          LocRS.AddNew
          LocRS.Fields("Location_ID").Value = SSDBGrid1.Columns(0).Value
          LocRS.Fields("Location_Description").Value = SSDBGrid1.Columns(1).Value
          LocRS.Update
        Else
          If UCase(Trim(LocRS.Fields("Location_description").Value)) <> UCase(Trim(SSDBGrid1.Columns(1).Value)) Then
            LocRS.Edit
            LocRS.Fields("Location_Description").Value = SSDBGrid1.Columns(1).Value
            LocRS.Update
          Else
            MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
            SSDBGrid1.Columns(0).Value = ""
            SSDBGrid1.Columns(1).Value = ""
          End If
        End If
      Else
        'Find the Record in the Database and Edit it
        LocRS.DBFindFirst "Upper(Location_ID) = '" + UCase(Trim(LocRS.Fields("Location_ID").Value)) + "'"
        If LocRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            LocRS.MoveFirst
            LocRS.DBFindFirst "Upper(Location_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
            If LocRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Location_Category")    'Lock the Table
              LocRS.Edit
              LocRS.Fields("Location_ID").Value = SSDBGrid1.Columns(0).Value
              LocRS.Fields("Location_Description").Value = SSDBGrid1.Columns(1).Value
              LocRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Location_Category")    'Lock the Table
            LocRS.Edit
            LocRS.Fields("Location_ID").Value = SSDBGrid1.Columns(0).Value
            LocRS.Fields("Location_Description").Value = SSDBGrid1.Columns(1).Value
            LocRS.Update
          End If
        End If
      End If
      LocRS.Close
      Set LocRS = Nothing
    Case "CUSTOMER"
      CustRS.MoveFirst
      CustRS.MoveRel SSDBGrid1.row
      If Trim(CustRS.Fields("Customer_ID").Value) = vbNullString Or IsNull(CustRS.Fields("Customer_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        CustRS.MoveFirst
        CustRS.DBFindFirst "Customer_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
        If CustRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Customer")    'Lock the Table
          CustRS.AddNew
          CustRS.Fields("Customer_ID").Value = SSDBGrid1.Columns(0).Value
          CustRS.Fields("Customer_Name").Value = SSDBGrid1.Columns(1).Value
          CustRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        CustRS.DBFindFirst "Customer_ID = " + Trim(Str(CustRS.Fields("Customer_ID").Value))
        If CustRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            CustRS.MoveFirst
            CustRS.DBFindFirst "Customer_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
            If CustRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Customer")    'Lock the Table
              CustRS.Edit
              CustRS.Fields("Customer_ID").Value = SSDBGrid1.Columns(0).Value
              CustRS.Fields("Customer_Name").Value = SSDBGrid1.Columns(1).Value
              CustRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Customer")    'Lock the Table
            CustRS.Edit
            CustRS.Fields("Customer_ID").Value = SSDBGrid1.Columns(0).Value
            CustRS.Fields("Customer_Name").Value = SSDBGrid1.Columns(1).Value
            CustRS.Update
          End If
        End If
      End If
      CustRS.Close
      Set CustRS = Nothing
    Case "VESSEL"
      VessRS.MoveFirst
      VessRS.MoveRel SSDBGrid1.row
      If Trim(VessRS.Fields("Vessel_ID").Value) = vbNullString Or IsNull(VessRS.Fields("Vessel_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        VessRS.MoveFirst
        VessRS.DBFindFirst "Vessel_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
        If VessRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Vessel")    'Lock the Table
          VessRS.AddNew
          VessRS.Fields("Vessel_ID").Value = SSDBGrid1.Columns(0).Value
          VessRS.Fields("Vessel_Name").Value = SSDBGrid1.Columns(1).Value
          VessRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        VessRS.DBFindFirst "Vessel_ID = " + Trim(Str(VessRS.Fields("Vessel_ID").Value))
        If VessRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            VessRS.MoveFirst
            VessRS.DBFindFirst "Vessel_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
            If VessRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Vessel")    'Lock the Table
              VessRS.Edit
              VessRS.Fields("Vessel_ID").Value = SSDBGrid1.Columns(0).Value
              VessRS.Fields("Vessel_Name").Value = SSDBGrid1.Columns(1).Value
              VessRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Vessel")    'Lock the Table
            VessRS.Edit
            VessRS.Fields("Vessel_ID").Value = SSDBGrid1.Columns(0).Value
            VessRS.Fields("Vessel_Name").Value = SSDBGrid1.Columns(1).Value
            VessRS.Update
          End If
        End If
      End If
      VessRS.Close
      Set VessRS = Nothing
    Case "EQUIPMENT_PROFILE"
      EquipRS.MoveFirst
      EquipRS.MoveRel SSDBGrid1.row
      If Trim(EquipRS.Fields("Equipment_ID").Value) = vbNullString Or IsNull(EquipRS.Fields("Equipment_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        EquipRS.MoveFirst
        EquipRS.DBFindFirst "Equipment_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
        If EquipRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Equipment_Profile")    'Lock the Table
          EquipRS.AddNew
          EquipRS.Fields("Equipment_ID").Value = SSDBGrid1.Columns(0).Value
          EquipRS.Fields("Equipment_Description").Value = SSDBGrid1.Columns(1).Value
          EquipRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        EquipRS.DBFindFirst "Equipment_ID = " + Trim(Str(EquipRS.Fields("Equipment_ID").Value))
        If EquipRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            EquipRS.MoveFirst
            EquipRS.DBFindFirst "Equipment_ID = " + Trim(Str(SSDBGrid1.Columns(0).Value))
            If EquipRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Equipment_Profile")    'Lock the Table
              EquipRS.Edit
              EquipRS.Fields("Equipment_ID").Value = SSDBGrid1.Columns(0).Value
              EquipRS.Fields("Equipment_Description").Value = SSDBGrid1.Columns(1).Value
              EquipRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Equipment_Profile")    'Lock the Table
            EquipRS.Edit
            EquipRS.Fields("Equipment_ID").Value = SSDBGrid1.Columns(0).Value
            EquipRS.Fields("Equipment_Description").Value = SSDBGrid1.Columns(1).Value
            EquipRS.Update
          End If
        End If
      End If
      EquipRS.Close
      Set EquipRS = Nothing
    Case "SERVICE"
      SvcRS.MoveFirst
      SvcRS.MoveRel SSDBGrid1.row
      If Trim(SvcRS.Fields("Service_Code").Value) = vbNullString Or IsNull(SvcRS.Fields("Service_Code").Value) Then
        'Check for Duplicate Data while Adding New Record
        SvcRS.MoveFirst
        SvcRS.DBFindFirst "Service_Code = " + Trim(Str(SSDBGrid1.Columns(0).Value))
        If SvcRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Service")    'Lock the Table
          SvcRS.AddNew
          SvcRS.Fields("Service_Code").Value = SSDBGrid1.Columns(0).Value
          SvcRS.Fields("Service_Name").Value = SSDBGrid1.Columns(1).Value
          If SSDBGrid1.Columns(2).Value = -1 Then
            SvcRS.Fields("Ship_Flag").Value = "Y"
          Else
            SvcRS.Fields("Ship_Flag").Value = "N"
          End If
'          If SvcRS.Fields.count > 5 Then        '5/2/2007 HD2759 Rudy:  new field exists
'            If SSDBGrid1.Columns(3).Value = -1 Then
'              SvcRS.Fields("COMMODITY_REQUIRED").Value = "Y"
'            Else
'              SvcRS.Fields("COMMODITY_REQUIRED").Value = "N"
'            End If
'          End If
'          'TESTED / UNTESTED
          
          SvcRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        SvcRS.DBFindFirst "Service_Code = " + Trim(Str(SvcRS.Fields("Service_Code").Value))
        If SvcRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            SvcRS.MoveFirst
            SvcRS.DBFindFirst "Service_Code = " + Trim(Str(SSDBGrid1.Columns(0).Value))
            If SvcRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Service")    'Lock the Table
              SvcRS.Edit
              SvcRS.Fields("Service_Code").Value = SSDBGrid1.Columns(0).Value
              SvcRS.Fields("Service_Name").Value = SSDBGrid1.Columns(1).Value
              If SSDBGrid1.Columns(2).Value = -1 Then
                SvcRS.Fields("Ship_Flag").Value = "Y"
              Else
                SvcRS.Fields("Ship_Flag").Value = "N"
              End If
'              If SvcRS.Fields.count > 5 Then         '5/2/2007 HD2759 Rudy:  new field exists
'                If SSDBGrid1.Columns(3).Value = -1 Then
'                  SvcRS.Fields("COMMODITY_REQUIRED").Value = "Y"
'                Else
'                  SvcRS.Fields("COMMODITY_REQUIRED").Value = "N"
'                End If
'              End If
'              'TESTED / UNTESTED
              
              SvcRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Service")    'Lock the Table
            SvcRS.Edit
            SvcRS.Fields("Service_Code").Value = SSDBGrid1.Columns(0).Value
            SvcRS.Fields("Service_Name").Value = SSDBGrid1.Columns(1).Value
            If SSDBGrid1.Columns(2).Value = -1 Then
              SvcRS.Fields("Ship_Flag").Value = "Y"
            Else
              SvcRS.Fields("Ship_Flag").Value = "N"
            End If
                     
'            If SvcRS.Fields.count > 5 Then        '5/2/2007 HD2759 Rudy:  new field exists
'              If SSDBGrid1.Columns(3).Value = -1 Then
'                SvcRS.Fields("COMMODITY_REQUIRED").Value = "Y"
'              Else
'                SvcRS.Fields("COMMODITY_REQUIRED").Value = "N"
'              End If
'            End If
'            'TESTED / UNTESTED
            
            SvcRS.Update
          End If
        End If
      End If
      SvcRS.Close
      Set SvcRS = Nothing
    Case "EARNING_TYPE"
      EarnRS.MoveFirst
      EarnRS.MoveRel SSDBGrid1.row
      If Trim(EarnRS.Fields("Earning_Type_ID").Value) = vbNullString Or IsNull(EarnRS.Fields("Earning_Type_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        EarnRS.MoveFirst
        EarnRS.DBFindFirst "Upper(Earning_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
        If EarnRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Earning_Type")    'Lock the Table
          EarnRS.AddNew
          EarnRS.Fields("Earning_Type_ID").Value = SSDBGrid1.Columns(0).Value
          EarnRS.Fields("Earning_Description").Value = SSDBGrid1.Columns(1).Value
          EarnRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        EarnRS.DBFindFirst "Upper(Earning_Type_ID) = '" + UCase(Trim(EarnRS.Fields("Earning_Type_ID").Value)) + "'"
        If EarnRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data While Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            EarnRS.MoveFirst
            EarnRS.DBFindFirst "Upper(Earning_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
            If EarnRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Earning_Type")    'Lock the Table
              EarnRS.Edit
              EarnRS.Fields("Earning_Type_ID").Value = SSDBGrid1.Columns(0).Value
              EarnRS.Fields("Earning_Description").Value = SSDBGrid1.Columns(1).Value
              EarnRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Earning_Type")    'Lock the Table
            EarnRS.Edit
            EarnRS.Fields("Earning_Type_ID").Value = SSDBGrid1.Columns(0).Value
            EarnRS.Fields("Earning_Description").Value = SSDBGrid1.Columns(1).Value
            EarnRS.Update
          End If
        End If
      End If
      EarnRS.Close
      Set EarnRS = Nothing
    Case "EMPLOYEE_TYPE"
      TypeRS.MoveFirst
      TypeRS.MoveRel SSDBGrid1.row
      If Trim(TypeRS.Fields("Employee_Type_ID").Value) = vbNullString Or IsNull(TypeRS.Fields("Employee_Type_ID").Value) Then
        'Check for Duplicate Data while Adding New Record
        TypeRS.MoveFirst
        TypeRS.DBFindFirst "Upper(Employee_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
        If TypeRS.NoMatch Then
          'New Record - Add to the Database
          'Call LockTbl("Employee_Type")    'Lock the Table
          TypeRS.AddNew
          TypeRS.Fields("Employee_Type_ID").Value = SSDBGrid1.Columns(0).Value
          TypeRS.Fields("Type_Description").Value = SSDBGrid1.Columns(1).Value
          TypeRS.Update
        Else
          MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
          SSDBGrid1.Columns(0).Value = ""
          SSDBGrid1.Columns(1).Value = ""
        End If
      Else
        'Find the Record in the Database and Edit it
        TypeRS.DBFindFirst "Upper(Employee_Type_ID) = '" + UCase(Trim(TypeRS.Fields("Employee_Type_ID").Value)) + "'"
        If TypeRS.NoMatch Then
        'Do Nothing
        Else
          'Check For Duplicate Data while Editing Existing Record
          If SSDBGrid1.Col = 0 Then   'Check only in Primary Column
            TypeRS.MoveFirst
            TypeRS.DBFindFirst "Upper(Employee_Type_ID) = '" + UCase(Trim(SSDBGrid1.Columns(0).Value)) + "'"
            If TypeRS.NoMatch Then
              'No Duplicates - Edit and Update the Changes to the Current Record
              'Call LockTbl("Employee_Type")    'Lock the Table
              TypeRS.Edit
              TypeRS.Fields("Employee_Type_ID").Value = SSDBGrid1.Columns(0).Value
              TypeRS.Fields("Type_Description").Value = SSDBGrid1.Columns(1).Value
              TypeRS.Update
            Else
              MsgBox "Duplicate Data Not Allowed!", vbInformation, "Duplicate Record"
              SSDBGrid1.Columns(0).Value = OriginalVal
            End If
          Else
            'Duplicate Entries are allowed in Description Column
            'Call LockTbl("Employee_Type")    'Lock the Table
            TypeRS.Edit
            TypeRS.Fields("Employee_Type_ID").Value = SSDBGrid1.Columns(0).Value
            TypeRS.Fields("Type_Description").Value = SSDBGrid1.Columns(1).Value
            TypeRS.Update
          End If
        End If
      End If
      TypeRS.Close
      Set TypeRS = Nothing
  End Select
  
  'Commit or RollBack the Transaction
  If OraDatabase.LastServerErr = 0 Then
    OraSession.CommitTrans
  Else
    MsgBox "Oracle error: " & OraDatabase.LastServerErrText & ", rolling back changes."     '5/2/2007 HD2759 Rudy:
    
    OraSession.Rollback
  End If
End Sub

'****************************************
'To Add / Edit a record in the Appropriate Master
'****************************************
Private Sub SSDBGrid1_BeforeColUpdate(ByVal ColIndex As Integer, ByVal OldValue As Variant, Cancel As Integer)
  OriginalVal = OldValue
End Sub

'****************************************
'To Delete a record from the Appropriate Master
'****************************************
Private Sub SSDBGrid1_BeforeDelete(Cancel As Integer, DispPromptMsg As Integer)
  DispPromptMsg = 0   'Not to Display System Message
  Dim DelConfirm As Integer
  DelConfirm = MsgBox("Are you sure to delete this Data from Master?", vbYesNo + vbQuestion, "Confirm Delete")
  If DelConfirm = vbYes Then
    OraSession.BeginTrans   'Begin the Transaction
    'Continue with the Deletion Process
    Dim myDelSQL As String
    Select Case UCase(Trim(MasterName))
      Case "COMMODITY"
        myDelSQL = "Delete from Commodity where Commodity_Code = " + Str(SSDBGrid1.Columns(0).Value)
      Case "SUBTYPE"
        myDelSQL = "Delete from Employee_Sub_Type where Employee_Sub_Type_ID = '" + Trim(SSDBGrid1.Columns(0).Value) + "'"
      Case "LOCATION_CATEGORY"
        myDelSQL = "Delete from Location_Category where Location_ID = '" + Trim(SSDBGrid1.Columns(0).Value) + "'"
      Case "CUSTOMER"
        myDelSQL = "Delete from Customer where Customer_ID = " + Str(SSDBGrid1.Columns(0).Value)
      Case "VESSEL"
        myDelSQL = "Delete from Vessel where Vessel_ID = " + Str(SSDBGrid1.Columns(0).Value)
      Case "EQUIPMENT_PROFILE"
        myDelSQL = "Delete from Equipment where Equipment_ID = " + Str(SSDBGrid1.Columns(0).Value)
      Case "SERVICE"
        myDelSQL = "Delete from Service where Service_Code = " + Str(SSDBGrid1.Columns(0).Value)
      Case "EARNING_TYPE"
        myDelSQL = "Delete from Earning_Type where Earning_Type_ID = '" + Trim(SSDBGrid1.Columns(0).Value) + "'"
      Case "EMPLOYEE_TYPE"
        myDelSQL = "Delete from Employee_Type where Employee_Type_ID = '" + Trim(SSDBGrid1.Columns(0).Value) + "'"
    End Select
    
    OraDatabase.ExecuteSQL myDelSQL
    
    'Commit or Roll Back the Transaction
    If OraDatabase.LastServerErr = 0 Then
      OraSession.CommitTrans
    Else
      MsgBox "Oracle error: " & OraDatabase.LastServerErrText & ", rolling back changes."     '5/2/2007 HD2759 Rudy:
      
      OraSession.Rollback
    End If
  Else
    Cancel = True
  End If
  
End Sub

Private Sub SSDBGrid1_Error(ByVal DataError As Integer, Response As Integer)
  If DataError = 6153 And Trim(SSDBGrid1.Columns(0).Value) <> "" Then
    'Msg Box "Duplicate Value Not Allowed", vbInformation, "Up dation Failure"
    MsgBox "Duplicate Value Not Allowed", vbInformation, "Duplicates"
    SSDBGrid1.Columns(0) = vbNullString
  ElseIf DataError = 6153 And Trim(SSDBGrid1.Columns(0).Value) = "" Then
    'Msg Box "Blank Value Not Allowed", vbInformation, "Up dation Failure"
    MsgBox "Blank Value Not Allowed", vbInformation, "Insufficient Data"
 ' ElseIf DataError = 7007 Or DataError = 6154 Then
  End If
  Response = 0
End Sub

Private Sub Form_Load()
  'Center the Form
  Me.Top = (Screen.Height - Me.Height) / 2
  Me.Left = (Screen.Width - Me.Width) / 2
  Label6.Caption = "The Port of Wilmington, Delaware" + Chr(13) + "LCS"
End Sub

Private Sub Form_QueryUnload(Cancel As Integer, UnloadMode As Integer)
  On Error GoTo ErrHandler
  If ValidateColumn = True Then
    'Do Nothing - Validation Failed
    SSDBGrid1.SetFocus
    Cancel = True
  Else
    Unload Me
    frmMaster.Show
  End If
  Exit Sub
ErrHandler:
  If Err.Number = -2147217900 Then
    'Msg Box "Duplicate Value Not Allowed", vbInformation, "Up dation Failure"
    MsgBox "Duplicate Value Not Allowed", vbInformation, "Duplicates"
    Unload Me
    frmMaster.Show
  ElseIf Err.Number = -2147467259 Then    'Can't in sert an empty row
    Unload Me
    frmMaster.Show
  End If
End Sub

'****************************************
'To Validate Columns in the Grid
'****************************************
Private Function ValidateColumn() As Boolean
  On Error Resume Next
  Select Case UCase(Trim(MasterName))
    Case "COMMODITY"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Commodity Code", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Commodity Code", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Commodity Description", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Commodity Description", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "SUBTYPE"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Employee Sub Type ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Employee Sub Type ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Employee Sub Type Description", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Employee Sub Type Description", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
      
    Case "LOCATION_CATEGORY"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Category Code", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Category Code", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Category Name", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Category Name", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "CUSTOMER"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Customer ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Customer ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Customer Name", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Customer Name", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "VESSEL"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Vessel ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Vessel ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Vessel Name", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Vessel Name", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "EQUIPMENT_PROFILE"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Equipment ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Equipment ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Equipment Description", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Equipment Description", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "SERVICE"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Service Code", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Service Code", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Service Name", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Service Name", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If

    Case "EARNING_TYPE"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Earning Type ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Earning Type ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Earning Description", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Earning Description", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
    
    Case "EMPLOYEE_TYPE"
      If Trim(SSDBGrid1.Columns(0).Value) = vbNullString Then
        'Msg Box "Please Enter Employee Type ID", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Employee Type ID", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 0
        ValidateColumn = True
      ElseIf Trim(SSDBGrid1.Columns(1).Value) = vbNullString Then
        'Msg Box "Please Enter Type Description", vbInformation, "Up dation Failure"
        MsgBox "Please Enter Employee Type Description", vbInformation, "Insufficient Data"
        SSDBGrid1.Col = 1
        ValidateColumn = True
      End If
  End Select
End Function

Private Sub Form_Terminate()
  'Set CommRS = Nothing
  'Set SubTypeRS = Nothing
  'Set VessRS = Nothing
  'Set CustRS = Nothing
  'Set LocRS = Nothing
  'Set EquipRS = Nothing
  'Set SvcRS = Nothing
  'Set EarnRS = Nothing
  'Set TypeRS = Nothing
End Sub

'****************************************
'To Populate Values in the Grid
'****************************************
Private Sub SSDBGrid1_InitColumnProps()
  SSDBGrid1.Columns.RemoveAll
  SSDBGrid1.Columns.add 0
  SSDBGrid1.Columns.add 1

  Select Case UCase(Trim(MasterName))
    Case "COMMODITY"
      SSDBGrid1.Columns(0).Caption = "Commodity Code"
      SSDBGrid1.Columns(1).Caption = "Commodity Name"
      Set CommRS = OraDatabase.DBCreateDynaset("Select * from Commodity Order by Commodity_Code", 0&)
      If CommRS.EOF And CommRS.BOF Then
      'Do Nothing
      Else
        CommRS.MoveFirst
        Do While Not CommRS.EOF
          SSDBGrid1.AddItem CommRS.Fields("Commodity_Code").Value & "!" & CommRS.Fields("Commodity_Name").Value
          CommRS.MoveNext
        Loop
      End If
    Case "SUBTYPE"
      SSDBGrid1.Columns(0).Caption = "Employee Sub Type"
      SSDBGrid1.Columns(1).Caption = "Sub Type Description"
      Set SubTypeRS = OraDatabase.DBCreateDynaset("Select * from Employee_Sub_Type Order by Employee_Sub_Type_ID", 0&)
      If SubTypeRS.EOF And SubTypeRS.BOF Then
      'Do Nothing
      Else
        SubTypeRS.MoveFirst
        Do While Not SubTypeRS.EOF
          SSDBGrid1.AddItem SubTypeRS.Fields("Employee_Sub_Type_ID").Value & "!" & SubTypeRS.Fields("Sub_Type_Description").Value
          SubTypeRS.MoveNext
        Loop
      End If
    Case "LOCATION_CATEGORY"
      SSDBGrid1.Columns(0).Caption = "Category Code"
      SSDBGrid1.Columns(1).Caption = "Category Name"
      Set LocRS = OraDatabase.DBCreateDynaset("Select * from Location_Category Order by Location_ID", 0&)
      If LocRS.EOF And LocRS.BOF Then
      'Do Nothing
      Else
        LocRS.MoveFirst
        Do While Not LocRS.EOF
          SSDBGrid1.AddItem LocRS.Fields("Location_ID").Value & "!" & LocRS.Fields("Location_Description").Value
          LocRS.MoveNext
        Loop
      End If
    Case "CUSTOMER"
      SSDBGrid1.Columns(0).Caption = "Customer ID"
      SSDBGrid1.Columns(1).Caption = "Customer Name"
      Set CustRS = OraDatabase.DBCreateDynaset("Select * from Customer Order by Customer_ID", 0&)
      If CustRS.EOF And CustRS.BOF Then
      'Do Nothing
      Else
        CustRS.MoveFirst
        Do While Not CustRS.EOF
          SSDBGrid1.AddItem CustRS.Fields("Customer_ID").Value & "!" & CustRS.Fields("Customer_Name").Value
          CustRS.MoveNext
        Loop
      End If
    Case "VESSEL"
      SSDBGrid1.Columns(0).Caption = "Vessel ID"
      SSDBGrid1.Columns(1).Caption = "Vessel Name"
      Set VessRS = OraDatabase.DBCreateDynaset("Select * from Vessel Order by Vessel_ID", 0&)
      If VessRS.EOF And VessRS.BOF Then
      'Do Nothing
      Else
        VessRS.MoveFirst
        Do While Not VessRS.EOF
          SSDBGrid1.AddItem VessRS.Fields("Vessel_ID").Value & "!" & VessRS.Fields("Vessel_Name").Value
          VessRS.MoveNext
        Loop
      End If
    Case "EQUIPMENT_PROFILE"
      SSDBGrid1.Columns(0).Caption = "Equipment ID"
      SSDBGrid1.Columns(1).Caption = "Equipment Description"
      Set EquipRS = OraDatabase.DBCreateDynaset("Select * from Equipment Order by Equipment_ID", 0&)
      If EquipRS.EOF And EquipRS.BOF Then
      'Do Nothing
      Else
        EquipRS.MoveFirst
        Do While Not EquipRS.EOF
          SSDBGrid1.AddItem EquipRS.Fields("Equipment_ID").Value & "!" & EquipRS.Fields("Equipment_Description").Value
          EquipRS.MoveNext
        Loop
      End If
    Case "SERVICE"
      SSDBGrid1.Columns(0).Caption = "Service Code"
      SSDBGrid1.Columns(1).Caption = "Service Name"
      SSDBGrid1.Columns.add 2
      SSDBGrid1.Columns(2).Caption = "Ship Flag"
      SSDBGrid1.Columns(2).Style = ssStyleCheckBox
      SSDBGrid1.Columns(1).Width = 4514.024
      SSDBGrid1.Columns(2).Width = 1049.953
      
'      '5/2/2007 HD2759 Rudy:
'      SSDBGrid1.Columns(3).Caption = "Comm. Req"
'      SSDBGrid1.Columns(3).Style = ssStyleCheckBox
'      SSDBGrid1.Columns(3).Width = 1049.953
'      Dim strAddItem As String
      
      Set SvcRS = OraDatabase.DBCreateDynaset("Select * from Service Order by Service_Code", 0&)
      If SvcRS.EOF And SvcRS.BOF Then
      'Do Nothing
      Else
        SvcRS.MoveFirst

        Do While Not SvcRS.EOF
          If UCase(SvcRS.Fields("Ship_Flag").Value) = "Y" Then
            SSDBGrid1.AddItem SvcRS.Fields("Service_Code").Value & "!" & SvcRS.Fields("Service_Name").Value & "!" & "-1"
            'strAddItem = SvcRS.Fields("Service_Code").Value & "!" & SvcRS.Fields("Service_Name").Value & "!" & "-1"
          Else
            SSDBGrid1.AddItem SvcRS.Fields("Service_Code").Value & "!" & SvcRS.Fields("Service_Name").Value & "!" & "0"
            'strAddItem = SvcRS.Fields("Service_Code").Value & "!" & SvcRS.Fields("Service_Name").Value & "!" & "0"
          End If
          
'          '5/2/2007 HD2759 Rudy:
'          If SvcRS.Fields.count > 5 Then        'new field exists
'            If UCase(SvcRS.Fields("COMMODITY_REQUIRED").Value) = "Y" Then
'              strAddItem = strAddItem & "!" & "-1"
'            Else
'              strAddItem = strAddItem & "!" & "0"
'            End If
''          End If
'          SSDBGrid1.AddItem strAddItem
          
          SvcRS.MoveNext
        Loop
      End If
    Case "EARNING_TYPE"
      SSDBGrid1.Columns(0).Caption = "Earning Type ID"
      SSDBGrid1.Columns(1).Caption = "Earning Description"
      Set EarnRS = OraDatabase.DBCreateDynaset("Select * from Earning_Type Order by Earning_Type_ID", 0&)
      If EarnRS.EOF And EarnRS.BOF Then
      'Do Nothing
      Else
        EarnRS.MoveFirst
        Do While Not EarnRS.EOF
          SSDBGrid1.AddItem EarnRS.Fields("Earning_Type_ID").Value & "!" & EarnRS.Fields("Earning_Description").Value
          EarnRS.MoveNext
        Loop
      End If
    Case "EMPLOYEE_TYPE"
      SSDBGrid1.Columns(0).Caption = "Employee Type ID"
      SSDBGrid1.Columns(1).Caption = "Type Description"
      Set TypeRS = OraDatabase.DBCreateDynaset("Select * from Employee_Type Order by Employee_Type_ID", 0&)
      If TypeRS.EOF And TypeRS.BOF Then
      'Do Nothing
      Else
        TypeRS.MoveFirst
        Do While Not TypeRS.EOF
          SSDBGrid1.AddItem TypeRS.Fields("Employee_Type_ID").Value & "!" & TypeRS.Fields("Type_Description").Value
          TypeRS.MoveNext
        Loop
      End If
  End Select
End Sub

'****************************************
'To Lock the Specified Table for updating
'****************************************
Private Sub LockTbl(TblName As String)
  'Lock the table in Exclusive mode before Updating
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
