<?php
/*
 |™*******************************************************************************************™*
 | ADOdb 5.03
 |™*******************************************************************************************™*
*/
// keep session in database (use before session_start)
ADOdb_Session::config(DB_DRIVER, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $options);
adodb_sess_open(false, false, $connectMode = false);