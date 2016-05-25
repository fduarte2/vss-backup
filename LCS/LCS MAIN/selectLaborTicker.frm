VERSION 5.00
Object = "{0D452EE1-E08F-101A-852E-02608C4D0BB4}#2.0#0"; "FM20.DLL"
Object = "{00025600-0000-0000-C000-000000000046}#5.2#0"; "crystl32.ocx"
Begin VB.Form frmSelectLaborTicket 
   Caption         =   "Labor Ticket"
   ClientHeight    =   4020
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   6690
   LinkTopic       =   "Form1"
   ScaleHeight     =   4020
   ScaleWidth      =   6690
   StartUpPosition =   3  'Windows Default
   Begin VB.TextBox txtNo 
      Height          =   330
      Left            =   3360
      TabIndex        =   4
      Top             =   1800
      Width           =   1695
   End
   Begin VB.CommandButton cmdExit 
      Caption         =   "E&XIT"
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   3360
      TabIndex        =   2
      ToolTipText     =   "Return Back"
      Top             =   2880
      Width           =   2295
   End
   Begin VB.CommandButton cmdShowRpt 
      Caption         =   "&PRINT REPORT"
      Default         =   -1  'True
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   12
         Charset         =   0
         Weight          =   700
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   480
      Left            =   720
      TabIndex        =   1
      ToolTipText     =   "Show Report for the Selected Date"
      Top             =   2880
      Width           =   2295
   End
   Begin Crystal.CrystalReport crw1 
      Left            =   120
      Top             =   4440
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   348160
      WindowState     =   2
      PrintFileLinesPerPage=   60
   End
   Begin VB.Label Label2 
      Alignment       =   1  'Right Justify
      Caption         =   "Labor Ticket#"
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
      Left            =   1080
      TabIndex        =   3
      Top             =   1800
      Width           =   1935
   End
   Begin VB.Line Line1 
      X1              =   0
      X2              =   6720
      Y1              =   960
      Y2              =   960
   End
   Begin VB.Label Label6 
      Alignment       =   2  'Center
      BeginProperty Font 
         Name            =   "MS Sans Serif"
         Size            =   13.5
         Charset         =   0
         Weight          =   400
         Underline       =   0   'False
         Italic          =   0   'False
         Strikethrough   =   0   'False
      EndProperty
      Height          =   855
      Left            =   1080
      TabIndex        =   0
      Top             =   0
      Width           =   5655
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
      Picture         =   "selectLaborTicker.frx":0000
   End
End
Attribute VB_Name = "frmSelectLaborTicket"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim rs As Object

Private Sub cmdExit_Click()
    Unload Me
    frmHiring.Show
End Sub

Private Sub cmdShowRpt_Click()
    Dim vessel_name As String
    Dim vessel_id As String
    Dim service As String
    Dim ser_name As String
    Dim sql As String
    Dim sqlStmt As String
    
    If Not IsNumeric(txtNo.Text) Then
        MsgBox "Invalid Labor ticket num!."
        Exit Sub
    End If
    
    sqlStmt = " SELECT * FROM LABOR_TICKET, LABOR_TICKET_HEADER, COMMODITY, CUSTOMER  " & _
             " WHERE LABOR_TICKET_HEADER.TICKET_NUM = " & txtNo.Text & _
             " AND LABOR_TICKET.TICKET_NUM = LABOR_TICKET_HEADER.TICKET_NUM AND COMMODITY.COMMODITY_CODE = " & _
             " LABOR_TICKET_HEADER.COMMODITY_CODE AND " & _
             " CUSTOMER.CUSTOMER_ID = LABOR_TICKET_HEADER.CUSTOMER_ID " & _
             " ORDER BY LABOR_TICKET.EMP_TYPE, LABOR_TICKET.START_TIME"
             
             'ORDER BY SERVICE_TYPE.ID, LABOR_TICKET.START_TIME
            'SERVICE_TYPE.SERVICE_TYPE=LABOR_TICKET.EMP_TYPE AND
    sql = "select vessel_id, service_group from labor_ticket_header where ticket_num = " & txtNo.Text
    
    Set rs = OraDatabase.DBCreateDynaset(sql, 0&)
    If OraDatabase.LastServerErr = 0 Then
        If Not rs.EOF And Not IsNull(rs.Fields("vessel_id")) And Not IsNull(rs.Fields("service_group")) Then
            vessel_id = rs.Fields("vessel_id").Value
            service = rs.Fields("service_group").Value
            vessel_name = getVesselName(vessel_id)
            ser_name = getServiceGroup(service)
                
            crw1.Connect = "DSN = LCS;UID = LABOR;PWD = LABOR"
            crw1.ReportFileName = App.Path + "\Laborticket.rpt"
            crw1.Formulas(0) = "service = '" + ser_name + "'"
            crw1.Formulas(1) = "vessel_name = '" + vessel_name + "'"
            crw1.DiscardSavedData = True
            crw1.SQLQuery = sqlStmt
            crw1.Action = 1
        Else
            MsgBox "Invalid Labor ticket num!."
        End If
    Else
        OraDatabase.LastServerErrReset
    End If
End Sub
Private Function getVesselName(id As String) As String
    Dim dsVESSEL As Object
    Dim sqlStmt1 As String
    Dim OraDatabase2 As Object
    
    'Set OraDatabase2 = OraSession.Open Database("BNI", "SAG_OWNER/SAG", 0&)
    'Set OraDatabase2 = OraSession.Open Database("BNI.DEV", "SAG_OWNER/SAG_DEV", 0&)  '2853 3/29/2007 Rudy: for testing, orig above
    Set OraDatabase2 = OraSession.OpenDatabase(DB, Login, 0&)  '5/2/2007 HD2759 Rudy: one init, orig above  TESTED / UNTESTED

    sqlStmt1 = "SELECT VESSEL_NAME FROM VESSEL_profile WHERE LR_NUM=" & id
    Set dsVESSEL = OraDatabase2.CreateDynaset(sqlStmt1, 0&)
    If dsVESSEL.RecordCount > 0 Then
        getVesselName = dsVESSEL.Fields("VESSEL_NAME").Value
    Else
        getVesselName = CStr(id)
    End If
        
    dsVESSEL.Close
    Set dsVESSEL = Nothing
    OraDatabase2.Close
    Set OraDatabase2 = Nothing
End Function

Private Function getServiceGroup(service As String) As String
    Dim rsService As Object
    Dim sqlStmt As String
    
    sqlStmt = " SELECT DISTINCT  SUBSTR(SERVICE_NAME, 6, INSTR(SERVICE_NAME, '/')-6) SERVICE " & _
              " FROM SERVICE  WHERE SUBSTR(SERVICE_NAME, 6, INSTR(SERVICE_NAME, '/')-6) IS NOT NULL AND SUBSTR(SERVICE_CODE, 1, 3) = " & service
    Set rsService = OraDatabase.CreateDynaset(sqlStmt, 0&)
    If OraDatabase.LastServerErr = 0 And rsService.RecordCount > 0 Then
        getServiceGroup = rsService.Fields("SERVICE").Value
    End If
    rsService.Close
    Set rsService = Nothing
End Function
