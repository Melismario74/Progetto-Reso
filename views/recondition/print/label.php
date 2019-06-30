<table width="100%" style="border-bottom: 1px solid #000; padding-bottom: 5px; margin-top: 5px;">
	<tr>
		<td style="text-align: center">
			REF. <?php echo $printData->article->article_code; ?>
		</td>
	</tr>
	<tr>		
		<td style="text-align: center">
			<?php echo $printData->article->name; ?>
		</td>
	</tr>
	<tr>	
		<td style="text-align: center">
			<?php echo $printData->article->description_lang_1; ?><br />
    		<?php echo $printData->article->description_lang_2; ?><br />
    		<?php echo $printData->article->description_lang_3; ?><br />
    		<?php echo $printData->article->description_lang_4; ?>
		</td>
	</tr>
	<tr>	
		<td style="text-align: center">
			N.PACKS / INNER <?php echo $printData->article->package_units; ?>
		</td>
	</tr>
	<tr>
		<td style="text-align: center">
			<img width="300" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $printData->article->article_code . '_' . $printData->batchOut->batch_out_code; ?>.png"></img><br />
			<?php echo $printData->barcode; ?>
		</td>
	</tr>
</table>
