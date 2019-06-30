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
					<td width="50%">
						<img src="<?php echo DM_APP_PATH . DS . 'media' . DS . 'bbs.png'; ?>" width="350px" />
					</td>
					<td style="text-align: center" valign="middle">
						<div style="margin-top: 20px; font-size: 16pt;">
							Documento di Trasporto
						</div>
						<div style="margin-top: 10px;">
							Numero: <b><?php echo $this->invoice->invoice_code_str; ?></b>
						</div>
						<div style="margin-top: 10px;">
							Data: <b><?php echo DMFormat::formatDate($this->invoice->invoice_date, 'd/m/Y', 'Y-m-d'); ?></b>
						</div>
					</td>
				</tr>
			</table>
			
			<table width="100%" class="bordered" style="margin-top: 30px;">
				<tr>
					<td width="50%">
						<div class="label">Cliente</div>
						<div class="value">
							<b><?php echo $this->invoice->client_name; ?></b><br />
							<?php echo $this->invoice->client_address; ?><br />
							<?php echo $this->invoice->client_postalcode; ?> - <?php echo $this->invoice->client_city; ?><br />
							<?php echo $this->invoice->client_state; ?>
						</div>
					</td>
					<td width="50%">
						<div class="label">Destinazione merce (se diversa)</div>
						<div class="value">&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="label">Causale</div>
						<div class="value"><?php echo nl2br($this->invoice->subject); ?>&nbsp;</div>
					</td>
				</tr>
			</table>	
		</div>
		
		<div class="footer">
			<table width="100%" class="bordered">
				<tr>
					<td>
						<div class="label">Trasporto a mezzo</div>
						<div class="value"><?php echo nl2br($this->invoice->transport_vector); ?>&nbsp;</div>
					</td>
					<td>
						<div class="label">Aspetto merce</div>
						<div class="value"><?php echo nl2br($this->invoice->goods_aspect); ?>&nbsp;</div>
					</td>
					<td colspan="2">
						<div class="label">Note</div>
						<div class="value"><?php echo nl2br($this->invoice->notes); ?>&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div class="label">Vettore</div>
						<div class="value">&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="label">Colli</div>
						<div class="value"><?php echo nl2br($this->invoice->packages); ?>&nbsp;</div>
					</td>
					<td>
						<div class="label">Data e ora trasporto</div>
						<div class="value"><?php echo nl2br($this->invoice->transport_date); ?>&nbsp;</div>
					</td>
					<td colspan="2">
						<div class="label">Firma conducente</div>
						<div class="value">&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="label">Luogo partenza merce (se diverso)</div>
						<div class="value"><?php echo nl2br($this->invoice->transport_source); ?>&nbsp;</div>
					</td>
					<td colspan="2">
						<div class="label">Firma vettore/destinatario</div>
						<div class="value">&nbsp;</div>
					</td>
				</tr>
			</table>
			
			<table width="100%" style="font-size: 9pt; text-align: center; margin-top: 20px;">
				<tr>
					<td>
						<b>BBS Spa</b><br />
						Sede legale: Via Viazza, 3043 - 41018 San Cesario S/P (MO)

<br />
						P. Iva: 00935120360 - N. Rea: MO-195942 del 18/01/1980 - Capitale Sociale: 1.000.000,00 €
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
						<th style="text-align: right">Cart.</th>
						<th>Magaz.</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->invoice->rows as $row) { ?>
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
						<td class="value" style="text-align: right">
							<?php echo $row->quantity_packages; ?>
						</td>
						<td class="value" style="text-align: center">
							<?php echo $row->stock_name; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		
		</div>
		
	</body>
</html>