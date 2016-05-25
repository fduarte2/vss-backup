Attribute VB_Name = "Module1"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_EXT As Object
Global dsCARGO_ACTIVITY_MAX As Object
Global dsCARGO_DELIVERY As Object
Global dsCARGO_DELIVERY_MIN As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_TRACKING As Object
' Cargo Tracking B added by Adam Walter, 4/14/2006
Global dsCARGO_TRACKING_B As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsCUSTOMER_PROFILE_A As Object
' Customer Profile B added by Adam Walter, 4/14/2006
Global dsCUSTOMER_PROFILE_B As Object
Global dsCARGO_ORIGINAL_MANIFEST As Object
Global dsVESSEL_PROFILE As Object
Global dsVOYAGE_CARGO As Object
'--
Global dsSTORAGE_RATE As Object
Global dsCARGO_MANIFEST1 As Object
Global dsLOCATION_CATEGORY As Object
Global dsFREETIMEFROM_RATE As Object

Global dsSHORT_TERM_DATA As Object


'---
Global dsCARGO_MANIFEST_A As Object
Global dsCARGO_MANIFEST_B As Object
Global dsCARGO_TRACKING_A As Object
Global dsBILLING_MAX As Object
Global dsBILLING As Object
Global dsSERVICE_CATEGORY As Object
Global dsTRANSFER_RATE As Object
Global SERVICE_CATEGORY As Object
Global gsPVItem As String
Global gsSqlStmt As String
Global sSqlStmt As String
Global glBillingNum As Long

'Array to hold DeliverTo and Remarks
Global giDelDetIndex As Integer


