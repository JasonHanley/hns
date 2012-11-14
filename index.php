<?php
$page = 'news';

if(isset($_GET['page']))
    $page = $_GET['page'];
    
require_once('layout.php');
