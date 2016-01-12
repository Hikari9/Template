<?php
	define('ROOT', $_SERVER['DOCUMENT_ROOT']);
	define('FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
	ini_set('include_path', dirname(__FILE__));

	require_once 'functions.php';
	include 'head.php';
?>