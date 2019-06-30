<html>
	<head>
		<style>
		
			body {
				font-family: "Helvetica";
				font-weight: bold;
				font-size: 9pt;
			}
			
		</style>
	</head>
	<body>
		<table width="100%">
			<thead>
				<tr>
					<th width="75px;" style="text-align: center">Codice</th>
					<th>Nome</th>
					<th width="75px;" style="text-align: right">Conf. OK</th>
					<th width="75px;" style="text-align: right">Cart. OK</th>
					<th width="75px;" style="text-align: right">Stock</th>
					<th width="75px;" style="text-align: right">Scarto</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->articles as $article) { ?>
				<tr>
					<td style="text-align: center">
						<?php echo $article->article_code; ?>
					</td>
					<td>
						<?php echo $article->name; ?>
					</td>
					<td style="text-align: right">
						<?php echo $article->stock[1]->total_units; ?>
					</td>
					<td style="text-align: right">
						<?php echo $article->stock[1]->total_packages; ?>
					</td>
					<td style="text-align: right">
						<?php echo $article->stock[3]->total_units; ?>
					</td>
					<td style="text-align: right">
						<?php echo $article->stock[2]->total_units; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</body>
</html>