VERSION 1.0 CLASS
BEGIN
  MultiUse = -1  'True
  Persistable = 0  'NotPersistable
  DataBindingBehavior = 0  'vbNone
  DataSourceBehavior  = 0  'vbNone
  MTSTransactionMode  = 0  'NotAnMTSObject
END
Attribute VB_Name = "employeeHourlyRecord"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Private mvarHireDate As String
Private mvarEmployeeID As String
Private mvarEmployeeName As String
Private mvarEmployeeType As String
Private mvarEmployeeSubType As String
Private mvarEarningType As String
Private mvarVesselID As String
Private mvarCommodityCode As String
Private mvarServiceCode As String
Private mvarSpecialCode As String
Private mvarEquipmentID As String
Private mvarLocationID As String
Private mvarDuration As String

Private mvarCeridianPay As ceridianPay
Private mvarExtraPay As ceridianPay

Public Property Let hireDate(s As String)

  mvarHireDate = Trim(s)
  
End Property

Public Property Let EmployeeID(s As String)

  mvarEmployeeID = Trim(s)
  
End Property

Public Property Let EmployeeName(s As String)

  mvarEmployeeName = Trim(s)
  
End Property

Public Property Let EmployeeType(s As String)

  mvarEmployeeType = UCase(Trim(s))
  
End Property

Public Property Let EmployeeSubType(s As String)

  mvarEmployeeSubType = UCase(Trim(s))
  
End Property

Public Property Let EarningType(s As String)

  mvarEarningType = UCase(Trim(s))
  
End Property

Public Property Let ServiceCode(s As String)

  mvarServiceCode = Trim(s)
  
End Property

Public Property Let VesselID(s As String)

  mvarVesselID = Trim(s)
  
End Property

Public Property Let CommodityCode(s As String)

  mvarCommodityCode = Trim(s)
  
End Property

Public Property Let SpecialCode(s As String)

  mvarSpecialCode = Trim(s)
  
End Property

Public Property Let EquipmentID(s As String)

  mvarEquipmentID = Trim(s)
  
End Property

Public Property Let LocationID(s As String)

  mvarLocationID = Trim(s)
  
End Property

Public Property Let Duration(s As String)

  mvarDuration = Trim(s)
  
End Property

Public Sub getCeridianPay()
    Dim payCode As String
    Dim rateCode As String
    Dim payRate As String
    Dim result As New ceridianPay, result2 As New ceridianPay
    Dim PayRate2 As String
    Dim PayString As String
    Dim SqlStmt As String
    Dim Short_Term_Data As Object
    
    payCode = getPayCode
    Select Case UCase(mvarEmployeeType)
        Case "REGR"
            ' Use old rate for any hire dates earlier than 10/01/03  -- Seth, 10/02/03
'            If DateDiff("d", "10/01/2003", mvarHireDate) >= 0 Then
'                If supervisorServiceCode() = True Then
'                    payRate = "20.27"
'                ElseIf specialServiceCode() = True Then
'                    payRate = "20.65"
'                ElseIf specialEquipment() = True Then
'                    payRate = "20.65"
'                ElseIf checkerRate() = True Then
'                    payRate = "19.77"
'                ElseIf liftTruckerRate() = True Then
'                    payRate = "19.77"
'                Else
'                    payRate = ""
'                End If
'            Else
'                If supervisorServiceCode() = True Then
'                    payRate = "20.27"
'                ElseIf specialServiceCode() = True Then
'                    payRate = "19.86"
'                ElseIf specialEquipment() = True Then
'                    payRate = "19.86"
'                ElseIf checkerRate() = True Then
'                    payRate = "19.01"
'                ElseIf liftTruckerRate() = True Then
'                    payRate = "19.01"
'                Else
'                    payRate = ""
'                End If
'            End If

'Rates has been changed - as on 10/11/2005
' and many times thereafter.  i really need the time to set up a DB table and change this to a function call... ~Adam.

                If supervisorServiceCode() = True Then
 '                   payRate = "20.27"
'                    payRate = "20.27"
                    PayString = "SUPER"
                ElseIf C1CraneOperCheck() = True Then
                    PayString = "C1CRANEOPER"
                ElseIf specialServiceCode() = True Then
                    '' Per HD# 2507, changed on 12/10/2006
                    ''payRate = "22.01"
                    ''payRate = "22.73"
                    ''payRate = "23.36"
 '                   payRate = "24.02"
    '                payRate = "24.74"
'                    payRate = "27.41"
'                    payRate = "27.96" '5/11/2012 retrochange]
                    PayString = "SPECSERV"
                ElseIf specialServiceCodeOther() = True Then
'                    payRate = "24.74"
'                    payRate = "25.23" '5/11/2012 retrochange
                    PayString = "SPECSERVOTHER"
                ElseIf specialEquipment() = True Then
                    '' Per HD# 2507, changed on 12/10/2006
                    ''payRate = "22.01"
                    ''payRate = "22.73"
                    ''payRate = "23.36"
''                    payRate = "24.02"
'                    payRate = "24.74"
'                    payRate = "25.23" '5/11/2012 retrochange
                    PayString = "SPECEQUIP"
                ' IOI Change 10/21/05 Tazzy request
                ElseIf checkerRate() = True Then
                    '' Per HD# 2507, changed on 12/10/2006
                    ''payRate = "21.08"
                    ''payRate = "21.76"
                    ''payRate = "22.36"
'                    payRate = "23.00"
'                    payRate = "23.69"
'                   payRate = "24.16" '5/11/2012 retrochange
                    PayString = "CHECKER"
                ElseIf liftTruckerRate() = True Then
                    '' Per HD# 2507, changed on 12/10/2006
                    ''payRate = "21.08"
                    ''payRate = "21.76"
                    ''payRate = "22.36"
'                    payRate = "23.00"
'                    payRate = "23.69"
'                   payRate = "24.16" '5/11/2012 retrochange
                    PayString = "LIFTTRUCK"
                
                Else
'                    payRate = ""
                    PayString = "NONE"
                End If
                
                SqlStmt = "SELECT RATE_AMOUNT FROM LCS_BASE_RATE WHERE SERVICE_ID = '" & PayString & "'"
                Set Short_Term_Data = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
                If OraDatabase.LastServerErr = 0 And Short_Term_Data.RecordCount > 0 Then
                    payRate = Short_Term_Data.Fields("RATE_AMOUNT").Value
                Else
                    payRate = ""
                End If
                
' New logic, October 2008:  check to see if the service code worked overwrites a default pay rate
                payRate = SpecialRateOverwrite(mvarServiceCode, payRate)

                        
            'Julius Cephas pay rate should always be 20.65, which is setup in ADP
'            If mvarEmployeeID = "E407769" Then
'                payRate = ""
'            End If
            
            

            If payRate <> "" Then
                If mvarEarningType = "REG" Then
                    rateCode = "P"
                ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
                    rateCode = "R"
                End If
            Else
                rateCode = "#"
            End If
            
'            If mvarSpecialCode = "1401" Then
'                PayRate2 = getFreezerPay
                PayRate2 = SpecialRateAddition(mvarServiceCode, mvarEarningType)
'            End If
        Case "CASB", "CASC", "CAS"
            rateCode = "#"
            payRate = ""
'            If mvarSpecialCode = "1401" Then
'                PayRate2 = getFreezerPay
                PayRate2 = SpecialRateAddition(mvarServiceCode, mvarEarningType)
'            End If
        Case "GUARD"
            rateCode = "#"
            payRate = ""
            If mvarSpecialCode = "02" Or mvarSpecialCode = "03" Or mvarSpecialCode = "2" Or mvarSpecialCode = "3" Then
                PayRate2 = getShiftPay
            End If
        Case "SUPV"
            rateCode = "#"
            payRate = ""
    End Select
    
    ' Abraham Abraham
'    If mvarEmployeeID = "E408368" Then
'        ' Use old rate for any hire dates earlier than 10/01/03  -- Seth, 10/06/03
'        If DateDiff("d", "10/01/2003", mvarHireDate) >= 0 Then
'            payRate = "22.88"
'        Else
'            payRate = "22.00"
'        End If
        
   
    ' Ben Dempsey
    'If mvarEmployeeID = "E405430" Then
        ' Use old rate for any hire dates earlier than 10/01/03  -- Seth, 10/06/03
    '    If DateDiff("d", "10/01/2003", mvarHireDate) >= 0 Then
    '        payRate = "22.88"
    '    Else
'            payRate = "22.00"
'        End If
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If
    
    ' Wilhelmina Archie
'    If mvarEmployeeID = "E405226" Then
'        ' Use old rate for any hire dates earlier than 10/01/03  -- Seth, 10/06/03
'        If DateDiff("d", "10/01/2003", mvarHireDate) >= 0 Then
'            payRate = "22.88"
'        Else
'            payRate = "22.00"
'        End If
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If
    
'    ' Tom White...
'    If mvarEmployeeID = "E406523" And mvarServiceCode = "6540" Then
'        rateCode = "P"
'
'        ' Use old rate for any hire dates earlier than 10/01/03  -- Seth, 10/06/03
'        If DateDiff("d", "10/01/2003", mvarHireDate) >= 0 Then
'            payRate = "22.88"
'        Else
'            payRate = "22.00"
'        End If
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If
    
'   ' Timmy Miller - Per Tazzy 8/20/04 - STM
'    If mvarEmployeeID = "E406878" And Mid(mvarServiceCode, 4, 1) = "0" Then
'      rateCode = "P"
'      payRate = "22.88"
'    End If
'
'    ' Julias C...
'    If mvarEmployeeID = "E407769" Then
'        rateCode = "P"
'        payRate = "22.65"
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If
    
    ' John C. Burgess   -- LFW, 2/27/04, Per Tazzy Scott
'    If mvarEmployeeID = "E407827" Then
'        payRate = "20.65"
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If

    ' Fall Scott   -- LFW, 2/27/04, Per Tazzy Scott
'    If mvarEmployeeID = "E406652" Then
'        payRate = "20.65"
'
'        If mvarEarningType = "REG" Then
'            rateCode = "P"
'        ElseIf mvarEarningType = "OT" Or mvarEarningType = "DT" Then
'            rateCode = "R"
'        End If
'    End If


    With result
        .EmployeeID = Right(mvarEmployeeID, 4)
        .payCode = payCode
        .payHour = mvarDuration
        .payRate = payRate
        .rateCode = rateCode
        .ServShiftEquipCode = getServShiftEquipCode()
    End With
    Set mvarCeridianPay = result
    
    With result2
         .EmployeeID = Right(mvarEmployeeID, 4)
         .ServShiftEquipCode = getServShiftEquipCode()
    End With
    If PayRate2 <> "" Then
        With result2
            .payCode = "1" & Right$(payCode, 1)
            .payHour = ""
            .payRate = PayRate2
            .rateCode = "E"
        End With
    Else
        With result2
            .payCode = ""
            .payHour = ""
            .payRate = ""
            .rateCode = ""
        End With
    End If
    
    Set mvarExtraPay = result2
End Sub

Public Function getCeridianString()
    Dim result(2) As String
    
    Call getCeridianPay
    
    result(1) = mvarCeridianPay.getCeridianString
    If mvarExtraPay.payCode <> "" Then
        result(2) = mvarExtraPay.getCeridianString
    Else
        result(2) = ""
    End If

    getCeridianString = result()
End Function


Public Function getServShiftEquipCode() As String
    Dim gsSql As String, gsSql1 As String
    Dim iAsset_code As String, loc_code As String
    Dim rsLoc As Object, rsAss As Object
    
    gsSql = " SELECT LOCATION_CODE FROM LOCATION_CATEGORY WHERE " & _
            " LOCATION_ID='" & mvarLocationID & "'"
   
    Set rsLoc = OraDatabase.DBCreateDynaset(gsSql, 0&)
    If OraDatabase.LastServerErr = 0 And rsLoc.RecordCount > 0 Then
        loc_code = rsLoc.Fields("LOCATION_CODE").Value
        gsSql1 = " SELECT ASSET_CODE FROM ASSET_PROFILE WHERE " & _
                " SERVICE_LOCATION_CODE='" & loc_code & "'"
        Set rsAss = OraDatabaseBNI.DBCreateDynaset(gsSql1, 0&)
        If OraDatabase.LastServerErr = 0 And rsAss.RecordCount > 0 Then
            iAsset_code = rsAss.Fields("ASSET_CODE").Value
        Else
            iAsset_code = "0000"
        End If
    Else
        iAsset_code = "0000"
    End If
    
    getServShiftEquipCode = Right("0000" & mvarServiceCode, 4) & Right("0000" & mvarCommodityCode, 4) & Right("0000" & iAsset_code, 4)
    If mvarSpecialCode = "1401" Then
        getServShiftEquipCode = getServShiftEquipCode & "50"
    Else
        getServShiftEquipCode = getServShiftEquipCode & Right("00" & mvarSpecialCode, 2)
    End If
                    
End Function

Public Function getPayCode() As String
    Dim gsSql As String
    Dim rsLoc As Object
    
    gsSql = " SELECT CERIDIAN_PAY_CODE FROM CERIDIAN_PAY_CODE WHERE " & _
            " EMPLOYEE_TYPE_ID='" & mvarEmployeeType & "'" & _
            " AND EARNING_TYPE_ID='" & mvarEarningType & "'"
   
    Set rsLoc = OraDatabase.DBCreateDynaset(gsSql, 0&)
    
    If OraDatabase.LastServerErr = 0 And rsLoc.RecordCount > 0 Then
        getPayCode = Trim(rsLoc.Fields("CERIDIAN_PAY_CODE").Value)
    Else
        Call errorLog("Invalid Earning Type - " & mvarHireDate & " - " & mvarEmployeeID & " - " & mvarEmployeeName & " , " & mvarEmployeeType & "  ,  " & mvarEarningType)
    End If
End Function

Public Function validRecord() As Boolean
    validRecord = False
    If mvarEmployeeID = "" Then
        Call errorLog("Invalid Employee ID - " & mvarHireDate & " - " & mvarEmployeeID & " - " & mvarEmployeeName & " , " & mvarEmployeeType & "  ,  " & mvarEarningType)
    ElseIf validEmployeeType() = False Then
        Call errorLog("Invalid Employee Type - " & mvarHireDate & " - " & mvarEmployeeID & " - " & mvarEmployeeName & " , " & mvarEmployeeType & "  ,  " & mvarEarningType)
    ElseIf validEarningType() = False Then
        Call errorLog("Invalid Earning Type - " & mvarHireDate & " - " & mvarEmployeeID & " - " & mvarEmployeeName & " , " & mvarEmployeeType & "  ,  " & mvarEarningType)
    ElseIf mvarServiceCode = "" Then
        Call errorLog("Invalid Service Code - " & mvarHireDate & " - " & mvarEmployeeID & " - " & mvarEmployeeName & " , " & mvarEmployeeType & "  ,  " & mvarEarningType)
    Else
        validRecord = True
    End If
End Function

Public Function validEmployeeType() As Boolean
    Select Case mvarEmployeeType
        Case "REGR", "CASB", "CAS", "CASC", "GUARD", "SUPV"
            validEmployeeType = True
        Case Else
            validEmployeeType = False
    End Select
End Function

Public Function validEarningType() As Boolean
    validEarningType = False
    Select Case mvarEmployeeType
        Case "REGR"
            Select Case mvarEarningType
                Case "REG", "OT", "DT"
                    validEarningType = True
            End Select
        Case "CASB", "CAS", "CASC"
            Select Case mvarEarningType
                Case "REG", "OT", "DT"
                    validEarningType = True
            End Select
        Case "GUARD"
            Select Case mvarEarningType
                Case "REG", "HOL-REG", "PERS", "BIRTH", "OT", "HOL-OT", "BIRTH-OT"
                    validEarningType = True
            End Select
        Case "SUPV"
            Select Case mvarEarningType
                Case "REG", "OT", "DT", "SICK", "PERS", "VAC"
                    validEarningType = True
            End Select
    End Select
End Function

Public Function specialEquipment() As Boolean
' Crane Operator, Rayco Dozer, Payloader, Weighmaster, electrician, Refer mechanic
    specialEquipment = False
    If mvarEmployeeType = "REGR" Then
        Select Case mvarEquipmentID
            Case "1100", "1101", "1103", "1104", "1106", "1112", "1113", "1114", "1115", "1151", "1152"
                specialEquipment = True
        End Select
    End If
End Function

Public Function checkerRate() As Boolean
    checkerRate = False
    If mvarEmployeeType = "REGR" Then
        
        '' pwu 5/22/2006
        '' A checker's job was performed
        '' Checker's rate is lower than CO, ME and WE
        '' CO-Crane Operator
        '' ME-Mechanics
        '' WE-Weighmaster
        '' They get paid based on their base rates
        If Right$(mvarServiceCode, 1) = "9" Then
            If (mvarEmployeeSubType <> "CO" And mvarEmployeeSubType <> "ME" And mvarEmployeeSubType <> "WE") Then
                checkerRate = True
            End If
        End If
    End If
End Function

Public Function liftTruckerRate() As Boolean
    liftTruckerRate = False
    
    If mvarEmployeeType = "REGR" Then
    
        '' pwu 5/22/2006
        '' An operator's job was performed
        '' Operator's rate is lower than CO, ME and WE
        '' CO-Crane Operator
        '' ME-Mechanics
        '' WE-Weighmaster
        '' They get paid based on their base rates
        If Right$(mvarServiceCode, 1) = "1" Then
            If (mvarEmployeeSubType <> "CO" And mvarEmployeeSubType <> "ME" And mvarEmployeeSubType <> "WE") Then
                liftTruckerRate = True
            End If
        End If
    End If
End Function

Public Function specialServiceCode() As Boolean
' contains Crane Technician, Termperatue Control Technician
    specialServiceCode = False
    If mvarEmployeeType = "REGR" Then
        If mvarServiceCode = "7440" Or mvarServiceCode = "7459" Then
            specialServiceCode = True
        End If
    End If
End Function

Public Function specialServiceCodeOther() As Boolean
' Welder, Temporary Mechanic
    specialServiceCodeOther = False
    If mvarEmployeeType = "REGR" Then
        If mvarServiceCode = "7245" Or mvarServiceCode = "7247" Then
            specialServiceCodeOther = True
        End If
    End If
End Function

Public Function supervisorServiceCode() As Boolean
    supervisorServiceCode = False
    If mvarEmployeeType = "REGR" Then
        If mvarServiceCode = "6320" Then
            supervisorServiceCode = True
        End If
    End If
End Function

Public Function C1CraneOperCheck() As Boolean
    C1CraneOperCheck = False
    If mvarEmployeeType = "REGR" Then
        If mvarServiceCode = "5111" Or mvarServiceCode = "5121" Then
            C1CraneOperCheck = True
        End If
    End If
End Function

Public Function getFreezerPay() As String
    Dim pay As Single
    Dim serviceGroup As String
    serviceGroup = Right$(mvarServiceCode, 1)
    pay = 0
    
'    ' Freezer pay updated to $1/hr 1-Jan-03
'    Select Case UCase(mvarEmployeeType)
'        Case "REGR"
'            If mvarEmployeeSubType = "LT" Then
'                pay = 1 * CSng(mvarDuration)
'            ElseIf serviceGroup = "1" Or serviceGroup = "9" Then
'                pay = 1 * CSng(mvarDuration)
'            Else
'                pay = 1 * CSng(mvarDuration)
'            End If
'        Case "CASB", "CASC", "CAS"
'            If serviceGroup = "1" Or serviceGroup = "9" Then
'                pay = 1 * CSng(mvarDuration)
'            Else
'                pay = 1 * CSng(mvarDuration)
'            End If
'    End Select

    ' Freezer pay updated to $1.35/hr 10-1-05
    Dim pay_rate As Single
    pay_rate = 1.35
    
    Select Case UCase(mvarEmployeeType)
        Case "REGR"
            If mvarEmployeeSubType = "LT" Then
                pay = pay_rate * CSng(mvarDuration)
            ElseIf serviceGroup = "1" Or serviceGroup = "9" Then
                pay = pay_rate * CSng(mvarDuration)
            Else
                pay = pay_rate * CSng(mvarDuration)
            End If
        Case "CASB", "CASC", "CAS"
            If serviceGroup = "1" Or serviceGroup = "9" Then
                pay = pay_rate * CSng(mvarDuration)
            Else
                pay = pay_rate * CSng(mvarDuration)
            End If
    End Select
    
    getFreezerPay = CStr(pay)
End Function

Public Function getShiftPay() As String
    Dim pay As Single
    pay = 0
    
    Select Case UCase(mvarEmployeeType)
        Case "GUARD"
            If mvarSpecialCode = "02" Then
               pay = 0.3 * CSng(mvarDuration)
            ElseIf mvarSpecialCode = "2" Then
               pay = 0.3 * CSng(mvarDuration)
            ElseIf mvarSpecialCode = "03" Then
               pay = 0.4 * CSng(mvarDuration)
            ElseIf mvarSpecialCode = "3" Then
               pay = 0.4 * CSng(mvarDuration)
            End If
    End Select
    
    getShiftPay = CStr(pay)
End Function

Public Function writeCeridianPay() As Boolean
    writeCeridianPay = False
    
'    If mvarEmployeeID = "E405973" Or mvarEmployeeID = "5973" Then
'        writeCeridianPay = False
'    End If
    
    If mvarCeridianPay.writeCeridianPay(mvarEmployeeID, mvarHireDate) = False Then
        Exit Function
    Else
        If mvarExtraPay.payCode <> "" Then
            If mvarExtraPay.writeCeridianPay(mvarEmployeeID, mvarHireDate) = False Then
                Exit Function
            End If
        End If
    End If
    
    writeCeridianPay = True
End Function
    

Public Function writeCeridianPayOLD() As Boolean
    Dim SqlStmt As String
    Dim iUpdRecCnt As Long
    Dim dsDetail As Object
    
    SqlStmt = " select * from CERIDIAN_PAY_DETAIL "
    
    Set dsDetail = OraDatabase.DBCreateDynaset(SqlStmt, 0&)
       
    dsDetail.AddNew
    dsDetail.Fields("HIRE_DATE").Value = Format(mvarHireDate, "MM/DD/YYYY")
    dsDetail.Fields("EMPLOYEE_ID").Value = mvarEmployeeID
    dsDetail.Fields("CERIDIAN_PAY_CODE").Value = mvarCeridianPay.payCode
    dsDetail.Fields("CERIDIAN_PAY_HOUR").Value = mvarCeridianPay.payHour
    dsDetail.Fields("CERIDIAN_RATE_CODE").Value = mvarCeridianPay.rateCode
    dsDetail.Fields("CERIDIAN_PAY_RATE").Value = mvarCeridianPay.payRate
    dsDetail.Fields("CERIDIAN_SERVICE_CODE").Value = mvarCeridianPay.ServShiftEquipCode
    dsDetail.Fields("PROCESS_DATE").Value = Now
    dsDetail.Update
    
    If mvarExtraPay.payCode <> "" Then
        dsDetail.AddNew
        dsDetail.Fields("HIRE_DATE").Value = Format(mvarHireDate, "MM/DD/YYYY")
        dsDetail.Fields("EMPLOYEE_ID").Value = mvarEmployeeID
        dsDetail.Fields("CERIDIAN_PAY_CODE").Value = mvarExtraPay.payCode
        dsDetail.Fields("CERIDIAN_PAY_HOUR").Value = mvarExtraPay.payHour
        dsDetail.Fields("CERIDIAN_RATE_CODE").Value = mvarExtraPay.rateCode
        dsDetail.Fields("CERIDIAN_PAY_RATE").Value = mvarExtraPay.payRate
        dsDetail.Fields("CERIDIAN_SERVICE_CODE").Value = mvarExtraPay.ServShiftEquipCode
        dsDetail.Fields("PROCESS_DATE").Value = Now
    End If
    
    If OraDatabase.LastServerErr = 0 Then
        writeCeridianPayOLD = True
    Else
        writeCeridianPayOLD = False
    End If
End Function

Public Function SpecialRateAddition(ServiceCode As String, EarningType As String)
' due to the "Special Code" field no longer being usable, it has been determined that we will start
' taking Service codes and, if said code warrants a pay above and beyond a checker's normal rate,
' will add an additional line to the output file the same way special codes used to.
' ~ Adam Walter, Oct 2008.

    Dim OTmultiplier As Long
'    If EarningType = "DT" Then
'        OTmultiplier = 2
'    ElseIf EarningType = "OT" Then
'        OTmultiplier = 1.5
'    Else
'        OTmultiplier = 1
'    End If


    Dim sql As String
    Dim dsSHORT_TERM_DATA As Object
    
    sql = "SELECT MULTIPLIER_VALUE FROM EARNING_MULTIPLER WHERE EARNING_TYPE = '" & EarningType & "'"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)
    If dsSHORT_TERM_DATA.RecordCount = 0 Then
        OTmultiplier = 1
    Else
        OTmultiplier = dsSHORT_TERM_DATA.Fields("MULTIPLIER_VALUE").Value
    End If
    
    sql = "SELECT NEW_RATE FROM LCS_SERVICE_CODE_EXCEPTIONS WHERE SERVICE_CODE = '" & ServiceCode & "' AND RATE_TYPE = 'ADDED'"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)
    
    If dsSHORT_TERM_DATA.RecordCount = 0 Then
        SpecialRateAddition = ""
    Else
        SpecialRateAddition = dsSHORT_TERM_DATA.Fields("NEW_RATE").Value * OTmultiplier
    End If
    
 
End Function

Public Function SpecialRateOverwrite(ServiceCode As String, payRate As String)
' due to the "Special Code" field no longer being usable, it has been determined that we will start
' taking Service codes and, if said code warrants a pay other than a checker's normal rate,
' will change a line to the output file.
' ~ Adam Walter, Oct 2008.

    Dim sql As String
    Dim dsSHORT_TERM_DATA As Object
    
    sql = "SELECT NEW_RATE FROM LCS_SERVICE_CODE_EXCEPTIONS WHERE SERVICE_CODE = '" & ServiceCode & "' AND RATE_TYPE = 'REPLACE'"
    Set dsSHORT_TERM_DATA = OraDatabase.DBCreateDynaset(sql, 0&)
    
    If dsSHORT_TERM_DATA.RecordCount = 0 Then
        SpecialRateOverwrite = payRate
    Else
        SpecialRateOverwrite = dsSHORT_TERM_DATA.Fields("NEW_RATE").Value
    End If
    
 
End Function

