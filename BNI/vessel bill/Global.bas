Attribute VB_Name = "modGlobal"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
'Global OraDatabase2 As Object
Global dsCOUNTRY As Object
Global dsCUSTOMER_PROFILE As Object
Global dsBERTH_NUM As Object
Global dsFLEET As Object
Global dsVESSEL_PROFILE As Object
Global dsCARGO_STATUS As Object
Global dsVOYAGE As Object
Global gsPVItem As String
Global gsSqlStmt As String
Global dsSHIPNO_MAX As Object
Global dsCustNo As Object
Global dsAgentNo As Object
Global dsEMP_PROFILE As Object
Global dsCARGO_IMPMANIFEST As Object
Global dsCARGO_EMPMANIFEST As Object
Global dsCARGO_PROFCHECK As Object
Global dsCARGO_PROFSUMMURY As Object
Global dsCARGO_JUICE As Object
Global dsTERMINAL_RATES As Object
Global dsCARGO_PROFILE As Object
Global dsFREE_TIME As Object
Global dsComeCode As Object
Global dsIMPEX As Object
Global dsTRUCK_HANDLING_RATE As Object
Global dsUNIT_CONVERSION As Object
Global dsPreInv As Object
Global dsBILLING As Object
Global dsBILLING_MAX As Object
Global dsBILLING_CONF As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_MANIFEST_SUM As Object
Global dsCOMMODITY_PROFILE As Object
Global dsSERVICE_CATEGORY As Object
Global dsVESSEL_RATE As Object
Global gsRecipientIdWharfage(15) As String
Global dsSERVICE_PROFILE As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_TRACKING As Object
Global dsVOYAGE_CARGO As Object
Global dsUNITS As Object
Global dsLOCATION_CATEGORY As Object
Global dsVOLUME_DISCOUNT As Object
Global dsINVDATE As Object
Global dsSHORT_TERM_DATA As Object
Global dsID As Object
Global gCont As Double 'getting container number
Global gsave As Boolean 'for save
Global gcontinue As Boolean 'for continue
Global gLotNum As String
Global gQtyRcvd As Double
Global gQtyExpct As Double
Global gQtyInHus As Double
Global gQtyChng As Double
Global iCheck As Boolean
Public gComcode As String
Public gEcode As String
Public lSHIPNO As Long
Global dsLOCATION As Object
Public bRetrive As Boolean
Global dsCom_NO As Object
Global dsRA_CUSTOMER As Object
Global dsFND_FLEX_VALUE As Object
Global dsAssetProfile As Object
Global dsSECURITY_LINES As Object ' used in 3 places in the Added Security function.
Global dsWEIGHT_CONVERSION As Object


Public Function ComboIndex(cbo As ComboBox, asItem As String, asColon As Integer) As Long
    
    Dim i As Integer
    Dim iFound As Integer
    Dim sTempItem As String
    Dim sTempKey As String
    Dim iPos As Integer
    
    sTempItem = UCase$(asItem)
    If sTempItem = "" Then
        ComboIndex = -1
    Else
        iFound = False
        For i = 0 To cbo.ListCount - 1
            If asColon Then
                iPos = InStr(cbo.List(i), "-")
                If iPos > 0 Then
                    sTempKey = Trim(UCase$(Mid$(cbo.List(i), iPos + 1)))
                Else
                    sTempKey = ""
                End If
            Else
                sTempKey = UCase$(cbo.List(i))
            End If
            If sTempItem = sTempKey Then
                iFound = True
                Exit For
            End If
        Next i
        If iFound Then
            ComboIndex = i
        Else
            ComboIndex = -1
        End If
    End If
    
End Function
Public Function NVL(avIn As Variant, avOut As Variant) As Variant

    If IsNull(avIn) Then
        NVL = avOut
    Else
        If Len(Trim$(avIn)) = 0 Then
            NVL = avOut
        Else
            NVL = avIn
        End If
    End If
    
End Function
