Attribute VB_Name = "modGlobal"
Option Explicit

Global OraSession As Object
Global OraDatabase As Object
Global OraDatabaseRF As Object
Global dsDummy As Object

'Global ds Dummy_max As Object            '5/14/2007 only in save_dummy
'Global ds Dummy_verify As Object         '5/14/2007 only in lost focus
'Global ds Dummy_Del As Object            '5/14/2007 only in save_dummy
'Global ds CARGO_ACTIVITY As Object       '5/14/2007 only in lost_focus
'Global ds CARGO_ACTIVITY_EXT As Object   '5/14/2007 only in lost_focus
'Global ds CARGO_ACTIVITY_MAX As Object   '5/14/2007 not used
'Global ds CARGO_DELIVERY As Object       '5/14/2007 only in lost_focus
'Global dsCARGO_DELIVERY_MAX As Object    '5/14/2007 not used

Global dsCARGO_MANIFEST As Object
Global dsCARGO_TRACKING_RF As Object

'Global ds CARGO_TRACKING As Object       '5/14/2007 only in lost_focus

Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsPERSONNEL As Object

'Global dsVOYAGE_CARGO As Object          '5/14/2007 not used
'Global ds Delivery_Remark As Object      '5/14/2007 only in lost_focus
'Global gsPassword As String              '5/14/2007 not used
'Global dsCARGO_DELIVERY_DELETE As Object '5/14/2007 not used

Global gsPVItem As String
Global gsSqlStmt As String
Global stmt As String

'Array to hold DeliverTo and Remarks
Global giDelDetIndex As Integer

