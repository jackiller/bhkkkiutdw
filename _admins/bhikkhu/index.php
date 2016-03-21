<?php

require ('../../xcrud/xcrud.php');
require ('html/pagedata.php');
session_start();

$theme = 'bootstrap';
Xcrud_config::$theme = 'bootstrap';
$title_2 = 'Default theme';

$page = (isset($_GET['page']) && isset($pagedata[$_GET['page']])) ? $_GET['page'] : 'default';
extract($pagedata[$page]);

$file = dirname(__file__) . '/pages/' . $filename;
$code = file_get_contents($file);
include ('html/template.php');
