<div style="text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000">
<div>
    REF. <?php echo $this->article->article_code; ?>
</div>
<div>
    <?php echo $this->article->name; ?>
</div>
<div>
    <?php echo $this->article->description_lang_1; ?><br />
    <?php echo $this->article->description_lang_2; ?><br />
    <?php echo $this->article->description_lang_3; ?><br />
    <?php echo $this->article->description_lang_4; ?><br />
</div>
<div>
    N.PACKS / INNER	<?php echo $this->article->package_units; ?>
</div>
<div>
	<img width="200" src="<?php echo DMUrl::getCurrentBaseUrl(); ?>temp/print/<?php echo $this->article->article_code . '_' . $this->batchOut->batch_out_code; ?>.png"></img><br />
    <?php echo $this->barcode; ?>
</div>
</div>