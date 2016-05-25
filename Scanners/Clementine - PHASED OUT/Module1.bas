Attribute VB_Name = "Module1"
Option Explicit

Global ServerName As String
Global OraSession As Object
Global OraDatabase As Object
Global accessDb As Database
Global dsACTIVITY_MAX_NUM As Object
Global dsCARGO_ACTIVITY As Object
Global dsCARGO_ACTIVITY_A As Object
Global dsCARGO_ACTIVITY_B As Object
Global dsCARGO_ACTIVITY_C As Object
Global dsCARGO_ACTIVITY_D As Object
Global dsCARGO_ACTIVITY_E As Object
Global dsCARGO_ACTIVITY_F As Object
Global dsCARGO_RETURN_ACTIVITY As Object
Global dsCARGO_RETURN_COUNT As Object
Global dsCARGO_SHIP_COUNT As Object
Global dsCARGO_TRANSFER_CHECK As Object
Global dsCARGO_TRANSFER_TO_CUSTOMER As Object
Global dsCARGO_TRANSFER_FROM_CUSTOMER As Object
Global dsCARGO_ACTIVITY_SUM As Object
Global dsCARGO_ACTIVITY_ALL As Object
Global dsORDER_HEADER As Object
Global dsCARGO_ACTIVITY_MAX_ACTIVITY_NUM As Object
Global dsCARGO_ACTIVITY_UPDATE_CUSTOMER As Object
Global dsCARGO_ACTIVITY_CHECK As Object
Global dsCARGO_MANIFEST As Object
Global dsCARGO_TRACKING As Object
Global dsCCD_LOCATION As Object
Global dsSEAL_ORDERS As Object
Global dsORDER_DETAIL As Object
Global dsCARGO_DAMAGE As Object
Global dsDAMAGE_LOG As Object

Global dsCARGO_TRACKING_CLEAR_DESCRIPTION As Object
Global dsPALLET_VOID_ALL As Object
Global dsCARGO_TRACKING_DATE As Object
Global dsCARGO_TRACKING_ALL As Object
Global dsCOMMODITY_PROFILE As Object
Global dsCUSTOMER_PROFILE As Object
Global dsVESSEL_PROFILE As Object
Global dsVOYAGE_CARGO As Object
Global dsVOYAGE_CARGO_ALL As Object
Global dsPERSONNEL_A As Object
Global dsLOCATION_CATEGORY As Object
Global dsPERSONNEL As Object
Global dsACTIVITY_LOG As Object
Global dsWING_PROFILE As Object
Global gsSqlStmt As String
Global BCField(40) As String
Global TempAddr As String
Global fTime As Boolean
Global LTime
Global iFld As Integer
Global oraErr As Long
Global oraErrStr As String
Global Barcode As String
Global iFirstTime As Boolean
Global rsxpxTranPallets As Recordset
Global rsPalletMaster As Recordset
Global iValidateString As String
Global iValidatePallet As Boolean
Global iOrderNumber As String
Global dsPL_ORDER_HEAD As Object
Global iPalletNumber As String
Global iOdbcErr As Boolean

Global UnloadLRNum As String
''Global bc As String  '' BarCode

