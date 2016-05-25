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
Dim sLastBooking As String
Dim sContainer As String
Dim sSeal As String
Dim sMasterBooking As String

Private Sub cmdClose_Click()
    End
End Sub

Private Sub Form_Load()
    Call ClearAllFields
End Sub

Sub ClearAllFields()

    frmMainForm.txtCustNum.Text = ""
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

'    Dim OraSession As Object
'    Dim OraDatabase As Object
    
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
    ElseIf txtCustNum.Text <> 314 And txtCustNum.Text <> 338 And txtCustNum.Text <> 517 And txtCustNum.Text <> 1 Then
        MsgBox "This Program currently only designed for Booking customers."
        Exit Sub
    End If
    strCustName = dsSHORT_TERM_DATA.Fields("CUSTOMER_NAME").Value


    strSql = "SELECT TO_CHAR(MIN(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') START_TIME, TO_CHAR(MAX(DATE_OF_ACTIVITY), 'MM/DD/YYYY HH:MI AM') END_TIME " _
            & "FROM CARGO_ACTIVITY WHERE ORDER_NUM = '" & txtOrderNum.Text & "' AND CUSTOMER_ID = '" & txtCustNum.Text & "' AND SERVICE_CODE = '6'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    If dsSHORT_TERM_DATA.RecordCount <= 0 Or IsNull(dsSHORT_TERM_DATA.Fields("START_TIME").Value) Then
        MsgBox "Entered Order (" & txtOrderNum.Text & ") Has no records of activity in the system."
        Exit Sub
    End If
    strStartTime = dsSHORT_TERM_DATA.Fields("START_TIME").Value
    strEndTime = dsSHORT_TERM_DATA.Fields("END_TIME").Value

' , EMPLOYEE PERS       , SUBSTR(EMPLOYEE_NAME, 0, 8) THE_CHECKER       AND TO_CHAR(CA.ACTIVITY_ID) = SUBSTR(PERS.EMPLOYEE_ID, -4)
    strSql = "SELECT CT.PALLET_ID, QTY_RECEIVED, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, CA.ACTIVITY_NUM, CA.ARRIVAL_NUM, " _
                & "BAD.ORDER_NUM, LENGTH || ' ' || LENGTH_MEAS THE_LINEAR, BAD.BOL THE_MANIFEST, ROUND(WEIGHT * UC3.CONVERSION_FACTOR) WEIGHT_LB, ROUND(WEIGHT * UC4.CONVERSION_FACTOR) WEIGHT_KG, DECODE(QTY_DAMAGED, NULL, 'N', '0', 'N', 'Y') DAMAGE_YN " _
                & "FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
                & "WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID " _
                & "AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                & "AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
                & "AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) " _
                & "AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL " _
                & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
                & "ORDER BY NVL(BOOKING_NUM, '--NONE--')"
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
        sMasterBooking = ""
        sSeal = ""
        sContainer = ""
        
        strSql = "SELECT CONTAINER_ID, SEAL_NUM, BOOKING_NUM FROM BOOKING_ORDERS WHERE ORDER_NUM = '" & txtOrderNum.Text & "'"
        Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        If Not IsNull(dsSHORT_TERM_DATA.Fields("CONTAINER_ID").Value) Then
            sContainer = dsSHORT_TERM_DATA.Fields("CONTAINER_ID").Value
        End If
        If Not IsNull(dsSHORT_TERM_DATA.Fields("SEAL_NUM").Value) Then
            sSeal = dsSHORT_TERM_DATA.Fields("SEAL_NUM").Value
        End If
        If Not IsNull(dsSHORT_TERM_DATA.Fields("BOOKING_NUM").Value) Then
            sMasterBooking = dsSHORT_TERM_DATA.Fields("BOOKING_NUM").Value
        End If

        ' go to printer
        If LCase$(Environ$("USERNAME")) = "tally" Then
            Printer.Copies = 2
        Else
            Printer.Copies = 1
        End If

        '' Page Header
        Printer.Orientation = 2
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
        Printer.Print Tab(10); "ORDER: " & txtOrderNum.Text; Tab(65); "END TIME: " & strEndTime
        Printer.Print Tab(10); "CONTAINER: " & sContainer; Tab(65); "MASTER BOOKING#: " & sMasterBooking
        Printer.Print Tab(10); "SEAL: " & sSeal
        Printer.Print ""
        Printer.FontSize = 8
        Printer.FontBold = False
'        Printer.Print "BARCODE"; Tab(20); "QTY"; Tab(27); "Size (cm)"; Tab(38); "DIA (cm)"; Tab(49); "PRODUCT"; Tab(85); "ORDER"; Tab(95); "Linear Meas"; Tab(110); "Rec. Manifest"; Tab(125); "LB"; Tab(135); "KG"; Tab(145); "DMG"
        Printer.Print "BARCODE"; Tab(26); "QTY"; Tab(35); "Size (cm)"; Tab(46); "DIA (cm)"; Tab(58); "PRODUCT"; Tab(77); "BOOKING"; Tab(97); "ORDER"; Tab(110); "Linear Meas"; Tab(130); "Rec. Manifest"; Tab(150); "LB"; Tab(158); "KG"; Tab(166); "DMG"; Tab(174); "CHECKER"
'        Printer.Print Tab(1); "_______________________________________________________________________________________________________________________________________________________________________________"
        sLastBooking = ""
        While (Not dsMAIN_DATA.EOF)
            If sLastBooking <> "" And sLastBooking <> dsMAIN_DATA.Fields("THE_BOOK").Value Then
                Printer.Print ""
            End If
            sLastBooking = dsMAIN_DATA.Fields("THE_BOOK").Value
            Printer.Print dsMAIN_DATA.Fields("PALLET_ID").Value; Tab(26); dsMAIN_DATA.Fields("QTY_RECEIVED").Value; _
            Tab(35); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(46); dsMAIN_DATA.Fields("THE_DIA").Value; _
            Tab(58); dsMAIN_DATA.Fields("THE_CODE").Value; Tab(77); dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(97); dsMAIN_DATA.Fields("ORDER_NUM").Value; _
            Tab(110); dsMAIN_DATA.Fields("THE_LINEAR").Value; _
            Tab(130); dsMAIN_DATA.Fields("THE_MANIFEST").Value; Tab(150); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
            Tab(158); dsMAIN_DATA.Fields("WEIGHT_KG").Value; Tab(166); dsMAIN_DATA.Fields("DAMAGE_YN").Value; Tab(174); get_checker_name(dsMAIN_DATA.Fields("PALLET_ID").Value, txtCustNum.Text, dsMAIN_DATA.Fields("ARRIVAL_NUM").Value, dsMAIN_DATA.Fields("ACTIVITY_NUM").Value)
'            Printer.Print dsMAIN_DATA.Fields("LINSN_PALLET_ID").Value; Tab(20); "1"; _
'            Tab(27); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(38); dsMAIN_DATA.Fields("THE_DIA").Value; _
'            Tab(49); dsMAIN_DATA.Fields("LINGC_PRODUCT_CODE").Value & " " & dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(85); dsMAIN_DATA.Fields("PRF_ORDER_NUM").Value; _
'            Tab(95); dsMAIN_DATA.Fields("MEALN_LENGTH").Value & " " & dsMAIN_DATA.Fields("MEALN_LENGTH_MEAS").Value; _
'            Tab(110); dsMAIN_DATA.Fields("REFBM_BOL").Value; Tab(125); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
'            Tab(135); dsMAIN_DATA.Fields("WEIGHT_KG").Value; Tab(145); dsMAIN_DATA.Fields("DAMAGE_YN").Value; dsMAIN_DATA.Fields("THE_CHECKER").Value

'            dTotalPallets = dTotalPallets + 1
            dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
            dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
            dTotalRolls = dTotalRolls + 1
            dsMAIN_DATA.MoveNext
        Wend

'        Printer.Print Tab(1); "====================================================================================================================================================="
        Printer.FontBold = True
        Printer.Print Tab(5); "Totals:"; Tab(24); dTotalRolls & " ROLLS"; Tab(139); dTotalWeightLB; Tab(148); dTotalWeightKG
        Printer.Print
        Printer.Print
        Printer.Print
        
        Printer.FontBold = True
        Printer.Print "Outgoing Summary"
        Printer.FontBold = False

        ' summary by measurement
        strSql = "SELECT SUM(QTY_RECEIVED) THE_REC, ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR) THE_WIDTH, ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR) THE_DIA, NVL(SSCC_GRADE_CODE, '--NONE--') THE_CODE, NVL(BOOKING_NUM, '--NONE--') THE_BOOK, " _
                    & "BAD.ORDER_NUM, BAD.BOL THE_MANIFEST, SUM(ROUND(WEIGHT * UC3.CONVERSION_FACTOR)) WEIGHT_LB, SUM(ROUND(WEIGHT * UC4.CONVERSION_FACTOR)) WEIGHT_KG " _
                    & "FROM CARGO_TRACKING CT, SAG_OWNER.BOOKING_ADDITIONAL_DATA BAD, CARGO_ACTIVITY CA, BOOKING_PAPER_GRADE_CODE BPGC, UNIT_CONVERSION_FROM_BNI UC1, UNIT_CONVERSION_FROM_BNI UC2, UNIT_CONVERSION_FROM_BNI UC3, UNIT_CONVERSION_FROM_BNI UC4 " _
                    & "WHERE CT.ARRIVAL_NUM = BAD.ARRIVAL_NUM And CT.PALLET_ID = BAD.PALLET_ID And CT.RECEIVER_ID = BAD.RECEIVER_ID " _
                    & "AND CA.ARRIVAL_NUM = BAD.ARRIVAL_NUM AND CA.PALLET_ID = BAD.PALLET_ID AND CA.CUSTOMER_ID = BAD.RECEIVER_ID " _
                    & "AND BAD.WIDTH_MEAS = UC1.PRIMARY_UOM AND UC1.SECONDARY_UOM = 'CM'  AND BAD.DIAMETER_MEAS = UC2.PRIMARY_UOM AND UC2.SECONDARY_UOM = 'CM'  AND CT.WEIGHT_UNIT = UC3.PRIMARY_UOM AND UC3.SECONDARY_UOM = 'LB'  AND CT.WEIGHT_UNIT = UC4.PRIMARY_UOM AND UC4.SECONDARY_UOM = 'KG' " _
                    & "AND BAD.PRODUCT_CODE = BPGC.PRODUCT_CODE(+) " _
                    & "AND CA.SERVICE_CODE = '6' AND CA.ACTIVITY_DESCRIPTION IS NULL " _
                    & "AND CA.ORDER_NUM = '" & txtOrderNum.Text & "' " _
                    & "GROUP BY ROUND(BAD.WIDTH * UC1.CONVERSION_FACTOR), ROUND(BAD.DIAMETER * UC2.CONVERSION_FACTOR), NVL(SSCC_GRADE_CODE, '--NONE--'), NVL(BOOKING_NUM, '--NONE--'), BAD.ORDER_NUM, BAD.BOL " _
                    & "ORDER BY NVL(BOOKING_NUM, '--NONE--')"
        Set dsMAIN_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
        sLastBooking = ""
        While (Not dsMAIN_DATA.EOF)
            If sLastBooking <> "" And sLastBooking <> dsMAIN_DATA.Fields("THE_BOOK").Value Then
                Printer.Print ""
            End If
            sLastBooking = dsMAIN_DATA.Fields("THE_BOOK").Value
            Printer.Print Tab(26); dsMAIN_DATA.Fields("THE_REC").Value; _
            Tab(35); dsMAIN_DATA.Fields("THE_WIDTH").Value; Tab(46); dsMAIN_DATA.Fields("THE_DIA").Value; _
            Tab(58); dsMAIN_DATA.Fields("THE_CODE").Value; Tab(77); dsMAIN_DATA.Fields("THE_BOOK").Value; Tab(97); dsMAIN_DATA.Fields("ORDER_NUM").Value; _
            Tab(130); dsMAIN_DATA.Fields("THE_MANIFEST").Value; Tab(150); dsMAIN_DATA.Fields("WEIGHT_LB").Value; _
            Tab(158); dsMAIN_DATA.Fields("WEIGHT_KG").Value

'            dTotalPallets = dTotalPallets + 1
'            dTotalWeightLB = dTotalWeightLB + dsMAIN_DATA.Fields("WEIGHT_LB").Value
'            dTotalWeightKG = dTotalWeightKG + dsMAIN_DATA.Fields("WEIGHT_KG").Value
'            dTotalRolls = dTotalRolls + 1
            dsMAIN_DATA.MoveNext
        Wend


        Printer.EndDoc
        
    End If
    
    Call ClearAllFields
    OraDatabase.Close
    Set OraDatabase = Nothing
    Set OraSession = Nothing
    
    GoTo exitsub
    
ErrHandler:
    MsgBox "Error " & Err & " - " & Error$ & " occurred while processing.", vbExclamation
    End


exitsub:
End Sub

Function get_checker_name(Barcode As String, cust As String, LR As String, act_num As String) As String
    Dim ActDate As String
    Dim empno As String
    
    strSql = "SELECT TO_CHAR(DATE_OF_ACTIVITY, 'MM/DD/YYYY') THE_DATE, ACTIVITY_ID " _
            & "FROM CARGO_ACTIVITY " _
            & "WHERE PALLET_ID = '" & Barcode & "' " _
            & "AND ARRIVAL_NUM = '" & LR & "' " _
            & "AND CUSTOMER_ID = '" & cust & "' " _
            & "AND ACTIVITY_NUM = '" & act_num & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    ActDate = dsSHORT_TERM_DATA.Fields("THE_DATE").Value
    empno = dsSHORT_TERM_DATA.Fields("ACTIVITY_ID").Value
    
    If IsNull(empno) Or empno = "" Then
        get_checker_name = "UNKNOWN"
        Exit Function
    End If
    
    strSql = "SELECT COUNT(*) THE_COUNT FROM LU_SCANNER_LOGIN_CHG_DATE WHERE CHANGE_DATE >= TO_DATE('" & ActDate & "', 'MM/DD/YYYY')"
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    If dsSHORT_TERM_DATA.Fields("THE_COUNT").Value >= 1 Then
        strSql = "SELECT LOGIN_ID THE_EMP FROM PER_OWNER.PERSONNEL WHERE EMPLOYEE_ID = '" & empno & "'"
    Else
'        get_checker_name = empno
'        Exit Function
        While Len(empno) < 5
            empno = "0" & empno
        Wend
'        get_checker_name = empno
'        Exit Function
        strSql = "SELECT SUBSTR(EMPLOYEE_NAME, 0, 8) THE_EMP FROM EMPLOYEE WHERE SUBSTR(EMPLOYEE_ID, -" & Len(empno) & ") = '" & empno & "'"
    End If
    Set dsSHORT_TERM_DATA = OraDatabase.DbCreateDynaset(strSql, 0&)
    
    get_checker_name = dsSHORT_TERM_DATA.Fields("THE_EMP").Value
    
End Function



