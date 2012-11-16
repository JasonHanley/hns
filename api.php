<?php

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

require_once('config.php');
require_once('custom.php');

$pdo = myDB::init();

$action = '';
if(isset($_GET['action']))
    $action = $_GET['action'];

if($action == 'trk')
{
    $json = $_POST['data']; echo $json;
    $datas = json_decode($json, true);
    foreach($datas as $data)
    {
        $url = $data['url'];
        $fromId = $data['fromId'];
        $fromName = $data['fromName'];
        $urlGuid = uniqid('');
        $sourceGuid = uniqid('');
        
        $results = myDB::doQuery('select * from sources where fbid=?', array($fromId));
        if(!count($results))
        {
            // Insert new source
            myDB::doInsert('insert into sources values (?,?,?)', array($sourceGuid, $fromName, $fromId));
        }
        elseif(count($results) == 1)
        {
            // Get existing source GUID
            $result = $results[0];
            $sourceGuid = $result['guid'];
        }
        
        $results = myDB::doQuery('select * from urls where url=?', array($url));
        if(!count($results))
        {
            // Insert new URL
            myDB::doInsert('insert into urls values (?,?)', array($urlGuid, $url));
        }
        elseif(count($results) == 1)
        {
            $result = $results[0];
            $urlGuid = $result['guid'];
        }
        
        // Check for duplicates
        $results = myDB::doQuery('select * from url_source where url=? and source=?', array($urlGuid, $sourceGuid));
        if(!count($results))
        {
            myDB::doInsert('insert into url_source values (?,?)', array($urlGuid, $sourceGuid));
        }
    }
}
