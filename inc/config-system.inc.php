<?php
/*
 |™*******************************************************************************************™*
 | Website Configuration
 |™*******************************************************************************************™*
*/
/*
 |----------------------------------------------------------------------------------------------
 | Error Report
 |----------------------------------------------------------------------------------------------
*/
	// recommend set to disable (0) for develop & production
	ini_set("register_globals", 0);

	// recommend set to comment for production
	// ini_set("error_reporting", E_ALL ^ E_NOTICE); // for PHP 5.3.x
	ini_set("error_reporting", E_ALL &  ~E_NOTICE & ~E_DEPRECATED);

	// recommend set to disable (0) for production
	ini_set("display_errors", 1);

	// recommend set to disable (0) for production
	ini_set("log_errors", 1);

/*
 |™*******************************************************************************************™*
*/