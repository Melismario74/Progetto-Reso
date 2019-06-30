<?php
	
	/**
		Inizializzazione della DM Platform
		
		
	*/
	
	define('_DMEXEC', 1);
	
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "constants.php");
	
	//Inizializzo JPlatform
	require_once(DM_PLATFORM_PATH . DS . 'libraries' . DS . 'jplatform' . DS . 'libraries' . DS . 'import.php');
	
	//Inizializzo Base
	require_once(DM_PLATFORM_PATH . DS . 'base.php');
	
	//Inizializzo Exception
	require_once(DM_PLATFORM_PATH . DS . 'exception.php');
	
	//Inizializzo Error
	require_once(DM_PLATFORM_PATH . DS . 'error.php');
	
	//Inizializzo Input
	require_once(DM_PLATFORM_PATH . DS . 'input.php');
	
	//Inizializzo Format
	require_once(DM_PLATFORM_PATH . DS . 'format.php');
	
	//Inizializzo Database
	require_once(DM_PLATFORM_PATH . DS . 'database.php');
	
	//Inizializzo Controller
	require_once(DM_PLATFORM_PATH . DS . 'controller.php');
	
	//Inizializzo View
	require_once(DM_PLATFORM_PATH . DS . 'view.php');
	
	//Inizializzo Document
	require_once(DM_PLATFORM_PATH . DS . 'document.php');
	
	//Inizializzo URL
	require_once(DM_PLATFORM_PATH . DS . 'url.php');
	
	//Inizializzo Session
	require_once(DM_PLATFORM_PATH . DS . 'session.php');
	
	//Inizializzo Lang
	require_once(DM_PLATFORM_PATH . DS . 'lang.php');
	
	//Inizializzo Route
	require_once(DM_PLATFORM_PATH . DS . 'route.php');
	
?>