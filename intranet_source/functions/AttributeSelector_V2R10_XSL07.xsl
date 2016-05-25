<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- ***     Attribute ActionType     *** -->

<xsl:template match="@ActionType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Special'"><xsl:value-of select="$ActionType-Special"/></xsl:when>
					<xsl:when test=".='Standard'"><xsl:value-of select="$ActionType-Standard"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ActionType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute AdjustmentType     *** -->

<xsl:template match="@AdjustmentType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='CancellationCharge'"><xsl:value-of select="$AdjustmentType-CancellationCharge"/></xsl:when>
					<xsl:when test=".='CashDiscount'"><xsl:value-of select="$AdjustmentType-CashDiscount"/></xsl:when>
					<xsl:when test=".='ChargesForward'"><xsl:value-of select="$AdjustmentType-ChargesForward"/></xsl:when>
					<xsl:when test=".='ClaimAdjustment'"><xsl:value-of select="$AdjustmentType-ClaimAdjustment"/></xsl:when>
					<xsl:when test=".='Commission'"><xsl:value-of select="$AdjustmentType-Commission"/></xsl:when>
					<xsl:when test=".='ContractAllowance'"><xsl:value-of select="$AdjustmentType-ContractAllowance"/></xsl:when>
					<xsl:when test=".='Core'"><xsl:value-of select="$AdjustmentType-Core"/></xsl:when>
					<xsl:when test=".='DeliveryNonConformanceAllowance'"><xsl:value-of select="$AdjustmentType-DeliveryNonConformanceAllowance"/></xsl:when>
					<xsl:when test=".='EarlyShipAllowance'"><xsl:value-of select="$AdjustmentType-EarlyShipAllowance"/></xsl:when>
					<xsl:when test=".='Environmental'"><xsl:value-of select="$AdjustmentType-Environmental"/></xsl:when>
					<xsl:when test=".='ExpeditedShipmentCharge'"><xsl:value-of select="$AdjustmentType-ExpeditedShipmentCharge"/></xsl:when>
					<xsl:when test=".='FreightAllowance'"><xsl:value-of select="$AdjustmentType-FreightAllowance"/></xsl:when>
					<xsl:when test=".='FreightCharge'"><xsl:value-of select="$AdjustmentType-FreightCharge"/></xsl:when>
					<xsl:when test=".='Inspection'"><xsl:value-of select="$AdjustmentType-Inspection"/></xsl:when>
					<xsl:when test=".='InterestCharge'"><xsl:value-of select="$AdjustmentType-InterestCharge"/></xsl:when>
					<xsl:when test=".='Pallet'"><xsl:value-of select="$AdjustmentType-Pallet"/></xsl:when>
					<xsl:when test=".='PriceCorrection'"><xsl:value-of select="$AdjustmentType-PriceCorrection"/></xsl:when>
					<xsl:when test=".='ProductionSetUpCharge'"><xsl:value-of select="$AdjustmentType-ProductionSetUpCharge"/></xsl:when>
					<xsl:when test=".='Provision'"><xsl:value-of select="$AdjustmentType-Provision"/></xsl:when>
					<xsl:when test=".='Rebate'"><xsl:value-of select="$AdjustmentType-Rebate"/></xsl:when>
					<xsl:when test=".='ReturnedLoadAllowance'"><xsl:value-of select="$AdjustmentType-ReturnedLoadAllowance"/></xsl:when>
					<xsl:when test=".='ReturnLoadCharge'"><xsl:value-of select="$AdjustmentType-ReturnLoadCharge"/></xsl:when>
					<xsl:when test=".='SpecialDeliveryCharge'"><xsl:value-of select="$AdjustmentType-SpecialDeliveryCharge"/></xsl:when>
					<xsl:when test=".='SpecialHandlingCharge'"><xsl:value-of select="$AdjustmentType-SpecialHandlingCharge"/></xsl:when>
					<xsl:when test=".='SpecialPackagingCharge'"><xsl:value-of select="$AdjustmentType-SpecialPackagingCharge"/></xsl:when>
					<xsl:when test=".='StopOff'"><xsl:value-of select="$AdjustmentType-StopOff"/></xsl:when>
					<xsl:when test=".='StopOffAllowance'"><xsl:value-of select="$AdjustmentType-StopOffAllowance"/></xsl:when>
					<xsl:when test=".='StopOffCharge'"><xsl:value-of select="$AdjustmentType-StopOffCharge"/></xsl:when>
					<xsl:when test=".='Storage'"><xsl:value-of select="$AdjustmentType-Storage"/></xsl:when>
					<xsl:when test=".='StorageCharge'"><xsl:value-of select="$AdjustmentType-StorageCharge"/></xsl:when>
					<xsl:when test=".='Straps'"><xsl:value-of select="$AdjustmentType-Straps"/></xsl:when>
					<xsl:when test=".='Tax'"><xsl:value-of select="$AdjustmentType-Tax"/></xsl:when>
					<xsl:when test=".='TestingCharge'"><xsl:value-of select="$AdjustmentType-TestingCharge"/></xsl:when>
					<xsl:when test=".='Total'"><xsl:value-of select="$AdjustmentType-Total"/></xsl:when>
					<xsl:when test=".='TotalOldInformationalQuantity'"><xsl:value-of select="$AdjustmentType-TotalOldInformationalQuantity"/></xsl:when>
					<xsl:when test=".='TotalOldQuantity'"><xsl:value-of select="$AdjustmentType-TotalOldQuantity"/></xsl:when>
					<xsl:when test=".='TradeDiscount'"><xsl:value-of select="$AdjustmentType-TradeDiscount"/></xsl:when>
					<xsl:when test=".='TrialDiscount'"><xsl:value-of select="$AdjustmentType-TrialDiscount"/></xsl:when>
					<xsl:when test=".='TransferCharge'"><xsl:value-of select="$AdjustmentType-TransferCharge"/></xsl:when>
					<xsl:when test=".='VolumeDiscount'"><xsl:value-of select="$AdjustmentType-VolumeDiscount"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$AdjustmentType-Other"/></xsl:when>
					<xsl:when test=".='OrderStatusQuantity'"><xsl:value-of select="$AdjustmentType-OrderStatusQuantity"/></xsl:when>
					<xsl:when test=".='Wrap'"><xsl:value-of select="$AdjustmentType-Wrap"/></xsl:when>
					<xsl:when test=".='WrapCore'"><xsl:value-of select="$AdjustmentType-WrapCore"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@AdjustmentType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- AgencyType gibts nicht mehr -->

<!-- ***     Attribute AgencyCode     *** -->

<xsl:template match="@Agency">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='AFPA'"><xsl:value-of select="$Agency-AFPA"/></xsl:when>
					<xsl:when test=".='ANSI'"><xsl:value-of select="$Agency-ANSI"/></xsl:when>
					<xsl:when test=".='APPITA'"><xsl:value-of select="$Agency-APPITA"/></xsl:when>
					<xsl:when test=".='BISAC'"><xsl:value-of select="$Agency-BISAC"/></xsl:when>
					<xsl:when test=".='Buyer'"><xsl:value-of select="$Agency-Buyer"/></xsl:when>
					<xsl:when test=".='CCTI'"><xsl:value-of select="$Agency-CCTI"/></xsl:when>
					<xsl:when test=".='CEPI'"><xsl:value-of select="$Agency-CEPI"/></xsl:when>
					<xsl:when test=".='DUNS'"><xsl:value-of select="$Agency-DUNS"/></xsl:when>
					<xsl:when test=".='EAN'"><xsl:value-of select="$Agency-EAN"/></xsl:when>
					<xsl:when test=".='Expresso'"><xsl:value-of select="$Agency-Expresso"/></xsl:when>
					<xsl:when test=".='ForestExpress'"><xsl:value-of select="$Agency-ForestExpress"/></xsl:when>
					<xsl:when test=".='GCA'"><xsl:value-of select="$Agency-GCA"/></xsl:when>
					<xsl:when test=".='GCA-CCTI'"><xsl:value-of select="$Agency-GCA-CCTI"/></xsl:when>
					<xsl:when test=".='IFRA'"><xsl:value-of select="$Agency-IFRA"/></xsl:when>
					<xsl:when test=".='Intrastat'"><xsl:value-of select="$Agency-Intrastat"/></xsl:when>
					<xsl:when test=".='ISO'"><xsl:value-of select="$Agency-ISO"/></xsl:when>
					<xsl:when test=".='NTPA'"><xsl:value-of select="$Agency-NTPA"/></xsl:when>
					<xsl:when test=".='Ondule'"><xsl:value-of select="$Agency-Ondule"/></xsl:when>
					<xsl:when test=".='PPPC'"><xsl:value-of select="$Agency-PPPC"/></xsl:when>
					<xsl:when test=".='Supplier'"><xsl:value-of select="$Agency-Supplier"/></xsl:when>
					<xsl:when test=".='TAPPI'"><xsl:value-of select="$Agency-TAPPI"/></xsl:when>
					<xsl:when test=".='UCC'"><xsl:value-of select="$Agency-UCC"/></xsl:when>
					<xsl:when test=".='XBITS'"><xsl:value-of select="$Agency-XBITS"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$Agency-Other"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@Agency"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute BandType     *** -->

<xsl:template match="@BandType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Metal'"><xsl:value-of select="$BandType-Metal"/></xsl:when>
					<xsl:when test=".='Nylon'"><xsl:value-of select="$BandType-Nylon"/></xsl:when>
					<xsl:when test=".='Plastic'"><xsl:value-of select="$BandType-Plastic"/></xsl:when>
					<xsl:when test=".='Standard'"><xsl:value-of select="$BandType-Standard"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@BandType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute BandsRequired     *** -->

<xsl:template match="@BandsRequired">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$BandsRequired-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$BandsRequired-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@BandsRequired"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute BleachingProcess   *** -->

<xsl:template match="@BleachingProcess">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='TotallyChlorineFree'"><xsl:value-of select="$BleachingProcess-TotallyChlorineFree"/></xsl:when>
					<xsl:when test=".='ElementaryChlorineFree'"><xsl:value-of select="$BleachingProcess-ElementaryChlorineFree"/></xsl:when>
					<xsl:when test=".='ChlorineBleached'"><xsl:value-of select="$BleachingProcess-ChlorineBleached"/></xsl:when>
					<xsl:when test=".='Unbleached'"><xsl:value-of select="$BleachingProcess-Unbleached"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@BleachingProcess"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute BaleType     *** -->

<xsl:template match="@BaleType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Sheet'"><xsl:value-of select="$BaleType-Sheet"/></xsl:when>
					<xsl:when test=".='FlashDried'"><xsl:value-of select="$BaleType-FlashDried"/></xsl:when>
					<xsl:when test=".='WetLap'"><xsl:value-of select="$BaleType-WetLap"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@BaleType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute BoxType     *** -->

<xsl:template match="@BoxType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
			<xsl:choose>
				<xsl:when test=".='Corrugated'"><xsl:value-of select="$BoxType-Corrugated"/></xsl:when>
				<xsl:when test=".='DoubleWall'"><xsl:value-of select="$BoxType-DoubleWall"/></xsl:when>
				<xsl:when test=".='SingleWall'"><xsl:value-of select="$BoxType-SingleWall"/></xsl:when>
				<xsl:when test=".='TripleWall'"><xsl:value-of select="$BoxType-TripleWall"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@BoxType"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ByLoadType     *** -->

<xsl:template match="@ByLoadType">
	<xsl:choose>
		<xsl:when test=".='Shipment'"><xsl:value-of select="$ByLoadType-Shipment"/></xsl:when>
		<xsl:when test=".='Forecast'"><xsl:value-of select="$ByLoadType-Forecast"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ByLoadType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CallOffHeaderStatusType     *** -->

<xsl:template match="@CallOffHeaderStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$CallOffHeaderStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$CallOffHeaderStatusType-Amended"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$CallOffHeaderStatusType-NoAction"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$CallOffHeaderStatusType-Original"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$CallOffHeaderStatusType-Rejected"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffHeaderStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CallOffLineItemStatusType     *** -->

<xsl:template match="@CallOffLineItemStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$CallOffLineItemStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$CallOffLineItemStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$CallOffLineItemStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$CallOffLineItemStatusType-NoAction"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$CallOffLineItemStatusType-Original"/></xsl:when>
		<xsl:when test=".='Pending'"><xsl:value-of select="$CallOffLineItemStatusType-Pending"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$CallOffLineItemStatusType-Rejected"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffLineItemStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CallOffProductConversionType     *** -->

<xsl:template match="@CallOffProductConversionType">
	<xsl:choose>
		<xsl:when test=".='CallOffReel'"><xsl:value-of select="$CallOffProductConversionType-CallOffReel"/></xsl:when>
		<xsl:when test=".='CallOffSheet'"><xsl:value-of select="$CallOffProductConversionType-CallOffSheet"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffProductConversionType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CallOffReferenceType     *** -->

<xsl:template match="@CallOffReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$CallOffReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$CallOffReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$CallOffReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$CallOffReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$CallOffReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$CallOffReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$CallOffReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$CallOffReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$CallOffReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$CallOffReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$CallOffReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$CallOffReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$CallOffReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$CallOffReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$CallOffReferenceType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute CallOffStatusType     *** -->

<xsl:template match="@CallOffStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$CallOffStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$CallOffStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$CallOffStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$CallOffStatusType-Original"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$CallOffStatusType-Rejected"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CallOffType     *** -->

<xsl:template match="@CallOffType">
	<xsl:choose>
		<xsl:when test=".='CallOff'"><xsl:value-of select="$CallOffType-CallOff"/></xsl:when>
		<xsl:when test=".='CallOffConfirmation'"><xsl:value-of select="$CallOffType-CallOffConfirmation"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CallOffType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ChargeType     *** -->

<xsl:template match="@ChargeType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='AdministrativeCosts'"><xsl:value-of select="$ChargeType-AdministrativeCosts"/></xsl:when>
				<xsl:when test=".='AgreedWasteToScrap'"><xsl:value-of select="$ChargeType-AgreedWasteToScrap"/></xsl:when>
				<xsl:when test=".='AgreedWasteToSell'"><xsl:value-of select="$ChargeType-AgreedWasteToSell"/></xsl:when>
				<xsl:when test=".='ConversionCosts'"><xsl:value-of select="$ChargeType-ConversionCosts"/></xsl:when>
				<xsl:when test=".='KnownProductionDowntime'"><xsl:value-of select="$ChargeType-KnownProductionDowntime"/></xsl:when>
				<xsl:when test=".='LabourCosts'"><xsl:value-of select="$ChargeType-LabourCosts"/></xsl:when>
				<xsl:when test=".='MachineDamage'"><xsl:value-of select="$ChargeType-MachineDamage"/></xsl:when>
				<xsl:when test=".='PriceDifference'"><xsl:value-of select="$ChargeType-PriceDifference"/></xsl:when>
				<xsl:when test=".='ProductionDowntime'"><xsl:value-of select="$ChargeType-ProductionDowntime"/></xsl:when>
				<xsl:when test=".='RejectedReel'"><xsl:value-of select="$ChargeType-RejectedReel"/></xsl:when>
				<xsl:when test=".='UnknownProductionDowntime'"><xsl:value-of select="$ChargeType-UnknownProductionDowntime"/></xsl:when>
				<xsl:when test=".='WasteToScrap'"><xsl:value-of select="$ChargeType-WasteToScrap"/></xsl:when>
				<xsl:when test=".='WasteToSell'"><xsl:value-of select="$ChargeType-WasteToSell"/></xsl:when>
				<xsl:when test=".='WrongQuantity'"><xsl:value-of select="$ChargeType-WrongQuantity"/></xsl:when>
				<xsl:when test=".='Other'"><xsl:value-of select="$ChargeType-Other"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@ChargeType"/>-</b>
				</xsl:otherwise>
			</xsl:choose>			
			</td>
		</tr>
		<tr>
			<td></td>
			<td><xsl:apply-templates select="../@Agency"/></td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ChargeContext     *** -->

<xsl:template match="@ChargeContext">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='Administrative'"><xsl:value-of select="$ChargeContext-Administrative"/></xsl:when>
				<xsl:when test=".='Logistical'"><xsl:value-of select="$ChargeContext-Logistical"/></xsl:when>
				<xsl:when test=".='Production'"><xsl:value-of select="$ChargeContext-Production"/></xsl:when>
				<xsl:when test=".='Rework'"><xsl:value-of select="$ChargeContext-Rework"/></xsl:when>
				<xsl:when test=".='Storage'"><xsl:value-of select="$ChargeContext-Storage"/></xsl:when>
				<xsl:when test=".='Waste'"><xsl:value-of select="$ChargeContext-Waste"/></xsl:when>
				<xsl:when test=".='Other'"><xsl:value-of select="$ChargeContext-Other"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@ChargeContext"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ChargeOrAllowanceType     *** -->

<xsl:template match="@ChargeOrAllowanceType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='CancellationCharge'"><xsl:value-of select="$ChargeOrAllowanceType-CancellationCharge"/></xsl:when>
					<xsl:when test=".='CashDiscount'"><xsl:value-of select="$ChargeOrAllowanceType-CashDiscount"/></xsl:when>
					<xsl:when test=".='ChargesForward'"><xsl:value-of select="$ChargeOrAllowanceType-ChargesForward"/></xsl:when>
					<xsl:when test=".='ClaimAdjustment'"><xsl:value-of select="$ChargeOrAllowanceType-ClaimAdjustment"/></xsl:when>
					<xsl:when test=".='Commission'"><xsl:value-of select="$ChargeOrAllowanceType-Commission"/></xsl:when>
					<xsl:when test=".='ContractAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-ContractAllowance"/></xsl:when>
					<xsl:when test=".='DeliveryNonConformanceAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-DeliveryNonConformanceAllowance"/></xsl:when>
					<xsl:when test=".='EarlyShipAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-EarlyShipAllowance"/></xsl:when>
					<xsl:when test=".='Environmental'"><xsl:value-of select="$ChargeOrAllowanceType-Environmental"/></xsl:when>
					<xsl:when test=".='ExpeditedShipmentCharge'"><xsl:value-of select="$ChargeOrAllowanceType-ExpeditedShipmentCharge"/></xsl:when>
					<xsl:when test=".='FreightAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-FreightAllowance"/></xsl:when>
					<xsl:when test=".='FreightCharge'"><xsl:value-of select="$ChargeOrAllowanceType-FreightCharge"/></xsl:when>
					<xsl:when test=".='Inspection'"><xsl:value-of select="$ChargeOrAllowanceType-Inspection"/></xsl:when>
					<xsl:when test=".='InterestCharge'"><xsl:value-of select="$ChargeOrAllowanceType-InterestCharge"/></xsl:when>
					<xsl:when test=".='MetalDetection'"><xsl:value-of select="$ChargeOrAllowanceType-MetalDetection"/></xsl:when>
					<xsl:when test=".='OrderQuantity'"><xsl:value-of select="$ChargeOrAllowanceType-OrderQuantity"/></xsl:when>
					<xsl:when test=".='PriceCorrection'"><xsl:value-of select="$ChargeOrAllowanceType-PriceCorrection"/></xsl:when>
					<xsl:when test=".='ProductionSetUpCharge'"><xsl:value-of select="$ChargeOrAllowanceType-ProductionSetUpCharge"/></xsl:when>
					<xsl:when test=".='Provision'"><xsl:value-of select="$ChargeOrAllowanceType-Provision"/></xsl:when>
					<xsl:when test=".='Rebate'"><xsl:value-of select="$ChargeOrAllowanceType-Rebate"/></xsl:when>
					<xsl:when test=".='ReelDiscount'"><xsl:value-of select="$ChargeOrAllowanceType-ReelDiscount"/></xsl:when>
					<xsl:when test=".='ReturnedLoadAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-ReturnedLoadAllowance"/></xsl:when>
					<xsl:when test=".='ReturnLoadCharge'"><xsl:value-of select="$ChargeOrAllowanceType-ReturnLoadCharge"/></xsl:when>
					<xsl:when test=".='SpecialConversionCharge'"><xsl:value-of select="$ChargeOrAllowanceType-SpecialConversionCharge"/></xsl:when>
					<xsl:when test=".='SpecialDeliveryCharge'"><xsl:value-of select="$ChargeOrAllowanceType-SpecialDeliveryCharge"/></xsl:when>
					<xsl:when test=".='SpecialHandlingCharge'"><xsl:value-of select="$ChargeOrAllowanceType-SpecialHandlingCharge"/></xsl:when>
					<xsl:when test=".='SpecialPackagingCharge'"><xsl:value-of select="$ChargeOrAllowanceType-SpecialPackagingCharge"/></xsl:when>
					<!--<xsl:when test=".='StopOff'"><xsl:value-of select="$ChargeOrAllowanceType-StopOff"/></xsl:when>-->
					<xsl:when test=".='StopOffAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-StopOffAllowance"/></xsl:when>
					<xsl:when test=".='StopOffCharge'"><xsl:value-of select="$ChargeOrAllowanceType-StopOffCharge"/></xsl:when>
					<!--<xsl:when test=".='Storage'"><xsl:value-of select="$ChargeOrAllowanceType-Storage"/></xsl:when>-->
					<xsl:when test=".='StorageAllowance'"><xsl:value-of select="$ChargeOrAllowanceType-StorageAllowance"/></xsl:when>
					<xsl:when test=".='StorageCharge'"><xsl:value-of select="$ChargeOrAllowanceType-StorageCharge"/></xsl:when>
					<xsl:when test=".='Tax'"><xsl:value-of select="$ChargeOrAllowanceType-Tax"/></xsl:when>
					<xsl:when test=".='TestingCharge'"><xsl:value-of select="$ChargeOrAllowanceType-TestingCharge"/></xsl:when>
					<xsl:when test=".='TradeDiscount'"><xsl:value-of select="$ChargeOrAllowanceType-TradeDiscount"/></xsl:when>
					<xsl:when test=".='TrialDiscount'"><xsl:value-of select="$ChargeOrAllowanceType-TrialDiscount"/></xsl:when>
					<xsl:when test=".='TransferCharge'"><xsl:value-of select="$ChargeOrAllowanceType-TransferCharge"/></xsl:when>
					<xsl:when test=".='VolumeDiscount'"><xsl:value-of select="$ChargeOrAllowanceType-VolumeDiscount"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$ChargeOrAllowanceType-Other"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ChargeOrAllowanceType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute CoatingBottom     *** -->

<xsl:template match="@CoatingBottom">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Acrylic'"><xsl:value-of select="$CoatingBottom-Acrylic"/></xsl:when>
					<xsl:when test=".='Flexolyn'"><xsl:value-of select="$CoatingBottom-Flexolyn"/></xsl:when>
					<xsl:when test=".='FoilCoated'"><xsl:value-of select="$CoatingBottom-FoilCoated"/></xsl:when>
					<xsl:when test=".='GreaseBarrier'"><xsl:value-of select="$CoatingBottom-GreaseBarrier"/></xsl:when>
					<xsl:when test=".='Metallic'"><xsl:value-of select="$CoatingBottom-Metallic"/></xsl:when>
					<xsl:when test=".='MetalizedPolyester'"><xsl:value-of select="$CoatingBottom-MetalizedPolyester"/></xsl:when>
					<xsl:when test=".='MoistureBarrier'"><xsl:value-of select="$CoatingBottom-MoistureBarrier"/></xsl:when>
					<xsl:when test=".='MoldInhibitor'"><xsl:value-of select="$CoatingBottom-MoldInhibitor"/></xsl:when>
					<xsl:when test=".='None'"><xsl:value-of select="$CoatingBottom-None"/></xsl:when>
					<xsl:when test=".='OilBarrier'"><xsl:value-of select="$CoatingBottom-OilBarrier"/></xsl:when>
					<xsl:when test=".='OxygenBarrier'"><xsl:value-of select="$CoatingBottom-OxygenBarrier"/></xsl:when>
					<xsl:when test=".='Plastic'"><xsl:value-of select="$CoatingBottom-Plastic"/></xsl:when>
					<xsl:when test=".='PolyCoating'"><xsl:value-of select="$CoatingBottom-PolyCoating"/></xsl:when>
					<xsl:when test=".='Polyethylene'"><xsl:value-of select="$CoatingBottom-Polyethylene"/></xsl:when>
					<xsl:when test=".='Pyroxylin'"><xsl:value-of select="$CoatingBottom-Pyroxylin"/></xsl:when>
					<xsl:when test=".='Silicone'"><xsl:value-of select="$CoatingBottom-Silicone"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@CoatingBottom"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute  CoatingTop    *** -->

<xsl:template match="@CoatingTop">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Acrylic'"><xsl:value-of select="$CoatingTop-Acrylic"/></xsl:when>
					<xsl:when test=".='Flexolyn'"><xsl:value-of select="$CoatingTop-Flexolyn"/></xsl:when>
					<xsl:when test=".='FoilCoated'"><xsl:value-of select="$CoatingTop-FoilCoated"/></xsl:when>
					<xsl:when test=".='GreaseBarrier'"><xsl:value-of select="$CoatingTop-GreaseBarrier"/></xsl:when>
					<xsl:when test=".='Metallic'"><xsl:value-of select="$CoatingTop-Metallic"/></xsl:when>
					<xsl:when test=".='MetalizedPolyester'"><xsl:value-of select="$CoatingTop-MetalizedPolyester"/></xsl:when>
					<xsl:when test=".='MoistureBarrier'"><xsl:value-of select="$CoatingTop-MoistureBarrier"/></xsl:when>
					<xsl:when test=".='MoldInhibitor'"><xsl:value-of select="$CoatingTop-MoldInhibitor"/></xsl:when>
					<xsl:when test=".='None'"><xsl:value-of select="$CoatingTop-None"/></xsl:when>
					<xsl:when test=".='OilBarrier'"><xsl:value-of select="$CoatingTop-OilBarrier"/></xsl:when>
					<xsl:when test=".='OxygenBarrier'"><xsl:value-of select="$CoatingTop-OxygenBarrier"/></xsl:when>
					<xsl:when test=".='Plastic'"><xsl:value-of select="$CoatingTop-Plastic"/></xsl:when>
					<xsl:when test=".='PolyCoating'"><xsl:value-of select="$CoatingTop-PolyCoating"/></xsl:when>
					<xsl:when test=".='Polyethylene'"><xsl:value-of select="$CoatingTop-Polyethylene"/></xsl:when>
					<xsl:when test=".='Pyroxylin'"><xsl:value-of select="$CoatingTop-Pyroxylin"/></xsl:when>
					<xsl:when test=".='Silicone'"><xsl:value-of select="$CoatingTop-Silicone"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@CoatingTop"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute ContactType     *** -->

<xsl:template match="@ContactType">
	<xsl:choose>
		<xsl:when test=".='AccountManager'"><xsl:value-of select="$ContactType-AccountManager"/></xsl:when>
		<xsl:when test=".='Carrier'"><xsl:value-of select="$ContactType-Carrier"/></xsl:when>
		<xsl:when test=".='CrossDock'"><xsl:value-of select="$ContactType-CrossDock"/></xsl:when>
		<xsl:when test=".='CustomerService'"><xsl:value-of select="$ContactType-CustomerService"/></xsl:when>
		<xsl:when test=".='HelpDesk'"><xsl:value-of select="$ContactType-HelpDesk"/></xsl:when>
		<xsl:when test=".='Merchant'"><xsl:value-of select="$ContactType-Merchant"/></xsl:when>
		<xsl:when test=".='Mill'"><xsl:value-of select="$ContactType-Mill"/></xsl:when>
		<xsl:when test=".='Plant'"><xsl:value-of select="$ContactType-Plant"/></xsl:when>
		<xsl:when test=".='Purchaser'"><xsl:value-of select="$ContactType-Purchaser"/></xsl:when>
		<xsl:when test=".='RemitTo'"><xsl:value-of select="$ContactType-RemitTo"/></xsl:when>
		<xsl:when test=".='SalesOffice'"><xsl:value-of select="$ContactType-SalesOffice"/></xsl:when>
		<xsl:when test=".='Warehouse'"><xsl:value-of select="$ContactType-Warehouse"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$ContactType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ContactType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ComplaintType     *** -->

<xsl:template match="@ComplaintType">
	<xsl:choose>
		<xsl:when test=".='Claim'"><xsl:value-of select="$ComplaintType-Claim"/></xsl:when>
		<xsl:when test=".='Feedback'"><xsl:value-of select="$ComplaintType-Feedback"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ComplaintType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ComplaintReasonType     *** -->

<xsl:template match="@ComplaintReasonType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Commercial'"><xsl:value-of select="$ComplaintReasonType-Commercial"/></xsl:when>
					<xsl:when test=".='Logistical'"><xsl:value-of select="$ComplaintReasonType-Logistical"/></xsl:when>
					<xsl:when test=".='Technical'"><xsl:value-of select="$ComplaintReasonType-Technical"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ComplaintType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ComplaintResponseReasonType     *** -->

<xsl:template match="@ComplaintResponseReasonType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Commercial'"><xsl:value-of select="$ComplaintResponseReasonType-Commercial"/></xsl:when>
					<xsl:when test=".='Logistical'"><xsl:value-of select="$ComplaintResponseReasonType-Logistical"/></xsl:when>
					<xsl:when test=".='Technical'"><xsl:value-of select="$ComplaintResponseReasonType-Technical"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ComplaintResponseType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ComplaintReferenceType     *** -->

<xsl:template match="@ComplaintReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>		
		<xsl:when test=".='BillOfLadingNumber'"><xsl:value-of select="$ComplaintReferenceType-BillOfLadingNumber"/></xsl:when>
		<xsl:when test=".='CallOffNumber'"><xsl:value-of select="$ComplaintReferenceType-CallOffNumber"/></xsl:when>
		<xsl:when test=".='CIMNumber'"><xsl:value-of select="$ComplaintReferenceType-CIMNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$ComplaintReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$ComplaintReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$ComplaintReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$ComplaintReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$ComplaintReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='DespatchInstructionNumber'"><xsl:value-of select="$ComplaintReferenceType-DespatchInstructionNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$ComplaintReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='InitialShipmentAdviceNumber'"><xsl:value-of select="$ComplaintReferenceType-InitialShipmentAdviceNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$ComplaintReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$ComplaintReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$ComplaintReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MasterBillOfLading'"><xsl:value-of select="$ComplaintReferenceType-MasterBillOfLading"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$ComplaintReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$ComplaintReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalDeliveryNumber'"><xsl:value-of select="$ComplaintReferenceType-OriginalDeliveryNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$ComplaintReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$ComplaintReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='RunNumber'"><xsl:value-of select="$ComplaintReferenceType-RunNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$ComplaintReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$ComplaintReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$ComplaintReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$ComplaintReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='WarehouseDeliveryNumber'"><xsl:value-of select="$ComplaintReferenceType-WarehouseDeliveryNumber"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ComplaintReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute ComplaintResponseReferenceType     *** -->

<xsl:template match="@ComplaintResponseReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>		
		<xsl:when test=".='BillOfLadingNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-BillOfLadingNumber"/></xsl:when>
		<xsl:when test=".='CallOffNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-CallOffNumber"/></xsl:when>
		<xsl:when test=".='CIMNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-CIMNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='DespatchInstructionNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-DespatchInstructionNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='InitialShipmentAdviceNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-InitialShipmentAdviceNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$ComplaintResponseReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$ComplaintResponseReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MasterBillOfLading'"><xsl:value-of select="$ComplaintResponseReferenceType-MasterBillOfLading"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalDeliveryNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-OriginalDeliveryNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='RunNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-RunNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='WarehouseDeliveryNumber'"><xsl:value-of select="$ComplaintResponseReferenceType-WarehouseDeliveryNumber"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ComplaintResponseReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute CommunicationRole     *** -->

<xsl:template match="@CommunicationRole">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='From'"><xsl:value-of select="$CommunicationRole-From"/></xsl:when>
		<xsl:when test=".='To'"><xsl:value-of select="$CommunicationRole-To"/></xsl:when>
		<xsl:when test=".='CC'"><xsl:value-of select="$CommunicationRole-CC"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CommunicationRole"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute CoreEndType     *** -->

<xsl:template match="@CoreEndType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Bevelled'"><xsl:value-of select="$CoreEndType-Bevelled"/></xsl:when>
					<xsl:when test=".='Bridge'"><xsl:value-of select="$CoreEndType-Bridge"/></xsl:when>
					<xsl:when test=".='Notched'"><xsl:value-of select="$CoreEndType-Notched"/></xsl:when>
					<xsl:when test=".='NotchedFullMetal'"><xsl:value-of select="$CoreEndType-NotchedFullMetal"/></xsl:when>
					<xsl:when test=".='Plain'"><xsl:value-of select="$CoreEndType-Plain"/></xsl:when>
					<xsl:when test=".='PlainFullMetal'"><xsl:value-of select="$CoreEndType-PlainFullMetal"/></xsl:when>
					<xsl:when test=".='Tapered'"><xsl:value-of select="$CoreEndType-Tapered"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@CoreEndType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute CoreMaterialType     *** -->

<xsl:template match="@CoreMaterialType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Aluminium'"><xsl:value-of select="$CoreMaterialType-Aluminium"/></xsl:when>
					<xsl:when test=".='Fibre'"><xsl:value-of select="$CoreMaterialType-Fibre"/></xsl:when>
					<xsl:when test=".='Iron'"><xsl:value-of select="$CoreMaterialType-Iron"/></xsl:when>
					<xsl:when test=".='Paper'"><xsl:value-of select="$CoreMaterialType-Paper"/></xsl:when>
					<xsl:when test=".='Plastic'"><xsl:value-of select="$CoreMaterialType-Plastic"/></xsl:when>
					<xsl:when test=".='Steel'"><xsl:value-of select="$CoreMaterialType-Steel"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@CoreMaterialType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute CryptoAlgorithm     *** -->

<xsl:template match="@CryptoAlgorithm">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='des'"><xsl:value-of select="$CryptoAlgorithm-des"/></xsl:when>
				<xsl:when test=".='idea'"><xsl:value-of select="$CryptoAlgorithm-idea"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@CryptoAlgorithm"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute CurrencySign     *** -->

<xsl:template match="@CurrencySign">
	<xsl:choose>
		<xsl:when test=".='Plus'"><xsl:value-of select="$CurrencySign-Plus"/></xsl:when>
		<xsl:when test=".='Minus'"><xsl:value-of select="$CurrencySign-Minus"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CurrencySign"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CreditDebitNoteReferenceType     *** -->

<xsl:template match="@CreditDebitNoteReferenceType">
	<xsl:choose>
		<xsl:when test=".='AccountNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-AccountNumber"/></xsl:when>
		<xsl:when test=".='BuyerClaimNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-BuyerClaimNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ComplaintNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-ComplaintNumber"/></xsl:when>
		<xsl:when test=".='ComplaintResponseNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-ComplaintResponseNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='DespatchInstructionNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-DespatchInstructionNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$CreditDebitNoteReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$CreditDebitNoteReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='SupplierClaimNumber'"><xsl:value-of select="$CreditDebitNoteReferenceType-SupplierClaimNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$CreditDebitNoteReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CreditDebitNoteReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute CurrencyType     *** -->

<xsl:template match="@CurrencyType">
	<xsl:choose>
		<xsl:when test=".='ADP'"><xsl:value-of select="$CurrencyType-ADP"/></xsl:when>
		<xsl:when test=".='AED'"><xsl:value-of select="$CurrencyType-AED"/></xsl:when>
		<xsl:when test=".='AFA'"><xsl:value-of select="$CurrencyType-AFA"/></xsl:when>
		<xsl:when test=".='ALL'"><xsl:value-of select="$CurrencyType-ALL"/></xsl:when>
		<xsl:when test=".='ANG'"><xsl:value-of select="$CurrencyType-ANG"/></xsl:when>
		<xsl:when test=".='AOK'"><xsl:value-of select="$CurrencyType-AOK"/></xsl:when>
		<xsl:when test=".='ARA'"><xsl:value-of select="$CurrencyType-ARA"/></xsl:when>
		<xsl:when test=".='ATS'"><xsl:value-of select="$CurrencyType-ATS"/></xsl:when>
		<xsl:when test=".='AUD'"><xsl:value-of select="$CurrencyType-AUD"/></xsl:when>
		<xsl:when test=".='AWG'"><xsl:value-of select="$CurrencyType-AWG"/></xsl:when>
		<xsl:when test=".='BBD'"><xsl:value-of select="$CurrencyType-BBD"/></xsl:when>
		<xsl:when test=".='BDT'"><xsl:value-of select="$CurrencyType-BDT"/></xsl:when>
		<xsl:when test=".='BEF'"><xsl:value-of select="$CurrencyType-BEF"/></xsl:when>
		<xsl:when test=".='BGL'"><xsl:value-of select="$CurrencyType-BGL"/></xsl:when>
		<xsl:when test=".='BHD'"><xsl:value-of select="$CurrencyType-BHD"/></xsl:when>
		<xsl:when test=".='BIF'"><xsl:value-of select="$CurrencyType-BIF"/></xsl:when>
		<xsl:when test=".='BMD'"><xsl:value-of select="$CurrencyType-BMD"/></xsl:when>
		<xsl:when test=".='BND'"><xsl:value-of select="$CurrencyType-BND"/></xsl:when>
		<xsl:when test=".='BOB'"><xsl:value-of select="$CurrencyType-BOB"/></xsl:when>
		<xsl:when test=".='BRC'"><xsl:value-of select="$CurrencyType-BRC"/></xsl:when>
		<xsl:when test=".='BSD'"><xsl:value-of select="$CurrencyType-BSD"/></xsl:when>
		<xsl:when test=".='BTN'"><xsl:value-of select="$CurrencyType-BTN"/></xsl:when>
		<xsl:when test=".='BUK'"><xsl:value-of select="$CurrencyType-BUK"/></xsl:when>
		<xsl:when test=".='BWP'"><xsl:value-of select="$CurrencyType-BWP"/></xsl:when>
		<xsl:when test=".='BZD'"><xsl:value-of select="$CurrencyType-BZD"/></xsl:when>
		<xsl:when test=".='CAD'"><xsl:value-of select="$CurrencyType-CAD"/></xsl:when>
		<xsl:when test=".='CHF'"><xsl:value-of select="$CurrencyType-CHF"/></xsl:when>
		<xsl:when test=".='CLF'"><xsl:value-of select="$CurrencyType-CLF"/></xsl:when>
		<xsl:when test=".='CLP'"><xsl:value-of select="$CurrencyType-CLP"/></xsl:when>
		<xsl:when test=".='CNY'"><xsl:value-of select="$CurrencyType-CNY"/></xsl:when>
		<xsl:when test=".='COP'"><xsl:value-of select="$CurrencyType-COP"/></xsl:when>
		<xsl:when test=".='CRC'"><xsl:value-of select="$CurrencyType-CRC"/></xsl:when>
		<xsl:when test=".='CSK'"><xsl:value-of select="$CurrencyType-CSK"/></xsl:when>
		<xsl:when test=".='CUP'"><xsl:value-of select="$CurrencyType-CUP"/></xsl:when>
		<xsl:when test=".='CVE'"><xsl:value-of select="$CurrencyType-CVE"/></xsl:when>
		<xsl:when test=".='CYP'"><xsl:value-of select="$CurrencyType-CYP"/></xsl:when>
		<xsl:when test=".='DDM'"><xsl:value-of select="$CurrencyType-DDM"/></xsl:when>
		<xsl:when test=".='DEM'"><xsl:value-of select="$CurrencyType-DEM"/></xsl:when>
		<xsl:when test=".='DJF'"><xsl:value-of select="$CurrencyType-DJF"/></xsl:when>
		<xsl:when test=".='DKK'"><xsl:value-of select="$CurrencyType-DKK"/></xsl:when>
		<xsl:when test=".='DOP'"><xsl:value-of select="$CurrencyType-DOP"/></xsl:when>
		<xsl:when test=".='DZD'"><xsl:value-of select="$CurrencyType-DZD"/></xsl:when>
		<xsl:when test=".='ECS'"><xsl:value-of select="$CurrencyType-ECS"/></xsl:when>
		<xsl:when test=".='EGP'"><xsl:value-of select="$CurrencyType-EGP"/></xsl:when>
		<xsl:when test=".='ESP'"><xsl:value-of select="$CurrencyType-ESP"/></xsl:when>
		<xsl:when test=".='ETB'"><xsl:value-of select="$CurrencyType-ETB"/></xsl:when>
		<xsl:when test=".='EUR'"><xsl:value-of select="$CurrencyType-EUR"/></xsl:when>
		<xsl:when test=".='FIM'"><xsl:value-of select="$CurrencyType-FIM"/></xsl:when>
		<xsl:when test=".='FJD'"><xsl:value-of select="$CurrencyType-FJD"/></xsl:when>
		<xsl:when test=".='FKP'"><xsl:value-of select="$CurrencyType-FKP"/></xsl:when>
		<xsl:when test=".='FRF'"><xsl:value-of select="$CurrencyType-FRF"/></xsl:when>
		<xsl:when test=".='GBP'"><xsl:value-of select="$CurrencyType-GBP"/></xsl:when>
		<xsl:when test=".='GHC'"><xsl:value-of select="$CurrencyType-GHC"/></xsl:when>
		<xsl:when test=".='GIP'"><xsl:value-of select="$CurrencyType-GIP"/></xsl:when>
		<xsl:when test=".='GMD'"><xsl:value-of select="$CurrencyType-GMD"/></xsl:when>
		<xsl:when test=".='GNF'"><xsl:value-of select="$CurrencyType-GNF"/></xsl:when>
		<xsl:when test=".='GRD'"><xsl:value-of select="$CurrencyType-GRD"/></xsl:when>
		<xsl:when test=".='GTQ'"><xsl:value-of select="$CurrencyType-GTQ"/></xsl:when>
		<xsl:when test=".='GWP'"><xsl:value-of select="$CurrencyType-GWP"/></xsl:when>
		<xsl:when test=".='GYD'"><xsl:value-of select="$CurrencyType-GYD"/></xsl:when>
		<xsl:when test=".='HKD'"><xsl:value-of select="$CurrencyType-HKD"/></xsl:when>
		<xsl:when test=".='HNL'"><xsl:value-of select="$CurrencyType-HNL"/></xsl:when>
		<xsl:when test=".='HTG'"><xsl:value-of select="$CurrencyType-HTG"/></xsl:when>
		<xsl:when test=".='HUF'"><xsl:value-of select="$CurrencyType-HUF"/></xsl:when>
		<xsl:when test=".='IDR'"><xsl:value-of select="$CurrencyType-IDR"/></xsl:when>
		<xsl:when test=".='IEP'"><xsl:value-of select="$CurrencyType-IEP"/></xsl:when>
		<xsl:when test=".='ILS'"><xsl:value-of select="$CurrencyType-ILS"/></xsl:when>
		<xsl:when test=".='INR'"><xsl:value-of select="$CurrencyType-INR"/></xsl:when>
		<xsl:when test=".='IQD'"><xsl:value-of select="$CurrencyType-IQD"/></xsl:when>
		<xsl:when test=".='IRR'"><xsl:value-of select="$CurrencyType-IRR"/></xsl:when>
		<xsl:when test=".='ISK'"><xsl:value-of select="$CurrencyType-ISK"/></xsl:when>
		<xsl:when test=".='ITL'"><xsl:value-of select="$CurrencyType-ITL"/></xsl:when>
		<xsl:when test=".='JMD'"><xsl:value-of select="$CurrencyType-JMD"/></xsl:when>
		<xsl:when test=".='JOD'"><xsl:value-of select="$CurrencyType-JOD"/></xsl:when>
		<xsl:when test=".='JPY'"><xsl:value-of select="$CurrencyType-JPY"/></xsl:when>
		<xsl:when test=".='KES'"><xsl:value-of select="$CurrencyType-KES"/></xsl:when>
		<xsl:when test=".='KHR'"><xsl:value-of select="$CurrencyType-KHR"/></xsl:when>
		<xsl:when test=".='KMF'"><xsl:value-of select="$CurrencyType-KMF"/></xsl:when>
		<xsl:when test=".='KPW'"><xsl:value-of select="$CurrencyType-KPW"/></xsl:when>
		<xsl:when test=".='KRW'"><xsl:value-of select="$CurrencyType-KRW"/></xsl:when>
		<xsl:when test=".='KWD'"><xsl:value-of select="$CurrencyType-KWD"/></xsl:when>
		<xsl:when test=".='KYD'"><xsl:value-of select="$CurrencyType-KYD"/></xsl:when>
		<xsl:when test=".='LAK'"><xsl:value-of select="$CurrencyType-LAK"/></xsl:when>
		<xsl:when test=".='LBP'"><xsl:value-of select="$CurrencyType-LBP"/></xsl:when>
		<xsl:when test=".='LKR'"><xsl:value-of select="$CurrencyType-LKR"/></xsl:when>
		<xsl:when test=".='LRD'"><xsl:value-of select="$CurrencyType-LRD"/></xsl:when>
		<xsl:when test=".='LSL'"><xsl:value-of select="$CurrencyType-LSL"/></xsl:when>
		<xsl:when test=".='LUF'"><xsl:value-of select="$CurrencyType-LUF"/></xsl:when>
		<xsl:when test=".='LYD'"><xsl:value-of select="$CurrencyType-LYD"/></xsl:when>
		<xsl:when test=".='MAD'"><xsl:value-of select="$CurrencyType-MAD"/></xsl:when>
		<xsl:when test=".='MGF'"><xsl:value-of select="$CurrencyType-MGF"/></xsl:when>
		<xsl:when test=".='MNT'"><xsl:value-of select="$CurrencyType-MNT"/></xsl:when>
		<xsl:when test=".='MOP'"><xsl:value-of select="$CurrencyType-MOP"/></xsl:when>
		<xsl:when test=".='MRO'"><xsl:value-of select="$CurrencyType-MRO"/></xsl:when>
		<xsl:when test=".='MTL'"><xsl:value-of select="$CurrencyType-MTL"/></xsl:when>
		<xsl:when test=".='MUR'"><xsl:value-of select="$CurrencyType-MUR"/></xsl:when>
		<xsl:when test=".='MVR'"><xsl:value-of select="$CurrencyType-MVR"/></xsl:when>
		<xsl:when test=".='MWK'"><xsl:value-of select="$CurrencyType-MWK"/></xsl:when>
		<xsl:when test=".='MXP'"><xsl:value-of select="$CurrencyType-MXP"/></xsl:when>
		<xsl:when test=".='MYR'"><xsl:value-of select="$CurrencyType-MYR"/></xsl:when>
		<xsl:when test=".='MZM'"><xsl:value-of select="$CurrencyType-MZM"/></xsl:when>
		<xsl:when test=".='NGN'"><xsl:value-of select="$CurrencyType-NGN"/></xsl:when>
		<xsl:when test=".='NIC'"><xsl:value-of select="$CurrencyType-NIC"/></xsl:when>
		<xsl:when test=".='NLG'"><xsl:value-of select="$CurrencyType-NLG"/></xsl:when>
		<xsl:when test=".='NOK'"><xsl:value-of select="$CurrencyType-NOK"/></xsl:when>
		<xsl:when test=".='NPR'"><xsl:value-of select="$CurrencyType-NPR"/></xsl:when>
		<xsl:when test=".='NZD'"><xsl:value-of select="$CurrencyType-NZD"/></xsl:when>
		<xsl:when test=".='OMR'"><xsl:value-of select="$CurrencyType-OMR"/></xsl:when>
		<xsl:when test=".='PAB'"><xsl:value-of select="$CurrencyType-PAB"/></xsl:when>
		<xsl:when test=".='PEI'"><xsl:value-of select="$CurrencyType-PEI"/></xsl:when>
		<xsl:when test=".='PGK'"><xsl:value-of select="$CurrencyType-PGK"/></xsl:when>
		<xsl:when test=".='PHP'"><xsl:value-of select="$CurrencyType-PHP"/></xsl:when>
		<xsl:when test=".='PKR'"><xsl:value-of select="$CurrencyType-PKR"/></xsl:when>
		<xsl:when test=".='PLZ'"><xsl:value-of select="$CurrencyType-PLZ"/></xsl:when>
		<xsl:when test=".='PTE'"><xsl:value-of select="$CurrencyType-PTE"/></xsl:when>
		<xsl:when test=".='PYG'"><xsl:value-of select="$CurrencyType-PYG"/></xsl:when>
		<xsl:when test=".='QAR'"><xsl:value-of select="$CurrencyType-QAR"/></xsl:when>
		<xsl:when test=".='ROL'"><xsl:value-of select="$CurrencyType-ROL"/></xsl:when>
		<xsl:when test=".='RWF'"><xsl:value-of select="$CurrencyType-RWF"/></xsl:when>
		<xsl:when test=".='SAR'"><xsl:value-of select="$CurrencyType-SAR"/></xsl:when>
		<xsl:when test=".='SBD'"><xsl:value-of select="$CurrencyType-SBD"/></xsl:when>
		<xsl:when test=".='SCR'"><xsl:value-of select="$CurrencyType-SCR"/></xsl:when>
		<xsl:when test=".='SDP'"><xsl:value-of select="$CurrencyType-SDP"/></xsl:when>
		<xsl:when test=".='SEK'"><xsl:value-of select="$CurrencyType-SEK"/></xsl:when>
		<xsl:when test=".='SGD'"><xsl:value-of select="$CurrencyType-SGD"/></xsl:when>
		<xsl:when test=".='SHP'"><xsl:value-of select="$CurrencyType-SHP"/></xsl:when>
		<xsl:when test=".='SLL'"><xsl:value-of select="$CurrencyType-SLL"/></xsl:when>
		<xsl:when test=".='SOS'"><xsl:value-of select="$CurrencyType-SOS"/></xsl:when>
		<xsl:when test=".='SRG'"><xsl:value-of select="$CurrencyType-SRG"/></xsl:when>
		<xsl:when test=".='STD'"><xsl:value-of select="$CurrencyType-STD"/></xsl:when>
		<xsl:when test=".='SUR'"><xsl:value-of select="$CurrencyType-SUR"/></xsl:when>
		<xsl:when test=".='SVC'"><xsl:value-of select="$CurrencyType-SVC"/></xsl:when>
		<xsl:when test=".='SYP'"><xsl:value-of select="$CurrencyType-SYP"/></xsl:when>
		<xsl:when test=".='SZL'"><xsl:value-of select="$CurrencyType-SZL"/></xsl:when>
		<xsl:when test=".='THB'"><xsl:value-of select="$CurrencyType-THB"/></xsl:when>
		<xsl:when test=".='TND'"><xsl:value-of select="$CurrencyType-TND"/></xsl:when>
		<xsl:when test=".='TOP'"><xsl:value-of select="$CurrencyType-TOP"/></xsl:when>
		<xsl:when test=".='TPE'"><xsl:value-of select="$CurrencyType-TPE"/></xsl:when>
		<xsl:when test=".='TRL'"><xsl:value-of select="$CurrencyType-TRL"/></xsl:when>
		<xsl:when test=".='TTD'"><xsl:value-of select="$CurrencyType-TTD"/></xsl:when>
		<xsl:when test=".='TWD'"><xsl:value-of select="$CurrencyType-TWD"/></xsl:when>
		<xsl:when test=".='TZS'"><xsl:value-of select="$CurrencyType-TZS"/></xsl:when>
		<xsl:when test=".='UGS'"><xsl:value-of select="$CurrencyType-UGS"/></xsl:when>
		<xsl:when test=".='USD'"><xsl:value-of select="$CurrencyType-USD"/></xsl:when>
		<xsl:when test=".='UYP'"><xsl:value-of select="$CurrencyType-UYP"/></xsl:when>
		<xsl:when test=".='VEB'"><xsl:value-of select="$CurrencyType-VEB"/></xsl:when>
		<xsl:when test=".='VND'"><xsl:value-of select="$CurrencyType-VND"/></xsl:when>
		<xsl:when test=".='VUV'"><xsl:value-of select="$CurrencyType-VUV"/></xsl:when>
		<xsl:when test=".='WST'"><xsl:value-of select="$CurrencyType-WST"/></xsl:when>
		<xsl:when test=".='YDD'"><xsl:value-of select="$CurrencyType-YDD"/></xsl:when>
		<xsl:when test=".='YER'"><xsl:value-of select="$CurrencyType-YER"/></xsl:when>
		<xsl:when test=".='YUD'"><xsl:value-of select="$CurrencyType-YUD"/></xsl:when>
		<xsl:when test=".='ZAR'"><xsl:value-of select="$CurrencyType-ZAR"/></xsl:when>
		<xsl:when test=".='ZMK'"><xsl:value-of select="$CurrencyType-ZMK"/></xsl:when>
		<xsl:when test=".='ZRZ'"><xsl:value-of select="$CurrencyType-ZRZ"/></xsl:when>
		<xsl:when test=".='ZWD'"><xsl:value-of select="$CurrencyType-ZWD"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@CurrencyType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute DateType     *** -->

<xsl:template match="@DateType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='AvailableToShipDate'"><xsl:value-of select="$DateType-AvailableToShipDate"/></xsl:when>
					<xsl:when test=".='BoundBookDate'"><xsl:value-of select="$DateType-BoundBookDate"/></xsl:when>
					<xsl:when test=".='CancelAfterDate'"><xsl:value-of select="$DateType-CancelAfterDate"/></xsl:when>
					<xsl:when test=".='ComponentDueDate'"><xsl:value-of select="$DateType-ComponentDueDate"/></xsl:when>
					<xsl:when test=".='ComponentShipDate'"><xsl:value-of select="$DateType-ComponentShipDate"/></xsl:when>
					<xsl:when test=".='DateOfLastChange'"><xsl:value-of select="$DateType-DateOfLastChange"/></xsl:when>
					<xsl:when test=".='DeliveryDate'"><xsl:value-of select="$DateType-DeliveryDate"/></xsl:when>
					<xsl:when test=".='DeliveryPriorToDate'"><xsl:value-of select="$DateType-DeliveryPriorToDate"/></xsl:when>
					<xsl:when test=".='DeliveryRequestedDate'"><xsl:value-of select="$DateType-DeliveryRequestedDate"/></xsl:when>
					<xsl:when test=".='DespatchDate'"><xsl:value-of select="$DateType-DespatchDate"/></xsl:when>
					<xsl:when test=".='DoNotDeliverAfterDate'"><xsl:value-of select="$DateType-DoNotDeliverAfterDate"/></xsl:when>
					<xsl:when test=".='DoNotShipAfterDate'"><xsl:value-of select="$DateType-DoNotShipAfterDate"/></xsl:when>
					<xsl:when test=".='EndCallOffDate'"><xsl:value-of select="$DateType-EndCallOffDate"/></xsl:when>
					<xsl:when test=".='EndOfDeliveryMonth'"><xsl:value-of select="$DateType-EndOfDeliveryMonth"/></xsl:when>
					<xsl:when test=".='EndOfDespatchMonth'"><xsl:value-of select="$DateType-EndOfDespatchMonth"/></xsl:when>
					<xsl:when test=".='EndOfInvoiceMonth'"><xsl:value-of select="$DateType-EndOfInvoiceMonth"/></xsl:when>
					<xsl:when test=".='EstimatedTimeOfArrival'"><xsl:value-of select="$DateType-EstimatedTimeOfArrival"/></xsl:when>
					<xsl:when test=".='EstimatedTimeOfDeparture'"><xsl:value-of select="$DateType-EstimatedTimeOfDeparture"/></xsl:when>
					<xsl:when test=".='ExMillDate'"><xsl:value-of select="$DateType-ExMillDate"/></xsl:when>
					<xsl:when test=".='InvoiceDate'"><xsl:value-of select="$DateType-InvoiceDate"/></xsl:when>
					<xsl:when test=".='LastChangeDate'"><xsl:value-of select="$DateType-LastChangeDate"/></xsl:when>
					<xsl:when test=".='OnPressDate'"><xsl:value-of select="$DateType-OnPressDate"/></xsl:when>
					<xsl:when test=".='OnSalesDate'"><xsl:value-of select="$DateType-OnSalesDate"/></xsl:when>
					<xsl:when test=".='OrderConfirmationDate'"><xsl:value-of select="$DateType-OrderConfirmationDate"/></xsl:when>
					<xsl:when test=".='OrderFirmedDate'"><xsl:value-of select="$DateType-OrderFirmedDate"/></xsl:when>
					<xsl:when test=".='PlannedShipDate'"><xsl:value-of select="$DateType-PlannedShipDate"/></xsl:when>
					<xsl:when test=".='PrintDate'"><xsl:value-of select="$DateType-PrintDate"/></xsl:when>
					<xsl:when test=".='ProductionDate'"><xsl:value-of select="$DateType-ProductionDate"/></xsl:when>
					<xsl:when test=".='PublicationDate'"><xsl:value-of select="$DateType-PublicationDate"/></xsl:when>
					<xsl:when test=".='ReferencePeriod'"><xsl:value-of select="$DateType-ReferencePeriod"/></xsl:when>
					<xsl:when test=".='RequiredByDate'"><xsl:value-of select="$DateType-RequiredByDate"/></xsl:when>
					<xsl:when test=".='ShipEvenlyThroughout'"><xsl:value-of select="$DateType-ShipEvenlyThroughout"/></xsl:when>
					<xsl:when test=".='ShipmentPriorToDate'"><xsl:value-of select="$DateType-ShipmentPriorToDate"/></xsl:when>
					<xsl:when test=".='ShipmentRequestedDate'"><xsl:value-of select="$DateType-ShipmentRequestedDate"/></xsl:when>
					<xsl:when test=".='SpecificationVersionDate'"><xsl:value-of select="$DateType-SpecificationVersionDate"/></xsl:when>
					<xsl:when test=".='StartCallOffDate'"><xsl:value-of select="$DateType-StartCallOffDate"/></xsl:when>
					<xsl:when test=".='TheWeekBeginning'"><xsl:value-of select="$DateType-TheWeekBeginning"/></xsl:when>
					<xsl:when test=".='TheWeekEnding'"><xsl:value-of select="$DateType-TheWeekEnding"/></xsl:when>
					<xsl:when test=".='WarehouseDate'"><xsl:value-of select="$DateType-WarehouseDate"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$DateType-Other"/></xsl:when>					
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@DateType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute DeliveryDateType     *** -->

<xsl:template match="@DeliveryDateType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='CancelAfterDate'"><xsl:value-of select="$DeliveryDateType-CancelAfterDate"/></xsl:when>
					<xsl:when test=".='DateOfLastChange'"><xsl:value-of select="$DeliveryDateType-DateOfLastChange"/></xsl:when>
					<xsl:when test=".='DeliveryRequestedDate'"><xsl:value-of select="$DeliveryDateType-DeliveryRequestedDate"/></xsl:when>
					<xsl:when test=".='DoNotDeliverAfterDate'"><xsl:value-of select="$DeliveryDateType-DoNotDeliverAfterDate"/></xsl:when>
					<xsl:when test=".='DoNotShipAfterDate'"><xsl:value-of select="$DeliveryDateType-DoNotShipAfterDate"/></xsl:when>
					<xsl:when test=".='EndCallOffDate'"><xsl:value-of select="$DeliveryDateType-EndCallOffDate"/></xsl:when>
					<xsl:when test=".='LastChangeDate'"><xsl:value-of select="$DeliveryDateType-LastChangeDate"/></xsl:when>
					<xsl:when test=".='PlannedShipDate'"><xsl:value-of select="$DeliveryDateType-PlannedShipDate"/></xsl:when>
					<xsl:when test=".='ReferencePeriod'"><xsl:value-of select="$DeliveryDateType-ReferencePeriod"/></xsl:when>
					<xsl:when test=".='ShipmentPriorToDate'"><xsl:value-of select="$DeliveryDateType-ShipmentPriorToDate"/></xsl:when>
					<xsl:when test=".='ShipmentRequestedDate'"><xsl:value-of select="$DeliveryDateType-ShipmentRequestedDate"/></xsl:when>
					<xsl:when test=".='StartCallOffDate'"><xsl:value-of select="$DeliveryDateType-StartCallOffDate"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@DeliveryDateType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute DeliveryMessageReferenceType     *** -->

<xsl:template match="@DeliveryMessageReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='BillOfLadingNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-BillOfLadingNumber"/></xsl:when>
		<xsl:when test=".='CallOffLineItemNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-CallOffLineItemNumber"/></xsl:when>
		<xsl:when test=".='CallOffNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-CallOffNumber"/></xsl:when>
		<xsl:when test=".='CIMNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-CIMNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='DeliveryLocation'"><xsl:value-of select="$DeliveryMessageReferenceType-DeliveryLocation"/></xsl:when>
		<xsl:when test=".='DespatchInstructionNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-DespatchInstructionNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='InitialShipmentAdviceNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-InitialShipmentAdviceNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$DeliveryMessageReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$DeliveryMessageReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MasterBillOfLading'"><xsl:value-of select="$DeliveryMessageReferenceType-MasterBillOfLading"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalDeliveryNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-OriginalDeliveryNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='RunNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-RunNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='WarehouseDeliveryNumber'"><xsl:value-of select="$DeliveryMessageReferenceType-WarehouseDeliveryNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$DeliveryMessageReferenceType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@DeliveryMessageReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute DeliveryMessageStatusType     *** -->

<xsl:template match="@DeliveryMessageStatusType">
	<xsl:choose>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$DeliveryMessageStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$DeliveryMessageStatusType-Original"/></xsl:when>
		<xsl:when test=".='Replaced'"><xsl:value-of select="$DeliveryMessageStatusType-Replaced"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@DeliveryMessageStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute DeliveryMessageType     *** -->

<xsl:template match="@DeliveryMessageType">
	<xsl:choose>
		<xsl:when test=".='DeliveryMessage'"><xsl:value-of select="$DeliveryMessageType-DeliveryMessage"/></xsl:when>
		<xsl:when test=".='InitialShipmentAdvice'"><xsl:value-of select="$DeliveryMessageType-InitialShipmentAdvice"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@DeliveryMessageType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute DeliveryStatusType     *** -->

<xsl:template match="@DeliveryStatusType">
	<xsl:choose>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$DeliveryStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Free'"><xsl:value-of select="$DeliveryStatusType-Free"/></xsl:when>
		<xsl:when test=".='NotFree'"><xsl:value-of select="$DeliveryStatusType-NotFree"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@DeliveryStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute DeliveryScheduleReferenceType     *** -->

<xsl:template match="@DeliveryScheduleReferenceType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryLineItemStyle1" width="125">&#160;</td>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
					<xsl:choose>
						<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-ContractLineNumber"/></xsl:when>
						<xsl:when test=".='ContractNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-ContractNumber"/></xsl:when>
						<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-CustomerReferenceNumber"/></xsl:when>
						<xsl:when test=".='CustomerBookingNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-CustomerBookingNumber"/></xsl:when>
						<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-DeliveryBookingNumber"/></xsl:when>
						<xsl:when test=".='DeliveryLocation'"><xsl:value-of select="$DeliveryScheduleReferenceType-DeliveryLocation"/></xsl:when>
						<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-IndentOrderNumber"/></xsl:when>
						<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-IntraStatNumber"/></xsl:when>
						<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$DeliveryScheduleReferenceType-ISODocumentReference"/></xsl:when>
						<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-MillOrderNumber"/></xsl:when>
						<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-PurchaseOrderNumber"/></xsl:when>
						<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$DeliveryScheduleReferenceType-SupplierReferenceNumber"/></xsl:when>
						<xsl:when test=".='Other'"><xsl:value-of select="$DeliveryScheduleReferenceType-Other"/></xsl:when>		
						<xsl:otherwise>
							<b style="color:red">-<xsl:value-of select="@DeliveryScheduleReferenceType"/>-</b>
						</xsl:otherwise>
					</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute DocumentName     *** -->

<xsl:template match="@DocumentName">
	<xsl:choose>
		<xsl:when test=".='Availability'"><xsl:value-of select="$DocumentName-Availability"/></xsl:when>
		<xsl:when test=".='BusinessAcknowledgement'"><xsl:value-of select="$DocumentName-BusinessAcknowledgement"/></xsl:when>
		<xsl:when test=".='CallOff'"><xsl:value-of select="$DocumentName-CallOff"/></xsl:when>
		<xsl:when test=".='Complaint'"><xsl:value-of select="$DocumentName-Complaint"/></xsl:when>
		<xsl:when test=".='ComplaintResponse'"><xsl:value-of select="$DocumentName-ComplaintResponse"/></xsl:when>
		<xsl:when test=".='CreditDebitNote'"><xsl:value-of select="$DocumentName-CreditDebitNote"/></xsl:when>
		<xsl:when test=".='DeliveryMessage'"><xsl:value-of select="$DocumentName-DeliveryMessage"/></xsl:when>
		<xsl:when test=".='Forecast'"><xsl:value-of select="$DocumentName-Forecast"/></xsl:when>
		<xsl:when test=".='GoodsReceipt'"><xsl:value-of select="$DocumentName-GoodsReceipt"/></xsl:when>
		<xsl:when test=".='InfoRequest'"><xsl:value-of select="$DocumentName-InfoRequest"/></xsl:when>
		<xsl:when test=".='InventoryChange'"><xsl:value-of select="$DocumentName-InventoryChange"/></xsl:when>
		<xsl:when test=".='InventoryStatus'"><xsl:value-of select="$DocumentName-InventoryStatus"/></xsl:when>
		<xsl:when test=".='Invoice'"><xsl:value-of select="$DocumentName-Invoice"/></xsl:when>
		<xsl:when test=".='OrderConfirmation'"><xsl:value-of select="$DocumentName-OrderConfirmation"/></xsl:when>
		<xsl:when test=".='OrderStatus'"><xsl:value-of select="$DocumentName-OrderStatus"/></xsl:when>
		<xsl:when test=".='Planning'"><xsl:value-of select="$DocumentName-Planning"/></xsl:when>
		<xsl:when test=".='ProductAttributes'"><xsl:value-of select="$DocumentName-ProductAttributes"/></xsl:when>
		<xsl:when test=".='ProductPerformance'"><xsl:value-of select="$DocumentName-ProductPerformance"/></xsl:when>
		<xsl:when test=".='ProductQuality'"><xsl:value-of select="$DocumentName-ProductQuality"/></xsl:when>
		<xsl:when test=".='PurchaseOrder'"><xsl:value-of select="$DocumentName-PurchaseOrder"/></xsl:when>
		<xsl:when test=".='RFQ'"><xsl:value-of select="$DocumentName-RFQ"/></xsl:when>
		<xsl:when test=".='RFQResponse'"><xsl:value-of select="$DocumentName-RFQResponse"/></xsl:when>
		<xsl:when test=".='ShippingInstructions'"><xsl:value-of select="$DocumentName-ShippingInstructions"/></xsl:when>
		<xsl:when test=".='Usage'"><xsl:value-of select="$DocumentName-Usage"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$DocumentName-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@DocumentName"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute EdgeType     *** -->

<xsl:template match="@EdgeType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Top'"><xsl:value-of select="$EdgeType-Top"/></xsl:when>
					<xsl:when test=".='Bottom'"><xsl:value-of select="$EdgeType-Bottom"/></xsl:when>
					<xsl:when test=".='Left'"><xsl:value-of select="$EdgeType-Left"/></xsl:when>
					<xsl:when test=".='Right'"><xsl:value-of select="$EdgeType-Right"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@EdgeType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ExchangeRateType     *** -->

<xsl:template match="@ExchangeRateType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Fixed'"><xsl:value-of select="$ExchangeRateType-Fixed"/></xsl:when>
					<xsl:when test=".='Float'"><xsl:value-of select="$ExchangeRateType-Float"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ExchangeRateType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute FibreSource     *** -->

<xsl:template match="@FibreSource">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>		
					<xsl:when test=".='Acacia'"><xsl:value-of select="$FibreSource-Acacia"/></xsl:when>
					<xsl:when test=".='Aspen'"><xsl:value-of select="$FibreSource-Aspen"/></xsl:when>
					<xsl:when test=".='Bagasse'"><xsl:value-of select="$FibreSource-Bagasse"/></xsl:when>
					<xsl:when test=".='Bamboo'"><xsl:value-of select="$FibreSource-Bamboo"/></xsl:when>
					<xsl:when test=".='Beech'"><xsl:value-of select="$FibreSource-Beech"/></xsl:when>
					<xsl:when test=".='Birch'"><xsl:value-of select="$FibreSource-Birch"/></xsl:when>
					<xsl:when test=".='DouglasFir'"><xsl:value-of select="$FibreSource-DouglasFir"/></xsl:when>
					<xsl:when test=".='Esparto'"><xsl:value-of select="$FibreSource-Esparto"/></xsl:when>
					<xsl:when test=".='Eucalyptus'"><xsl:value-of select="$FibreSource-Eucalyptus"/></xsl:when>
					<xsl:when test=".='Fir'"><xsl:value-of select="$FibreSource-Fir"/></xsl:when>
					<xsl:when test=".='Flax'"><xsl:value-of select="$FibreSource-Flax"/></xsl:when>
					<xsl:when test=".='Hemp'"><xsl:value-of select="$FibreSource-Hemp"/></xsl:when>
					<xsl:when test=".='Jute'"><xsl:value-of select="$FibreSource-Jute"/></xsl:when>
					<xsl:when test=".='Maple'"><xsl:value-of select="$FibreSource-Maple"/></xsl:when>
					<xsl:when test=".='MixedTropicalHardwood'"><xsl:value-of select="$FibreSource-MixedTropicalHardwood"/></xsl:when>
					<xsl:when test=".='NorthernMixedHardwood'"><xsl:value-of select="$FibreSource-NorthernMixedHardwood"/></xsl:when>
					<xsl:when test=".='Pine'"><xsl:value-of select="$FibreSource-Pine"/></xsl:when>
					<xsl:when test=".='Radiata'"><xsl:value-of select="$FibreSource-Radiata"/></xsl:when>
					<xsl:when test=".='Rag'"><xsl:value-of select="$FibreSource-Rag"/></xsl:when>
					<xsl:when test=".='Rope'"><xsl:value-of select="$FibreSource-Rope"/></xsl:when>
					<xsl:when test=".='SouthernMixedHardwood'"><xsl:value-of select="$FibreSource-SouthernMixedHardwood"/></xsl:when>
					<xsl:when test=".='SouthernSoftwood'"><xsl:value-of select="$FibreSource-SouthernSoftwood"/></xsl:when>
					<xsl:when test=".='Spruce'"><xsl:value-of select="$FibreSource-Spruce"/></xsl:when>
					<xsl:when test=".='Straw'"><xsl:value-of select="$FibreSource-Straw"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$FibreSource-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@FibreSource"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute FinishType     *** -->

<xsl:template match="@FinishType">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Dull'"><xsl:value-of select="$FinishType-Dull"/></xsl:when>
					<xsl:when test=".='English'"><xsl:value-of select="$FinishType-English"/></xsl:when>
					<xsl:when test=".='Gloss'"><xsl:value-of select="$FinishType-Gloss"/></xsl:when>
					<xsl:when test=".='Laid'"><xsl:value-of select="$FinishType-Laid"/></xsl:when>
					<xsl:when test=".='Linen'"><xsl:value-of select="$FinishType-Linen"/></xsl:when>
					<xsl:when test=".='Machine'"><xsl:value-of select="$FinishType-Machine"/></xsl:when>
					<xsl:when test=".='Matte'"><xsl:value-of select="$FinishType-Matte"/></xsl:when>
					<xsl:when test=".='Satin'"><xsl:value-of select="$FinishType-Satin"/></xsl:when>
					<xsl:when test=".='SCA'"><xsl:value-of select="$FinishType-SCA"/></xsl:when>
					<xsl:when test=".='SCB'"><xsl:value-of select="$FinishType-SCB"/></xsl:when>
					<xsl:when test=".='Silk'"><xsl:value-of select="$FinishType-Silk"/></xsl:when>
					<xsl:when test=".='Smooth'"><xsl:value-of select="$FinishType-Smooth"/></xsl:when>
					<xsl:when test=".='SoftGloss'"><xsl:value-of select="$FinishType-SoftGloss"/></xsl:when>
					<xsl:when test=".='Vellum'"><xsl:value-of select="$FinishType-Vellum"/></xsl:when>
					<xsl:when test=".='Velvet'"><xsl:value-of select="$FinishType-Velvet"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@FinishType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute GoodsReceiptAcceptance     *** -->

<xsl:template match="@GoodsReceiptAcceptance">
	<xsl:choose>
		<!--<xsl:when test=".='GoodsReceivedAdditionalItemReceived'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedAdditionalItemReceived"/></xsl:when>-->
		<xsl:when test=".='GoodsReceivedAsIs'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedAsIs"/></xsl:when>
		<xsl:when test=".='GoodsReceivedAsSpecified'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedAsSpecified"/></xsl:when>
		<xsl:when test=".='GoodsReceivedCancelled'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedCancelled"/></xsl:when>
		<!--<xsl:when test=".='GoodsReceivedItemNotReceived'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedItemNotReceived"/></xsl:when>-->
		<xsl:when test=".='GoodsReceivedRejected'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedRejected"/></xsl:when>
		<xsl:when test=".='GoodsReceivedWithDamage'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedWithDamage"/></xsl:when>
		<!--<xsl:when test=".='GoodsReceivedWithoutDeliveryMessage'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedWithoutDeliveryMessage"/></xsl:when>-->
		<xsl:when test=".='GoodsReceivedWithVariance'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedWithVariance"/></xsl:when>
		<xsl:when test=".='GoodsReceivedWithVarianceAndDamage'"><xsl:value-of select="$GoodsReceiptAcceptance-GoodsReceivedWithVarianceAndDamage"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@GoodsReceiptAcceptance"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute GoodsReceiptStatusType     *** -->

<xsl:template match="@GoodsReceiptStatusType">
	<xsl:choose>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$GoodsReceiptStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$GoodsReceiptStatusType-Original"/></xsl:when>
		<xsl:when test=".='Replaced'"><xsl:value-of select="$GoodsReceiptStatusType-Replaced"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@GoodsReceiptStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute GoodsReceivedRejectedType     *** -->

<xsl:template match="@GoodsReceivedRejectedType">
	<xsl:choose>
		<xsl:when test=".='ExcessiveTransitDamage'"><xsl:value-of select="$GoodsReceivedRejectedType-ExcessiveTransitDamage"/></xsl:when>
		<xsl:when test=".='TooEarly'"><xsl:value-of select="$GoodsReceivedRejectedType-TooEarly"/></xsl:when>
		<xsl:when test=".='TooLate'"><xsl:value-of select="$GoodsReceivedRejectedType-TooLate"/></xsl:when>
		<xsl:when test=".='UnableToUnload'"><xsl:value-of select="$GoodsReceivedRejectedType-UnableToUnload"/></xsl:when>
		<xsl:when test=".='WrongBarCode'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongBarCode"/></xsl:when>
		<xsl:when test=".='WrongBasisWeight'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongBasisWeight"/></xsl:when>
		<xsl:when test=".='WrongDiameter'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongDiameter"/></xsl:when>
		<xsl:when test=".='WrongLabel'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongLabel"/></xsl:when>
		<xsl:when test=".='WrongVehicleType'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongVehicleType"/></xsl:when>
		<xsl:when test=".='WrongWrap'"><xsl:value-of select="$GoodsReceivedRejectedType-WrongWrap"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@GoodsReceivedRejectedType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute GoodsReceiptReferenceType     *** -->

<xsl:template match="@GoodsReceiptReferenceType">
	<xsl:choose>
		<xsl:when test=".='BillOfLadingNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-BillOfLadingNumber"/></xsl:when>
		<xsl:when test=".='CallOffNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-CallOffNumber"/></xsl:when>
		<xsl:when test=".='CIMNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-CIMNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DespatchInstructionNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-DespatchInstructionNumber"/></xsl:when>
		<xsl:when test=".='InventoryChangeNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-InventoryChangeNumber"/></xsl:when>
		<xsl:when test=".='InitialShipmentAdviceNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-InitialShipmentAdviceNumber"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$GoodsReceiptReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MasterBillOfLading'"><xsl:value-of select="$GoodsReceiptReferenceType-MasterBillOfLading"/></xsl:when>
		<xsl:when test=".='MillOrderNumberLineItemNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-MillOrderNumberLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalDeliveryNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-OriginalDeliveryNumber"/></xsl:when>
		<xsl:when test=".='OriginalGoodsReceiptNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-OriginalGoodsReceiptNumber"/></xsl:when>
		<xsl:when test=".='OriginalPurchaseOrderNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-OriginalPurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$GoodsReceiptReferenceType-SupplierVoyageNumber"/></xsl:when>				
		<xsl:when test=".='Other'"><xsl:value-of select="$GoodsReceiptReferenceType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@GoodsReceiptReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute GrainDirection     *** -->

<xsl:template match="@GrainDirection">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Any'"><xsl:value-of select="$GrainDirection-Any"/></xsl:when>
					<xsl:when test=".='Long'"><xsl:value-of select="$GrainDirection-Long"/></xsl:when>
					<xsl:when test=".='Short'"><xsl:value-of select="$GrainDirection-Short"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@GrainDirection"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute GrainDirectionType     *** -->

<xsl:template match="@GrainDirectionType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Long'"><xsl:value-of select="$GrainDirectionType-Long"/></xsl:when>
					<xsl:when test=".='Short'"><xsl:value-of select="$GrainDirectionType-Short"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@GrainDirectionType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute HashAlgorithm     *** -->

<xsl:template match="@HashAlgorithm">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='md5'"><xsl:value-of select="$HashAlgorithm-md5"/></xsl:when>
				<xsl:when test=".='sha'"><xsl:value-of select="$HashAlgorithm-sha"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@HashAlgorithm"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute HoleReinforcement     *** -->

<xsl:template match="@HoleReinforcement">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$HoleReinforcement-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$HoleReinforcement-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@HoleReinforcement"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute IdentifierCodeType     *** -->

<xsl:template match="@IdentifierCodeType">
	<xsl:choose>
		<xsl:when test=".='EAN8'"><xsl:value-of select="$IdentifierCodeType-EAN8"/></xsl:when>
		<xsl:when test=".='EAN13'"><xsl:value-of select="$IdentifierCodeType-EAN13"/></xsl:when>
		<xsl:when test=".='EUGROPA'"><xsl:value-of select="$IdentifierCodeType-EUGROPA"/></xsl:when>
		<xsl:when test=".='GlobalReturnableAssetIdentifier'"><xsl:value-of select="$IdentifierCodeType-GlobalReturnableAssetIdentifier"/></xsl:when>
		<xsl:when test=".='GlobalTradeItemNumber'"><xsl:value-of select="$IdentifierCodeType-GlobalTradeItemNumber"/></xsl:when>
		<xsl:when test=".='IFRA'"><xsl:value-of select="$IdentifierCodeType-IFRA"/></xsl:when>
		<xsl:when test=".='ISBN10'"><xsl:value-of select="$IdentifierCodeType-ISBN10"/></xsl:when>
		<xsl:when test=".='ISBN10Dash'"><xsl:value-of select="$IdentifierCodeType-ISBN10Dash"/></xsl:when>
		<xsl:when test=".='ISBN13'"><xsl:value-of select="$IdentifierCodeType-ISBN13"/></xsl:when>
		<xsl:when test=".='NARI'"><xsl:value-of select="$IdentifierCodeType-NARI"/></xsl:when>
		<xsl:when test=".='NPTA'"><xsl:value-of select="$IdentifierCodeType-NPTA"/></xsl:when>
		<xsl:when test=".='SerialisedShippingContainerCode'"><xsl:value-of select="$IdentifierCodeType-SerialisedShippingContainerCode"/></xsl:when>
		<xsl:when test=".='Supplier'"><xsl:value-of select="$IdentifierCodeType-Supplier"/></xsl:when>
		<xsl:when test=".='TAPPI9'"><xsl:value-of select="$IdentifierCodeType-TAPPI9"/></xsl:when>
		<xsl:when test=".='TAPPI13'"><xsl:value-of select="$IdentifierCodeType-TAPPI13"/></xsl:when>
		<xsl:when test=".='UIC14'"><xsl:value-of select="$IdentifierCodeType-UIC14"/></xsl:when>
		<xsl:when test=".='UIC16'"><xsl:value-of select="$IdentifierCodeType-UIC16"/></xsl:when>
		<xsl:when test=".='UPC'"><xsl:value-of select="$IdentifierCodeType-UPC"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@IdentifierCodeType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute IdentifierType     *** -->

<xsl:template match="@IdentifierType">
	<xsl:choose>
		<xsl:when test=".='Barcode'"><xsl:value-of select="$IdentifierType-Barcode"/></xsl:when>
		<xsl:when test=".='Primary'"><xsl:value-of select="$IdentifierType-Primary"/></xsl:when>
		<xsl:when test=".='RFTag'"><xsl:value-of select="$IdentifierType-RFTag"/></xsl:when>
		<xsl:when test=".='Secondary'"><xsl:value-of select="$IdentifierType-Secondary"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@IdentifierType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute Incoterms     *** -->

<xsl:template match="@Incoterms">
	<xsl:choose>
		<xsl:when test=".='CFR'"><xsl:value-of select="$Incoterms-CFR"/></xsl:when>
		<xsl:when test=".='CIF'"><xsl:value-of select="$Incoterms-CIF"/></xsl:when>
		<xsl:when test=".='CIP'"><xsl:value-of select="$Incoterms-CIP"/></xsl:when>
		<xsl:when test=".='CPT'"><xsl:value-of select="$Incoterms-CPT"/></xsl:when>
		<xsl:when test=".='DAF'"><xsl:value-of select="$Incoterms-DAF"/></xsl:when>
		<xsl:when test=".='DDP'"><xsl:value-of select="$Incoterms-DDP"/></xsl:when>
		<xsl:when test=".='DDU'"><xsl:value-of select="$Incoterms-DDU"/></xsl:when>
		<xsl:when test=".='DEQ'"><xsl:value-of select="$Incoterms-DEQ"/></xsl:when>
		<xsl:when test=".='DES'"><xsl:value-of select="$Incoterms-DES"/></xsl:when>
		<xsl:when test=".='EXW'"><xsl:value-of select="$Incoterms-EXW"/></xsl:when>
		<xsl:when test=".='FAS'"><xsl:value-of select="$Incoterms-FAS"/></xsl:when>
		<xsl:when test=".='FCA'"><xsl:value-of select="$Incoterms-FCA"/></xsl:when>
		<xsl:when test=".='FOB'"><xsl:value-of select="$Incoterms-FOB"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$Incoterms-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@Incoterms"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute InventoryChangeReferenceType     *** -->

<xsl:template match="@InventoryChangeReferenceType">
	<xsl:choose>
		<xsl:when test=".='AccountNumber'"><xsl:value-of select="$InventoryChangeReferenceType-AccountNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$InventoryChangeReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ComplaintNumber'"><xsl:value-of select="$InventoryChangeReferenceType-ComplaintNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$InventoryChangeReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$InventoryChangeReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$InventoryChangeReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$InventoryChangeReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$InventoryChangeReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$InventoryChangeReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$InventoryChangeReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$InventoryChangeReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$InventoryChangeReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$InventoryChangeReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$InventoryChangeReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='SupplierClaimNumber'"><xsl:value-of select="$InventoryChangeReferenceType-SupplierClaimNumber"/></xsl:when>
		<xsl:when test=".='BuyerClaimNumber'"><xsl:value-of select="$InventoryChangeReferenceType-BuyerClaimNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$InventoryChangeReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@InventoryChangeReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute InventoryStatusReferenceType     *** -->

<xsl:template match="@InventoryStatusReferenceType">
	<xsl:choose>
		<xsl:when test=".='AccountNumber'"><xsl:value-of select="$InventoryStatusReferenceType-AccountNumber"/></xsl:when>
		<xsl:when test=".='CMRNumber'"><xsl:value-of select="$InventoryStatusReferenceType-CMRNumber"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$InventoryStatusReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$InventoryStatusReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$InventoryStatusReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$InventoryStatusReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$InventoryStatusReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$InventoryStatusReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$InventoryStatusReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$InventoryStatusReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$InventoryStatusReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$InventoryStatusReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$InventoryStatusReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='SupplierClaimNumber'"><xsl:value-of select="$InventoryStatusReferenceType-SupplierClaimNumber"/></xsl:when>
		<xsl:when test=".='BuyerClaimNumber'"><xsl:value-of select="$InventoryStatusReferenceType-BuyerClaimNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$InventoryStatusReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@InventoryStatusReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute InvoiceReferenceType     *** -->

<xsl:template match="@InvoiceReferenceType">
	<!--<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>-->
				<xsl:choose>
					<xsl:when test=".='AccountNumber'"><xsl:value-of select="$InvoiceReferenceType-AccountNumber"/></xsl:when>
					<xsl:when test=".='Author'"><xsl:value-of select="$InvoiceReferenceType-Author"/></xsl:when>
					<xsl:when test=".='BookLanguage'"><xsl:value-of select="$InvoiceReferenceType-BookLanguage"/></xsl:when>
					<xsl:when test=".='CMRNumber'"><xsl:value-of select="$InvoiceReferenceType-CMRNumber"/></xsl:when>
					<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$InvoiceReferenceType-ContractLineNumber"/></xsl:when>
					<xsl:when test=".='ContractNumber'"><xsl:value-of select="$InvoiceReferenceType-ContractNumber"/></xsl:when>
					<xsl:when test=".='ConvertingReportNumber'"><xsl:value-of select="$InvoiceReferenceType-ConvertingReportNumber"/></xsl:when>
					<xsl:when test=".='Copyright'"><xsl:value-of select="$InvoiceReferenceType-Copyright"/></xsl:when>
					<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$InvoiceReferenceType-CustomerReferenceNumber"/></xsl:when>
					<xsl:when test=".='DespatchInformationNumber'"><xsl:value-of select="$InvoiceReferenceType-DespatchInformationNumber"/></xsl:when>
					<xsl:when test=".='Edition'"><xsl:value-of select="$InvoiceReferenceType-Edition"/></xsl:when>
					<xsl:when test=".='GoodsReceiptNumber'"><xsl:value-of select="$InvoiceReferenceType-GoodsReceiptNumber"/></xsl:when>
					<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$InvoiceReferenceType-IndentOrderNumber"/></xsl:when>
					<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$InvoiceReferenceType-IntraStatNumber"/></xsl:when>
					<xsl:when test=".='ISBN10'"><xsl:value-of select="$InvoiceReferenceType-ISBN10"/></xsl:when>
					<xsl:when test=".='ISBN10Dash'"><xsl:value-of select="$InvoiceReferenceType-ISBN10Dash"/></xsl:when>
					<xsl:when test=".='ISBN13'"><xsl:value-of select="$InvoiceReferenceType-ISBN13"/></xsl:when>
					<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$InvoiceReferenceType-ISODocumentReference"/></xsl:when>
					<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$InvoiceReferenceType-LotIdentifier"/></xsl:when>
					<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$InvoiceReferenceType-MillOrderLineItemNumber"/></xsl:when>
					<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$InvoiceReferenceType-MillOrderNumber"/></xsl:when>
					<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$InvoiceReferenceType-OriginalInvoiceNumber"/></xsl:when>
					<xsl:when test=".='PupilsTeachers'"><xsl:value-of select="$InvoiceReferenceType-PupilsTeachers"/></xsl:when>
					<xsl:when test=".='SchoolGrade'"><xsl:value-of select="$InvoiceReferenceType-SchoolGrade"/></xsl:when>
					<xsl:when test=".='SchoolGradeLevel'"><xsl:value-of select="$InvoiceReferenceType-SchoolGradeLevel"/></xsl:when>
					<xsl:when test=".='ShippingInstructionsLineItemNumber'"><xsl:value-of select="$InvoiceReferenceType-ShippingInstructionsLineItemNumber"/></xsl:when>
					<xsl:when test=".='ShippingInstructionsNumber'"><xsl:value-of select="$InvoiceReferenceType-ShippingInstructionsNumber"/></xsl:when>
					<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$InvoiceReferenceType-StockOrderNumber"/></xsl:when>
					<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$InvoiceReferenceType-SupplierReferenceNumber"/></xsl:when>
					<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$InvoiceReferenceType-SupplierVoyageNumber"/></xsl:when>
					<xsl:when test=".='UsageNumber'"><xsl:value-of select="$InvoiceReferenceType-UsageNumber"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$InvoiceReferenceType-Other"/></xsl:when>						
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@InvoiceReferenceType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<!--<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>-->
</xsl:template>

<!-- ***     Attribute InvoiceType     *** -->

<xsl:template match="@InvoiceType">
	<xsl:choose>
		<xsl:when test=".='Duplicate'"><xsl:value-of select="$InvoiceType-Duplicate"/></xsl:when>
		<xsl:when test=".='Invoice'"><xsl:value-of select="$InvoiceType-Invoice"/></xsl:when>
		<xsl:when test=".='PrePayment'"><xsl:value-of select="$InvoiceType-PrePayment"/></xsl:when>
		<xsl:when test=".='ProForma'"><xsl:value-of select="$InvoiceType-ProForma"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@InvoiceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute IssueNewPO     *** -->

<xsl:template match="@IssueNewPO">
	<xsl:choose>
		<xsl:when test=".='Yes'"><xsl:value-of select="$IssueNewPO-Yes"/></xsl:when>
		<xsl:when test=".='No'"><xsl:value-of select="$IssueNewPO-No"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@IssueNewPO"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ISOCountryCode     *** -->

<xsl:template match="@ISOCountryCode">
	<xsl:choose>
		<xsl:when test=".='AF'"><xsl:value-of select="$ISOCountryCode-AF"/></xsl:when>
		<xsl:when test=".='AL'"><xsl:value-of select="$ISOCountryCode-AL"/></xsl:when>
		<xsl:when test=".='DZ'"><xsl:value-of select="$ISOCountryCode-DZ"/></xsl:when>
		<xsl:when test=".='AS'"><xsl:value-of select="$ISOCountryCode-AS"/></xsl:when>
		<xsl:when test=".='AD'"><xsl:value-of select="$ISOCountryCode-AD"/></xsl:when>
		<xsl:when test=".='AO'"><xsl:value-of select="$ISOCountryCode-AO"/></xsl:when>
		<xsl:when test=".='AI'"><xsl:value-of select="$ISOCountryCode-AI"/></xsl:when>
		<xsl:when test=".='AQ'"><xsl:value-of select="$ISOCountryCode-AQ"/></xsl:when>
		<xsl:when test=".='AG'"><xsl:value-of select="$ISOCountryCode-AG"/></xsl:when>
		<xsl:when test=".='AR'"><xsl:value-of select="$ISOCountryCode-AR"/></xsl:when>
		<xsl:when test=".='AM'"><xsl:value-of select="$ISOCountryCode-AM"/></xsl:when>
		<xsl:when test=".='AW'"><xsl:value-of select="$ISOCountryCode-AW"/></xsl:when>
		<xsl:when test=".='AU'"><xsl:value-of select="$ISOCountryCode-AU"/></xsl:when>
		<xsl:when test=".='AT'"><xsl:value-of select="$ISOCountryCode-AT"/></xsl:when>
		<xsl:when test=".='AZ'"><xsl:value-of select="$ISOCountryCode-AZ"/></xsl:when>
		<xsl:when test=".='BS'"><xsl:value-of select="$ISOCountryCode-BS"/></xsl:when>
		<xsl:when test=".='BH'"><xsl:value-of select="$ISOCountryCode-BH"/></xsl:when>
		<xsl:when test=".='BD'"><xsl:value-of select="$ISOCountryCode-BD"/></xsl:when>
		<xsl:when test=".='BB'"><xsl:value-of select="$ISOCountryCode-BB"/></xsl:when>
		<xsl:when test=".='BY'"><xsl:value-of select="$ISOCountryCode-BY"/></xsl:when>
		<xsl:when test=".='BE'"><xsl:value-of select="$ISOCountryCode-BE"/></xsl:when>
		<xsl:when test=".='BJ'"><xsl:value-of select="$ISOCountryCode-BJ"/></xsl:when>
		<xsl:when test=".='BM'"><xsl:value-of select="$ISOCountryCode-BM"/></xsl:when>
		<xsl:when test=".='BT'"><xsl:value-of select="$ISOCountryCode-BT"/></xsl:when>
		<xsl:when test=".='BO'"><xsl:value-of select="$ISOCountryCode-BO"/></xsl:when>
		<xsl:when test=".='BA'"><xsl:value-of select="$ISOCountryCode-BA"/></xsl:when>
		<xsl:when test=".='BW'"><xsl:value-of select="$ISOCountryCode-BW"/></xsl:when>
		<xsl:when test=".='BV'"><xsl:value-of select="$ISOCountryCode-BV"/></xsl:when>
		<xsl:when test=".='BR'"><xsl:value-of select="$ISOCountryCode-BR"/></xsl:when>
		<xsl:when test=".='IO'"><xsl:value-of select="$ISOCountryCode-IO"/></xsl:when>
		<xsl:when test=".='BN'"><xsl:value-of select="$ISOCountryCode-BN"/></xsl:when>
		<xsl:when test=".='BG'"><xsl:value-of select="$ISOCountryCode-BG"/></xsl:when>
		<xsl:when test=".='BF'"><xsl:value-of select="$ISOCountryCode-BF"/></xsl:when>
		<xsl:when test=".='BI'"><xsl:value-of select="$ISOCountryCode-BI"/></xsl:when>
		<xsl:when test=".='KH'"><xsl:value-of select="$ISOCountryCode-KH"/></xsl:when>
		<xsl:when test=".='CM'"><xsl:value-of select="$ISOCountryCode-CM"/></xsl:when>
		<xsl:when test=".='CA'"><xsl:value-of select="$ISOCountryCode-CA"/></xsl:when>
		<xsl:when test=".='CV'"><xsl:value-of select="$ISOCountryCode-CV"/></xsl:when>
		<xsl:when test=".='KY'"><xsl:value-of select="$ISOCountryCode-KY"/></xsl:when>
		<xsl:when test=".='CF'"><xsl:value-of select="$ISOCountryCode-CF"/></xsl:when>
		<xsl:when test=".='TD'"><xsl:value-of select="$ISOCountryCode-TD"/></xsl:when>
		<xsl:when test=".='CL'"><xsl:value-of select="$ISOCountryCode-CL"/></xsl:when>
		<xsl:when test=".='CN'"><xsl:value-of select="$ISOCountryCode-CN"/></xsl:when>
		<xsl:when test=".='CX'"><xsl:value-of select="$ISOCountryCode-CX"/></xsl:when>
		<xsl:when test=".='CC'"><xsl:value-of select="$ISOCountryCode-CC"/></xsl:when>
		<xsl:when test=".='CO'"><xsl:value-of select="$ISOCountryCode-CO"/></xsl:when>
		<xsl:when test=".='KM'"><xsl:value-of select="$ISOCountryCode-KM"/></xsl:when>
		<xsl:when test=".='CG'"><xsl:value-of select="$ISOCountryCode-CG"/></xsl:when>
		<xsl:when test=".='CK'"><xsl:value-of select="$ISOCountryCode-CK"/></xsl:when>
		<xsl:when test=".='CR'"><xsl:value-of select="$ISOCountryCode-CR"/></xsl:when>
		<xsl:when test=".='HR'"><xsl:value-of select="$ISOCountryCode-HR"/></xsl:when>
		<xsl:when test=".='CU'"><xsl:value-of select="$ISOCountryCode-CU"/></xsl:when>
		<xsl:when test=".='CY'"><xsl:value-of select="$ISOCountryCode-CY"/></xsl:when>
		<xsl:when test=".='CZ'"><xsl:value-of select="$ISOCountryCode-CZ"/></xsl:when>
		<xsl:when test=".='DK'"><xsl:value-of select="$ISOCountryCode-DK"/></xsl:when>
		<xsl:when test=".='DJ'"><xsl:value-of select="$ISOCountryCode-DJ"/></xsl:when>
		<xsl:when test=".='DM'"><xsl:value-of select="$ISOCountryCode-DM"/></xsl:when>
		<xsl:when test=".='DO'"><xsl:value-of select="$ISOCountryCode-DO"/></xsl:when>
		<xsl:when test=".='TP'"><xsl:value-of select="$ISOCountryCode-TP"/></xsl:when>
		<xsl:when test=".='EC'"><xsl:value-of select="$ISOCountryCode-EC"/></xsl:when>
		<xsl:when test=".='EG'"><xsl:value-of select="$ISOCountryCode-EG"/></xsl:when>
		<xsl:when test=".='SV'"><xsl:value-of select="$ISOCountryCode-SV"/></xsl:when>
		<xsl:when test=".='GQ'"><xsl:value-of select="$ISOCountryCode-GQ"/></xsl:when>
		<xsl:when test=".='ER'"><xsl:value-of select="$ISOCountryCode-ER"/></xsl:when>
		<xsl:when test=".='EF'"><xsl:value-of select="$ISOCountryCode-EF"/></xsl:when>
		<xsl:when test=".='ET'"><xsl:value-of select="$ISOCountryCode-ET"/></xsl:when>
		<xsl:when test=".='FK'"><xsl:value-of select="$ISOCountryCode-FK"/></xsl:when>
		<xsl:when test=".='FO'"><xsl:value-of select="$ISOCountryCode-FO"/></xsl:when>
		<xsl:when test=".='FJ'"><xsl:value-of select="$ISOCountryCode-FJ"/></xsl:when>
		<xsl:when test=".='FI'"><xsl:value-of select="$ISOCountryCode-FI"/></xsl:when>
		<xsl:when test=".='FR'"><xsl:value-of select="$ISOCountryCode-FR"/></xsl:when>
		<xsl:when test=".='FX'"><xsl:value-of select="$ISOCountryCode-FX"/></xsl:when>
		<xsl:when test=".='GF'"><xsl:value-of select="$ISOCountryCode-GF"/></xsl:when>
		<xsl:when test=".='PF'"><xsl:value-of select="$ISOCountryCode-PF"/></xsl:when>
		<xsl:when test=".='TF'"><xsl:value-of select="$ISOCountryCode-TF"/></xsl:when>
		<xsl:when test=".='GA'"><xsl:value-of select="$ISOCountryCode-GA"/></xsl:when>
		<xsl:when test=".='GM'"><xsl:value-of select="$ISOCountryCode-GM"/></xsl:when>
		<xsl:when test=".='GE'"><xsl:value-of select="$ISOCountryCode-GE"/></xsl:when>
		<xsl:when test=".='DE'"><xsl:value-of select="$ISOCountryCode-DE"/></xsl:when>
		<xsl:when test=".='GH'"><xsl:value-of select="$ISOCountryCode-GH"/></xsl:when>
		<xsl:when test=".='GI'"><xsl:value-of select="$ISOCountryCode-GI"/></xsl:when>
		<xsl:when test=".='GR'"><xsl:value-of select="$ISOCountryCode-GR"/></xsl:when>
		<xsl:when test=".='GL'"><xsl:value-of select="$ISOCountryCode-GL"/></xsl:when>
		<xsl:when test=".='GD'"><xsl:value-of select="$ISOCountryCode-GD"/></xsl:when>
		<xsl:when test=".='GP'"><xsl:value-of select="$ISOCountryCode-GP"/></xsl:when>
		<xsl:when test=".='GU'"><xsl:value-of select="$ISOCountryCode-GU"/></xsl:when>
		<xsl:when test=".='GT'"><xsl:value-of select="$ISOCountryCode-GT"/></xsl:when>
		<xsl:when test=".='GN'"><xsl:value-of select="$ISOCountryCode-GN"/></xsl:when>
		<xsl:when test=".='GW'"><xsl:value-of select="$ISOCountryCode-GW"/></xsl:when>
		<xsl:when test=".='GY'"><xsl:value-of select="$ISOCountryCode-GY"/></xsl:when>
		<xsl:when test=".='HT'"><xsl:value-of select="$ISOCountryCode-HT"/></xsl:when>
		<xsl:when test=".='HM'"><xsl:value-of select="$ISOCountryCode-HM"/></xsl:when>
		<xsl:when test=".='HN'"><xsl:value-of select="$ISOCountryCode-HN"/></xsl:when>
		<xsl:when test=".='HK'"><xsl:value-of select="$ISOCountryCode-HK"/></xsl:when>
		<xsl:when test=".='HU'"><xsl:value-of select="$ISOCountryCode-HU"/></xsl:when>
		<xsl:when test=".='IS'"><xsl:value-of select="$ISOCountryCode-IS"/></xsl:when>
		<xsl:when test=".='IN'"><xsl:value-of select="$ISOCountryCode-IN"/></xsl:when>
		<xsl:when test=".='ID'"><xsl:value-of select="$ISOCountryCode-ID"/></xsl:when>
		<xsl:when test=".='IR'"><xsl:value-of select="$ISOCountryCode-IR"/></xsl:when>
		<xsl:when test=".='IQ'"><xsl:value-of select="$ISOCountryCode-IQ"/></xsl:when>
		<xsl:when test=".='IE'"><xsl:value-of select="$ISOCountryCode-IE"/></xsl:when>
		<xsl:when test=".='IL'"><xsl:value-of select="$ISOCountryCode-IL"/></xsl:when>
		<xsl:when test=".='IT'"><xsl:value-of select="$ISOCountryCode-IT"/></xsl:when>
		<xsl:when test=".='CI'"><xsl:value-of select="$ISOCountryCode-CI"/></xsl:when>
		<xsl:when test=".='JM'"><xsl:value-of select="$ISOCountryCode-JM"/></xsl:when>
		<xsl:when test=".='JP'"><xsl:value-of select="$ISOCountryCode-JP"/></xsl:when>
		<xsl:when test=".='JO'"><xsl:value-of select="$ISOCountryCode-JO"/></xsl:when>
		<xsl:when test=".='KZ'"><xsl:value-of select="$ISOCountryCode-KZ"/></xsl:when>
		<xsl:when test=".='KE'"><xsl:value-of select="$ISOCountryCode-KE"/></xsl:when>
		<xsl:when test=".='KI'"><xsl:value-of select="$ISOCountryCode-KI"/></xsl:when>
		<xsl:when test=".='KP'"><xsl:value-of select="$ISOCountryCode-KP"/></xsl:when>
		<xsl:when test=".='KR'"><xsl:value-of select="$ISOCountryCode-KR"/></xsl:when>
		<xsl:when test=".='KW'"><xsl:value-of select="$ISOCountryCode-KW"/></xsl:when>
		<xsl:when test=".='KG'"><xsl:value-of select="$ISOCountryCode-KG"/></xsl:when>
		<xsl:when test=".='LA'"><xsl:value-of select="$ISOCountryCode-LA"/></xsl:when>
		<xsl:when test=".='LV'"><xsl:value-of select="$ISOCountryCode-LV"/></xsl:when>
		<xsl:when test=".='LB'"><xsl:value-of select="$ISOCountryCode-LB"/></xsl:when>
		<xsl:when test=".='LS'"><xsl:value-of select="$ISOCountryCode-LS"/></xsl:when>
		<xsl:when test=".='LR'"><xsl:value-of select="$ISOCountryCode-LR"/></xsl:when>
		<xsl:when test=".='LY'"><xsl:value-of select="$ISOCountryCode-LY"/></xsl:when>
		<xsl:when test=".='LI'"><xsl:value-of select="$ISOCountryCode-LI"/></xsl:when>
		<xsl:when test=".='LT'"><xsl:value-of select="$ISOCountryCode-LT"/></xsl:when>
		<xsl:when test=".='LU'"><xsl:value-of select="$ISOCountryCode-LU"/></xsl:when>
		<xsl:when test=".='MO'"><xsl:value-of select="$ISOCountryCode-MO"/></xsl:when>
		<xsl:when test=".='MG'"><xsl:value-of select="$ISOCountryCode-MG"/></xsl:when>
		<xsl:when test=".='MW'"><xsl:value-of select="$ISOCountryCode-MW"/></xsl:when>
		<xsl:when test=".='MY'"><xsl:value-of select="$ISOCountryCode-MY"/></xsl:when>
		<xsl:when test=".='MV'"><xsl:value-of select="$ISOCountryCode-MV"/></xsl:when>
		<xsl:when test=".='ML'"><xsl:value-of select="$ISOCountryCode-ML"/></xsl:when>
		<xsl:when test=".='MT'"><xsl:value-of select="$ISOCountryCode-MT"/></xsl:when>
		<xsl:when test=".='MH'"><xsl:value-of select="$ISOCountryCode-MH"/></xsl:when>
		<xsl:when test=".='MQ'"><xsl:value-of select="$ISOCountryCode-MQ"/></xsl:when>
		<xsl:when test=".='MR'"><xsl:value-of select="$ISOCountryCode-MR"/></xsl:when>
		<xsl:when test=".='MU'"><xsl:value-of select="$ISOCountryCode-MU"/></xsl:when>
		<xsl:when test=".='YT'"><xsl:value-of select="$ISOCountryCode-YT"/></xsl:when>
		<xsl:when test=".='MX'"><xsl:value-of select="$ISOCountryCode-MX"/></xsl:when>
		<xsl:when test=".='FM'"><xsl:value-of select="$ISOCountryCode-FM"/></xsl:when>
		<xsl:when test=".='MD'"><xsl:value-of select="$ISOCountryCode-MD"/></xsl:when>
		<xsl:when test=".='MC'"><xsl:value-of select="$ISOCountryCode-MC"/></xsl:when>
		<xsl:when test=".='MN'"><xsl:value-of select="$ISOCountryCode-MN"/></xsl:when>
		<xsl:when test=".='MS'"><xsl:value-of select="$ISOCountryCode-MS"/></xsl:when>
		<xsl:when test=".='MA'"><xsl:value-of select="$ISOCountryCode-MA"/></xsl:when>
		<xsl:when test=".='MZ'"><xsl:value-of select="$ISOCountryCode-MZ"/></xsl:when>
		<xsl:when test=".='MM'"><xsl:value-of select="$ISOCountryCode-MM"/></xsl:when>
		<xsl:when test=".='NA'"><xsl:value-of select="$ISOCountryCode-NA"/></xsl:when>
		<xsl:when test=".='NR'"><xsl:value-of select="$ISOCountryCode-NR"/></xsl:when>
		<xsl:when test=".='NP'"><xsl:value-of select="$ISOCountryCode-NP"/></xsl:when>
		<xsl:when test=".='NL'"><xsl:value-of select="$ISOCountryCode-NL"/></xsl:when>
		<xsl:when test=".='AN'"><xsl:value-of select="$ISOCountryCode-AN"/></xsl:when>
		<xsl:when test=".='NC'"><xsl:value-of select="$ISOCountryCode-NC"/></xsl:when>
		<xsl:when test=".='NZ'"><xsl:value-of select="$ISOCountryCode-NZ"/></xsl:when>
		<xsl:when test=".='NI'"><xsl:value-of select="$ISOCountryCode-NI"/></xsl:when>
		<xsl:when test=".='NE'"><xsl:value-of select="$ISOCountryCode-NE"/></xsl:when>
		<xsl:when test=".='NG'"><xsl:value-of select="$ISOCountryCode-NG"/></xsl:when>
		<xsl:when test=".='NU'"><xsl:value-of select="$ISOCountryCode-NU"/></xsl:when>
		<xsl:when test=".='NF'"><xsl:value-of select="$ISOCountryCode-NF"/></xsl:when>
		<xsl:when test=".='MP'"><xsl:value-of select="$ISOCountryCode-MP"/></xsl:when>
		<xsl:when test=".='NO'"><xsl:value-of select="$ISOCountryCode-NO"/></xsl:when>
		<xsl:when test=".='OM'"><xsl:value-of select="$ISOCountryCode-OM"/></xsl:when>
		<xsl:when test=".='PK'"><xsl:value-of select="$ISOCountryCode-PK"/></xsl:when>
		<xsl:when test=".='PW'"><xsl:value-of select="$ISOCountryCode-PW"/></xsl:when>
		<xsl:when test=".='PA'"><xsl:value-of select="$ISOCountryCode-PA"/></xsl:when>
		<xsl:when test=".='PG'"><xsl:value-of select="$ISOCountryCode-PG"/></xsl:when>
		<xsl:when test=".='PY'"><xsl:value-of select="$ISOCountryCode-PY"/></xsl:when>
		<xsl:when test=".='PE'"><xsl:value-of select="$ISOCountryCode-PE"/></xsl:when>
		<xsl:when test=".='PH'"><xsl:value-of select="$ISOCountryCode-PH"/></xsl:when>
		<xsl:when test=".='PN'"><xsl:value-of select="$ISOCountryCode-PN"/></xsl:when>
		<xsl:when test=".='PL'"><xsl:value-of select="$ISOCountryCode-PL"/></xsl:when>
		<xsl:when test=".='PT'"><xsl:value-of select="$ISOCountryCode-PT"/></xsl:when>
		<xsl:when test=".='PR'"><xsl:value-of select="$ISOCountryCode-PR"/></xsl:when>
		<xsl:when test=".='QA'"><xsl:value-of select="$ISOCountryCode-QA"/></xsl:when>
		<xsl:when test=".='RE'"><xsl:value-of select="$ISOCountryCode-RE"/></xsl:when>
		<xsl:when test=".='RO'"><xsl:value-of select="$ISOCountryCode-RO"/></xsl:when>
		<xsl:when test=".='RU'"><xsl:value-of select="$ISOCountryCode-RU"/></xsl:when>
		<xsl:when test=".='RW'"><xsl:value-of select="$ISOCountryCode-RW"/></xsl:when>
		<xsl:when test=".='SH'"><xsl:value-of select="$ISOCountryCode-SH"/></xsl:when>
		<xsl:when test=".='KN'"><xsl:value-of select="$ISOCountryCode-KN"/></xsl:when>
		<xsl:when test=".='LC'"><xsl:value-of select="$ISOCountryCode-LC"/></xsl:when>
		<xsl:when test=".='PM'"><xsl:value-of select="$ISOCountryCode-PM"/></xsl:when>
		<xsl:when test=".='VC'"><xsl:value-of select="$ISOCountryCode-VC"/></xsl:when>
		<xsl:when test=".='WS'"><xsl:value-of select="$ISOCountryCode-WS"/></xsl:when>
		<xsl:when test=".='SM'"><xsl:value-of select="$ISOCountryCode-SM"/></xsl:when>
		<xsl:when test=".='ST'"><xsl:value-of select="$ISOCountryCode-ST"/></xsl:when>
		<xsl:when test=".='SA'"><xsl:value-of select="$ISOCountryCode-SA"/></xsl:when>
		<xsl:when test=".='SN'"><xsl:value-of select="$ISOCountryCode-SN"/></xsl:when>
		<xsl:when test=".='SC'"><xsl:value-of select="$ISOCountryCode-SC"/></xsl:when>
		<xsl:when test=".='SL'"><xsl:value-of select="$ISOCountryCode-SL"/></xsl:when>
		<xsl:when test=".='SG'"><xsl:value-of select="$ISOCountryCode-SG"/></xsl:when>
		<xsl:when test=".='SK'"><xsl:value-of select="$ISOCountryCode-SK"/></xsl:when>
		<xsl:when test=".='SI'"><xsl:value-of select="$ISOCountryCode-SI"/></xsl:when>
		<xsl:when test=".='SB'"><xsl:value-of select="$ISOCountryCode-SB"/></xsl:when>
		<xsl:when test=".='SO'"><xsl:value-of select="$ISOCountryCode-SO"/></xsl:when>
		<xsl:when test=".='ZA'"><xsl:value-of select="$ISOCountryCode-ZA"/></xsl:when>
		<xsl:when test=".='GS'"><xsl:value-of select="$ISOCountryCode-GS"/></xsl:when>
		<xsl:when test=".='ES'"><xsl:value-of select="$ISOCountryCode-ES"/></xsl:when>
		<xsl:when test=".='LK'"><xsl:value-of select="$ISOCountryCode-LK"/></xsl:when>
		<xsl:when test=".='SD'"><xsl:value-of select="$ISOCountryCode-SD"/></xsl:when>
		<xsl:when test=".='SR'"><xsl:value-of select="$ISOCountryCode-SR"/></xsl:when>
		<xsl:when test=".='SJ'"><xsl:value-of select="$ISOCountryCode-SJ"/></xsl:when>
		<xsl:when test=".='SZ'"><xsl:value-of select="$ISOCountryCode-SZ"/></xsl:when>
		<xsl:when test=".='SE'"><xsl:value-of select="$ISOCountryCode-SE"/></xsl:when>
		<xsl:when test=".='CH'"><xsl:value-of select="$ISOCountryCode-CH"/></xsl:when>
		<xsl:when test=".='SY'"><xsl:value-of select="$ISOCountryCode-SY"/></xsl:when>
		<xsl:when test=".='TW'"><xsl:value-of select="$ISOCountryCode-TW"/></xsl:when>
		<xsl:when test=".='TJ'"><xsl:value-of select="$ISOCountryCode-TJ"/></xsl:when>
		<xsl:when test=".='TZ'"><xsl:value-of select="$ISOCountryCode-TZ"/></xsl:when>
		<xsl:when test=".='TH'"><xsl:value-of select="$ISOCountryCode-TH"/></xsl:when>
		<xsl:when test=".='TG'"><xsl:value-of select="$ISOCountryCode-TG"/></xsl:when>
		<xsl:when test=".='TK'"><xsl:value-of select="$ISOCountryCode-TK"/></xsl:when>
		<xsl:when test=".='TO'"><xsl:value-of select="$ISOCountryCode-TO"/></xsl:when>
		<xsl:when test=".='TT'"><xsl:value-of select="$ISOCountryCode-TT"/></xsl:when>
		<xsl:when test=".='TN'"><xsl:value-of select="$ISOCountryCode-TN"/></xsl:when>
		<xsl:when test=".='TR'"><xsl:value-of select="$ISOCountryCode-TR"/></xsl:when>
		<xsl:when test=".='TM'"><xsl:value-of select="$ISOCountryCode-TM"/></xsl:when>
		<xsl:when test=".='TC'"><xsl:value-of select="$ISOCountryCode-TC"/></xsl:when>
		<xsl:when test=".='TV'"><xsl:value-of select="$ISOCountryCode-TV"/></xsl:when>
		<xsl:when test=".='UG'"><xsl:value-of select="$ISOCountryCode-UG"/></xsl:when>
		<xsl:when test=".='UA'"><xsl:value-of select="$ISOCountryCode-UA"/></xsl:when>
		<xsl:when test=".='AE'"><xsl:value-of select="$ISOCountryCode-AE"/></xsl:when>
		<xsl:when test=".='GB'"><xsl:value-of select="$ISOCountryCode-GB"/></xsl:when>
		<xsl:when test=".='US'"><xsl:value-of select="$ISOCountryCode-US"/></xsl:when>
		<xsl:when test=".='UM'"><xsl:value-of select="$ISOCountryCode-UM"/></xsl:when>
		<xsl:when test=".='UY'"><xsl:value-of select="$ISOCountryCode-UY"/></xsl:when>
		<xsl:when test=".='UZ'"><xsl:value-of select="$ISOCountryCode-UZ"/></xsl:when>
		<xsl:when test=".='VU'"><xsl:value-of select="$ISOCountryCode-VU"/></xsl:when>
		<xsl:when test=".='VA'"><xsl:value-of select="$ISOCountryCode-VA"/></xsl:when>
		<xsl:when test=".='VE'"><xsl:value-of select="$ISOCountryCode-VE"/></xsl:when>
		<xsl:when test=".='VN'"><xsl:value-of select="$ISOCountryCode-VN"/></xsl:when>
		<xsl:when test=".='VG'"><xsl:value-of select="$ISOCountryCode-VG"/></xsl:when>
		<xsl:when test=".='VI'"><xsl:value-of select="$ISOCountryCode-VI"/></xsl:when>
		<xsl:when test=".='WF'"><xsl:value-of select="$ISOCountryCode-WF"/></xsl:when>
		<xsl:when test=".='EH'"><xsl:value-of select="$ISOCountryCode-EH"/></xsl:when>
		<xsl:when test=".='YE'"><xsl:value-of select="$ISOCountryCode-YE"/></xsl:when>
		<xsl:when test=".='YU'"><xsl:value-of select="$ISOCountryCode-YU"/></xsl:when>
		<xsl:when test=".='ZR'"><xsl:value-of select="$ISOCountryCode-ZR"/></xsl:when>
		<xsl:when test=".='ZM'"><xsl:value-of select="$ISOCountryCode-ZM"/></xsl:when>
		<xsl:when test=".='ZW'"><xsl:value-of select="$ISOCountryCode-ZW"/></xsl:when>                                      
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ISOCountryCode"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ItemType     *** -->

<xsl:template match="@ItemType">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='BaleItem'"><xsl:value-of select="$ItemType-BaleItem"/></xsl:when>
					<xsl:when test=".='Box'"><xsl:value-of select="$ItemType-Box"/></xsl:when>
					<xsl:when test=".='BoxItem'"><xsl:value-of select="$ItemType-BoxItem"/></xsl:when>
					<!--<xsl:when test=".='MultiReel'"><xsl:value-of select="$ItemType-MultiReel"/></xsl:when>-->
					<xsl:when test=".='Pallet'"><xsl:value-of select="$ItemType-Pallet"/></xsl:when>
					<xsl:when test=".='PulpUnit'"><xsl:value-of select="$ItemType-PulpUnit"/></xsl:when>
					<xsl:when test=".='ReamItem'"><xsl:value-of select="$ItemType-ReamItem"/></xsl:when>
					<!--<xsl:when test=".='Ream'"><xsl:value-of select="$ItemType-Ream"/></xsl:when>-->
					<xsl:when test=".='ReelItem'"><xsl:value-of select="$ItemType-ReelItem"/></xsl:when>
					<xsl:when test=".='ReelPackage'"><xsl:value-of select="$ItemType-ReelPackage"/></xsl:when>
					<xsl:when test=".='Tambour'"><xsl:value-of select="$ItemType-Tambour"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ItemType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute ItemType     *** -->

<xsl:template match="@ItemType" mode="Complaint">
	<xsl:choose>
		<xsl:when test=".='BaleItem'"><xsl:value-of select="$ItemType-BaleItem"/></xsl:when>
		<xsl:when test=".='Box'"><xsl:value-of select="$ItemType-Box"/></xsl:when>
		<xsl:when test=".='BoxItem'"><xsl:value-of select="$ItemType-BoxItem"/></xsl:when>
		<!--<xsl:when test=".='MultiReel'"><xsl:value-of select="$ItemType-MultiReel"/></xsl:when>-->
		<xsl:when test=".='Pallet'"><xsl:value-of select="$ItemType-Pallet"/></xsl:when>
		<xsl:when test=".='PulpUnit'"><xsl:value-of select="$ItemType-PulpUnit"/></xsl:when>
		<xsl:when test=".='ReamItem'"><xsl:value-of select="$ItemType-ReamItem"/></xsl:when>
		<!--<xsl:when test=".='Ream'"><xsl:value-of select="$ItemType-Ream"/></xsl:when>-->
		<xsl:when test=".='ReelItem'"><xsl:value-of select="$ItemType-ReelItem"/></xsl:when>
		<xsl:when test=".='ReelPackage'"><xsl:value-of select="$ItemType-ReelPackage"/></xsl:when>
		<xsl:when test=".='Tambour'"><xsl:value-of select="$ItemType-Tambour"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ItemType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute Language     *** -->

<xsl:template match="@Language">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<!--<xsl:choose>
					<xsl:when test=".='aa'"><xsl:value-of select="$Language-aa"/></xsl:when>
					<xsl:when test=".='ab'"><xsl:value-of select="$Language-ab"/></xsl:when>
					<xsl:when test=".='af'"><xsl:value-of select="$Language-af"/></xsl:when>
					<xsl:when test=".='am'"><xsl:value-of select="$Language-am"/></xsl:when>
					<xsl:when test=".='ar'"><xsl:value-of select="$Language-ar"/></xsl:when>
					<xsl:when test=".='as'"><xsl:value-of select="$Language-as"/></xsl:when>
					<xsl:when test=".='ay'"><xsl:value-of select="$Language-ay"/></xsl:when>
					<xsl:when test=".='az'"><xsl:value-of select="$Language-az"/></xsl:when>
					<xsl:when test=".='ba'"><xsl:value-of select="$Language-ba"/></xsl:when>
					<xsl:when test=".='be'"><xsl:value-of select="$Language-be"/></xsl:when>
					<xsl:when test=".='bg'"><xsl:value-of select="$Language-bg"/></xsl:when>
					<xsl:when test=".='bh'"><xsl:value-of select="$Language-bh"/></xsl:when>
					<xsl:when test=".='bi'"><xsl:value-of select="$Language-bi"/></xsl:when>
					<xsl:when test=".='bn'"><xsl:value-of select="$Language-bn"/></xsl:when>
					<xsl:when test=".='bo'"><xsl:value-of select="$Language-bo"/></xsl:when>
					<xsl:when test=".='ca'"><xsl:value-of select="$Language-ca"/></xsl:when>
					<xsl:when test=".='co'"><xsl:value-of select="$Language-co"/></xsl:when>
					<xsl:when test=".='cs'"><xsl:value-of select="$Language-cs"/></xsl:when>
					<xsl:when test=".='cy'"><xsl:value-of select="$Language-cy"/></xsl:when>
					<xsl:when test=".='da'"><xsl:value-of select="$Language-da"/></xsl:when>
					<xsl:when test=".='de'"><xsl:value-of select="$Language-de"/></xsl:when>
					<xsl:when test=".='dz'"><xsl:value-of select="$Language-dz"/></xsl:when>
					<xsl:when test=".='el'"><xsl:value-of select="$Language-el"/></xsl:when>
					<xsl:when test=".='en'"><xsl:value-of select="$Language-en"/></xsl:when>
					<xsl:when test=".='eo'"><xsl:value-of select="$Language-eo"/></xsl:when>
					<xsl:when test=".='es'"><xsl:value-of select="$Language-es"/></xsl:when>
					<xsl:when test=".='et'"><xsl:value-of select="$Language-et"/></xsl:when>
					<xsl:when test=".='eu'"><xsl:value-of select="$Language-eu"/></xsl:when>
					<xsl:when test=".='fa'"><xsl:value-of select="$Language-fa"/></xsl:when>
					<xsl:when test=".='fi'"><xsl:value-of select="$Language-fi"/></xsl:when>
					<xsl:when test=".='fj'"><xsl:value-of select="$Language-fj"/></xsl:when>
					<xsl:when test=".='fo'"><xsl:value-of select="$Language-fo"/></xsl:when>
					<xsl:when test=".='fr'"><xsl:value-of select="$Language-fr"/></xsl:when>
					<xsl:when test=".='fy'"><xsl:value-of select="$Language-fy"/></xsl:when>
					<xsl:when test=".='ga'"><xsl:value-of select="$Language-ga"/></xsl:when>
					<xsl:when test=".='gl'"><xsl:value-of select="$Language-gl"/></xsl:when>
					<xsl:when test=".='gn'"><xsl:value-of select="$Language-gn"/></xsl:when>
					<xsl:when test=".='gu'"><xsl:value-of select="$Language-gu"/></xsl:when>
					<xsl:when test=".='ha'"><xsl:value-of select="$Language-ha"/></xsl:when>
					<xsl:when test=".='he'"><xsl:value-of select="$Language-he"/></xsl:when>
					<xsl:when test=".='hi'"><xsl:value-of select="$Language-hi"/></xsl:when>
					<xsl:when test=".='hr'"><xsl:value-of select="$Language-hr"/></xsl:when>
					<xsl:when test=".='hu'"><xsl:value-of select="$Language-hu"/></xsl:when>
					<xsl:when test=".='hy'"><xsl:value-of select="$Language-hy"/></xsl:when>
					<xsl:when test=".='ia'"><xsl:value-of select="$Language-ia"/></xsl:when>
					<xsl:when test=".='id'"><xsl:value-of select="$Language-id"/></xsl:when>
					<xsl:when test=".='ik'"><xsl:value-of select="$Language-ik"/></xsl:when>
					<xsl:when test=".='is'"><xsl:value-of select="$Language-is"/></xsl:when>
					<xsl:when test=".='it'"><xsl:value-of select="$Language-it"/></xsl:when>
					<xsl:when test=".='iu'"><xsl:value-of select="$Language-iu"/></xsl:when>
					<xsl:when test=".='ja'"><xsl:value-of select="$Language-ja"/></xsl:when>
					<xsl:when test=".='jv'"><xsl:value-of select="$Language-jv"/></xsl:when>
					<xsl:when test=".='jw'"><xsl:value-of select="$Language-jw"/></xsl:when>
					<xsl:when test=".='ka'"><xsl:value-of select="$Language-ka"/></xsl:when>
					<xsl:when test=".='kk'"><xsl:value-of select="$Language-kk"/></xsl:when>
					<xsl:when test=".='kl'"><xsl:value-of select="$Language-kl"/></xsl:when>
					<xsl:when test=".='km'"><xsl:value-of select="$Language-km"/></xsl:when>
					<xsl:when test=".='kn'"><xsl:value-of select="$Language-kn"/></xsl:when>
					<xsl:when test=".='ko'"><xsl:value-of select="$Language-ko"/></xsl:when>
					<xsl:when test=".='ks'"><xsl:value-of select="$Language-ks"/></xsl:when>
					<xsl:when test=".='ky'"><xsl:value-of select="$Language-ky"/></xsl:when>
					<xsl:when test=".='ku'"><xsl:value-of select="$Language-ku"/></xsl:when>
					<xsl:when test=".='la'"><xsl:value-of select="$Language-la"/></xsl:when>
					<xsl:when test=".='ln'"><xsl:value-of select="$Language-ln"/></xsl:when>
					<xsl:when test=".='lo'"><xsl:value-of select="$Language-lo"/></xsl:when>
					<xsl:when test=".='lt'"><xsl:value-of select="$Language-lt"/></xsl:when>
					<xsl:when test=".='lv'"><xsl:value-of select="$Language-lv"/></xsl:when>
					<xsl:when test=".='mg'"><xsl:value-of select="$Language-mg"/></xsl:when>
					<xsl:when test=".='mi'"><xsl:value-of select="$Language-mi"/></xsl:when>
					<xsl:when test=".='mk'"><xsl:value-of select="$Language-mk"/></xsl:when>
					<xsl:when test=".='ml'"><xsl:value-of select="$Language-ml"/></xsl:when>
					<xsl:when test=".='mn'"><xsl:value-of select="$Language-mn"/></xsl:when>
					<xsl:when test=".='mo'"><xsl:value-of select="$Language-mo"/></xsl:when>
					<xsl:when test=".='mr'"><xsl:value-of select="$Language-mr"/></xsl:when>
					<xsl:when test=".='ms'"><xsl:value-of select="$Language-ms"/></xsl:when>
					<xsl:when test=".='my'"><xsl:value-of select="$Language-my"/></xsl:when>
					<xsl:when test=".='na'"><xsl:value-of select="$Language-na"/></xsl:when>
					<xsl:when test=".='ne'"><xsl:value-of select="$Language-ne"/></xsl:when>
					<xsl:when test=".='nl'"><xsl:value-of select="$Language-nl"/></xsl:when>
					<xsl:when test=".='no'"><xsl:value-of select="$Language-no"/></xsl:when>
					<xsl:when test=".='oc'"><xsl:value-of select="$Language-oc"/></xsl:when>
					<xsl:when test=".='om'"><xsl:value-of select="$Language-om"/></xsl:when>
					<xsl:when test=".='or'"><xsl:value-of select="$Language-or"/></xsl:when>
					<xsl:when test=".='pa'"><xsl:value-of select="$Language-pa"/></xsl:when>
					<xsl:when test=".='pl'"><xsl:value-of select="$Language-pl"/></xsl:when>
					<xsl:when test=".='ps'"><xsl:value-of select="$Language-ps"/></xsl:when>
					<xsl:when test=".='pt'"><xsl:value-of select="$Language-pt"/></xsl:when>
					<xsl:when test=".='qu'"><xsl:value-of select="$Language-qu"/></xsl:when>
					<xsl:when test=".='rm'"><xsl:value-of select="$Language-rm"/></xsl:when>
					<xsl:when test=".='ro'"><xsl:value-of select="$Language-ro"/></xsl:when>
					<xsl:when test=".='rn'"><xsl:value-of select="$Language-rn"/></xsl:when>
					<xsl:when test=".='ru'"><xsl:value-of select="$Language-ru"/></xsl:when>
					<xsl:when test=".='rw'"><xsl:value-of select="$Language-rw"/></xsl:when>
					<xsl:when test=".='sa'"><xsl:value-of select="$Language-sa"/></xsl:when>
					<xsl:when test=".='sd'"><xsl:value-of select="$Language-sd"/></xsl:when>
					<xsl:when test=".='sg'"><xsl:value-of select="$Language-sg"/></xsl:when>
					<xsl:when test=".='sh'"><xsl:value-of select="$Language-sh"/></xsl:when>
					<xsl:when test=".='si'"><xsl:value-of select="$Language-si"/></xsl:when>
					<xsl:when test=".='sk'"><xsl:value-of select="$Language-sk"/></xsl:when>
					<xsl:when test=".='sl'"><xsl:value-of select="$Language-sl"/></xsl:when>
					<xsl:when test=".='sm'"><xsl:value-of select="$Language-sm"/></xsl:when>
					<xsl:when test=".='sn'"><xsl:value-of select="$Language-sn"/></xsl:when>
					<xsl:when test=".='so'"><xsl:value-of select="$Language-so"/></xsl:when>
					<xsl:when test=".='sq'"><xsl:value-of select="$Language-sq"/></xsl:when>
					<xsl:when test=".='sr'"><xsl:value-of select="$Language-sr"/></xsl:when>
					<xsl:when test=".='ss'"><xsl:value-of select="$Language-ss"/></xsl:when>
					<xsl:when test=".='st'"><xsl:value-of select="$Language-st"/></xsl:when>
					<xsl:when test=".='su'"><xsl:value-of select="$Language-su"/></xsl:when>
					<xsl:when test=".='sv'"><xsl:value-of select="$Language-sv"/></xsl:when>
					<xsl:when test=".='sw'"><xsl:value-of select="$Language-sw"/></xsl:when>
					<xsl:when test=".='ta'"><xsl:value-of select="$Language-ta"/></xsl:when>
					<xsl:when test=".='te'"><xsl:value-of select="$Language-te"/></xsl:when>
					<xsl:when test=".='tg'"><xsl:value-of select="$Language-tg"/></xsl:when>
					<xsl:when test=".='th'"><xsl:value-of select="$Language-th"/></xsl:when>
					<xsl:when test=".='ti'"><xsl:value-of select="$Language-ti"/></xsl:when>
					<xsl:when test=".='tk'"><xsl:value-of select="$Language-tk"/></xsl:when>
					<xsl:when test=".='tl'"><xsl:value-of select="$Language-tl"/></xsl:when>
					<xsl:when test=".='tn'"><xsl:value-of select="$Language-tn"/></xsl:when>
					<xsl:when test=".='to'"><xsl:value-of select="$Language-to"/></xsl:when>
					<xsl:when test=".='tr'"><xsl:value-of select="$Language-tr"/></xsl:when>
					<xsl:when test=".='ts'"><xsl:value-of select="$Language-ts"/></xsl:when>
					<xsl:when test=".='tt'"><xsl:value-of select="$Language-tt"/></xsl:when>
					<xsl:when test=".='tw'"><xsl:value-of select="$Language-tw"/></xsl:when>
					<xsl:when test=".='ug'"><xsl:value-of select="$Language-ug"/></xsl:when>
					<xsl:when test=".='uk'"><xsl:value-of select="$Language-uk"/></xsl:when>
					<xsl:when test=".='ur'"><xsl:value-of select="$Language-ur"/></xsl:when>
					<xsl:when test=".='uz'"><xsl:value-of select="$Language-uz"/></xsl:when>
					<xsl:when test=".='vi'"><xsl:value-of select="$Language-vi"/></xsl:when>
					<xsl:when test=".='vo'"><xsl:value-of select="$Language-vo"/></xsl:when>
					<xsl:when test=".='wo'"><xsl:value-of select="$Language-wo"/></xsl:when>
					<xsl:when test=".='xh'"><xsl:value-of select="$Language-xh"/></xsl:when>
					<xsl:when test=".='yi'"><xsl:value-of select="$Language-yi"/></xsl:when>
					<xsl:when test=".='yo'"><xsl:value-of select="$Language-yo"/></xsl:when>
					<xsl:when test=".='za'"><xsl:value-of select="$Language-za"/></xsl:when>
					<xsl:when test=".='zh'"><xsl:value-of select="$Language-zh"/></xsl:when>
					<xsl:when test=".='zu'"><xsl:value-of select="$Language-zu"/></xsl:when>		
					<xsl:otherwise>-->
						<xsl:value-of select="."/>
						<!--<b style="color:red">-<xsl:value-of select="@Language"/>-</b>
					</xsl:otherwise>
				</xsl:choose>-->
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute LocationQualifier     *** -->

<xsl:template match="@LocationQualifier">
	<xsl:choose>
		<xsl:when test=".='DistributionCentre'"><xsl:value-of select="$LocationQualifier-DistributionCentre"/></xsl:when>
		<xsl:when test=".='Mill'"><xsl:value-of select="$LocationQualifier-Mill"/></xsl:when>
		<xsl:when test=".='OriginAfterLoadingOnEquipment'"><xsl:value-of select="$LocationQualifier-OriginAfterLoadingOnEquipment"/></xsl:when>
		<xsl:when test=".='OriginShippingPoint'"><xsl:value-of select="$LocationQualifier-OriginShippingPoint"/></xsl:when>
		<xsl:when test=".='OnVesselFOBPoint'"><xsl:value-of select="$LocationQualifier-OnVesselFOBPoint"/></xsl:when>
		<xsl:when test=".='Plant'"><xsl:value-of select="$LocationQualifier-Plant"/></xsl:when>
		<xsl:when test=".='Warehouse'"><xsl:value-of select="$LocationQualifier-Warehouse"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@LocationQualifier"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute MailAttachmentType     *** -->

<xsl:template match="@MailAttachmentType">
	<xsl:choose>
		<xsl:when test=".='BillOfLading'"><xsl:value-of select="$MailAttachmentType-BillOfLading"/></xsl:when>
		<xsl:when test=".='CMR'"><xsl:value-of select="$MailAttachmentType-CMR"/></xsl:when>
		<xsl:when test=".='Photo'"><xsl:value-of select="$MailAttachmentType-Photo"/></xsl:when>
		<xsl:when test=".='Sample'"><xsl:value-of select="$MailAttachmentType-Sample"/></xsl:when>
		<xsl:when test=".='Waybill'"><xsl:value-of select="$MailAttachmentType-Waybill"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@MailAttachmentType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute MessageName     *** -->

<xsl:template match="@MessageName">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='PurchaseOrder'"><xsl:value-of select="$MessageName-PurchaseOrder"/></xsl:when>
				<xsl:when test=".='OrderConfirmation'"><xsl:value-of select="$MessageName-OrderConfirmation"/></xsl:when>
				<xsl:when test=".='CallOff'"><xsl:value-of select="$MessageName-CallOff"/></xsl:when>
				<xsl:when test=".='DeliveryMessage'"><xsl:value-of select="$MessageName-DeliveryMessage"/></xsl:when>
				<xsl:when test=".='Invoice'"><xsl:value-of select="$MessageName-Invoice"/></xsl:when>
				<xsl:when test=".='Other'"><xsl:value-of select="$MessageName-Other"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@MessageName"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute Method     *** -->

<xsl:template match="@Method">
	<xsl:choose>
		<xsl:when test=".='CollectFreightCreditedBackToCustomer'"><xsl:value-of select="$Method-CollectFreightCreditedBackToCustomer"/></xsl:when>
		<xsl:when test=".='CustomerPickupBackhaul'"><xsl:value-of select="$Method-CustomerPickupBackhaul"/></xsl:when>
		<xsl:when test=".='PrepaidButChargedToCustomer'"><xsl:value-of select="$Method-PrepaidButChargedToCustomer"/></xsl:when>
		<xsl:when test=".='PrepaidBySeller'"><xsl:value-of select="$Method-PrepaidBySeller"/></xsl:when>
		<xsl:when test=".='Pickup'"><xsl:value-of select="$Method-Pickup"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@Method"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute MixProductIndicator     *** -->

<xsl:template match="@MixProductIndicator">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$MixProductIndicator-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$MixProductIndicator-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@MixProductIndicator"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute MixedProductPalletIndicator     *** -->

<xsl:template match="@MixedProductPalletIndicator">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$MixedProductPalletIndicator-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$MixedProductPalletIndicator-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@MixedProductPalletIndicator"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute OrderStatusCode     *** -->

<xsl:template match="@OrderStatusCode">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='ActiveFree'"><xsl:value-of select="$OrderStatusCode-ActiveFree"/></xsl:when>
				<xsl:when test=".='ActiveHold'"><xsl:value-of select="$OrderStatusCode-ActiveHold"/></xsl:when>
				<xsl:when test=".='Cancelled'"><xsl:value-of select="$OrderStatusCode-Cancelled"/></xsl:when>
				<xsl:when test=".='Complete'"><xsl:value-of select="$OrderStatusCode-Complete"/></xsl:when>
				<xsl:when test=".='Delayed'"><xsl:value-of select="$OrderStatusCode-Delayed"/></xsl:when>
				<xsl:when test=".='FinalPlanning'"><xsl:value-of select="$OrderStatusCode-FinalPlanning"/></xsl:when>
				<xsl:when test=".='Invoiced'"><xsl:value-of select="$OrderStatusCode-Invoiced"/></xsl:when>
				<xsl:when test=".='Loaded'"><xsl:value-of select="$OrderStatusCode-Loaded"/></xsl:when>
				<xsl:when test=".='NotReceived'"><xsl:value-of select="$OrderStatusCode-NotReceived"/></xsl:when>
				<xsl:when test=".='OrderLineConfirmed'"><xsl:value-of select="$OrderStatusCode-OrderLineConfirmed"/></xsl:when>
				<xsl:when test=".='Packed'"><xsl:value-of select="$OrderStatusCode-Packed"/></xsl:when>
				<xsl:when test=".='PartiallyShipped'"><xsl:value-of select="$OrderStatusCode-PartiallyShipped"/></xsl:when>
				<xsl:when test=".='Pending'"><xsl:value-of select="$OrderStatusCode-Pending"/></xsl:when>
				<xsl:when test=".='Planned'"><xsl:value-of select="$OrderStatusCode-Planned"/></xsl:when>
				<xsl:when test=".='ProductionComplete'"><xsl:value-of select="$OrderStatusCode-ProductionComplete"/></xsl:when>
				<xsl:when test=".='ProductionStarted'"><xsl:value-of select="$OrderStatusCode-ProductionStarted"/></xsl:when>
				<xsl:when test=".='Received'"><xsl:value-of select="$OrderStatusCode-Received"/></xsl:when>
				<xsl:when test=".='ReservedInProductionPlanningSystem'"><xsl:value-of select="$OrderStatusCode-ReservedInProductionPlanningSystem"/></xsl:when>
				<xsl:when test=".='Scheduled'"><xsl:value-of select="$OrderStatusCode-Scheduled"/></xsl:when>
				<xsl:when test=".='ShipmentComplete'"><xsl:value-of select="$OrderStatusCode-ShipmentComplete"/></xsl:when>
				<xsl:when test=".='Shuttled'"><xsl:value-of select="$OrderStatusCode-Shuttled"/></xsl:when>
				<xsl:when test=".='Staged'"><xsl:value-of select="$OrderStatusCode-Staged"/></xsl:when>
				<xsl:when test=".='Transferred'"><xsl:value-of select="$OrderStatusCode-Transferred"/></xsl:when>
				<xsl:when test=".='TransferredToMillSystem'"><xsl:value-of select="$OrderStatusCode-TransferredToMillSystem"/></xsl:when>
				<xsl:when test=".='Unscheduled'"><xsl:value-of select="$OrderStatusCode-Unscheduled"/></xsl:when>
				<xsl:when test=".='Unshipped'"><xsl:value-of select="$OrderStatusCode-Unshipped"/></xsl:when>				
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@OrderStatusCode"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute OrderStatusType     *** -->

<xsl:template match="@OrderStatusType">
	<xsl:choose>
		<xsl:when test=".='ByProduct'"><xsl:value-of select="$OrderStatusType-ByProduct"/></xsl:when>
		<xsl:when test=".='ByPurchaseOrder'"><xsl:value-of select="$OrderStatusType-ByPurchaseOrder"/></xsl:when>
		<xsl:when test=".='BySupplierOrderNumber'"><xsl:value-of select="$OrderStatusType-BySupplierOrderNumber"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrderStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute OrderConfirmationHeaderStatusType     *** -->

<xsl:template match="@OrderConfirmationHeaderStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$OrderConfirmationHeaderStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$OrderConfirmationHeaderStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Pending'"><xsl:value-of select="$OrderConfirmationHeaderStatusType-Pending"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$OrderConfirmationHeaderStatusType-Rejected"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$OrderConfirmationHeaderStatusType-NoAction"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrderConfirmationHeaderStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute OrderConfirmationLineItemStatusType     *** -->

<xsl:template match="@OrderConfirmationLineItemStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Pending'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-Pending"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-Rejected"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$OrderConfirmationLineItemStatusType-NoAction"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrderConfirmationLineItemStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute OrderConfirmationReferenceType     *** -->

<xsl:template match="@OrderConfirmationReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='AccountNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-AccountNumber"/></xsl:when>
		<xsl:when test=".='Author'"><xsl:value-of select="$OrderConfirmationReferenceType-Author"/></xsl:when>
		<xsl:when test=".='BookLanguage'"><xsl:value-of select="$OrderConfirmationReferenceType-BookLanguage"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='Copyright'"><xsl:value-of select="$OrderConfirmationReferenceType-Copyright"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='Edition'"><xsl:value-of select="$OrderConfirmationReferenceType-Edition"/></xsl:when>
		<xsl:when test=".='EndCallOffDate'"><xsl:value-of select="$OrderConfirmationReferenceType-EndCallOffDate"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISBN10'"><xsl:value-of select="$OrderConfirmationReferenceType-ISBN10"/></xsl:when>
		<xsl:when test=".='ISBN10Dash'"><xsl:value-of select="$OrderConfirmationReferenceType-ISBN10Dash"/></xsl:when>
		<xsl:when test=".='ISBN13'"><xsl:value-of select="$OrderConfirmationReferenceType-ISBN13"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$OrderConfirmationReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$OrderConfirmationReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='MillSalesOfficeNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-MillSalesOfficeNumber"/></xsl:when>
		<xsl:when test=".='OriginalPurchaseOrderNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-OriginalPurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='PackageNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-PackageNumber"/></xsl:when>
		<xsl:when test=".='PriceList'"><xsl:value-of select="$OrderConfirmationReferenceType-PriceList"/></xsl:when>
		<xsl:when test=".='PupilsTeachers'"><xsl:value-of select="$OrderConfirmationReferenceType-PupilsTeachers"/></xsl:when>
		<xsl:when test=".='ReleaseNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-ReleaseNumber"/></xsl:when>
		<xsl:when test=".='RFQLineItemNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-RFQLineItemNumber"/></xsl:when>
		<xsl:when test=".='RFQNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-RFQNumber"/></xsl:when>
		<xsl:when test=".='RunNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-RunNumber"/></xsl:when>
		<xsl:when test=".='SchoolGrade'"><xsl:value-of select="$OrderConfirmationReferenceType-SchoolGrade"/></xsl:when>
		<xsl:when test=".='SchoolGradeLevel'"><xsl:value-of select="$OrderConfirmationReferenceType-SchoolGradeLevel"/></xsl:when>
		<xsl:when test=".='ShippingInstructionsLineItemNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-ShippingInstructionsLineItemNumber"/></xsl:when>
		<xsl:when test=".='ShippingInstructionsNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-ShippingInstructionsNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$OrderConfirmationReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$OrderConfirmationReferenceType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrderConfirmationReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute OrderConfirmationStatusType     *** -->

<xsl:template match="@OrderConfirmationStatusType">
	<xsl:choose>
		<xsl:when test=".='Accepted'"><xsl:value-of select="$OrderConfirmationStatusType-Accepted"/></xsl:when>
		<xsl:when test=".='Amended'"><xsl:value-of select="$OrderConfirmationStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$OrderConfirmationStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Rejected'"><xsl:value-of select="$OrderConfirmationStatusType-Rejected"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrderConfirmationStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute OrganisationUnitType     *** -->

<xsl:template match="@OrganisationUnitType">
	<xsl:choose>
		<xsl:when test=".='StorageLocation'"><xsl:value-of select="$OrganisationUnitType-StorageLocation"/></xsl:when>
		<xsl:when test=".='Department'"><xsl:value-of select="$OrganisationUnitType-Department"/></xsl:when>
		<xsl:when test=".='Dock'"><xsl:value-of select="$OrganisationUnitType-Dock"/></xsl:when>
		<xsl:when test=".='Division'"><xsl:value-of select="$OrganisationUnitType-Division"/></xsl:when>
		<xsl:when test=".='Location'"><xsl:value-of select="$OrganisationUnitType-Location"/></xsl:when>
		<xsl:when test=".='Region'"><xsl:value-of select="$OrganisationUnitType-Region"/></xsl:when>
		<xsl:when test=".='Terminal'"><xsl:value-of select="$OrganisationUnitType-Terminal"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$OrganisationUnitType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@OrganisationUnitType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PackageType     *** -->

<xsl:template match="@PackageType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='Box'"><xsl:value-of select="$PackageType-Box"/></xsl:when>
				<xsl:when test=".='Pallet'"><xsl:value-of select="$PackageType-Pallet"/></xsl:when>
				<xsl:when test=".='PulpUnit'"><xsl:value-of select="$PackageType-PulpUnit"/></xsl:when>
				<xsl:when test=".='ReelPackage'"><xsl:value-of select="$PackageType-Reel"/></xsl:when>
				<xsl:when test=".='UnformedPulp'"><xsl:value-of select="$PackageType-UnformedPulp"/></xsl:when>
				<xsl:when test=".='Vehicle'"><xsl:value-of select="$PackageType-Vehicle"/></xsl:when>		
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@PackageType"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PalletAdditionsType     *** -->

<xsl:template match="@PalletAdditionsType">
	<xsl:if test="string-length(.) !='0'">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='FullPerimeter'"><xsl:value-of select="$PalletAdditionsType-FullPerimeter"/></xsl:when>
					<xsl:when test=".='FungicideTreatment'"><xsl:value-of select="$PalletAdditionsType-FungicideTreatment"/></xsl:when>
					<xsl:when test=".='LongSlats'"><xsl:value-of select="$PalletAdditionsType-LongSlats"/></xsl:when>
					<xsl:when test=".='NoBlockOffset'"><xsl:value-of select="$PalletAdditionsType-NoBlockOffset"/></xsl:when>
					<xsl:when test=".='Reinforced'"><xsl:value-of select="$PalletAdditionsType-Reinforced"/></xsl:when>
					<xsl:when test=".='TrimmedEdges'"><xsl:value-of select="$PalletAdditionsType-TrimmedEdges"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PalletAdditionsType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute PalletCoverType     *** -->

<xsl:template match="@PalletCoverType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='MetalPlateWithPE'"><xsl:value-of select="$PalletCoverType-MetalPlateWithPE"/></xsl:when>
					<xsl:when test=".='WithoutPE'"><xsl:value-of select="$PalletCoverType-WithoutPE"/></xsl:when>
					<xsl:when test=".='WithPE'"><xsl:value-of select="$PalletCoverType-WithPE"/></xsl:when>
					<xsl:when test=".='WithPEUnderCover'"><xsl:value-of select="$PalletCoverType-WithPEUnderCover"/></xsl:when>
					<xsl:when test=".='WoodStripWithPE'"><xsl:value-of select="$PalletCoverType-WoodStripWithPE"/></xsl:when>
					<xsl:when test=".='WoodStripWithoutPE'"><xsl:value-of select="$PalletCoverType-WoodStripWithoutPE"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PalletCoverType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PalletLedgeType     *** -->

<xsl:template match="@PalletLedgeType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='LongWay'"><xsl:value-of select="$PalletLedgeType-LongWay"/></xsl:when>
					<xsl:when test=".='LongWayWithBelt'"><xsl:value-of select="$PalletLedgeType-LongWayWithBelt"/></xsl:when>
					<xsl:when test=".='ShortWay'"><xsl:value-of select="$PalletLedgeType-ShortWay"/></xsl:when>
					<xsl:when test=".='ShortWayWithBelt'"><xsl:value-of select="$PalletLedgeType-ShortWayWithBelt"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PalletLedgeType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PalletTopType     *** -->

<xsl:template match="@PalletTopType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='CartonboardCover'"><xsl:value-of select="$PalletTopType-CartonboardCover"/></xsl:when>
					<xsl:when test=".='Chipboard'"><xsl:value-of select="$PalletTopType-Chipboard"/></xsl:when>
					<xsl:when test=".='Corrugated'"><xsl:value-of select="$PalletTopType-Corrugated"/></xsl:when>
					<xsl:when test=".='EdgeProtection'"><xsl:value-of select="$PalletTopType-EdgeProtection"/></xsl:when>
					<xsl:when test=".='FiveStripsOfWood'"><xsl:value-of select="$PalletTopType-FiveStripsOfWood"/></xsl:when>
					<xsl:when test=".='FullyCoverThreeCrossBoards'"><xsl:value-of select="$PalletTopType-FullyCoverThreeCrossBoards"/></xsl:when>
					<xsl:when test=".='FungicTreatWoodFrame'"><xsl:value-of select="$PalletTopType-FungicTreatWoodFrame"/></xsl:when>
					<xsl:when test=".='OneStripOfWood'"><xsl:value-of select="$PalletTopType-OneStripOfWood"/></xsl:when>
					<xsl:when test=".='PlywoodCover'"><xsl:value-of select="$PalletTopType-PlywoodCover"/></xsl:when>
					<xsl:when test=".='TwoStripsOfWood'"><xsl:value-of select="$PalletTopType-TwoStripsOfWood"/></xsl:when>
					<xsl:when test=".='ThreeStripsOfWood'"><xsl:value-of select="$PalletTopType-ThreeStripsOfWood"/></xsl:when>
					<xsl:when test=".='WoodenFrame'"><xsl:value-of select="$PalletTopType-WoodenFrame"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PalletTopType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PalletType     *** -->

<xsl:template match="@PalletType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Euro'"><xsl:value-of select="$PalletType-Euro"/></xsl:when>
					<xsl:when test=".='Export'"><xsl:value-of select="$PalletType-Export"/></xsl:when>
					<xsl:when test=".='NonStop'"><xsl:value-of select="$PalletType-NonStop"/></xsl:when>
					<xsl:when test=".='Twinned'"><xsl:value-of select="$PalletType-Twinned"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PalletType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PartyIdentifierType     *** -->

<xsl:template match="@PartyIdentifierType">
	<xsl:choose>
		<xsl:when test=".='ABINumber'"><xsl:value-of select="$PartyIdentifierType-ABINumber"/></xsl:when>
		<xsl:when test=".='ABNNumber'"><xsl:value-of select="$PartyIdentifierType-ABNNumber"/></xsl:when>
		<xsl:when test=".='AFPA'"><xsl:value-of select="$PartyIdentifierType-AFPA"/></xsl:when>
		<xsl:when test=".='AssignedByBuyer'"><xsl:value-of select="$PartyIdentifierType-AssignedByBuyer"/></xsl:when>
		<xsl:when test=".='AssignedBySeller'"><xsl:value-of select="$PartyIdentifierType-AssignedBySeller"/></xsl:when>
		<xsl:when test=".='BankIdentificationCode'"><xsl:value-of select="$PartyIdentifierType-BankIdentificationCode"/></xsl:when>
		<xsl:when test=".='CABNumber'"><xsl:value-of select="$PartyIdentifierType-CABNumber"/></xsl:when>
		<xsl:when test=".='ChamberOfCommerceRegistrationNumber'"><xsl:value-of select="$PartyIdentifierType-ChamberOfCommerceRegistrationNumber"/></xsl:when>
		<xsl:when test=".='DunsNumber'"><xsl:value-of select="$PartyIdentifierType-DunsNumber"/></xsl:when>
		<xsl:when test=".='Duns4Number'"><xsl:value-of select="$PartyIdentifierType-Duns4Number"/></xsl:when>
		<xsl:when test=".='EANNumber'"><xsl:value-of select="$PartyIdentifierType-EANNumber"/></xsl:when>
		<xsl:when test=".='GlobalLocationNumber'"><xsl:value-of select="$PartyIdentifierType-GlobalLocationNumber"/></xsl:when>
		<xsl:when test=".='papiNetGlobalPartyIdentifier'"><xsl:value-of select="$PartyIdentifierType-papiNetGlobalPartyIdentifier"/></xsl:when>
		<xsl:when test=".='PayerAccountNumber'"><xsl:value-of select="$PartyIdentifierType-PayerAccountNumber"/></xsl:when>
		<xsl:when test=".='PayeeAccountNumber'"><xsl:value-of select="$PartyIdentifierType-PayeeAccountNumber"/></xsl:when>
		<xsl:when test=".='PayeeFinancialInstitution'"><xsl:value-of select="$PartyIdentifierType-PayeeFinancialInstitution"/></xsl:when>
		<xsl:when test=".='PayerFinancialInstitution'"><xsl:value-of select="$PartyIdentifierType-PayerFinancialInstitution"/></xsl:when>
		<xsl:when test=".='RegisterOfCompaniesSubscriptionNumber'"><xsl:value-of select="$PartyIdentifierType-RegisterOfCompaniesSubscriptionNumber"/></xsl:when>
		<xsl:when test=".='StandardAddressNumber'"><xsl:value-of select="$PartyIdentifierType-StandardAddressNumber"/></xsl:when>
		<xsl:when test=".='StandardCarrierAlphaCode'"><xsl:value-of select="$PartyIdentifierType-StandardCarrierAlphaCode"/></xsl:when>
		<xsl:when test=".='StockCapital'"><xsl:value-of select="$PartyIdentifierType-StockCapital"/></xsl:when>
		<xsl:when test=".='SWIFT'"><xsl:value-of select="$PartyIdentifierType-SWIFT"/></xsl:when>
		<xsl:when test=".='TaxIdentifier'"><xsl:value-of select="$PartyIdentifierType-TaxIdentifier"/></xsl:when>
		<xsl:when test=".='VATIdentificationNumber'"><xsl:value-of select="$PartyIdentifierType-VATIdentificationNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$PartyIdentifierType-Other"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PartyIdentifierType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PartyType     *** -->

<xsl:template match="@PartyType">
	<xsl:choose>
		<xsl:when test=".='Bank'"><xsl:value-of select="$PartyType-Bank"/></xsl:when>
		<xsl:when test=".='BillTo'"><xsl:value-of select="$PartyType-BillTo"/></xsl:when>
		<xsl:when test=".='Broker'"><xsl:value-of select="$PartyType-Broker"/></xsl:when>
		<xsl:when test=".='Buyer'"><xsl:value-of select="$PartyType-Buyer"/></xsl:when>
		<xsl:when test=".='Carrier'"><xsl:value-of select="$PartyType-Carrier"/></xsl:when>
		<xsl:when test=".='CarrierAssignmentResponsible'"><xsl:value-of select="$PartyType-CarrierAssignmentResponsible"/></xsl:when>
		<xsl:when test=".='ComponentVendor'"><xsl:value-of select="$PartyType-ComponentVendor"/></xsl:when>
		<xsl:when test=".='Consuming'"><xsl:value-of select="$PartyType-Consuming"/></xsl:when>
		<xsl:when test=".='CreditDepartment'"><xsl:value-of select="$PartyType-CreditDepartment"/></xsl:when>
		<xsl:when test=".='CrossDock'"><xsl:value-of select="$PartyType-CrossDock"/></xsl:when>
		<xsl:when test=".='CustomerFacility'"><xsl:value-of select="$PartyType-CustomerFacility"/></xsl:when>
		<xsl:when test=".='CustomerStock'"><xsl:value-of select="$PartyType-CustomerStock"/></xsl:when>
		<xsl:when test=".='EndUser'"><xsl:value-of select="$PartyType-EndUser"/></xsl:when>
		<xsl:when test=".='Forwarder'"><xsl:value-of select="$PartyType-Forwarder"/></xsl:when>
		<xsl:when test=".='Insurer'"><xsl:value-of select="$PartyType-Insurer"/></xsl:when>
		<xsl:when test=".='Merchant'"><xsl:value-of select="$PartyType-Merchant"/></xsl:when>
		<xsl:when test=".='Mill'"><xsl:value-of select="$PartyType-Mill"/></xsl:when>
		<xsl:when test=".='OrderParty'"><xsl:value-of select="$PartyType-OrderParty"/></xsl:when>
		<xsl:when test=".='Port'"><xsl:value-of select="$PartyType-Port"/></xsl:when>
		<xsl:when test=".='PrinterFacility'"><xsl:value-of select="$PartyType-PrinterFacility"/></xsl:when>
		<xsl:when test=".='ProFormaInvoice'"><xsl:value-of select="$PartyType-ProFormaInvoice"/></xsl:when>
		<xsl:when test=".='RemitTo'"><xsl:value-of select="$PartyType-RemitTo"/></xsl:when>
		<xsl:when test=".='Requestor'"><xsl:value-of select="$PartyType-Requestor"/></xsl:when>
		<xsl:when test=".='SalesAgent'"><xsl:value-of select="$PartyType-SalesAgent"/></xsl:when>
		<xsl:when test=".='SalesOffice'"><xsl:value-of select="$PartyType-SalesOffice"/></xsl:when>
		<xsl:when test=".='Seller'"><xsl:value-of select="$PartyType-Seller"/></xsl:when>
		<xsl:when test=".='ShipFromLocation'"><xsl:value-of select="$PartyType-ShipFromLocation"/></xsl:when>
		<xsl:when test=".='ShipTo'"><xsl:value-of select="$PartyType-ShipTo"/></xsl:when>
		<xsl:when test=".='Supplier'"><xsl:value-of select="$PartyType-Supplier"/></xsl:when>
		<xsl:when test=".='Terminal'"><xsl:value-of select="$PartyType-Terminal"/></xsl:when>
		<xsl:when test=".='TerminalOperator'"><xsl:value-of select="$PartyType-TerminalOperator"/></xsl:when>
		<xsl:when test=".='Warehouse'"><xsl:value-of select="$PartyType-Warehouse"/></xsl:when>
		<xsl:when test=".='WillAdvise'"><xsl:value-of select="$PartyType-WillAdvise"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$PartyType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PartyType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PeriodType     *** -->

<xsl:template match="@PeriodType">
	<xsl:choose>
		<xsl:when test=".='Day'"><xsl:value-of select="$PeriodType-Day"/></xsl:when>
		<xsl:when test=".='FinancialPeriodBeginning'"><xsl:value-of select="$PeriodType-FinancialPeriodBeginning"/></xsl:when>
		<xsl:when test=".='MonthBeginning'"><xsl:value-of select="$PeriodType-MonthBeginning"/></xsl:when>
		<xsl:when test=".='Period'"><xsl:value-of select="$PeriodType-Period"/></xsl:when>
		<xsl:when test=".='QuarterBeginning'"><xsl:value-of select="$PeriodType-QuarterBeginning"/></xsl:when>
		<xsl:when test=".='WeekBeginning'"><xsl:value-of select="$PeriodType-WeekBeginning"/></xsl:when>
		<xsl:when test=".='WeekNumber'"><xsl:value-of select="$PeriodType-WeekNumber"/></xsl:when>
		<xsl:when test=".='YearBeginning'"><xsl:value-of select="$PeriodType-YearBeginning"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PeriodType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PriceQuantityBasis     *** -->

<xsl:template match="@PriceQuantityBasis">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='AirDryWeight'"><xsl:value-of select="$PriceQuantityBasis-AirDryWeight"/></xsl:when>
					<xsl:when test=".='Area'"><xsl:value-of select="$PriceQuantityBasis-Area"/></xsl:when>
					<xsl:when test=".='BoneDry'"><xsl:value-of select="$PriceQuantityBasis-BoneDry"/></xsl:when>
					<xsl:when test=".='Count'"><xsl:value-of select="$PriceQuantityBasis-Count"/></xsl:when>
					<xsl:when test=".='GrossWeight'"><xsl:value-of select="$PriceQuantityBasis-GrossWeight"/></xsl:when>
					<xsl:when test=".='Length'"><xsl:value-of select="$PriceQuantityBasis-Length"/></xsl:when>
					<xsl:when test=".='NetWeight'"><xsl:value-of select="$PriceQuantityBasis-NetWeight"/></xsl:when>
					<xsl:when test=".='NetNetWeight'"><xsl:value-of select="$PriceQuantityBasis-NetNetWeight"/></xsl:when>
					<xsl:when test=".='NominalWeight'"><xsl:value-of select="$PriceQuantityBasis-NominalWeight"/></xsl:when>
					<xsl:when test=".='Percent'"><xsl:value-of select="$PriceQuantityBasis-Percent"/></xsl:when>
					<xsl:when test=".='TareWeight'"><xsl:value-of select="$PriceQuantityBasis-TareWeight"/></xsl:when>
					<xsl:when test=".='Time'"><xsl:value-of select="$PriceQuantityBasis-Time"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PriceQuantityBasis"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PriceTaxBasis     *** -->

<xsl:template match="@PriceTaxBasis">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$PriceTaxBasis-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$PriceTaxBasis-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PriceTaxBasis"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PrintType     *** -->

<xsl:template match="@PrintType">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='ColdsetOffset'"><xsl:value-of select="$PrintType-ColdsetOffset"/></xsl:when>
					<xsl:when test=".='ContinuousForms'"><xsl:value-of select="$PrintType-ContinuousForms"/></xsl:when>
					<xsl:when test=".='Digital'"><xsl:value-of select="$PrintType-Digital"/></xsl:when>
					<xsl:when test=".='Flexography'"><xsl:value-of select="$PrintType-Flexography"/></xsl:when>
					<xsl:when test=".='FoilPrint'"><xsl:value-of select="$PrintType-FoilPrint"/></xsl:when>
					<xsl:when test=".='Forms'"><xsl:value-of select="$PrintType-Forms"/></xsl:when>
					<xsl:when test=".='Gravure'"><xsl:value-of select="$PrintType-Gravure"/></xsl:when>
					<xsl:when test=".='HeatSetOffset'"><xsl:value-of select="$PrintType-HeatSetOffset"/></xsl:when>
					<xsl:when test=".='InkJet'"><xsl:value-of select="$PrintType-InkJet"/></xsl:when>
					<xsl:when test=".='InstantOffset'"><xsl:value-of select="$PrintType-InstantOffset"/></xsl:when>
					<xsl:when test=".='Laser'"><xsl:value-of select="$PrintType-Laser"/></xsl:when>
					<xsl:when test=".='Letterpress'"><xsl:value-of select="$PrintType-Letterpress"/></xsl:when>
					<xsl:when test=".='LightPrint'"><xsl:value-of select="$PrintType-LightPrint"/></xsl:when>
					<xsl:when test=".='MiniWeb'"><xsl:value-of select="$PrintType-MiniWeb"/></xsl:when>
					<xsl:when test=".='RotoFlexography'"><xsl:value-of select="$PrintType-RotoFlexography"/></xsl:when>
					<xsl:when test=".='RotoGravure'"><xsl:value-of select="$PrintType-RotoGravure"/></xsl:when>
					<xsl:when test=".='RotoLetterpress'"><xsl:value-of select="$PrintType-RotoLetterpress"/></xsl:when>
					<xsl:when test=".='RotoSilkScreen'"><xsl:value-of select="$PrintType-RotoSilkScreen"/></xsl:when>
					<xsl:when test=".='SheetfedOffset'"><xsl:value-of select="$PrintType-SheetfedOffset"/></xsl:when>
					<xsl:when test=".='SilkScreen'"><xsl:value-of select="$PrintType-SilkScreen"/></xsl:when>
					<xsl:when test=".='WebOffset'"><xsl:value-of select="$PrintType-WebOffset"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@PrintType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute ProductAttributesReferenceType     *** -->

<xsl:template match="@ProductAttributesReferenceType">
	<xsl:choose>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$ProductAttributesReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='SupplierProductAttributesIdentifier'"><xsl:value-of select="$ProductAttributesReferenceType-SupplierProductAttributesIdentifier"/></xsl:when>
		<xsl:when test=".='VendorProductAttributesIdentifier'"><xsl:value-of select="$ProductAttributesReferenceType-VendorProductAttributesIdentifier"/></xsl:when>
		<xsl:when test=".='MarketplaceProductAttributesIdentifier'"><xsl:value-of select="$ProductAttributesReferenceType-MarketplaceProductAttributesIdentifier"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$ProductAttributesReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ProductAttributesReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ProductBasisSizeType     *** -->

<xsl:template match="@ProductBasisSizeType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				&#160;
			</td>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>		
					<xsl:when test=".='GramsPerSquareMeter'"><xsl:value-of select="$ProductBasisSizeType-GramsPerSquareMeter"/></xsl:when>
					<xsl:when test=".='16x18'"><xsl:value-of select="$ProductBasisSizeType-16x18"/></xsl:when>
					<xsl:when test=".='17x22'"><xsl:value-of select="$ProductBasisSizeType-17x22"/></xsl:when>
					<xsl:when test=".='20x26'"><xsl:value-of select="$ProductBasisSizeType-20x26"/></xsl:when>
					<xsl:when test=".='20x30'"><xsl:value-of select="$ProductBasisSizeType-20x30"/></xsl:when>
					<xsl:when test=".='22.5x22.5'"><xsl:value-of select="$ProductBasisSizeType-22.5x22.5"/></xsl:when>
					<xsl:when test=".='22.5x28.5'"><xsl:value-of select="$ProductBasisSizeType-22.5x28.5"/></xsl:when>
					<xsl:when test=".='24x26'"><xsl:value-of select="$ProductBasisSizeType-24x26"/></xsl:when>
					<xsl:when test=".='24x36'"><xsl:value-of select="$ProductBasisSizeType-24x36"/></xsl:when>
					<xsl:when test=".='25x38'"><xsl:value-of select="$ProductBasisSizeType-25x38"/></xsl:when>
					<xsl:when test=".='25x40'"><xsl:value-of select="$ProductBasisSizeType-25x40"/></xsl:when>
					<xsl:when test=".='25.5x28.5'"><xsl:value-of select="$ProductBasisSizeType-25.5x28.5"/></xsl:when>
					<xsl:when test=".='25.5x30.5'"><xsl:value-of select="$ProductBasisSizeType-25.5x30.5"/></xsl:when>
					<xsl:when test=".='35.5x30.5'"><xsl:value-of select="$ProductBasisSizeType-35.5x30.5"/></xsl:when>
					<xsl:when test=".='1000SqFt'"><xsl:value-of select="$ProductBasisSizeType-1000SqFt"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ProductBasisSizeType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ProductionRunReferenceType     *** -->

<xsl:template match="@ProductionRunReferenceType">
	<xsl:choose>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$ProductionRunReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$ProductionRunReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerJobNumber'"><xsl:value-of select="$ProductionRunReferenceType-CustomerJobNumber"/></xsl:when>
		<xsl:when test=".='CustomerJobTitle'"><xsl:value-of select="$ProductionRunReferenceType-CustomerJobTitle"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$ProductionRunReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$ProductionRunReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderLineItemNumber'"><xsl:value-of select="$ProductionRunReferenceType-PurchaseOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$ProductionRunReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$ProductionRunReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierVoyageNumber'"><xsl:value-of select="$ProductionRunReferenceType-SupplierVoyageNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$ProductionRunReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ProductionRunReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ProductIdentifierType     *** -->

<xsl:template match="@ProductIdentifierType">
	<tr>
		<td class="LineItemStyle6" valign="top">
			<xsl:text>[</xsl:text>
			<xsl:choose>
				<xsl:when test=".='BrandName'"><xsl:value-of select="$ProductIdentifierType-BrandName"/></xsl:when>
				<xsl:when test=".='CatalogueNumber'"><xsl:value-of select="$ProductIdentifierType-CatalogueNumber"/></xsl:when>
				<xsl:when test=".='CustomsTariffNumber'"><xsl:value-of select="$ProductIdentifierType-CustomsTariffNumber"/></xsl:when>
				<xsl:when test=".='EAN8'"><xsl:value-of select="$ProductIdentifierType-EAN8"/></xsl:when>
				<xsl:when test=".='EAN13'"><xsl:value-of select="$ProductIdentifierType-EAN13"/></xsl:when>
				<xsl:when test=".='ExportHarmonisedSystemCode'"><xsl:value-of select="$ProductIdentifierType-ExportHarmonisedSystemCode"/></xsl:when>
				<xsl:when test=".='GradeCode'"><xsl:value-of select="$ProductIdentifierType-GradeCode"/></xsl:when>
				<xsl:when test=".='GradeName'"><xsl:value-of select="$ProductIdentifierType-GradeName"/></xsl:when>
				<xsl:when test=".='ImportHarmonisedSystemCode'"><xsl:value-of select="$ProductIdentifierType-ImportHarmonisedSystemCode"/></xsl:when>
				<xsl:when test=".='ManufacturingGradeCode'"><xsl:value-of select="$ProductIdentifierType-ManufacturingGradeCode"/></xsl:when>
				<xsl:when test=".='ManufacturingGradeName'"><xsl:value-of select="$ProductIdentifierType-ManufacturingGradeName"/></xsl:when>
				<xsl:when test=".='Ondule'"><xsl:value-of select="$ProductIdentifierType-Ondule"/></xsl:when>
				<xsl:when test=".='PartNumber'"><xsl:value-of select="$ProductIdentifierType-PartNumber"/></xsl:when>
				<xsl:when test=".='RFQPartNumber'"><xsl:value-of select="$ProductIdentifierType-RFQPartNumber"/></xsl:when>
				<xsl:when test=".='SKU'"><xsl:value-of select="$ProductIdentifierType-SKU"/></xsl:when>
				<xsl:when test=".='UPC'"><xsl:value-of select="$ProductIdentifierType-UPC"/></xsl:when>
				<xsl:when test=".='Other'"><xsl:value-of select="$ProductIdentifierType-Other"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@ProductIdentifierType"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:text>]</xsl:text>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute ProductionStatusType     *** -->

<xsl:template match="@ProductionStatusType">
	<xsl:choose>
		<xsl:when test=".='Free'"><xsl:value-of select="$ProductionStatusType-Free"/></xsl:when>
		<xsl:when test=".='NotFree'"><xsl:value-of select="$ProductionStatusType-NotFree"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ProductionStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PulpingProcess     *** -->

<xsl:template match="@PulpingProcess">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="LineItemStyle1" width="125" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="LineItemStyle2">
			<xsl:choose>
				<xsl:when test=".='ChemicalPulp'"><xsl:value-of select="$PulpingProcess-ChemicalPulp"/></xsl:when>
				<xsl:when test=".='ChemoThermoMechanicalPulp'"><xsl:value-of select="$PulpingProcess-ChemoThermoMechanicalPulp"/></xsl:when>
				<xsl:when test=".='DeinkedPulp'"><xsl:value-of select="$PulpingProcess-DeinkedPulp"/></xsl:when>
				<xsl:when test=".='RefinerGroundwood'"><xsl:value-of select="$PulpingProcess-RefinerGroundwood"/></xsl:when>
				<xsl:when test=".='StoneGroundwood'"><xsl:value-of select="$PulpingProcess-StoneGroundwood"/></xsl:when>
				<xsl:when test=".='ThermoMechanical'"><xsl:value-of select="$PulpingProcess-ThermoMechanical"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@PulpingProcess"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ProofType     *** -->

<xsl:template match="@ProofType">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="LineItemStyle1" width="125" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="LineItemStyle2">
			<xsl:choose>
				<xsl:when test=".='Blues'"><xsl:value-of select="$ProofType-Blues"/></xsl:when>
				<xsl:when test=".='ColorKey'"><xsl:value-of select="$ProofType-ColorKey"/></xsl:when>
				<xsl:when test=".='Cromalin'"><xsl:value-of select="$ProofType-Cromalin"/></xsl:when>
				<xsl:when test=".='CustomCromalin'"><xsl:value-of select="$ProofType-CustomCromalin"/></xsl:when>
				<xsl:when test=".='DigitalProofs'"><xsl:value-of select="$ProofType-DigitalProofs"/></xsl:when>
				<xsl:when test=".='Dylux'"><xsl:value-of select="$ProofType-Dylux"/></xsl:when>
				<xsl:when test=".='FilmProofs'"><xsl:value-of select="$ProofType-FilmProofs"/></xsl:when>
				<xsl:when test=".='FoldedGathered'"><xsl:value-of select="$ProofType-FoldedGathered"/></xsl:when>
				<xsl:when test=".='InkDrawDown'"><xsl:value-of select="$ProofType-InkDrawDown"/></xsl:when>
				<xsl:when test=".='Iris'"><xsl:value-of select="$ProofType-Iris"/></xsl:when>
				<xsl:when test=".='MatchPrint'"><xsl:value-of select="$ProofType-MatchPrint"/></xsl:when>
				<xsl:when test=".='PressProofs'"><xsl:value-of select="$ProofType-PressProofs"/></xsl:when>
				<xsl:when test=".='ReferenceCDCassette'"><xsl:value-of select="$ProofType-ReferenceCDCassette"/></xsl:when>
				<xsl:when test=".='Samples'"><xsl:value-of select="$ProofType-Samples"/></xsl:when>
				<xsl:when test=".='T-Print'"><xsl:value-of select="$ProofType-T-Print"/></xsl:when>
				<xsl:when test=".='Other'"><xsl:value-of select="$ProofType-Other"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@ProofType"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute PurchaseOrderHeaderStatusType     *** -->

<xsl:template match="@PurchaseOrderHeaderStatusType">
	<xsl:choose>
		<xsl:when test=".='Amended'"><xsl:value-of select="$PurchaseOrderHeaderStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$PurchaseOrderHeaderStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='New'"><xsl:value-of select="$PurchaseOrderHeaderStatusType-New"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$PurchaseOrderHeaderStatusType-NoAction"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PurchaseOrderHeaderStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PurchaseOrderLineItemStatusType     *** -->

<xsl:template match="@PurchaseOrderLineItemStatusType">
	<xsl:choose>
		<xsl:when test=".='Amended'"><xsl:value-of select="$PurchaseOrderLineItemStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$PurchaseOrderLineItemStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='New'"><xsl:value-of select="$PurchaseOrderLineItemStatusType-New"/></xsl:when>
		<xsl:when test=".='NoAction'"><xsl:value-of select="$PurchaseOrderLineItemStatusType-NoAction"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PurchaseOrderLineItemStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PurchaseOrderStatusType     *** -->

<xsl:template match="@PurchaseOrderStatusType">
	<xsl:choose>
		<xsl:when test=".='Amended'"><xsl:value-of select="$PurchaseOrderStatusType-Amended"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$PurchaseOrderStatusType-Cancelled"/></xsl:when>
		<xsl:when test=".='Original'"><xsl:value-of select="$PurchaseOrderStatusType-Original"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PurchaseOrderStatusType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute PurchaseOrderReferenceType     *** -->

<xsl:template match="@PurchaseOrderReferenceType">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='AccountNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-AccountNumber"/></xsl:when>
		<xsl:when test=".='Author'"><xsl:value-of select="$PurchaseOrderReferenceType-Author"/></xsl:when>
		<xsl:when test=".='AudioVideoSelectionNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-AudioVideoSelectionNumber"/></xsl:when>
		<xsl:when test=".='BookLanguage'"><xsl:value-of select="$PurchaseOrderReferenceType-BookLanguage"/></xsl:when>
		<xsl:when test=".='BuyerBudgetCenter'"><xsl:value-of select="$PurchaseOrderReferenceType-BuyerBudgetCenter"/></xsl:when>
		<xsl:when test=".='BuyerDivisionIdentifier'"><xsl:value-of select="$PurchaseOrderReferenceType-BuyerDivisionIdentifier"/></xsl:when>
		<xsl:when test=".='BuyerImprint'"><xsl:value-of select="$PurchaseOrderReferenceType-BuyerImprint"/></xsl:when>
		<xsl:when test=".='BuyerRetailPrice'"><xsl:value-of select="$PurchaseOrderReferenceType-BuyerRetailPrice"/></xsl:when>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='Copyright'"><xsl:value-of select="$PurchaseOrderReferenceType-Copyright"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='Edition'"><xsl:value-of select="$PurchaseOrderReferenceType-Edition"/></xsl:when>
		<xsl:when test=".='FromPurchaseOrderNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-FromPurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISBN10'"><xsl:value-of select="$PurchaseOrderReferenceType-ISBN10"/></xsl:when>
		<xsl:when test=".='ISBN10Dash'"><xsl:value-of select="$PurchaseOrderReferenceType-ISBN10Dash"/></xsl:when>
		<xsl:when test=".='ISBN13'"><xsl:value-of select="$PurchaseOrderReferenceType-ISBN13"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$PurchaseOrderReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$PurchaseOrderReferenceType-LotIdentifier"/></xsl:when>
		<xsl:when test=".='MasterContractNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-MasterContractNumber"/></xsl:when>
		<xsl:when test=".='OrderPartyReferenceNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-OrderPartyReferenceNumber"/></xsl:when>
		<xsl:when test=".='OriginalPurchaseOrderNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-OriginalPurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='PackageNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-PackageNumber"/></xsl:when>
		<xsl:when test=".='PriceContractNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-PriceContractNumber"/></xsl:when>
		<xsl:when test=".='PriceList'"><xsl:value-of select="$PurchaseOrderReferenceType-PriceList"/></xsl:when>
		<xsl:when test=".='PrintingNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-PrintingNumber"/></xsl:when>
		<xsl:when test=".='PupilsTeachers'"><xsl:value-of select="$PurchaseOrderReferenceType-PupilsTeachers"/></xsl:when>
		<xsl:when test=".='ReleaseNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ReleaseNumber"/></xsl:when>
		<xsl:when test=".='RFQLineItemNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-RFQLineItemNumber"/></xsl:when>
		<xsl:when test=".='RFQNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-RFQNumber"/></xsl:when>
		<xsl:when test=".='RunNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-RunNumber"/></xsl:when>
		<xsl:when test=".='SchoolGrade'"><xsl:value-of select="$PurchaseOrderReferenceType-SchoolGrade"/></xsl:when>
		<xsl:when test=".='SchoolGradeLevel'"><xsl:value-of select="$PurchaseOrderReferenceType-SchoolGradeLevel"/></xsl:when>
		<xsl:when test=".='ShippingInstructionsLineItemNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ShippingInstructionsLineItemNumber"/></xsl:when>
		<xsl:when test=".='ShippingInstructionsNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ShippingInstructionsNumber"/></xsl:when>
		<xsl:when test=".='SpecificationReferenceNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-SpecificationReferenceNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='Title'"><xsl:value-of select="$PurchaseOrderReferenceType-Title"/></xsl:when>
		<xsl:when test=".='TitleAlias'"><xsl:value-of select="$PurchaseOrderReferenceType-TitleAlias"/></xsl:when>
		<xsl:when test=".='ToPurchaseOrderNumber'"><xsl:value-of select="$PurchaseOrderReferenceType-ToPurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='UniversalProductIdentifier'"><xsl:value-of select="$PurchaseOrderReferenceType-UniversalProductIdentifier"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$PurchaseOrderReferenceType-Other"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PurchaseOrderReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute PurchaseOrderType     *** -->

<xsl:template match="@PurchaseOrderType">
	<xsl:choose>
		<xsl:when test=".='BlanketOrder'"><xsl:value-of select="$PurchaseOrderType-BlanketOrder"/></xsl:when>
		<xsl:when test=".='ConsumptionOrder'"><xsl:value-of select="$PurchaseOrderType-ConsumptionOrder"/></xsl:when>
		<xsl:when test=".='ConfirmingOrder'"><xsl:value-of select="$PurchaseOrderType-ConfirmingOrder"/></xsl:when>
		<xsl:when test=".='ReleaseOrder'"><xsl:value-of select="$PurchaseOrderType-ReleaseOrder"/></xsl:when>
		<xsl:when test=".='ReservationOrder'"><xsl:value-of select="$PurchaseOrderType-ReservationOrder"/></xsl:when>
		<xsl:when test=".='StandardOrder'"><xsl:value-of select="$PurchaseOrderType-StandardOrder"/></xsl:when>
		<xsl:when test=".='TrialOrder'"><xsl:value-of select="$PurchaseOrderType-TrialOrder"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@PurchaseOrderType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute QuantityType     *** -->

<xsl:template match="@QuantityType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='AirDryWeight'"><xsl:value-of select="$QuantityType-AirDryWeight"/></xsl:when>
					<xsl:when test=".='Area'"><xsl:value-of select="$QuantityType-Area"/></xsl:when>
					<xsl:when test=".='BoneDry'"><xsl:value-of select="$QuantityType-BoneDry"/></xsl:when>
					<xsl:when test=".='Count'"><xsl:value-of select="$QuantityType-Count"/></xsl:when>
					<xsl:when test=".='Freight'"><xsl:value-of select="$QuantityType-Freight"/></xsl:when>
					<xsl:when test=".='GrossWeight'"><xsl:value-of select="$QuantityType-GrossWeight"/></xsl:when>
					<xsl:when test=".='Length'"><xsl:value-of select="$QuantityType-Length"/></xsl:when>
					<xsl:when test=".='NetWeight'"><xsl:value-of select="$QuantityType-NetWeight"/></xsl:when>
					<xsl:when test=".='NetNetWeight'"><xsl:value-of select="$QuantityType-NetNetWeight"/></xsl:when>
					<xsl:when test=".='NominalWeight'"><xsl:value-of select="$QuantityType-NominalWeight"/></xsl:when>
					<xsl:when test=".='Percent'"><xsl:value-of select="$QuantityType-Percent"/></xsl:when>
					<xsl:when test=".='TareWeight'"><xsl:value-of select="$QuantityType-TareWeight"/></xsl:when>
					<xsl:when test=".='Time'"><xsl:value-of select="$QuantityType-Time"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@QuantityType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute QuantityTypeContext     *** -->

<xsl:template match="@QuantityTypeContext">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='AgreedToClaimValue'"><xsl:value-of select="$QuantityTypeContext-AgreedToClaimValue"/></xsl:when>
					<xsl:when test=".='Allocated'"><xsl:value-of select="$QuantityTypeContext-Allocated"/></xsl:when>
					<xsl:when test=".='Balance'"><xsl:value-of select="$QuantityTypeContext-Balance"/></xsl:when>
					<xsl:when test=".='CalledOff'"><xsl:value-of select="$QuantityTypeContext-CalledOff"/></xsl:when>
					<xsl:when test=".='Consumed'"><xsl:value-of select="$QuantityTypeContext-Consumed"/></xsl:when>
					<xsl:when test=".='Damaged'"><xsl:value-of select="$QuantityTypeContext-Damaged"/></xsl:when>
					<xsl:when test=".='Delivered'"><xsl:value-of select="$QuantityTypeContext-Delivered"/></xsl:when>
					<xsl:when test=".='Freight'"><xsl:value-of select="$QuantityTypeContext-Freight"/></xsl:when>
					<xsl:when test=".='Intransit'"><xsl:value-of select="$QuantityTypeContext-Intransit"/></xsl:when>	
					<xsl:when test=".='Invoiced'"><xsl:value-of select="$QuantityTypeContext-Invoiced"/></xsl:when>	
					<xsl:when test=".='Loaded'"><xsl:value-of select="$QuantityTypeContext-Loaded"/></xsl:when>	
					<xsl:when test=".='OnHand'"><xsl:value-of select="$QuantityTypeContext-OnHand"/></xsl:when>	
					<xsl:when test=".='Ordered'"><xsl:value-of select="$QuantityTypeContext-Ordered"/></xsl:when>	
					<xsl:when test=".='Packed'"><xsl:value-of select="$QuantityTypeContext-Packed"/></xsl:when>	
					<xsl:when test=".='Planned'"><xsl:value-of select="$QuantityTypeContext-Planned"/></xsl:when>	
					<xsl:when test=".='Produced'"><xsl:value-of select="$QuantityTypeContext-Produced"/></xsl:when>	
					<xsl:when test=".='Released'"><xsl:value-of select="$QuantityTypeContext-Released"/></xsl:when>
					<xsl:when test=".='Reorder'"><xsl:value-of select="$QuantityTypeContext-Reorder"/></xsl:when>
					<xsl:when test=".='ReorderPoint'"><xsl:value-of select="$QuantityTypeContext-ReorderPoint"/></xsl:when>
					<xsl:when test=".='Reserved'"><xsl:value-of select="$QuantityTypeContext-Reserved"/></xsl:when>	
					<xsl:when test=".='Trimmed'"><xsl:value-of select="$QuantityTypeContext-Trimmed"/></xsl:when>	
					<xsl:when test=".='Unloaded'"><xsl:value-of select="$QuantityTypeContext-Unloaded"/></xsl:when>
					<xsl:when test=".='UnspecifiedDamage'"><xsl:value-of select="$QuantityTypeContext-UnspecifiedDamage"/></xsl:when>
					<xsl:when test=".='ValueClaimed'"><xsl:value-of select="$QuantityTypeContext-ValueClaimed"/></xsl:when>
					<xsl:when test=".='VendorSupplied'"><xsl:value-of select="$QuantityTypeContext-VendorSupplied"/></xsl:when>
					<xsl:when test=".='Wound'"><xsl:value-of select="$QuantityTypeContext-Wound"/></xsl:when>	
					<xsl:when test=".='Wrapped'"><xsl:value-of select="$QuantityTypeContext-Wrapped"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@QuantityTypeContext"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ReamType     *** -->

<xsl:template match="@ReamType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Any'"><xsl:value-of select="$ReamType-Any"/></xsl:when>
					<xsl:when test=".='ReamWrapped'"><xsl:value-of select="$ReamType-ReamWrapped"/></xsl:when>
					<xsl:when test=".='BulkPackedNonTabbed'"><xsl:value-of select="$ReamType-BulkPackedNonTabbed"/></xsl:when>
					<xsl:when test=".='BulkPackedTabbed'"><xsl:value-of select="$ReamType-BulkPackedTabbed"/></xsl:when>				
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ReamType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ReasonIdentifierType     *** -->

<xsl:template match="@ReasonIdentifierType">
	<xsl:choose>
		<xsl:when test=".='Claim'"><xsl:value-of select="$ReasonIdentifierType-Claim"/></xsl:when>
		<xsl:when test=".='Rebate'"><xsl:value-of select="$ReasonIdentifierType-Rebate"/></xsl:when>
		<xsl:when test=".='Commission'"><xsl:value-of select="$ReasonIdentifierType-Commission"/></xsl:when>
		<xsl:when test=".='InvoiceError'"><xsl:value-of select="$ReasonIdentifierType-InvoiceError"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@ReasonIdentifierType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute RFQReferenceType     *** -->

<xsl:template match="@RFQReferenceType">
	<xsl:choose>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$RFQReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$RFQReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$RFQReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$RFQReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$RFQReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$RFQReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$RFQReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$RFQReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$RFQReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$RFQReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$RFQReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$RFQReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$RFQReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$RFQReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$RFQReferenceType-Other"/></xsl:when>				
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@RFQReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute RFQResponseReferenceType     *** -->

<xsl:template match="@RFQResponseReferenceType">
	<xsl:choose>
		<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$RFQResponseReferenceType-ContractLineNumber"/></xsl:when>
		<xsl:when test=".='ContractNumber'"><xsl:value-of select="$RFQResponseReferenceType-ContractNumber"/></xsl:when>
		<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$RFQResponseReferenceType-CustomerReferenceNumber"/></xsl:when>
		<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$RFQResponseReferenceType-DeliveryBookingNumber"/></xsl:when>
		<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$RFQResponseReferenceType-IndentOrderNumber"/></xsl:when>
		<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$RFQResponseReferenceType-IntraStatNumber"/></xsl:when>
		<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$RFQResponseReferenceType-ISODocumentReference"/></xsl:when>
		<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$RFQResponseReferenceType-MillOrderLineItemNumber"/></xsl:when>
		<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$RFQResponseReferenceType-MillOrderNumber"/></xsl:when>
		<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$RFQResponseReferenceType-OriginalInvoiceNumber"/></xsl:when>
		<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$RFQResponseReferenceType-PurchaseOrderNumber"/></xsl:when>
		<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$RFQResponseReferenceType-StockOrderNumber"/></xsl:when>
		<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$RFQResponseReferenceType-SupplierCallOffNumber"/></xsl:when>
		<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$RFQResponseReferenceType-SupplierReferenceNumber"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$RFQResponseReferenceType-Other"/></xsl:when>				
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@RFQResponseReferenceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute ResultSource     *** -->

<xsl:template match="@ResultSource">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='AutoLab'"><xsl:value-of select="$ResultSource-AutoLab"/></xsl:when>
					<xsl:when test=".='Calculated'"><xsl:value-of select="$ResultSource-Calculated"/></xsl:when>
					<xsl:when test=".='ManualLab'"><xsl:value-of select="$ResultSource-ManualLab"/></xsl:when>
					<xsl:when test=".='OnMachine'"><xsl:value-of select="$ResultSource-OnMachine"/></xsl:when>
					<xsl:when test=".='Predicted'"><xsl:value-of select="$ResultSource-Predicted"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ResultSource"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute RewoundIndicator     *** -->

<xsl:template match="@RewoundIndicator">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Yes'"><xsl:value-of select="$RewoundIndicator-Yes"/></xsl:when>
					<xsl:when test=".='No'"><xsl:value-of select="$RewoundIndicator-No"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@RewoundIndicator"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute SampleType     *** -->

<xsl:template match="@SampleType">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Average'"><xsl:value-of select="$SampleType-Average"/></xsl:when>
					<xsl:when test=".='Bottom'"><xsl:value-of select="$SampleType-Bottom"/></xsl:when>
					<xsl:when test=".='CDAverage'"><xsl:value-of select="$SampleType-CDAverage"/></xsl:when>
					<xsl:when test=".='CDBottom'"><xsl:value-of select="$SampleType-CDBottom"/></xsl:when>
					<xsl:when test=".='CDTop'"><xsl:value-of select="$SampleType-CDTop"/></xsl:when>
					<xsl:when test=".='MDAverage'"><xsl:value-of select="$SampleType-MDAverage"/></xsl:when>
					<xsl:when test=".='MDBottom'"><xsl:value-of select="$SampleType-MDBottom"/></xsl:when>
					<xsl:when test=".='MDTop'"><xsl:value-of select="$SampleType-MDTop"/></xsl:when>
					<xsl:when test=".='Target'"><xsl:value-of select="$SampleType-Target"/></xsl:when>
					<xsl:when test=".='Top'"><xsl:value-of select="$SampleType-Top"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@SampleType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute ShapeOfHole     *** -->

<xsl:template match="@ShapeOfHole">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Elongated'"><xsl:value-of select="$ShapeOfHole-Elongated"/></xsl:when>
					<xsl:when test=".='Oval'"><xsl:value-of select="$ShapeOfHole-Oval"/></xsl:when>
					<xsl:when test=".='Round'"><xsl:value-of select="$ShapeOfHole-Round"/></xsl:when>
					<xsl:when test=".='Square'"><xsl:value-of select="$ShapeOfHole-Square"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$ShapeOfHole-Other"/></xsl:when>					
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ShapeOfHole"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute SheetCountMethodType     *** -->

<xsl:template match="@SheetCountMethodType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Counter'"><xsl:value-of select="$SheetCountMethodType-Counter"/></xsl:when>
					<xsl:when test=".='Laser'"><xsl:value-of select="$SheetCountMethodType-Laser"/></xsl:when>
					<xsl:when test=".='NominalGrammage'"><xsl:value-of select="$SheetCountMethodType-NominalGrammage"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@SheetCountMethodType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute ShipmentDetailsType     *** -->

<xsl:template match="@ShipmentDetailsType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Shipment'"><xsl:value-of select="$ShipmentDetailsType-Shipment"/></xsl:when>
					<xsl:when test=".='Forecast'"><xsl:value-of select="$ShipmentDetailsType-Forecast"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@ShipmentDetailsType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute Sign     *** -->

<xsl:template match="@Sign">
	<xsl:choose>
		<xsl:when test=".='Plus'"><xsl:value-of select="$Sign-Plus"/></xsl:when>
		<xsl:when test=".='Minus'"><xsl:value-of select="$Sign-Minus"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@Sign"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute SignatureAlgorithm     *** -->

<xsl:template match="@SignatureAlgorithm">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='rsa'"><xsl:value-of select="$SignatureAlgorithm-rsa"/></xsl:when>
				<xsl:when test=".='dsa'"><xsl:value-of select="$SignatureAlgorithm-dsa"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@SignatureAlgorithm"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute StatusCode     *** -->

<xsl:template match="@StatusCode">
	<xsl:choose>
		<xsl:when test=".='ActiveFree'"><xsl:value-of select="$StatusCode-ActiveFree"/></xsl:when>
		<xsl:when test=".='ActiveHold'"><xsl:value-of select="$StatusCode-ActiveHold"/></xsl:when>
		<xsl:when test=".='Packed'"><xsl:value-of select="$StatusCode-Packed"/></xsl:when>
		<xsl:when test=".='ReservedInProductionPlanningSystem'"><xsl:value-of select="$StatusCode-ReservedInProductionPlanningSystem"/></xsl:when>
		<xsl:when test=".='TransferredToMillSystem'"><xsl:value-of select="$StatusCode-TransferredToMillSystem"/></xsl:when>
		<xsl:when test=".='FinalPlanning'"><xsl:value-of select="$StatusCode-FinalPlanning"/></xsl:when>
		<xsl:when test=".='ProductionStarted'"><xsl:value-of select="$StatusCode-ProductionStarted"/></xsl:when>
		<xsl:when test=".='ProductionComplete'"><xsl:value-of select="$StatusCode-ProductionComplete"/></xsl:when>
		<xsl:when test=".='PartiallyShipped'"><xsl:value-of select="$StatusCode-PartiallyShipped"/></xsl:when>
		<xsl:when test=".='ShipmentComplete'"><xsl:value-of select="$StatusCode-ShipmentComplete"/></xsl:when>
		<xsl:when test=".='Cancelled'"><xsl:value-of select="$StatusCode-Cancelled"/></xsl:when>
		<xsl:when test=".='Invoiced'"><xsl:value-of select="$StatusCode-Invoiced"/></xsl:when>
		<xsl:when test=".='Scheduled'"><xsl:value-of select="$StatusCode-Scheduled"/></xsl:when>
		<xsl:when test=".='Staged'"><xsl:value-of select="$StatusCode-Staged"/></xsl:when>
		<xsl:when test=".='Transferred'"><xsl:value-of select="$StatusCode-Transferred"/></xsl:when>
		<xsl:when test=".='Loaded'"><xsl:value-of select="$StatusCode-Loaded"/></xsl:when>
		<xsl:when test=".='Shuttled'"><xsl:value-of select="$StatusCode-Shuttled"/></xsl:when>
		<xsl:when test=".='Unscheduled'"><xsl:value-of select="$StatusCode-Unscheduled"/></xsl:when>
		<xsl:when test=".='OrderLineConfirmed'"><xsl:value-of select="$StatusCode-OrderLineConfirmed"/></xsl:when>		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@StatusCode"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute StencilContent    *** -->

<xsl:template match="@StencilContent">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='BasisWeight'"><xsl:value-of select="$StencilContent-BasisWeight"/></xsl:when>
					<xsl:when test=".='ExportShipMark'"><xsl:value-of select="$StencilContent-ExportShipMark"/></xsl:when>
					<xsl:when test=".='Identifier'"><xsl:value-of select="$StencilContent-Identifier"/></xsl:when>
					<xsl:when test=".='LotIdentifier'"><xsl:value-of select="$StencilContent-LotIdentifier"/></xsl:when>
					<xsl:when test=".='PlainText'"><xsl:value-of select="$StencilContent-PlainText"/></xsl:when>
					<xsl:when test=".='ProductIdentifier'"><xsl:value-of select="$StencilContent-ProductIdentifier"/></xsl:when>
					<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$StencilContent-PurchaseOrderNumber"/></xsl:when>
					<xsl:when test=".='PurchaseOrderReference'"><xsl:value-of select="$StencilContent-PurchaseOrderReference"/></xsl:when>
					<xsl:when test=".='TambourID'"><xsl:value-of select="$StencilContent-TambourID"/></xsl:when>
					<xsl:when test=".='VendorBrandName'"><xsl:value-of select="$StencilContent-VendorBrandName"/></xsl:when>
					<xsl:when test=".='VendorGradeCode'"><xsl:value-of select="$StencilContent-VendorGradeCode"/></xsl:when>
					<xsl:when test=".='VendorGradeName'"><xsl:value-of select="$StencilContent-VendorGradeName"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@StencilContent"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute SuppliedComponentType     *** -->

<xsl:template match="@SuppliedComponentType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Component'"><xsl:value-of select="$SuppliedComponentType-Component"/></xsl:when>
					<xsl:when test=".='RawMaterial'"><xsl:value-of select="$SuppliedComponentType-RawMaterial"/></xsl:when>					
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@SuppliedComponentType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute StencilFormat     *** -->

<xsl:template match="@StencilFormat">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Text'"><xsl:value-of select="$StencilFormat-Text"/></xsl:when>
					<xsl:when test=".='Barcode'"><xsl:value-of select="$StencilFormat-Barcode"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@StencilFormat"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute StencilType     *** -->

<xsl:template match="@StencilType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='CutOut'"><xsl:value-of select="$StencilType-CutOut"/></xsl:when>
					<xsl:when test=".='InkJet'"><xsl:value-of select="$StencilType-InkJet"/></xsl:when>
					<xsl:when test=".='Rubber'"><xsl:value-of select="$StencilType-Rubber"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@StencilType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute StencilInkType     *** -->

<xsl:template match="@StencilInkType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='EdibleWaterSoluble'"><xsl:value-of select="$StencilInkType-EdibleWaterSoluble"/></xsl:when>
					<xsl:when test=".='EdibleNotWaterSoluble'"><xsl:value-of select="$StencilInkType-EdibleNotWaterSoluble"/></xsl:when>
					<xsl:when test=".='InedibleWaterSoluble'"><xsl:value-of select="$StencilInkType-InedibleWaterSoluble"/></xsl:when>
					<xsl:when test=".='InedibleNotWaterSoluble'"><xsl:value-of select="$StencilInkType-InedibleNotWaterSoluble"/></xsl:when>
					<xsl:when test=".='Unknown'"><xsl:value-of select="$StencilInkType-Unknown"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@StencilInkType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute StencilLocation     *** -->

<xsl:template match="@StencilLocation">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Top'"><xsl:value-of select="$StencilLocation-Top"/></xsl:when>
					<xsl:when test=".='Side'"><xsl:value-of select="$StencilLocation-Side"/></xsl:when>
					<xsl:when test=".='End'"><xsl:value-of select="$StencilLocation-End"/></xsl:when>
					<xsl:when test=".='UnwrappedReelEnd'"><xsl:value-of select="$StencilLocation-UnwrappedReelEnd"/></xsl:when>
					<xsl:when test=".='UnwrappedReelBilge'"><xsl:value-of select="$StencilLocation-UnwrappedReelBilge"/></xsl:when>
					<xsl:when test=".='WrappedReelBilge'"><xsl:value-of select="$StencilLocation-WrappedReelBilge"/></xsl:when>		
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@StencilLocation"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TaxCategoryType     *** -->

<xsl:template match="@TaxCategoryType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Exempt'"><xsl:value-of select="$TaxCategoryType-Exempt"/></xsl:when>
					<xsl:when test=".='Standard'"><xsl:value-of select="$TaxCategoryType-Standard"/></xsl:when>
					<xsl:when test=".='Zero'"><xsl:value-of select="$TaxCategoryType-Zero"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TaxCategoryType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TaxCategoryType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TaxCategoryType     *** -->

<xsl:template match="@TaxCategoryType" mode="Summary">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="180" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Exempt'"><xsl:value-of select="$TaxCategoryType-Exempt"/></xsl:when>
					<xsl:when test=".='Standard'"><xsl:value-of select="$TaxCategoryType-Standard"/></xsl:when>
					<xsl:when test=".='Zero'"><xsl:value-of select="$TaxCategoryType-Zero"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TaxCategoryType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TaxCategoryType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TaxType     *** -->

<xsl:template match="@TaxType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Federal'"><xsl:value-of select="$TaxType-Federal"/></xsl:when>
					<xsl:when test=".='GST'"><xsl:value-of select="$TaxType-GST"/></xsl:when>
					<xsl:when test=".='Harmonized'"><xsl:value-of select="$TaxType-Harmonized"/></xsl:when>
					<xsl:when test=".='Local'"><xsl:value-of select="$TaxType-Local"/></xsl:when>
					<xsl:when test=".='StateOrProvincial'"><xsl:value-of select="$TaxType-StateOrProvincial"/></xsl:when>
					<xsl:when test=".='VAT'"><xsl:value-of select="$TaxType-VAT"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TaxType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TaxType     *** -->

<xsl:template match="@TaxType" mode="Summary">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="180" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='Federal'"><xsl:value-of select="$TaxType-Federal"/></xsl:when>
					<xsl:when test=".='GST'"><xsl:value-of select="$TaxType-GST"/></xsl:when>
					<xsl:when test=".='Harmonized'"><xsl:value-of select="$TaxType-Harmonized"/></xsl:when>
					<xsl:when test=".='Local'"><xsl:value-of select="$TaxType-Local"/></xsl:when>
					<xsl:when test=".='StateOrProvincial'"><xsl:value-of select="$TaxType-StateOrProvincial"/></xsl:when>
					<xsl:when test=".='VAT'"><xsl:value-of select="$TaxType-VAT"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TaxType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TermsBasisDateType     *** -->

<xsl:template match="@TermsBasisDateType">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="DeliveryInstructionsStyle1" width="125" valign="top">
						<xsl:value-of select="$map[@key=name(current())]"/>
					</td>
					<td class="DeliveryInstructionsStyle2" valign="top">
						<xsl:choose>
							<xsl:when test=".='DeliveryDate'"><xsl:value-of select="$TermsBasisDateType-DeliveryDate"/></xsl:when>
							<xsl:when test=".='DespatchDate'"><xsl:value-of select="$TermsBasisDateType-DespatchDate"/></xsl:when>
							<xsl:when test=".='EndOfDeliveryMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfDeliveryMonth"/></xsl:when>
							<xsl:when test=".='EndOfDespatchMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfDespatchMonth"/></xsl:when>
							<xsl:when test=".='EndOfInvoiceMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfInvoiceMonth"/></xsl:when>
							<xsl:when test=".='EstimatedTimeOfArrival'"><xsl:value-of select="$TermsBasisDateType-EstimatedTimeOfArrival"/></xsl:when>
							<xsl:when test=".='EstimatedTimeOfDeparture'"><xsl:value-of select="$TermsBasisDateType-EstimatedTimeOfDeparture"/></xsl:when>
							<xsl:when test=".='InvoiceDate'"><xsl:value-of select="$TermsBasisDateType-InvoiceDate"/></xsl:when>
							<xsl:when test=".='OrderConfirmationDate'"><xsl:value-of select="$TermsBasisDateType-OrderConfirmationDate"/></xsl:when>
							<xsl:otherwise>
								<b style="color:red">-<xsl:value-of select="@TermsBasisDateType"/>-</b>
							</xsl:otherwise>
						</xsl:choose>
					</td>
				</tr>
			</table>
</xsl:template>

<!-- ***     Attribute TermsBasisDateType     *** -->

<xsl:template match="@TermsBasisDateType" mode="OrderConfirmationSummary">
	<xsl:choose>
		<xsl:when test=".='DeliveryDate'"><xsl:value-of select="$TermsBasisDateType-DeliveryDate"/></xsl:when>
		<xsl:when test=".='DespatchDate'"><xsl:value-of select="$TermsBasisDateType-DespatchDate"/></xsl:when>
		<xsl:when test=".='EndOfDeliveryMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfDeliveryMonth"/></xsl:when>
		<xsl:when test=".='EndOfDespatchMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfDespatchMonth"/></xsl:when>
		<xsl:when test=".='EndOfInvoiceMonth'"><xsl:value-of select="$TermsBasisDateType-EndOfInvoiceMonth"/></xsl:when>
		<xsl:when test=".='EstimatedTimeOfArrival'"><xsl:value-of select="$TermsBasisDateType-EstimatedTimeOfArrival"/></xsl:when>
		<xsl:when test=".='EstimatedTimeOfDeparture'"><xsl:value-of select="$TermsBasisDateType-EstimatedTimeOfDeparture"/></xsl:when>
		<xsl:when test=".='InvoiceDate'"><xsl:value-of select="$TermsBasisDateType-InvoiceDate"/></xsl:when>
		<xsl:when test=".='OrderConfirmationDate'"><xsl:value-of select="$TermsBasisDateType-OrderConfirmationDate"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@TermsBasisDateType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute TestAgency     *** -->

<xsl:template match="@TestAgency">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
				<xsl:choose>
					<xsl:when test=".='ASTM'"><xsl:value-of select="$TestAgency-ASTM"/></xsl:when>
					<xsl:when test=".='BS'"><xsl:value-of select="$TestAgency-BS"/></xsl:when>
					<xsl:when test=".='CIE'"><xsl:value-of select="$TestAgency-CIE"/></xsl:when>
					<xsl:when test=".='DuPont'"><xsl:value-of select="$TestAgency-DuPont"/></xsl:when>
					<xsl:when test=".='EN'"><xsl:value-of select="$TestAgency-EN"/></xsl:when>
					<xsl:when test=".='GE'"><xsl:value-of select="$TestAgency-GE"/></xsl:when>
					<xsl:when test=".='ImageXpert'"><xsl:value-of select="$TestAgency-ImageXpert"/></xsl:when>
					<xsl:when test=".='ISO'"><xsl:value-of select="$TestAgency-ISO"/></xsl:when>
					<xsl:when test=".='NS-EN'"><xsl:value-of select="$TestAgency-NS-EN"/></xsl:when>
					<xsl:when test=".='PAPTAC'"><xsl:value-of select="$TestAgency-PAPTAC"/></xsl:when>
					<xsl:when test=".='SCAN-test'"><xsl:value-of select="$TestAgency-SCAN-test"/></xsl:when>
					<xsl:when test=".='SFS-EN'"><xsl:value-of select="$TestAgency-SFS-EN"/></xsl:when>
					<xsl:when test=".='SS-EN'"><xsl:value-of select="$TestAgency-SS-EN"/></xsl:when>
					<xsl:when test=".='TAPPI'"><xsl:value-of select="$TestAgency-TAPPI"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TestAgency"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TestFlag     *** -->

<xsl:template match="@TestFlag">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='Production'"><xsl:value-of select="$TestFlag-Production"/></xsl:when>
				<xsl:when test=".='Test'"><xsl:value-of select="$TestFlag-Test"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@TestFlag"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
</xsl:template>

<!-- ***     Attribute TransmissionMode     *** -->

<xsl:template match="@TransmissionMode">
	<xsl:if test="string-length(.) !='0'">
	<tr>
		<td class="EnvelopeStyle1" width="200" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='BestEffort'"><xsl:value-of select="$TransmissionMode-BestEffort"/></xsl:when>
				<xsl:when test=".='ExactlyOnce'"><xsl:value-of select="$TransmissionMode-ExactlyOnce"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@TransmissionMode"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute TransmissionProtocol     *** -->

<xsl:template match="@TransmissionProtocol">
	<xsl:if test="string-length(.) !='0'">
	<tr>
		<td class="EnvelopeStyle1" valign="top">
			<xsl:value-of select="$map[@key=name(current())]"/>
		</td>
		<td class="EnvelopeStyle2" valign="top">
			<xsl:choose>
				<xsl:when test=".='HTTP'"><xsl:value-of select="$TransmissionProtocol-HTTP"/></xsl:when>
				<xsl:when test=".='eMail'"><xsl:value-of select="$TransmissionProtocol-eMail"/></xsl:when>
				<xsl:otherwise>
					<b style="color:red">-<xsl:value-of select="@TransmissionProtocol"/>-</b>
				</xsl:otherwise>
			</xsl:choose>
		</td>
	</tr>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute TransportLoadingType     *** -->

<xsl:template match="@TransportLoadingType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Lying'"><xsl:value-of select="$TransportLoadingType-Lying"/></xsl:when>
					<xsl:when test=".='Standing'"><xsl:value-of select="$TransportLoadingType-Standing"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportLoadingType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportLoadingType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportDeckOption     *** -->

<xsl:template match="@TransportDeckOption">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='FullDeck'"><xsl:value-of select="$TransportDeckOption-FullDeck"/></xsl:when>
					<xsl:when test=".='UnderDeck'"><xsl:value-of select="$TransportDeckOption-UnderDeck"/></xsl:when>
					<xsl:when test=".='HalfDeck'"><xsl:value-of select="$TransportDeckOption-HalfDeck"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportDeckOption"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportModeType     *** -->

<xsl:template match="@TransportModeType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Air'"><xsl:value-of select="$TransportModeType-Air"/></xsl:when>
					<xsl:when test=".='InlandWaterway'"><xsl:value-of select="$TransportModeType-InlandWaterway"/></xsl:when>
					<xsl:when test=".='Intermodal'"><xsl:value-of select="$TransportModeType-Intermodal"/></xsl:when>
					<xsl:when test=".='Mail'"><xsl:value-of select="$TransportModeType-Mail"/></xsl:when>
					<xsl:when test=".='Rail'"><xsl:value-of select="$TransportModeType-Rail"/></xsl:when>
					<xsl:when test=".='Road'"><xsl:value-of select="$TransportModeType-Road"/></xsl:when>
					<xsl:when test=".='Sea'"><xsl:value-of select="$TransportModeType-Sea"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportModeType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportModeType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportUnitIdentifierType     *** -->

<xsl:template match="@TransportUnitIdentifierType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='ContainerID'"><xsl:value-of select="$TransportUnitIdentifierType-ContainerID"/></xsl:when>
					<xsl:when test=".='GlobalReturnableAssetIdentifier'"><xsl:value-of select="$TransportUnitIdentifierType-GlobalReturnableAssetIdentifier"/></xsl:when>
					<xsl:when test=".='RailCarID'"><xsl:value-of select="$TransportUnitIdentifierType-RailCarID"/></xsl:when>
					<xsl:when test=".='RFTag'"><xsl:value-of select="$TransportUnitIdentifierType-RFTag"/></xsl:when>
					<xsl:when test=".='SealNumber'"><xsl:value-of select="$TransportUnitIdentifierType-SealNumber"/></xsl:when>
					<xsl:when test=".='SerialisedShippingContainerCode'"><xsl:value-of select="$TransportUnitIdentifierType-SerialisedShippingContainerCode"/></xsl:when>
					<xsl:when test=".='TrailerID'"><xsl:value-of select="$TransportUnitIdentifierType-TrailerID"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportUnitIdentifierType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportUnitIdentifierType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportUnitType     *** -->

<xsl:template match="@TransportUnitType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Container'"><xsl:value-of select="$TransportUnitType-Container"/></xsl:when>
					<xsl:when test=".='ConventionalVessel'"><xsl:value-of select="$TransportUnitType-ConventionalVessel"/></xsl:when>
					<xsl:when test=".='Flatbed'"><xsl:value-of select="$TransportUnitType-Flatbed"/></xsl:when>
					<xsl:when test=".='FlatCar'"><xsl:value-of select="$TransportUnitType-FlatCar"/></xsl:when>
					<xsl:when test=".='RailCar'"><xsl:value-of select="$TransportUnitType-RailCar"/></xsl:when>
					<xsl:when test=".='StackTrain'"><xsl:value-of select="$TransportUnitType-StackTrain"/></xsl:when>
					<xsl:when test=".='SwapBodies'"><xsl:value-of select="$TransportUnitType-SwapBodies"/></xsl:when>
					<xsl:when test=".='Trailer'"><xsl:value-of select="$TransportUnitType-Trailer"/></xsl:when>
					<xsl:when test=".='Wagon'"><xsl:value-of select="$TransportUnitType-Wagon"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportUnitType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportUnitType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportUnitVariable     *** -->

<xsl:template match="@TransportUnitVariable">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='CubicCapacity'"><xsl:value-of select="$TransportUnitVariable-CubicCapacity"/></xsl:when>
					<xsl:when test=".='Height'"><xsl:value-of select="$TransportUnitVariable-Height"/></xsl:when>
					<xsl:when test=".='Length'"><xsl:value-of select="$TransportUnitVariable-Length"/></xsl:when>
					<xsl:when test=".='RailcarDoorSize'"><xsl:value-of select="$TransportUnitVariable-RailcarDoorSize"/></xsl:when>
					<xsl:when test=".='WeightCarryingCapacity'"><xsl:value-of select="$TransportUnitVariable-WeightCarryingCapacity"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportUnitVariable"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportVehicleIdentifierType     *** -->

<xsl:template match="@TransportVehicleIdentifierType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>
				<xsl:choose>
					<xsl:when test=".='FlightNumber'"><xsl:value-of select="$TransportVehicleIdentifierType-FlightNumber"/></xsl:when>
					<xsl:when test=".='GlobalReturnableAssetIdentifier'"><xsl:value-of select="$TransportVehicleIdentifierType-GlobalReturnableAssetIdentifier"/></xsl:when>
					<xsl:when test=".='LicencePlateNumber'"><xsl:value-of select="$TransportVehicleIdentifierType-LicencePlateNumber"/></xsl:when>
					<xsl:when test=".='RFTag'"><xsl:value-of select="$TransportVehicleIdentifierType-RFTag"/></xsl:when>
					<xsl:when test=".='SerialisedShippingContainerCode'"><xsl:value-of select="$TransportVehicleIdentifierType-SerialisedShippingContainerCode"/></xsl:when>
					<xsl:when test=".='StandardCarrierAlphaCode'"><xsl:value-of select="$TransportVehicleIdentifierType-StandardCarrierAlphaCode"/></xsl:when>
					<xsl:when test=".='TrainNumber'"><xsl:value-of select="$TransportVehicleIdentifierType-TrainNumber"/></xsl:when>
					<xsl:when test=".='VesselName'"><xsl:value-of select="$TransportVehicleIdentifierType-VesselName"/></xsl:when>
					<xsl:when test=".='VoyageNumber'"><xsl:value-of select="$TransportVehicleIdentifierType-VoyageNumber"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportVehicleIdentifierType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportVehicleIdentifierType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute TransportVehicleType     *** -->

<xsl:template match="@TransportVehicleType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="DeliveryInstructionsStyle1" width="125">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="DeliveryInstructionsStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Barge'"><xsl:value-of select="$TransportVehicleType-Barge"/></xsl:when>
					<xsl:when test=".='ConventionalVessel'"><xsl:value-of select="$TransportVehicleType-ConventionalVessel"/></xsl:when>
					<xsl:when test=".='RollOnOffShip'"><xsl:value-of select="$TransportVehicleType-RollOnOffShip"/></xsl:when>
					<xsl:when test=".='SidePortVessel'"><xsl:value-of select="$TransportVehicleType-SidePortVessel"/></xsl:when>
					<xsl:when test=".='TruckTrailer'"><xsl:value-of select="$TransportVehicleType-TruckTrailer"/></xsl:when>
					<xsl:when test=".='Other'"><xsl:value-of select="$TransportVehicleType-Other"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@TransportVehicleType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute UOM     *** -->

<xsl:template match="@UOM">
	<xsl:choose>
		<xsl:when test=".='AirDryMetricTonne'"><xsl:value-of select="$UOM-AirDryMetricTonne"/></xsl:when>
		<xsl:when test=".='AirDryPercent'"><xsl:value-of select="$UOM-AirDryPercent"/></xsl:when>
		<xsl:when test=".='AirDryShortTon'"><xsl:value-of select="$UOM-AirDryShortTon"/></xsl:when>
		<xsl:when test=".='Bale'"><xsl:value-of select="$UOM-Bale"/></xsl:when>
		<xsl:when test=".='BookUnit'"><xsl:value-of select="$UOM-BookUnit"/></xsl:when>
		<xsl:when test=".='Box'"><xsl:value-of select="$UOM-Box"/></xsl:when>
		<xsl:when test=".='C-Size'"><xsl:value-of select="$UOM-C-Size"/></xsl:when>
        <xsl:when test=".='Celsius'"><xsl:value-of select="$UOM-Celsius"/></xsl:when>
		<xsl:when test=".='Centimeter'"><xsl:value-of select="$UOM-Centimeter"/></xsl:when>
        <xsl:when test=".='CentimeterPerSecond'"><xsl:value-of select="$UOM-CentimeterPerSecond"/></xsl:when>
		<xsl:when test=".='CubicCentimeterPerSecond'"><xsl:value-of select="$UOM-CubicCentimeterPerSecond"/></xsl:when>
		<xsl:when test=".='CubicInchesPerSecond'"><xsl:value-of select="$UOM-CubicInchesPerSecond"/></xsl:when>
		<xsl:when test=".='Day'"><xsl:value-of select="$UOM-Day"/></xsl:when>
		<xsl:when test=".='Degree'"><xsl:value-of select="$UOM-Degree"/></xsl:when>
		<xsl:when test=".='DegreesSchopperRiegler'"><xsl:value-of select="$UOM-DegreesSchopperRiegler"/></xsl:when>
		<xsl:when test=".='DotsPerInch'"><xsl:value-of select="$UOM-DotsPerInch"/></xsl:when>
		<xsl:when test=".='Farenheit'"><xsl:value-of select="$UOM-Farenheit"/></xsl:when>
		<xsl:when test=".='Foot'"><xsl:value-of select="$UOM-Foot"/></xsl:when>
		<xsl:when test=".='Gram'"><xsl:value-of select="$UOM-Gram"/></xsl:when>
        <xsl:when test=".='GramCentimeter'"><xsl:value-of select="$UOM-GramCentimeter"/></xsl:when>
		<xsl:when test=".='GramForce'"><xsl:value-of select="$UOM-GramForce"/></xsl:when>
		<xsl:when test=".='GramPerCubicCentimeter'"><xsl:value-of select="$UOM-GramPerCubicCentimeter"/></xsl:when>
		<xsl:when test=".='GramPerMeter'"><xsl:value-of select="$UOM-GramPerMeter"/></xsl:when>
		<xsl:when test=".='GramsPerSquareMeter'"><xsl:value-of select="$UOM-GramsPerSquareMeter"/></xsl:when>
		<xsl:when test=".='Hour'"><xsl:value-of select="$UOM-Hour"/></xsl:when>
		<xsl:when test=".='HundredPound'"><xsl:value-of select="$UOM-HundredPound"/></xsl:when>
		<xsl:when test=".='Inch'"><xsl:value-of select="$UOM-Inch"/></xsl:when>
        <xsl:when test=".='JoulePerSquareMeter'"><xsl:value-of select="$UOM-JoulePerSquareMeter"/></xsl:when>
		<xsl:when test=".='KnownBreaks'"><xsl:value-of select="$UOM-KnownBreaks"/></xsl:when>
		<xsl:when test=".='Kilogram'"><xsl:value-of select="$UOM-Kilogram"/></xsl:when>
        <xsl:when test=".='KilogramForcePerSquareCentimeter'"><xsl:value-of select="$UOM-KilogramForcePerSquareCentimeter"/></xsl:when>
		<xsl:when test=".='KilogramForcePerCentimeter'"><xsl:value-of select="$UOM-KilogramForcePerCentimeter"/></xsl:when>
		<xsl:when test=".='KilogramPerCubicMeter'"><xsl:value-of select="$UOM-KilogramPerCubicMeter"/></xsl:when>
		<xsl:when test=".='KilogramPerSquareMeter'"><xsl:value-of select="$UOM-KilogramPerSquareMeter"/></xsl:when>
		<xsl:when test=".='KiloNewtonPerMeter'"><xsl:value-of select="$UOM-KiloNewtonPerMeter"/></xsl:when>
		<xsl:when test=".='KiloPascal'"><xsl:value-of select="$UOM-KiloPascal"/></xsl:when>
		<xsl:when test=".='Layer'"><xsl:value-of select="$UOM-Layer"/></xsl:when>
		<xsl:when test=".='Leaves'"><xsl:value-of select="$UOM-Leaves"/></xsl:when>
		<xsl:when test=".='LinesPerInch'"><xsl:value-of select="$UOM-LinesPerInch"/></xsl:when>
		<xsl:when test=".='LinearFoot'"><xsl:value-of select="$UOM-LinearFoot"/></xsl:when>
		<xsl:when test=".='Load'"><xsl:value-of select="$UOM-Load"/></xsl:when>
		<xsl:when test=".='Megabyte'"><xsl:value-of select="$UOM-Megabyte"/></xsl:when>
		<xsl:when test=".='Meter'"><xsl:value-of select="$UOM-Meter"/></xsl:when>
        <xsl:when test=".='MeterPerSecond'"><xsl:value-of select="$UOM-MeterPerSecond"/></xsl:when>
		<xsl:when test=".='MetricTon'"><xsl:value-of select="$UOM-MetricTon"/></xsl:when>
		<!--<xsl:when test=".='Microns'"><xsl:value-of select="$UOM-Microns"/></xsl:when>-->
        <xsl:when test=".='Micron'"><xsl:value-of select="$UOM-Micron"/></xsl:when>
		<xsl:when test=".='MicroMeterPerPascalSecond'"><xsl:value-of select="$UOM-MicroMeterPerPascalSecond"/></xsl:when>
		<xsl:when test=".='MilliLitersPerMinute'"><xsl:value-of select="$UOM-MilliLitersPerMinute"/></xsl:when>
		<xsl:when test=".='Millimeter'"><xsl:value-of select="$UOM-Millimeter"/></xsl:when>
        <xsl:when test=".='MilliNewton'"><xsl:value-of select="$UOM-MilliNewton"/></xsl:when>
		<xsl:when test=".='MilliNewtonMeter'"><xsl:value-of select="$UOM-MilliNewtonMeter"/></xsl:when>
		<xsl:when test=".='MilliNewtonSquareMeterPerGram'"><xsl:value-of select="$UOM-MilliNewtonSquareMeterPerGram"/></xsl:when>
		<xsl:when test=".='Minute'"><xsl:value-of select="$UOM-Minute"/></xsl:when>
		<xsl:when test=".='NanoMeter'"><xsl:value-of select="$UOM-NanoMeter"/></xsl:when>
		<xsl:when test=".='Newton'"><xsl:value-of select="$UOM-Newton"/></xsl:when>
		<xsl:when test=".='NewtonMeterPerGram'"><xsl:value-of select="$UOM-NewtonMeterPerGram"/></xsl:when>
		<xsl:when test=".='None'"><xsl:value-of select="$UOM-None"/></xsl:when>
		<xsl:when test=".='Package'"><xsl:value-of select="$UOM-Package"/></xsl:when>
		<xsl:when test=".='Page'"><xsl:value-of select="$UOM-Page"/></xsl:when>
		<xsl:when test=".='PagesPerInch'"><xsl:value-of select="$UOM-PagesPerInch"/></xsl:when>
		<xsl:when test=".='PalletUnit'"><xsl:value-of select="$UOM-PalletUnit"/></xsl:when>
        <xsl:when test=".='PartsPerMillion'"><xsl:value-of select="$UOM-PartsPerMillion"/></xsl:when>
		<xsl:when test=".='Percentage'"><xsl:value-of select="$UOM-Percentage"/></xsl:when>
		<xsl:when test=".='PerThousand'"><xsl:value-of select="$UOM-PerThousand"/></xsl:when>
        <xsl:when test=".='pH'"><xsl:value-of select="$UOM-pH"/></xsl:when>
		<xsl:when test=".='Picas'"><xsl:value-of select="$UOM-Picas"/></xsl:when>
		<xsl:when test=".='PixelsPerInch'"><xsl:value-of select="$UOM-PixelsPerInch"/></xsl:when>
		<xsl:when test=".='Pound'"><xsl:value-of select="$UOM-Pound"/></xsl:when>
        <xsl:when test=".='PoundForce'"><xsl:value-of select="$UOM-PoundForce"/></xsl:when>
		<xsl:when test=".='PoundPerCubicFoot'"><xsl:value-of select="$UOM-PoundPerCubicFoot"/></xsl:when>
		<xsl:when test=".='PoundPerSixInchDiameter'"><xsl:value-of select="$UOM-PoundPerSixInchDiameter"/></xsl:when>
		<xsl:when test=".='PoundPerSquareInch'"><xsl:value-of select="$UOM-PoundPerSquareInch"/></xsl:when>
		<xsl:when test=".='PulpUnit'"><xsl:value-of select="$UOM-PulpUnit"/></xsl:when>
		<xsl:when test=".='Ream'"><xsl:value-of select="$UOM-Ream"/></xsl:when>
		<xsl:when test=".='Reel'"><xsl:value-of select="$UOM-Reel"/></xsl:when>
		<xsl:when test=".='Set'"><xsl:value-of select="$UOM-Set"/></xsl:when>
		<xsl:when test=".='Second'"><xsl:value-of select="$UOM-Second"/></xsl:when>
		<xsl:when test=".='Sheet'"><xsl:value-of select="$UOM-Sheet"/></xsl:when>
		<xsl:when test=".='ShortTon'"><xsl:value-of select="$UOM-ShortTon"/></xsl:when>
		<xsl:when test=".='Signature'"><xsl:value-of select="$UOM-Signature"/></xsl:when>
		<xsl:when test=".='SquareInch'"><xsl:value-of select="$UOM-SquareInch"/></xsl:when>
		<xsl:when test=".='Skid'"><xsl:value-of select="$UOM-Skid"/></xsl:when>
		<xsl:when test=".='SquareFeet'"><xsl:value-of select="$UOM-SquareFeet"/></xsl:when>
		<xsl:when test=".='SquareMeter'"><xsl:value-of select="$UOM-SquareMeter"/></xsl:when> 
        <xsl:when test=".='SquareMeterPerKilogram'"><xsl:value-of select="$UOM-SquareMeterPerKilogram"/></xsl:when>
		<xsl:when test=".='Ton'"><xsl:value-of select="$UOM-Ton"/></xsl:when>
		<xsl:when test=".='Unit'"><xsl:value-of select="$UOM-Unit"/></xsl:when>
		<xsl:when test=".='UnknownBreaks'"><xsl:value-of select="$UOM-UnknownBreaks"/></xsl:when>
		<xsl:when test=".='Yard'"><xsl:value-of select="$UOM-Yard"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@UOM"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute UsageReferenceType     *** -->

<xsl:template match="@UsageReferenceType">
	<xsl:text>[</xsl:text>
		<xsl:choose>
			<xsl:when test=".='ContractLineNumber'"><xsl:value-of select="$UsageReferenceType-ContractLineNumber"/></xsl:when>
			<xsl:when test=".='ContractNumber'"><xsl:value-of select="$UsageReferenceType-ContractNumber"/></xsl:when>
			<xsl:when test=".='CustomerReferenceNumber'"><xsl:value-of select="$UsageReferenceType-CustomerReferenceNumber"/></xsl:when>
			<xsl:when test=".='DeliveryBookingNumber'"><xsl:value-of select="$UsageReferenceType-DeliveryBookingNumber"/></xsl:when>
			<xsl:when test=".='FormType'"><xsl:value-of select="$UsageReferenceType-FormType"/></xsl:when>
			<xsl:when test=".='IndentOrderNumber'"><xsl:value-of select="$UsageReferenceType-IndentOrderNumber"/></xsl:when>
			<xsl:when test=".='IntraStatNumber'"><xsl:value-of select="$UsageReferenceType-IntraStatNumber"/></xsl:when>
			<xsl:when test=".='ISODocumentReference'"><xsl:value-of select="$UsageReferenceType-ISODocumentReference"/></xsl:when>
			<xsl:when test=".='IssueEvent'"><xsl:value-of select="$UsageReferenceType-IssueEvent"/></xsl:when>
			<xsl:when test=".='JobNumber'"><xsl:value-of select="$UsageReferenceType-JobNumber"/></xsl:when>
			<xsl:when test=".='MillOrderLineItemNumber'"><xsl:value-of select="$UsageReferenceType-MillOrderLineItemNumber"/></xsl:when>
			<xsl:when test=".='MillOrderNumber'"><xsl:value-of select="$UsageReferenceType-MillOrderNumber"/></xsl:when>
			<xsl:when test=".='OriginalInvoiceNumber'"><xsl:value-of select="$UsageReferenceType-OriginalInvoiceNumber"/></xsl:when>
			<xsl:when test=".='OriginalUsageNumber'"><xsl:value-of select="$UsageReferenceType-OriginalUsageNumber"/></xsl:when>
			<xsl:when test=".='PubName'"><xsl:value-of select="$UsageReferenceType-PubName"/></xsl:when>
			<xsl:when test=".='PubNumber'"><xsl:value-of select="$UsageReferenceType-PubNumber"/></xsl:when>
			<xsl:when test=".='PurchaseOrderNumber'"><xsl:value-of select="$UsageReferenceType-PurchaseOrderNumber"/></xsl:when>
			<xsl:when test=".='StockOrderNumber'"><xsl:value-of select="$UsageReferenceType-StockOrderNumber"/></xsl:when>
			<xsl:when test=".='SupplierCallOffNumber'"><xsl:value-of select="$UsageReferenceType-SupplierCallOffNumber"/></xsl:when>
			<xsl:when test=".='SupplierReferenceNumber'"><xsl:value-of select="$UsageReferenceType-SupplierReferenceNumber"/></xsl:when>
			<xsl:when test=".='Other'"><xsl:value-of select="$UsageReferenceType-Other"/></xsl:when>		
			<xsl:otherwise>
				<b style="color:red">-<xsl:value-of select="@UsageReferenceType"/>-</b>
			</xsl:otherwise>
		</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute UsageStatus     *** -->

<xsl:template match="@UsageStatus">
	<xsl:if test="string-length(.) !='0'">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2">
	<xsl:choose>
		<xsl:when test=".='Consumed'"><xsl:value-of select="$UsageStatus-Consumed"/></xsl:when>
		<xsl:when test=".='MachineReject'"><xsl:value-of select="$UsageStatus-MachineReject"/></xsl:when>
		<xsl:when test=".='StrippedButt'"><xsl:value-of select="$UsageStatus-StrippedButt"/></xsl:when>
		<xsl:when test=".='PartialSkid'"><xsl:value-of select="$UsageStatus-PartialSkid"/></xsl:when>
		<xsl:when test=".='UseableButt'"><xsl:value-of select="$UsageStatus-UseableButt"/></xsl:when>
		<xsl:when test=".='UnusableButt'"><xsl:value-of select="$UsageStatus-UnusableButt"/></xsl:when>		
		<xsl:when test=".='UsablePallet'"><xsl:value-of select="$UsageStatus-UsablePallet"/></xsl:when>	
		<xsl:when test=".='UnusablePallet'"><xsl:value-of select="$UsageStatus-UnusablePallet"/></xsl:when>	
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@UsageStatus"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
			</td>
		</tr>
	</table>
	</xsl:if>
</xsl:template>

<!-- ***     Attribute VarianceType     *** -->

<xsl:template match="@VarianceType">
	<xsl:choose>
		<xsl:when test=".='NotReceived'"><xsl:value-of select="$VarianceType-NotReceived"/></xsl:when>
		<xsl:when test=".='ReceivedNotSpecified'"><xsl:value-of select="$VarianceType-ReceivedNotSpecified"/></xsl:when>
		<xsl:when test=".='DifferingWeights'"><xsl:value-of select="$VarianceType-DifferingWeights"/></xsl:when>
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@VarianceType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<!-- ***     Attribute WasteType     *** -->

<xsl:template match="@WasteType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle6" valign="top">
				<xsl:text>[</xsl:text>
					<xsl:choose>
						<xsl:when test=".='Binding'"><xsl:value-of select="$WasteType-Binding"/></xsl:when>
						<xsl:when test=".='Core'"><xsl:value-of select="$WasteType-Core"/></xsl:when>
						<xsl:when test=".='MakeReady'"><xsl:value-of select="$WasteType-MakeReady"/></xsl:when>
						<xsl:when test=".='Running'"><xsl:value-of select="$WasteType-Running"/></xsl:when>
						<xsl:when test=".='Strip'"><xsl:value-of select="$WasteType-Strip"/></xsl:when>
						<xsl:when test=".='Wrapper'"><xsl:value-of select="$WasteType-Wrapper"/></xsl:when>		
						<xsl:otherwise>
							<b style="color:red">-<xsl:value-of select="@WasteType"/>-</b>
						</xsl:otherwise>
					</xsl:choose>
				<xsl:text>]</xsl:text>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute WebBreakType     *** -->

<xsl:template match="@WebBreakType">
	<xsl:text>[</xsl:text>
	<xsl:choose>
		<xsl:when test=".='MachineCaused'"><xsl:value-of select="$WebBreakType-MachineCaused"/></xsl:when>
		<xsl:when test=".='PaperCaused'"><xsl:value-of select="$WebBreakType-PaperCaused"/></xsl:when>
		<xsl:when test=".='Unknown'"><xsl:value-of select="$WebBreakType-Unknown"/></xsl:when>
		<!--<xsl:when test=".='Paper'"><xsl:value-of select="$WebBreakType-Paper"/></xsl:when>
		<xsl:when test=".='Printer'"><xsl:value-of select="$WebBreakType-Printer"/></xsl:when>
		<xsl:when test=".='Other'"><xsl:value-of select="$WebBreakType-Other"/></xsl:when>-->		
		<xsl:otherwise>
			<b style="color:red">-<xsl:value-of select="@WebBreakType"/>-</b>
		</xsl:otherwise>
	</xsl:choose>
	<xsl:text>]</xsl:text>
</xsl:template>

<!-- ***     Attribute WindingDirectionType     *** -->
<!--
<xsl:template match="@WindingDirectionType">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='WireSideIn'"><xsl:value-of select="$WindingDirectionType-WireSideIn"/></xsl:when>
					<xsl:when test=".='WireSideOut'"><xsl:value-of select="$WindingDirectionType-WireSideOut"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@WindingDirectionType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>
-->
<!-- ***     Attribute WrapProperties     *** -->
 
<xsl:template match="@WrapProperties">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>
					<xsl:when test=".='Bleached'"><xsl:value-of select="$WrapProperties-Bleached"/></xsl:when>
					<xsl:when test=".='Unbleached'"><xsl:value-of select="$WrapProperties-Unbleached"/></xsl:when>
					<xsl:when test=".='MoistureBarrier'"><xsl:value-of select="$WrapProperties-MoistureBarrier"/></xsl:when>
					<xsl:when test=".='Pulpable'"><xsl:value-of select="$WrapProperties-Pulpable"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@WrapProperties"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<!-- ***     Attribute WrapType     *** -->

<xsl:template match="@WrapType">
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="LineItemStyle1" width="125" valign="top">
				<xsl:value-of select="$map[@key=name(current())]"/>
			</td>
			<td class="LineItemStyle2" valign="top">
				<xsl:choose>		
					<xsl:when test=".='KraftPaper'"><xsl:value-of select="$WrapType-KraftPaper"/></xsl:when>
					<xsl:when test=".='None'"><xsl:value-of select="$WrapType-None"/></xsl:when>
					<xsl:when test=".='PlasticBag'"><xsl:value-of select="$WrapType-PlasticBag"/></xsl:when>
					<xsl:when test=".='Self'"><xsl:value-of select="$WrapType-Self"/></xsl:when>
					<xsl:when test=".='ShrinkWrap'"><xsl:value-of select="$WrapType-ShrinkWrap"/></xsl:when>
					<xsl:when test=".='StretchWrap'"><xsl:value-of select="$WrapType-StretchWrap"/></xsl:when>
					<xsl:otherwise>
						<b style="color:red">-<xsl:value-of select="@WrapType"/>-</b>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</table>
</xsl:template>

<xsl:template match="@TestMethod">
	<xsl:if test="string-length(.) !='0'">
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
	</xsl:if>
</xsl:template>

</xsl:stylesheet><!-- Stylus Studio meta-information - (c)1998-2002 eXcelon Corp.
<metaInformation>
<scenarios ><scenario default="no" name="po" userelativepaths="yes" externalpreview="no" url="PurchaseOrder_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="no" name="inv" userelativepaths="yes" externalpreview="no" url="Invoice_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/><scenario default="yes" name="comp" userelativepaths="yes" externalpreview="no" url="Complaint_full_V2R10.xml" htmlbaseurl="" processortype="internal" commandline="" additionalpath="" additionalclasspath="" postprocessortype="none" postprocesscommandline="" postprocessadditionalpath="" postprocessgeneratedext=""/></scenarios><MapperInfo srcSchemaPath="" srcSchemaRoot="" srcSchemaPathIsRelative="yes" srcSchemaInterpretAsXML="no" destSchemaPath="" destSchemaRoot="" destSchemaPathIsRelative="yes" destSchemaInterpretAsXML="no"/>
</metaInformation>
-->