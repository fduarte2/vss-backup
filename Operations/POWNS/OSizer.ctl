VERSION 5.00
Begin VB.UserControl OSizer 
   BackColor       =   &H80000005&
   ClientHeight    =   4320
   ClientLeft      =   0
   ClientTop       =   0
   ClientWidth     =   5985
   ControlContainer=   -1  'True
   DefaultCancel   =   -1  'True
   ScaleHeight     =   4320
   ScaleWidth      =   5985
   Begin VB.PictureBox pic 
      BorderStyle     =   0  'None
      Height          =   4335
      Left            =   2880
      ScaleHeight     =   4335
      ScaleWidth      =   135
      TabIndex        =   0
      Top             =   0
      Width           =   135
   End
End
Attribute VB_Name = "OSizer"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = True
Attribute VB_PredeclaredId = False
Attribute VB_Exposed = False
Option Explicit

Public Enum enumOrientation
    Horizontal
    Vertical
End Enum

Public Enum enumStyle
    PlainStyle
    FancyStyle
End Enum

Public Enum enumPosUnits
    Percentage
    AbsTwips
End Enum


Private Type typDimensions
    dimLeft As Single
    dimTop As Single
    dimWidth As Single
    dimHeight As Single
    blnFirstHalf As Boolean  'true or false, depending on which side of the split bar the control falls (top left corner)
End Type
Private intMyWidth(0 To 1) As Single, intMyHeight(0 To 1) As Single

Private m_sngInitialPosition As Single
Private m_blnHorizontal As Boolean
Private m_intSplitBarWidth As Integer
Private m_lngSplitColour As Long
Private m_lngDragColour As Long
Private m_lngBackColour As Long
Private m_blnFancyBar As Boolean    'true means borderstyle 1 on pic
Private m_blnExpandOneEachHalf As Boolean  'if true, and if there is one control per half, each will be sized to fill that half
Private m_blnSizerOnly As Boolean  'if true, there is no split bar, and the control only sizes.
Private m_PositionUnits As enumPosUnits

Private Dimensions() As typDimensions
Private intScaleWidth As Integer, intScaleHeight As Integer  'initial size so that positions are useful.
Private intPicLeft As Integer, intPicTop As Integer 'initial location so that positions are correct
Private blnResize As Boolean    'set to true at run-time when BeginResize is called.
Private blnSizeCursor  As Boolean    'true if can change back to default--used for mousepointer stuff
Private blnPropsRead As Boolean
Private m_LTLimit As Long
Private m_RBLimit As Long
'variables for sliding splitter
Private blnSliding As Boolean
Private sngStartPos As Single

Const intDEFAULT_WIDTH As Integer = 80

Public Event Resize()

'---------
Private Sub GetPositions()
    Dim i As Integer
    Dim ctl As Control
     
    If ContainedControls.Count > 0 Then
        ReDim Dimensions(0 To ContainedControls.Count - 1)
        For i = 0 To ContainedControls.Count - 1
            Set ctl = ContainedControls(i)
            Dimensions(i).dimLeft = ctl.left
            Dimensions(i).dimTop = ctl.top
            Dimensions(i).dimWidth = ctl.Width
            Dimensions(i).dimHeight = ctl.Height
        Next i
    End If
    
    blnResize = True
    
    'PrintInfo "GetPositions"
End Sub

Private Sub ActivateResize()
    Dim i As Integer
    
    Static blnAlreadyDone As Boolean
    If blnAlreadyDone Then Exit Sub
    blnAlreadyDone = True
    
    If UserControl.ContainedControls.Count > 0 Then
'        MoveSplitBar
        For i = 0 To UserControl.ContainedControls.Count - 1
            If m_blnHorizontal Then
                'middle of control determins side (first half or not)
                Dimensions(i).blnFirstHalf = Dimensions(i).dimTop + (Dimensions(i).dimHeight / 2) < intPicTop
                Dimensions(i).dimLeft = Dimensions(i).dimLeft
                Dimensions(i).dimWidth = Dimensions(i).dimWidth
                If Dimensions(i).blnFirstHalf Then  'top half
                    Dimensions(i).dimTop = Dimensions(i).dimTop
                    'make sure it isn't too big
                    If Dimensions(i).dimTop + Dimensions(i).dimHeight <= intPicTop Then
                        Dimensions(i).dimHeight = Dimensions(i).dimHeight
                    Else 'put it right against the pic split bar
                        Dimensions(i).dimHeight = intPicTop - Dimensions(i).dimTop
                    End If
                Else  'bottom half
                    'make sure it isn't too big
                    If Dimensions(i).dimTop >= intPicTop + m_intSplitBarWidth Then
                        Dimensions(i).dimTop = Dimensions(i).dimTop - intPicTop - m_intSplitBarWidth
                        Dimensions(i).dimHeight = Dimensions(i).dimHeight
                    Else 'put it right against the pic split bar
                        Dimensions(i).dimTop = 0
                        Dimensions(i).dimHeight = Dimensions(i).dimHeight - ((intPicTop + m_intSplitBarWidth) - Dimensions(i).dimTop)
                    End If
                End If
            Else
                Dimensions(i).blnFirstHalf = Dimensions(i).dimLeft + (Dimensions(i).dimWidth / 2) < intPicLeft
                Dimensions(i).dimTop = Dimensions(i).dimTop
                Dimensions(i).dimHeight = Dimensions(i).dimHeight
                If Dimensions(i).blnFirstHalf Then 'left half
                    Dimensions(i).dimLeft = Dimensions(i).dimLeft
                    'make sure it isn't too big
                    If Dimensions(i).dimLeft + Dimensions(i).dimWidth <= intPicLeft Then
                        Dimensions(i).dimWidth = Dimensions(i).dimWidth
                    Else 'put it right against the pic split bar
                        Dimensions(i).dimWidth = intPicLeft - Dimensions(i).dimLeft
                    End If
                Else  'right half
                    'make sure it isn't too big
                    If Dimensions(i).dimLeft >= intPicLeft + m_intSplitBarWidth Then
                        Dimensions(i).dimLeft = Dimensions(i).dimLeft - intPicLeft - m_intSplitBarWidth
                        Dimensions(i).dimWidth = Dimensions(i).dimWidth
                    Else 'put it right against the pic split bar
                        Dimensions(i).dimLeft = 0
                        Dimensions(i).dimWidth = Dimensions(i).dimWidth - ((intPicLeft + m_intSplitBarWidth) - Dimensions(i).dimLeft)
                    End If
                End If
            End If
        Next i
        If m_blnHorizontal Then
            intMyWidth(0) = intScaleWidth
            intMyWidth(1) = intScaleWidth
            intMyHeight(0) = intPicTop
            intMyHeight(1) = intScaleHeight - intPicTop - m_intSplitBarWidth
        Else
            intMyWidth(0) = intPicLeft
            intMyWidth(1) = intScaleWidth - intPicLeft - m_intSplitBarWidth
            intMyHeight(0) = intScaleHeight
            intMyHeight(1) = intScaleHeight
        End If
        
        'now, make use of the m_blnExpandOneEachHalf option:
        Dim intIndex(0 To 1) As Integer
        If m_blnExpandOneEachHalf Then
            If UserControl.ContainedControls.Count = 2 Then
                'make sure there is exactly one per side...
                If Dimensions(0).blnFirstHalf And Not Dimensions(1).blnFirstHalf Then
                    intIndex(0) = 0
                    intIndex(1) = 1
                ElseIf Not Dimensions(0).blnFirstHalf And Dimensions(1).blnFirstHalf Then
                    intIndex(0) = 1
                    intIndex(1) = 0
                Else
                    GoTo NotExactlyOnePerHalf
                End If
                'first control
                Dimensions(0).dimLeft = 0
                Dimensions(0).dimTop = 0
                Dimensions(0).dimWidth = intMyWidth(intIndex(0))
                Dimensions(0).dimHeight = intMyHeight(intIndex(0))
                'second control
                Dimensions(1).dimLeft = 0
                Dimensions(1).dimTop = 0
                Dimensions(1).dimWidth = intMyWidth(intIndex(1))
                Dimensions(1).dimHeight = intMyHeight(intIndex(1))
NotExactlyOnePerHalf:
            End If
        End If
    End If
    
    'PrintInfo "ActivateResize"
    
End Sub

Private Sub PrintInfo(strCaller As String)
'    Debug.Print strCaller & ": "; "Scalewidth:"; ScaleWidth; "PicLeft:"; pic.left
End Sub

Private Sub pic_MouseDown(Button As Integer, Shift As Integer, X As Single, Y As Single)
    If Button = 1 Then
        'ActivateResize  'just in case this is the first time.
        blnSliding = True
        pic.BackColor = m_lngDragColour
        
        pic.ZOrder 0 'just in case
        If m_blnHorizontal Then
            sngStartPos = Y
        Else
            sngStartPos = X
        End If
    End If
End Sub

Private Sub pic_MouseMove(Button As Integer, Shift As Integer, X As Single, Y As Single)
    Dim sngNewPos As Single
    
    If blnSliding Then
        
        If m_blnHorizontal Then
            sngNewPos = pic.top + Y - sngStartPos
            If sngNewPos < m_LTLimit Then
                sngNewPos = m_LTLimit
            ElseIf sngNewPos > UserControl.ScaleHeight - m_intSplitBarWidth - m_RBLimit Then
                sngNewPos = UserControl.ScaleHeight - m_RBLimit - m_intSplitBarWidth
            End If
            pic.top = sngNewPos
            If m_PositionUnits = Percentage Then
                m_sngInitialPosition = (pic.top + m_intSplitBarWidth / 2) / UserControl.ScaleHeight * 100
            Else
                m_sngInitialPosition = pic.top + m_intSplitBarWidth / 2
            End If
        Else 'vertical
            sngNewPos = pic.left + X - sngStartPos
            If sngNewPos < m_LTLimit Then
                sngNewPos = m_LTLimit
            ElseIf sngNewPos > UserControl.ScaleWidth - m_intSplitBarWidth - m_RBLimit Then
                sngNewPos = UserControl.ScaleWidth - m_intSplitBarWidth - m_RBLimit
            End If
            pic.left = sngNewPos
            If m_PositionUnits = Percentage Then
                m_sngInitialPosition = (pic.left + m_intSplitBarWidth / 2) / UserControl.ScaleWidth * 100
            Else
                m_sngInitialPosition = pic.left + m_intSplitBarWidth / 2
            End If
        End If
    End If
End Sub

Private Sub pic_MouseUp(Button As Integer, Shift As Integer, X As Single, Y As Single)
    If blnSliding = True Then
        blnSliding = False
        pic.BackColor = m_lngSplitColour
        ResizeProportionally
    End If
End Sub

Private Sub UserControl_Initialize()
    pic.ZOrder 0
    'PrintInfo "Initialize"
End Sub

Private Sub UserControl_InitProperties()
    m_sngInitialPosition = 50
    m_intSplitBarWidth = intDEFAULT_WIDTH
    m_blnHorizontal = False
    m_lngSplitColour = vbButtonFace
    m_lngDragColour = vb3DShadow
    m_lngBackColour = vbWindowBackground
    m_blnFancyBar = False
    m_blnExpandOneEachHalf = True
    m_blnSizerOnly = False
    m_PositionUnits = Percentage
    m_LTLimit = 0
    m_RBLimit = 0
    
    'PrintInfo "InitProps"
End Sub

Private Sub UserControl_ReadProperties(PropBag As PropertyBag)
    m_sngInitialPosition = PropBag.ReadProperty("InitialPosition", 50)
    m_LTLimit = PropBag.ReadProperty("LTLimit", 0)
    m_blnHorizontal = PropBag.ReadProperty("Horizontal", intDEFAULT_WIDTH)
    
    m_RBLimit = 0 'PropBag.ReadProperty("RBLimit", 0)
    
    m_intSplitBarWidth = PropBag.ReadProperty("SplitBarWidth", intDEFAULT_WIDTH)
    m_lngSplitColour = PropBag.ReadProperty("SplitColour", vbButtonFace)
    pic.BackColor = m_lngSplitColour
    m_lngDragColour = PropBag.ReadProperty("DragColour", vb3DShadow)
    m_lngBackColour = PropBag.ReadProperty("BackColour", vbWindowBackground)
    UserControl.BackColor = m_lngBackColour
    m_blnFancyBar = PropBag.ReadProperty("FancyBar", False)
    If m_blnFancyBar Then
        pic.BorderStyle = 1
    Else
        pic.BorderStyle = 0
    End If
    m_blnExpandOneEachHalf = PropBag.ReadProperty("ExpandOneEachHalf", True)
    m_blnSizerOnly = PropBag.ReadProperty("SizerOnly", False)
    m_PositionUnits = PropBag.ReadProperty("PosUnits", Percentage)
    
    
    'stuff to do now...
    If UserControl.Ambient.UserMode = True Then
        If m_blnHorizontal Then
            pic.MousePointer = vbSizeNS
        Else
            pic.MousePointer = vbSizeWE
        End If
    End If
    
    blnPropsRead = True
    
    MoveSplitBar
    
    blnPropsRead = True
    intScaleHeight = ScaleHeight
    intScaleWidth = ScaleWidth
    intPicLeft = pic.left
    intPicTop = pic.top
    
    'PrintInfo "ReadProps"
    
End Sub

Private Sub UserControl_Resize()
    
    On Error Resume Next
    
    MoveSplitBar
   
    'only do this next part if this is the first time loading in usermode with some controls
    Static blnAlreadyDone As Boolean
    If Not blnAlreadyDone Then
        If Ambient.UserMode = True Then
            If ContainedControls.Count > 0 Then
                GetPositions
                blnAlreadyDone = True
            End If
        End If
    End If
    
    
    'only do if in runtime
    If blnResize And ContainedControls.Count > 0 Then
        ActivateResize
        ResizeProportionally
    End If
    'PrintInfo "Resize"
    
End Sub

Private Sub ResizeProportionally()

    On Error Resume Next

    Dim i As Integer
    Dim ctl As Control
    Dim dblWidRatio(0 To 1) As Double
    Dim dblHeiRatio(0 To 1) As Double
    Dim intHalf As Integer  'either 0 or 1
    
       
    If UserControl.ContainedControls.Count > 0 And blnResize Then
        If m_blnHorizontal Then
            dblWidRatio(0) = UserControl.ScaleWidth / intMyWidth(0)
            dblWidRatio(1) = UserControl.ScaleWidth / intMyWidth(1)
            dblHeiRatio(0) = pic.top / intMyHeight(0)
            dblHeiRatio(1) = (UserControl.ScaleHeight - pic.top - m_intSplitBarWidth) / intMyHeight(1)
        Else
            dblWidRatio(0) = pic.left / intMyWidth(0)
            dblWidRatio(1) = (UserControl.ScaleWidth - pic.left - m_intSplitBarWidth) / intMyWidth(1)
            dblHeiRatio(0) = UserControl.ScaleHeight / intMyHeight(0)
            dblHeiRatio(1) = UserControl.ScaleHeight / intMyHeight(1)
        End If
        For i = 0 To UserControl.ContainedControls.Count - 1
            Set ctl = UserControl.ContainedControls(i)
            If Dimensions(i).blnFirstHalf Then
                intHalf = 0
            Else
                intHalf = 1
            End If
            ctl.Width = Dimensions(i).dimWidth * dblWidRatio(intHalf)
            ctl.Height = Dimensions(i).dimHeight * dblHeiRatio(intHalf)
            If m_blnHorizontal Then
                ctl.left = Dimensions(i).dimLeft * dblWidRatio(intHalf)
                If intHalf = 0 Then
                    ctl.top = Dimensions(i).dimTop * dblHeiRatio(intHalf)
                Else
                    ctl.top = Dimensions(i).dimTop * dblHeiRatio(intHalf) + pic.top + m_intSplitBarWidth
                End If
            Else
                ctl.top = Dimensions(i).dimTop * dblHeiRatio(intHalf)
                If intHalf = 0 Then
                    ctl.left = Dimensions(i).dimLeft * dblWidRatio(intHalf)
                Else
                    ctl.left = Dimensions(i).dimLeft * dblWidRatio(intHalf) + pic.left + m_intSplitBarWidth
                End If
            End If
        Next i
    End If
    RaiseEvent Resize
End Sub

Public Property Get BarInitialPosition() As Integer
    BarInitialPosition = m_sngInitialPosition
End Property

Public Property Let BarInitialPosition(ByVal vNewValue As Integer)
    m_sngInitialPosition = vNewValue
    If m_sngInitialPosition < 0 Then
        m_sngInitialPosition = 0
    ElseIf m_sngInitialPosition > 100 And m_PositionUnits = Percentage Then
        m_sngInitialPosition = 100
    End If
        
    MoveSplitBar
    
    If Not blnPropsRead Then
        intScaleHeight = ScaleHeight
        intScaleWidth = ScaleWidth
        intPicLeft = pic.left
        intPicTop = pic.top
    End If

    ResizeProportionally
End Property

Public Property Get BarColour() As OLE_COLOR
    BarColour = m_lngSplitColour
End Property

Public Property Let BarColour(ByVal vNewValue As OLE_COLOR)
    m_lngSplitColour = vNewValue
    pic.BackColor = m_lngSplitColour
End Property

Public Property Get BarDragColour() As OLE_COLOR
    BarDragColour = m_lngDragColour
End Property

Public Property Let BarDragColour(ByVal vNewValue As OLE_COLOR)
    m_lngDragColour = vNewValue
End Property

Public Property Get BackColour() As OLE_COLOR
    BackColour = m_lngBackColour
End Property

Public Property Let BackColour(ByVal vNewValue As OLE_COLOR)
    m_lngBackColour = vNewValue
    UserControl.BackColor = m_lngBackColour
End Property

Public Property Get BarWidth() As Integer
    BarWidth = m_intSplitBarWidth
End Property

Public Property Let BarWidth(ByVal vNewValue As Integer)
    m_intSplitBarWidth = vNewValue
    If m_intSplitBarWidth < 0 Then
        m_intSplitBarWidth = 0
    ElseIf m_intSplitBarWidth > 2000 Then
        m_intSplitBarWidth = 2000
    End If
    
    MoveSplitBar
End Property

Public Property Get BarStyle() As enumStyle
    If m_blnFancyBar Then
        BarStyle = FancyStyle
    Else
        BarStyle = PlainStyle
    End If
End Property

Public Property Let BarStyle(ByVal vNewValue As enumStyle)
    If vNewValue = FancyStyle Then
        pic.BorderStyle = 1
        m_blnFancyBar = True
    Else
        pic.BorderStyle = 0
        m_blnFancyBar = False
    End If
End Property

Public Property Get hWnd() As Long
    hWnd = UserControl.hWnd
End Property

Public Property Get PositionUnits() As enumPosUnits
    PositionUnits = m_PositionUnits
End Property

Public Property Let PositionUnits(ByVal vNewValue As enumPosUnits)
    m_PositionUnits = vNewValue
    MoveSplitBar
    ResizeProportionally
End Property

Public Property Get BarOrientation() As enumOrientation
    If m_blnHorizontal Then
        BarOrientation = Horizontal
    Else
        BarOrientation = Vertical
    End If
End Property

Public Property Let BarOrientation(ByVal vNewValue As enumOrientation)
    If vNewValue = Horizontal Then
        m_blnHorizontal = True
    Else
        'm_blnExpandOneEachHalf = False
        m_blnHorizontal = False
    End If
    If m_blnHorizontal Then
        pic.MousePointer = vbSizeNS
    Else
        pic.MousePointer = vbSizeWE
    End If
    MoveSplitBar
End Property

Public Property Get SmartSizing() As Boolean
    SmartSizing = m_blnExpandOneEachHalf
End Property

Public Property Let SmartSizing(ByVal vNewValue As Boolean)
    m_blnExpandOneEachHalf = vNewValue
End Property

Public Property Get SizerOnly() As Boolean
    SizerOnly = m_blnSizerOnly
End Property

Public Property Let SizerOnly(ByVal vNewValue As Boolean)
    m_blnSizerOnly = vNewValue
    
    MoveSplitBar
End Property

Private Sub MoveSplitBar()
    
    Dim intHei As Integer, intWid As Integer
    Dim sngRatio As Single
    Dim PosUnits As enumPosUnits
    
    Dim sngInitPos As Single, intBarWid As Integer  'both for internal use, to handle the sizeronly situation
    
    If m_blnSizerOnly Then
        'these settings will force an invisible splitter at far end.
        sngInitPos = 100
        intBarWid = 0
        PosUnits = Percentage
    Else
        sngInitPos = m_sngInitialPosition
        intBarWid = m_intSplitBarWidth
        PosUnits = m_PositionUnits
    End If
    
    
    intHei = ScaleHeight
    intWid = ScaleWidth
    
        
    If m_blnHorizontal Then
        If PosUnits = Percentage Then
            sngRatio = sngInitPos / 100
        Else 'posunits = AbsTwips
            sngRatio = sngInitPos / intHei
        End If
        pic.Move 0, Min(Max(0, intHei * sngRatio - intBarWid / 2), _
          intHei - intBarWid), _
          intWid, intBarWid
    Else
        If PosUnits = Percentage Then
            sngRatio = sngInitPos / 100
        Else 'posunits = AbsTwips
            sngRatio = sngInitPos / intWid
        End If
        pic.Move Min(Max(0, intWid * sngRatio - intBarWid / 2), _
          intWid - intBarWid), 0, _
          intBarWid, intHei
    End If
    
    pic.ZOrder 0
    
    'PrintInfo "MoveSplitBar"
    
End Sub

Private Sub UserControl_WriteProperties(PropBag As PropertyBag)
    PropBag.WriteProperty "InitialPosition", m_sngInitialPosition
    PropBag.WriteProperty "SplitBarWidth", m_intSplitBarWidth
    PropBag.WriteProperty "Horizontal", m_blnHorizontal
    PropBag.WriteProperty "SplitColour", m_lngSplitColour
    PropBag.WriteProperty "DragColour", m_lngDragColour
    PropBag.WriteProperty "BackColour", m_lngBackColour
    PropBag.WriteProperty "FancyBar", m_blnFancyBar
    PropBag.WriteProperty "ExpandOneEachHalf", m_blnExpandOneEachHalf
    PropBag.WriteProperty "SizerOnly", m_blnSizerOnly
    PropBag.WriteProperty "PosUnits", m_PositionUnits
    PropBag.WriteProperty "LTLimit", m_LTLimit
    PropBag.WriteProperty "RBLimit", m_RBLimit
    'PrintInfo "WriteProps"
End Sub

Private Function Max(ByVal vnt1 As Variant, ByVal vnt2 As Variant) As Variant
    If vnt1 > vnt2 Then
        Max = vnt1
    Else
        Max = vnt2
    End If
End Function

Private Function Min(ByVal vnt1 As Variant, ByVal vnt2 As Variant) As Variant
    If vnt1 < vnt2 Then
        Min = vnt1
    Else
        Min = vnt2
    End If
End Function

Public Property Get LTLimit() As Long
    LTLimit = m_LTLimit
End Property

Public Property Let LTLimit(ByVal vNewValue As Long)
    If vNewValue >= 0 Then m_LTLimit = vNewValue
End Property

Public Property Get RBLimit() As Variant
    RBLimit = m_RBLimit
End Property

Public Property Let RBLimit(ByVal vNewValue As Variant)
    If m_RBLimit < 0 Or vNewValue > UserControl.ScaleWidth Then
        m_RBLimit = 0
    Else
        m_RBLimit = vNewValue
    End If
End Property
