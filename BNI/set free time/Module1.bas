Attribute VB_Name = "Module1"
Global dateOption As Integer
Global lrNumList As New Collection
Global colVesselName As New Collection

Global DB As String
Global Login As String
Global truckVessel As New Collection
Global colTruckLRNum As New Collection
Global Load4FirstTime As Boolean
Global ShipSailed As Boolean
Sub iniVariables()
    DB = "BNITEST"
    Login = "SAG_OWNER/BNITEST238"

'    DB = "BNI"
'    Login = "SAG_OWNER/SAG"
End Sub

Sub RetrieveStartFT(lr_num As Integer)

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "select v.DATE_DEPARTED, v.FREE_TIME_START" & _
                " from voyage v" & _
                " Where v.LR_NUM =" & lr_num
                
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        If rs.RecordCount <> 1 Then
            MsgBox "More than one record found for vessel " & lr_num & "in VOYAGE table"
            Set OraSession = Nothing
            Set OraDatabase = Nothing
            Set rs = Nothing
            End
        End If
        
        '' Clean labels for new values
        frmSetFreeTime.lblSailedDate.Caption = ""
        frmSetFreeTime.lblSailWeekDay.Caption = ""
        frmSetFreeTime.lblStartFTDate.Caption = ""
        frmSetFreeTime.lblStartFTWeekday.Caption = ""
        
        '' Set value for sail date
        If (Len(rs.Fields(0).Value) <> 0) Then
            ShipSailed = True
        End If
        frmSetFreeTime.lblSailedDate.Caption = Format(rs.Fields(0).Value, "mm/dd/yyyy")
        
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "RetrieveStartFT"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If
    
End Sub


Function calStartFT(depDate As String) As String

    Dim sDate As Date
    Dim eDate As Date
    Dim fd As Integer
    Dim tmp As String
    
    sDate = Format(depDate, "mm/dd/yyyy")
    eDate = DateAdd("d", 1, sDate)
    
    tmp = UCase(Format(eDate, "dddd"))
    
    If tmp = "SATURDAY" Then
        eDate = DateAdd("d", 3, sDate)
    ElseIf tmp = "SUNDAY" Then
        eDate = DateAdd("d", 2, sDate)
    End If
    
    
    calStartFT = Format(eDate, "mm/dd/yyyy")


End Function

Sub UpdateTruckedInCargo(lr_num As String)

    Dim i As Integer
        
    ''Step-1 Update records of a given vessel
    '' if lr_num is "-1" then update all other trucked in cargos
    '' otherwise only process the given vessel
    If (lr_num = "-1") Then
        For i = 1 To colTruckLRNum.Count
            Call UpdateTruckedInCargoFT(colTruckLRNum.Item(i))
        Next i
    Else
        Call UpdateTruckedInCargoFT(lr_num)
    End If
End Sub

Sub GetTruckVesselFreeDays()

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "SELECT * FROM FREE_TIME WHERE AUTO_RUN = 'Y'"
        
        
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        ''rs.Fields(0)-COMMODITY_CODE
        ''rs.Fields(1)-FREE_DAYS
        ''rs.Fields(2)-CUSTOMER_ID
        ''rs.Fields(3)-LR_NUM
        ''rs.Fields(4)-AUTO_RUN
        '' Load values into truckVessel (Collection) with key-value
        rs.MoveFirst
        Do While Not rs.EOF
            truckVessel.Add rs.Fields(1).Value, rs.Fields(3).Value
            colTruckLRNum.Add rs.Fields(3).Value
            rs.MoveNext
        Loop
    
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "GetTruckVesselFreeDays"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
        
    End If


End Sub


Sub UpdateTruckedInCargoFT(lr_num As String)

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    Dim fd As String
    Dim rCount As Integer
    
    fd = truckVessel.Item(lr_num)

    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        ' Prepare sql statement
        '' update CARGO_TRACKING table
        '' set START_FREE_TIME, FREE_DAYS and FREE_TIME_END
        '' if DATE_RECEIVED is not null
        '' and START_FREE_TIME is null
        '' and FREE_DAYS is null
        '' and FREE_TIME_END is null
        '' START_FREE_TIME=DATE_RECEIVED
        '' FREE_DAYS is retrieved from FREE_TIME table based on LR_NUM
        '' FREE_TIME_END=START_FREE_TIME + FREE_DAYS
        strSql = "UPDATE CARGO_TRACKING c" & _
                    " SET c.START_FREE_TIME=c.DATE_RECEIVED," & _
                    " c.FREE_DAYS=" & fd & "," & _
                    " c.FREE_TIME_END = c.START_FREE_TIME +" & fd & _
                    " where c.LOT_NUM in" & _
                    " (select m.CONTAINER_NUM" & _
                    " from cargo_manifest m" & _
                    " Where m.lr_num =" & lr_num & ")" & _
                    " and c.DATE_RECEIVED is not null" & _
                    " and c.START_FREE_TIME is null" & _
                    " and c.FREE_DAYS is null" & _
                    " and c.FREE_TIME_END is null"
                
        '' Begin Transaction
        OraSession.BeginTrans
        
        '' Create Recordset
        rCount = OraDatabase.ExecuteSQL(strSql)
        MsgBox rCount & " records have been updated for vessel:" & lr_num
        
        '' Commit Tansaction
        OraSession.CommitTrans
        
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "UpdateTruckedInCargoFT"
        OraSession.RollBack
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If

End Sub


Sub RetrieveVessels()

On Error GoTo Err_Handler
    
    Dim OraSession As Object
    Dim OraDatabase As Object
    Dim rs As Object
    Dim strSql As String
    
    
    'Create the OraSession Object
    Set OraSession = CreateObject("OracleInProcServer.XOraSession")
    'Create the OraDatabase Object
    Set OraDatabase = OraSession.OpenDatabase(DB, Login, 0&)
    
    If OraDatabase.LastServerErr = 0 Then
        
        '' Prepare sql statement
        strSql = "select c.LR_NUM,  c.VESSEL_NAME from Vessel_Profile c" & _
                    " where c.LR_NUM in (select v.LR_NUM from Voyage v)" & _
                    " order by c.LR_NUM desc"
                
        '' Create Recordset
        Set rs = OraDatabase.CreateDynaset(strSql, 0&)
        
        '' Load values into lrNumList (Collection) and lstVesselName (list box)
        rs.MoveFirst
        Do While Not rs.EOF
            lrNumList.Add rs.Fields(0).Value, rs.Fields(1).Value
            colVesselName.Add rs.Fields(1).Value, rs.Fields(0).Value
            rs.MoveNext
        Loop
    
        '' Set variables to Nothing
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
    
    
    Else
        MsgBox "Error:" & OraDatabase.LastServerErrText & " occured.", vbExclamation, "Logon"
        End
        
    End If
    
    Exit Sub
    
Err_Handler:

    If Err.Number <> 0 Then
        MsgBox Err.Description & " occurred in " & App.Title & "." & _
        "RetrieveVessels"
        Set OraSession = Nothing
        Set OraDatabase = Nothing
        Set rs = Nothing
        End
    End If

End Sub

Function IsAutoRun(lr_num As String) As Boolean

Dim retVal As Boolean
Dim i As Integer

    retVal = False
    
    For i = 1 To colTruckLRNum.Count
    
        If UCase(colTruckLRNum.Item(i)) = UCase(lr_num) Then
            retVal = True
            Exit For
        End If

    Next i

    IsAutoRun = retVal

End Function
