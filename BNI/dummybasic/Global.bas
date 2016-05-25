Attribute VB_Name = "modGlobal"
Option Explicit


Global OraSession As Object
Global OraDatabase As Object
Global dsDummy As Object
Global dsDummy_max As Object
Global dsDummy_verify As Object
Global dsDummy_Del As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_EXT As Object
Global dsCARGO_ACTIVITY_MAX As Object
Global dsCARGO_DELIVERY As Object
Global dsCARGO_DELIVERY_MAX As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_TRACKING As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsPERSONNEL As Object
Global dsVOYAGE_CARGO As Object
Global dsDelivery_Remark As Object
Global gsPassword As String
Global dsCARGO_DELIVERY_DELETE As Object
Global gsPVItem As String
Global gsSqlStmt As String
Global stmt As String

'Array to hold DeliverTo and Remarks
Global giDelDetIndex As Integer

