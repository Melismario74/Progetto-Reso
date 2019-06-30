<?php $printData = $this; ?>

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
			
		</style>
	</head>
	<body>
		<table width="100%">
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
	</body>
</html>