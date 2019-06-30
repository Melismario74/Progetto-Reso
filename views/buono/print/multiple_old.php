<html>
	<head>
		<style>
		
			@page { margin: 0px; }
			
			body {
				font-family: "Helvetica";
				font-weight: bold;
				font-size: 9pt;
				margin: 0px;
			}
			
			td {
				text-align: center;
				padding-bottom: 70px;
				padding-right: 10px;
			}
			
			.articleName {
				border-bottom: 2px solid #000;
				padding-top: 15px;
			}
			
			.articleUnits {
				border-top: 2px solid #000;
			}
			
			.articleDescription {
				text-overflow: clip;
			}
			
		</style>
	</head>
	<body>
		<?php $totalCount = count($this->printArray); $i = 0; ?>
		<?php foreach ($this->printArray as $printData) { ?>
		<?php $i++; ?>
		<table width="100%" style="<?php if ($totalCount > $i) echo 'page-break-after: always'; ?>">
			<tr>
				<td>
					<div>
						REF. <?php echo $printData->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $printData->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $printData->article->description_lang_1; ?><br />
						<?php echo $printData->article->description_lang_2; ?><br />
						<?php echo $printData->article->description_lang_3; ?><br />
						<?php echo $printData->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER<br />
						<?php echo $printData->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $printData->article->article_code . '_' . $printData->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $printData->barcode; ?>
					</div>
				</td>
				<td>
					<div>
						REF. <?php echo $printData->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $printData->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $printData->article->description_lang_1; ?><br />
						<?php echo $printData->article->description_lang_2; ?><br />
						<?php echo $printData->article->description_lang_3; ?><br />
						<?php echo $printData->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER<br />
						<?php echo $printData->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $printData->article->article_code . '_' . $printData->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $printData->barcode; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						REF. <?php echo $printData->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $printData->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $printData->article->description_lang_1; ?><br />
						<?php echo $printData->article->description_lang_2; ?><br />
						<?php echo $printData->article->description_lang_3; ?><br />
						<?php echo $printData->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER<br />
						<?php echo $printData->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $printData->article->article_code . '_' . $printData->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $printData->barcode; ?>
					</div>
				</td>
				<td>
					<div>
						REF. <?php echo $printData->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $printData->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $printData->article->description_lang_1; ?><br />
						<?php echo $printData->article->description_lang_2; ?><br />
						<?php echo $printData->article->description_lang_3; ?><br />
						<?php echo $printData->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER<br />
						<?php echo $printData->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $printData->article->article_code . '_' . $printData->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $printData->barcode; ?>
					</div>
				</td>
			</tr>
		</table>
		<?php } ?>
	</body>
</html>