<?php

	define("DM_APP_PATH", dirname(__FILE__));

	date_default_timezone_set("Europe/Berlin");

	ob_start();
	
	
	
	//Inizializzo DM Platform
	require_once("dmplatform" . DIRECTORY_SEPARATOR . "import.php");
	
	//Carico le config
	require_once('config.php');
	
	//Carico gli helper
	require_once('helpers' . DS . 'fhhelper.php');
	
	//Imposto il metodo di sessione
	DMSession::setHandler('none');
	
	$document =& DMDocument::getInstance();
	$document->setBase(DMUrl::getCurrentBaseUrl());
	$document->setTemplate('felsinea');
	
	//Supporto tabelle
	DMBase::loadModule('Table');
	
	//Inizializzo la lingua
	$langCode = DMInput::getFilename('lang', 'it');
	DMLang::init($langCode);
	DMLang::importLanguage($langCode, DM_APP_PATH . DS . 'languages', 'it');
	
	//Carico gli script system-wide
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/cascadingdropdown/jquery.cascadingdropdown.js');
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/validate/jquery.validate.js');
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/imageZoom/jquery.imageZoom.js');
	$document->addCSS(DMUrl::getCurrentBaseUrl() . 'scripts/imageZoom/jquery.imageZoom.css');
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/inputreturn/inputreturn.js');
	if ($langCode != 'en') {
		$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/validate/languages/messages_' . $langCode . '.js');
	}
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'scripts/fh.js');
	
	//Carico gli script che mi servono dalla platform
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'dmplatform/scripts/util.js');
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'dmplatform/scripts/response.js');
	$document->addJS(DMUrl::getCurrentBaseUrl() . 'dmplatform/scripts/format.js');
	
	//Login obbligatorio
	DMBase::loadModule('User');
	DMUser::init();
	
	//ACL
	DMBase::loadModule('Acl');
	DMAcl::init();
	DMAcl::loadPrivileges();
	
	//Supporto popup
	DMBase::loadModule('Popup');
	DMPopup::init();
	
	//Supporto stampa
	DMBase::loadModule('Print');
	DMPrint::init();

	//Supporto logs
	DMBase::loadModule('Log');
	
	//Se non sono loggato ridireziono opportunamente al login (tranne in alcune eccezioni)
	
	
	if (
		(DMInput::getString('controller') != 'system') &&
		(!DMUser::getUser())
	) {
		$currentController = DMInput::getString('controller');
	
		if ($currentController != 'login') {
		DMInput::set('task', 'display');
		}
	
	
		DMInput::set('controller', 'login');
		DMInput::set('view', 'login');
	}
	
	DMRoute::routeRequest();
	
	$document->render();
	

?>