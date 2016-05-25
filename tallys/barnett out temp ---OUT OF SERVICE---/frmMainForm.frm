VERSION 5.00
Begin VB.Form frmMainForm 
   Caption         =   "Booking Paper Outbound Tally"
   ClientHeight    =   5130
   ClientLeft      =   60
   ClientTop       =   450
   ClientWidth     =   7110
   LinkTopic       =   "Form1"
   ScaleHeight     =   5130
   ScaleWidth      =   7110
   StartUpPosition =   3  'Windows Default
   Begin VB.Frame Frame2 
      Height          =   1815
      Left            =   240
      TabIndex        =   6
      Top             =   3120
      Width           =   6615
      Begin VB.CommandButton cmdPrint 
         Caption         =   "Print"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   720
         TabIndex        =   8
         Top             =   240
         Width           =   5175
      End
      Begin VB.CommandButton cmdClose 
         Caption         =   "Close"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   615
         Left            =   720
         TabIndex        =   7
         Top             =   960
         Width           =   5175
      End
   End
   Begin VB.Frame Frame1 
      Height          =   2775
      Left            =   240
      TabIndex        =   0
      Top             =   240
      Width           =   6615
      Begin VB.TextBox txtOrderNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3000
         TabIndex        =   1
         Top             =   960
         Width           =   2655
      End
      Begin VB.TextBox txtCustNum 
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   12
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   495
         Left            =   3000
         TabIndex        =   2
         Top             =   1680
         Width           =   2655
      End
      Begin VB.Label Label1 
         AutoSize        =   -1  'True
         Caption         =   "Order Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   5
         Top             =   1080
         Width           =   1470
      End
      Begin VB.Label Label2 
         AutoSize        =   -1  'True
         Caption         =   "Customer Number"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   9.75
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         Height          =   240
         Left            =   360
         TabIndex        =   4
         Top             =   1800
         Width           =   1860
      End
      Begin VB.Label Label4 
         Alignment       =   2  'Center
         AutoSize        =   -1  'True
         Caption         =   "Barnett Outbound Tally"
         BeginProperty Font 
            Name            =   "MS Sans Serif"
            Size            =   13.5
            Charset         =   0
            Weight          =   700
            Underline       =   0   'False
            Italic          =   0   'False
            Strikethrough   =   0   'False
         EndProperty
         ForeColor       =   &H000000FF&
         Height          =   360
         Left            =   1500
         TabIndex        =   3
         Top             =   360
         Width           =   3255
      End
   End
End
Attribute VB_Name = "frmMainForm"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
Option Explicit
Dim strBarcode As String
Dim dTotalWeightLB As Double
Dim dTotalWeightKG As Double
Dim dTotalPallets As Double
Dim dTotalRolls As Double

Private Sub cmdClose_Click()
    End
End Sub

Private Sub Form_Load()
    Call ClearAllFields
End Sub

Sub ClearAllFields()

    frmMainForm.txtCustNum.Text = "314"
    frmMainForm.txtOrderNum.Text = ""
    
End Sub

Private Sub cmdPrint_Click()
    ' I place the database definition files within this print routine, so that when the routine
    ' ends, it closes the connections along with the routine.  this prevents the Oracle Timeout error
    ' that occurs if the screen is left open for several hours.
        
    If txtOrderNum.Text = "" Or txtCustNum.Text = "" Then
        MsgBox "Both fields need to be entered"
        Exit Sub
    End If
    
    txtOrderNum.Text = UCase$(txtOrderNum.Text)

    Dim OraSession As Object
    Dim OraDatabase As Object
    
    On Error GoTo ErrHandler
    
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Set OraDatabase = OraSession.OpenDatabase("RFTEST", "SAG_OWNER/RFTEST238", 0&)
    Set OraDatabase = OraSession.OpenDatabase("RF", "SAG_OWNER/owner", 0&)
    If OraDatabase.LastServerErr <> 0 Then
        MsgBox "Database connection could not be made.  Please Contact TS."
        End
    End If

    strSql = "SELECT CUSTOMER_NAME FROM CUSTOMER_PROFILE WHERE CUSTOMER_ID = '" & txtCustNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If IsNull(dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value) Then
        MsgBox "Entered Customer (" & txtCustNum.Text & ") was not found in system."
        Exit Sub
    ElseIf txtCustNum.Text <> 314 Then
        MsgBox "This Program currently only designed for customer 314."
        Exit Sub
    End If
    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value


    strSql = "SELECT TO_CHAR(MIN(SHIPOUT_TIME), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(SHIPOUT_TIME), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM TEMP_BARNETT_DATA WHERE SHIPOUT_ORDER = '" & txtOrderNum.Text & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.RecordCount <= 0 Or IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value


    strSql = "SELECT LINSN_PALLET_ID, ROUND(TBD.MEAWD_WIDTH / UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(TBD.MEADI_DIAMETER / UC2.CONVERSION_FACTOR) THE_DIA, LINGC_PRODUCT_CODE, PRF_ORDER_NUM, " _
            & "MEALN_LENGTH, MEALN_LENGTH_MEAS, REFBM_BOL, DAMAGE_YN, ROUND(TBD.MEAGW_WEIGHT / UC3.CONVERSION_FACTOR) WEIGHT_LB, ROUND(TBD.MEAGW_WEIGHT / UC4.CONVERSION_FACTOR) WEIGHT_KG, NVL(TBD.BOOKING_NUM, 'NO BK') THE_BOOK " _
            & "FROM TEMP_BARNETT_DATA TBD, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
            & "WHERE SHIPOUT_ORDER = '" & txtOrderNum.Text & "' " _
            & "AND TBD.MEAWD_WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM' " _
            & "AND TBD.MEADI_DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM' " _
            & "AND TBD.MEAGW_WEIGHTUNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB' " _
            & "AND TBD.MEAGW_WEIGHTUNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
            & "ORDER BY LINSN_PALLET_ID, PRF_ORDER_NUM"
    Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)

    If dsMAIN_DATA.RecordCount = 0 Then
        MsgBox "No records for this Order / Customer combination"
        OraDatabase.Close
'        OraSession.Close
        Set OraDatabase = Nothing
        Set OraSession = Nothing
        Exit Sub
    Else
        dTotalWeightLB = 0
        dTotalWeightKG = 0
'        dTotalPallets = 0
        dTotalRolls = 0


        ' go to printer
        If LCase$(Environ$("USERNAME")) = "tally" Then
            Printer.Copies = 2
        Else
            Printer.Copies = 1
        End If

        '' Page Header
        Printer.Font = "Arial"
        Printer.FontBold = True
        Printer.Print ""
        Printer.FontSize = 11
        Printer.FontBold = True
        Printer.Print Tab(20); "PORT OF WILMINGTON TALLY - COMMERCIAL PAPER (Outbound)"
        Printer.Print ""
        Printer.Print ""
        Printer.Print Tab(10); "CUSTOMER: " & strCustName; Tab(65)
        Printer.Print Tab(10); "Printed On: " & Format(Now, "mm/dd/yyyy"); Tab(65); "START TIME: " & strStartTime
        Printer.Print Tab(10); "CONTAINER: " & txtOrderNum.Text; Tab(65); "END TIME: " & strEndTime
        Printer.Print ""
        Printer.FontSize = 8
        Printer.FontBold = False
        Printer.Print "BARCODE"; Tab(20); "QTY"; Tab(27); "Size (cm)"; Tab(38); "DIA (cm)"; Tab(49); "PRODUCT"; Tab(85); "ORDER"; Tab(95); "Linear Meas"; Tab(110); "Rec. Manifest"; Tab(125); "LB"; Tab(135); "KG"; Tab(145); "DMG"
'        Printer.Print Tab(1); "_______________________________________________________________________________________________________________________________________________________________________________"
        While (Not dsMAIN_DATA.EOF)
            Printer.Print dsMAIN_DATA.Fields("LINSN_PALLET_ID").Value; Tab(20); "1"; _
            Tab(27); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(38); dsMAIN_DATA.Fields("THE_DIA").Value; _
            Tab(49); dsMAIN_DATA.Fields("LINGC_PRODUCT_CODE").Value & " " & dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(85); dsMAIN_DATA.Fields("PRF_ORDER_NUM").Value; _
            Tab(95); dsMAIN_DATA.Fields("MEALN_LENGTH").Value & " " & dsMAIN_DATA.Fields("MEALN_LENGTH_MEAS").Value; _
            Tab(110); dsMAIN_DATA.Fields("REFBM_BOL").Value; Tab(125); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
            Tab(135); dsMAIN_DATA.Fields("WEIGHT_KG").Value; Tab(145); dsMAIN_DATA.Fields("DAMAGE_YN").Value

'            dTotalPallets = dTotalPallets + 1
            dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
            dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
            dTotalRolls = dTotalRolls + 1
            dsMAIN_DATA.MoveNext
        Wend

'        Printer.Print Tab(1); "====================================================================================================================================================="
        Printer.FontBold = True
        Printer.Print Tab(5); "Totals:"; Tab(18); dTotalRolls & " ROLLS"; Tab(116); dTotalWeightLB; Tab(125); dTotalWeightKG

        Printer.EndDoc
        
    End If
    
    Call ClearAllFields
    OraDatabase.Close
    Set OraDatabase = Nothing
    Set OraSession = Nothing
    
    GoTo exitsub
    
ErrHandler:
    End


exitsub:
End Sub

