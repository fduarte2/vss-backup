<?

  if ($HTTP_POST_VARS['pallet'] <>""){
        $index = $HTTP_POST_VARS['pallet'];
        $arrPId = $HTTP_POST_VARS['pallet_id'];
        $arrVes = $HTTP_POST_VARS['vessel'];
        $arrBOL = $HTTP_POST_VARS['bol'];
		$arrProd = $HTTP_POST_VARS['prod'];
		$arrArrival_num = $HTTP_POST_VARS['arrival_num'];
		$arrExporter = $HTTP_POST_VARS['exporter'];
		$arrVoyage = $HTTP_POST_VARS['voyage'];
  }
  $season = $HTTP_POST_VARS['season'];
  

?>
<script language="javascript">
   opener.document.detail.pallet_id.value = "<?= $arrPId[$index]?>";
   opener.document.detail.season.value = "<?= $season?>";
   opener.document.detail.arrival_num.value = "<?= $arrArrival_num[$index]?>";
   opener.document.detail.vessel.value = "<?= $arrVes[$index]?>";
   opener.document.detail.bol.value = "<?= $arrBOL[$index]?>";
   opener.document.detail.product.value = "<?= $arrProd[$index]?>";
   opener.document.detail.exporter.value = "<?= $arrExporter[$index]?>";
   opener.document.detail.voyage.value = "<?= $arrVoyage[$index]?>";
   opener.document.detail.action="";
   opener.document.detail.submit();
   self.close();
</script>
