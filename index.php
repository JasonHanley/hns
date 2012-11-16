<?php
require_once('config.php');
require_once('custom.php');

$pdo = myDB::init();

$page = 'news';

if(isset($_GET['page']))
    $page = $_GET['page'];
    
require_once('layout.php');