<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
</head>

<body>

<?php

			/* First we need to get the protocol the website is using */
        	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https://' ? 'https://' : 'http://';
		
		echo $protocol;	
        	/* returns /myproject/index.php */
        	$path = $_SERVER['PHP_SELF'];
		
		echo $path;
			
        	
        	$path_parts = pathinfo($path);
		echo $path_parts;
        	$directory = $path_parts['dirname'];
		echo $directory;
        	/*
        	 * If we are visiting a page off the base URL, the dirname would just be a "/",
        	 * If it is, we would want to remove this
        	 */
        	$directory = ($directory == "/") ? "" : $directory;
		echo $directory;	
        	/* Returns localhost OR mysite.com */
        	$host = $_SERVER['HTTP_HOST'];
		echo $host;	
?>

</body>
</html>
