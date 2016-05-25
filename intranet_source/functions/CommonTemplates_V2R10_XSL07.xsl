<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:param name="WIDTH">640</xsl:param>

<xsl:variable name="default-width"><xsl:value-of select="$WIDTH"/></xsl:variable>

<xsl:attribute-set name="default-table-center-640">
	<xsl:attribute name="border">0</xsl:attribute>
	<xsl:attribute name="cellspacing">0</xsl:attribute>
	<xsl:attribute name="cellpadding">0</xsl:attribute>
	<xsl:attribute name="align">center</xsl:attribute>
	<xsl:attribute name="width">
           <xsl:value-of select="$default-width"/>
	</xsl:attribute>
</xsl:attribute-set>

<xsl:template match="TotalNumberOfLineItems">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="250">
				<tr>
					<td class="DeliveryInstructionsStyle1" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="DeliveryInstructionsStyle2">
						<xsl:value-of select="."/>
					</td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="TotalQuantity | TotalInformationalQuantity">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<span class="DeliveryInstructionsStyle1">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</span>
		</td>
		<td>
			<xsl:apply-templates select="Value"/>
			<xsl:if test="string-length(@QuantityType)!='0'">
				<xsl:apply-templates select="@QuantityType"/>
			</xsl:if>
			<xsl:if test="string-length(@AdjustmentType)!='0'">
				<xsl:apply-templates select="@AdjustmentType"/>
			</xsl:if>
			<xsl:if test="string-length(@QuantityTypeContext)!='0'">
				<xsl:apply-templates select="@QuantityTypeContext"/>
			</xsl:if>
			<br/>
		</td>
		<td bgcolor="#F5F5F5">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="TotalAmount">
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<span class="DeliveryInstructionsStyle1">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</span>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5" class="DeliveryInstructionsStyle2">
			<xsl:apply-templates/>
		</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="TermsAndDisclaimers">
	<tr>
		<td bgcolor="#F5F5F5" valign="top" colspan="4">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="190" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="SummaryStyle2" valign="top">
						<xsl:value-of select="."/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</xsl:template>

<xsl:template match="Text">
	<xsl:value-of select="."/><br/><!--
	<xsl:apply-templates select="../@Language"/><br/>-->
</xsl:template>

<xsl:template match="Value">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="DeliveryInstructionsStyle2">
				<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
			</td>
		</tr>
		<tr>
			<td class="DeliveryInstructionsStyle2">
				<xsl:if test="../RangeMin | ../RangeMax">
					<xsl:text> (</xsl:text>
				</xsl:if>
				<xsl:if test="../RangeMin">
					<xsl:text>Min: </xsl:text>
					<xsl:apply-templates select="../RangeMin"/>
				</xsl:if>
				<xsl:if test="../RangeMin !='0'">
					<xsl:if test="../RangeMax !='0'">
						<xsl:text>, </xsl:text>
					</xsl:if>
				</xsl:if>
				<xsl:if test="../RangeMax">
					<xsl:text>Max: </xsl:text>
					<xsl:apply-templates select="../RangeMax"/>
				</xsl:if>
				<xsl:if test="../RangeMin | ../RangeMax">
					<xsl:text>)</xsl:text>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="CurrencyValue">
	<xsl:value-of select="."/>&#160;
	<xsl:apply-templates select="@CurrencyType"/>
</xsl:template>

<xsl:template match="RangeMin | RangeMax">
	<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
</xsl:template>

<xsl:template match="Product">
	<xsl:if test="string-length(Paper/Sheet|Paper/Reel|Pulp/NonStandardPulp|Pulp/Bale|Pulp/Reel|Pulp/Slurry|RecoveredPaper|BookManufacturing/PackagingCharacteristics/BoxCharacteristics|BookManufacturing/PackagingCharacteristics/PalletPackagingCharacteristics)!='0'">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$ProductType"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:if test="string-length(Paper/Sheet)!='0'">
					<xsl:value-of select="$Sheet"/>
				</xsl:if>
				<xsl:if test="string-length(Paper/Reel)!='0'">
					<xsl:value-of select="$Reel"/>
				</xsl:if>
				<xsl:if test="string-length(../../CallOffProductConversionType/CallOffSheet)!='0'">
					<xsl:value-of select="$Sheet"/>
				</xsl:if>
				<xsl:if test="string-length(../../CallOffProductConversionType/CallOffReel)!='0'">
					<xsl:value-of select="$Reel"/>
				</xsl:if>
				<xsl:if test="string-length(Pulp/NonStandardPulp)!='0'">
					<xsl:value-of select="$NonStandardPulp"/>
				</xsl:if>
				<xsl:if test="string-length(Pulp/Bale)!='0'">
					<xsl:value-of select="$Bale"/>
				</xsl:if>
				<xsl:if test="string-length(Pulp/Reel)!='0'">
					<xsl:value-of select="$Reel2"/>
				</xsl:if>
				<xsl:if test="string-length(Pulp/Slurry)!='0'">
					<xsl:value-of select="$Slurry"/>
				</xsl:if>
				<xsl:if test="string-length(RecoveredPaper)!='0'">
					<xsl:value-of select="$RecoveredPaper"/>
				</xsl:if>
				<xsl:if test="string-length(BookManufacturing/PackagingCharacteristics/BoxCharacteristics)!='0'">
					<xsl:value-of select="$BooksInBox"/>
				</xsl:if>
				<xsl:if test="string-length(BookManufacturing/PackagingCharacteristics/PalletPackagingCharacteristics)!='0'">
					<xsl:value-of select="$BooksInPallet"/>
				</xsl:if>
			</td>
		</tr>
	</table>
	</xsl:if>
	<xsl:apply-templates select="BookManufacturing"/>
	<xsl:apply-templates select="Paper"/>
	<xsl:apply-templates select="Pulp"/>
	<xsl:apply-templates select="RecoveredPaper"/>
	<xsl:apply-templates select="ProductDescription"/>
	<xsl:apply-templates select="Classification"/>
</xsl:template>

<xsl:template match="ProductIdentifier">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle2" valign="top">
					<xsl:value-of select="."/>
				</td>
			</tr>
			<xsl:apply-templates select="@ProductIdentifierType"/>
		</table>
		<xsl:apply-templates select="@Agency"/>
</xsl:template>

<xsl:template match="PurchaseOrderReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@PurchaseOrderReferenceType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:apply-templates select="@PurchaseOrderReferenceType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="Quantity | InformationalQuantity">
	<xsl:apply-templates select="Value"/>
	<xsl:if test="string-length(@QuantityType)!='0'">
		<xsl:apply-templates select="@QuantityType"/>
	</xsl:if>
	<xsl:if test="string-length(@AdjustmentType)!='0'">
		<xsl:apply-templates select="@AdjustmentType"/>
	</xsl:if>
	<xsl:if test="string-length(@QuantityTypeContext)!='0'">
		<xsl:apply-templates select="@QuantityTypeContext"/>
	</xsl:if>
</xsl:template>



<xsl:template match="ClassificationCode | EmbossingCode | DeliveryRouteCode | TransportModeCode | TransportVehicleCode | TransportUnitCode | TransportLoadingCode | TransportInstructionCode | TermsOfPaymentCode | CoreStrengthCode | ColourShade | PackagingCode | LocationCode | InventoryClassCode | ComplaintReasonCode | ComplaintResponseReasonCode">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@Agency">
			<tr>
				<td>&#160;</td>
				<td>
					<xsl:apply-templates select="@Agency"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="PaperCharacteristics">
	<xsl:if test="@CoatingTop | @CoatingBottom | @FinishType | @PrintType">
		<xsl:apply-templates select="@CoatingTop | @CoatingBottom | @FinishType | @PrintType"/>
		<br/>
	</xsl:if>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="Abrasion | AbsorptionInk | AbsorptionLight | AbsorptionWater | Ash | BendingResistance | BendingStiffness | Brightness | Bulk | Burst | BurstIndex | Caliper | CoatWeight | Density | Dirt | DominantWavelength | FibreLength | FibreClassification | Folding | Formation | Freeness | Friction | Gloss | LightScattering | Moisture | Opacity | Permeability | pH | PlyBond | Porosity | PostConsumerWaste | PreConsumerWaste | Recycled | Resistance | RingCrush | Roughness | RunnabilityIndex | Sizing | Smoothness | Stiffness | Stretch | SurfaceStrength | TEA | Tear | TearIndex | Tensile | TensileIndex | Viscosity | Whiteness | Appearance | ShowThrough | DrainageResistance">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<xsl:apply-templates select="DetailValue"/>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<xsl:apply-templates select="@TestMethod | @TestAgency | @SampleType | @ResultSource"/>
				<xsl:apply-templates select="StandardDeviation | SampleSize | TwoSigmaLower | TwoSigmaUpper"/>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>

<xsl:template match="DetailValue">
	<td class="DeliveryInstructionsStyle2">
		<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
		<xsl:if test="../DetailRangeMin | ../DetailRangeMax">
			<xsl:text> (</xsl:text>
		</xsl:if>
		<xsl:if test="../DetailRangeMin">
			<xsl:text>Min: </xsl:text>
			<xsl:apply-templates select="../DetailRangeMin"/>
		</xsl:if>
		<xsl:if test="../DetailRangeMin !='0'">
			<xsl:if test="../DetailRangeMax !='0'">
				<xsl:text>, </xsl:text>
			</xsl:if>
		</xsl:if>
		<xsl:if test="../DetailRangeMax">
			<xsl:text>Max: </xsl:text>
			<xsl:apply-templates select="../DetailRangeMax"/>
		</xsl:if>
		<xsl:if test="../DetailRangeMin | ../DetailRangeMax">
			<xsl:text>)</xsl:text>
		</xsl:if>
	</td>
</xsl:template>

<xsl:template match="DetailRangeMin | DetailRangeMax">
	<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
</xsl:template>

<xsl:template match="AdditionalTest">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<xsl:apply-templates select="DetailValue"/>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<xsl:apply-templates select="@TestMethod | @TestAgency | @SampleType | @ResultSource"/>
				<xsl:apply-templates select="AdditionalTestName | StandardDeviation | SampleSize | TwoSigmaLower | TwoSigmaUpper"/>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>

<xsl:template match="AdditionalTestName">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>



<xsl:template match="MonetaryAdjustmentStartQuantity | AdjustmentPercentage | TransportVehicleLength | TransportVehicleWidth | TransportVehicleHeight | TransportVehicleWeight | TransportUnitLength | TransportUnitWidth | TransportUnitHeight | TransportUnitWeight | TermsDiscountPercent | PPI | ReelWidth | ReelDiameter | ReelLength | TargetProductWeight | CoreDiameterInside | CoreDiameterOutside | MaximumHeight | MaximumGrossWeight | PalletLength | PalletWidth | Length | Width | BaleHeight | BaleWidth | BaleDepth | BaleStandardWeight | ItemCount | LengthFromCore |DistanceFromCore | TargetSolidsContent | TargetMoisture | BasisWeightVariation | Weight | UnitsPerCarton | Height | Thickness">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<xsl:apply-templates select="Value"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="MonetaryAdjustmentStartAmount | InformationalAmount | MonetaryAdjustmentAmount" mode="Summary">
	<xsl:apply-templates select="CurrencyValue"/>
</xsl:template>

<xsl:template match="MonetaryAdjustmentStartQuantity" mode="Summary">
	<xsl:apply-templates select="Value"/>
</xsl:template>

<xsl:template match="PriceAdjustment" mode="Summary">
	<xsl:apply-templates select="AdjustmentPercentage" mode="Summary"/>
	<xsl:apply-templates select="AdjustmentValue" mode="Summary"/>
</xsl:template>

<xsl:template match="CoreCharacteristics">
	<xsl:apply-templates select="@CoreEndType | @CoreMaterialType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="AdjustmentValue" mode="Summary">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="180" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td valign="top">
						<span class="DeliveryInstructionsStyle2">
							<xsl:apply-templates select="CurrencyValue"/>
						</span> / 
						<xsl:apply-templates select="Value"/>
					</td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="AdjustmentPercentage" mode="Summary">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="175" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="SummaryStyle2">
						<xsl:apply-templates select="Value"/>
					</td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="ClassificationDescription | EmbossingDescription | AdditionalText | ReelsPerPack | EndCaps | CorePlugs | Brand | NumberOfWraps | NumberOfBands | BandColour | ItemsPerPallet | StacksPerPallet | TiersPerPallet | StackingMethod | LabelStyle | LabelBrandName | LabelPosition | NumberOfLabels | SampleSize | NumberOfPlies | PlyNumber | StencilText | CustomerMarks | PaperSizeType | NumberOfHoles | PerCarton | PerReam | PerPallet | RecoveredPaperAttributes | MonetaryAdjustmentReferenceLine | GeneralLedgerAccount | TaxPercent | TaxLocation | MachineID  | TransportModeText | TransportVehicleCount | TransportVehicleText | TransportUnitCount | TransportUnitText | TransportLoadingText | TransportInstructionText | BalesPerUnit | LayersPerUnit | MethodOfPayment | TermsNetDaysDue | TermsDiscountDaysDue | ColourDescription | MillOrderNumber | PurchaseOrderLineItemNumber | PurchaseOrderNumber | PurchaseOrderReleaseNumber | CallOffLineItemText | NumberOfPackages | TambourID | SetNumber | SetPosition | TotalSetPositions | TotalSetNumbers | NumberOfMillJoins | MillJoinNumber | PerTab | WindingDirection | GrainDirection | MakeTo | ProductDescription | InventoryClassDescriptionv | FreightPayableAt | TermsInterestPenaltyPercent | AttachmentFileName | NumberOfAttachments | ColourCode | WatermarkCode | WatermarkDescription | PackagesPerWrap | SuppliedComponentReference | OrderSecondaryStatus | TermsOfChartering | InventoryClassDescription | ComplaintReasonDescription | RequestedAction | ConsumptionProcess | CorrectiveAction | ComplaintResponseReasonDescription | DeliveryMessageNumber | SupplierOrderNumber | SupplierOrderLineItemNumber | TransportStatus">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="StandardDeviation | TwoSigmaLower | TwoSigmaUpper">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="ReelPackagingCharacteristics">
	<xsl:apply-templates select="@ActionType"/>
	<xsl:apply-templates/>
</xsl:template>



<xsl:template match="PackagingDescription">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/><!--<br/>
				<xsl:apply-templates select="@Language"/>-->
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Wrap">
	<xsl:apply-templates select="@WrapType"/>
	<xsl:apply-templates select="@WrapProperties"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="BandCharacteristics">
	<xsl:apply-templates select="@BandType"/>
	<xsl:apply-templates select="@BandsRequired"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="PalletCharacteristics">
	<xsl:apply-templates select="@MixedProductPalletIndicator"/>
	<xsl:apply-templates select="@PalletType"/>
	<xsl:apply-templates select="@PalletLedgeType"/>
	<xsl:apply-templates select="@PalletCoverType"/>
	<xsl:apply-templates select="@PalletAdditionsType"/>
	<xsl:apply-templates select="@PalletTopType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="StencilCharacteristics">
	<xsl:apply-templates select="@StencilType"/>
	<xsl:apply-templates select="@StencilInkType"/>
	<xsl:apply-templates select="@StencilLocation"/>
	<xsl:apply-templates select="@StencilContent"/>
	<xsl:apply-templates select="@StencilFormat"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="SheetPackagingCharacteristics">
	<xsl:apply-templates select="@ActionType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="SheetCount">
	<xsl:apply-templates select="@SheetCountMethodType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="PulpCharacteristics">
	<xsl:apply-templates select="@PulpingProcess | @BleachingProcess | @FibreSource"/>
	<br/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="Bale">
	<xsl:apply-templates select="@BaleType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="PriceDetails">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">
			<xsl:apply-templates select="@PriceQuantityBasis"/>
			<xsl:apply-templates select="@PriceTaxBasis"/>
			<xsl:apply-templates select="AdditionalText"/>
			<xsl:apply-templates select="ExchangeRate"/>
			<br/>
			<xsl:if test="MonetaryAdjustment">
				<xsl:for-each select="MonetaryAdjustment">
					<tr>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
						<td valign="top" bgcolor="#F5F5F5">									
							<xsl:call-template name="MonetaryAdjustmentHeader3"/>
							<xsl:apply-templates select="."/>
						</td>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
						<td valign="top" bgcolor="#F5F5F5">&#160;</td>
					</tr>
				</xsl:for-each>
			</xsl:if>
			<xsl:if test="GeneralLedgerAccount">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F5F5F5">
						<xsl:apply-templates select="GeneralLedgerAccount"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F5F5F5">&#160;</td>
				</tr>
			</xsl:if>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="PricePerUnit | InformationalPricePerUnit">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle2"><xsl:apply-templates select="CurrencyValue"/>/</td>
	 	</tr>
	</table>
	<xsl:apply-templates select="Value" mode="PricePerUnit"/>
</xsl:template>

<xsl:template match="ExchangeRate">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="CurrencyValue"/>&#160;				
				<xsl:call-template name="GetDate"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="@ExchangeRateType"/>
</xsl:template>

<xsl:template match="MonetaryAdjustment">
	<xsl:if test="@AdjustmentType">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle1" width="125">
					<xsl:value-of select="$AdjustmentType"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:value-of select="@AdjustmentType"/>
				</td>
			</tr>
			<xsl:if test="FlatAmountAdjustment">
				<tr>
					<td class="LineItemStyle1" width="125">&#160;</td>
					<td class="LineItemStyle6">
						<xsl:text>[</xsl:text>
						<xsl:value-of select="$FlatAmountAdjustment2"/>
						<xsl:text>]</xsl:text>
					</td>
				</tr>
			</xsl:if>
			<xsl:if test="PriceAdjustment">
				<tr>
					<td class="LineItemStyle1" width="125">&#160;</td>
					<td class="LineItemStyle6">
						<xsl:text>[</xsl:text>
						<xsl:value-of select="$PriceAdjustment2"/>
						<xsl:text>]</xsl:text>
					</td>
				</tr>
			</xsl:if>
			<xsl:if test="TaxAdjustment">
				<tr>
					<td class="LineItemStyle1" width="125">&#160;</td>
					<td class="LineItemStyle6">
						<xsl:text>[</xsl:text>
						<xsl:value-of select="$TaxAdjustment2"/>
						<xsl:text>]</xsl:text>
					</td>
				</tr>
			</xsl:if>
		</table>
	</xsl:if>
	<xsl:apply-templates select="MonetaryAdjustmentStartAmount"/>
	<xsl:apply-templates select="MonetaryAdjustmentStartQuantity"/>
	<xsl:apply-templates select="PriceAdjustment"/>
	<xsl:apply-templates select="InformationalAmount"/>
	<xsl:apply-templates select="MonetaryAdjustmentReferenceLine"/>
	<xsl:apply-templates select="AdditionalText"/>
	<xsl:apply-templates select="GeneralLedgerAccount"/>
	<xsl:apply-templates select="MonetaryAdjustmentAmount"/>
	<xsl:apply-templates select="FlatAmountAdjustment"/>
	<xsl:apply-templates select="TaxAdjustment"/>
	<br/>
</xsl:template>
<xsl:template match="MonetaryAdjustmentStartAmount | MonetaryAdjustmentAmount | TaxAmount | AdjustmentFixedAmount | InformationalAmount | TotalBlanketOrderValue">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="CurrencyValue"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="AdditionalText"/>
</xsl:template>

<xsl:template match="AdjustmentValue">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td valign="top">
				<span class="DeliveryInstructionsStyle2">
					<xsl:apply-templates select="CurrencyValue"/>
				</span> / 
				<xsl:apply-templates select="Value"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="TaxAdjustment">
	<xsl:apply-templates select="@TaxCategoryType | @TaxType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="ValidityPeriod | DateTimeRange">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td valign="top">
				<xsl:apply-templates select="DateTimeFrom"/>
				<xsl:apply-templates select="DateTimeTo"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="DateTimeFrom | DateTimeTo">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="30" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryLineItemStyle2" valign="top">
				<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="ProductionLastDateOfChange | DeliveryLastDateOfChange | LastDateOfChange | TransportStatusIssueDate">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle5" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="LineItemStyle2" valign="top">
							&#160;<xsl:call-template name="GetDate"/><br/>&#160;<xsl:value-of select="Time"/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="ProductionStatus">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="@ProductionStatusType"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="DeliveryStatus">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="@DeliveryStatusType"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="OtherDate">
	<xsl:if test="Date">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$DeliveryDate3"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="Week">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$Week3"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:value-of select="Week"/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates select="@DateType"/>
</xsl:template>

<xsl:template match="TermsOfDelivery">
	<xsl:choose>
		<xsl:when test="../../../PurchaseOrderLineItem">
			<xsl:call-template name="TermsOfDeliveryHeader"/>
		</xsl:when>
		<xsl:when test="../../../OrderConfirmationLineItem">
			<xsl:call-template name="TermsOfDeliveryHeader"/>
		</xsl:when>
		<xsl:when test="../../../InvoiceLineItem">
			<xsl:call-template name="TermsOfDeliveryHeader2"/>
		</xsl:when>
	</xsl:choose>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="ShipmentMethodOfPayment">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125" valign="top">
				<xsl:value-of select="$Terms2"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:apply-templates select="@Method"/>&#160;
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="@LocationQualifier"/>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="IncotermsLocation">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" valign="top" width="125">
				<xsl:value-of select="$IncotermsLocation"/>
			</td>
			<xsl:if test="@Incoterms!=''">
				<td class="DeliveryInstructionsStyle2" valign="top">
					<xsl:apply-templates select="@Incoterms"/>
				</td>
			</xsl:if>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MillCharacteristicsHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Millcharacteristics2"/>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MillCharacteristicsHeader2">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Millcharacteristics2"/>&#160;
				<i>
					<xsl:value-of select="$ForInvoiceLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="ShipToCharacteristicsHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$ShipTo2"/>&#160;
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/@PartyType"/>
				<xsl:text>]</xsl:text>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="BillToHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$BillTo2"/>&#160;
				<i>
					<xsl:value-of select="$ForDeliveryMessageLineNumber"/>
				</i>&#160;<xsl:value-of select="DeliveryMessageLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="ShipToCharacteristicsHeader2">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$ShipTo2"/>&#160;
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/@PartyType"/>
				<xsl:text>]</xsl:text>&#160;
				<i>
					<xsl:value-of select="$ForInvoiceLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="OtherPartyHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="@PartyType"/>
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="TermsOfDeliveryHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$TermsOfDelivery2"/>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="../../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="TransportinstructionsHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Transportinstructions2"/>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>	
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="TransportModeCharacteristics">
	<xsl:apply-templates select="@TransportModeType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="TransportVehicleCharacteristics">
	<xsl:apply-templates select="@TransportVehicleType"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="TransportVehicleIdentifier">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@TransportVehicleIdentifierType">
			<tr>
				<td>&#160;</td>
				<td>
					<xsl:apply-templates select="@TransportVehicleIdentifierType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="TransportUnitCharacteristics">
	<xsl:apply-templates select="@TransportUnitType"/>
	<xsl:apply-templates select="@TransportUnitVariable"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="TransportLoadingCharacteristics">
	<xsl:apply-templates select="@MixProductIndicator"/>
	<xsl:apply-templates select="@TransportLoadingType"/>
	<xsl:apply-templates select="@TransportDeckOption"/>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="TransportUnitIdentifier">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<span class="LineItemStyle2">
				<xsl:value-of select="."/>
				</span>
				<xsl:if test="@TransportUnitIdentifierType">
					<br/>
					<xsl:apply-templates select="@TransportUnitIdentifierType"/>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="DeliverySchedule">
	<tr>
		<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
			<xsl:value-of select="$DeliveryNumber"/>
		</td>
		<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
			<xsl:value-of select="$DeliverySchedule"/>
		</td>
		<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
			<xsl:value-of select="$Quantity"/>
		</td>
		<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
			<xsl:value-of select="$Price"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" class="LineItemStyle2">
			<xsl:value-of select="DeliveryLineNumber"/>
		</td>
		<td valign="top" bgcolor="#F5F5F5">
			<xsl:apply-templates select="ProductionStatus | DeliveryStatus"/>
			<xsl:apply-templates select="DeliveryDateWindow"/>
			<xsl:apply-templates select="AdditionalText"/>
			<xsl:apply-templates select="DeliveryScheduleReference"/>
			<br/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="Quantity"/>
			<xsl:apply-templates select="InformationalQuantity"/>
		</td>
		<td valign="top" bgcolor="#F5F5F5">
			<xsl:apply-templates select="PriceDetails/PricePerUnit | PriceDetails/InformationalPricePerUnit"/>
		</td>
	</tr>
	<xsl:if test="PriceDetails">
		<tr>
			<td valign="top" bgcolor="#FEFEFE" class="LineItemStyle1">&#160;</td>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$PriceDetails"/>&#160;<i>
				<xsl:value-of select="$ForDeliveryNumber"/></i>&#160;<xsl:value-of select="DeliveryLineNumber"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE" class="LineItemStyle1">&#160;</td>
			<td valign="top" bgcolor="#F5F5F5" class="LineItemStyle1">&#160;</td>
		</tr>
		<xsl:apply-templates select="PriceDetails"/>
	</xsl:if>
	<xsl:if test="MonetaryAdjustment">
		<xsl:for-each select="MonetaryAdjustment">
			<tr>
				<td valign="top" bgcolor="#FEFEFE" class="LineItemStyle1">&#160;</td>
				<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
					<xsl:value-of select="$Adjustment"/>&#160;<xsl:value-of select="MonetaryAdjustmentLine"/>&#160;<i>
					<xsl:value-of select="$ForDeliveryNumber"/></i>&#160;<xsl:value-of select="../DeliveryLineNumber"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="LineItemStyle1">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5" class="LineItemStyle1">&#160;</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">
					<xsl:apply-templates select="."/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">&#160;</td>
			</tr>
		</xsl:for-each>
	</xsl:if>
</xsl:template>

<xsl:template match="DeliveryDateWindow">
	<xsl:if test="Date">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="DeliveryLineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$DeliveryDate2"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:call-template name="GetDate"/>&#160;<xsl:value-of select="Time"/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="Week">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="DeliveryLineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$Week2"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:value-of select="Week"/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="Month">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="DeliveryLineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$Month2"/>
				</td>
				<td class="DeliveryLineItemStyle2" valign="top">
					<xsl:value-of select="."/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates select="DateTimeRange"/>
	<xsl:apply-templates select="@DeliveryDateType"/>
</xsl:template>

<xsl:template match="DeliveryScheduleReference">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="@DeliveryScheduleReferenceType"/>
</xsl:template>

<xsl:template match="PartyIdentifier">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="PartiesStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<tr>
			<td class="PartiesStyle3" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="@PartyIdentifierType"/>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="OtherParty | ShipToParty | MillParty | CarrierParty | BillToParty | SupplierParty | LocationParty | BuyerParty | EndUserParty | SenderParty | ReceiverParty | RespondToParty | RemitToParty | RequestingParty | ForwarderParty | MerchantParty | SalesOfficeParty">
	<br/>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="PartiesStyle1a">
				<xsl:value-of select="$Company"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="NameAddress" mode="Name"/>
	<xsl:if test="NameAddress/Address1 | NameAddress/Address2 | NameAddress/Address3 | NameAddress/City | NameAddress/County | NameAddress/StateOrProvince | NameAddress/Country">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#E6E6E6" class="PartiesStyle1a">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
		</table>
		<xsl:apply-templates select="NameAddress" mode="Address"/>
	</xsl:if>
	<xsl:apply-templates select="URL"/>
	<xsl:if test="NameAddress/OrganisationUnit">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#E6E6E6" class="PartiesStyle1a">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
		</table>
		<xsl:apply-templates select="NameAddress/OrganisationUnit"/>
	</xsl:if>
	<xsl:if test="CommonContact">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#E6E6E6" class="PartiesStyle1a">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
		</table>
		<xsl:apply-templates select="CommonContact"/>
	</xsl:if>
</xsl:template>

<xsl:template match="Name1">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="PartiesStyle2">
				<xsl:value-of select="."/>
				<xsl:apply-templates select="@CommunicationRole"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Name2 | Name3 | Address1 | Address2 | Address3 | Address4 | City | County | ContactName">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="PartiesStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="URL">
	<br/>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" class="PartiesStyle2">
				<a href="{.}" target="_blank">
					<xsl:value-of select="."/>
				</a>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>

<xsl:template match="NameAddress" mode="Address">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<xsl:apply-templates select="Address1 | Address2 | Address3 | Address4"/>
				<br/>
				<xsl:apply-templates select="City | County"/>
			</td>
		</tr>
	</table>
	<xsl:if test="StateOrProvince">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="PartiesStyle2">
					<xsl:value-of select="StateOrProvince"/>&#160;<xsl:value-of select="PostalCode"/>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates select="Country"/>
	<xsl:apply-templates select="GPSCoordinates"/>
</xsl:template>

<xsl:template match="OrganisationUnit">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="0" width="180">
					<tr>
						<td class="PartiesStyle2">
							<xsl:value-of select="OrganisationUnitName"/>
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="0" width="180">
					<tr>
						<td class="PartiesStyle4">
							<xsl:value-of select="$PartyIdentifier"/>
						</td>
					</tr>
					<tr>
						<td class="PartiesStyle2">
							<xsl:value-of select="OrganisationUnitCode"/>
						</td>
					</tr>
				</table>
				<xsl:if test="@OrganisationUnitType!=''">
					<table border="0" cellspacing="0" cellpadding="0" width="180">
						<tr>
							<td class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:apply-templates select="@OrganisationUnitType"/>
								<xsl:text>]</xsl:text>
							</td>
						</tr>
					</table>
				</xsl:if>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>

<xsl:template match="CommonContact">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<xsl:apply-templates select="ContactName"/>				
				<xsl:if test="@ContactType">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:apply-templates select="@ContactType"/>
								<xsl:text>]</xsl:text>
							</td>
						</tr>
					</table>
				</xsl:if>
				<xsl:apply-templates select="Telephone"/>
				<xsl:apply-templates select="MobilePhone"/>
				<xsl:apply-templates select="Email"/>
				<xsl:apply-templates select="Fax"/>
				<xsl:apply-templates select="GPSCoordinates"/>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>

<xsl:template match="NameAddress" mode="Name">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<xsl:apply-templates select="Name1 | Name2 | Name3"/>
			</td>
		</tr>
	</table>
	<xsl:if test="../PartyIdentifier">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="PartiesStyle4">
								<xsl:value-of select="$PartyIdentifier"/>
							</td>
						</tr>
					</table>
					<xsl:apply-templates select="../PartyIdentifier"/>
				</td>
			</tr>
		</table>
	</xsl:if>
</xsl:template>

<xsl:template match="Telephone | MobilePhone | Fax">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="PartiesStyle4">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
		</tr>
		<tr>
			<td class="PartiesStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Email">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="PartiesStyle4">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
		</tr>
		<tr>
			<td class="PartiesStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>


<xsl:template match="Country">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="PartiesStyle2">
				<xsl:value-of select="."/>&#160;<xsl:value-of select="@ISOCountryCode"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="BuyerPartyLetterhead">
	<xsl:if test="string-length(SenderParty) !='0'">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(SenderParty) ='0'">
		<xsl:if test="string-length(BuyerParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="BuyerParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="BuyerParty/NameAddress/Address1"/>
							<xsl:if test="BuyerParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',BuyerParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(BuyerParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="BuyerParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(BuyerParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(BuyerParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="BuyerParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="BuyerParty/NameAddress/Address1"/>
							<xsl:if test="BuyerParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="BuyerParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(BuyerParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(BuyerParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(BuyerParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="BuyerParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template name="ReceiverPartyAddressfield">
	<xsl:if test="string-length(ReceiverParty) ='0'">
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(SupplierParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<xsl:if test="SupplierParty/NameAddress/Name2">
								<xsl:if test="string-length(SupplierParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="SupplierParty/NameAddress/Name2"/>
									<xsl:if test="SupplierParty/NameAddress/Name3">
										<xsl:if test="string-length(SupplierParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="SupplierParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
						<xsl:if test="SupplierParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',SupplierParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="SupplierParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(SupplierParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<xsl:if test="SupplierParty/NameAddress/Name2">
								<xsl:if test="string-length(SupplierParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="SupplierParty/NameAddress/Name2"/>
									<xsl:if test="SupplierParty/NameAddress/Name3">
										<xsl:if test="string-length(SupplierParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="SupplierParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
						<xsl:if test="SupplierParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="SupplierParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/City,', ')"/>
						<xsl:if test="SupplierParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(SupplierParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="SupplierParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="SupplierParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(ReceiverParty) !='0'">
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',ReceiverParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="ReceiverParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="ReceiverParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/City,', ')"/>
						<xsl:if test="ReceiverParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(ReceiverParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="ReceiverParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="ReceiverParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template match="TermsOfDelivery" mode="Header">
	<tr>
		<td valign="top" colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" width="70">&#160;</td>
		<td valign="top" colspan="3" class="DeliveryInstructionsStyle5">
			<xsl:value-of select="$TermsOfDelivery"/>
		</td>
		<td valign="top" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" width="70">&#160;</td>
		<td valign="top" colspan="3">
			<xsl:apply-templates select="ShipmentMethodOfPayment"/>
			<xsl:apply-templates select="IncotermsLocation"/>
			<xsl:apply-templates select="FreightPayableAt"/>
			<xsl:apply-templates select="AdditionalText"/>
		</td>
		<td valign="top" width="30">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="DeliveryRouteCode" mode="Header">
		<!--<tr>
			<td valign="top" colspan="5">
				<hr style="height:1px;color:#000000;noshade"/>
			</td>
		</tr>
		<tr>
			<td valign="top" width="70">&#160;</td>
			<td valign="top" colspan="3" class="DeliveryInstructionsStyle5">
				<xsl:value-of select="$DeliveryRoute"/>
			</td>
			<td valign="top" width="30">&#160;</td>
		</tr>-->
		<tr>
			<td valign="top" width="70">&#160;</td>
			<td valign="top" colspan="3">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="DeliveryInstructionsStyle1" width="125" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td valign="top">
						<span class="DeliveryInstructionsStyle2">
						<xsl:value-of select="."/><br/>
						</span>
						<xsl:apply-templates select="@Agency"/>
					</td>
				</tr>
			</table>
			</td>
			<td valign="top" width="30">&#160;</td>
		</tr>
</xsl:template>

<xsl:template match="TermsOfPayment">
	<xsl:apply-templates select="TermsDescription"/>
	<xsl:apply-templates select="TermsBasisDate"/>
	<xsl:apply-templates select="@TermsBasisDateType"/>
	<xsl:apply-templates select="TermsOfPaymentCode"/>
	<xsl:apply-templates select="TermsDiscountPercent"/>
	<xsl:apply-templates select="TermsDiscountDueDate"/>
	<xsl:apply-templates select="TermsDiscountDaysDue"/>
	<xsl:apply-templates select="TermsNetDueDate"/>
	<xsl:apply-templates select="TermsNetDaysDue"/>
	<xsl:apply-templates select="TermsInterestPenaltyPercent"/>
	<xsl:apply-templates select="MethodOfPayment"/>
</xsl:template>

<xsl:template match="TermsDescription">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="TermsBasisDate | TermsDiscountDueDate | TermsNetDueDate">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryLineItemStyle2" valign="top">
				<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="ColourDetail">
	<xsl:if test="string-length(CMYK|HSB|Lab|RGB)!='0'">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$map[@key=name(current())]"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:if test="string-length(CMYK)!='0'">
						<xsl:value-of select="$CYMK"/>
					</xsl:if>
					<!--<xsl:if test="string-length(ColourShade)!='0'">
						<xsl:value-of select="$ColourShade"/>
					</xsl:if>-->
					<xsl:if test="string-length(HSB)!='0'">
						<xsl:value-of select="$HSB"/>
					</xsl:if>
					<xsl:if test="string-length(Lab)!='0'">
						<xsl:value-of select="$Lab"/>
					</xsl:if>
					<xsl:if test="string-length(RGB)!='0'">
						<xsl:value-of select="$RGB"/>
					</xsl:if>
				</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates/>
	<br/>
</xsl:template>

<xsl:template match="CMYK | HSB | Lab | RGB">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top" colspan="2">
				<xsl:apply-templates select="@TestMethod | @TestAgency | @SampleType | @ResultSource"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="Cyan | Yellow | Magenta | Black | Hue | Saturation | Brilliance | L | a | b | Red | Green | Blue">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<xsl:apply-templates select="DetailValue"/>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<xsl:apply-templates select="StandardDeviation | SampleSize | TwoSigmaLower | TwoSigmaUpper"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MonetaryAdjustmentHeader">
	<br/>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td bgcolor="#767676" class="LineItemHeaderStyle6">
				<xsl:choose>
					<xsl:when test="../OrderConfirmation">
						<xsl:value-of select="$OrderConfirmation"/>&#160;
						<xsl:value-of select="$MonetaryAdjustmentHeader"/>
					</xsl:when>
					<xsl:when test="../Invoice">
						<xsl:value-of select="$MessageInformation"/>&#160;
						<xsl:value-of select="$MonetaryAdjustmentHeader"/>
					</xsl:when>
				</xsl:choose>
			</td>
		</tr>
	</xsl:element>
</xsl:template>	

<xsl:template match="TermsBasisDate | TermsDiscountDueDate | TermsNetDueDate" mode="Summary">
	<xsl:call-template name="GetDate"/>&#160;<xsl:value-of select="Time"/>
</xsl:template>

<xsl:template match="TermsDiscountPercent" mode="Summary">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="PartiesStyle4" width="180" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td valign="top">
						<xsl:apply-templates select="Value"/>
					</td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>





<xsl:template match="OrderConfirmationReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@OrderConfirmationReferenceType">
		<tr>
			<td class="LineItemStyle1">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:apply-templates select="@OrderConfirmationReferenceType"/>
			</td>
		</tr>
		</xsl:if>
	</table>
</xsl:template>



<xsl:template match="DeliveryOrigin | DeliveryDestination">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$map[@key=name(current())]"/>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;
				<xsl:value-of select="../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$Location"/>
			</td>
			<td valign="top" class="DeliveryInstructionsStyle2">
				<xsl:apply-templates select="LocationParty/@PartyType"/><br/>
				<xsl:if test="Date|Time">
					<xsl:call-template name="GetDate"/>&#160;<xsl:value-of select="Time"/>
				</xsl:if>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="LocationCode"/>
	<xsl:apply-templates select="LocationParty"/>
	<xsl:apply-templates select="GPSCoordinates"/>
</xsl:template>

<xsl:template match="MillProductionInformation">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
						<xsl:value-of select="$MillProductionInformation"/>&#160;
						<i>
							<xsl:value-of select="$ForOrderLineNumber"/>
						</i>&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>
					</td>
				</tr>
			</table>
			<xsl:apply-templates select="MillOrderNumber"/>
			<xsl:apply-templates select="MillCharacteristics"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="Quantity"/>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>

<xsl:template name="SupplierPartyLetterhead">
	<xsl:if test="string-length(SenderParty) !='0'">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(SenderParty) ='0'">
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
							<xsl:if test="SupplierParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SupplierParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SupplierParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
							<xsl:if test="SupplierParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SupplierParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="concat(SupplierParty/NameAddress/Country,'  ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template name="BuyerPartyAddressfield">
	<xsl:if test="string-length(ReceiverParty) !='0'">
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',ReceiverParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="ReceiverParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="ReceiverParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/City,', ')"/>
						<xsl:if test="ReceiverParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(ReceiverParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="ReceiverParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="ReceiverParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(ReceiverParty) ='0'">
		<xsl:if test="string-length(BuyerParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(BuyerParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="BuyerParty/NameAddress/Name1"/>
							<xsl:if test="BuyerParty/NameAddress/Name2">
								<xsl:if test="string-length(BuyerParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="BuyerParty/NameAddress/Name2"/>
									<xsl:if test="BuyerParty/NameAddress/Name3">
										<xsl:if test="string-length(BuyerParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="BuyerParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="BuyerParty/NameAddress/Address1"/>
						<xsl:if test="BuyerParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',BuyerParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BuyerParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="BuyerParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BuyerParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(BuyerParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(BuyerParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="BuyerParty/NameAddress/Name1"/>
							<xsl:if test="BuyerParty/NameAddress/Name2">
								<xsl:if test="string-length(BuyerParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="BuyerParty/NameAddress/Name2"/>
									<xsl:if test="BuyerParty/NameAddress/Name3">
										<xsl:if test="string-length(BuyerParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="BuyerParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="BuyerParty/NameAddress/Address1"/>
						<xsl:if test="BuyerParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="BuyerParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BuyerParty/NameAddress/City,', ')"/>
						<xsl:if test="BuyerParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(BuyerParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="BuyerParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="BuyerParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template name="BuyerSupplierShipToParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Buyer"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$ShipTo"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BuyerParty/NameAddress/Address1 | 
		BuyerParty/NameAddress/Address2 | 
		BuyerParty/NameAddress/Address3 | 
		BuyerParty/NameAddress/City | 
		BuyerParty/NameAddress/County | 
		BuyerParty/NameAddress/StateOrProvince | 
		BuyerParty/NameAddress/Country |
		SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		ShipToCharacteristics/ShipToParty/NameAddress/Address1 | 
		ShipToCharacteristics/ShipToParty/NameAddress/Address2 | 
		ShipToCharacteristics/ShipToParty/NameAddress/Address3 | 
		ShipToCharacteristics/ShipToParty/NameAddress/City | 
		ShipToCharacteristics/ShipToParty/NameAddress/County | 
		ShipToCharacteristics/ShipToParty/NameAddress/StateOrProvince | 
		ShipToCharacteristics/ShipToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/URL | SupplierParty/URL | ShipToCharacteristics/ShipToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/NameAddress/OrganisationUnit or SupplierParty/NameAddress/OrganisationUnit or ShipToCharacteristics/ShipToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BuyerParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/CommonContact | SupplierParty/CommonContact | ShipToCharacteristics/ShipToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>

<xsl:template match="PurchaseOrderIssuedDate | DeliveryDate | OrderConfirmationIssuedDate | InvoiceDate | CallOffIssuedDate | DeliveryMessageDate | GoodsReceiptArrivalDate | GoodsReceiptUnloadDate | ComplaintIssueDate | GoodsReceiptIssueDate | InventoryStatusIssuedDate | CreditDebitNoteDate | ProductQualityIssueDate | ComplaintResponseIssueDate | OrderStatusResponseDate">
	<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
</xsl:template>

<xsl:template match="PurchaseOrderIssuedDate | DeliveryDate | OrderConfirmationIssuedDate | InvoiceDate | CallOffIssuedDate | DeliveryMessageDate | GoodsReceiptArrivalDate | GoodsReceiptUnloadDate | ComplaintIssueDate | GoodsReceiptIssueDate | InventoryStatusIssuedDate | CreditDebitNoteDate | ProductQualityIssueDate | ComplaintResponseIssueDate | OrderStatusResponseDate" mode="Title">
	<xsl:call-template name="GetDate"/>&#160;<xsl:value-of select="Time"/>
</xsl:template>

<xsl:template match="ChargeOrAllowance">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@ChargeOrAllowanceType">
			<tr>
				<td>&#160;</td>
				<td>
					<xsl:apply-templates select="@ChargeOrAllowanceType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="InvoiceReference">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@InvoiceReferenceType">
			<tr>
				<td>&#160;</td>
				<td>
					<xsl:apply-templates select="@InvoiceReferenceType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template name="MonetaryAdjustmentHeader2">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Adjustment"/>&#160;<xsl:value-of select="MonetaryAdjustmentLine"/>&#160;<i>
				<xsl:value-of select="$ForOrderLineNumber"/></i>&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MonetaryAdjustmentHeader3">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Adjustment"/>&#160;<xsl:value-of select="MonetaryAdjustmentLine"/>&#160;<i>
				<xsl:value-of select="$ForInvoiceLineNumber"/></i>&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="TransportModeCharacteristics">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Transportinstructions2"/>
				<i>&#160;<xsl:value-of select="$ForInvoiceLineNumber"/>
				</i>&#160;<xsl:value-of select="InvoiceLineNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="CountryOfOrigin" mode="Header">
	<tr>
		<td valign="top" colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" width="70">&#160;</td>
		<td valign="top" colspan="3" class="DeliveryInstructionsStyle5">
			<xsl:value-of select="$CountryOfOrigin"/>
		</td>
		<td valign="top" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" width="70">&#160;</td>
		<td valign="top" colspan="3">
			<xsl:apply-templates select="Country"/>
		</td>
		<td valign="top" width="30">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="TotalNumberOfLineItems" mode="CallOff">
	<tr>
		<td bgcolor="#F5F5F5" valign="top" colspan="2">
			<table cellpadding="0" cellspacing="0" border="0" width="250">
				<tr>
					<td class="DeliveryInstructionsStyle1" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="DeliveryInstructionsStyle2">
						<xsl:value-of select="."/>
					</td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td>&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="TotalQuantity | TotalInformationalQuantity | TotalOldQuantity | TotalOldInformationalQuantity" mode="CallOff">
	<tr>
		<td bgcolor="#F5F5F5" valign="top" colspan="2">
			<span class="DeliveryInstructionsStyle1">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</span>
		</td>
		<td>
			<xsl:apply-templates select="Value"/>
			<xsl:if test="string-length(@QuantityType)!='0'">
				<xsl:apply-templates select="@QuantityType"/>
			</xsl:if>
			<xsl:if test="string-length(@AdjustmentType)!='0'">
				<xsl:apply-templates select="@AdjustmentType"/>
			</xsl:if>
			<xsl:if test="string-length(@QuantityTypeContext)!='0'">
				<xsl:apply-templates select="@QuantityTypeContext"/>
			</xsl:if>
			<br/>
		</td>
		<td>&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="3">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="TermsAndDisclaimers" mode="CallOff">
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td bgcolor="#F5F5F5" valign="top" colspan="4">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="190" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="SummaryStyle2">
						<xsl:value-of select="."/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</xsl:template>

<xsl:template name="SupplierPartyAddressfield">
	<xsl:if test="string-length(ReceiverParty) !='0'">
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',ReceiverParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="ReceiverParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="ReceiverParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/City,', ')"/>
						<xsl:if test="ReceiverParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(ReceiverParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="ReceiverParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="ReceiverParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(ReceiverParty) ='0'">
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(SupplierParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<xsl:if test="SupplierParty/NameAddress/Name2">
								<xsl:if test="string-length(SupplierParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="SupplierParty/NameAddress/Name2"/>
									<xsl:if test="SupplierParty/NameAddress/Name3">
										<xsl:if test="string-length(SupplierParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="SupplierParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
						<xsl:if test="SupplierParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',SupplierParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="SupplierParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SupplierParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(SupplierParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="SupplierParty/NameAddress/Name1"/>
							<xsl:if test="SupplierParty/NameAddress/Name2">
								<xsl:if test="string-length(SupplierParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="SupplierParty/NameAddress/Name2"/>
									<xsl:if test="SupplierParty/NameAddress/Name3">
										<xsl:if test="string-length(SupplierParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="SupplierParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="SupplierParty/NameAddress/Address1"/>
						<xsl:if test="SupplierParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="SupplierParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(SupplierParty/NameAddress/City,', ')"/>
						<xsl:if test="SupplierParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(SupplierParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="SupplierParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="SupplierParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template match="CallOffPurchaseOrderLineItem">
	<xsl:apply-templates select="PurchaseOrderLineItemNumber"/>
	<xsl:apply-templates select="PurchaseOrderInformation/PurchaseOrderNumber"/>
	<xsl:apply-templates select="PurchaseOrderInformation/PurchaseOrderReleaseNumber"/>
	<xsl:apply-templates select="PurchaseOrderInformation/PurchaseOrderIssuedDate" mode="CallOff"/>
	<xsl:apply-templates select="PurchaseOrderInformation/PurchaseOrderReference"/>
	<xsl:apply-templates select="PackageIdentifier"/>
</xsl:template>

<xsl:template match="PackageIdentifier">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:apply-templates select="Identifier/@IdentifierCodeType"/>
			</td>
		</tr>
		<xsl:if test="Identifier/@IdentifierType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="Identifier/@IdentifierType"/>
					<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="PurchaseOrderIssuedDate" mode="CallOff">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="LineItemStyle1" width="125" valign="top">
					<xsl:value-of select="$OrderIssuedDate"/>
				</td>
				<td class="DeliveryLineItemStyle2">
					<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
				</td>
			</tr>
		</table>
</xsl:template>

<xsl:template match="CallOffReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@CallOffReferenceType">
		<tr>
			<td class="LineItemStyle1">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:apply-templates select="@CallOffReferenceType"/>
			</td>
		</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="CallOffProduct">
	<xsl:if test="string-length(CallOffPaper/CallOffProductConversionType/CallOffSheet | CallOffPaper/CallOffProductConversionType/CallOffReel | CallOffPulp | CallOffRecoveredPaper)!='0'">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$ProductType"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:if test="string-length(CallOffPaper/CallOffProductConversionType/CallOffSheet)!='0'">
					<xsl:value-of select="$Sheet"/>
				</xsl:if>
				<xsl:if test="string-length(CallOffPaper/CallOffProductConversionType/CallOffReel)!='0'">
					<xsl:value-of select="$Reel"/>
				</xsl:if>
				<xsl:if test="string-length(CallOffPulp)!='0'">
					<xsl:value-of select="$Pulp2"/>
				</xsl:if>
				<xsl:if test="string-length(CallOffRecoveredPaper)!='0'">
					<xsl:value-of select="$RecoveredPaper"/>
				</xsl:if>
			</td>
		</tr>
	</table>
	</xsl:if>
	<xsl:apply-templates select="CallOffPaper"/>
	<br/>
	<xsl:if test="MillCharacteristics">
		<xsl:call-template name="MillCharacteristicsHeader3"/>
		<xsl:apply-templates select="MillCharacteristics"/>
	</xsl:if>
</xsl:template>

<xsl:template name="TransportinstructionsHeader2">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Transportinstructions2"/>&#160;
				<i>
					<xsl:value-of select="$ForCallOffLineNumber"/>
				</i>&#160;<xsl:value-of select="CallOffLineItemNumber"/>	
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="TransportinstructionsHeader3">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Transportinstructions2"/>&#160;
				<i>
					<xsl:value-of select="$ForDeliveryMessageLineNumber"/>
				</i>&#160;<xsl:value-of select="DeliveryMessageLineItemNumber"/>	
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="MillCharacteristicsHeader3">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Millcharacteristics2"/>&#160;
				<i>
					<xsl:value-of select="$ForCallOffLineNumber"/>
				</i>&#160;<xsl:value-of select="../CallOffLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="CallOffSheet">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template name="TermsOfDeliveryHeader2">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$TermsOfDelivery2"/>&#160;
				<i>
					<xsl:value-of select="$ForInvoiceLineNumber"/>
				</i>&#160;<xsl:value-of select="../../PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="DeliveryLeg">
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<tr>
			<td bgcolor="#DEDEDE" class="LineItemStyle1" nowrap="">
				<xsl:value-of select="$LegNumber"/>
			</td>
			<td bgcolor="#DEDEDE" class="LineItemStyle1">&#160;</td>
			<td bgcolor="#DEDEDE" class="LineItemStyle1">
				<xsl:value-of select="$LocationType2"/>
			</td>
			<td bgcolor="#DEDEDE" class="LineItemStyle1">
				<xsl:value-of select="$Date"/>/<xsl:value-of select="$Time"/>
			</td>
			<td bgcolor="#DEDEDE" class="LineItemStyle1">
				<xsl:value-of select="$LocationCode2"/>
			</td>
			<td bgcolor="#DEDEDE" class="LineItemStyle1"> 
				<xsl:value-of select="$Location"/>
			</td>
		</tr>
		<tr>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="DeliveryLegSequenceNumber"/>
			</td>
			<td class="DeliveryInstructionsStyle5" valign="top">
				<xsl:value-of select="$DateTimeFrom"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="DeliveryOrigin/LocationParty/@PartyType"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="DeliveryOrigin" mode="DeliveryLeg"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="DeliveryOrigin/LocationCode"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="DeliveryOrigin/LocationParty"/>
			</td>
		</tr>
		<xsl:if test="CarrierParty">
			<tr>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:apply-templates select="CarrierParty"/>
				</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty">
			<tr>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:apply-templates select="OtherParty"/>
				</td>
			</tr>
		</xsl:if>
		<xsl:if test="DeliveryDestination">
			<tr>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="DeliveryInstructionsStyle5" valign="top">
					<xsl:value-of select="$DateTimeTo"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:apply-templates select="DeliveryDestination/LocationParty/@PartyType"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:apply-templates select="DeliveryDestination" mode="DeliveryLeg"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:value-of select="DeliveryDestination/LocationCode"/>
				</td>
				<td class="LineItemStyle2" valign="top">
					<xsl:apply-templates select="DeliveryDestination/LocationParty"/>
				</td>
			</tr>
		</xsl:if>
		<xsl:if test="DeliveryTransitTime">
			<tr>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td class="DeliveryInstructionsStyle5" valign="top" colspan="2">
					<xsl:value-of select="$TransitTime"/>
				</td>
				<td class="LineItemStyle2" valign="top" colspan="4">
					<xsl:if test="DeliveryTransitTime/Days">
						<xsl:value-of select="DeliveryTransitTime/Days"/>&#160;<xsl:value-of select="$Days"/>&#160;</xsl:if>
					<xsl:if test="DeliveryTransitTime/Hours">
						<xsl:value-of select="DeliveryTransitTime/Hours"/>&#160;<xsl:value-of select="$Hours"/>&#160;</xsl:if>
					<xsl:if test="DeliveryTransitTime/Minutes">
						<xsl:value-of select="DeliveryTransitTime/Minutes"/>&#160;<xsl:value-of select="$Minutes"/><br/></xsl:if>
				</td>
			</tr>
		</xsl:if>
		<xsl:if test="DeliveryDateWindow">
			<tr>
				<td class="LineItemStyle2" valign="top">&#160;</td>
				<td valign="top" colspan="6">
					<xsl:apply-templates select="DeliveryDateWindow"/>
				</td>
			</tr>
		</xsl:if>
	</xsl:element>
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
		<xsl:if test="TransportModeCharacteristics | TransportVehicleCharacteristics | TransportUnitCharacteristics | TransportLoadingCharacteristics | TransportOtherCharacteristics | TermsOfChartering">
			<tr>
				<td valign="top" colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3" class="DeliveryInstructionsStyle5" width="540">
					<xsl:value-of select="$TransportInstructions"/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
			<tr>
				<td valign="top" width="70">&#160;</td>
				<td valign="top" colspan="3" width="540">
					<xsl:apply-templates select="TransportModeCharacteristics"/>
					<xsl:apply-templates select="TransportVehicleCharacteristics"/>
					<xsl:apply-templates select="TransportUnitCharacteristics"/>
					<xsl:apply-templates select="TransportLoadingCharacteristics"/>
					<xsl:apply-templates select="TransportOtherInstructions"/>
					<xsl:apply-templates select="TermsOfChartering"/>
					<br/>
				</td>
				<td valign="top" width="30">&#160;</td>
			</tr>
		</xsl:if>
	</xsl:element>
</xsl:template>

<xsl:template match="DeliveryOrigin | DeliveryDestination" mode="DeliveryLeg">
		<xsl:call-template name="GetDate"/>&#160;<xsl:value-of select="Time"/>
</xsl:template>

<xsl:template match="DeliveryMessageReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@DeliveryMessageReferenceType">
		<tr>
			<td class="LineItemStyle1">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:apply-templates select="@DeliveryMessageReferenceType"/>
			</td>
		</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template match="Quantity | InformationalQuantity | OldQuantity | OldInformationalQuantity" mode="Other">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" valign="top" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<xsl:apply-templates select="Value"/>
				<xsl:if test="string-length(@QuantityType)!='0'">
					<xsl:apply-templates select="@QuantityType"/>
				</xsl:if>
				<xsl:if test="string-length(@AdjustmentType)!='0'">
					<xsl:apply-templates select="@AdjustmentType"/>
				</xsl:if>
				<xsl:if test="string-length(@QuantityTypeContext)!='0'">
					<xsl:apply-templates select="@QuantityTypeContext"/>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Quantity | InformationalQuantity" mode="GrossWeight">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<xsl:apply-templates select="Value"/>
				<xsl:if test="string-length(@AdjustmentType)!='0'">
					<xsl:apply-templates select="@AdjustmentType"/>
				</xsl:if>
				<xsl:if test="string-length(@QuantityTypeContext)!='0'">
					<xsl:apply-templates select="@QuantityTypeContext"/>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Quantity | InformationalQuantity" mode="NetWeight">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<xsl:apply-templates select="Value"/>
				<xsl:if test="string-length(@AdjustmentType)!='0'">
					<xsl:apply-templates select="@AdjustmentType"/>
				</xsl:if>
				<xsl:if test="string-length(@QuantityTypeContext)!='0'">
					<xsl:apply-templates select="@QuantityTypeContext"/>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="BaleItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$BaleItemID"/>
					</td>
					<td valign="top">
						<xsl:for-each select="Identifier">
							<span class="LineItemStyle2">
								<xsl:value-of select="."/>
								<br/>
							</span>
							<span class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:value-of select="@IdentifierCodeType"/>&#160;<xsl:value-of select="@IdentifierType"/>
							<xsl:text>]</xsl:text>
							</span>
							<br/>
						</xsl:for-each>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="PartyIdentifier" mode="PackageInformation"/>
			<xsl:apply-templates select="MachineID"/>
			<xsl:apply-templates select="Product"/>
			<xsl:apply-templates select="Quantity" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
			<br/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="PackageInformation">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<!--<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$PackageID"/>
					</td>-->
					<td valign="top">
						<xsl:for-each select="Identifier">
							<span class="LineItemStyle2">
								<xsl:value-of select="."/>
								<br/>
							</span>
							<span class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:value-of select="@IdentifierCodeType"/>&#160;<xsl:value-of select="@IdentifierType"/>
							<xsl:text>]</xsl:text>
							</span>
							<br/>
						</xsl:for-each>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">
			<xsl:apply-templates select="Quantity[@QuantityType='GrossWeight']" mode="GrossWeight"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='GrossWeight']" mode="GrossWeight"/>
		</td>
		<td valign="top">
			<xsl:apply-templates select="RawMaterialSet"/>
			<xsl:apply-templates select="@PackageType"/>
			<xsl:apply-templates select="@MixedProductPalletIndicator"/>
			<xsl:apply-templates select="ItemCount"/>
			<xsl:apply-templates select="Quantity[@QuantityType='NetWeight']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='NetWeight']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='AirDryWeight']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='AirDryWeight']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='Area']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='Area']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='BoneDry']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='BoneDry']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='Count']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='Count']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='Freight']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='Freight']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='Length']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='Length']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='NominalWeight']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='NominalWeight']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='Percent']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='Percent']" mode="Other"/>
			<xsl:apply-templates select="Quantity[@QuantityType='TareWeight']" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity[@QuantityType='TareWeight']" mode="Other"/>
			<br/>
		</td>
	</tr>
	<!--<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>-->
	<xsl:apply-templates select="BaleItem"/>
	<xsl:apply-templates select="BoxItem"/>
	<xsl:apply-templates select="ReelItem"/>
	<xsl:apply-templates select="ReamItem"/>
	<xsl:apply-templates select="SheetItem"/>
	<xsl:apply-templates select="UnitItem"/>
</xsl:template>

<xsl:template match="PartyIdentifier" mode="PackageInformation">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<tr>
			<td>&#160;</td>
			<td class="PartiesStyle3" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="@PartyIdentifierType"/>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="BoxItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$BoxID"/>
					</td>
					<td valign="top">
						<xsl:for-each select="Identifier">
							<span class="LineItemStyle2">
								<xsl:value-of select="."/>
								<br/>
							</span>
							<span class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:value-of select="@IdentifierCodeType"/>&#160;<xsl:value-of select="@IdentifierType"/>
							<xsl:text>]</xsl:text>
							</span>
							<br/>
						</xsl:for-each>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="PartyIdentifier" mode="PackageInformation"/>
			<xsl:apply-templates select="MachineID"/>
			<xsl:apply-templates select="ItemCount"/>
			<xsl:apply-templates select="Quantity" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
			<br/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<xsl:apply-templates select="ReamItem"/>
	<xsl:apply-templates select="UnitItem"/>
	<xsl:apply-templates select="SheetItem"/>
	<xsl:if test="BoxCharacteristics">
		<tr>
			<td colspan="3">&#160;</td>
			<td>
				<xsl:apply-templates select="BoxCharacteristics"/>
			</td>
		</tr>
	</xsl:if>
</xsl:template>

<xsl:template match="ReamItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$ReamID"/>
					</td>
					<td valign="top">
						<xsl:for-each select="Identifier">
							<span class="LineItemStyle2">
								<xsl:value-of select="."/>
								<br/>
							</span>
							<span class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:value-of select="@IdentifierCodeType"/>&#160;<xsl:value-of select="@IdentifierType"/>
							<xsl:text>]</xsl:text>
							</span>
							<br/>
						</xsl:for-each>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="ItemCount"/>
			<xsl:apply-templates select="Quantity" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
			<br/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<xsl:apply-templates select="SheetItem"/>
</xsl:template>

<xsl:template match="SheetItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$SheetItemID"/>
					</td>
					<td valign="top">&#160;</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="DateSheeted"/>
			<xsl:apply-templates select="DateFinished"/>
			<br/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>



<xsl:template match="ReelItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						<xsl:value-of select="$ReelItemID"/>
					</td>
					<td valign="top">
						<xsl:for-each select="Identifier">
							<span class="LineItemStyle2">
								<xsl:value-of select="."/>
								<br/>
							</span>
							<span class="PartiesStyle3">
								<xsl:text>[</xsl:text>
								<xsl:value-of select="@IdentifierCodeType"/>&#160;<xsl:value-of select="@IdentifierType"/>
								<xsl:text>]</xsl:text>
							</span>
							<br/>
						</xsl:for-each>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="PartyIdentifier" mode="PackageInformation"/>
			<xsl:apply-templates select="MachineID"/>
			<xsl:apply-templates select="MillOrderNumber"/>
			<xsl:apply-templates select="Quantity" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
			<br/>
			<xsl:apply-templates select="DeliveryMessageReelCharacteristics"/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>

<xsl:template match="MillCharacteristics | ShipToCharacteristics">
	<xsl:apply-templates/>
	<br/>
</xsl:template>

<xsl:template match="Paper | MillJoinLocation | DeliveryMessageReelCharacteristics | CallOffReel | CallOffProductConversionType | CallOffPaper | PurchaseOrderInformation | PulpUnitCharacteristics | BalePackagingCharacteristics | TransportUnitMeasurements | TransportVehicleMeasurements | TransportOtherInstructions | Classification | Embossing | PlyAttributes | ReelConversionCharacteristics | Reel | LabelCharacteristics | Sheet | SheetConversionCharacteristics | SheetSize | Pulp | BaleConversionCharacteristics | RecoveredPaper | Slurry | NonStandardPulp | FlatAmountAdjustment |PriceAdjustment | InventoryClass | Watermark | BookManufacturing | WrapCharacteristics | PalletPackagingCharacteristics | PackagingCharacteristics | OrderStatusInformation | UnitCharacteristics">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="DateWound | DateFinished | DateSheeted">
	<xsl:apply-templates select="@RewoundIndicator"/>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="LineItemStyle2" valign="top">
							<xsl:call-template name="GetDate"/><br/>
							<xsl:value-of select="Time"/>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template name="GetDate">
	<xsl:choose>
		<xsl:when test="string-length(Date/Month) = 2 and number(Date/Month) &gt; 0 and number(Date/Month) &lt; 13">
			<xsl:value-of select="Date/Year"/>-<xsl:value-of select="Date/Month"/>-<xsl:value-of select="Date/Day"/>
	  </xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="Date/Year"/>-<xsl:value-of select="Date/Month"/>-<xsl:value-of select="Date/Day"/>
	  </xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="ShipToPartyLetterhead">
	<xsl:if test="string-length(SenderParty) !='0'">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(SenderParty) ='0'">
		<xsl:if test="string-length(ShipToCharacteristics/ShipToParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Address1"/>
							<xsl:if test="ShipToCharacteristics/ShipToParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',ShipToCharacteristics/ShipToParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(ShipToCharacteristics/ShipToParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(ShipToCharacteristics/ShipToParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ShipToCharacteristics/ShipToParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Address1"/>
							<xsl:if test="ShipToCharacteristics/ShipToParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:if test="ShipToCharacteristics/ShipToParty/NameAddress/City">
								<xsl:value-of select="concat(ShipToCharacteristics/ShipToParty/NameAddress/City,',  ')"/>
							</xsl:if>
							<xsl:value-of select="concat(ShipToCharacteristics/ShipToParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(ShipToCharacteristics/ShipToParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="ShipToCharacteristics/ShipToParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>
<xsl:template match="GoodsReceiptReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@GoodsReceiptReferenceType">
		<tr>
			<td class="LineItemStyle1">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="@GoodsReceiptReferenceType"/>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
		</xsl:if>
	</table>
</xsl:template>

<xsl:template name="GoodsReceiptAcceptanceColour">
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedWithoutDeliveryMessage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedRejected'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedAsSpecified'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedAsIs'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedWithVariance'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedWithDamage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedWithVarianceAndDamage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="@GoodsReceiptAcceptance='GoodsReceivedCancelled'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
</xsl:template>

<xsl:template name="GoodsReceiptAcceptanceColourHeader">
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedWithoutDeliveryMessage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedRejected'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedAsSpecified'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedAsIs'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="green">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedWithVariance'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedWithDamage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedWithVarianceAndDamage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="yellow">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceiptAcceptance='GoodsReceivedCancelled'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
</xsl:template>
<xsl:template match="GoodsReceiptPackage">
	<tr>
		<td colspan="4" valign="top">
			<xsl:if test="string-length(@GoodsReceiptAcceptance)!='0'">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
							<xsl:call-template name="GoodsReceiptAcceptanceColour"/>
						</td>
						<td class="LineItemStyle1" width="150">
							&#160;<xsl:value-of select="$GoodsReceiptAcceptance"/>
						</td>
						<td class="LineItemStyle2">
							&#160;<xsl:apply-templates select="@GoodsReceiptAcceptance"/>
						</td>
					</tr>
				</table>
			</xsl:if>
			<xsl:if test="string-length(@GoodsReceivedRejectedType)!='0'">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>&#160;&#160;&#160;</td>
								</tr>
							</table>
							<!--<xsl:call-template name="GoodsReceivedRejectedTypeColourHeader"/>-->
						</td>
						<td class="LineItemStyle1" width="150">
							&#160;<xsl:value-of select="$GoodsReceivedRejectedType"/>
						</td>
						<td class="LineItemStyle2">
							&#160;<xsl:apply-templates select="@GoodsReceivedRejectedType"/>
						</td>
					</tr>
				</table>
			</xsl:if>
			<xsl:if test="string-length(@VarianceType)!='0'">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>&#160;&#160;&#160;</td>
								</tr>
							</table>
						</td>
						<td class="LineItemStyle1" width="150">
							&#160;<xsl:value-of select="$VarianceType"/>
						</td>
						<td class="LineItemStyle2">
							&#160;<xsl:apply-templates select="@VarianceType"/>
						</td>
					</tr>
				</table>
			</xsl:if>
		</td>
	</tr>
	<xsl:apply-templates select="PackageInformation"/>
</xsl:template>
<xsl:template name="GoodsReceivedRejectedTypeColourHeader">
	<xsl:if test="../@GoodsReceivedRejectedType='ExcessiveTransitDamage'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='WrongWrap'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='WrongLabel'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='WrongDiameter'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='TooEarly'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='TooLate'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='UnableToUnload'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:if test="../@GoodsReceivedRejectedType='WrongVehicleType'">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td class="red">&#160;</td>
			</tr>
		</table>
	</xsl:if>
</xsl:template>
<xsl:template name="OtherParty">
	<xsl:if test="OtherParty[1]">
		<tr>
			<td colspan="5">
				<hr style="height:1px;color:#000000;noshade"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[1]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[2]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Company"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<xsl:if test="OtherParty[1]/NameAddress/Address1 | 
			OtherParty[1]/NameAddress/Address2 | 
			OtherParty[1]/NameAddress/Address3 | 
			OtherParty[1]/NameAddress/City | 
			OtherParty[1]/NameAddress/County | 
			OtherParty[1]/NameAddress/StateOrProvince | 
			OtherParty[1]/NameAddress/Country |
			OtherParty[2]/NameAddress/Address1 | 
			OtherParty[2]/NameAddress/Address2 | 
			OtherParty[2]/NameAddress/Address3 | 
			OtherParty[2]/NameAddress/City | 
			OtherParty[2]/NameAddress/County | 
			OtherParty[2]/NameAddress/StateOrProvince | 
			OtherParty[2]/NameAddress/Country |
			OtherParty[3]/NameAddress/Address1 | 
			OtherParty[3]/NameAddress/Address2 | 
			OtherParty[3]/NameAddress/Address3 | 
			OtherParty[3]/NameAddress/City | 
			OtherParty[3]/NameAddress/County | 
			OtherParty[3]/NameAddress/StateOrProvince | 
			OtherParty[3]/NameAddress/Country">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Address"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[1]/URL | OtherParty[2]/URL | OtherParty[3]/URL">
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[1]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[2]/URL"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[3]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit | OtherParty[3]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[1]/CommonContact | OtherParty[2]/CommonContact | OtherParty[3]/CommonContact">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Contact"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
	</xsl:if>
	<xsl:if test="OtherParty[4]">
		<tr>
			<td colspan="5">
				<hr style="height:1px;color:#000000;noshade"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Company"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<xsl:if test="OtherParty[4]/NameAddress/Address1 | 
			OtherParty[4]/NameAddress/Address2 | 
			OtherParty[4]/NameAddress/Address3 | 
			OtherParty[4]/NameAddress/City | 
			OtherParty[4]/NameAddress/County | 
			OtherParty[4]/NameAddress/StateOrProvince | 
			OtherParty[4]/NameAddress/Country |
			OtherParty[5]/NameAddress/Address1 | 
			OtherParty[5]/NameAddress/Address2 | 
			OtherParty[5]/NameAddress/Address3 | 
			OtherParty[5]/NameAddress/City | 
			OtherParty[5]/NameAddress/County | 
			OtherParty[5]/NameAddress/StateOrProvince | 
			OtherParty[5]/NameAddress/Country |
			OtherParty[6]/NameAddress/Address1 | 
			OtherParty[6]/NameAddress/Address2 | 
			OtherParty[6]/NameAddress/Address3 | 
			OtherParty[6]/NameAddress/City | 
			OtherParty[6]/NameAddress/County | 
			OtherParty[6]/NameAddress/StateOrProvince | 
			OtherParty[6]/NameAddress/Country">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Address"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[4]/URL | OtherParty[5]/URL | OtherParty[6]/URL">
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[4]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[5]/URL"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[6]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit | OtherParty[6]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[4]/CommonContact | OtherParty[5]/CommonContact | OtherParty[6]/CommonContact">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Contact"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
	</xsl:if>
	<xsl:if test="OtherParty[7]">
		<tr>
			<td colspan="5">
				<hr style="height:1px;color:#000000;noshade"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Company"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<xsl:if test="OtherParty[7]/NameAddress/Address1 | 
			OtherParty[7]/NameAddress/Address2 | 
			OtherParty[7]/NameAddress/Address3 | 
			OtherParty[7]/NameAddress/City | 
			OtherParty[7]/NameAddress/County | 
			OtherParty[7]/NameAddress/StateOrProvince | 
			OtherParty[7]/NameAddress/Country |
			OtherParty[8]/NameAddress/Address1 | 
			OtherParty[8]/NameAddress/Address2 | 
			OtherParty[8]/NameAddress/Address3 | 
			OtherParty[8]/NameAddress/City | 
			OtherParty[8]/NameAddress/County | 
			OtherParty[8]/NameAddress/StateOrProvince | 
			OtherParty[8]/NameAddress/Country |
			OtherParty[9]/NameAddress/Address1 | 
			OtherParty[9]/NameAddress/Address2 | 
			OtherParty[9]/NameAddress/Address3 | 
			OtherParty[9]/NameAddress/City | 
			OtherParty[9]/NameAddress/County | 
			OtherParty[9]/NameAddress/StateOrProvince | 
			OtherParty[9]/NameAddress/Country">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Address"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[7]/URL | OtherParty[8]/URL | OtherParty[9]/URL">
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[7]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[8]/URL"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[9]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit | OtherParty[9]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[7]/CommonContact | OtherParty[8]/CommonContact | OtherParty[9]/CommonContact">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Contact"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
	</xsl:if>
	<xsl:if test="OtherParty[10]">
		<tr>
			<td colspan="5">
				<hr style="height:1px;color:#000000;noshade"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
				<xsl:apply-templates select="OtherParty[12]/@PartyType"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Company"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[12]/NameAddress" mode="Name"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		<xsl:if test="OtherParty[10]/NameAddress/Address1 | 
			OtherParty[10]/NameAddress/Address2 | 
			OtherParty[10]/NameAddress/Address3 | 
			OtherParty[10]/NameAddress/City | 
			OtherParty[10]/NameAddress/County | 
			OtherParty[10]/NameAddress/StateOrProvince | 
			OtherParty[10]/NameAddress/Country |
			OtherParty[11]/NameAddress/Address1 | 
			OtherParty[11]/NameAddress/Address2 | 
			OtherParty[11]/NameAddress/Address3 | 
			OtherParty[11]/NameAddress/City | 
			OtherParty[11]/NameAddress/County | 
			OtherParty[11]/NameAddress/StateOrProvince | 
			OtherParty[11]/NameAddress/Country |
			OtherParty[12]/NameAddress/Address1 | 
			OtherParty[12]/NameAddress/Address2 | 
			OtherParty[12]/NameAddress/Address3 | 
			OtherParty[12]/NameAddress/City | 
			OtherParty[12]/NameAddress/County | 
			OtherParty[12]/NameAddress/StateOrProvince | 
			OtherParty[12]/NameAddress/Country">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Address"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[12]/NameAddress" mode="Address"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[10]/URL | OtherParty[11]/URL | OtherParty[12]/URL">
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[10]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[11]/URL"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[12]/URL"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
		<xsl:if test="OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit | OtherParty[12]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[12]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[10]/CommonContact | OtherParty[11]/CommonContact | OtherParty[12]/CommonContact">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Contact"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="OtherParty[12]/CommonContact"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
		</xsl:if>
	</xsl:if>
</xsl:template>
<xsl:template name="BillToParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country |
		OtherParty[2]/NameAddress/Address1 | 
		OtherParty[2]/NameAddress/Address2 | 
		OtherParty[2]/NameAddress/Address3 | 
		OtherParty[2]/NameAddress/City | 
		OtherParty[2]/NameAddress/County | 
		OtherParty[2]/NameAddress/StateOrProvince | 
		OtherParty[2]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/CommonContact | OtherParty[1]/CommonContact | OtherParty[2]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="BuyerSupplierBillToParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Buyer"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BuyerParty/NameAddress/Address1 | 
		BuyerParty/NameAddress/Address2 | 
		BuyerParty/NameAddress/Address3 | 
		BuyerParty/NameAddress/City | 
		BuyerParty/NameAddress/County | 
		BuyerParty/NameAddress/StateOrProvince | 
		BuyerParty/NameAddress/Country |
		SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/URL | SupplierParty/URL | BillToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/NameAddress/OrganisationUnit or SupplierParty/NameAddress/OrganisationUnit or BillToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BuyerParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/CommonContact | SupplierParty/CommonContact | BillToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="BillToRemitToParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="$RemitTo"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="RemitToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country |
		RemitToParty/NameAddress/Address1 | 
		RemitToParty/NameAddress/Address2 | 
		RemitToParty/NameAddress/Address3 | 
		RemitToParty/NameAddress/City | 
		RemitToParty/NameAddress/County | 
		RemitToParty/NameAddress/StateOrProvince | 
		RemitToParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RemitToParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RemitToParty/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/NameAddress/OrganisationUnit | RemitToParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RemitToParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/CommonContact | RemitToParty/CommonContact | OtherParty[1]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RemitToParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[2]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[2]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[2]/NameAddress/Address1 | 
				OtherParty[2]/NameAddress/Address2 | 
				OtherParty[2]/NameAddress/Address3 | 
				OtherParty[2]/NameAddress/City | 
				OtherParty[2]/NameAddress/County | 
				OtherParty[2]/NameAddress/StateOrProvince | 
				OtherParty[2]/NameAddress/Country |
				OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/URL | OtherParty[3]/URL | OtherParty[4]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/NameAddress/OrganisationUnit | OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/CommonContact | OtherParty[3]/CommonContact | OtherParty[4]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[5]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country |
				OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/URL | OtherParty[6]/URL | OtherParty[7]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/NameAddress/OrganisationUnit | OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/CommonContact | OtherParty[6]/CommonContact | OtherParty[7]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[8]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country |
				OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/URL | OtherParty[9]/URL | OtherParty[10]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/NameAddress/OrganisationUnit | OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/CommonContact | OtherParty[9]/CommonContact | OtherParty[10]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="RemitToParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$RemitTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RemitToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="RemitToParty/NameAddress/Address1 | 
		RemitToParty/NameAddress/Address2 | 
		RemitToParty/NameAddress/Address3 | 
		RemitToParty/NameAddress/City | 
		RemitToParty/NameAddress/County | 
		RemitToParty/NameAddress/StateOrProvince | 
		RemitToParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country |
		OtherParty[2]/NameAddress/Address1 | 
		OtherParty[2]/NameAddress/Address2 | 
		OtherParty[2]/NameAddress/Address3 | 
		OtherParty[2]/NameAddress/City | 
		OtherParty[2]/NameAddress/County | 
		OtherParty[2]/NameAddress/StateOrProvince | 
		OtherParty[2]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RemitToParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RemitToParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RemitToParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RemitToParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RemitToParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RemitToParty/CommonContact | OtherParty[1]/CommonContact | OtherParty[2]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RemitToParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="BillToCarrierParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="$Carrier"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="CarrierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country |
		CarrierParty/NameAddress/Address1 | 
		CarrierParty/NameAddress/Address2 | 
		CarrierParty/NameAddress/Address3 | 
		CarrierParty/NameAddress/City | 
		CarrierParty/NameAddress/County | 
		CarrierParty/NameAddress/StateOrProvince | 
		CarrierParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="CarrierParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="CarrierParty/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/NameAddress/OrganisationUnit | CarrierParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="CarrierParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="BillToParty/CommonContact | CarrierParty/CommonContact | OtherParty[1]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="BillToParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="CarrierParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[2]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[2]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[2]/NameAddress/Address1 | 
				OtherParty[2]/NameAddress/Address2 | 
				OtherParty[2]/NameAddress/Address3 | 
				OtherParty[2]/NameAddress/City | 
				OtherParty[2]/NameAddress/County | 
				OtherParty[2]/NameAddress/StateOrProvince | 
				OtherParty[2]/NameAddress/Country |
				OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/URL | OtherParty[3]/URL | OtherParty[4]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/NameAddress/OrganisationUnit | OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/CommonContact | OtherParty[3]/CommonContact | OtherParty[4]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[5]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country |
				OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/URL | OtherParty[6]/URL | OtherParty[7]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/NameAddress/OrganisationUnit | OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/CommonContact | OtherParty[6]/CommonContact | OtherParty[7]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[8]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country |
				OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/URL | OtherParty[9]/URL | OtherParty[10]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/NameAddress/OrganisationUnit | OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/CommonContact | OtherParty[9]/CommonContact | OtherParty[10]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="CarrierParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Carrier"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="CarrierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="CarrierParty/NameAddress/Address1 | 
		CarrierParty/NameAddress/Address2 | 
		CarrierParty/NameAddress/Address3 | 
		CarrierParty/NameAddress/City | 
		CarrierParty/NameAddress/County | 
		CarrierParty/NameAddress/StateOrProvince | 
		CarrierParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country |
		OtherParty[2]/NameAddress/Address1 | 
		OtherParty[2]/NameAddress/Address2 | 
		OtherParty[2]/NameAddress/Address3 | 
		OtherParty[2]/NameAddress/City | 
		OtherParty[2]/NameAddress/County | 
		OtherParty[2]/NameAddress/StateOrProvince | 
		OtherParty[2]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="CarrierParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="CarrierParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="CarrierParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="CarrierParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="CarrierParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="CarrierParty/CommonContact | OtherParty[1]/CommonContact | OtherParty[2]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="CarrierParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="SenderReceiverBillToParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | ReceiverParty/URL | BillToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or ReceiverParty/NameAddress/OrganisationUnit or BillToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | BillToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="SenderReceiverParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SenderParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SenderParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="ReceiverParty/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SenderParty/NameAddress/OrganisationUnit | ReceiverParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | OtherParty[1]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SenderParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="ReceiverParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[2]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[2]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[2]/NameAddress/Address1 | 
				OtherParty[2]/NameAddress/Address2 | 
				OtherParty[2]/NameAddress/Address3 | 
				OtherParty[2]/NameAddress/City | 
				OtherParty[2]/NameAddress/County | 
				OtherParty[2]/NameAddress/StateOrProvince | 
				OtherParty[2]/NameAddress/Country |
				OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/URL | OtherParty[3]/URL | OtherParty[4]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/NameAddress/OrganisationUnit | OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[2]/CommonContact | OtherParty[3]/CommonContact | OtherParty[4]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[5]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country |
				OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/URL | OtherParty[6]/URL | OtherParty[7]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/NameAddress/OrganisationUnit | OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[5]/CommonContact | OtherParty[6]/CommonContact | OtherParty[7]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[8]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country |
				OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/URL | OtherParty[9]/URL | OtherParty[10]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/NameAddress/OrganisationUnit | OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[8]/CommonContact | OtherParty[9]/CommonContact | OtherParty[10]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="SenderReceiverCarrierParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Carrier"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="CarrierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		CarrierParty/NameAddress/Address1 | 
		CarrierParty/NameAddress/Address2 | 
		CarrierParty/NameAddress/Address3 | 
		CarrierParty/NameAddress/City | 
		CarrierParty/NameAddress/County | 
		CarrierParty/NameAddress/StateOrProvince | 
		CarrierParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="CarrierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | ReceiverParty/URL | CarrierParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="CarrierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or ReceiverParty/NameAddress/OrganisationUnit or CarrierParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="CarrierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | CarrierParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="CarrierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template match="Value" mode="PricePerUnit">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="DeliveryInstructionsStyle2">
				<xsl:choose>
					<xsl:when test=".='1'">
						<xsl:apply-templates select="@UOM"/>
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="."/>&#160;<xsl:apply-templates select="@UOM"/>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
		<tr>
			<td class="DeliveryInstructionsStyle2">
				<xsl:if test="../RangeMin | ../RangeMax">
					<xsl:text> (</xsl:text>
				</xsl:if>
				<xsl:if test="../RangeMin">
					<xsl:text>Min: </xsl:text>
					<xsl:apply-templates select="../RangeMin"/>
				</xsl:if>
				<xsl:if test="../RangeMin !='0'">
					<xsl:if test="../RangeMax !='0'">
						<xsl:text>, </xsl:text>
					</xsl:if>
				</xsl:if>
				<xsl:if test="../RangeMax">
					<xsl:text>Max: </xsl:text>
					<xsl:apply-templates select="../RangeMax"/>
				</xsl:if>
				<xsl:if test="../RangeMin | ../RangeMax">
					<xsl:text>)</xsl:text>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="MonetaryAdjustment" mode="Summary">
	<xsl:element name="table" use-attribute-sets="default-table-center-640">
	<tr>
		<td bgcolor="#F5F5F5" width="390" valign="top">				
			<xsl:if test="MonetaryAdjustmentLine">
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="LineItemStyle1" valign="top" width="190">
							<xsl:value-of select="$Adjustment"/>&#160;<xsl:value-of select="MonetaryAdjustmentLine"/>:
						</td>
						<td class="SummaryStyle2" valign="top">
							<xsl:value-of select="@AdjustmentType"/>
						</td>
					<xsl:if test="FlatAmountAdjustment">
						<td>&#160;</td>
						<td class="LineItemStyle6a">
							<xsl:text>[</xsl:text>
							<xsl:value-of select="$FlatAmountAdjustment2"/>
							<xsl:text>]</xsl:text>
						</td>
					</xsl:if>
					<xsl:if test="PriceAdjustment">
						<td>&#160;</td>
						<td class="LineItemStyle6a">
							<xsl:text>[</xsl:text>
							<xsl:value-of select="$PriceAdjustment2"/>
							<xsl:text>]</xsl:text>
						</td>
					</xsl:if>
					<xsl:if test="TaxAdjustment">
						<td>&#160;</td>
						<td class="LineItemStyle6a">
							<xsl:text>[</xsl:text>
							<xsl:value-of select="$TaxAdjustment2"/>
							<xsl:text>]</xsl:text>
						</td>
					</xsl:if>
					</tr>
				</table>
			</xsl:if>
		</td>
		<td width="120" nowrap="true">&#160;</td>
		<td bgcolor="#F5F5F5" width="120">&#160;</td>
		<td bgcolor="#F5F5F5" width="10">&#160;</td>
	</tr>
	<xsl:if test="MonetaryAdjustmentStartAmount!=''">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="PartiesStyle4" width="190">
							<xsl:value-of select="$MonetaryAdjustmentStartAmount"/>
						</td>
						<td class="SummaryStyle2">
							<xsl:apply-templates select="MonetaryAdjustmentStartAmount" mode="Summary"/>
						</td>
					</tr>
				</table>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="MonetaryAdjustmentStartQuantity!=''">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="PartiesStyle4" width="175" valign="top">
							<xsl:value-of select="$MonetaryAdjustmentStartQuantity"/>
						</td>
						<td class="SummaryStyle2">
							<xsl:apply-templates select="MonetaryAdjustmentStartQuantity" mode="Summary"/>
						</td>
					</tr>
				</table>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:apply-templates select="PriceAdjustment" mode="Summary"/>
	<xsl:apply-templates select="FlatAmountAdjustment" mode="Summary"/>
	<xsl:apply-templates select="TaxAdjustment" mode="Summary"/>
	<xsl:if test="InformationalAmount!=''">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="PartiesStyle4" width="190">
							<xsl:value-of select="$InformationalAmount2"/>
						</td>
						<td class="SummaryStyle2">
							<xsl:apply-templates select="InformationalAmount" mode="Summary"/>
						</td>
					</tr>
				</table>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:apply-templates select="AdditionalText" mode="Summary"/>
	<xsl:if test="MonetaryAdjustmentReferenceLine!=''">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="PartiesStyle4" width="190" valign="top">
							<xsl:value-of select="$MonetaryAdjustmentReferenceLine"/>
						</td>
						<td class="SummaryStyle2">
							<xsl:value-of select="MonetaryAdjustmentReferenceLine"/>
						</td>
					</tr>
				</table>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:apply-templates select="InformationalAmount/AdditionalText" mode="Summary"/>
	<xsl:apply-templates select="GeneralLedgerAccount" mode="Summary"/>
	<xsl:if test="MonetaryAdjustmentAmount!=''">
		<tr>
			<td bgcolor="#F5F5F5" class="PartiesStyle4">
				<xsl:value-of select="$MonetaryAdjustmentAmount"/>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5" align="right" class="SummaryStyle2">
				<xsl:apply-templates select="MonetaryAdjustmentAmount" mode="Summary"/>
			</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:if>
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:element>
</xsl:template>

<xsl:template match="TaxAdjustment" mode="Summary">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<xsl:apply-templates select="@TaxCategoryType" mode="Summary"/>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<xsl:apply-templates select="@TaxType" mode="Summary"/>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
		<xsl:apply-templates select="TaxPercent" mode="Summary"/>
		<xsl:apply-templates select="TaxAmount" mode="Summary"/>
		<xsl:apply-templates select="TaxLocation" mode="Summary"/>
	</xsl:template>
<xsl:template match="TaxPercent  | TaxLocation | AdditionalText | GeneralLedgerAccount" mode="Summary">
		<tr>
			<td bgcolor="#F5F5F5" valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="PartiesStyle4" width="190" valign="top">
							<xsl:value-of select="$map[@key=name(current())]"/>
						</td>
						<td class="SummaryStyle2">
							<xsl:value-of select="."/></td>
					</tr>
				</table>
			</td>
			<td>&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
			<td bgcolor="#F5F5F5">&#160;</td>
		</tr>
	</xsl:template>
<xsl:template match="TaxAmount" mode="Summary">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="190" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="SummaryStyle2"><xsl:apply-templates select="CurrencyValue"/></td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5" class="SummaryStyle2" align="right">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template><xsl:template match="AdjustmentFixedAmount" mode="Summary">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="PartiesStyle4" width="190" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="SummaryStyle2"><xsl:apply-templates select="CurrencyValue"/></td>
				</tr>
			</table>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5" class="SummaryStyle2" align="right">&#160;</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
</xsl:template>
<xsl:template match="AdditionalText" mode="Summary2">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="PartiesStyle4" width="190" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="SummaryStyle2">
				<xsl:value-of select="."/></td>
		</tr>
	</table>
</xsl:template>


<xsl:template match="ReamType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='ReamWrapped'"><xsl:value-of select="$ReamType-ReamWrapped"/></xsl:when>
					<xsl:when test=".='BulkPackedNonTabbed'"><xsl:value-of select="$ReamType-BulkPackedNonTabbed"/></xsl:when>
					<xsl:when test=".='BulkPackedTabbed'"><xsl:value-of select="$ReamType-BulkPackedTabbed"/></xsl:when>				
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="."/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>


<xsl:template name="BuyerSupplierEndUserParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Buyer"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$EndUser"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="EndUserParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BuyerParty/NameAddress/Address1 | 
		BuyerParty/NameAddress/Address2 | 
		BuyerParty/NameAddress/Address3 | 
		BuyerParty/NameAddress/City | 
		BuyerParty/NameAddress/County | 
		BuyerParty/NameAddress/StateOrProvince | 
		BuyerParty/NameAddress/Country |
		SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		EndUserParty/NameAddress/Address1 | 
		EndUserParty/NameAddress/Address2 | 
		EndUserParty/NameAddress/Address3 | 
		EndUserParty/NameAddress/City | 
		EndUserParty/NameAddress/County | 
		EndUserParty/NameAddress/StateOrProvince | 
		EndUserParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="EndUserParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/URL | SupplierParty/URL | EndUserParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="EndUserParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/NameAddress/OrganisationUnit or SupplierParty/NameAddress/OrganisationUnit or EndUserParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BuyerParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="EndUserParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="BuyerParty/CommonContact | SupplierParty/CommonContact | EndUserParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="EndUserParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="LocationSenderReceiver">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Location2"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:apply-templates select="LocationParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:apply-templates select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:apply-templates select="ReceiverParty[1][1]/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="LocationParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ReceiverParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="LocationParty/NameAddress/Address1 | 
		LocationParty/NameAddress/Address2 | 
		LocationParty/NameAddress/Address3 | 
		LocationParty/NameAddress/City | 
		LocationParty/NameAddress/County | 
		LocationParty/NameAddress/StateOrProvince | 
		LocationParty/NameAddress/Country |
		SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty[1]/NameAddress/Address1 | 
		ReceiverParty[1]/NameAddress/Address2 | 
		ReceiverParty[1]/NameAddress/Address3 | 
		ReceiverParty[1]/NameAddress/City | 
		ReceiverParty[1]/NameAddress/County | 
		ReceiverParty[1]/NameAddress/StateOrProvince | 
		ReceiverParty[1]/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="LocationParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ReceiverParty[1]/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="LocationParty/URL | SenderParty/URL | ReceiverParty[1]/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="LocationParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ReceiverParty[1]/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="LocationParty/NameAddress/OrganisationUnit or SenderParty/NameAddress/OrganisationUnit or ReceiverParty[1]/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="LocationParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="ReceiverParty[1]/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="LocationParty/CommonContact | SenderParty/CommonContact | ReceiverParty[1]/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="LocationParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ReceiverParty[1]/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="LocationParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Location2"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="LocationParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="LocationParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="LocationParty/NameAddress/Address1 | 
		LocationParty/NameAddress/Address2 | 
		LocationParty/NameAddress/Address3 | 
		LocationParty/NameAddress/City | 
		LocationParty/NameAddress/County | 
		LocationParty/NameAddress/StateOrProvince | 
		LocationParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country |
		OtherParty[2]/NameAddress/Address1 | 
		OtherParty[2]/NameAddress/Address2 | 
		OtherParty[2]/NameAddress/Address3 | 
		OtherParty[2]/NameAddress/City | 
		OtherParty[2]/NameAddress/County | 
		OtherParty[2]/NameAddress/StateOrProvince | 
		OtherParty[2]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="LocationParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="LocationParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="LocationParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="LocationParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="LocationParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="LocationParty/CommonContact | OtherParty[1]/CommonContact | OtherParty[2]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="LocationParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template match="UsageReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@UsageReferenceType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:apply-templates select="@UsageReferenceType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template match="WebBreaks">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@WebBreakType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:apply-templates select="@WebBreakType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template match="WasteQuantity">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" valign="top" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<xsl:apply-templates select="Value"/>
				<xsl:if test="string-length(@WasteType)!='0'">
					<xsl:apply-templates select="@WasteType"/>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="Identifier | RawMaterialSet">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:apply-templates select="@IdentifierCodeType"/>
			</td>
		</tr>
		<xsl:if test="@IdentifierType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@IdentifierType"/>
					<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template name="LocationPartyLetterhead">
	<xsl:if test="string-length(SenderParty) !='0'">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(SenderParty) ='0'">
		<xsl:if test="string-length(LocationParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="LocationParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="LocationParty/NameAddress/Address1"/>
							<xsl:if test="LocationParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',LocationParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(LocationParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="LocationParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(LocationParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(LocationParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="LocationParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="LocationParty/NameAddress/Address1"/>
							<xsl:if test="LocationParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="LocationParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(LocationParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(LocationParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(LocationParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="LocationParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>
<xsl:template name="OtherPartyAddressfield">
	<xsl:if test="string-length(ReceiverParty) !='0'">
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',ReceiverParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="ReceiverParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="ReceiverParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/City,', ')"/>
						<xsl:if test="ReceiverParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(ReceiverParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="ReceiverParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="ReceiverParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(ReceiverParty) ='0'">
		<xsl:if test="string-length(OtherParty[1]/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(OtherParty[1]/NameAddress/Name1) !='0'">
							<xsl:value-of select="OtherParty[1]/NameAddress/Name1"/>
							<xsl:if test="OtherParty[1]/NameAddress/Name2">
								<xsl:if test="string-length(OtherParty[1]/NameAddress/Name2) !='0'">
									<xsl:value-of select="OtherParty[1]/NameAddress/Name2"/>
									<xsl:if test="OtherParty[1]/NameAddress/Name3">
										<xsl:if test="string-length(OtherParty[1]/NameAddress/Name3) !='0'">
											<xsl:value-of select="OtherParty[1]/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="OtherParty[1]/NameAddress/Address1"/>
						<xsl:if test="OtherParty[1]/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',OtherParty[1]/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(OtherParty[1]/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="OtherParty[1]/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(OtherParty[1]/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(OtherParty[1]/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(OtherParty[1]/NameAddress/Name1) !='0'">
							<xsl:value-of select="OtherParty[1]/NameAddress/Name1"/>
							<xsl:if test="OtherParty[1]/NameAddress/Name2">
								<xsl:if test="string-length(OtherParty[1]/NameAddress/Name2) !='0'">
									<xsl:value-of select="OtherParty[1]/NameAddress/Name2"/>
									<xsl:if test="OtherParty[1]/NameAddress/Name3">
										<xsl:if test="string-length(OtherParty[1]/NameAddress/Name3) !='0'">
											<xsl:value-of select="OtherParty[1]/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="OtherParty[1]/NameAddress/Address1"/>
						<xsl:if test="OtherParty[1]/NameAddress/Address2">
							<br/>
							<xsl:value-of select="OtherParty[1]/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(OtherParty[1]/NameAddress/City,', ')"/>
						<xsl:if test="OtherParty[1]/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(OtherParty[1]/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="OtherParty[1]/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="OtherParty[1]/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>
<xsl:template name="EndUserPartyLetterhead">
	<xsl:if test="string-length(SenderParty) !='0'">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(SenderParty) ='0'">
		<xsl:if test="string-length(EndUserParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="EndUserParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="EndUserParty/NameAddress/Address1"/>
							<xsl:if test="EndUserParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',EndUserParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(EndUserParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="EndUserParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(EndUserParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(EndUserParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="EndUserParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="EndUserParty/NameAddress/Address1"/>
							<xsl:if test="EndUserParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="EndUserParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(EndUserParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(EndUserParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(EndUserParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="EndUserParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
</xsl:template>
<xsl:template match="GPSCoordinates">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<xsl:if test="@GPSSystem">
					<table border="0" cellspacing="0" cellpadding="0" width="180">
						<tr>
							<td class="PartiesStyle4">
								<xsl:value-of select="$GPSSystem"/>
							</td>
						</tr>
						<tr>
							<td class="PartiesStyle2">
								<xsl:value-of select="@GPSSystem"/>
							</td>
						</tr>
					</table>
				</xsl:if>
				<table border="0" cellspacing="0" cellpadding="0" width="180">
					<tr>
						<td class="PartiesStyle4">
							<xsl:value-of select="$Latitude"/>
						</td>
					</tr>
					<tr>
						<td class="PartiesStyle2">
							<xsl:value-of select="Latitude"/>
						</td>
					</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="0" width="180">
					<tr>
						<td class="PartiesStyle4">
							<xsl:value-of select="$Longitude"/>
						</td>
					</tr>
					<tr>
						<td class="PartiesStyle2">
							<xsl:value-of select="Longitude"/>
						</td>
					</tr>
				</table>
				<xsl:if test="Height">
					<table border="0" cellspacing="0" cellpadding="0" width="180">
						<tr>
							<td class="PartiesStyle4">			
								HEIGHT:
							</td>
						</tr>
						<tr>
							<td>			
								<xsl:apply-templates select="Height/Value"/>
							</td>
						</tr>
					</table>
				</xsl:if>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template name="SenderPartyLetterhead">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
</xsl:template>
<xsl:template match="URL" mode="Header">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				 <xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td valign="top" class="PartiesStyle2">
				<a href="{.}" target="_blank">
					<xsl:value-of select="."/>
				</a>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="TotalNetAmount">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<span class="DeliveryInstructionsStyle1">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</span>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5" class="DeliveryInstructionsStyle2" align="right">
			<xsl:apply-templates/>
		</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>
<xsl:template match="TotalInformationalAmount">
	<tr>
		<td bgcolor="#F5F5F5" valign="top">
			<span class="DeliveryInstructionsStyle1">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</span>
		</td>
		<td>&#160;</td>
		<td bgcolor="#F5F5F5" class="DeliveryInstructionsStyle2" align="right">
			<xsl:apply-templates select="CurrencyValue"/>
		</td>
		<td bgcolor="#F5F5F5">&#160;</td>
	</tr>
	<tr>
		<td colspan="4">
			<xsl:apply-templates select="AdditionalText" mode="Summary"/>
		</td>
	</tr>
	<tr>
		<td valign="top" colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>
<xsl:template match="PunchedHoleDetails">
	<xsl:apply-templates select="@HoleReinforcement"/>
	<xsl:apply-templates/>
</xsl:template>
<xsl:template name="MonetaryAdjustmentHeader4">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Adjustment"/>&#160;<xsl:value-of select="MonetaryAdjustmentLine"/>&#160;<i>
				<xsl:value-of select="$ForPriceDetails"/></i>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="BoxCharacteristics">
	<!--<tr>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">-->
			<xsl:apply-templates select="@BoxType"/>
			<xsl:apply-templates/>
			<br/>
		<!--</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>-->
</xsl:template>
<xsl:template match="ProofInformationalQuantity">
	<xsl:apply-templates select="@ProofType"/>
	<xsl:apply-templates select="Quantity" mode="Other"/>
	<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
	<xsl:if test="OtherParty">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">
					<xsl:call-template name="OtherPartyHeader2"/>
					<xsl:apply-templates select="OtherParty"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates select="ProofApprovalDate"/>
	<xsl:apply-templates select="ProofDueDate"/>
	<xsl:apply-templates select="AdditionalText"/>
</xsl:template>


<xsl:template name="OtherPartyHeader2">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="OtherParty/@PartyType"/>
				<i>
					- FOR PROOF INFORMATION
					<!--<xsl:value-of select="$ForOrderLineNumber"/>-->
				</i><!--&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>-->
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="SuppliedComponentInformation">
	<xsl:apply-templates select="@SuppliedComponentType"/>
	<xsl:if test="SupplierParty">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">
					<xsl:call-template name="SupplierPartyHeader"/>
					<xsl:apply-templates select="SupplierParty"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F5F5F5">&#160;</td>
			</tr>
		</table>
	</xsl:if>
	<xsl:apply-templates select="ProductIdentifier"/>
	<xsl:apply-templates select="ProductDescription"/>
	<xsl:apply-templates select="Classification"/>
	<xsl:apply-templates select="SuppliedComponentReference"/>
	<xsl:apply-templates select="Quantity" mode="Other"/>
	<xsl:apply-templates select="ComponentShipDate"/>
	<xsl:apply-templates select="ComponentDueDate"/>
	<xsl:apply-templates select="ComponentNeededDate"/>
	<xsl:apply-templates select="OrderStatusInformation"/>
	<xsl:apply-templates select="AdditionalText"/>
</xsl:template>
<xsl:template name="SupplierPartyHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$Supplier"/>
				<i>
					- FOR SUPPLIED COMPONENT INFORMATION
					<!--<xsl:value-of select="$ForOrderLineNumber"/>-->
				</i><!--&#160;<xsl:value-of select="../PurchaseOrderLineItemNumber"/>-->
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="OrderPrimaryStatus">	
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="@OrderStatusCode"/>
</xsl:template>
<xsl:template match="ProofApprovalDate | ProofDueDate | ComponentShipDate | ComponentDueDate | ComponentNeededDate">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="SizeOfHolePunch">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<xsl:apply-templates select="Value"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="@ShapeOfHole"/>
</xsl:template>
<xsl:template match="DistanceFromEdge">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td>
				<xsl:apply-templates select="Value"/>
			</td>
		</tr>
	</table>
	<xsl:apply-templates select="@EdgeType"/>
</xsl:template>
<xsl:template match="BasisWeight">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">			
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<xsl:apply-templates select="DetailValue"/>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<xsl:apply-templates select="@ProductBasisSizeType | @TestMethod | @TestAgency | @SampleType | @ResultSource"/>
				<xsl:apply-templates select="StandardDeviation | SampleSize | TwoSigmaLower | TwoSigmaUpper"/>
			</td>
		</tr>
	</table>
	<br/>
</xsl:template>
<xsl:template match="UnitItem">
	<tr>
		<!--<td valign="top" bgcolor="#F5F5F5">&#160;</td>-->
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="LineItemStyle1">
						Unit Item:
					</td>
				</tr>
			</table>
		</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top">
			<xsl:apply-templates select="UnitCharacteristics"/>
			<xsl:apply-templates select="Product"/>
			<xsl:apply-templates select="Quantity" mode="Other"/>
			<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
			<br/>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
</xsl:template>
	<xsl:template name="BillToPartyAddressfield">
	<xsl:if test="string-length(ReceiverParty) !='0'">
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',ReceiverParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="ReceiverParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(ReceiverParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(ReceiverParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="ReceiverParty/NameAddress/Name1"/>
							<xsl:if test="ReceiverParty/NameAddress/Name2">
								<xsl:if test="string-length(ReceiverParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="ReceiverParty/NameAddress/Name2"/>
									<xsl:if test="ReceiverParty/NameAddress/Name3">
										<xsl:if test="string-length(ReceiverParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="ReceiverParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="ReceiverParty/NameAddress/Address1"/>
						<xsl:if test="ReceiverParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="ReceiverParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(ReceiverParty/NameAddress/City,', ')"/>
						<xsl:if test="ReceiverParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(ReceiverParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="ReceiverParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="ReceiverParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	<xsl:if test="string-length(ReceiverParty) ='0'">
		<xsl:if test="string-length(BillToParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(BillToParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="BillToParty/NameAddress/Name1"/>
							<xsl:if test="BillToParty/NameAddress/Name2">
								<xsl:if test="string-length(BillToParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="BillToParty/NameAddress/Name2"/>
									<xsl:if test="BillToParty/NameAddress/Name3">
										<xsl:if test="string-length(BillToParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="BillToParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="BillToParty/NameAddress/Address1"/>
						<xsl:if test="BillToParty/NameAddress/Address2">
							<xsl:value-of select="concat(',  ',BillToParty/NameAddress/Address2)"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BillToParty/NameAddress/PostalCode,'  ')"/>
						<xsl:value-of select="BillToParty/NameAddress/City"/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BillToParty/NameAddress/Country,'  ')"/>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(BillToParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" width="350">
				<tr>
					<td class="ReceiverAddressStyle10">
						<xsl:if test="string-length(BillToParty/NameAddress/Name1) !='0'">
							<xsl:value-of select="BillToParty/NameAddress/Name1"/>
							<xsl:if test="BillToParty/NameAddress/Name2">
								<xsl:if test="string-length(BillToParty/NameAddress/Name2) !='0'">
									<xsl:value-of select="BillToParty/NameAddress/Name2"/>
									<xsl:if test="BillToParty/NameAddress/Name3">
										<xsl:if test="string-length(BillToParty/NameAddress/Name3) !='0'">
											<xsl:value-of select="BillToParty/NameAddress/Name3"/>
										</xsl:if>
									</xsl:if>
								</xsl:if>
							</xsl:if>
						</xsl:if>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="BillToParty/NameAddress/Address1"/>
						<xsl:if test="BillToParty/NameAddress/Address2">
							<br/>
							<xsl:value-of select="BillToParty/NameAddress/Address2"/>
						</xsl:if>
						<br/>
					</td>
				</tr>
				<tr>
					<td class="ReceiverAddressStyle11">
						<xsl:value-of select="concat(BillToParty/NameAddress/City,', ')"/>
						<xsl:if test="BillToParty/NameAddress/StateOrProvince">
							<xsl:value-of select="concat(BillToParty/NameAddress/StateOrProvince,'  ')"/>
						</xsl:if>
						<xsl:value-of select="BillToParty/NameAddress/PostalCode"/>
						<br/>
						<br/>
						<xsl:value-of select="BillToParty/NameAddress/Country"/>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:if>
	</xsl:template>
<xsl:template name="BillToSupplierShipToParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$BillTo"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$ShipTo"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="BillToParty/NameAddress/Address1 | 
		BillToParty/NameAddress/Address2 | 
		BillToParty/NameAddress/Address3 | 
		BillToParty/NameAddress/City | 
		BillToParty/NameAddress/County | 
		BillToParty/NameAddress/StateOrProvince | 
		BillToParty/NameAddress/Country |
		SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		ShipToCharacteristics/ShipToParty/NameAddress/Address1 | 
		ShipToCharacteristics/ShipToParty/NameAddress/Address2 | 
		ShipToCharacteristics/ShipToParty/NameAddress/Address3 | 
		ShipToCharacteristics/ShipToParty/NameAddress/City | 
		ShipToCharacteristics/ShipToParty/NameAddress/County | 
		ShipToCharacteristics/ShipToParty/NameAddress/StateOrProvince | 
		ShipToCharacteristics/ShipToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BillToParty/URL | SupplierParty/URL | ShipToCharacteristics/ShipToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="BillToParty/NameAddress/OrganisationUnit or SupplierParty/NameAddress/OrganisationUnit or ShipToCharacteristics/ShipToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BillToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="BillToParty/CommonContact | SupplierParty/CommonContact | ShipToCharacteristics/ShipToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BillToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="ShipToCharacteristics/ShipToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template match="PriceDetails" mode="CreditDebitNote">
	<tr>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">
			<xsl:apply-templates select="@PriceQuantityBasis"/>
			<xsl:apply-templates select="@PriceTaxBasis"/>
			<xsl:apply-templates select="AdditionalText"/>
			<xsl:apply-templates select="ExchangeRate"/>
			<br/>
			<xsl:if test="MonetaryAdjustment">
				<xsl:for-each select="MonetaryAdjustment">
					<tr>
						<td valign="top" bgcolor="#F5F5F5">&#160;</td>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
						<td valign="top" bgcolor="#F5F5F5">								
							<xsl:call-template name="MonetaryAdjustmentHeader3"/>
							<xsl:apply-templates select="."/>
						</td>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
						<td valign="top" bgcolor="#F5F5F5">&#160;</td>
						<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					</tr>
				</xsl:for-each>
			</xsl:if>
			<xsl:if test="GeneralLedgerAccount">
				<tr>
					<td valign="top" bgcolor="#F5F5F5">&#160;</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F5F5F5">	
						<xsl:apply-templates select="GeneralLedgerAccount"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F5F5F5">&#160;</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F5F5F5">&#160;</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
</xsl:template>

<xsl:template match="TermsAndDisclaimers" mode="Header">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="TimePeriod">
	<tr>
		<td class="MessageInformationStyle1">
			<xsl:value-of select="$TimePeriod"/>
		</td>
	</tr>	
	<xsl:if test="Date">
		<tr>
			<td class="MessageInformationStyle2">
				<xsl:call-template name="GetDate"/><br/><xsl:value-of select="Time"/>
				<xsl:if test="@PeriodType">
					<br/>
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@PeriodType"/>
					<xsl:text>]</xsl:text>
				</xsl:if>
			</td>
		</tr>		
	</xsl:if>
	<xsl:if test="DateTimeRange">
		<tr>
			<td class="MessageInformationStyle2">
				<xsl:apply-templates select="DateTimeRange/DateTimeFrom"/>
				<xsl:apply-templates select="DateTimeRange/DateTimeTo"/>
				<xsl:if test="@PeriodType">
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@PeriodType"/>
					<xsl:text>]</xsl:text>
				</xsl:if>
			</td>
		</tr>
	</xsl:if>
	<xsl:if test="Week">
		<tr>
			<td class="MessageInformationStyle2">
				<xsl:apply-templates select="Week"/>
				<xsl:if test="@PeriodType">
					<br/>
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@PeriodType"/>
					<xsl:text>]</xsl:text>
				</xsl:if>
			</td>
		</tr>
	</xsl:if>
</xsl:template>
<xsl:template match="ProductQualityReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template name="ShipToPartyHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				<xsl:value-of select="$ShipTo2"/>&#160;
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="ShipToParty/@PartyType"/>
				<xsl:text>]</xsl:text>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template name="LocationPartyHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				LOCATION<!--<xsl:value-of select="$ShipTo2"/>-->&#160;
				<xsl:text>[</xsl:text>
				<xsl:apply-templates select="LocationParty/@PartyType"/>
				<xsl:text>]</xsl:text>&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template name="EndUserPartyHeader">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td valign="top" bgcolor="#E6E6E6" class="LineItemStyle4">
				END USER<!--<xsl:value-of select="$ShipTo2"/>-->&#160;
				<i>
					<xsl:value-of select="$ForOrderLineNumber"/>
				</i>&#160;<xsl:value-of select="PurchaseOrderLineItemNumber"/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="Identifier | RawMaterialSet" mode="ItemDetails">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@IdentifierCodeType">
			<tr>
				<td class="LineItemStyle6" valign="top">
					<xsl:text>[</xsl:text>
						<xsl:apply-templates select="@IdentifierCodeType"/>
					<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
		<xsl:if test="@IdentifierType">
			<tr>
				<td class="LineItemStyle6">
					<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@IdentifierType"/>
					<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<!--
	<xsl:template name="SenderPartyLetterhead">
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) ='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<xsl:value-of select="concat(',  ',SenderParty/NameAddress/Address2)"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,' ')"/>
							<xsl:value-of select="SenderParty/NameAddress/City"/>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/Country,' ')"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
		<xsl:if test="string-length(SenderParty/NameAddress/StateOrProvince) !='0'">
			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<br/>
						<span class="SenderAddressStyle12">
							<xsl:value-of select="SenderParty/NameAddress/Name1"/>
							<br/>
						</span>
						<span class="SenderAddressStyle13">
							<xsl:value-of select="SenderParty/NameAddress/Address1"/>
							<xsl:if test="SenderParty/NameAddress/Address2">
								<br/>
								<xsl:value-of select="SenderParty/NameAddress/Address2"/>
							</xsl:if>
							<br/>
							<xsl:value-of select="concat(SenderParty/NameAddress/City,',  ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/StateOrProvince,' ')"/>
							<xsl:value-of select="concat(SenderParty/NameAddress/PostalCode,'  ')"/>
							<br/>
							<xsl:value-of select="SenderParty/NameAddress/Country"/>
							<br/>
							<br/>
						</span>
					</td>
				</tr>
			</table>
		</xsl:if>
	</xsl:template>
-->
<xsl:template name="SenderReceiverBuyerParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Buyer"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		BuyerParty/NameAddress/Address1 | 
		BuyerParty/NameAddress/Address2 | 
		BuyerParty/NameAddress/Address3 | 
		BuyerParty/NameAddress/City | 
		BuyerParty/NameAddress/County | 
		BuyerParty/NameAddress/StateOrProvince | 
		BuyerParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | ReceiverParty/URL | BuyerParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or ReceiverParty/NameAddress/OrganisationUnit or BuyerParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="BuyerParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | BuyerParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="BuyerParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="SupplierOtherParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[1]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="OtherParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		OtherParty[1]/NameAddress/Address1 | 
		OtherParty[1]/NameAddress/Address2 | 
		OtherParty[1]/NameAddress/Address3 | 
		OtherParty[1]/NameAddress/City | 
		OtherParty[1]/NameAddress/County | 
		OtherParty[1]/NameAddress/StateOrProvince | 
		OtherParty[1]/NameAddress/Country |
		OtherParty[2]/NameAddress/Address1 | 
		OtherParty[2]/NameAddress/Address2 | 
		OtherParty[2]/NameAddress/Address3 | 
		OtherParty[2]/NameAddress/City | 
		OtherParty[2]/NameAddress/County | 
		OtherParty[2]/NameAddress/StateOrProvince | 
		OtherParty[2]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SupplierParty/URL | OtherParty[1]/URL | OtherParty[2]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SupplierParty/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SupplierParty/NameAddress/OrganisationUnit | OtherParty[1]/NameAddress/OrganisationUnit | OtherParty[2]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="SupplierParty/CommonContact | OtherParty[1]/CommonContact | OtherParty[2]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="SupplierParty/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template match="ComplaintReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@ComplaintReferenceType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
					<xsl:apply-templates select="@ComplaintReferenceType"/>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template match="ComplaintReason">
	<xsl:apply-templates select="@ComplaintReasonType"/>
	<xsl:apply-templates/>
</xsl:template>
<xsl:template match="Charge">
	<xsl:apply-templates select="@ChargeType"/>
	<xsl:apply-templates select="@ChargeContext"/>
	<xsl:apply-templates select="Quantity" mode="Other"/>
	<xsl:apply-templates select="InformationalQuantity" mode="Other"/>
	<xsl:apply-templates select="ChargePerUnit"/>
	<xsl:apply-templates select="NetChargeAmount"/>
	<xsl:apply-templates select="AdditionalText"/>
</xsl:template>
<xsl:template match="NetChargeAmount">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="CurrencyValue"/>
			</td>
		</tr>
	</table>
</xsl:template>
<xsl:template match="ChargePerUnit">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:apply-templates select="CurrencyValue"/>/<xsl:apply-templates select="Value"/>
			</td>
		</tr>
	</table>	
</xsl:template>
<xsl:template match="ComplaintResponseReason">
	<xsl:apply-templates select="@ComplaintResponseReasonType"/>
	<xsl:apply-templates/>
</xsl:template>
<xsl:template match="ComplaintResponseReference">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@ComplaintResponseReferenceType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
						<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@ComplaintResponseReferenceType"/>					
						<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template match="MailAttachment">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:value-of select="."/>
			</td>
		</tr>
		<xsl:if test="@MailAttachmentType">
			<tr>
				<td class="LineItemStyle1">&#160;</td>
				<td class="LineItemStyle6">
						<xsl:text>[</xsl:text>
					<xsl:apply-templates select="@MailAttachmentType"/>
						<xsl:text>]</xsl:text>
				</td>
			</tr>
		</xsl:if>
	</table>
</xsl:template>
<xsl:template name="SenderReceiverRequestingParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<!--<xsl:value-of select="$Receiver"/>-->Requesting&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="RequestingParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RequestingParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		RequestingParty/NameAddress/Address1 | 
		RequestingParty/NameAddress/Address2 | 
		RequestingParty/NameAddress/Address3 | 
		RequestingParty/NameAddress/City | 
		RequestingParty/NameAddress/County | 
		RequestingParty/NameAddress/StateOrProvince | 
		RequestingParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RequestingParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | ReceiverParty/URL | RequestingParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RequestingParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or ReceiverParty/NameAddress/OrganisationUnit or RequestingParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="RequestingParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | RequestingParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RequestingParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="RespondToParty">
	<tr>
		<td colspan="5">
			<hr style="height:1px;color:#000000;noshade"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<!--<xsl:value-of select="$RespondTo"/>-->Respond To&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="RespondToParty[1]/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
			<xsl:value-of select="RespondToParty[2]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
			<xsl:value-of select="RespondToParty[3]/@PartyType"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty[1]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="RespondToParty[2]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty[3]/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="RespondToParty[1]/NameAddress/Address1 | 
		RespondToParty[1]/NameAddress/Address2 | 
		RespondToParty[1]/NameAddress/Address3 | 
		RespondToParty[1]/NameAddress/City | 
		RespondToParty[1]/NameAddress/County | 
		RespondToParty[1]/NameAddress/StateOrProvince | 
		RespondToParty[1]/NameAddress/Country |
		RespondToParty[2]/NameAddress/Address1 | 
		RespondToParty[2]/NameAddress/Address2 | 
		RespondToParty[2]/NameAddress/Address3 | 
		RespondToParty[2]/NameAddress/City | 
		RespondToParty[2]/NameAddress/County | 
		RespondToParty[2]/NameAddress/StateOrProvince | 
		RespondToParty[2]/NameAddress/Country |
		RespondToParty[3]/NameAddress/Address1 | 
		RespondToParty[3]/NameAddress/Address2 | 
		RespondToParty[3]/NameAddress/Address3 | 
		RespondToParty[3]/NameAddress/City | 
		RespondToParty[3]/NameAddress/County | 
		RespondToParty[3]/NameAddress/StateOrProvince | 
		RespondToParty[3]/NameAddress/Country">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Address"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[1]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RespondToParty[2]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[3]/NameAddress" mode="Address"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RespondToParty[1]/URL | RespondToParty[2]/URL | RespondToParty[3]/URL">
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[1]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RespondToParty[2]/URL"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[3]/URL"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RespondToParty[1]/NameAddress/OrganisationUnit | RespondToParty[2]/NameAddress/OrganisationUnit | RespondToParty[3]/NameAddress/OrganisationUnit">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Department"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[1]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RespondToParty[2]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[3]/NameAddress/OrganisationUnit"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="RespondToParty[1]/CommonContact | RespondToParty[2]/CommonContact | RespondToParty[3]/CommonContact">
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Contact"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[1]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="RespondToParty[2]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="RespondToParty[3]/CommonContact"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
		</xsl:if>
		<xsl:if test="OtherParty[3]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[3]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[4]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[5]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[3]/NameAddress/Address1 | 
				OtherParty[3]/NameAddress/Address2 | 
				OtherParty[3]/NameAddress/Address3 | 
				OtherParty[3]/NameAddress/City | 
				OtherParty[3]/NameAddress/County | 
				OtherParty[3]/NameAddress/StateOrProvince | 
				OtherParty[3]/NameAddress/Country |
				OtherParty[4]/NameAddress/Address1 | 
				OtherParty[4]/NameAddress/Address2 | 
				OtherParty[4]/NameAddress/Address3 | 
				OtherParty[4]/NameAddress/City | 
				OtherParty[4]/NameAddress/County | 
				OtherParty[4]/NameAddress/StateOrProvince | 
				OtherParty[4]/NameAddress/Country |
				OtherParty[5]/NameAddress/Address1 | 
				OtherParty[5]/NameAddress/Address2 | 
				OtherParty[5]/NameAddress/Address3 | 
				OtherParty[5]/NameAddress/City | 
				OtherParty[5]/NameAddress/County | 
				OtherParty[5]/NameAddress/StateOrProvince | 
				OtherParty[5]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/URL | OtherParty[4]/URL | OtherParty[5]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/NameAddress/OrganisationUnit | OtherParty[4]/NameAddress/OrganisationUnit | OtherParty[5]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[3]/CommonContact | OtherParty[4]/CommonContact | OtherParty[5]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[3]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[4]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[5]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[6]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[6]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[7]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[8]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[6]/NameAddress/Address1 | 
				OtherParty[6]/NameAddress/Address2 | 
				OtherParty[6]/NameAddress/Address3 | 
				OtherParty[6]/NameAddress/City | 
				OtherParty[6]/NameAddress/County | 
				OtherParty[6]/NameAddress/StateOrProvince | 
				OtherParty[6]/NameAddress/Country |
				OtherParty[7]/NameAddress/Address1 | 
				OtherParty[7]/NameAddress/Address2 | 
				OtherParty[7]/NameAddress/Address3 | 
				OtherParty[7]/NameAddress/City | 
				OtherParty[7]/NameAddress/County | 
				OtherParty[7]/NameAddress/StateOrProvince | 
				OtherParty[7]/NameAddress/Country |
				OtherParty[8]/NameAddress/Address1 | 
				OtherParty[8]/NameAddress/Address2 | 
				OtherParty[8]/NameAddress/Address3 | 
				OtherParty[8]/NameAddress/City | 
				OtherParty[8]/NameAddress/County | 
				OtherParty[8]/NameAddress/StateOrProvince | 
				OtherParty[8]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/URL | OtherParty[7]/URL | OtherParty[8]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/NameAddress/OrganisationUnit | OtherParty[7]/NameAddress/OrganisationUnit | OtherParty[8]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[6]/CommonContact | OtherParty[7]/CommonContact | OtherParty[8]/CommonContact">  
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[6]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[7]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[8]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
		<xsl:if test="OtherParty[9]">
			<tr>
				<td colspan="5">
					<hr style="height:1px;color:#000000;noshade"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[9]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[10]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5">
					<xsl:apply-templates select="OtherParty[11]/@PartyType"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<tr>
				<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
					<xsl:value-of select="$Company"/>
				</td>
			</tr>
			<tr>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">
					<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#F6F6F6">
					<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Name"/>
				</td>
				<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			</tr>
			<xsl:if test="OtherParty[9]/NameAddress/Address1 | 
				OtherParty[9]/NameAddress/Address2 | 
				OtherParty[9]/NameAddress/Address3 | 
				OtherParty[9]/NameAddress/City | 
				OtherParty[9]/NameAddress/County | 
				OtherParty[9]/NameAddress/StateOrProvince | 
				OtherParty[9]/NameAddress/Country |
				OtherParty[10]/NameAddress/Address1 | 
				OtherParty[10]/NameAddress/Address2 | 
				OtherParty[10]/NameAddress/Address3 | 
				OtherParty[10]/NameAddress/City | 
				OtherParty[10]/NameAddress/County | 
				OtherParty[10]/NameAddress/StateOrProvince | 
				OtherParty[10]/NameAddress/Country |
				OtherParty[11]/NameAddress/Address1 | 
				OtherParty[11]/NameAddress/Address2 | 
				OtherParty[11]/NameAddress/Address3 | 
				OtherParty[11]/NameAddress/City | 
				OtherParty[11]/NameAddress/County | 
				OtherParty[11]/NameAddress/StateOrProvince | 
				OtherParty[11]/NameAddress/Country">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Address"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress" mode="Address"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/URL | OtherParty[10]/URL | OtherParty[11]/URL">
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/URL"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/URL"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/NameAddress/OrganisationUnit | OtherParty[10]/NameAddress/OrganisationUnit | OtherParty[11]/NameAddress/OrganisationUnit">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Department"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/NameAddress/OrganisationUnit"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
			<xsl:if test="OtherParty[9]/CommonContact | OtherParty[10]/CommonContact | OtherParty[11]/CommonContact">
				<tr>
					<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
						<xsl:value-of select="$Contact"/>
					</td>
				</tr>
				<tr>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[9]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">
						<xsl:apply-templates select="OtherParty[10]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#F6F6F6">
						<xsl:apply-templates select="OtherParty[11]/CommonContact"/>
					</td>
					<td valign="top" bgcolor="#FEFEFE">&#160;</td>
				</tr>
			</xsl:if>
		</xsl:if>
</xsl:template>
<xsl:template name="SenderReceiverRespondToParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Receiver"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="ReceiverParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<!--<xsl:value-of select="$Receiver"/>-->RespondTo&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="RespondToParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		ReceiverParty/NameAddress/Address1 | 
		ReceiverParty/NameAddress/Address2 | 
		ReceiverParty/NameAddress/Address3 | 
		ReceiverParty/NameAddress/City | 
		ReceiverParty/NameAddress/County | 
		ReceiverParty/NameAddress/StateOrProvince | 
		ReceiverParty/NameAddress/Country |
		RespondToParty/NameAddress/Address1 | 
		RespondToParty/NameAddress/Address2 | 
		RespondToParty/NameAddress/Address3 | 
		RespondToParty/NameAddress/City | 
		RespondToParty/NameAddress/County | 
		RespondToParty/NameAddress/StateOrProvince | 
		RespondToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | ReceiverParty/URL | RespondToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or ReceiverParty/NameAddress/OrganisationUnit or RespondToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="ReceiverParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="RespondToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | ReceiverParty/CommonContact | RespondToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="ReceiverParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
<xsl:template name="SenderSupplierRespondToParty">
	<tr>
		<td valign="top" bgcolor="#FEFEFE" width="70">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Sender"/>&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="SenderParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" class="PartiesStyle5" width="180">
			<xsl:value-of select="$Supplier"/>&#160;
		</td>
		<td valign="top" bgcolor="#F6F6F6" class="PartiesStyle5" width="180">
			RespondTo&#160;
			<xsl:text>[</xsl:text>
			<xsl:value-of select="RespondToParty/@PartyType"/>
			<xsl:text>]</xsl:text>
		</td>
		<td valign="top" bgcolor="#FEFEFE" width="30">&#160;</td>
	</tr>
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Company"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/NameAddress" mode="Name"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	<xsl:if test="SenderParty/NameAddress/Address1 | 
		SenderParty/NameAddress/Address2 | 
		SenderParty/NameAddress/Address3 | 
		SenderParty/NameAddress/City | 
		SenderParty/NameAddress/County | 
		SenderParty/NameAddress/StateOrProvince | 
		SenderParty/NameAddress/Country |
		SupplierParty/NameAddress/Address1 | 
		SupplierParty/NameAddress/Address2 | 
		SupplierParty/NameAddress/Address3 | 
		SupplierParty/NameAddress/City | 
		SupplierParty/NameAddress/County | 
		SupplierParty/NameAddress/StateOrProvince | 
		SupplierParty/NameAddress/Country |
		RespondToParty/NameAddress/Address1 | 
		RespondToParty/NameAddress/Address2 | 
		RespondToParty/NameAddress/Address3 | 
		RespondToParty/NameAddress/City | 
		RespondToParty/NameAddress/County | 
		RespondToParty/NameAddress/StateOrProvince | 
		RespondToParty/NameAddress/Country">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Address"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/NameAddress" mode="Address"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/URL | SupplierParty/URL | RespondToParty/URL">
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/URL"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/URL"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
	<xsl:if test="SenderParty/NameAddress/OrganisationUnit or SupplierParty/NameAddress/OrganisationUnit or RespondToParty/NameAddress/OrganisationUnit">
		<tr>
			<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
				<xsl:value-of select="$Department"/>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="SenderParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">
				<xsl:apply-templates select="SupplierParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#F6F6F6">
				<xsl:apply-templates select="RespondToParty/NameAddress/OrganisationUnit"/>
			</td>
			<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		</tr>
	</xsl:if>
	<xsl:if test="SenderParty/CommonContact | SupplierParty/CommonContact | RespondToParty/CommonContact">
	<tr>
		<td valign="top" colspan="5" bgcolor="#DEDEDE" class="PartiesStyle1">
			<xsl:value-of select="$Contact"/>
		</td>
	</tr>
	<tr>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="SenderParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">
			<xsl:apply-templates select="SupplierParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#F6F6F6">
			<xsl:apply-templates select="RespondToParty/CommonContact"/>
		</td>
		<td valign="top" bgcolor="#FEFEFE">&#160;</td>
	</tr>
	</xsl:if>
</xsl:template>
</xsl:stylesheet>








<!-- Stylus Studio meta-information - (c)1998-2002 eXcelon Corp.
<metaInformation>
<scenarios ><scenario default="no" name="po" userelativepaths="yes" externalpreview="no" url="PurchaseOrder_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="inv" userelativepaths="yes" externalpreview="no" url="Invoice_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="comp" userelativepaths="yes" externalpreview="no" url="Complaint_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="oc" userelativepaths="yes" externalpreview="no" url="OrderConfirmation_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="iv" userelativepaths="yes" externalpreview="no" url="Invoice_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="ic" userelativepaths="yes" externalpreview="no" url="InventoryChange_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="co" userelativepaths="yes" externalpreview="no" url="CallOff_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="gr" userelativepaths="yes" externalpreview="no" url="GoodsReceipt_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="is" userelativepaths="yes" externalpreview="no" url="InventoryStatus_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="cdn" userelativepaths="yes" externalpreview="no" url="CreditDebitNote_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="pq" userelativepaths="yes" externalpreview="no" url="ProductQuality_PurchaseOrder_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="pq&#x2D;po1" userelativepaths="yes" externalpreview="no" url="ProductQuality1_PurchaseOrder_SENA_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="pq&#x2D;ship" userelativepaths="yes" externalpreview="no" url="ProductQuality_Shipment_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="compres" userelativepaths="yes" externalpreview="no" url="ComplaintResponse_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="os" userelativepaths="yes" externalpreview="no" url="OrderStatus_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="yes" name="compresA" userelativepaths="yes" externalpreview="no" url="Complaint_Response_ A_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/></scenarios><MapperInfo srcSchemaPath="" srcSchemaRoot="" srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
</metaInformation>
-->