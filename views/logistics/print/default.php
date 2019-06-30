<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>

		@page { margin: 0px; }

		body {
			font-family: "Helvetica";
			font-size: 11pt;
			margin-left: 20px;
			margin-right: 20px;
		}

		table {
			padding: 0px;
			margin: 0px;
			border-spacing: 0;
			border-collapse: collapse;
		}

		table.bordered td {
			border: 1px solid #000;
		}

	</style>
</head>
<body>
<br />
<div style="border-bottom: 100px;">
<table width="100%">
	<tr>
		<td style="font-size: 14pt; text-align: center;">
			UDM: <?php echo $this->udm->udm_code; ?>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<img width="250" height="80" src="<?php echo DM_APP_PATH; ?>/temp/print/<?php echo $this->udm->udm_code; ?>.png" />
		</td>
	</tr>
</table>
</div>
<br />
<br />
<table width="100%" style="border-bottom: 5px;">
	<tr>
		<td style="text-align: center; font-size: 14pt;">Articoli in UDM</td>
	</tr>
</table>
<br />
<table width="100%" class="table bordered">
	<tr>
		<td>Codice</td>
		<td>Descrizione</td>
		<td>Quantità</td>
	</tr>
	<?php foreach ($this->udm->articles as $article): ?>
	<tr style="font-size: 10pt;">
		<td>
			<?php echo $article->article_code; ?>
		</td>
		<td>
			<?php echo $article->name; ?>
		</td>
		<td>
			<?php echo $article->quantity_units; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</body>
</html>