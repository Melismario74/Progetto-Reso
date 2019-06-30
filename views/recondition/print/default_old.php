<html>
	<head>
		<style>
		
			@page { margin: 0px; }
			
			body {
				font-family: "Helvetica";
				font-weight: bold;
				font-size: 8pt;
				margin: 0px;
			}
			
			td {
				text-align: center;
				padding-bottom: 25px;
				padding-right: 10px;
			}
			
			.articleName {
				border-bottom: 2px solid #000;
				padding-top: 5px;
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
		<table width="100%">
			<tr>
				<td>
					<div>
						REF. <?php echo $this->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $this->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $this->article->description_lang_1; ?><br />
						<?php echo $this->article->description_lang_2; ?><br />
						<?php echo $this->article->description_lang_3; ?><br />
						<?php echo $this->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER	<?php echo $this->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="200" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $this->article->article_code . '_' . $this->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $this->barcode; ?>
					</div>
				</td>
				<td>
					<div>
						REF. <?php echo $this->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $this->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $this->article->description_lang_1; ?><br />
						<?php echo $this->article->description_lang_2; ?><br />
						<?php echo $this->article->description_lang_3; ?><br />
						<?php echo $this->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER	<?php echo $this->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="200" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $this->article->article_code . '_' . $this->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $this->barcode; ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						REF. <?php echo $this->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $this->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $this->article->description_lang_1; ?><br />
						<?php echo $this->article->description_lang_2; ?><br />
						<?php echo $this->article->description_lang_3; ?><br />
						<?php echo $this->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER	<?php echo $this->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $this->article->article_code . '_' . $this->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $this->barcode; ?>
					</div>
				</td>
				<td>
					<div>
						REF. <?php echo $this->article->article_code; ?>
					</div>
					<div class="articleName">
						<?php echo $this->article->name; ?>
					</div>
					<div class="articleDescription">
						<?php echo $this->article->description_lang_1; ?><br />
						<?php echo $this->article->description_lang_2; ?><br />
						<?php echo $this->article->description_lang_3; ?><br />
						<?php echo $this->article->description_lang_4; ?><br />
					</div>
					<div class="articleUnits">
						N.PACKS / INNER <?php echo $this->article->package_units; ?>
					</div>
					<div class="barcode">
						<img width="170" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $this->article->article_code . '_' . $this->batchOut->batch_out_code; ?>.png"></img><br />
						<?php //echo $this->barcode; ?>
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>