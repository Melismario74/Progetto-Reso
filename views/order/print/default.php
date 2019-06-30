<?php $printData = $this; ?>

<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style>
		
			@page { margin: 0px; }
			
			body {
				font-family: "Helvetica";
				font-size: 11pt;
				margin-top: 330px;
				margin-bottom: 320px;
				margin-left: 20px;
				margin-right: 20px;
			}			
			
			.header {
			  	position: fixed;
			  	top: 0px;
			  	left: 0px;
			  	right: 0px;
			  	height: 330px;
				margin-left: 20px;
				margin-right: 20px;
			}
		
			.footer {
			  	position: fixed;
			  	bottom: 0px;
			  	left: 0px;
			  	right: 0px;
			  	height: 320px;
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
			
			table td {
				position: relative;
			}
			
			table td div.label {
				position: absolute;
				top: 0px;
				left: 0px;
				font-style: italic;
				font-size: 8pt;
			}
			
			table td div.value {
				margin-top: 15px;
				margin-bottom: 5px;
				padding: 5px;
			}
			
			table td.value {
				padding-left: 5px;
				padding-right: 5px;
				padding-top: 3px;
				padding-bottom: 3px;
			}
			
		</style>
	</head>
	<body>
	
		<script type="text/php">

        if ( isset($pdf) ) {

          $font = Font_Metrics::get_font("helvetica", "bold");
          $pdf->page_text(510, 235, "Pagina {PAGE_NUM} di {PAGE_COUNT}", $font, 9, array(0,0,0));

        }
        </script>
        	
		<div class="header">
			<table width="100%">
				<tr>
					<td style="text-align: center" valign="middle">
						<div style="margin-top: 20px; font-size: 16pt;">
							Distinta di prelievo magazzino reso lavorato
						</div>
						<div style="margin-top: 10px;">
							Numero: <b><?php echo $this->order->order_code_str; ?></b>
						</div>
						<div style="margin-top: 10px;">
							Data: <b><?php echo DMFormat::formatDate($this->order->order_date, 'd/m/Y', 'Y-m-d'); ?></b>
						</div>
					</td>
				</tr>
			</table>
			
			<table width="100%" class="bordered" style="margin-top: 30px;">
				<tr>
					<td colspan="2">
						<div class="label">Ufficio richiedente</div>
						<div class="value"><?php echo nl2br($this->order->client_name); ?>&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="label">Causale</div>
						<div class="value"><?php echo nl2br($this->order->subject); ?>&nbsp;</div>
					</td>
				</tr>
			</table>	
		</div>
		
		<div class="footer">
			<table width="100%" class="bordered">
				<tr>
					<td colspan="2">
						<div class="label">Note</div>
						<div class="value"><?php echo nl2br($this->order->notes); ?>&nbsp;</div>
					</td>
				</tr>
			</table>
		</div>
		
		<div class="content">
		
			<table width="100%" class="bordered">
				<thead>
					<tr>
						<th>Codice</th>
						<th>Descrizione</th>
						<th style="text-align: right">Conf.</th>
						<th>Udm</th>
						<th>Ubicazione</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->order->rows as $row) { ?>
					<tr>
						<td class="value" style="text-align: center">
							<?php echo $row->article_code; ?>
						</td>
						<td class="value">
							<?php echo $row->description; ?>
						</td>
						<td class="value" style="text-align: right">
							<?php echo $row->quantity_units; ?>
						</td>
						<td class="value" style="text-align: center">
							<?php echo ($row->udm_code + $row->udm_code_old); ?>
						</td>
						<td class="value" style="text-align: center">
							<?php echo $row->ubicazione; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		
		</div>
		
	</body>
</html>