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
			
		</style>
	</head>
	<body>
		<?php $totalCount = count($this->printArray); $i = 0; ?>
		<?php foreach ($this->printArray as $printData) { ?>
		<?php $i++; ?>
		<table width="100%" style="<?php if ($totalCount > $i) echo 'page-break-after: always'; ?>">
			<tr>
				<td>
					<?php require(DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print' . DS . 'label.php'); ?>
				</td>
				<td>
					<?php require(DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print' . DS . 'label.php'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php require(DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print' . DS . 'label.php'); ?>
				</td>
				<td>
					<?php require(DM_APP_PATH . DS . 'views' . DS . 'recondition' . DS . 'print' . DS . 'label.php'); ?>
				</td>
			</tr>
		</table>
		<?php } ?>
	</body>
</html>